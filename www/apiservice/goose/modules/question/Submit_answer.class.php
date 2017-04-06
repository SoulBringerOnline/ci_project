<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/10
 * Time: 17:25
 */

namespace Goose\Modules\Question;

use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Question\Help\Errorcode_Model_Question;
use \Goose\Package\Question\DBQuestion_Manager;

class Submit_answer extends \Goose\Libs\Wmodule{

	public function run()
	{
		$content = array();
		//检查token
		if(true)
		{
			$uid = intval($this->session->uid);
			if(DBQuestion_Manager::instance()->is_exist_user($uid))
			{
				if(DBQuestion_Manager::instance()->is_answered($uid))
				{
					$user = DBQuestion_Manager::instance()->get_user_info($uid);
					$content['new_user'] = $user['f_new_user'];
					$content['points'] = $user['f_points'];
					$content['correct_count'] = $user['f_correct_count'];
					$this->response->make_json_ok('你答过题目', $content);
					$this->app->log->log('Has_Answered' , array('uin'=>$uid, 'content'=>$content));

				}
				else
				{
					if(count($_REQUEST) == 0)
					{
						$this->response->make_json_response(intval(Errorcode_Model_Question::ERROR_LACK_ANSWER), '答案为空');
					}
					else
					{
						$data = array();
						$correct_count = 0;
						foreach ($_REQUEST as $k => $v)
						{
							if(strpos($k,"id") === 0) {
								$k = ltrim($k, "id");
								$this->app->log->log("answer", array($k, $v, DBQuestion_Manager::instance()->judge_answer($k, $v)) );
								if (DBQuestion_Manager::instance()->judge_answer($k, $v))
								{
									$correct_count++;
								}
							}
						}
						//新用户：答对1或者2到 20M， 答对3道 100M
						if (DBQuestion_Manager::instance()->is_new_user($uid))
						{
							$content['new_user'] = true;

							if($correct_count == 3)
							{
								$data['f_points'] = new \MongoInt32(100);
							}
							else if($correct_count == 1 || $correct_count ==2)
							{
								$data['f_points'] = new \MongoInt32(20);
							}
						}
						else//老用户3道题全答对才能拿到100M
						{
							$content['new_user'] = false;

							if ($correct_count == 3)
							{
								$data['f_points'] = new \MongoInt32(100);
							}
						}
						$data['f_commit'] = true;
						$data['f_correct_count'] = new \MongoInt32($correct_count);

						$content['correct_count'] = $correct_count;
						$content['points'] = isset($data['f_points'])?$data['f_points']->{'value'}:0;
						$this->response->make_json_ok('', $content);
						$this->app->log->log('Submit_Answer' , array('uin'=>$uid,'req'=>$_REQUEST, 'content'=>$content));
						DBQuestion_Manager::instance()->update_personal_qt($uid, $data);
					}
				}
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_NOT_EXIST), '该用户还没有拉取过问题');
			}
		}
		else
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE), 'token过期');
		}
	}
} 