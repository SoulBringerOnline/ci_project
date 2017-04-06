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
			array('host'=>'lg-mqmrrors.api.zy.glodon.com' , 'port'=> '5672', 'user'=>'admin' , 'passwd'=> 'www.glodon.c0m', 'vhost'=>"/"),
		);
	}
}