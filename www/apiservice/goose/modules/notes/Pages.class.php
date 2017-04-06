<?php
	namespace Goose\Modules\Notes;

	use \Goose\Package\Notes\DBNotes_Manager;
	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Notes\Helper\Errorcode_Model_Notes;

	class Pages extends \Goose\Libs\Wmodule {
		public function run() {
			if ($this->session->checkToken()) {
				$uid = intval($this->session->uid);
				$page = isset($_GET['page'])?intval($_GET['page']): 1;
				$size = isset($_GET['size'])?intval($_GET['size']): 10;
				$type = isset($_GET['type'])?intval($_GET['type']): 0;
				$where = array(
					'f_uin'=>$uid
				);
				if($type){
					$where['f_type'] = $type;
				}
				$list = DBNotes_Manager::instance()->pages($where, $page, $size);
				$this->response->make_json_ok("", $list);return;
			} else {
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
				return;
			}
		}
	}