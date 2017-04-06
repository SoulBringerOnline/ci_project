<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/9
 * Time: 20:16
 */
namespace Goose\Modules\Question;

use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Question\DBQuestion_Manager;

class Get_person_question extends \Goose\Libs\Wmodule{

	public function run()
	{
		//判断token是否失效
		if(true)
		{
			$uid = intval($this->session->uid);
			if(DBQuestion_Manager::instance()->is_exist_user($uid))
			{
				$this->get_user_question($uid);
			}
			else
			{
				$this->save_user_question($uid);
			}
		}
		else
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_TOKEN_FAILURE));
		}
	}

	private function get_rand_num($count, $max)
	{
		$rand_num = array();

		while(count($rand_num) < $count)
		{
			$num = rand(0, $max - 1);
			if(!in_array($num, $rand_num))
			{
				array_push($rand_num, $num);
			}
		}

		return $rand_num;
	}

	private function save_user_question($uid)
	{
		$new_user_time_limit = strtotime("2015-11-23 10:00:00");//活动开始时间后，注册的用户就为新用户
		$create_time = DBQuestion_Manager::instance()->get_user_create_time($uid);
		$content= $questions= $questions_id= array();
		if(!empty($create_time))
		{
			$create_time = intval(substr($create_time, 0, -3));
			$knowledge_qt = DBQuestion_Manager::instance()->get_question(1);
			$app_qt = DBQuestion_Manager::instance()->get_question(0);
			$knowledge_qt = array_values($knowledge_qt);
			$app_qt = array_values($app_qt);
			//判断新老用户
			if($create_time >= $new_user_time_limit)//新用户
			{
				$content['new_user'] = true;
			}
			else//老用户
			{
				$content['new_user'] = false;
			}

			//随机产生两道建筑知识问题
			$two_knowledge_qt = $this->get_rand_num(2, count($knowledge_qt));
			for($i=0; $i<count($two_knowledge_qt); $i++)
			{
				$question = array();
				$qt = $knowledge_qt[$two_knowledge_qt[$i]];
				array_push($questions_id, $qt['_id']);
				array_push($questions, $this->question($qt));
			}
			//随机产生一道app问题
			$one_app_qt = $app_qt[rand(0, count($app_qt) - 1)];
			array_push($questions_id, $one_app_qt['_id']);
			array_push($questions, $this->question($one_app_qt));

			DBQuestion_Manager::instance()->save_user_question($uid, $content['new_user'], $questions_id);
			$content['commit'] = false;
			$content['points'] = 0;
			$content['correct_count'] = 0;
			$content['questions'] = $questions;
			$this->response->make_json_ok('', $content);
		}
		else
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_NOT_EXIST));
		}
	}

	private function question($qt =array())
	{
		$question = array();
		if(!is_null($qt))
		{
			$title = $qt['f_question'];
			$answers = array(
				$qt['f_options_A'],
				$qt['f_options_B'],
				$qt['f_options_C'],
				$qt['f_options_D']
			);
			$question['title'] = $title;
			$question['qt_id'] = $qt['_id']->{'$id'};
			$question['answers'] = $answers;
		}
		return $question;
	}

	private function get_user_question($uid)
	{
		$content = array();
		$msg = DBQuestion_Manager::instance()->get_user_qt_info($uid);
		$content['new_user'] = $msg['f_new_user'];
		$content['commit'] = $msg['f_commit'];
		$content['points'] = $msg['f_points'];
		$content['correct_count'] = $msg['f_correct_count'];
		$content['questions'] = $this->get_questions_info($msg['f_question_id']);
		$this->response->make_json_ok('', $content);
	}

	private function get_questions_info($qts_id)
	{
		$questions = array();
		for($i=0; $i<count($qts_id); $i++)
		{
			$qt = DBQuestion_Manager::instance()->get_one_qt_info($qts_id[$i]);
			array_push($questions, $this->question($qt));
		}
		return $questions;
	}
} 