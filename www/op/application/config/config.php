<?php


$config['base_url'] = '';
$config['index_page'] = 'index.php';
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'GSK_';
$config['composer_autoload'] = FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['log_threshold'] = 0;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;
$config['encryption_key'] = '';
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = '/tmp';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();
$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = TRUE;
$config['proxy_ips'] = '';

//线上

$config['redis_host'] = "10.128.63.250";
$config['redis_port'] = 6380;
$config['mongodb_gsk_ol'] = "mongodb://10.128.6.61:20000";
$config['mongodb_spider'] = "mongodb://192.168.165.240:27017";
$config['mongodb_op'] = "mongodb://10.128.63.250:27017";
// //测试
// $config['redis_host'] = "192.168.164.199";
// $config['redis_port'] = 6380;
// $config['mongodb_gsk_ol'] = "mongodb://192.168.164.199:20000";
// $config['mongodb_spider'] = "mongodb://192.168.165.240:27017";
// $config['mongodb_op'] = "mongodb://192.168.164.199:27017";

$config['redis_host'] = "192.168.165.241";
$config['redis_port'] = 6380;
$config['mongodb_gsk_ol'] = "mongodb://192.168.165.240:27017";
$config['mongodb_spider'] = "mongodb://192.168.165.240:27017";
$config['mongodb_op'] = "mongodb://192.168.165.240:27017";




$config['priv'] = array( 
	'wuxian' => array( 'pswd' => 'glodon' , 'role' => 'guest' , 'name' => '开发者' ),
	'admin' => array( 'pswd' => 'wuxian1533' , 'role' => 'admin', 'name' => '管理员' ),
	'op' => array( 'pswd' => 'wuxian1833' , 'role' => 'op' , 'name' => '产品运营'),
	'stat' => array( 'pswd' => 'wuxian2100' , 'role' => 'stat' , 'name' => '数据用户'),
	'asist' => array( 'pswd' => 'wuxian2110' , 'role' => 'asist' , 'name' => '助理用户'),
);

	$config['priv_user'] = array(
		'4' => array( 'pswd' => 'glodon' , 'role' => 'guest' , 'name' => '开发者' ),
		'1' => array( 'pswd' => 'wuxian1533' , 'role' => 'admin', 'name' => '管理员' ),
		'3' => array( 'pswd' => 'wuxian1833' , 'role' => 'op' , 'name' => '产品运营'),
		'2' => array( 'pswd' => 'wuxian2100' , 'role' => 'stat' , 'name' => '数据用户'),
	);


$config['call_zhuyou'] = array(
	'account_sid' => 'aaf98f89511a246a01511e59bd260c71',
	'account_token' => 'd1f88aee5a454014889c8dcc6cc265c7',
	'app_id' => '8a48b551511a2cec01511e5e47ab0cf7',
	'app_token' => '008e6178184f960286cfb53af369684b',
	'sub_account_sid' => '78ffef9a919311e5bb61ac853d9d52fd',
	'sub_account_token' => 'f8d86fb0b5e5045bdff8edbbf52ca9fd',
	'vo_ip_account' => '8005223100000003',
	'vo_ip_password' => 'ncKYr760',
	'base_url' => 'sandboxapp.cloopen.com',
	'port' => '8883',
	'version' => '2013-12-26',
);