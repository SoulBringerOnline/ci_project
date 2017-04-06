<?php
	/**
	 * 便签分享
	 */
	namespace Goose\Package\Notes;

	use \Libs\Mongo\MongoDB;

	class DBNotesShare_Manager {
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
			$result = $this->mongo_ol->notesShare->insert($data);

			if ($result['ok'] == 1) {
				return true;
			}

			return false;
		}

		public function get($where) {
			$result = $this->mongo_ol->notesShare->findOne($where);
			if(! $result){
				return false;
			}
			$ret = array();
			$ret['share_id'] = $result['f_share_id'];
			$ret['uin'] = $result['f_uin'];
			$ret['share_uin'] = $result['f_share_uin'];
			$ret['share_type'] = $result['f_share_type'];
			$ret['type'] = $result['f_type'];
			$ret['content'] = $result['f_content'];
			$ret['images'] = $result['f_images'];
			$ret['voice'] = $result['f_voice'];
			$ret['images'] = $result['f_images'];
			$ret['create_time'] = $result['f_create_time'];
			$ret['last_time'] = $result['f_last_time'];
			$ret['share_time'] = $result['f_share_time'];

			return $ret;
		}
	}