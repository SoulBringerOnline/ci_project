<?php
/**
 * 安信捷短信网关回调函数
 * User: lixd-a
 * Date: 2015/12/14
 * Time: 19:20
 */

namespace Goose\Modules\Sms;

use \Goose\Package\Sms\DBSms_Manager;


class Sendsms_callback extends \Goose\Libs\Wmodule{

	public function run()
	{
		DBSms_Manager::instance()->add_sms_callback_history($_REQUEST);
	}
} 