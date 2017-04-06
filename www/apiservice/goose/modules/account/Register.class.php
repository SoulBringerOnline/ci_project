<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/19
 * Time: 10:53
 */
namespace  Goose\Modules\Account;

use \Goose\Libs\Util\Util;
use \Goose\Libs\Token;
use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Account\DBAccount_Manager;
use \Goose\Package\Account\Helper\Errorcode_Model_Account;
use \Goose\Package\Sms\Helper\Errorcode_Model_VerifySMS;


class Register extends \Goose\Libs\Wmodule{

	public function run()
	{
		ini_set('display_errors', 'off');
		ini_set('html_errors', 'off');

		$encypt_pwd = isset($_REQUEST['encrypt_pwd'])?$_REQUEST['encrypt_pwd']:"";
		$mobile = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";
		$app_id = isset($_REQUEST['app_id'])?$_REQUEST['app_id']:"";
		$cli_id = $this->session->cltid;
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
		$key = 'sms-'.$mobile.'-'.$type.'-'.$app_id.'-'.$cli_id;

		$this->app->log->log('Account' , array('key'=>$key, $_REQUEST));
		if(empty($encypt_pwd) || empty($mobile))
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
			return;
		}
		else
		{
			$code = DBAccount_Manager::instance()->get_SMS_code($key, $mobile);
			if(empty($code))
			{
				$this->response->make_json_response(intval(Errorcode_Model_VerifySMS::ERROR_SMS_FAILURE), "验证码失效！");
				return;
			}
			else
			{
				$code_md5_16 = substr(md5($code), 0, 16);//验证码先md5加密再取前16位
				$md5_pwd = Util::gsk_decrypt($code_md5_16, base64_decode($encypt_pwd));
				if(empty($md5_pwd))
				{
					//用TEA算法解析$encypt_pwd， 失败就意味着 code码 不对
					$this->response->make_json_response(intval(ErrorCode_Model_Common::FAILD), "验证码错误！");
					$this->app->log->log('Account' , array('解析失败'=>array('code'=>$code, 'encypt_pwd'=>$encypt_pwd)));
					return;
				}
				else
				{
					//解析成功，删除验证码记录
					DBAccount_Manager::instance()->delete_code($key);
					
					//判断手机号是否已经存在
					if(DBAccount_Manager::instance()->is_exist_phone($mobile))
					{
						$this->app->log->log('Account' , array('用户注册失败，手机号已经存在 '.$mobile));
						$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_USER_EXIST), "手机号已经注册过！");
						return;
					}

					$uid = DBAccount_Manager::instance()->get_new_uid();
					//从redis获取uid失败
					if($uid < 1)
					{
						$this->app->log->log('Account' , array('用户注册失败，获取uid失败。get uid from redis ['.$uid.']'));
						$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_UID_FAILD), "获取uid失败！");
						return;
					}

					DBAccount_Manager::instance()->save_new_user($uid, $md5_pwd, $mobile);
					$this->app->log->log('Account' , array('用户注册成功!'=>array('uid'=>$uid, 'pwd'=>$md5_pwd)));

					$token = Token::get($uid, $cli_id);
					if(!is_null($token))
					{
						$content = array(
							'uid' => $uid,
							'token' => $token['token'],
							'refresh_token' => $token['refresh_token'],
						);
						$this->response->make_json_ok('用户注册成功', $content);
					}
					else
					{
						$this->response->make_json_fail('用户注册成功，但获取token失败', $uid);
					}

				}
			}
		}
	}
} 