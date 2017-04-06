<?php
	/**
	 * 电话接听回调
	 */
	namespace Goose\Modules\Call;

	use \Goose\Package\Call\DBUserCallFlowLog_Manager;
	use \Goose\Package\Call\DBUserFreeTimeLog_Manager;

	class CallEstablish extends \Goose\Libs\Wmodule {
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
			//修改通话流水,已接听状态
			$flow = array();
			$flow['f_is_connect'] = 1;
			$result = DBUserCallFlowLog_Manager::instance()->update($data['callSid'], $flow);

			echo $this->buildRet();
		}

		private function callback() {
			$data = simplexml_load_string(trim(file_get_contents("php://input"), " \t\n\r"));
			return array(
					'orderid' => (string)$data->orderid,
					'callSid' => (string)$data->callSid,
					'starttime' => (string)$data->starttime
			);
		}

		private function buildRet() {
			return "<?xml version='1.0' encoding='utf-8'?>
		              <Response>
		              <statuscode>0000</statuscode>
		              <statusmsg>success</statusmsg>
		              <billdata>billdata</billdata>
		              <sessiontime>30</sessiontime>
		            </Response>";
		}
	}