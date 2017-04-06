<?php
namespace Goose\Config\Webapi;

class Mongo extends \Goose\Libs\Singleton {
	public function configs() {
		return array(
			'servers' => array(
				"online"=>"mongodb://192.168.165.240:27017",
				"op"=>"mongodb://192.168.165.240:27017",
				"test"=>"mongodb://192.168.165.240:27017",
				"test_ol"=>"mongodb://10.128.6.61:20000",
			),
		);
	}
}