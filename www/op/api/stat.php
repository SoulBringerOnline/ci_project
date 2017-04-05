<?php

require_once( 'init.php' );
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$redis = new Redis();
$redis->connect( $config['redis_host'] , $config['redis_port'] );


$users = array();
$key = "V_OL_CRON_DATA";
$cache_data = $redis->get($key);

if(empty($cache_data)) {
	$keys = $redis->keys('V_OL#*');
	foreach ($keys as $idx => $key) {
		list($pre, $uin, $channel) = explode("#", $key);
		if($uin == "1031602"){
			continue;
		}
		array_push( $users, $redis->get($key)  . ' ' .  '渠道:' . $channel . ' UIN:' . $uin );
	}
	echo json_encode($users);
}
else {
	echo $cache_data;
}


$redis->close();
unset($redis);

?>
