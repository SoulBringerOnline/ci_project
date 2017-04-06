<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/25
 * Time: 11:31
 */

namespace Goose\Modules\Weixin;

use \Libs\Redis\Redis;
use \Goose\Libs\Util\Util;

class Weixin_share_signature extends \Goose\Libs\Wmodule{

	private $appId;
	private $appSecret;

	public function __construct($app) {
		parent::__construct($app);
		$this->appId = "wx755a176a07f52614";
		$this->appSecret = "385bb22c122ec25ae18b15a54c0de6f5";
	}

	public function run()
	{
		$jsapiTicket = $this->getJsApiTicket();

		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$_SERVER[HTTP_REFERER]";

		$timestamp = time();
		$nonceStr = $this->createNonceStr();

		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

		$signature = sha1($string);

		$signPackage = array(
			"appId"     => $this->appId,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
//			"url"       => $url,
			"signature" => $signature
//			"rawString" => $string
		);
		$this->response->make_json_ok('', array('signPackage'=>$signPackage));
	}

	private function getJsApiTicket() {
		// jsapi_ticket 应该全局存储与更新

		if(Redis::exists($this->appId."_jsapi_ticket"))
		{
			return Redis::get($this->appId."_jsapi_ticket");
		}
		else
		{
			$accessToken = $this->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode(Util::send_request_get($url));
			$ticket = $res->ticket;
			if ($ticket)
			{
				Redis::setex($this->appId."_jsapi_ticket", 7200, $ticket);//key在redis中2小时过期
			}
			return $ticket;
		}

	}

	private function getAccessToken() {
		// access_token 应该全局存储与更新
		if(Redis::exists($this->appId."_access_token"))
		{
			return Redis::get($this->appId."_access_token");
		}
		else
		{
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$res = json_decode(Util::send_request_get($url));
			$access_token = $res->access_token;
			if ($access_token)
			{
				Redis::setex($this->appId."_access_token", 7200, $access_token);//key在redis中2小时过期
			}
			return $access_token;
		}
	}


	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
} 