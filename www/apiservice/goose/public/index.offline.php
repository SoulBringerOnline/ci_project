<?php

//xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU);

ini_set('display_errors', 0);
error_reporting(E_ALL);

$start = microtime(true);

define('FRAME_PATH', __DIR__ . '/../../frame');
define('LIB_PATH', __DIR__ . '/../../libs');
define('VENDOR_PATH', __DIR__ . '/../../goose');

define('CONFIG', 'Webapi');
define('MODLUES_NAMESPACE', '\\Goose\\Modules\\');

require(VENDOR_PATH . '/config/webapi/config.inc.php');
require(FRAME_PATH . '/Autoloader.class.php');
require(FRAME_PATH . '/Application.class.php');

$root_path_setting = array(
	'Frame' => FRAME_PATH,
	'Libs' => LIB_PATH,
	'default' => VENDOR_PATH,
);

$autoloader = Autoloader::register($root_path_setting);

$app = \Frame\Application::instance();

$app->singleton('config', function () {
	return new \Goose\Libs\Config(CONFIG);
});

//注册module的namespace
$app->module_namespace = '\\Goose\\Modules\\';

$app->singleton('request', function($c) {
	return new \Goose\Libs\Http\Request($c);
	//return new \Libs\Http\BasicRequest();
});

//response
$app->singleton('response', function($c) {
	return new \Goose\Libs\Http\Response($c);
});

//router
$app->singleton('router', function($c) {
	// 用本地的
	return new \Goose\Libs\Router\GooseRouter($c);
});

$app->singleton('view', function($c) {
	return new \Libs\View\Json($c);
});

$app->singleton('session', function($c) {
	return new \Goose\Libs\Session\Session($c);
});

$app->run();
$spend = microtime(true) - $start;

$log_env = array(
	'get'=>$app->request->GET,
	'post'=>$app->request->POST,
	'zhuyou'=>$app->request->ZHUYOU
);
$app->log->log("Goose_request", "[{$app->request->path}]\t[request:" .json_encode($log_env) ."]\t [time: {$spend}s]"."\t [ip: {$app->request->ip}]");

//register_shutdown_function(function() {
/*
	$xhprof_data = xhprof_disable();
	$XHPROF_ROOT = realpath(dirname(__FILE__) . '/../../xhprof');
	include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
	include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
	$xhprof_runs = new XHProfRuns_Default();
	$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
	if (function_exists('fastcgi_finish_request')) {
		fastcgi_finish_request();
	}
*/
