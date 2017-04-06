<?php
namespace Goose\Modules\Account;

use \Goose\Package\Account\DBAccount_Manager;
use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Account\Helper\Errorcode_Model_Account;
use \Goose\Libs\Util\Util;
use \Goose\Libs\Token;

class Login extends \Goose\Libs\Wmodule{
	public function run() {
		$account = $_POST['account'];
		if (! $account) {
			//参数错误
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
		}
		$tg_token = base64_decode(trim($_POST['tg_token']));
		if (! $tg_token) {
			//参数错误
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
		}
		$cltid = $this->session->cltid;
		if (! $cltid) {
			//参数错误
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_HEADER_FAILURE));return;
		}
		if (preg_match("/.*?@.*/", $account)) {
			$where = array('f_account_email'=>$account);
		} else {
			$where = array('f_account_phone'=>$account);
		}
		$password = DBAccount_Manager::instance()->get_new_password($where);
		if (! $password) {
			//账户不存在
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_NOT_EXIST));return;
		}
		$uid = DBAccount_Manager::instance()->get_user_uin($where);
		if (! $uid) {
			//账户不存在
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_NOT_EXIST));return;
		}
		$key = mb_substr($password, 0, 16);
		//解密TEA加密串
		$tg_key = Util::gsk_decrypt($key, $tg_token);
		if (! $tg_key) {
			//解密失败
			$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_ACCOUNT_PASSWORD));return;
		}
		if(mb_strlen($tg_key) != 48){
			$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_TGTOKEN_FORMAT));return;
		}
		$tg_key = mb_substr($tg_key, 0, 16);

		//生成token和refresh_token
		try {
			$return = Token::get($uid, $cltid);
			$return['uin'] = $uid;
			$mcrypt_token = Util::gsk_encrypt($tg_key, json_encode($return));
			$this->response->make_json_ok("", array('result'=>base64_encode($mcrypt_token)));return;
		} catch (\Exception $e) {
			$this->app->log->log('LOGIN' , array('[ERROR]'=>$e));
			$this->response->make_json_response(intval(Errorcode_Model_Account::ERROR_LOGIN_FAILD));return;
		}
	}
}