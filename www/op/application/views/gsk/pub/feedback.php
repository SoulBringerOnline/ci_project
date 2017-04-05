<?php
/**
 * 处理用户反馈信息.
 * User: lixd-a
 * Date: 2015/9/9
 * Time: 13:54
 */
	ini_set('display_errors', 'on');
	ini_set('error_reporting', E_ALL&~E_NOTICE);
	ini_set('html_errors', 'on');

//配置
	$CONFIG_FILE = dirname(__FILE__) . '/../application/config/config.php';
	require_once( $CONFIG_FILE );

	$connection = new MongoClient($config['mongodb_op']);
	$mongo_op = new MongoDB($connection, 'gsk');
	$news_feedback = $_POST['feedback'];
	list($u, $clt, $extension) = explode("&", $_POST['zhuyou_extension']);
	$news = array(
		'f_feedback_uid'=>explode("=", $u)[1],
		'f_feedback_clt'=>explode("=", $clt)[1],
		'f_feedback_extension'=>explode("=", $extension)[1],
		'f_feedback_feedback'=>$news_feedback
	);

	$mongo_op->feedback->insert($news);
	$connection->close();
?>