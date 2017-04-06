<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotesTemplate_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class UpdateTemplate extends \Goose\Libs\Wmodule {
		public function run() {
			if ($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				$template = isset($_POST['template'])? trim($_POST['template']): null;
				if (!json_decode($template, true)) {
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}

				$where = array(
					'f_uin' => $uid
				);

				$update = array();
				$update['f_template'] = json_decode($template, true);
				$update['f_last_time'] = time();
				$result = DBNotesTemplate_Manager::instance()->update($where, $update);
				if (false === $result) {
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_UPDATE_NOTESTEMPLATE));

					return;
				}

				$this->response->make_json_ok();
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));

				return;
			}
		}
	}