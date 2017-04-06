<?php
	namespace Goose\Modules\Call;

	use \Goose\Package\Call\DBUserCallFlowLog_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;

	class Detail extends \Goose\Libs\Wmodule {
		public function run() {
			if($this->session->checkToken())
			{
				$friend_uin = intval($_REQUEST['friend_uin']);
				if(! $friend_uin){
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				$uid = intval($this->session->uid);
				$list = DBUserCallFlowLog_Manager::instance()->callDetail($uid, $friend_uin);
				$return = array();
				foreach ($list as $key=>$value) {
					if($value['f_uin'] == $uid){
						$type = 1;
					}else{
						$type = 0;
					}
					$return[$key]['uin'] = $value['f_uin'];
					$return[$key]['to_uin'] = $value['f_to_uin'];
					$return[$key]['to_name'] = $value['f_to_name'];
					$return[$key]['duration'] = $value['f_duration'];
					$return[$key]['createtime'] = $value['f_create_time'];
					$return[$key]['type'] = $type;
				}

				$this->response->make_json_ok("", array_values($return));return;
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));return;
			}
		}
	}