<?php
	/**
	 * 通话记录
	 */
	namespace Goose\Modules\Call;

	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Call\DBUserCallFlowLog_Manager;
	use \Goose\Package\User\DBUser_Manager;

	class Record extends \Goose\Libs\Wmodule {
		public function run() {
			if($this->session->checkToken())
			{
				$uid = intval($this->session->uid);
				$page = isset($_REQUEST['page'])? $_REQUEST['page']: 1;
				$size = isset($_REQUEST['size'])? $_REQUEST['size']: 20;
				$result = DBUserCallFlowLog_Manager::instance()->flowList($uid, $page, $size);
				$return = array();
				foreach ($result['list'] as $key=>$value) {
					if($value['f_uin'] == $uid){
						$type = 1;
						$return['list'][$key]['phone'] = $value['f_to_phone'];
					}else{
						$type = 0;
						$return['list'][$key]['phone'] = $value['f_phone'];
					}
					$return['list'][$key]['uin'] = $value['f_uin'];
					$return['list'][$key]['to_uin'] = $value['f_to_uin'];
					$user = DBUser_Manager::instance()->get_fields(array('f_uin'=>$value['f_to_uin']), array('f_name'));
					$return['list'][$key]['to_name'] = $user['f_name'];
					$user = DBUser_Manager::instance()->get_fields(array('f_uin'=>$value['f_uin']), array('f_name'));
					$return['list'][$key]['from_name'] = $user['f_name'];
					$return['list'][$key]['duration'] = $value['f_duration'];
					$return['list'][$key]['is_connect'] = intval($value['f_is_connect']);
					$return['list'][$key]['createtime'] = $value['f_create_time'];
					$return['list'][$key]['type'] = $type;
				}
				if(empty($return['list'])){
					$return['list'] = array();
				}else{
					$return['list'] = array_values($return['list']);
				}
				$return['page'] = $result['page'];
				$return['size'] = $result['size'];
				$return['total'] = $result['total'];
				$this->response->make_json_ok("", $return);return;
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));return;
			}
		}
	}