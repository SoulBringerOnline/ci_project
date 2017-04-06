<?php
	/**
	 * 百度地图配置
	 * Date: 2015/11/20
	 */

	namespace Goose\Config\Webapi;

	class BMap extends \Goose\Libs\Singleton{
		public function configs() {
			return array (
				'host' => 'http://api.map.baidu.com',
				'ip_weather_api' => '/location/ip',
				'ak' => 'CBb647957161c31cf0bdc7c86f39233b'
			);
		}
	}