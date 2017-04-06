<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotes_Manager;
	use \Goose\Package\Notes\DBNotesShare_Manager;
	use \Goose\Package\User\DBUser_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class Share extends \Goose\Libs\Wmodule {
		public function run() {
			if ($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				$notesId = $_POST['notes_id'];
				if(!$notesId){
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				$notes = DBNotes_Manager::instance()->get(array('f_uin'=>$uid, 'f_notes_id'=>$notesId));
				if(!$notes){
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_NOTES_NOT_EXIST));return;
				}
				$type = intval($_POST['type']);
				if(!in_array($type, array(1, 2, 3))){
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				$shareUin = $_POST['share_uin'];
				if(!$shareUin){
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				if($type == 1){
					//查看用户是否在该群组
					$check = DBUser_Manager::instance()->checkGroup($uid, $shareUin);
					if(!$check){
						$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_GROUP_INFO));return;
					}
				}elseif($type == 2){
					//查看用户是否在该项目组
					$check = DBUser_Manager::instance()->checkProject($uid, $shareUin);
					if(!$check){
						$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_PROJECT_INFO));return;
					}
				}elseif($type == 3){
					//查看用户是否与对方好友关系
					$check = DBUser_Manager::instance()->checkFriend($uid, $shareUin);
					if(!$check){
						$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_FRIEND_INFO));return;
					}
				}
				//添加到分享
				$columns = array();
				$columns['f_share_id'] = $this->shareId();
				$columns['f_uin'] = $uid;
				$columns['f_share_uin'] = $shareUin;
				$columns['f_share_type'] = $type;
				$columns['f_type'] = $notes['f_type'];
				$columns['f_content'] = $notes['f_content'];
				$columns['f_images'] = $notes['f_images'];
				$columns['f_voice'] = $notes['f_voice'];
				$columns['f_last_time'] = $notes['f_last_time'];
				$columns['f_create_time'] = $notes['f_create_time'];
				$columns['f_share_time'] = time();
				$result = DBNotesShare_Manager::instance()->add($columns);
				if(!$result){
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_ADD_SHARE));return;
				}

				$return = array();
				$return['share_id'] = $columns['f_share_id'];
				$return['content'] = $columns['f_content'];
				$return['images'] = $columns['f_images'];
				$return['voice'] = $columns['f_voice'];
				$return['share_time'] = $columns['f_share_time'];
				//成功
				$this->response->make_json_ok("", $return);return;
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
				return;
			}
		}

		private function shareId() {
			return $this->session->uid.mt_rand(1000,9999).uniqid().mt_rand(1000, 9999);
		}
	}