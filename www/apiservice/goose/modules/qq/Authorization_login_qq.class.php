<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/17
 * Time: 19:19
 */

namespace Goose\Modules\QQ;
use \Libs\Mongo\MongoDB;
use \Libs\Redis\Redis;

class Authorization_login_qq extends \Goose\Libs\Wmodule{
	public function run()
	{
		if (empty($_COOKIE["unionid"]))
		{
			$data = array(
				'is_setcookie'=>false,
			);

			$str = new \MongoId();
			setcookie('unionid', $str, time() + 86400*30,"/", ".glodon.com");//key在redis中30天过期
			Redis::setex($str."_unionid", 86400*30, md5($str));//key在redis中30天过期

			$this->response->make_json_ok('', array('authorization'=>$data));
		}
		else
		{
			$data = array(
				'is_setcookie'=>true,
			);

			$this->response->make_json_ok('', array('authorization'=>$data));
		}
	}
} 