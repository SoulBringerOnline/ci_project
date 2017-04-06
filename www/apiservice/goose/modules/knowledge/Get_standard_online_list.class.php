<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/2
 * Time: 14:20
 */

namespace Goose\Modules\Knowledge;

use \Goose\Package\Knowledge\DBKnowledge;
use \Goose\Libs\ErrorCode_Model_Common;

class Get_standard_online_list extends \Goose\Libs\Wmodule{
	public function run() {
		$start = isset($_REQUEST['start'])?$_REQUEST['start']:"";
		$end = isset($_REQUEST['end'])?$_REQUEST['end']:"";
		if (empty($end)) {
			//参数为空
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
		} else {
			//判断token是否失效
			if ($this->session->checkToken()) {
				$data = DBKnowledge::instatnce()->get_standard_online_list(intval($start), intval($end));
				$this->response->make_json_ok("", $data);
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
			}
		}
	}
} 
