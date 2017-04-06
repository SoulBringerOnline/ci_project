<?php
	require_once('../includes/init.php');
	$url = 'zhuyou-news.oss-cn-hangzhou.aliyuncs.com';
	$rep_url = 'res.zy.glodon.com';
	$connection = new MongoClient($_CFG['mongodb_gsk_ol']);
	$mongo_ol = new MongoDB($connection, 'gsk_ol');

	$data = iterator_to_array($mongo_ol->hotspot->find(array(), array('f_hotspot_first_image', 'f_hotspot_big_image')));
	foreach($data as $item)
	{
		$first_image =str_replace($url, $rep_url, $item['f_hotspot_first_image']);
		$big_image =str_replace($url, $rep_url, $item['f_hotspot_big_image']);
		$mongo_ol->hotspot->update(array('_id'=>$item['_id']), array('$set'=>array('f_hotspot_first_image'=>$first_image, 'f_hotspot_big_image'=>$big_image)));
	}

	echo count($data);
?>