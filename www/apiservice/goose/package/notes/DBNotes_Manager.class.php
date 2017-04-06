<?php
	/**
	 * 便签
	 */
	namespace Goose\Package\Notes;

	use \Libs\Mongo\MongoDB;

	class DBNotes_Manager {
		private static $intance = null;
		private $mongo_ol = null;

		private function __construct() {
			$this->mongo_ol = MongoDB::getMongoDB("online", "gsk_ol");
		}

		public static function instance() {
			if (self::$intance === null) {
				self::$intance = new self();
			}

			return self::$intance;
		}

		public function create($data) {
			$result = $this->mongo_ol->notes->insert($data);
			if($result['ok'] == 1){
				return true;
			}

			return false;
		}

		public function delete($where) {
			$result = $this->mongo_ol->notes->remove($where);
			if($result['ok'] == 1){
				return true;
			}

			return false;
		}

		public function update($where, $data) {
			$result = $this->mongo_ol->notes->update($where, array('$set' => $data));
			if($result['ok'] == 1){
				return true;
			}

			return false;
		}

		public function get($where) {
			$result = $this->mongo_ol->notes->findOne($where);
			if(! $result){
				return false;
			}

			return $result;
		}

		public function pages($where, $page=1, $size=20) {
			$skip = ($page-1)*$size;
			$total = $this->mongo_ol->notes->find($where)->count();
			$data = iterator_to_array($this->mongo_ol->notes->find($where, array('f_uin', 'f_notes_id', 'f_weather', 'f_type', 'f_content', 'f_voice', 'f_images', 'f_last_time', 'f_create_time'))->limit($size)->skip($skip)->sort(array('f_create_time'=>-1)));
			$list = array();
			foreach (array_values($data) as $key=>$val) {
				$list[$key]['uin'] = $val['f_uin'];
				$list[$key]['notes_id'] = $val['f_notes_id'];
				$list[$key]['weather'] = $val['f_weather'];
				$list[$key]['type'] = $val['f_type'];
				$list[$key]['content'] = $val['f_content'];
				$list[$key]['images'] = isset($val['f_images'])? $val['f_images']: array();
				$list[$key]['voice'] = isset($val['f_voice'])? $val['f_voice']: array();
				/*$list[$key]['images_count'] = $val['f_images_count'];
				$list[$key]['create_time'] = $val['f_create_time'];
				$list[$key]['last_time'] = $val['f_last_time'];*/
				if($val['f_type'] == 2){
					$list[$key]['date'] = $val['f_create_time'];
				} else {
					$list[$key]['date'] = $val['f_last_time'];
				}
			}

			$ret = array(
				'total'=>$total,
				'page'=>$page,
				'size'=>$size,
				'data' => $list
			);

			return $ret;
		}
	}