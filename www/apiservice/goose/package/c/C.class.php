<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/10/14
 * Time: 11:12
 */
namespace Goose\Package\C;

use \Goose\Package\C\Helper\DBTestC;
use \Libs\Mongo\MongoDB;
use \Libs\Redis\Redis;

class C {
	// 数据处理类C
	private static $intance = NULL;
	private function __construct() {

	}

	public static function instatnce() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	public function getDataFromMongo() {
		$mongo = \Libs\Mongo\MongoDB::getMongoDB("online","gsk");
		$where = array("f_cmd"=>257);
        $rr = $mongo->log->find($where, array() )->limit(10);
		$rr = iterator_to_array($rr);
		return $rr;
	}


	public function getDataFromDB() {
		$sql = "select * from MyClass where 1=1";
		$result = DBTestC::getConn()->read($sql, array());
		return $result;
	}


	public function getDataFromRedis() {
		$key = "we_test";
		Redis::setex($key,300,"hello redis");
	    $ret = Redis::get($key);

		return $ret;
	}
}