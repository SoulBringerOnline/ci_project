<?php
/**
 * 微信授权、获取微信用户openid
 * User: lixd-a
 * Date: 2015/12/15
 * Time: 10:27
 */
namespace Goose\Modules\Weixin;

use \Goose\Libs\Util\Util;

class Get_user_authorization extends \Goose\Libs\Wmodule {

	public function run()
	{
//		$this->app->log->log('Weixin', $_COOKIE);
		if (empty($_COOKIE["unionid"]))
		{
			$jump_url = $_REQUEST['jump_url'];
			$appid = "wx755a176a07f52614";
			$weixin_callback_url = urlencode(API_URL."2.0/weixin/weixin_login_callback?jump_url=$jump_url");
			$code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$weixin_callback_url&response_type=code&scope=snsapi_base";

			$data = array(
				'is_setcookie'=>false,
				'url'=>$code_url
			);

			$this->response->make_json_ok('', array('authorization'=>$data));
		}
		else
		{
			$data = array(
				'is_setcookie'=>true,
				'url'=>""
			);

			$this->response->make_json_ok('', array('authorization'=>$data));
		}
	}
}