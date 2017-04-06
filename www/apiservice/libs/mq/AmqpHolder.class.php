<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/11/9
 * Time: 20:06
 */

namespace Libs\Mq;

class AmqpHolder {
	private $conn=NULL;
	private $channel=NULL;
	private $exchange=NULL;
	private $queue = NULL;

	private $ip;
	private $port;
	private $user;
	private $passwd;
	private $vhost;



	public function __construct($ip, $port, $user, $passwd, $vhost="/") {
		//$conn_args = array('host' => $ip, 'port' => $port, 'login' => $user, 'password' => $passwd, $vhost);
		$this->ip = $ip;
		$this->port = $port;
		$this->user = $user;
		$this->passwd = $passwd;
		$this->vhost = $vhost;

	}

	public function initMq() {
		$conn_args = array('host' => $this->ip, 'port' => $this->port, 'login' => $this->user, 'password' => $this->passwd, 'vhost'=>$this->vhost);
		$this->conn = new \AMQPConnection($conn_args);

		if (!$this->conn->connect()) {
			$this->conn = null;
		}

		return $this->conn;
	}

	public function getChannel() {
		if(empty($this->conn)) {
			$this->initMq();
		}

		if(empty($this->channel)) {
			$this->channel = new \AMQPChannel($this->conn);
		}

		return $this->channel;
	}

	public function getExchange($name, $type = AMQP_EX_TYPE_DIRECT , $flag = AMQP_DURABLE, $option=array()) {
		if(empty($this->channel)) {
			$this->channel = $this->getChannel();
		}

		if(empty($this->exchange)) {
			$this->exchange = new \AMQPExchange($this->channel);
		}

		$this->exchange->setName($name);
		$this->exchange->setType($type);
		$this->exchange->setFlags($flag);
		return $this->exchange;
	}

	public function getQueue($name, $flag = AMQP_DURABLE, $option=array()) {
		if(empty($this->channel)) {
			$this->channel = $this->getChannel();
		}

		if(empty($this->queue)) {
			$this->queue = new \AMQPQueue($this->channel);
		}

		$this->queue->setName($name);
		$this->queue->setFlags($flag);

		return $this->queue;
	}

	public function __destruct() {
		if(!empty($this->conn)) {
			$this->conn->disconnect();
		}
	}
}