<?php

require_once('includes/init.php');
require_once($_CFG['DIR_CFG'].'cfg_version.php'); 

$channels = array();
foreach( $channel_version[3] as $c => $v ){
    array_push( $channels, $c );
}

header('Content-Type: application/json');
die( json_encode($channels) );
?>
