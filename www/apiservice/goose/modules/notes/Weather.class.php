<?php
	namespace Goose\Modules\Notes;

	use \Goose\Libs\Bmap\Location;
	use \Goose\Package\Notes\DBCityWeather_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class Weather extends \Goose\Libs\Wmodule {
		public function run() {
			$ip = $this->getIP();
			$data = Location::ip2location($ip);
			if(!$data){
				$this->response->make_json_ok("", array('weather'=>"未知", 'date'=>time()));return;
			}
			$where = array(
				'f_city' => rtrim($data['city'], "市")
			);
			$weather = DBCityWeather_Manager::instance()->selectWeather($where);
			if(!$weather){
				$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_FIND_WEATHER));return;
			}

			$this->response->make_json_ok("", array('weather'=>$weather, 'date'=>time()));return;
		}

		function getIP(){
			if(!empty($_SERVER["HTTP_CLIENT_IP"])){
				$cip = $_SERVER["HTTP_CLIENT_IP"];
			}elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}elseif(!empty($_SERVER["REMOTE_ADDR"])){
				$cip = $_SERVER["REMOTE_ADDR"];
			}else {
				$cip = "127.0.0.1";
			}

			return $cip;
		}
	}