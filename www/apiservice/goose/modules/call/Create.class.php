<?php
	namespace Goose\Modules\Call;

	use \Goose\Package\Call\DBProjectUserFreeTellTime_Manager;
	use \Goose\Package\Call\DBUserFreeTimeLog_Manager;
	use \Goose\Package\Call\DBUserCallFlowLog_Manager;
	use \Goose\Package\User\DBUser_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Call\Helper\Errorcode_Model_Call;
	use \Goose\Libs\Call\Call as RongLian;

	class Create extends \Goose\Libs\Wmodule {
		public function run() {
			if($this->session->checkToken())
			{
				$friend_uin = intval($_REQUEST['friend_uin']);
				if(! $friend_uin){
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				$uid = intval($this->session->uid);
				//获取用户信息
				$user = DBUser_Manager::instance()->get_fields(array('f_uin'=>$uid), array('f_phone', 'f_name'));
				//获取主叫手机号
				$from = $user['f_phone'];
				if(! $from){
					$this->response->make_json_response(intval(Errorcode_Model_Call::EMPTY_FROM_PHONE));return;
				}
				//获取被叫用户信息
				$friend = DBUser_Manager::instance()->get_fields(array('f_uin'=>$friend_uin), array('f_phone', 'f_name'));
				//获取主叫手机号
				$to = $friend['f_phone'];
				if(! $to){
					$this->response->make_json_response(intval(Errorcode_Model_Call::EMPTY_TO_PHONE));return;
				}
				//获取用户通话时间
				$time = DBProjectUserFreeTellTime_Manager::instance()->get_call_time($uid);
				if($time <= 0){
					$this->response->make_json_response(intval(Errorcode_Model_Call::EMPTY_CALL_TIME));return;
				}
				//发送到容云
				$params = array();
				$params['from'] = $from;
				$params['to'] = $to;
				//$params['customerSerNum'] = $from;
				$params['fromSerNum'] = "01056616689";
				$params['maxCallTime'] = $time*60;
				$params['userData'] = $uid;
                $callResult = RongLian::callback($params);
				if($callResult['error_code'] != 0){
					$this->response->make_json_response(intval(ErrorCode_Model_Call::ERROR_RONGLIAN_REQUEST));
					$this->app->log->log('CALL', array('[ERROR]'=>$callResult['msg']));return;
				}
				//添加消费流水
				$price = DBProjectUserFreeTellTime_Manager::instance()->get_call_price($uid);
				$flow = array();
				$flow['f_uin'] = new \MongoInt32($uid);
				$flow['f_type'] = new \MongoInt32(1);
				$flow['f_call_id'] = $callResult['result']['callSid'];
				$flow['f_price'] = $price? $price: 0.00;
				$flow['f_group'] = $uid."_".$friend_uin;
				$flow['f_status'] = new \MongoInt32(0);
				$flow['f_create_time'] = new \MongoInt64($this->microtime_float());
				$result = DBUserFreeTimeLog_Manager::instance()->add($flow);
				//流水写入失败，挂断电话
				if(! $result){
					RongLian::CallCancel($callResult['result']['callSid'], 0);
					$this->response->make_json_response(intval(ErrorCode_Model_Call::FAILED_WRITE_STATUS_LOG));
					$this->app->log->log('CAL_CREATE', array('[ERROR]'=>"创建通话状态日志写入失败"));return;
				}
				//添加通话流水
				$flow = array();
				$flow['f_uin'] =  new \MongoInt32($uid);
				$flow['f_to_uin'] =  new \MongoInt32($friend_uin);
				$flow['f_phone'] =  $from;
				$flow['f_to_phone'] =  $to;
				$flow['f_name'] = $user['f_name'];
				$flow['f_to_name'] = $friend['f_name'];
				$flow['f_call_id'] = $callResult['result']['callSid'];
				$flow['f_duration'] =  new \MongoInt32(0);
				$flow['f_is_connect'] =  0;
				$flow['f_status'] =  new \MongoInt32(0);
				$flow['f_create_time'] =  new \MongoInt64($this->microtime_float());
				$result = DBUserCallFlowLog_Manager::instance()->add($flow);

				$this->response->make_json_ok();return;
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));return;
			}

		}

		private  function microtime_float() {
			list($usec, $sec) = explode(" ", microtime());
			return floor(((float)$usec + (float)$sec) * 1000);
		}
	}
