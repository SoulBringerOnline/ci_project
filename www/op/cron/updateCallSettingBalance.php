<?php
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL&~E_NOTICE);
	date_default_timezone_set('PRC');
	date_default_timezone_set('Asia/Beijing');

	require(__DIR__ . "/../application/libraries/CCPRestSDK.php");
	require(__DIR__ . "/../application/config/config.php");

	function cron_log($message, $grade="ERROR", $params=array()) {
		$message = "TIME:".date("Y-m-d H:i:s".PHP_EOL);
		$message .= "[$grade]:$message".PHP_EOL;
		$message .= "PARAMS:".json_encode($params).PHP_EOL;
		$dirName = "/var/tmp/crontab";
		if(!is_dir($dirName)){
			if(!mkdir($dirName)){
				echo "Failed Create Directory ".$dirName;
			}
		}
		$fileName = $dirName."/crontab_call_".date("Y-m-d").".log";
		error_log($message, 3, $fileName);
	}

	function queryAccountInfo()
	{
		global $config;
		// 初始化REST SDK
		$rest = new CCPRestSDK($config['call_zhuyou']['base_url'],$config['call_zhuyou']['port'],$config['call_zhuyou']['version']);
		$rest->setAccount($config['call_zhuyou']['account_sid'],$config['call_zhuyou']['account_token']);
		$rest->setAppId($config['call_zhuyou']['app_id']);

		// 调用主帐号信息查询接口
		$result = $rest->queryAccountInfo();
		if($result == NULL ) {
			cron_log("result error!");return;
		}
		if($result->statusCode!=0) {
			cron_log("error code :" . $result->statusCode . "error msg :" . $result->statusMsg);return;
		}else{
			// 获取返回信息
			$account = $result->Account;
			$ret = array(
				'balance' => (string)$account->balance,
				'subBalance' => (string)$account->subBalance,
			);

			return $ret;
		}
	}

	$account = queryAccountInfo();

	//MongoDB连接
	$connect = new MongoClient($config['mongodb_spider']);
	$mongo_ol = new MongoDB($connect, 'gsk_ol');

	//修改本月主副账户余额
	$update = array();
	$update['f_balance'] = floatval($account['balance']);
	$update['f_sub_balance'] = floatval($account['subBalance']);

	$dateid = date("Ym");
	$where = array('f_date_id'=>intval($dateid));
	$mongo_ol->callComboSetting->update($where, array('$set'=>$update));



