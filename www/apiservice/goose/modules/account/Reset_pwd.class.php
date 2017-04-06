<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/20
 * Time: 19:29
 */

namespace Goose\Modules\Account;

use \Goose\Libs\Util\Util;
use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Account\DBAccount_Manager;
use \Goose\Package\Account\Helper\Errorcode_Model_Account;

class Reset_pwd extends \Goose\Libs\Wmodule{

	public function run()
	{
		ini_set('display_errors', 'off');
		ini_set('html_errors', 'off');
		
		$encypt_pwd = isset($_REQUEST['encrypt_pwd'])?$_REQUEST['encrypt_pwd']:"";
		$uin = intval($this->session->uid);

		if(empty($encypt_pwd))
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
			return;
		}

		if(empty($uin))
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_HEADER_FAILURE), 'Header解析失败');
			$this->app->log->log('Account' , array('重置密码失败，Header解析失败'=>array('uin'=>$uin, 'old_pwd'=>$encypt_pwd)));
			return;
		}

		$old_pwd = DBAccount_Manager::instance()->get_user_old_pwd($uin);
		if(empty($old_pwd))
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_NOT_EXIST), '用户不存在或者是第三方登录');
			$this->app->log->log('Account' , array('重置密码失败，库中密码为空'=>array('uin'=>$uin, 'old_pwd'=>$encypt_pwd)));
			return;
		}
		else
		{
			$old_pwd_16 = substr($old_pwd, 0, 16);//取老密码前16位
			$new_pwd = Util::gsk_decrypt($old_pwd_16, base64_decode($encypt_pwd));

			if(empty($new_pwd))
			{
				$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_WRONG_PWD), '旧密码不正确！');
				$this->app->log->log('Account' , array('重置密码失败，旧密码不正确'=>array('uin'=>$uin, 'old_pwd'=>$encypt_pwd)));
			}
			else
			{
				DBAccount_Manager::instance()->update_user_pwd($uin, $new_pwd);
				$this->response->make_json_ok('重置密码成功!');
				$this->app->log->log('Account' , array('重置密码成功'=>array('uin'=>$uin, 'new_pwd'=>$new_pwd)));
			}
		}

	}
} 