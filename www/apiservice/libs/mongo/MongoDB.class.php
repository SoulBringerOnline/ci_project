<?php
namespace Libs\Mongo;

class MongoDB {
	protected $database;    // 数据表
	protected static $connections = array();
	protected function __construct($database) {
		$this->connection_op = new \MongoClient( $this->config->item('mongodb_op') );
		$this->mongo_op = new \MongoDB($this->connection_op, 'gsk');
	}

	public static function getConnection($collection) {
		$config = self::getConfig();

		$url = $config["servers"][$collection];
		if (!isset(self::$connections[$url])) {
			self::$connections[$url] = self::connect($url);
		}
		return self::$connections[$url];
	}

	public static function getMongoDB($collection, $database) {
		$connection = self::getConnection($collection);

		$momgodb = new \MongoDB($connection, $database);

		return $momgodb;
	}

	public static function connect($url) {
		return new \MongoClient($url);
	}

	//TODO
	protected static function getConfig() {
		$config = \Frame\ConfigFilter::instance()->getConfig('mongo');
		return $config;
	}

	public static function releaseConns() {
		if(count(self::$connections) > 1) {
			foreach(self::$connections as $key => $conn) {
				$conn->close();
				unset(self::$connections[$key]);
			}
		}

	}
}