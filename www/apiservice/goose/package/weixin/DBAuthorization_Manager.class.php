<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/22
 * Time: 11:42
 */

namespace Goose\Package\Weixin;

use \Libs\Mongo\MongoDB;

class DBAuthorization_Manager {

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

	public function save_user_openid_weixin($uin, $openid, $unionid)
	{
		$data = array(
			'f_uin'=>intval($uin),
			'f_openid'=>$openid,
			'f_unionid'=>$unionid,
			'f_time'=>new \MongoInt64(time())
		);
		$this->mongo_ol->red_packet_history->insert($data);
	}

	public function is_uin_binded($uin)
	{
		$count = $this->mongo_ol->red_packet_history->count(array('f_uin'=>intval($uin)));
		if($count === 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function is_unionid_binded($unionid)
	{
		$count = $this->mongo_ol->red_packet_history->count(array('f_unionid'=>$unionid));
		if($count === 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}