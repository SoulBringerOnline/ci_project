<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/10/30
 * Time: 14:01
 */

namespace Goose\Modules\Knowledge;

use \Goose\Package\Knowledge\DBKnowledge;
use \Goose\Libs\ErrorCode_Model_Common;

class Get_technology_info extends \Goose\Libs\Wmodule{
		public function run() {
			$technology_id = isset($_REQUEST['technology_id'])?$_REQUEST['technology_id']:"";
			if (empty($technology_id)) {
				//参数为空
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
			} else {
				//判断token是否失效
				if ($this->session->checkToken()) {
					$data = DBKnowledge::instatnce()->get_technology_info($technology_id);
					$this->response->make_json_ok("", $data);
				} else {
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
				}
			}
		}
	}