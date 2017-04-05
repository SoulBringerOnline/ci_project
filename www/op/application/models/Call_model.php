<?php
	class Call_Model extends CI_Model {
		public function __construct() {
			//$this->connection_ol = new MongoClient($this->config->item('mongodb_gsk_ol'));
			$this->connection_ol = new MongoClient( $this->config->item('mongodb_spider') );
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');
		}

		function __destruct() {
			$this->connection_ol->close();
		}

		function flow_list_by_time($start, $end, $type=null) {
			$where = array('f_create_time'=>array('$gt'=>intval($start), '$lte'=>intval($end)));
			if(isset($type)){
				$where['f_type'] = $type;
			}
			$data = iterator_to_array($this->mongo_ol->userFreeTimeLog->find($where));
			$return = array();
			$return['start'] = floor($start/1000);
			$return['end'] =  floor($end/1000);
			$return['list'] = array();
			$return['totalMoney'] = 0;
			$return['totalTime'] = 0;
			foreach ($data as $key=>$val) {
				$date = date("Ymd", floor($val['f_create_time']/1000));
				if(isset($return['list'][$date])){
					//$return[$date][] = $val;
					$return['list'][$date]['count'] = $return['list'][$date]['count'] + 1;
					$return['list'][$date]['duration'] = $return['list'][$date]['duration'] + $val['f_free_time_point'];
					if($this->is_connect($val['f_call_id'])){
						$return['list'][$date]['money'] = $return['list'][$date]['money'] + $val['f_free_time_point']*$val['f_price'];
					}else{
						$return['list'][$date]['money'] = $return['list'][$date]['money'] + $val['f_free_time_point']*($val['f_price']/2);
					}
					if($val['f_status'] == 0){
						$return['list'][$date]['error'] = $return['list'][$date]['error'] + 1;
					}
				} else {
					//$return[$date][] = $val;
					$return['list'][$date]['count'] = 1;
					$return['list'][$date]['duration'] = $val['f_free_time_point'];
					if($this->is_connect($val['f_call_id'])){
						$return['list'][$date]['money'] = $val['f_free_time_point']*$val['f_price'];
					}else{
						$return['list'][$date]['money'] = $val['f_free_time_point']*($val['f_price']/2);
					}
					if($val['f_status'] == 0){
						$return['list'][$date]['error'] = 1;
					} else {
						$return['list'][$date]['error'] = 0;
					}
				}
				$return['totalMoney'] += $val['f_free_time_point']*$val['f_price'];
				$return['totalTime'] += $val['f_free_time_point'];
			}
			ksort($return['list']);
			return $return;
		}

		function is_connect($callsid) {
			$where = array('f_call_id'=>$callsid);
			$result = $this->mongo_ol->userCallFlowLog->findOne($where, array('f_is_connect'));
			if($result['f_is_connect'] == 1){
				return true;
			}else{
				return false;
			}
		}

		function free_time_count($start, $end, $type=null) {
			$where = array('f_create_time'=>array('$gt'=>intval($start), '$lte'=>intval($end)));
			if(isset($type)){
				$where['f_type'] = $type;
			}
			$where['f_status'] = 1;
			$data = iterator_to_array($this->mongo_ol->userFreeTimeLog->find($where));
			$totalTime = 0;
			$totalUser = 0;
			foreach ($data as $value) {
				$totalUser = $totalUser + 1;
				$totalTime = $totalTime + $value['f_free_time_point'];
			}

			return array('totalTime'=>$totalTime, 'totalUser'=>$totalUser);
		}

		function call_list($where, $page=0, $size=18) {
			$total = $this->mongo_ol->userCallFlowLog->find($where)->count();
			$list = iterator_to_array($this->mongo_ol->userCallFlowLog->find($where)->limit($size)->skip(($page-1)*$size)->sort(array( "f_create_time"=>-1)));
			$return = array();
			$return['total'] = $total;
			$return['page'] = $page;
			$return['size'] = $size;
			$return['list'] = $list;

			return $return;
		}

		function point_to_free_list($where, $page=0, $size=18) {
			$total = $this->mongo_ol->pointToFreeTimeLog->find($where)->count();
			$list = iterator_to_array($this->mongo_ol->pointToFreeTimeLog->find($where)->limit($size)->skip(($page-1)*$size)->sort(array( "f_create_time"=>-1)));
			$return = array();
			$return['total'] = $total;
			$return['page'] = $page;
			$return['size'] = $size;
			$return['list'] = $list;

			return $return;
		}

		function add_combo($param) {
			$this->mongo_ol->callComboSetting->insert($param);
			return true;
		}

		public function get_recent_combo($dateid) {
			$where = array(
				'f_date_id' => array(
					'$lte' => new MongoInt32($dateid)
				)
			);
			$order = array('f_date_id'=>-1);
			$data = array_values(iterator_to_array($this->mongo_ol->callComboSetting->find($where)->limit(1)->skip(0)->sort($order)));

			return $data[0];
		}

		public function get_combo_by_dateid($dateid) {
			$result = $this->mongo_ol->callComboSetting->findOne(array('f_date_id'=>new MongoInt32($dateid)));
			if($result){
				return $result;
			}

			return false;
		}

		public function update_combo($where, $data){
			$this->mongo_ol->callComboSetting->update($where, array(
				'$set' => $data
			));

			return true;
		}

		public function get_user_fields($where, $fields) {
			$result = $this->mongo_ol->user->findOne($where, $fields);
			if($result){
				return $result;
			}

			return false;
		}
		public function get_user_call_time($uin) {
			$call = $this->mongo_ol->projectUserFreeTellTime->findOne(array('f_uin'=>intval($uin)), array('f_free_time_num'));
			return $call['f_free_time_num'];
		}

		public function update_user_call_time($uin, $data) {
			$ret = $this->mongo_ol->projectUserFreeTellTime->update(array('f_uin'=>$uin), array('$set'=>$data));
			if($ret){
				return true;
			}

			return false;
		}

		public function update_user_free_time_log($callSid, $data) {
			$ret = $this->mongo_ol->userFreeTimeLog->update(array('f_call_id'=>$callSid), array('$set'=>$data));
			if($ret){
				return true;
			}

			return false;
		}

		public function update_user_call_flow_log($callSid, $data) {
			$ret = $this->mongo_ol->userCallFlowLog->update(array('f_call_id'=>$callSid), array('$set'=>$data));
			if($ret){
				return true;
			}

			return false;
		}
	}