<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/12/29
 * Time: 11:01
 */
	namespace Goose\Scripts;

	use \Libs\Mq\AmqpManager;
	use \Libs\Mongo\MongoDB;
	use \Goose\Libs\Util\Util;
	use \Goose\Package\User\Helper\PbReportReport;
	class AmqpConsumer extends \Frame\Script {

		public function run() {

			$queue = "gsk_test_queue";
			$ack_type = AMQP_AUTOACK;
			//$ack_type = "";
			AmqpManager::instance()->getMsgFromQueue('gsk-mq-exchange', 'gsk_register_key', $queue , array($this, callback ), $ack_type);
		}

		public function callback($envelope, $queue) {
			$msg = $envelope->getBody();
			$foo = new PbReportReport();
			try
			{
				if(!is_null($foo))
				{
					$foo->parseFromString($msg);
					$cmd = $foo->getICmd();
					$uin = $foo->getInfo()->getUin();
					$this->app->log->log('MQ_log' , array('uin'=>$uin, 'cmd'=>$cmd));
					if(!empty($cmd)&&!empty($uin))
					{
						if($cmd == 0x0906)//新用户注册
						{

							$mongo_ol = MongoDB::getMongoDB("online","gsk_ol");
							$user = $mongo_ol->account->findOne(array('f_account_id'=>intval($uin)));
							$phone = $user['f_account_phone'];
							if(!empty($phone))
							{
								$url = API_URL."event/newyear/updateNewUserGameInfo?uin=$uin&phone=$phone";
								Util::send_request_get($url);
							}
						}
					}
				}
			}
			catch (\Exception $ex)
			{
				echo $ex->getMessage();
			}
		}
	}