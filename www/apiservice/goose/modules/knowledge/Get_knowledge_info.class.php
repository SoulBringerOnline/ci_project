<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/10/30
 * Time: 10:11
 */

namespace Goose\Modules\Knowledge;

use \Goose\Package\Knowledge\DBKnowledge;
use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Libs\Util\Util;
use \Goose\Package\User\User;

class Get_knowledge_info extends \Goose\Libs\Wmodule{
	public function run() {
		$uid = $this->session->uid;
		$catalog_id = isset($_REQUEST['catalog_id'])?$_REQUEST['catalog_id']:"";
		if(empty($catalog_id))
		{
			//参数为空
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
		}
		else
		{
			//判断token是否失效
			if($this->session->checkToken())
			{
				$data = DBKnowledge::instatnce()->get_knowledge_info($catalog_id);
				$this->response->make_json_ok("", $data);
				//通知积分系统，给制定uid加分
				if(!empty($uid))
				{
					  // 老的方法
					$url = API_URL."mq/sendmsg/2306/".$uid;
					Util::send_request_get($url);
					/*
					$user = User::instance();
					$baseInfo = $user->getUserBaseInfo($uid);
					$cmd = 2306;
					$user->addUserPointTask($cmd, $baseInfo);
					*/
				}
				else
				{
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_HEADER_FAILURE));
				}
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
			}
		}
	}
} 