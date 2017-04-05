<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/9
 * Time: 13:50
 */
	$prj_id = '70d0b514992a49f5a272543045917ed6';
	$uin = '100529';
	$reason = '1234567890我们都是好孩子的分组1234567890我们都是好孩子的分组1234567890我们都是好孩子的分组1、2） =？！。我们都是好孩子的分组1234567890我们都是好孩子的';
	$reason = rawurlencode($reason);
//	echo $reason;
	$url_offline_xiaomishu_fail ='http://192.168.164.200:5000/send_msg/prj_auth_fail/%s/&&cardatctionscheme|%s|&&cardfinishactionscheme|%s|&&carddesc|%s/';
	$url_offline_xiaomishu_fail = sprintf($url_offline_xiaomishu_fail, $uin, $prj_id, $prj_id, $reason);
//	$url_offline_xiaomishu_fail = rawurlencode($url_offline_xiaomishu_fail);
	send_request_get($url_offline_xiaomishu_fail);
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