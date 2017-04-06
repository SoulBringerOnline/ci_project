<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/23
 * Time: 16:25
 */

namespace Goose\Modules\Test;

use \Goose\Package\Test\DBTest_Manager;


class Delete_redPacket_user extends \Goose\Libs\Wmodule{

	public function run()
	{
		$uin = intval($_REQUEST['uin']);

		if(!empty($uin))
		{
			DBTest_Manager::instatnce()->delete_redPacket_user($uin);
			$this->response->make_json_ok();
		}
	}
} 