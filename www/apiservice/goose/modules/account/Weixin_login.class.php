<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2016/1/6
 * Time: 17:36
 */

namespace Goose\Modules\Account;

use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Account\DBAccount_Manager;
use \Goose\Package\Weixin\Helper\Errorcode_Model_Weixin;
use \Goose\Package\Account\Helper\Errorcode_Model_Account;
use \Goose\Libs\Util\Util;
use \Goose\Libs\Token;

class Weixin_login extends \Goose\Libs\Wmodule{

	public function run()
	{
		$appid = "wx19e23873eca688e6";
		$secret = "49e9ddc9356f57edb5d9a45df61dbeb2";
		$code = $_REQUEST['code'];
		$cli_id = $this->session->cltid;

		if(empty($code))
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM), "code码为空");
		}
		else
		{
			$url ="https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";
			$url = sprintf($url, $appid, $secret, $code);
			$res = json_decode(Util::send_request_get($url), true);

			$openid = $res['openid'];
			$unionid = $res['unionid'];
			$this->app->log->log('Account_weixin', array('code'=>$code, 'res'=>$res));

			if($res['errcode'] == 40029)
			{
				$this->response->make_json_response(intval(Errorcode_Model_Weixin::ERROR_FAILURE_CODE), "code码失效");
				return;
			}

			if(empty($openid) || empty($unionid))
			{
				$this->response->make_json_response(intval(Errorcode_Model_Weixin::ERROR_EMPTY_OPENID), "openid为空");
				return;
			}

			$uid = null;

			//第一步：检查是否存在unionid
			$uid = DBAccount_Manager::instance()->is_exist_unionid($unionid);
			if(empty($uid))
			{
				//第二步：检查是否存在openid
				$uid = DBAccount_Manager::instance()->is_exist_weixin_openid($openid, $unionid);
				if(empty($uid))
				{
					//第三步:检查是否存在QQ_openid（IOS遗留BUG）
					$uid = DBAccount_Manager::instance()->is_exist_QQ_openid($openid, $unionid);
					if(empty($uid))
					{
						//第四步：前三步都找不到，说明用户真的不存在，需要走注册流程
						$uid = DBAccount_Manager::instance()->get_new_uid();
						//从redis获取uid失败
						if($uid < 1)
						{
							$this->app->log->log('Account' , array('用户注册失败，获取uid失败。get uid from redis ['.$uid.']'));
							$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_UID_FAILD), "获取uid失败！");
							return;
						}

						DBAccount_Manager::instance()->save_new_user_weixin($uid, $openid, $unionid);
						$this->app->log->log('Account' , array('微信用户注册成功!'=>array('uid'=>$uid, 'openid'=>$openid, 'unionid'=>$unionid)));
					}
				}
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
	}
} 