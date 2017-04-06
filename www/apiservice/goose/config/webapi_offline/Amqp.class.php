<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/11/9
 * Time: 20:59
 */

namespace Goose\Config\Webapi;

class Amqp extends \Goose\Libs\Singleton {
	public function configs() {
		return array(
			array('host'=>'192.168.164.199' , 'port'=> '5672', 'user'=>'admin' , 'passwd'=> 'www.admin', 'vhost'=>"/"),
		);
	}
}