<?php

//			$url="http://sms.glodon.com/SMSService.asmx?WSDL";
//			$charset="utf-8";
//	         $code = mt_rand(1000,9999);//四位数验证码
//			$param=array(
//				'mobile'=> "15201189594",//手机号
//				'text'=>  "验证码：".$code."，欢迎加入【筑友】大家庭！请在1小时内完成验证。请勿向任何人提供该验证码",
//				'username'=> "gld_wx", //用户名
//				'password'=> "gld_wx20150713", //密码
//			);
//
//			$client = new SoapClient($url);
//			$client->soap_defencoding = $charset;
//			$client->decode_utf8 = false;
//			$client->xml_encoding = $charset;
//			$res = $client->SendSms($param);
//			print_r($res->SendSmsResult);

	$phone_number = '15201189594';
	$val = substr($phone_number, -4);
	if(intval($val)%2 === 0)//手机号尾号为偶数，用安信捷公司短信通道
	{
		$type_sms = 1;
	}
	echo $type_sms;
	$text = '验证码：3140,%20欢迎加入筑友大家庭！请在1小时内完成验证。请勿向任何人提供该验证码【筑友】';
	$url = 'http://111.13.56.193:9007/axj_http_server/sms?name=gld222&pass=gld888&mobiles=%s&content=%s';
	$url = sprintf($url, $phone_number, $text);
	echo $url;

	send_request_get($url);


	//发送http get方式请求
	function send_request_get($url)
	{
		if(empty($url)) exit;

		//初始化
		$ch = curl_init();

		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		//执行并获取HTML文档内容
		$output = curl_exec($ch);

		//释放curl句柄
		curl_close($ch);
	}
?>

