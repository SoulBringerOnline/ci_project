<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/4
 * Time: 15:09
 */

namespace Goose\Package\Sms;

use \Libs\Mongo\MongoDB;

class DBSms_Manager {

	private static $intance = NULL;
	private  $mongo_op =null;
	private  $time = 0;
	private function __construct() {
		$this->mongo_op = MongoDB::getMongoDB("op","gsk");
		$this->time = strtotime(date("Y-m-d"));
	}

	public static function instance() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	/**
	 * @param $type 0:IT平台  1：安信捷公司
	 */
	public function update_send_all($type)
	{
		$count = $this->mongo_op->sms_history->count(array('f_time'=>$this->time, 'f_type'=>$type));
		if($count === 0)
		{
			$this->mongo_op->sms_history->insert(array('f_type'=>$type, 'f_time'=>$this->time, 'f_all_send'=>1));
		}
		else
		{
			$this->mongo_op->sms_history->update(array('f_type'=>$type, 'f_time'=>$this->time), array('$inc' => array('f_all_send'=>1)));
		}
	}

	public function update_send_succ($type)
	{
		$this->mongo_op->sms_history->update(array('f_type'=>$type, 'f_time'=>$this->time), array('$inc' => array('f_success_send'=>1)), array('upsert'=>true));
	}

	public function update_verify_all($type)
	{
		$count = $this->mongo_op->sms_history->count(array('f_type'=>$type, 'f_time'=>$this->time));
		if($count === 0)
		{
			$this->mongo_op->sms_history->insert(array('f_type'=>$type, 'f_time'=>$this->time, 'f_all_verify'=>1));
		}
		else
		{
			$this->mongo_op->sms_history->update(array('f_type'=>$type, 'f_time'=>$this->time), array('$inc' => array('f_all_verify'=>1)));
		}
	}

	public function update_verify_succ($type)
	{
		$this->mongo_op->sms_history->update(array('f_type'=>$type, 'f_time'=>$this->time), array('$inc' => array('f_success_verify'=>1)), array('upsert'=>true));
	}

	/**
	 * 安信捷短信网关回调接口
	 * @param $data
	 */
	public function add_sms_callback_history($data)
	{
		$msgtype = $data['msgtype'];
		$mobile = $data['mobile'];
		$recvTime = $data['recvTime'];
		$sendtime = $data['sendtime'];
		$order_num = $data['content'];
		$state = $data['state'];

		$his = array(
			'f_msgtype'=>intval($msgtype),
			'f_mobile'=>$mobile,
			'f_recvTime'=>intval($recvTime),
			'f_sendtime'=>intval($sendtime),
			'f_order_num'=>$order_num,
			'f_state'=>intval($state)
		);

		$this->mongo_op->sms_callback_log->insert($his);
	}
} 