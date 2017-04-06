<?php
	namespace Goose\Package\Call;

	use \Libs\Mongo\MongoDB;

	class DBProjectUserFreeTellTime_Manager {
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
			$result = $this->mongo_ol->projectUserFreeTellTime->insert($data);
			if($result){
				return true;
			}

			return false;
		}

		public function get_call_time($uin) {
			$call = $this->mongo_ol->projectUserFreeTellTime->findOne(array('f_uin'=>intval($uin)), array('f_free_time_num'));
			return $call['f_free_time_num'];
		}

		public function get_call_price($uin) {
			$call = $this->mongo_ol->projectUserFreeTellTime->findOne(array('f_uin'=>$uin), array('f_price'));

			return $call['f_price'];
		}

		public function update_call_time($uin, $data) {
			$ret = $this->mongo_ol->projectUserFreeTellTime->update(array('f_uin'=>$uin), array('$set'=>$data));
			if($ret){
				return true;
			}

			return false;
		}
	}