<?php
namespace Goose\Libs\Call;

use Goose\Libs\Call\Sdk\Rest;

class Call {
	//主帐号
	public $accountSid;

	//主帐号Token
	public $accountToken;

	//子帐号
	public $subAccountSid;

	//子帐号Token
	public $subAccountToken;

	//VoIP帐号
	public $voIPAccount;

	//VoIP密码
	public $voIPPassword;

	//应用Id
	public $appId;

	//Rest实例
	public $rest;

	public function __construct($app="zhuyou") {
		$config = \Frame\ConfigFilter::instance()->getConfig('call');
		$serverIP = $config[$app]['base_url'];
		$serverPort = $config[$app]['port'];
		$softVersion = $config[$app]['version'];

		$this->rest = new Rest($serverIP,$serverPort,$softVersion);
		$this->accountSid = $config[$app]['account_sid'];
		$this->accountToken = $config[$app]['account_token'];
		$this->appId = $config[$app]['app_id'];
		$this->subAccountSid = $config[$app]['sub_account_sid'];
		$this->subAccountToken = $config[$app]['sub_account_token'];
		$this->voIPAccount = $config[$app]['vo_ip_account'];
		$this->voIPPassword = $config[$app]['account_token'];
	}

	/**
	 * 双向回呼
	 * @param from 主叫电话号码
	 * @param to 被叫电话号码
	 * @param customerSerNum 被叫侧显示的客服号码
	 * @param fromSerNum 主叫侧显示的号码
	 * @param promptTone 自定义回拨提示音
	 * @param userData 第三方私有数据
	 * @param maxCallTime 最大通话时长
	 * @param hangupCdrUrl 实时话单通知地址
	 * @param alwaysPlay 是否一直播放提示音
	 * @param terminalDtmf 用于终止播放promptTone参数定义的提示音
	 * @param needBothCdr 是否给主被叫发送话单
	 * @param needRecord 是否录音
	 * @param $countDownTime 设置倒计时时间
	 * @param countDownPrompt 倒计时时间到后播放的提示音
	 */
	static public function callback($params) {
		$from = $params['from'];
		$to = $params['to'];
		$fromSerNum = $params['fromSerNum'];
		$maxCallTime = $params['maxCallTime'];
		$userData = isset($params['userData'])? $params['userData']: null;
		$promptTone = null;
		$alwaysPlay = true;
		$terminalDtmf = null;
		$hangupCdrUrl = null;
		$needBothCdr = false;
		$needRecord = false;
		$countDownTime = 100;
		$countDownPrompt = null;

		$_self = new self("zhuyou");
		//设置子账户信息
		$_self->rest->setSubAccount($_self->subAccountSid, $_self->subAccountToken, $_self->voIPAccount, $_self->voIPPassword);
		$_self->rest->setAppId($_self->appId);

		// 调用回拨接口
		$result = $_self->rest->callBack($from,$to,$fromSerNum,$promptTone,$alwaysPlay,$terminalDtmf,$userData,$maxCallTime,$hangupCdrUrl,$needBothCdr,$needRecord,$countDownTime,$countDownPrompt);

		if($result == NULL ) {
			$ret = array(
				'error_code' => 1,
				'error_message' => "unknown error",
				'error_status_code' => null
			);
		}
		if($result->statusCode!=0) {
			$ret = array(
				'error_code' => 1,
				'error_message' => (string)$result->statusMsg,
				'error_status_code' => (string)$result->statusCode
			);
		} else {
			// 获取返回信息
			$callback = $result->CallBack;
			$ret = array(
				'error_code' => 0,
				'result' => self::ObjectToArray($callback)
			);
		}

		return $ret;
	}

	/**
	 * 创建子帐号
	 * @param friendlyName 子帐号名称
	 */
	static function createSubAccount($friendlyName) {
		// 初始化REST SDK
		$_self = new self("zhuyou");
		$_self->rest->setAccount($_self->accountSid, $_self->accountToken);
		$_self->rest->setAppId($_self->appId);

		// 调用云通讯平台的创建子帐号,绑定您的子帐号名称
		$result = $_self->rest->CreateSubAccount($friendlyName);
		if($result == NULL ) {
			$ret = array(
					'error_code' => 1,
					'error_message' => "unknown error",
					'error_status_code' => null
			);
		}
		if($result->statusCode!=0) {
			$ret = array(
					'error_code' => 1,
					'error_message' => (string)$result->statusMsg,
					'error_status_code' => (string)$result->statusCode
			);
		} else {
			// 获取返回信息
			$subAccount = $result->SubAccount;
			$ret = array(
					'error_code' => 0,
					'result' => $SubAccount
			);
		}

		return $ret;
	}

	/**
	 * 取消回拨
	 * @param callSid 一个由32个字符组成的电话唯一标识符
	 * @param type   0： 任意时间都可以挂断电话；1 ：被叫应答前可以挂断电话，其他时段返回错误代码；2： 主叫应答前可以挂断电话，其他时段返回错误代码；默认值为0。
	 */
	static function CallCancel($callSid, $type) {
		// 初始化REST SDK
		$_self = new self("zhuyou");
		$_self->rest->setSubAccount($_self->subAccountSid, $_self->subAccountToken, $_self->voIPAccount, $_self->voIPPassword);
		$_self->rest->setAppId($_self->appId);

		// 调用取消回拨接口
		$result = $_self->rest->CallCancel($callSid, $type);
		if($result == NULL ) {
			$ret = array(
					'error_code' => 1,
					'error_message' => "unknown error",
					'error_status_code' => null
			);
		}
		if($result->statusCode!=0) {
			$ret = array(
					'error_code' => 1,
					'error_message' => (string)$result->statusMsg,
					'error_status_code' => (string)$result->statusCode
			);
		} else {
			// 获取返回信息
			$ret = array('error_code' => 0);
		}

		return $ret;
	}

	/**
	 * 呼叫结果查询
	 * @param callSid 呼叫Id
	 */
    static function CallResult($callSid)
	{
		// 初始化REST SDK
		$_self = new self("zhuyou");
		$_self->rest->setAccount($_self->accountSid, $_self->accountToken);
		$_self->rest->setAppId($_self->appId);

		// 调用呼叫结果查询接口
		$result = $_self->rest->CallResult($callSid);
		if($result == NULL ) {
			$ret = array(
					'error_code' => 1,
					'error_message' => "unknown error",
					'error_status_code' => null
			);
		}
		if($result->statusCode!=0) {
			$ret = array(
					'error_code' => 1,
					'error_message' => (string)$result->statusMsg,
					'error_status_code' => (string)$result->statusCode
			);
		} else {
			// 获取返回信息
			$callResult = $result->CallResult;
			$ret = array(
					'error_code' => 0,
					'result' => self::ObjectToArray($callResult)
			);
		}

		return $ret;
	}

	static public function ObjectToArray($o) {
		if(is_object($o)) $o = get_object_vars($o);
		if(is_array($o))
			foreach($o as $k=>$v) $o[$k] = self::ObjectToArray($v);

		return $o;
	}
}