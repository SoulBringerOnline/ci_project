<?php
require_once('../includes/init.php');
/*
try{
    $connection = new MongoClient('mongodb://10.128.6.61:20000');
    #$connection = new MongoClient('mongodb://192.168.165.240:27017');
    $mongo_op = new MongoDB($connection, 'gsk_ol');

    if(!empty($_GET['phone']) && !empty($_GET['point']))
    {
        $mongo_op->user->update(
            array('f_phone'=>$_GET['phone']),
            array('$set'=>array('f_points'=> new MongoInt32($_GET['point']) )));
    }
    if(!empty($_GET['name']) && !empty($_GET['point']))
    {
        $mongo_op->user->update(
            array('f_name'=>$_GET['name']),
            array('$set'=>array('f_points'=> new MongoInt32($_GET['point']) )));
    }

    if(!empty($_GET['phone']) && !empty($_GET['degree']))
    {
        $mongo_op->user->update(
            array('f_phone'=>$_GET['phone']),
            array('$set'=>array('f_degree'=> new MongoInt32($_GET['degree']) )));
    }
    if(!empty($_GET['name']) && !empty($_GET['degree']))
    {
        $mongo_op->user->update(
            array('f_name'=>$_GET['name']),
            array('$set'=>array('f_degree'=> new MongoInt32($_GET['degree']) )));
    }

    if(!empty($_GET['phone']) && !empty($_GET['go_on_day']))
    {
        $mongo_op->user->update(
            array('f_phone'=>$_GET['phone']),
            array('$set'=>array('f_go_on_day'=> new MongoInt32($_GET['go_on_day']) )));
    }
    if(!empty($_GET['name']) && !empty($_GET['go_on_day']))
    {
        $mongo_op->user->update(
            array('f_name'=>$_GET['name']),
            array('$set'=>array('f_go_on_day'=> new MongoInt32($_GET['go_on_day']) )));
    }
    $connection->close();
    make_json_ok();
}
catch(Exception $e) 
{ 
    make_json_fail();
}
 */
?>
