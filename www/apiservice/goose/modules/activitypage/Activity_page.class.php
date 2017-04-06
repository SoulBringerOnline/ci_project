<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/27
 * Time: 16:37
 */
namespace Goose\Modules\Activitypage;

use  \Goose\Package\Activitypage\DBActivity_page_Manager;
use  \Goose\Libs\ErrorCode_Model_Common;

class Activity_page extends \Goose\Libs\Wmodule{

	public function run()
	{
		$type = $_REQUEST['type'];
//		$url = 'http://zhuyou-p.oss-cn-beijing.aliyuncs.com/test%2F2a1e10aea93a0679202ad4b04a9b2de0';
//		//先添加一张固定路径，以后后台运营做好，通过配置查询
//		$this->response->make_json_ok('', array('img_url'=>$url));
		if(empty($type))
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
		}
		else
		{
			$content = DBActivity_page_Manager::instatnce()->get_activity_page($type);
			$this->response->make_json_ok('', $content);
		}
	}
} 