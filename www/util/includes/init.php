<?php
/* ------------------------------------------------------ */
// - 环境初始化
/* ------------------------------------------------------ */
/* 加载整站配置文件库 */
require_once('config.php');
/* ------------------------------------------------------ */
// - 环境配置
/* ------------------------------------------------------ */
ini_set('display_errors', 'off');
ini_set('error_reporting', 0);
ini_set('html_errors', 'off');

require_once($_CFG['DIR_INC'] . 'inc_func.php');
require_once($_CFG['DIR_INC'] . 'inc_logger.php');
date_default_timezone_set($_CFG['SYS_TIMEZONE']);
/* ------------------------------------------------------ */
// - 变量初始化
/* ------------------------------------------------------ */
/* 对用户传入的变量进行转义操作。*/
if( !get_magic_quotes_gpc() ){
    if( !empty($_GET) )  $_GET  = addslashes_deep($_GET);
    if( !empty($_POST) ) $_POST = addslashes_deep($_POST);
    $_REQUEST  = addslashes_deep($_REQUEST);
}
/* 初始化请求变量 */
$_REQUEST = array_merge($_GET, $_POST);
/* 文件头信息 */
header('Content-Type: application/json; charset=utf-8');
?>
