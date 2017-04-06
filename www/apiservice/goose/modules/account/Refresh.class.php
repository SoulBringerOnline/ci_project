<?php
namespace Goose\Modules\Account;

use \Goose\Package\Account\DBAccount_Manager;
use \Goose\Package\User\DBUser_Manager;
use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Account\Helper\Errorcode_Model_Account;
use \Goose\Libs\Util\Util;
use \Goose\Libs\Token;

class Refresh extends \Goose\Libs\Wmodule{
	public function run() {
		$cltid = $this->request->headers['Clt'];
		if (! $cltid) {
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_HEADER_FAILURE));return;
		}
		$uid =  $this->request->headers['U'];
		if (! $uid) {
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_HEADER_FAILURE));return;
		}
		$refresh_token = trim($_POST['refresh_token']);
		if (! $refresh_token) {
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
		}
		try {
			$refresh = Token::refresh($uid, $cltid, $refresh_token);
		} catch (\Exception $e) {
			$this->app->log->log('REFRESH_LOGIN' , array('[ERROR]'=>$e));
			$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_REFRESH_TOKEN_FAILD));return;
		}
		if (! $refresh) {
			//刷新登录失败，重新登录
			$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_REFRESH_TOKEN_FAILD));return;
		}
		$refresh['uin'] = $uid;
		
		$this->response->make_json_ok("", array('token'=>$refresh));
	}
}