<?php
/**
 * 微信账号--前端的appid
 * User: lixd-a
 * Date: 2015/12/21
 * Time: 19:16
 */

namespace Goose\Modules\Weixin;

use \Goose\Package\Weixin\Helper\Errorcode_Model_Weixin;
use \Goose\Libs\Util\Util;
use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Weixin\DBAuthorization_Manager;

class Authorization_login_weixin extends \Goose\Libs\Wmodule{

	public function run()
	{
		$appid = "wx19e23873eca688e6";
		$secret = "49e9ddc9356f57edb5d9a45df61dbeb2";
		$code = $_REQUEST['code'];
		$uid = intval($this->session->uid);
		//判断token是否失效
		if(!$this->session->checkToken())
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
			return;
		}
		if(empty($uid))
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_HEADER_FAILURE), "用户uid为空");
			return;
		}

		if(empty($code))
		{
			$this->response->make_json_response(intval(Errorcode_Model_Weixin::ERROR_EMPTY_CODE), "code码为空");
		}
		else
		{
			$url ="https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";
			$url = sprintf($url, $appid, $secret, $code);
			$res = json_decode(Util::send_request_get($url), true);

			$openid = $res['openid'];
			$unionid = $res['unionid'];
			$this->app->log->log('Weixin', array('code'=>$code, 'url'=>$url, 'res'=>$res));

			if($res['errcode'] == 40029)
			{
				$this->response->make_json_response(intval(Errorcode_Model_Weixin::ERROR_FAILURE_CODE), "code码失效");
				return;
			}

			if(empty($openid) || empty($unionid))
			{
				$this->response->make_json_response(intval(Errorcode_Model_Weixin::ERROR_EMPTY_OPENID), "openid为空");
			}
			else
			{
				if(DBAuthorization_Manager::instatnce()->is_uin_binded($uid))
				{
					$this->response->make_json_response(intval(Errorcode_Model_Weixin::ERROR_OPENID_BINDED), "该用户已经绑定过微信");
					return;
				}

				if(DBAuthorization_Manager::instatnce()->is_unionid_binded($unionid))
				{
					$this->response->make_json_response(intval(Errorcode_Model_Weixin::ERROR_UNIONID_BINDED), "该微信号已经绑定过用户");
					return;
				}

				DBAuthorization_Manager::instatnce()->save_user_openid_weixin($uid, $openid, $unionid);
				$this->response->make_json_ok();
			}
		}
	}
} 