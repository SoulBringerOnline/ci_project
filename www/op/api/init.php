<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL&~E_NOTICE);
ini_set('html_errors', 'on');

//配置
$CONFIG_FILE = dirname(__FILE__) . '/../application/config/config.php';
require_once( $CONFIG_FILE );

$cur_time = time() + 28800 ;
$cur_day = intval( $cur_time / 86400 );
$cur_hour = intval( $cur_time / 3600 ); 

