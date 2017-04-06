<?php
$DOMAIN_NAME = 'http://' . $_SERVER['SERVER_NAME'] . '/';
define('BASE_URL', $DOMAIN_NAME);

// ScribLogCollector
define ( 'LOG_COLLECT', FALSE);
//线下接口访问地址
define ( 'API_URL', 'http://api.zy.work.glodon.com/');
//安信捷短信网关 测试账号 密码
define ( 'SMS_ACCOUNT', 'gld222');
define ( 'SMS_PWD', 'gld888');
$GLOBALS['THRIFT_ROOT_LIB'] = LIB_PATH . '//thrift';

