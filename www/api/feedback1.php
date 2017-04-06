<?php
require_once('includes/init.php');

$log = Logger::instance(  $_CFG['DIR_LOG'] . 'feedback' , Logger::DEBUG);
if( empty($_REQUEST['feedbackCon']) || empty($_REQUEST['zhuyouExtension']) )
{
    $log->logInfo('[ERROR]' , 'PARAMS MISS!');
    make_json_fail();
}

echo 111;
try{
    $connection = new MongoClient($_CFG['mongodb_op']);
    $mongo_op = new MongoDB($connection, 'gsk');

    $feedback = split_str($_REQUEST['zhuyouExtension']);
    $feedback['client_ip'] = client_ip();
    $feedback['user_agent'] = $_REQUEST['userAgent'];
    $feedback['user_feedback'] = $_REQUEST['feedbackCon'];

    $log->logInfo('[RSP]' , $feedback);
    $mongo_op->feedback->insert($feedback);
    $connection->close();
    make_json_ok();
}
catch(Exception $e) 
{ 
    $log->logInfo('[ERROR]' , $e);
    make_json_fail();
}

?>
