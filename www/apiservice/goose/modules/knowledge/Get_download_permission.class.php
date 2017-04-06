<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/5
 * Time: 19:46
 * 以后下载规范、工艺等文档需要权限（可能包括积分、下载次数等控制，先留下这个接口，以后补充条件）
 */

namespace Goose\Modules\Knowledge;

use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Knowledge\DBKnowledge;
use \Goose\Package\Knowledge\Helper\Errorcode_Model_Knowledge;


class Get_download_permission extends \Goose\Libs\Wmodule{

	public function run()
	{
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:"";//类型，是工艺还是规范，还可能更多
		$book_id = isset($_REQUEST['book_id'])?$_REQUEST['book_id']:"";//需要下载的书id
		$uid = intval($this->session->uid);
		$cli_id = $this->session->cltid;
		$version = $this->session->version;

		//解决安卓版本号12957, type应该为3  却传为1的BUG
		if($cli_id == 3 && $version == 12957 && $type == 1)
		{
			$type = 3;
		}
		
		if($type == 3)
		{
			$this->response->make_json_ok();
			return;
		}

		if(empty($type)||empty($book_id))
		{
			//参数为空
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_WRONG_PARAM));
		}
		else
		{
			if(DBKnowledge::instatnce()->is_exist_user($uid))
			{
				//判断token是否失效
				if($this->session->checkToken())
				{
					//后期需要添加的各种判断下载权限的代码
					//1、是否通过项目认证，针对工艺、规范整本，章节不做限制
					if($type == 1 || $type == 2)
					{
						if(DBKnowledge::instatnce()->is_pass_prj_member($uid))
						{
							$this->response->make_json_ok();
						}
						else
						{
							$this->response->make_json_response(intval(Errorcode_Model_Knowledge::ERROR_REJECT_PROJECT),'项目认证未通过');
						}
					}
					else
					{
						$this->response->make_json_ok();
					}
				}
				else
				{
					$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE), 'token失效');
				}
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_NOT_EXIST), '用户不存在');
				$this->app->log->log('Knowledge' , array('用户不存在' => array('uin'=>$uid)));
			}
		}
	}

} 
