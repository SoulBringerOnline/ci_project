<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/25
 * Time: 17:09
 */
namespace Goose\Package\Newyeargame;

use \Libs\Mongo\MongoDB;
use \Libs\Redis\Redis;

class DBNewyear_game_Manager {

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

	public function update_newYear_game_info($openid, $unionid)
	{
		$count = $this->mongo_ol->newYearGameToUser->count(array('f_union_id'=>$unionid));
		if($count === 0)
		{
			$data = array(
				'f_open_id'=>$openid,
				'f_union_id'=>$unionid,
				'f_game_point'=>0,
				'f_is_old_user'=>0,
				'f_new_prize_num'=>3
			);
			$this->mongo_ol->newYearGameToUser->insert($data);
		}
		else
		{
			$data = array('f_new_prize_num'=>3);
			$this->mongo_ol->newYearGameToUser->update(array('f_union_id'=>$unionid), array('$set'=>$data));
		}

		return true;
	}

	public function save_cookie($key)
	{
		$openid = Redis::get($key.'_openid');
		if(!$openid)
		{
			$openid = "";
		}

		$unionid = Redis::get($key.'_unionid');
		if(empty($openid) && empty($unionid))
		{
			return false;
		}
		else
		{
			$this->update_newYear_game_info($openid, $unionid);
			return true;
		}
	}

	public function update_user_point($key, $point)
	{
		$res = array();
		$unionid = Redis::get($key.'_unionid');
		$count = $this->mongo_ol->newYearGameToUser->count(array('f_union_id'=>$unionid));
		if($count === 0)
		{
			$res['falg'] = false;
		}
		else
		{
			$user = $this->mongo_ol->newYearGameToUser->findOne(array('f_union_id'=>$unionid));
			$new_point = intval($point);
			$old_point = intval($user['f_game_point']);
			if($old_point < $new_point)
			{
				$new_data = array('f_game_point'=>$new_point);
				$this->mongo_ol->newYearGameToUser->update(array('f_union_id'=>$unionid), array('$set'=>$new_data));
			}

			$all_count_point = $this->mongo_ol->newYearGameToUser->count(array('f_game_point'=>array('$gt'=>0)));//得到所有游戏分数不为0的用户数
			$count_gt_point = $this->mongo_ol->newYearGameToUser->count(array('f_game_point'=>array('$gt'=>$new_point)));//得到所有游戏分数不为0的用户数
			$percentage = floor(($all_count_point - $count_gt_point)/$all_count_point*100)."%";

			$res['falg'] = true;
			$res['percentage'] = $percentage;
		}
		return $res;
	}

	public function is_exist_user($phone, $key)
	{
		$count = $this->mongo_ol->account->count(array('f_account_phone'=>$phone));
		if($count === 0)
		{
			$count_bind = $this->mongo_ol->newYearGameToUser->count(array('f_phone'=>$phone));
			if($count_bind===0)
			{
				$unionid = Redis::get($key.'_unionid');
				$user = $this->mongo_ol->newYearGameToUser->findOne(array('f_union_id'=>$unionid));
				$old_phone = $user['f_phone'];
				if(empty($old_phone))
				{
					$this->mongo_ol->newYearGameToUser->update(array('f_union_id'=>$unionid), array('$set'=>array('f_phone'=>$phone, 'f_verify_time'=>new \MongoInt64(time()))));
					return 0;
				}
				else
				{
					return 102;
				}

			}
			else
			{
				return 101;
			}

		}
		else
		{
			return 1;
		}
	}

	public function get_out_user_prizetime($key)
	{
		$time = 0;
		$unionid = Redis::get($key.'_unionid');
		$user = $this->mongo_ol->newYearGameToUser->findOne(array('f_union_id'=>$unionid));
		if(!is_null($user))
		{
			$time = $user['f_new_prize_num'];
		}
		return $time;
	}

	public function delete_user_newyear($phone)
	{
		$this->mongo_ol->account->update(array('f_account_phone'=>$phone), array('$set'=>array('f_account_phone'=>'')));
		$this->mongo_ol->user->update(array('f_phone'=>$phone), array('$set'=>array('f_phone'=>'')));
		$this->mongo_ol->newYearGameToUser->remove(array('f_phone'=>$phone));
	}
} 