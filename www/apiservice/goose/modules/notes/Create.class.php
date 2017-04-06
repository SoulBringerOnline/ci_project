<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotes_Manager;
	use \Goose\Package\Notes\Search;
	use \Goose\Package\Notes\DBNotesTemplate_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class Create extends \Goose\Libs\Wmodule {
		public function run() {
			if ($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				if (!isset($_POST['type'])) {
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));

					return;
				}
				$type = intval($_POST['type']);

				if (!isset($_POST['content'])) {
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
					return;
				}

				if ($type == 1) {
					$content = json_decode($_POST['content'], true);
					if (!$content) {
						$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
						return;
					}
					$search = array();
					foreach ($content as $key => $v) {
						$content[$key]['content'] = urldecode($v['content']);
						$search[] = urldecode($v['content']);
					}
				} else {
					$content = urldecode($_POST['content']);
					$search = urldecode($_POST['content']);
				}
				$images = json_decode($_POST['images'], true);
				$voice = json_decode($_POST['voice'], true);
				$weather = trim($_POST['weather']);

				$notes = array();
				$notes['f_uin'] = $uid;
				$notes['f_notes_id'] = uniqid($uid);
				$notes['f_weather'] = empty($weather) ? "未知" : $weather;
				$notes['f_type'] = $type;
				$notes['f_content'] = $content;
				$notes['f_search'] = $search;
				$notes['f_images'] = $images? $images: array();
				$notes['f_voice'] = $voice? $voice: array();
				$notes['f_last_time'] = time();
				$notes['f_create_time'] = time();
				$result = DBNotes_Manager::instance()->create($notes);
				if (false === $result) {
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_CREATE_NOTES));

					return;
				}

				//创建索引
				Search::instance()->create($notes['f_notes_id']);

				$this->response->make_json_ok("", array('notes_id'=>$notes['f_notes_id']));

				return;
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));

				return;
			}
		}
	}