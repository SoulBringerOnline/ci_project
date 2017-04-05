<?php

ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL&~E_NOTICE);
ini_set('html_errors', 'on');


try{ 

    $m = new MongoClient( "mongodb://192.168.165.240:27017");
    $db = new MongoDB($m, 'gsk');

    $databases = $m->listDBs(); //List all databases 
    var_dump($databases);
	echo "AAA";
    // $cursor = $db->update->find(  );
    // var_dump( $cursor );

    $m->close(); 
} catch(mConnectionException $e) { 
    //handle connection error 
    die($e->getMessage()); 
} 
?> 
