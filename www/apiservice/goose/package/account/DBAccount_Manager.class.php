<?php
namespace Goose\Package\Account;

use \Libs\Mongo\MongoDB;
use \Libs\Redis\Redis;
use \Goose\Package\Sms\DBSms_Manager;

class DBAccount_Manager {
	private static $intance = NULL;
	private static $KEY_USERID = "USER_ID";
	private $mongo_ol = null;

	private function __construct() {
		$this->mongo_ol = MongoDB::getMongoDB("online", "gsk_ol");
	}

	public static function instance() {
		if (self::$intance === NULL) {
			self::$intance = new self();
		}

		return self::$intance;
	}


	public function get_new_password($where) {
		$account = $this->mongo_ol->account->findOne($where, array('f_account_new_password'));
		if (!$account) {
			return false;
		}

		return $account['f_account_new_password'];
	}

	public function get_user_uin($where) {
		$user = $this->mongo_ol->account->findOne($where, array('f_account_id'));

		return $user['f_account_id'];
	}

	public function check_account($phone, $password) {
		$account = $this->mongo_ol->account->findOne(array('f_account_phone' => $phone, 'f_account_new_password' => $password), array('f_account_id'));
		if ($account) {
			return true;
		}

		return false;
	}

	/**
	 * 判断手机号是否已注册过
	 * @param $phone
	 * @return bool
	 */
	public function is_exist_phone($phone) {
		$account = $this->mongo_ol->account->count(array('f_account_phone' => $phone));

		if ($account == 0) {
			return false;
		} else {
			return true;
		}
	}

	public function get_uin_phone($phone) {
		$account = $this->mongo_ol->account->count(array('f_account_phone' => $phone));
		if ($account === 0) {
			return "";
		} else {
			$user = $this->mongo_ol->account->findOne(array('f_account_phone' => $phone));

			return $user['f_account_id'];
		}
	}

	public function get_new_uid() {
		{
			$uid = Redis::rPop(self::$KEY_USERID);
			if (is_null($uid) || empty($uid)) {
				$uid = 0;
			}
			$uid = intval($uid);
			$count = $this->mongo_ol->account->count(array('f_account_id' => $uid));
		}
		while ($count > 0) ;

		return $uid;
	}

	public function get_SMS_code($key, $phone) {
		$type_sms = 1;
		DBSms_Manager::instance()->update_verify_all($type_sms);
		if (!Redis::exists($key)) {
			return '';
		} else {
			DBSms_Manager::instance()->update_verify_succ($type_sms);
			$data = Redis::get($key);
			$data = explode("&", $data);

			return $data[1];
		}
	}

	/**
	 * 新用户保存到account表
	 * @param $uin 用户id
	 * @param $pwd
	 * @param $phone
	 * @return bool
	 */
	public function save_new_user($uin, $pwd, $phone) {
		$pwd = md5($pwd . md5($uin));// md5(s1,ouid) （db中存储的密码）
		$user = array('f_account_id' => $uin,
			'f_account_phone' => $phone,
			'f_account_new_password' => $pwd,
			'f_account_gly_id' => "",
			'f_account_weixin_id' => "",
			'f_account_qq_id' => "",
			'f_account_create_time' => time() * 1000);
		$this->mongo_ol->account->insert($user);

		return true;
	}

	public function save_new_user_weixin($uin, $openid, $unionid)
	{
		$user = array('f_account_id' => $uin,
			'f_account_phone' => "",
			'f_account_new_password' => "",
			'f_account_gly_id' => "",
			'f_account_weixin_id' => $openid,
			'f_account_qq_id' => "",
			'f_account_weixin_unionid'=>$unionid,
			'f_account_create_time' => time() * 1000);
		$this->mongo_ol->account->insert($user);

		return true;
	}

	public function save_new_user_qq($uin, $openid)
	{
		$user = array('f_account_id' => $uin,
			'f_account_phone' => "",
			'f_account_new_password' => "",
			'f_account_gly_id' => "",
			'f_account_weixin_id' => "",
			'f_account_qq_id' => $openid,
			'f_account_create_time' => time() * 1000);
		$this->mongo_ol->account->insert($user);

		return true;
	}

	public function update_user_pwd($uin, $pwd) {
		$user = array('f_account_new_password' => $pwd,);

		$this->mongo_ol->account->update(array('f_account_id' => intval($uin)), array('$set' => $user));

		return true;
	}

	public function get_user_old_pwd($uin)
	{
		$account = $this->mongo_ol->account->count(array('f_account_id'=>$uin));
		if($account === 0)
		{
			return "";
		}
		else
		{
			$user = $this->mongo_ol->account->findOne(array('f_account_id'=>$uin));
			return $user['f_account_new_password'];
		}
	}

	public function delete_code($key)
	{
		Redis::delete($key);
	}

	public function is_exist_unionid($unionid)
	{
		$uin = 0;
		$user = $this->mongo_ol->account->findOne(array('f_account_weixin_unionid'=>$unionid));

		if(!is_null($user))
		{
			$uin = $user['f_account_id'];
		}
		return $uin;
	}

	public function is_exist_weixin_openid($openid, $unionid)
	{
		$uin = 0;
		$user = $this->mongo_ol->account->findOne(array('f_account_weixin_id'=>$openid));

		if(!is_null($user))
		{
			$uin = $user['f_account_id'];
		}

		if(!empty($uin))
		{
			$this->mongo_ol->account->update(array('f_account_weixin_id'=>$openid), array('$set'=>array('f_account_weixin_unionid'=>$unionid)), array('upsert'=>true));
		}
		return $uin;
	}

	public function is_exist_QQ_openid($openid, $unionid)
	{
		$uin = 0;
		$user = $this->mongo_ol->account->findOne(array('f_account_qq_id'=>$openid));

		if(!is_null($user))
		{
			$uin = $user['f_account_id'];
		}

		if(!empty($uin))
		{
			//修正老版本（IOS）将微信openid存到QQ的openid字段下的错误
			$data = array(
				'f_account_weixin_unionid'=>$unionid,
				'f_account_qq_id'=>"",
				'f_account_weixin_id'=>$openid
			);
			$this->mongo_ol->account->update(array('f_account_qq_id'=>$openid), array('$set'=>$data), array('upsert'=>true));
		}
		return $uin;
	}

	public function get_uin_qq_openid($openid)
	{
		$uin = 0;
		$user = $this->mongo_ol->account->findOne(array('f_account_qq_id'=>$openid));

		if(!is_null($user))
		{
			$uin = $user['f_account_id'];
		}
		return $uin;
	}
}