<?php
	require_once('../includes/init.php');

	$data = file_get_contents("php://input");
	$data =  json_decode($data, true);
	$log = Logger::instance( $_CFG['DIR_LOG'] . 'flowback' , Logger::DEBUG);
	$log->logInfo('[REQ]' , $data);
	$order_no = $data['orderNo'];
	$uin = $data['extNo'];
	$status = $data['status'];
	$code = $data['code'];

	if(empty($order_no))
	{
		echo 1;
		exit;
	}

	try{
		$connection = new MongoClient($_CFG['mongodb_gsk_ol']);
		$mongo_op = new MongoDB($connection, 'gsk_ol');
		$msg = $mongo_op->flowLog->findOne(array('f_order_no'=>$order_no), array('f_action_id', 'f_pay_time', 'f_status'));
		if(is_null($msg))
		{
			$log->logInfo('[999]' , "无该订单号！".$order_no);
			$connection->close();
			exit;
		}
		$action_type = action_type($msg['f_action_id']);
		$pay_time = human_date($msg['f_pay_time']/1000);

		if($status == "成功")
		{
			//推送小秘书--通知用户流量兑换成功
			$log->logInfo('msg' , $msg);
			if($msg['f_status'] == 0)
			{
				$url = 'http://10.128.63.250:5000/send_msg/sucevent/'.$uin.'/&&msginfo|'.$pay_time.'|'.$action_type.'/';
				$out = send_request_get($url);
				$log->logInfo('[success]' , array("success--send  ".$url, $out));
			}
			$mongo_op->flowLog->update(array('f_order_no'=>$order_no),
				array('$set'=>array('f_status'=>1, 'f_callBack_time'=> new MongoInt64(time()), 'f_liumi_code'=>$code)));
		}
		else
		{
			//兑换失败，返还积分
			$postpackage = $mongo_op->flowLog->findOne(array('f_order_no'=>$order_no), array('f_post_package'));
			$mongo_op->user->update(array('f_uin'=>$uin), array('$inc'=>array('f_Points'=>$postpackage['f_post_package'])));
			//同时，修改f_status状态为-1
			$mongo_op->flowLog->update(array('f_order_no'=>$order_no),
				array('$set'=>array('f_status'=>-1, 'f_callBack_time'=> new MongoInt64(time()), 'f_liumi_code'=>$code)));
			$log->logInfo('[faild]', "兑换失败");
		}
		$connection->close();
	}
	catch(Exception $e)
	{
		$log->logInfo('[ERROR]' , $e);
	}
	echo 1;
?>
