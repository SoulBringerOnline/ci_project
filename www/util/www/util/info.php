<?php

require_once('includes/init.php');
require_once($_CFG['DIR_CFG'].'cfg_version.php'); 
require_once($_CFG['DIR_CFG'].'cfg_white_user.php'); 
require_once($_CFG['DIR_HELPER'].'helper_info.php'); 

$log = Logger::instance(  'log/info' , Logger::DEBUG);

/*
参数说明
c : channel
v : app版本号
u : 用户uin
sys : 手机操作系统
dev : 设备名称
net : 当前网络连接方式 （wifi或wwan）
sp : 手机运营商
t : 测试标志位（1为测试环境）
clt : 平台类型（2 ：ios；3:android；4:pc）
uuid：设备唯一标识
*/

$filter = $_REQUEST;
$filter['white_user'] = in_array( $filter['u'], $white_user) ;
$filter['version'] = $channel_version[ $filter['clt'] ][ $filter['c'] ];
$filter['client_ip'] = client_ip();
$log->logInfo('[REQ]' , $filter);

$info = array();
$info['hosts'] = get_host( $filter );
$info['version'] = strval($filter['version']);
$info['cdn'] = get_cdn_url( $filter ) ;
$info['qrcode'] =  get_qrcode_png( $filter ) ;
$info['debug'] = get_debug( $filter ) ;
$info['update'] = get_update_type( $filter );
$info['tips'] = get_update_tips( $filter );
$info['dl_url'] = get_dl_url( $filter );
$info['dl_page'] = get_dl_page( $filter );
$info['api'] = get_api_url( $filter );
$info['news'] = get_news_url( $filter ) ;
// $info['feedback'] = get_api_url( $filter );
$info['img_style'] = 0;
$info['flag'] = 0;
$log->logInfo('[RSP]' , $info);

header('Content-Type: application/json');
die( json_encode($info) );
?>
