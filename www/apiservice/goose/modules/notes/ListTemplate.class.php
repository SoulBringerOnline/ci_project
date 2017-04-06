<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotesTemplate_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class ListTemplate extends \Goose\Libs\Wmodule {
		public function run() {
			if ($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				$template = DBNotesTemplate_Manager::instance()->listTemplate($uid);
				if(!$template){
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_FIND_NOTESTEMPLATE));
				}

				$this->response->make_json_ok("", array('template'=>$template));return;
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
				return;
			}
		}
	}