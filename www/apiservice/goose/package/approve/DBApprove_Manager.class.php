<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/30
 * Time: 11:20
 */
namespace Goose\Package\Approve;

use \Libs\Mongo\MongoDB;

class DBApprove_Manager {

	// 数据处理类C
	private static $intance = NULL;
	private  $mongo_ol =null;
	private function __construct() {
		$this->mongo_ol = MongoDB::getMongoDB("online","gsk_ol");
	}

	public static function instatnce() {
		if(self::$intance === NULL) {
			self::$intance = new self();
		}
		return self::$intance;
	}

	public function get_user_approved_prj($uin)
	{
		$res = array();
		$list = $this->mongo_ol->user->findOne(array('f_uin'=>$uin), array('f_project_list'));
		if(count($list['f_project_list']) > 0)
		{
			foreach($list['f_project_list'] as $item)
			{
				$name = $this->mongo_ol->project->findOne(array('f_prj_id'=>$item['f_prj_id']), array('f_prj_name', 'f_status', 'f_auth_status', 'f_member_count'));
				if($item['f_status'] == true && $name['f_auth_status'] == 3)
				{
					$prj = array();
					$prj['prj_id'] = $item['f_prj_id'];
					$prj['prj_name'] = $name['f_prj_name'];
					$prj['prj_member_count'] = $name['f_member_count'];
					array_push($res, $prj);
				}
			}
		}

		return $res;
	}
} 