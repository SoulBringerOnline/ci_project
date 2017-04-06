<?php
	namespace Goose\Package\Call;

	use \Libs\Mongo\MongoDB;

	class DBUserFreeTimeLog_Manager {
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
			$result = $this->mongo_ol->userFreeTimeLog->insert($data);
			if($result){
				return true;
			}

			return false;
		}

		public function get_uin($where) {
			$result = $this->mongo_ol->userFreeTimeLog->findOne($where, array("f_uin"));
			if($result){
				return $result[0]['f_uin'];
			}

			return false;
		}

		public function get_fields($where, $fields) {
			$result = $this->mongo_ol->userFreeTimeLog->findOne($where, $fields);
			if($result){
				return $result;
			}

			return false;
		}

		public function get_recent_call_sid($where) {
			$result = array_values(iterator_to_array($this->mongo_ol->userFreeTimeLog->find($where, array("f_call_id"))->limit(1)->skip(0)->sort(array('f_create_time'=>-1))));
			if($result){
				return $result[0]['f_call_id'];
			}

			return false;
		}

		public function update($callSid, $data) {
			$ret = $this->mongo_ol->userFreeTimeLog->update(array('f_call_id'=>$callSid), array('$set'=>$data));
			if($ret){
				return true;
			}

			return false;
		}
	}