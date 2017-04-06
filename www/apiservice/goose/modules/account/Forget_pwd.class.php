<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/20
 * Time: 17:13
 */

namespace Goose\Modules\Account;

use \Goose\Libs\Util\Util;
use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Account\DBAccount_Manager;
use \Goose\Package\Account\Helper\Errorcode_Model_Account;
use \Goose\Package\Sms\Helper\Errorcode_Model_VerifySMS;


class Forget_pwd extends \Goose\Libs\Wmodule{

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

					//判断手机号是否绑定用户了
					$uin = DBAccount_Manager::instance()->get_uin_phone($mobile);
					if(empty($uin))
					{
						$this->app->log->log('Account' , array('用户【忘记密码】操作失败，不存在手机号 '.$mobile.' 的用户'));
						$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_USER_NOT_EXIST), "该用户不存在！");
						return;
					}
					else
					{
						$pwd = md5($md5_pwd.md5($uin));// md5(s1,ouid) （db中存储的密码）
						DBAccount_Manager::instance()->update_user_pwd($uin, $pwd);
						$this->response->make_json_OK('修改密码成功！');
						$this->app->log->log('Account' , array('用户修改密码成功!'=>array('uid'=>$uin, 'pwd'=>$md5_pwd, 'last_pwd' => $pwd, 'md5_uin'=>md5($uin))));
					}
				}
			}
		}
	}
} 