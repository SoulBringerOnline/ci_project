<?php
	class Prize_Model extends CI_Model {
		public function __construct() {
			//$this->connection_ol = new MongoClient($this->config->item('mongodb_gsk_ol'));
			$this->connection_ol = new MongoClient( $this->config->item('mongodb_spider') );
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');
		}

		function __destruct() {
			$this->connection_ol->close();
		}

		// 添加奖品
		public function addCard($param) {
			$this->mongo_ol->gamePrizePool->insert($param);
			return true;
		}

		public function updatePrize($where, $data) {
			$res = $this->mongo_ol->gamePrize->update($where, $data);
			return true;
		}

		public function  updatepPrize($filter=array())//保存更新
		{
			$where = array();
			$data = array();
			if(array_key_exists("prizeId",$filter)){
				$where["prizeId"] = $filter['prizeId'];
			}
			$data = $this->get_manage($where);
			$new = array();
			foreach($data as &$item)
			{
				$change = $filter['f_num'] - $item['f_num'];

			}
			$new['f_num'] = new MongoInt32($filter['f_num']);
			$new['f_origin_total'] =  new MongoInt32($item['f_origin_total']+$change);
			//$new['f_prize_name'] = $filter['f_prize_name'];
			$new['f_prize_desc'] = $filter['f_prize_desc'];
			//$new['f_prize_img'] = $filter['f_prize_img'];
			//print_r($new);

			$this->mongo_ol->gamePrize->update(array('f_prize_id'=>$filter['prizeId']), array('$set'=>$new));
			return true;
		}
		public function checkCard($where) {
			$res = $this->mongo_ol->gamePrizePool->findOne($where);
			if($res){
				return true;
			}
			
			return false;
	    }
		public function  get_all_prize($filter = array(),$is_export_excel = false)//领取记录
		{
			$p = $f = $list = array();
			$f['order_fd']   = 'f_create_time';
			$f['order_type'] = -1;
			$rows_page = 20;
			$p['cur_page'] = isset($filter['page']) ? intval($filter	['page']) : 1;
			$p['rows_page']  = $rows_page;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];
			$where = array( );
			if (!empty($filter['f_uin'])){
				$where['f_uin'] = intval($filter['f_uin']);
			}
			if(!empty($filter['f_name']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_name'=>$filter['f_name']), array('f_uin'));
				$where['f_uin'] =  intval($name['f_uin']);
			}
			if(!empty($filter['f_phone']))
			{
				//$where['f_phone'] = $filter['f_phone'];
				$uin = $this->mongo_ol->user->findOne(array('f_phone'=>$filter['f_phone']),array('f_uin'));
				$where['f_uin'] = intval($uin['f_uin']);
			}
			if(!is_null($filter['start_time']) ||  !is_null($filter['end_time']) )
			{
				$start_timet=is_null($filter['start_time'])?0:strtotime($filter['start_time'])*1000;
				$end_timet = empty($filter['end_time'])?1579132800000:strtotime($filter['end_time'])*1000;
				$where['f_create_time'] = array('$gte' => $start_timet,'$lte'=>$end_timet);
			}
			/*if(!empty($filter['end_time']) )
			{
				$where['f_create_time'] = array('$lte' => strtotime($filter['end_time'])*1000);
			}*/
			if(isset($filter['prize_type']))
			{
				if($filter['prize_type']!= 14){
					$where['f_prize_code'] = $filter['prize_type'];
				}

			}
			if($is_export_excel)
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->newYearGameLog->find($where)->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}
			else
			{
				$data = iterator_to_array( $this->mongo_ol->newYearGameLog->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
				$total = $this->mongo_ol->newYearGameLog->find($where)->count();
				//$total = sizeof($data);

			}
			$p['max_count'] = $total;
			/*$p['max'] = array(
				'max_count'  =>$total
			);*/
			foreach($data as &$item){
				if(empty($item["f_uin"]) or $item["f_uin"]== -1){
					$item["f_uin"] = -1;
					$item["f_name"] = "";
					$item["f_phone"] = "";

				}else{
					$intro = $this->mongo_ol->user->findOne(array('f_uin'=>$item["f_uin"]), array('f_name','f_phone'));
					if(!empty($intro)){
						//$item["f_uin"] = -1;
						$item["f_name"] = $intro["f_name"];
						$item["f_phone"] = $intro["f_phone"];
					}else{
						$item["f_name"] = "";
						$item["f_phone"] = "";
					}

				}
			}
			foreach($data as &$item1){
				if($item1["f_type"] == 0){
					$item1["f_reason"] = "app内抽奖（春节活动)";
				}elseif($item1["f_type"] == 1){
					$item1["f_reason"] = "app外部抽奖后注册（春节活动）";
				}else{
					$item1["f_reason"] = "app外抽奖";
				}
			}
			foreach($data as &$item2){
				switch ($item2['f_prize_code']){
					case 1:
						$item2['f_integral'] = 1400;
						break;
					case 2:
						$item2['f_integral'] = 100;
						break;
					case 3:
						$item2['f_integral'] = 400;
						break;
					case 4:
						$item2['f_integral'] = 600;
						break;
					case 5 or 12:
						$item2['f_integral'] = 1000;
						break;
					case 6 or 13:
						$item2['f_integral'] = 1400;
						break;
					case 7:
						$item2['f_integral'] = 2000;
						break;
					default:
						$item2['f_integral'] = 0;
						break;
				}
			}
			$list['data'] = $data;
			$list['page'] = $p;
			return $list;

		}
	public function get_all_detail($filter = array())//查询获奖明细记录
	{
		$where = array();
		$p = array();
		$rows = 20;
		$p['cur_page'] = isset($filter['page']) ? intval($filter	['page']) : 1;
		$p['rows_page']  = $rows;
		$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];
		if(!empty($filter['prize_type']))
		{
			if($filter['prize_type']!=7){
				$where['f_prize_id'] = $filter['prize_type'];
			}

		}
		if(!is_null($filter['start_time']) ||  !is_null($filter['end_time']) )
		{
			$start_timet=is_null($filter['start_time'])?0:strtotime($filter['start_time'])*1000;
			$end_timet = empty($filter['end_time'])?1579132800000:strtotime($filter['end_time'])*1000;
			$where['f_create_time'] = array('$gte' => $start_timet,'$lte'=>$end_timet);
		}
		/*if(!empty($filter['start_time']))
		{
			$where['f_create_time'] =  array('$gte' => strtotime($filter['start_time']));
		}
		if(!empty($filter['end_time']))
		{
			$where['f_create_time'] =  array('$lte' => strtotime($filter['end_time']));
		}*/

		if (!empty($filter['f_uin']))
		{
			$where['f_uin'] = intval($filter['f_uin']);
			//$where['f_uin'] = 103084;

		}
		if(!empty($filter['f_name']))
		{
			$name = $this->mongo_ol->user->findOne(array('f_name'=>$filter['f_name']), array('f_uin'));
			$where['f_uin'] =  intval($name['f_uin']);
			//$where['f_uin'] = 103084;
		}
		if(!empty($filter['f_phone']))
		{

			$uin = $this->mongo_ol->user->findOne(array('f_phone'=>$filter['f_phone']),array('f_uin'));
			$where['f_uin'] = intval($uin['f_uin']);
			//$where['f_uin'] = 103084;
		}

		$data = iterator_to_array( $this->mongo_ol->gamePrizePool->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array(  "f_create_time"=>-1 ) ) );
		$total = $this->mongo_ol->gamePrizePool->find($where)->count();
		$p['max_count'] = $total;
		foreach($data as &$item)
		{
			$f_uin = $item['f_uin'];
			//$f_uin = 103084;
			$information = $this->mongo_ol->user->findOne(array("f_uin"=>$f_uin),array('f_name','f_phone'));
			$item['f_uin'] = $f_uin;
			$item['f_name'] = $information['f_name'];
			$item['f_phone'] = $information['f_phone'];
		}
		foreach($data as &$item1)
		{
			switch ($item1['f_prize_id'])
			{

				case 8:
					$item1['f_prize_name'] = "50元京东卡";
					break;
				case 9:
					$item1['f_prize_name'] = "100元京东卡";
					break;
				case 10:
					$item1['f_prize_name'] = "300元京东卡";
					break;

			}
		}
		$list['data'] = $data;
		$list['page'] = $p;
		return $list;
	}
	public  function get_manage($filter=array())//查询奖品类别
	{
		$where = array();
		if(array_key_exists("prizeId",$filter))
		{
			$where["f_prize_id"] = $filter["prizeId"];
		}
		$data= iterator_to_array($this->mongo_ol->gamePrize->find($where)->skip(0)->sort(array("_id"=>1)));
		return $data;
	}

	}