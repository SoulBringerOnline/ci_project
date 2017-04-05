<?php

// ini_set('display_errors', 'on');
// ini_set('error_reporting', E_ALL&~E_NOTICE);
// ini_set('html_errors', 'on');


include 'Lava.class.php';
require_once dirname(__FILE__) . '/config.php';
$config = array('APP_PATH'    => './App/');
LavaPHP::getInstance($config)->run();
