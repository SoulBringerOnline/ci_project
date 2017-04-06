<?php
/**
 * 容联云通讯配置文件
 * Date: 2015/11/20
 */

namespace Goose\Config\Webapi;

class Call extends \Goose\Libs\Singleton{
	public function configs() {
		return array (
			'zhuyou' => array(
				'account_sid' => 'aaf98f89511a246a01511e59bd260c71',
				'account_token' => 'd1f88aee5a454014889c8dcc6cc265c7',
				'app_id' => '8a48b551511a2cec01511e5e47ab0cf7',
				'app_token' => '008e6178184f960286cfb53af369684b',
				'sub_account_sid' => '78ffef9a919311e5bb61ac853d9d52fd',
				'sub_account_token' => 'f8d86fb0b5e5045bdff8edbbf52ca9fd',
				'vo_ip_account' => '8005223100000003',
				'vo_ip_password' => 'ncKYr760',
				//'base_url' => 'sandboxapp.cloopen.com',
				'base_url' => 'app.cloopen.com',
				'port' => '8883',
				'version' => '2013-12-26',
			),
		);
	}
}