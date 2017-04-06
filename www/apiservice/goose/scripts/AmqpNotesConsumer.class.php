<?php
	namespace Goose\Scripts;

	use \Libs\Mq\AmqpManager;
	use \Libs\Mongo\MongoDB;
	use \Goose\Libs\Util\Util;
	use \Goose\Package\User\Helper\PbReportReport;
	use \Goose\Package\Notes\DBNotes_Manager;
	use \Goose\Package\Notes\Search;

	class AmqpNotesConsumer extends \Frame\Script {

		public function run() {

			$queue = "gsk_notes_queue";
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
					$this->app->log->log('MQ_log'   , array('uin'=>$uin, 'cmd'=>$cmd));
					if(!empty($cmd)&&!empty($uin))
					{
						if($cmd == 0x0906)//新用户注册
						{
							$mongo_ol = MongoDB::getMongoDB("online","gsk_ol");
							$notes = array();
							$notes['f_uin'] = intval($uin);
							$notes['f_notes_id'] = uniqid($uin);
							$notes['f_weather'] = "未知";
							$notes['f_type'] = 2;
							$notes['f_content'] = "欢迎使用工作随手记，在这里你可以记录自己的工作点滴，并分享给你的筑友好友~";
							$notes['f_search'] = "欢迎使用工作随手记，在这里你可以记录自己的工作点滴，并分享给你的筑友好友~";
							$notes['f_last_time'] = time();
							$notes['f_create_time'] = time();
							$result = DBNotes_Manager::instance()->create($notes);
							if ($result) {
								//创建索引
								Search::instance()->create($notes['f_notes_id']);

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