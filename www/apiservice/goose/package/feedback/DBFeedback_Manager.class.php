<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/3
 * Time: 10:45
 */
namespace Goose\Package\Feedback;

use \Libs\Mongo\MongoDB;

class DBFeedback_Manager {

	private static $intance = NULL;
	private  $mongo_op =null;
	private function __construct() {
		$this->mongo_op = MongoDB::getMongoDB("op","gsk");
	}

	public static function instatnce() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	public function save_feedback($content=array())
	{
		if(!is_null($content))
		{
			$this->mongo_op->feedback->insert($content);
		}
	}
} 