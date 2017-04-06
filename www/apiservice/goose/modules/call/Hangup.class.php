<?php
	namespace Goose\Modules\Call;

	use \Goose\Package\Call\DBUserCallFlowLog_Manager;
	use \Goose\Package\Call\DBProjectUserFreeTellTime_Manager;
	use \Goose\Package\Call\DBUserFreeTimeLog_Manager;
	use Goose\Libs\Call\Call as RongLian;

	class Hangup extends \Goose\Libs\Wmodule {
		public function run() {
			$data = $this->callback();
			//获取用户信息
			$user = DBUserFreeTimeLog_Manager::instance()->get_fields(array('f_call_id'=>$data['callSid']), array('f_uin','f_status'));
			if(!$user){
				echo $this->buildRet();return;
			}
			if($user['f_status'] == 1){
				echo $this->buildRet();return;
			}
			$uid = $user['f_uin'];

			//获取用户时间
			$free_time = DBProjectUserFreeTellTime_Manager::instance()->get_call_time($uid);

			//获取用户通话时间
			$callResult = RongLian::callResult($data['callSid']);
			if($callResult['error_code']){
				$this->app->log->log('CALL_HANGUP', array('[ERROR]'=>"MSG:".$callResult['error_message'].PHP_EOL."CODE:".$callResult['error_status_code'], "callSid"=>$data['callSid']));
				echo $this->buildRet();return;
			}

			$duration = $callResult['result']['callTime'];
			$release = ceil($duration / 60);
			//用户时间递减
			if($duration > 0){
				$free_time = ($free_time - $release)<0? 0: ($free_time - $release);
				$update = array();
				$update['f_free_time_num'] = $free_time;
				$update['f_last_update_time'] = $this->microtime_float();

				$result = DBProjectUserFreeTellTime_Manager::instance()->update_call_time($uid, $update);
				if(! $result){
					$this->app->log->log('CALL_HANGUP', array('[ERROR]'=>"用户通话时间更新失败", "uin"=>$uid));
				}
			}

			//修改流水
			$flow = array();
			$flow['f_order_no'] = $data['orderid'];
			$flow['f_free_time_point'] = new \MongoInt32($release);
			$flow['f_status'] = new \MongoInt32(1);
			$result = DBUserFreeTimeLog_Manager::instance()->update($data['callSid'], $flow);
			if(! $result){
				$this->app->log->log('CALL_HANGUP', array('[ERROR]'=>"用户修改流水失败", "callSid"=>$data['callSid']));
			}

			//修改通话流水
			$flow = array();
			$flow['f_duration'] = new \MongoInt32($duration);
			$flow['f_status'] = new \MongoInt32(1);
			$result = DBUserCallFlowLog_Manager::instance()->update($data['callSid'], $flow);

			echo $this->buildRet();
		}

		private function callback() {
			$data = simplexml_load_string(trim(file_get_contents("php://input"), " \t\n\r"));
			return array(
					'orderid' => (string)$data->orderid,
					'callSid' => (string)$data->callSid,
					'starttime' => (string)$data->starttime,
					'endtime' => (string)$data->endtime,
					'talkDuration' => (string)$data->talkDuration
			);
		}

		private function buildRet() {
			return "<?xml version='1.0' encoding='utf-8'?>
		              <Response>
		              <statuscode>0000</statuscode>
		              <statusmsg>状态描述信息</statusmsg>
		              <record>1</record>
		             </Response>";
		}

		private  function microtime_float() {
			list($usec, $sec) = explode(" ", microtime());
			return floor(((float)$usec + (float)$sec) * 1000);
		}
	}