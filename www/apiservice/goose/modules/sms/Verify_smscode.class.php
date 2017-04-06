<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/10/30
 * Time: 16:21
 */
namespace Goose\Modules\Sms;

use \Goose\Libs\ErrorCode_Model_Common;
use \Goose\Package\Sms\Helper\Errorcode_Model_VerifySMS;
use \Goose\Libs\Util\White_user;
use \Goose\Package\Sms\DBSms_Manager;
use \Libs\Redis\Redis;
use \Goose\Libs\Util\Util;

class Verify_smscode extends \Goose\Libs\Wmodule{

	public function run()
	{
		$flag = true;
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
		$app_id = isset($_REQUEST['app_id'])?$_REQUEST['app_id']:"";
		$cli_id = $this->session->cltid;
		$act = isset($_REQUEST['act'])?$_REQUEST['act']:"";
		$phone_number = isset($_REQUEST['phone'])?$_REQUEST['phone']:"";
		$type_sms = 1;

		if(empty($phone_number))
		{
			$this->response->make_json_response(intval(Errorcode_Model_VerifySMS::ERROR_LACK_PARAMETER));
			return FALSE;
		}
		
		if(trim($act) == "sendSms")//请求发送短信验证消息
		{
			$code = mt_rand(1000,9999);//四位数验证码

			$text = $code;
			if($type_sms == 0)
			{
				if($type == "0")
				{
					$text = "验证码：".$code."，欢迎加入【筑友】大家庭！请在1小时内完成验证。请勿向任何人提供该验证码";
				}
				else if($type == "1")
				{
					$text = "验证码：".$code."，您正在验证【筑友】，请在1小时内完成验证。请勿向任何人提供该验证码";
				}
				else if($type == "2")
				{
					$text = "验证码：".$code."，您正在操作【筑友】绑定手机号！请在1小时内完成验证。请勿向任何人提供该验证码";
				}
			}
			else
			{
				if($type == "0")
				{
					$text = "验证码：".$code.",%20欢迎加入筑友大家庭！请在1小时内完成验证。请勿向任何人提供该验证码【筑友】";
				}
				else if($type == "1")
				{
					$text = "验证码：".$code.",%20您正在验证筑友，请在1小时内完成验证。请勿向任何人提供该验证码【筑友】";
				}
				else if($type == "2")
				{
					$text = "验证码：".$code.",%20您正在操作筑友绑定手机号！请在1小时内完成验证。请勿向任何人提供该验证码【筑友】";
				}
			}


			try
			{
				if(Redis::exists($phone_number))
				{
					if(!White_user::white_user_sms($phone_number))
					{
						if(intval(Redis::get($phone_number))<5)
						{
							Redis::incr($phone_number);
						}
						else
						{
							$flag = false;
							$this->response->make_json_response(intval(ErrorCode_Model_VerifySMS::ERROR_MT_COUNT));//同一手机号1天内超过5次了，失败
						}
					}
					else
					{
						Redis::incr($phone_number);
					}

				}
				else
				{
					Redis::setex($phone_number, 86400, 1);//同一个手机号码一天只能注册5次
				}

				if($flag)
				{
					if($type_sms === 0)
					{
						$url="http://sms.glodon.com/SMSService.asmx?WSDL";
						$charset="utf-8";
						$param=array(
							'mobile'=> $phone_number,//手机号
							'text'=> $text,//验证码
							'username'=> "gld_wx", //用户名
							'password'=> "gld_wx20150713", //密码
						);

						$client = new \SoapClient($url);
						$client->soap_defencoding = $charset;
						$client->decode_utf8 = false;
						$client->xml_encoding = $charset;
						$res = $client->SendSms($param);
					}
					else
					{
						$url = 'http://111.13.56.193:9007/axj_http_server/sms?name=%s&pass=%s&mobiles=%s&content=%s';
						$url = sprintf($url, SMS_ACCOUNT, SMS_PWD, $phone_number, $text);
						$res = Util::send_request_get($url);
					}

					DBSms_Manager::instance()->update_send_all($type_sms);
					$seq_id = md5(uniqid());
					$key = 'sms-'.$phone_number.'-'.$type.'-'.$app_id.'-'.$cli_id;
					$value = array("seq_id"=>$seq_id, "code"=>$code, "starttime"=>time());
					Redis::setex($key, 3600, implode("&",$value));//key在redis中1小时过期
					$this->app->log->log('SMS' ,array('success'=>array('type'=> $type_sms, 'mobile'=>$phone_number, 'text'=> $code, $seq_id=>$value, 'key'=>$key, 'sms_res' => $res)));
					$this->response->make_json_response(intval(ErrorCode_Model_Common::SUCCESS), "", array('seq_id'=>$seq_id));

				}
			}
			catch(\Exception $e)
			{
				$this->app->log->log('SMS' , array('[ERROR]'=>$e));
				$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_UNKNOWN));
			}
		}
		else if(trim($act) == "verifySms")//请求验证短信验证码
		{
			$type_sms =1;
			$data =array();
			$code = isset($_REQUEST['verifycode'])?$_REQUEST['verifycode']:"";
			$key = 'sms-'.$phone_number.'-'.$type.'-'.$app_id.'-'.$cli_id;
			$data[$key] = $code;
			if(empty($code))
			{
				$this->response->make_json_response(intval(Errorcode_Model_VerifySMS::ERROR_LACK_PARAMETER));
				return null;
			}

			DBSms_Manager::instance()->update_verify_all($type_sms);
			//验证码成功验证后，就会删除，再次使用这个验证码，提示失效
			if(!Redis::exists($key))
			{
				$this->response->make_json_response(intval(ErrorCode_Model_VerifySMS::ERROR_SMS_FAILURE));
				return null;
			}
			$tempdata = Redis::get($key);
			$data['redis_data'] = explode("&",$tempdata);
			$this->app->log->log('SMS' , $data);

			if($data['redis_data'][1] == $code)
			{
				Redis::delete($key);
				$data['code'] = $code;
				$data['time'] = time() - strtotime($data['redis_data'][2]);//计算用户整个短信验证的操作时间
				$this->response->make_json_response();
				DBSms_Manager::instance()->update_verify_succ($type_sms);
			}
			else
			{
				$this->response->make_json_response(intval(ErrorCode_Model_Common::FAILD));
			}
		}
		else
		{
			$this->response->make_json_response(intval(ErrorCode_Model_Common::ERROR_UNKNOWN));
		}
	}
} 