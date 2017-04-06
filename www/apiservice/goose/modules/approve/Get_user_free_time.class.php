<?php
namespace Goose\Modules\Approve;

use \Goose\Package\Call\DBProjectUserFreeTellTime_Manager;

class Get_user_free_time extends \Goose\Libs\Wmodule {
	public function run() {
		if ($this->session->checkToken()) {
			$uid = intval($this->session->uid);
			$time = DBProjectUserFreeTellTime_Manager::instance()->get_call_time($uid);
			if(! $time){
				$time = 0;
			}

			$this->response->make_json_ok("", array("free_time"=>$time));return;
		} else {
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
			return;
		}
	}

}

