<?php
	namespace Goose\Modules\Call;

	use \Goose\Package\Call\DBUserFreeTimeLog_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use Goose\Package\Call\Helper\Errorcode_Model_Call;
	use Goose\Libs\Call\Call as RongLian;

	class Cancel extends \Goose\Libs\Wmodule {
		public function run() {
			if($this->session->checkToken())
			{
				$uid = intval($this->session->uid);
				//获取用户创建的链接日志
				$call_sid = DBUserFreeTimeLog_Manager::instance()->get_recent_call_sid(array('f_uin'=>$uid, 'f_status'=>0));
				if(! $call_sid){
					$this->response->make_json_ok();return;
				}

				$result = RongLian::CallCancel($call_sid, 0);
				if($result['error_code'] != 0){
					//日志写入
					$result = DBUserCallStatusLog_Manager::instance()->update(array('f_call_sid'=>$call_sid), array('f_status'=>5));
					if(! $result){
						$this->app->log->log('CALL_CANCEL', array('[ERROR]'=>"取消通话状态日志写入失败"));
					}
					$this->response->make_json_response(intval(Errorcode_Model_Call::ERROR_CANCEL_FAILED));return;
				}

				$this->response->make_json_ok();return;
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));return;
			}
		}
	}