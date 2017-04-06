<?php
	namespace Goose\Modules\Call;

	class CallAuth extends \Goose\Libs\Wmodule {

		public function run() {
			echo $this->buildRet();
		}

		private function callback() {
			$data = simplexml_load_string(trim(file_get_contents("php://input"), " \t\n\r"));
			return array(
				'orderid' => $data->orderid,
				'callSid' => $data->callSid
			);
		}

		private function buildRet() {
			return "<?xml version='1.0' encoding='utf-8'?>
					<Response>
						<statuscode>0000</statuscode>
						<statusmsg>success</statusmsg>
						<record>1</record>
					</Response>";
		}
	}
