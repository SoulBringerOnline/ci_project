<?php
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL&~E_NOTICE);
	date_default_timezone_set('PRC');
	date_default_timezone_set('Asia/Beijing');
    
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

	//MongoDB连接
	$connect = new MongoClient($config['mongodb_spider']);
	$mongo_ol = new MongoDB($connect, 'gsk_ol');

	//每月15号复制本月套餐给下月，如果下月套餐已存在，则忽略
	$dateid = intval(date("Ym")); //本月dateid
	$nextDateid = intval(date("Ym", strtotime("+1 month"))); //下月dateid
	$where = array(
			'f_date_id' => $nextDateid,
	);
	$result = $mongo_ol->callComboSetting->findOne($where);
	if(!$result){
		$where = array(
				'f_date_id' => $dateid
		);
		$data = $mongo_ol->callComboSetting->findOne($where);
		$columns = array();
		$columns['f_date_id'] = new MongoInt32($nextDateid);
		$columns['f_grade'] = intval($data['f_grade']);
		$columns['f_price'] = floatval($data['f_price']);
		$columns['f_deputy_account_minus'] = floatval($data['f_deputy_account_minus']);
		$columns['f_min_cost'] = intval($data['f_min_cost']);
		$columns['f_create_time'] = time();

		$mongo_ol->callComboSetting->insert($columns);
	}



