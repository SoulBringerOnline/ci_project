<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/10
 * Time: 10:49
 */
namespace Goose\Package\Question;

use \Libs\Mongo\MongoDB;
class DBQuestion_Manager{

	private static $intance = NULL;
	private  $mongo_ol =null;
	private function __construct() {
		$this->mongo_ol = MongoDB::getMongoDB("online","gsk_ol");
	}

	public static function instance() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	public function get_user_create_time($uin)
	{
		$time = "";
		if(!empty($uin))
		{
			$user = $this->mongo_ol->account->findOne(array('f_account_id'=>intval($uin)));

			$time = $user['f_account_create_time'];
		}
		return $time;
	}

	/**
	 * 得到所有问题
	 * @param $type 区别问题类型 0：app类问题   1：建筑知识类问题
	 * @return array
	 */
	public function get_question($type)
	{
		$question = iterator_to_array($this->mongo_ol->question_bank->find(array('f_type'=>intval($type))));
		return $question;
	}

	/**
	 * 保存用户问题
	 * @param $uin
	 * @param $type 新老用户类别
	 * @param $questions 用户随机分配到的问题
	 * @return bool
	 */
	public function save_user_question($uin, $type, $questions)
	{
		$msg = array();
		$msg['f_uin'] = intval($uin);
		$msg['f_new_user'] = $type;
		$msg['f_commit'] = false;
		$msg['f_question_id'] = $questions;
		$msg['f_points'] = new \MongoInt32(0);
		$msg['f_correct_count'] = new \MongoInt32(0);
		$msg['f_submit_time'] = new \MongoInt64(time());

		$this->mongo_ol->personal_qt_history->insert($msg);
		return true;
	}

	/**
	 * 获取某个用户的所有问题
	 * @param $uin
	 * @return array|null
	 */
	public function get_user_qt_info($uin)
	{
		$data = $this->mongo_ol->personal_qt_history->findOne(array('f_uin'=>$uin));
		return $data;
	}

	/**
	 * 获取某个问题的详细内容
	 * @param $id 问题id
	 * @return array|null
	 */
	public function get_one_qt_info($id)
	{
		$data = $this->mongo_ol->question_bank->findOne(array('_id'=>$id));
		return $data;
	}

	/**
	 * 判断某个用户是否已经拉取过问题
	 * @param $uin
	 * @return bool
	 */
	public function is_exist_user($uin)
	{
		if(empty($uin))
		{
			return false;
		}
		else
		{
			$count = $this->mongo_ol->personal_qt_history->count(array('f_uin'=>$uin));
			if($count == 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	/**
	 * 判断用户是否为新用户
	 * @param $uin
	 * @return mixed
	 */
	public function is_new_user($uin)
	{
		$user = $this->mongo_ol->personal_qt_history->findOne(array('f_uin'=>$uin));
		return $user['f_new_user'];
	}

	public function get_user_info($uin)
	{
		$user = $this->mongo_ol->personal_qt_history->findOne(array('f_uin'=>$uin));
		return $user;
	}

	public function get_user_points($uin)
	{
		$user = $this->mongo_ol->personal_qt_history->findOne(array('f_uin'=>$uin));
		return $user['f_points'];
	}

	/**
	 * 判断用户是否回答过问题
	 * @param $uin
	 * @return mixed
	 */
	public function is_answered($uin)
	{
		$user = $this->mongo_ol->personal_qt_history->findOne(array('f_uin'=>$uin));
		return $user['f_commit'];
	}

	/**
	 * 判断答案是否正确
	 * @param $id
	 * @param $answer
	 * @return bool
	 */
	public function judge_answer($id, $answer)
	{
		$qt = $this->mongo_ol->question_bank->findOne(array('_id'=>new \MongoId($id)));
		if(!is_null($qt) && isset($qt['f_answer']))
		{
			if($qt['f_answer'] == $answer)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * 更新个人答题信息
	 * @param $uin
	 * @param $data
	 * @return bool
	 */
	public function update_personal_qt($uin, $data)
	{
		$this->mongo_ol->personal_qt_history->update(array('f_uin'=>$uin), array('$set'=>$data));
		return true;
	}

	public function is_set_phone($uin)
	{
		$user = $this->mongo_ol->user->findOne(array('f_uin'=>$uin));
		if(empty($user['f_phone']))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * 判断用户上传头像是否存在
	 * @param $uin
	 * @return bool
	 */
	public function check_image($uin)
	{
		$url = 'http://zhuyou-f.oss-cn-beijing.aliyuncs.com/head%2F'.strtoupper(md5($uin."Glondon@#4%")).'.png';
		$flag = @file_get_contents($url,null,null,-1,1);
		return !empty($flag);
	}

	public function is_set_personal_info($uin) {
		$user = $this->mongo_ol->user->findOne(array('f_uin' => $uin), array('f_job_type', 'f_name', 'f_avatar_version'));

		$res = true;
		if (empty($user['f_job_type'])) {
			$res = false;
		}

		if (strstr($user['f_name'], "筑友用户")) {
			$res = false;
		}

		if (!isset($user['f_avatar_version'])||$user['f_avatar_version']==0)
		{
			if($this->check_image($uin))
			{
				$this->mongo_ol->user->update(array('f_uin'=>$uin), array('$set'=>array('f_avatar_version'=>1)), array('upsert'=>true));
			}
			else
			{
				$res =false;
			}
		}

		return  $res;
	}

	/**
	 * 用户是否领取过流量及流量值
	 * @param $uin
	 * @return array
	 */
	public function is_receive_point($uin)
	{
		$event_id = "20000";
		$action_id = "20000";
		$where = array('f_event_id'=>$event_id, 'f_action_id'=>$action_id, 'f_uin'=> intval($uin));
		$flow_count = $this->mongo_ol->flowLog->count($where);
		if($flow_count === 0)
		{
			//老用户存在抽奖环节，可能flowlog没记录，抽到流量为0
			$count = $this->mongo_ol->countForNovemberOldUser->count(array('f_uin'=>intval($uin)));
			if($count === 0)
			{
				return array('received_point'=>false, 'f_post_package'=>null);
			}
			else//老用户抽取O兆流量
			{
				return array('received_point'=>true, 'f_post_package'=>0);
			}
		}
		else
		{
			$point = $this->mongo_ol->flowLog->find($where);
			$points = 0;
			foreach($point as $item)
			{
				$points += intval($item['f_post_package']);
			}
			return array('received_point'=>true, 'f_post_package'=>$points);
		}
	}
}