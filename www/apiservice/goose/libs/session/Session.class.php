<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/10/29
 * Time: 14:43
 */
namespace Goose\Libs\Session;
use \Libs\Redis\Redis;

class Session {
	private $app = null;
	private static $_session = NULL;
	public function __construct($app) {
		$this->app = $app;
	}

	public function __destruct() {

	}

	public function checkToken() {
		$zhuyou = $this->app->request->ZHUYOU;
		$cltid = isset($zhuyou['clt']) ? $zhuyou['clt'] : "";
		$uid =  isset($zhuyou['u']) ? $zhuyou['u'] : "";
		$token =  isset($zhuyou['token']) ? $zhuyou['token'] : "";

		$token = empty($token) && isset($this->app->request->headers['Token'])? $this->app->request->headers['Token']:$token;
		$this->app->log->log("token", array("token"=>$token));
		if(empty($uid) || empty($token)) {
			// token 验证失败
			return FALSE;
		}
		else {
			// 去redis 验证token的正确性
			$redistoken = Redis::get($this->getTokenKey($uid, $cltid));
			$this->app->log->log("token", array($uid, $cltid, $redistoken, $this->getTokenKey($uid, $cltid)));
			if(!empty($redistoken) && $redistoken == $token) {
				return TRUE;
			}
			return FALSE;
		}
		return FALSE;
	}

	private function getTokenKey($uid, $cltid) {
		return "token_" . $uid . "_" . $cltid;
	}

	public function __get($arg) {
		if(!empty(self::$_session) && isset(self::$_session[$arg])) {
			return self::$_session[$arg];
		}
		$this->loadSession();
		return self::$_session[$arg];
	}

	private function loadSession() {
		$zhuyou = $this->app->request->ZHUYOU;
		$cltid = isset($zhuyou['clt']) ? $zhuyou['clt'] : "";
		$uid =  isset($zhuyou['u']) ? $zhuyou['u'] : "";
		$token =  isset($zhuyou['token']) ? $zhuyou['token'] : "";
		$version =  isset($zhuyou['v']) ? $zhuyou['v'] : "";

		$token = empty($token)? $this->app->request->headers['Token']:$token;
		$uid = empty($uid)? $this->app->request->headers['Uid']:$uid;
		self::$_session['uid']= $uid;
		self::$_session['cltid']= $cltid;
		self::$_session['token']= $token;
		self::$_session['version']= $version;
	}
}