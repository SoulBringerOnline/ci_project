<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2016/1/11
 * Time: 14:54
 */

namespace Goose\Modules\Newyeargame;

use Goose\Package\Newyeargame\DBNewyear_game_Manager;

class Delete_user_newyear_game extends \Goose\Libs\Wmodule{
	public function run()
	{
		$host = $_SERVER['HTTP_HOST'];
		if($host == "api.zy.work.glodon.com")//外网不允许删除
		{
			$phone = $_REQUEST['phone'];
			if(empty($phone))
			{
				$this->response->make_json_ok('请输入要删除用户的手机号');
			}
			else
			{
				DBNewyear_game_Manager::instatnce()->delete_user_newyear($phone);
				$this->response->make_json_ok('failure！');
			}
		}
		else
		{
			$this->response->make_json_ok('success');
		}

	}
} 