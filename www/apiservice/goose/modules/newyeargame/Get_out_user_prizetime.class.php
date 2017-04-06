<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2016/1/6
 * Time: 17:14
 */

namespace Goose\Modules\Newyeargame;

use \Goose\Package\Newyeargame\DBNewyear_game_Manager;
use \Goose\Package\Newyeargame\Helper\Errorcode_Model_NewyearGame;

class Get_out_user_prizetime extends \Goose\Libs\Wmodule{
	public function run()
	{
		$key = $_COOKIE['unionid'];

		if(empty($key))
		{
			$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_EMPTY_COOKIE), "没种cookid");
			return;
		}

		$times = DBNewyear_game_Manager::instatnce()->get_out_user_prizetime($key);
		$content = array('times'=>$times);
		$this->response->make_json_ok('', $content);
	}
} 