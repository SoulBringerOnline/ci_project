<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotes_Manager;
	use \Goose\Package\Notes\DBNotesShare_Manager;
	use \Goose\Package\User\DBUser_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class Viewshare extends \Goose\Libs\Wmodule {
		public function run() {
			if ($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				$shareId = $_GET['share_id'];
				if(!$shareId){
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				$share = DBNotesShare_Manager::instance()->get(array('f_share_id'=>$shareId));
				if(!$share){
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_SHARE_NOT_EXIST));return;
				}
				if($share['uin'] == $uid){
					$return = $share;
				}else if($share['share_type'] == 1){
					$check = DBUser_Manager::instance()->checkGroup($uid, $share['share_uin']);
					if(!$check){
						$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_GROUP_INFO));return;
					}
					$return = $share;
				}else if($share['share_type'] == 2){
					$check = DBUser_Manager::instance()->checkProject($uid, $share['share_uin']);
					if(!$check){
						$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_PROJECT_INFO));return;
					}
					$return = $share;
				}else if($share['share_type'] == 3) {
					if($share['share_uin'] != $uid){
						$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_FRIEND_INFO));return;
					}
					$return = $share;
				} else {
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_SHARE_NOT_EXIST));return;
				}
				
				//成功
				$this->response->make_json_ok("", $return);return;
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
				return;
			}
		}
	}