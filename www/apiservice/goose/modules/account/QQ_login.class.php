<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2016/1/7
 * Time: 11:14
 */

namespace Goose\Modules\Account;

use \Goose\Package\Account\DBAccount_Manager;
use Goose\Package\Weixin\Helper\Errorcode_Model_QQ;
use \Goose\Package\Weixin\Helper\Errorcode_Model_Weixin;
use \Goose\Package\Account\Helper\Errorcode_Model_Account;
use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Libs\Util\Util;
use \Goose\Libs\Token;

class QQ_login extends \Goose\Libs\Wmodule{
	public function run()
	{
		$qq_appid="1104837198";
		$cli_id = $this->session->cltid;

		$openid = $_REQUEST['openid'];
		$access_token = $_REQUEST['access_token'];

		if(empty($openid) || empty($access_token))
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM), "参数有误");
			return;
		}

		$url = "https://graph.qq.com/user/get_simple_userinfo?access_token=%s&oauth_consumer_key=%s&openid=%s&format=json";
		$url = sprintf($url, $access_token, $qq_appid, $openid);
		$res = json_decode(Util::send_request_get($url), true);

		if($res['ret'] == 0)
		{
			$uid = DBAccount_Manager::instance()->get_uin_qq_openid($openid);
			if(empty($uid))
			{
				$uid = DBAccount_Manager::instance()->get_new_uid();
				//从redis获取uid失败
				if($uid < 1)
				{
					$this->app->log->log('Account' , array('用户注册失败，获取uid失败。get uid from redis ['.$uid.']'));
					$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_UID_FAILD), "获取uid失败！");
					return;
				}

				DBAccount_Manager::instance()->save_new_user_qq($uid, $openid);
				$this->app->log->log('Account' , array('QQ用户注册成功!'=>array('uid'=>$uid, 'openid'=>$openid)));
			}

			$token = Token::get($uid, $cli_id);
			if(!is_null($token))
			{
				$content = array(
					'uid' => $uid,
					'token' => $token['token'],
					'refresh_token' => $token['refresh_token'],
				);
				$this->response->make_json_ok('用户登录成功', $content);
			}
			else
			{
				$this->response->make_json_fail('用户登录成功，但获取token失败', $uid);
			}
		}
		else
		{
			$this->response->make_json_response(intval(Errorcode_Model_QQ::ERROR_FAILURE_OPENID), "openid或access_token失效");
		}

	}
} 