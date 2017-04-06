<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/22
 * Time: 10:27
 */

namespace Goose\Package\Huodong;

use \Libs\Mongo\MongoDB;

class DBFirst_freetime_Manager {

	private static $intance = NULL;
	private  $mongo_op =null;
	private function __construct() {
		$this->mongo_op = MongoDB::getMongoDB("online","gsk_ol");
	}

	public static function instatnce() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	public function is_user_first_freetime($uin)
	{
		$count = $this->mongo_op->userFreeTimeLog->count(array('f_uin'=>intval($uin), 'f_type'=>3));
		if($count === 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function pc_activity_pos() {
		$data =  $this->mongo_op->pcActivityPos->findOne(array('f_pos_id'=>'1'), array('f_name', 'f_url'));
		$ret = array();
		if($data){
			$ret['name'] = $data['f_name'];
			$ret['url'] = $data['f_url'];
		}
		
		return $ret;
	}
} 