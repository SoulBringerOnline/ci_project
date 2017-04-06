<?php
namespace Goose\Modules\Account;

use \Goose\Package\Account\DBAccount_Manager;
use \Goose\Libs\ErrorCode_Model_Common;

class Uid extends \Goose\Libs\Wmodule{
	public function run() {
		$account = $_GET['account'];
		if (! $account) {
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
		}
		if (preg_match("/.*?@.*/", $account)) {
			$where = array('f_account_email'=>$account);
		} else {
			$where = array('f_account_phone'=>$account);
		}
		$uin = DBAccount_Manager::instance()->get_user_uin($where);
		if (! $uin) {
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_NOT_EXIST));return;
		}
		
		$this->response->make_json_ok("", array('ouid'=>md5($uin)));
	}
}