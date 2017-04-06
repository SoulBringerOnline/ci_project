<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/22
 * Time: 10:20
 */

namespace Goose\Modules\Huodong;

use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Huodong\DBFirst_freetime_Manager;

class Get_user_first_freetime extends \Goose\Libs\Wmodule{

	public function run()
	{
		if(true)
		{
			$uid = intval($this->session->uid);
			if(!empty($uid))
			{
				$flag = DBFirst_freetime_Manager::instatnce()->is_user_first_freetime($uid);
				if($flag)
				{
					$content = array('joinFreeCall'=>false);
					$this->response->make_json_ok('', $content);
				}
				else
				{
					$content = array('joinFreeCall'=>true);
					$this->response->make_json_ok('', $content);
				}
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_HEADER_FAILURE), "用户uid为空");
			}
		}
		else
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
		}
	}
} 