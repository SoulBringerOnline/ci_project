<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotesTemplate_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class DeleteTemplate extends \Goose\Libs\Wmodule {
		public function run() {
			if($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				if(!isset($_POST['template_id'])){
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				$templateId = trim($_POST['template_id']);

				$where = array(
					'f_uin' => $uid,
					'f_template_id' => $templateId
				);
				$result = DBNotesTemplate_Manager::instance()->delete($where);
				if(false === $result){
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_DELETE_NOTESTEMPLATE));return;
				}

				$this->response->make_json_ok();return;
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));

				return;
			}
		}
	}