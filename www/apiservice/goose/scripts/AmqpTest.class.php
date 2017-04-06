<?php

namespace Goose\Scripts;

use \Libs\Mq\AmqpManager;
use \Goose\Package\User\User;
class AmqpTest extends \Frame\Script {

	public function run() {
		/*
		$user =User::instance();

		$uin = 437832;
		$baseInfo = $user->getUserBaseInfo($uin);
		var_dump($baseInfo);

		$cmd = 3972;
		$user->addUserPointTask($cmd, $baseInfo);
		*/
		//$jsonArr = array("uin"=>12345,"time"=>121423142);
		//AmqpManager::instance()->sendMsgToExchange('gsk-mq-exchange', 'gsk_register_key',json_encode($jsonArr));

		$user =User::instance();

		$uin = 437832;
		$baseInfo = $user->getUserBaseInfo($uin);
		var_dump($baseInfo);

		$cmd = 3972;
		$user->addUserPointTask($cmd, $baseInfo);
	}
}