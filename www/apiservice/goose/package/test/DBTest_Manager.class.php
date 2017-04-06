<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/23
 * Time: 16:41
 */

namespace Goose\Package\Test;
use \Libs\Mongo\MongoDB;

class DBTest_Manager {
	// 数据处理类C
	private static $intance = NULL;
	private  $mongo_ol =null;
	private function __construct() {
		$this->mongo_ol = MongoDB::getMongoDB("online","gsk_ol");
	}

	public static function instatnce() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	public function delete_redPacket_user($uin)
	{
		$this->mongo_ol->red_packet_history->remove(array('f_uin'=>$uin));
	}
} 