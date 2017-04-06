<?php
	require_once('../includes/init.php');
	require_once('../config/cfg_white_user.php');

	$phone_number = $_REQUEST['phone'];
	if(empty($phone_number))
	{
		make_json_response(intval(ErrorCode_Model_VerifySMS::ERROR_LACK_PARAMETER));
	}

	$log = Logger::instance($_CFG['DIR_LOG'] . 'SMScode' , Logger::DEBUG);
	$flag = true;
	$redis_gsk = new Redis();
	$redis_gsk->connect( $_CFG['redis_host'], $_CFG['redis_port'] );

	$type = $_REQUEST['type'];
	$app_id = $_REQUEST['app_id'];
	$cli_id = $_REQUEST['clt'];

	if(trim($_REQUEST['act']) == "sendSms")//请求发送短信验证消息
	{
		$code = mt_rand(1000,9999);//四位数验证码

		$text = $code;
		if($type == "0")
		{
			$text = "验证码：".$code.",%20欢迎加入筑友大家庭！请在1小时内完成验证。请勿向任何人提供该验证码【筑友】";
		}
		else if($type == "1")
		{
			$text = "验证码：".$code.",%20您正在验证筑友，请在1小时内完成验证。请勿向任何人提供该验证码【筑友】";
		}
		else if($type == "2")
		{
			$text = "验证码：".$code.",%20您正在操作筑友绑定手机号！请在1小时内完成验证。请勿向任何人提供该验证码【筑友】";
		}
		try
		{
			if($redis_gsk->exists($phone_number))
			{
				if(!in_array($phone_number, $white_user))
				{
					if(intval($redis_gsk->get($phone_number))<5)
					{
						$redis_gsk->incr($phone_number);
					}
					else
					{
						$flag = false;
						$redis_gsk->close();
						make_json_response(intval(ErrorCode_Model_VerifySMS::ERROR_MT_COUNT));//同一手机号1天内超过5次了，失败
					}
				}
				else
				{
					$redis_gsk->incr($phone_number);
				}
			}
			else
			{
				$redis_gsk->setex($phone_number, 86400, 1);//同一个手机号码一天只能注册5次
			}

			if($flag)
			{
				$url = 'http://111.13.56.193:9007/axj_http_server/sms?name=%s&pass=%s&mobiles=%s&content=%s';
				$url = sprintf($url, "gld333", "gld888", $phone_number, $text);
				send_request_get($url);

				$seq_id = md5(uniqid());
				$key = 'sms-'.$phone_number.'-'.$type.'-'.$app_id.'-'.$cli_id;
				$value = array("seq_id"=>$seq_id, "code"=>$code, "starttime"=>time());
				$redis_gsk->setex($key, 3600, implode("&",$value));//key在redis中1小时过期
				$redis_gsk->close();
				$log->logInfo('[success]' , array('mobile'=>$phone_number, 'text'=> $code, $key=>$value));
				make_json_response(intval(ErrorCode_Model_Common::SUCCESS), "", array('seq_id'=>$seq_id));
			}
		}
		catch(Exception $e)
		{
			$log->logInfo('[ERROR]' , $e);
			$redis_gsk->close();
			make_json_response(intval(ErrorCode_Model_Common::ERROR_UNKNOWN));
		}
	}
	else if(trim($_GET['act']) == "verifySms")//请求验证短信验证码
	{
			$data =array();
			$code = $_REQUEST['verifycode'];
			$key = 'sms-'.$phone_number.'-'.$type.'-'.$app_id.'-'.$cli_id;
			$data[$key] = $code;

			//验证码成功验证后，就会删除，再次使用这个验证码，提示失效
			if(!$redis_gsk->exists($key))
			{
				$redis_gsk->close();
				make_json_response(intval(ErrorCode_Model_VerifySMS::ERROR_SMS_FAILURE));
			}
			$tempdata = $redis_gsk->get($key);
			$data['redis_data'] = explode("&",$tempdata);
			$log->logInfo('[success_verify]' , $data);

			if($data['redis_data'][1] == $code)
			{
				$redis_gsk->delete($key);
				$redis_gsk->close();
				$data['code'] = $code;
				$data['time'] = time() - strtotime($data['redis_data'][2]);//计算用户整个短信验证的操作时间
				make_json_response();

			}
			else
			{
				$redis_gsk->close();
				make_json_response(intval(ErrorCode_Model_Common::FAILD));
			}
	}
	else
	{
		$redis_gsk->close();
		make_json_response(intval(ErrorCode_Model_Common::ERROR_UNKNOWN));
	}
?>
