<?php
	/**
	 * Created by PhpStorm.
	 * User: lixd-a
	 * Date: 2015/11/24
	 * Time: 17:25
	 */
	namespace Goose\Modules\Test;


	class Point extends \Goose\Libs\Wmodule {

		public function run() {
			$data = array(
				'V1001_MY_OPT' => array('key'=>'V1_BILL_MATERIAL_CREATE', 'type'=>1, 'number'=>10),
				'V1001_USUAL_PROBLEM3' => array('key'=>'V1_BILL_MATERIAL_MY','type'=>1, 'number'=>0),
				'V1001_USUAL_PROBLEM2' => array('key'=>'V1_BILL_MATERIAL_MY_CREATED','type'=>1, 'number'=>2),
				'V1001_USUAL_PROBLEM1' => array('key'=>'V1_BILL_MATERIAL_MY_UNTREATED','type'=>2, 'number'=>2),
				'V1001_USUAL_PROBLEM4' => array('key'=>'V1_BILL_MATERIAL_ALL','type'=>1, 'number'=>0),
				'V1001_USUAL_PROBLEM5' => array('key'=>'V1_BILL_MATERIAL_ALL_IN_STORAGE','type'=>1, 'number'=>10),
				'V1001_FIND_NEW' => array('key'=>'V1_BILL_MATERIAL_ALL_SUBALL','type'=>2, 'number'=>4)
			);

			$this->response->make_json_ok("", array_values($data));
		}
	}