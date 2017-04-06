<?php
	/**
	 * 搜索
	 */
	namespace Goose\Package\Notes;

	use \Libs\Mongo\MongoDB;

	class Search {
		private static $intance = null;
		private $mongo_ol = null;
		private $config;

		private function __construct() {
			$this->mongo_ol = MongoDB::getMongoDB("online", "gsk_ol");
			$this->config = \Frame\ConfigFilter::instance()->getConfig('search');
		}

		public static function instance() {
			if (self::$intance === null) {
				self::$intance = new self();
			}

			return self::$intance;
		}

		public function search($uid, $keywords, $type=0, $page=1, $size=10) {
			$columns = array(
				'uid' => $uid,
				'keywords' => $keywords,
				'page' => $page,
				'size' => $size,
			    'type' => $type
			);
			//搜索
			$searchUrl = $this->config['searchUrl'];
			foreach ($columns as $key=>$val) {
				$searchUrl = preg_replace("/\{$key\}/", $val, $searchUrl);
			}
			$data = json_decode(self::curl_get($searchUrl), true);
			if(! $data['success']){
				return false;
			}
			//var_dump($data);exit;
			//无结果
			if(! $data['data']['notesList']){
				$return = array();
				$return['total'] = 0;
				$return['page'] = $page;
				$return['size'] = $size;
				$return['list'] = array();

				return $return;
			}
			$total = $data['data']['count'];
			$list = $data['data']['notesList'];
			$noteIds = array();
			$search = array();
			foreach ($list as $val) {
				$noteIds[] = $val['notesId'];
				$search[$val['notesId']] = $val['searchContent'];
			}
			//查询数据
			$where = array('f_notes_id'=>array('$in'=>$noteIds));
			$result = iterator_to_array($this->mongo_ol->notes->find($where, array('f_notes_id', 'f_images', 'f_weather', 'f_type', 'f_content', 'f_last_time', 'f_create_time'))->sort(array('f_create_time'=>-1)));
			if(! $result){
				return false;
			}
			$list = array();
			foreach($result as $key=>$val){
				if($val['f_type'] == 1){
					//日志
					$_search = trim($search[$val['f_notes_id']], ' "[{}]');
					//获取关键词
					preg_match_all("/<font color='#ff5722'>(.*?)<\/font>/", $_search, $matches);
					$keywords = array_unique($matches[1]);
					$content = $val['f_content'];
					$mark = 0;
					$content_len = count($content);
					for ($i=0; $i<$content_len; $i++) {
						$start = mb_strpos($content[$i]['content'], $keywords[0], 0, 'UTF-8');
						if($start !== false && $mark==0){
							$mark = $i;
						}
						if(mb_strlen($content[$i]['content'], "UTF-8") <= 30){
							if(isset($start)){
								foreach ($keywords as $word) {
									$content[$i]['content'] = str_replace($word, "<font color='#ff5722'>".$word."</font>", $content[$i]['content']);
								}
							}else{
								$content[$i]['content'] = mb_substr($content[$i]['content'], 0, 30, 'UTF-8');
							}
						} else {
							if(isset($start)){
								$length = mb_strlen($content[$i]['content'], 'UTF-8');
								if(($length - 30)<0){
									$start = 0;
								}
								if(($length-30)<$start){
									$start = $length-30;
								}
								$content[$i]['content'] = mb_substr($content[$i]['content'], $start, 30, 'UTF-8');
								foreach ($keywords as $word) {
									$content[$i]['content'] = str_replace($word, "<font color='#ff5722'>".$word."</font>", $content[$i]['content']);
								}
							}else{
								$content[$i]['content'] = mb_substr($content[$i]['content'], 0, 30, 'UTF-8');
							}
						}
					}
					if($content_len>3 && $mark>=3){
						$r_start = ($content_len-$mark)<3? ($content_len-3): ($content_len-$mark);
					}else{
						$r_start = 0;
					}
					$_content = array($content[$r_start], $content[$r_start+1], $content[$r_start+2]);
					$list[$key]['search'] = $_content;
					$list[$key]['date'] = $val['f_last_time'];
				} else {
					$list[$key]['search'] = $search[$val['f_notes_id']];
					$list[$key]['date'] = $val['f_create_time'];
				}
				$list[$key]['content'] = $val['f_content'];
				$list[$key]['weather'] = $val['f_weather'];
				$list[$key]['images'] = isset($val['f_images'])? $val['f_images']: array();
				$list[$key]['voice'] = isset($val['f_voice'])? $val['f_voice']: array();
				$list[$key]['type'] = $val['f_type'];
			}

			$return = array();
			$return['total'] = $total;
			$return['page'] = $page;
			$return['size'] = $size;
			$return['list'] = array_values($list);

			return $return;
		}

		public function create($notesId) {
			$createUrl = str_replace("{id}", $notesId, $this->config['createUrl']);

			$result = self::curl_get($createUrl);
			if($result['code'] == 1){
				return true;
			}

			return false;
		}

		public function update($notesId) {
			$updateUrl = str_replace("{id}", $notesId, $this->config['updateUrl']);

			$result = self::curl_get($updateUrl);
			if($result['code'] == 1){
				return true;
			}

			return false;
		}

		public function delete($notesId) {
			$deleteUrl = str_replace("{id}", $notesId, $this->config['deleteUrl']);

			$result = self::curl_get($deleteUrl);
			if($result['code'] == 1){
				return true;
			}

			return false;
		}

		//发送http get方式请求
		public static function curl_get($url)
		{
			if(empty($url)) exit;
			//初始化
			$ch = curl_init();
			//设置选项，包括URL
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			//执行并获取HTML文档内容
			$output = curl_exec($ch);
			//释放curl句柄
			curl_close($ch);

			return $output;
		}
	}