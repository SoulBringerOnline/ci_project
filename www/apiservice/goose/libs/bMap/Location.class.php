<?php
	namespace Goose\Libs\Bmap;

	use \Goose\Libs\Util\Util;

	class Location {
		public $host, $api, $ak;

		public function __construct() {
			$config = \Frame\ConfigFilter::instance()->getConfig('BMap');
			$this->host = $config['host'];
			$this->api = $config['ip_weather_api'];
			$this->ak = $config['ak'];
		}

		static function ip2location($ip) {
			$that = new self();
			$url = $that->host . $that->api . "?ak=" . $that->ak . "&ip=" . $ip;
			$data = json_decode(Util::send_request_get($url), true);
			if(isset($data['address_detail'])){
				return array(
					'city' => $data['address_detail']['city'],
					'province' => $data['address_detail']['province'],
					'point' => $data['point']
				);
			}else{
				return false;
			}
		}
	}