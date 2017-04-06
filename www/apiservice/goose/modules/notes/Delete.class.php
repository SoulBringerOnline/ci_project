<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotes_Manager;
	use \Goose\Package\Notes\Search;
	use \Goose\Package\Notes\DBNotesTemplate_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class Delete extends \Goose\Libs\Wmodule {
		public function run() {
			if($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				if(!isset($_POST['notes_id'])){
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				$notesId = trim($_POST['notes_id']);

				$where = array(
					'f_uin' => $uid,
					'f_notes_id' => $notesId
				);
				$result = DBNotes_Manager::instance()->delete($where);
				if(false === $result){
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_DELETE_NOTES));return;
				}
				//刷新索引
				Search::instance()->delete($_POST['notes_id']);

				$this->response->make_json_ok();return;
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));

				return;
			}
		}
	}