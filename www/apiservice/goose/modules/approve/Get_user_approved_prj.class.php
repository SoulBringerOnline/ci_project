<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/30
 * Time: 11:02
 */
namespace Goose\Modules\Approve;

use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Approve\DBApprove_Manager;


class Get_user_approved_prj extends \Goose\Libs\Wmodule{

	public function run()
	{
		$uid = intval($this->session->uid);
		//判断token是否失效
		if (true)
		{
			$data = DBApprove_Manager::instatnce()->get_user_approved_prj($uid);
			$this->response->make_json_ok("", array("prj_list" => $data));
		}
		else
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
		}
	}
} 