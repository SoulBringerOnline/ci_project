<?php
	namespace Goose\Modules\Notes;
	
	use \Goose\Package\Notes\DBNotes_Manager;
	use \Goose\Package\Notes\Search as SearchModel;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class Search extends \Goose\Libs\Wmodule {
		public function run() {
			if ($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				$keyword = trim($_GET['keyword']);
				if (! $keyword) {
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));return;
					return;
				}
				$page = empty($_GET['page'])? 1: intval($_GET['page']);
				$size = empty($_GET['size'])? 10: intval($_GET['size']);
				$type = empty($_GET['type'])? 0: intval($_GET['type']);
				$data = SearchModel::instance()->search($uid, $keyword, $type, $page, $size);
				if(false == $data){
					$this->response->make_json_response(intval(Errorcode_Model_Notes::ERROR_SEARCH_RESULT));return;
				}

				$this->response->make_json_ok("", $data);return;
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));

				return;
			}
		}
	}