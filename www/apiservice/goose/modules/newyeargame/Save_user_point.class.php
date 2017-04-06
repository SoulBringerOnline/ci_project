<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/30
 * Time: 14:34
 */

namespace Goose\Modules\Newyeargame;

use \Goose\Package\Newyeargame\DBNewyear_game_Manager;
use \Goose\Package\Newyeargame\Helper\Errorcode_Model_NewyearGame;


class Save_user_point extends \Goose\Libs\Wmodule{

	public function run()
	{
		$key = $_COOKIE['unionid'];
		$point = $_REQUEST['point'];

		if(empty($key))
		{
			$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_EMPTY_COOKIE), "没种cookid");
			return;
		}

		if(DBNewyear_game_Manager::instatnce()->save_cookie($key))
		{
			$res = DBNewyear_game_Manager::instatnce()->update_user_point($key, $point);
			if($res['falg'] == true)
			{
				$content = array(
					'drawNum'=>3,
					'rate'=>$res['percentage']
				);
				$this->response->make_json_ok('', $content);
			}
			else
			{
				$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_FAILE_OPENID), "cookid中openid无效");
			}
		}
		else
		{
			$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_FAILE_OPENID), "cookid中openid失效");
		}


	}
} 