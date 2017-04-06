<?php
	/**
	 * 通话记录
	 */
	namespace Goose\Package\Call;

	use \Libs\Mongo\MongoDB;

	class DBUserCallFlowLog_Manager {
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

		public function add($data) {
			$result = $this->mongo_ol->userCallFlowLog->insert($data);
			if($result){
				return true;
			}

			return false;
		}

		public function update($callSid, $data) {
			$ret = $this->mongo_ol->userCallFlowLog->update(array('f_call_id'=>$callSid), array('$set'=>$data));
			if($ret){
				return true;
			}

			return false;
		}

		public function flowList($uin, $page=1, $size=20) {
			$where = array(
				'$or'=>array(
					array('f_uin'=>$uin),
					array('f_to_uin'=>$uin)
				)
			);
			$skip = ($page-1)*$size;
			$total = $this->mongo_ol->userCallFlowLog->find($where)->count();
			$data = iterator_to_array($this->mongo_ol->userCallFlowLog->find($where)->limit($size)->skip($skip)->sort(array('f_create_time'=>-1)));
			$return = array();
			$return['total'] = $total;
			$return['page'] = $page;
			$return['size'] = $size;
			$return['list'] = $data;

			return $return;
		}

		public function callDetail($from, $to) {
			$data = iterator_to_array($this->mongo_ol->userCallFlowLog->find(array('$or'=>array(array('f_uin'=>$from, 'f_to_uin'=>$to), array('f_uin'=>$to,'f_to_uin'=>$from))))->sort(array('f_create_time'=>-1)));
			return $data;
		}
	}