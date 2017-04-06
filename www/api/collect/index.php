<?php
require_once('../includes/init.php');


try{
    $connection = new MongoClient($_CFG['mongodb_op']);
    $mongo_op = new MongoDB($connection, 'gsk');
    $mongo_op->report->insert($_REQUEST);
    $connection->close();
}
catch(Exception $e) 
{ 

}

die('');
?>

