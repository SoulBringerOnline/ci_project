<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/25
 * Time: 17:02
 */
namespace Goose\Modules\Newyeargame;

use \Goose\Package\Newyeargame\DBNewyear_game_Manager;
use \Goose\Package\Newyeargame\Helper\Errorcode_Model_NewyearGame;

class Update_user_unionid extends \Goose\Libs\Wmodule{

	public function run()
	{
		$key = $_COOKIE['unionid'];
		if(empty($key))
		{
			$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_EMPTY_COOKIE), "没种cookid");
			return;
		}

		if(DBNewyear_game_Manager::instatnce()->save_cookie($key))
		{
			$this->response->make_json_ok();
		}
		else
		{
			$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_FAILE_OPENID), "cookid中openid失效");
		}
	}
} 