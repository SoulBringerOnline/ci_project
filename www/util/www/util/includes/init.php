<?php
/* ------------------------------------------------------ */
// - 环境初始化
/* ------------------------------------------------------ */

/* SESSION启动 */
#session_start();
/* ------------------------------------------------------ */
// - 加载
/* ------------------------------------------------------ */

/* 加载整站配置文件库 */
require_once('config.php');

/* ------------------------------------------------------ */
// - 环境配置
/* ------------------------------------------------------ */
/* 设置错误警报等级 */
if($_CFG['DEBUG'])
{
    ini_set('display_errors', 'on');
    ini_set('error_reporting', E_ALL&~E_NOTICE);
    ini_set('html_errors', 'on');
}

/* 加载全局变量 $_LANG */
require_once($_CFG['DIR_INC'] . 'inc_lang.php');

/* 加载整站公用函数库 */
require_once($_CFG['DIR_INC'] . 'inc_func.php');

/* 加载Mysql数据库类 */
require_once($_CFG['DIR_INC'] . 'inc_logger.php');




/* 设置时区 */
if( PHP_VERSION >= '5.1' && !empty($_CFG['SYS_TIMEZONE']) ){
    date_default_timezone_set($_CFG['SYS_TIMEZONE']);
}

/* ------------------------------------------------------ */
// - 变量初始化
/* ------------------------------------------------------ */


/* 对用户传入的变量进行转义操作。*/
if( !get_magic_quotes_gpc() ){
    if( !empty($_GET) )  $_GET  = addslashes_deep($_GET);
    if( !empty($_POST) ) $_POST = addslashes_deep($_POST);

    $_COOKIE = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}

/* 初始化请求变量 */
$_REQUEST = array_merge($_GET, $_POST);

/* 初始化操作变量 */
$_REQUEST['act'] = $_REQUEST['act'] ? trim($_REQUEST['act']) : '';


/* 文件头信息 */
header('Content-Type: application/json; charset=utf-8');
?>
