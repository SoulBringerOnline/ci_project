<?php
	/**
	 * 便签模板
	 */
	namespace Goose\Package\Notes;

	use \Libs\Mongo\MongoDB;

	class DBNotesTemplate_Manager {
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
			$result = $this->mongo_ol->notesTemplate->insert($data);
			if ($result['ok'] == 1) {
				return true;
			}

			return false;
		}

		public function delete($where) {
			$result = $this->mongo_ol->notesTemplate->remove($where);
			if ($result['ok'] == 1) {
				return true;
			}

			return false;
		}

		public function update($where, $data) {
			$result = $this->mongo_ol->notesTemplate->update($where, array('$set' => $data));
			if($result['ok'] == 1){
				return true;
			}

			return false;
		}

		public function listTemplate($uid) {
			$data = $this->mongo_ol->notesTemplate->findOne(array('f_uin'=>$uid), array('f_template'));
			if(! $data){
				$columns = array();
				$columns['f_uin'] = intval($uid);
				$columns['f_template'] = array('今日安排', '明日计划', '备注');
				$columns['f_create_time'] = time();
				$columns['f_last_time'] = time();
				if(!$this->create($columns)){
					return false;
				}

				return $columns['f_template'];
			}

			return $data['f_template'];
		}
	}