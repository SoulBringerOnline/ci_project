<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/11/9
 * Time: 20:06
 */
namespace Libs\Mq;

class AmqpManager {
	static $amqps=array();

	private $mq_name = '';
	private $amqp_holder = NULL;

	private function __construct($mq) {
		$this->mq_name = $mq;
		$config = self::getConfig();
		$host = $config[$mq]['host'];
		$port = $config[$mq]['port'];
		$user = $config[$mq]['user'];
		$passwd = $config[$mq]['passwd'];
		$vhost = $config[$mq]['vhost'];

		$this->amqp_holder = new \Libs\Mq\AmqpHolder($host, $port, $user, $passwd, $vhost);
	}

	public function sendMsgToExchange($exchange, $routeKey, $data) {
		$ex = $this->amqp_holder->getExchange($exchange);
		$ex->publish($data, $routeKey);
		return true;
	}

	public function getMsgFromQueue($exchange, $routeKey, $queue , $callback, $ack_type) {
		$queue = $this->amqp_holder->getQueue($queue );
		$queue->declare();
		$queue->bind($exchange,$routeKey);

		if(!empty($ack_type)) {
			$queue->consume($callback, $ack_type);
		}
		else {
			$queue->consume($callback);
		}

	}

	public static function instance($mq=0) {
		if(!isset(self::$amqps[$mq])) {
			self::$amqps[$mq] = new AmqpManager($mq);
		}

		return self::$amqps[$mq];
	}


	protected static function getConfig() {
		$config = \Frame\ConfigFilter::instance()->getConfig('amqp');
		return $config;
	}
}