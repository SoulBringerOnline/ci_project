<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotesTemplate_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class CreateTemplate extends \Goose\Libs\Wmodule {
		public function run() {
			if ($this->session->checkToken()) {
				$uid = intval($this->session->uid);//$uid = 111;
				$title = isset($_POST['title'])? trim($_POST['title']): null;
				if (!$title) {
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}
				$sort = isset($_POST['sort'])? trim($_POST['sort']): 0;
				if (!$sort) {
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
				}

				$template = array();
				$template['f_template_id'] = uniqid($uid);
				$template['f_uin'] = $uid;
				$template['f_title'] = $title;
				$template['f_sort'] = $sort;
				$template['f_create_time'] = time();
				$result = DBNotesTemplate_Manager::instance()->create($template);
				if (false === $result) {
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_CREATE_NOTESTEMPLATE));

					return;
				}

				$this->response->make_json_ok("", array('template_id'=>$template['f_template_id']));
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));

				return;
			}
		}
	}