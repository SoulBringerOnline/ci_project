<?php
	/**
	 * Created by PhpStorm.
	 * User: lixd-a
	 * Date: 2015/12/22
	 * Time: 10:20
	 */

	namespace Goose\Modules\Huodong;

	use \Goose\Libs\ErrorCode_Model_Common;
	use \Goose\Package\Huodong\DBFirst_freetime_Manager;

	class Pc_huodong_data extends \Goose\Libs\Wmodule {

		public function run() {
			$data = DBFirst_freetime_Manager::instatnce()->pc_activity_pos();

			$this->response->make_json_ok("", $data);return;
		}
	}