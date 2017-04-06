<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/11
 * Time: 11:34
 */

namespace Goose\Modules\Question;

use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Question\DBQuestion_Manager;

class Get_user_receive_permission extends \Goose\Libs\Wmodule{

	public function run()
	{
		//检查token
		if(true)
		{
			$content = array();
			$uid = intval($this->session->uid);
			$content['new_user'] = DBQuestion_Manager::instance()->is_new_user($uid);
			$content['receiving_point'] = DBQuestion_Manager::instance()->get_user_points($uid);
			if(DBQuestion_Manager::instance()->is_set_phone($uid))
			{
				$content['bind_phone'] = true;
			}
			else
			{
				$content['bind_phone'] = false;
			}

			if(DBQuestion_Manager::instance()->is_set_personal_info($uid))
			{
				$content['hasDetail'] = true;
			}
			else
			{
				$content['hasDetail'] = false;
			}

			$content['received_point'] = DBQuestion_Manager::instance()->is_receive_point($uid);

			$this->response->make_json_ok('', $content);
		}
		else
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE), 'token过期');
		}
	}
} 