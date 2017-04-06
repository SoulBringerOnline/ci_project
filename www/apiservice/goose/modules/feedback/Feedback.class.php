<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/3
 * Time: 10:19
 */
namespace Goose\Modules\Feedback;
use \Goose\Package\Feedback\DBFeedback_Manager;
use \Goose\Libs\Util\Util;
use \Goose\Libs\ErrorCode_Model_Common;

class Feedback extends \Goose\Libs\Wmodule{

	public function run()
	{
		$this->request->REQUEST['feedbackCon'] = isset($this->request->REQUEST['feedbackCon'])?$this->request->REQUEST['feedbackCon']:"";
		if( empty($this->request->REQUEST['feedbackCon']) || empty($this->request->ZHUYOU) )
		{
			$this->response->make_json_response(ErrorCode_Model_Common::ERROR_WRONG_PARAM, "PARAMS MISS!");
			$this->app->log->log('Feedback' , array('[ERROR]'=>'PARAMS MISS!'));
		}

		try{
			$feedback = array();
			$feedback = $this->request->ZHUYOU;
			$feedback['client_ip'] = Util::client_ip();
			$feedback['user_agent'] = $this->request->userAgent;
			$feedback['user_feedback'] = $_REQUEST['feedbackCon'];
			$feedback['f_feedback_time'] = new \MongoInt64(time());

			$this->app->log->log('Feedback' , array('[RSP]'=>$feedback));
			DBFeedback_Manager::instatnce()->save_feedback($feedback);
			$this->response->make_json_ok();
		}
		catch(\Exception $e)
		{
			$this->app->log->log('Feedback' , array('[ERROR]'=>$e));
			$this->response->make_json_fail();
		}
	}
} 