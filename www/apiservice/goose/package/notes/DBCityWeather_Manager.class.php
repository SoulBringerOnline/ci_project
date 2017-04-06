<?php
	/**
	 * 天气查询
	 */
	namespace Goose\Package\Notes;

	use \Libs\Mongo\MongoDB;

	class DBCityWeather_Manager {
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

		public function selectWeather($where) {
			$result = $this->mongo_ol->city_weather->findOne($where, array("f_prj_weathers"));
			if(!$result){
				return false;
			}
			$day = date("Y-m-d");
			foreach ($result['f_prj_weathers'] as $val) {
				if($val['createDate'] == $day){
					return $val['weather'];
				}
			}

			return false;
		}
	}