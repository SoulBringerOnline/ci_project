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
	}