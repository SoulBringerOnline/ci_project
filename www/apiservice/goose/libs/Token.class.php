<?php
namespace Goose\Libs;
use \Libs\Redis\Redis;

class Token {
	public function _create($uid, $cltid) {
		list($usec, $sec) = explode(" ", microtime());
		$micro = floor(((float)$usec + (float)$sec) * 1000);
		$token = substr(md5($micro . $uid . $cltid), 0, 16);
		$key = self::getTokenKey($uid, $cltid);
		Redis::setex($key, 86400, $token);
		
		return $token;
	}
	
	public function _refresh($uid, $cltid) {
		list($usec, $sec) = explode(" ", microtime());
		$micro = floor(((float)$usec + (float)$sec) * 1000);
		$refresh = substr(md5(($micro + 100) . $uid . $cltid), 0, 16);
		$key = self::getRefreshKey($uid, $cltid);
		Redis::setex($key, 86400*30, $refresh);
		
		return $refresh;
	}
	
	public function check_refresh_token($uid, $cltid, $refresh_token) {
		$key = self::getRefreshKey($uid, $cltid);
		$storeage_refresh_token = Redis::get($key);
		if ($storeage_refresh_token && ($refresh_token == $storeage_refresh_token)) {
			return true;
		}
		
		return false;
	}
	
	static public function get($uid, $cltid) {
		$ret = array(
			'token' => self::_create($uid, $cltid),
			'refresh_token' => self::_refresh($uid, $cltid)
		);
		return $ret;
	}
	
	static public function refresh($uid, $cltid, $refresh_token) {
		$check = self::check_refresh_token($uid, $cltid, $refresh_token);
		if ($check) {
			return self::get($uid, $cltid);
		}
		
		return false;
	}
	
	private function getTokenKey($uid, $cltid) {
		return "token_" . $uid . "_" . $cltid;
	}
	
	private function getRefreshKey($uid, $cltid) {
		return "refresh_" . $uid . "_" . $cltid;
	}
}
