<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/30
 * Time: 15:56
 */

namespace Goose\Modules\Newyeargame;

use \Goose\Package\Sms\Helper\Errorcode_Model_VerifySMS;
use \Goose\Package\Newyeargame\DBNewyear_game_Manager;
use \Goose\Package\Newyeargame\Helper\Errorcode_Model_NewyearGame;

class Is_exist_user extends \Goose\Libs\Wmodule{

	public function run()
	{
		$phone = $_REQUEST['phone'];
		$key = $_COOKIE['unionid'];

		if(empty($key))
		{
			$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_EMPTY_COOKIE), "没种cookid");
			return;
		}

		if(empty($phone))
		{
			$this->response->make_json_response(intval(Errorcode_Model_VerifySMS::ERROR_LACK_PARAMETER));
		}
		else
		{
			$falg = DBNewyear_game_Manager::instatnce()->is_exist_user($phone, $key);
			if($falg == 1)
			{
				$this->response->make_json_ok('', array('is_exist'=>true));
			}
			else if($falg == 0)
			{
				$this->response->make_json_ok('', array('is_exist'=>false));
			}
			else if($falg == 101)
			{
				$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_BINDED_PHONE), "该手机号已经验证过了，无法重新验证哦！请使用该手机号直接使用筑友领取奖品吧~");
			}
			else
			{
				$this->response->make_json_response(intval(Errorcode_Model_NewyearGame::ERROR_BINDED_UNIONID), "你已经验证过手机号，无法重复验证哦！请使用验证手机号直接使用筑友领取奖品吧~");
			}
		}
	}
} 