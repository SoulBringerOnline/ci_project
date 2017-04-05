<?php

require_once( 'init.php' );
$news_id = $_GET['news_id'];

if(!empty($news_id))
{
	$db = new MongoClient($config['mongodb_gsk_ol']);
	$col = new MongoDB($db, 'gsk_ol');

	$t = time() ;
	$news = array( 'f_hotspot_sumbit_time'=>new MongoInt64($t) , 'f_hotspot_status' => new MongoInt32(2) ) ;
	$col->hotspot->update( array('f_hotspot_id' => $news_id)  , array('$set' => $news) ); 

	$news = $col->hotspot->find( array( 'f_hotspot_id' => $news_id ) );
	foreach ($news as $item) {
		echo $item['f_hotspot_id'];
		echo '<br>';
		echo $item['f_hotspot_title'];
		echo '<br>';
		echo $item['f_hotspot_status'];
		echo '<br>';
	}

	$db->close();
}
?>
