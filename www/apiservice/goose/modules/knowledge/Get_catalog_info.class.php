<?php

namespace Goose\Modules\Knowledge;

use \Goose\Package\Knowledge\DBKnowledge;
use \Goose\Libs\ErrorCode_Model_Common;


class Get_catalog_info extends \Goose\Libs\Wmodule{
	public function run() {
		$standard_id = isset($_REQUEST['standard_id'])?$_REQUEST['standard_id']:"";
		if(empty($standard_id))
		{
			//参数为空
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
		}
		else
		{
			//判断token是否失效
			if($this->session->checkToken())
			{
				$data = DBKnowledge::instatnce()->get_catalog_info($standard_id);
				$this->response->make_json_ok("", $data);
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
			}
		}
	}

}
