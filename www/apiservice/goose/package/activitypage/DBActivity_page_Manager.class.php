<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/14
 * Time: 14:50
 */
namespace Goose\Package\Activitypage;

use \Libs\Mongo\MongoDB;

class DBActivity_page_Manager {

	// 数据处理类C
	private static $intance = NULL;
	private  $mongo_op =null;
	private function __construct() {
		$this->mongo_op = MongoDB::getMongoDB("op","gsk");
	}

	public static function instatnce() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	public function get_activity_page($type)
	{
		$main_switch = $this->mongo_op->activity_position->findOne(array('f_position_id'=>intval($type)));
		if(is_null($main_switch)||$main_switch['f_position_state']==0)//总开关
		{
			return null;
		}
		else
		{
			$time = time();
			$where = array(
				'f_activity_pt_id'=>intval($type),
				'f_begin_time'=>array('$lte' => $time),
				'f_finish_time'=>array('$gte' => $time)
			);
			$not_need = array(
				"_id"=>0,
				"f_pic_order"=>0,
				"f_pic_size"=>0,
				"f_operater"=>0,
				"f_operater_type"=>0,
				"f_operater_time"=>0,
			);
			$res = $this->mongo_op->activity_pt_history->findOne($where, $not_need);
			if(is_null($res))
			{
				return null;
			}
			else
			{
				return $res;
			}
		}

	}
} 