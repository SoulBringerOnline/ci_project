<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/15
 * Time: 10:53
 */
namespace Goose\Modules\Weixin;

use \Goose\Libs\Util\Util;
use \Libs\Mongo\MongoDB;
use \Libs\Redis\Redis;
use \Goose\Package\Weixin\Helper\Errorcode_Model_Weixin;

class Weixin_login_callback extends \Goose\Libs\Wmodule{

	public function run()
	{
		$code = $_REQUEST['code'];
		$jump_url = $_REQUEST['jump_url'];
		$appid = "wx755a176a07f52614";
		$secret = "385bb22c122ec25ae18b15a54c0de6f5";

		$url ="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";

		$res = json_decode(Util::send_request_get($url), true);
		$openid = $res['openid'];
		$unionid = $res['unionid'];
		if(!empty($unionid))
		{
			$str = new \MongoId();
			setcookie('unionid', $str, time() + 86400*30,"/", ".glodon.com");
			Redis::setex($str."_openid", 86400*30, $openid);//key在redis中20天过期
			Redis::setex($str."_unionid", 86400*30, $unionid);//key在redis中20天过期
			header("location: $jump_url");
		}
		else
		{
			$this->response->make_json_response(intval(Errorcode_Model_Weixin::ERROR_EMPTY_OPENID), "获取openid失败");
		}

	}
} 