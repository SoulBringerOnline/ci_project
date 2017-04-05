<?php 
	class News_Model extends CI_Model
	{
		public function __construct()
		{
			$this->connection = new MongoClient( $this->config->item('mongodb_gsk_ol') );
			$this->mongo_gsk = new MongoDB($this->connection, 'gsk_ol');

			$this->connection_spider = new MongoClient( $this->config->item('mongodb_spider') );
			$this->mongo_spider = new MongoDB($this->connection_spider, 'gsk_spider');
		}

		function __destruct(){
		    $this->connection->close();
		}

		public function test()
		{
			$i = 0;
			$d = $this->mongo_gsk->hotspot->find(  )->sort(array('f_hotspot_sort' => -1));
			foreach ($d as $item) 
			{
				// $newstr = str_replace('<p></p></p><p>', '<p></p>',  $item['f_hotspot_content']);
			
				if( $item['f_hotspot_status'] != 1 )
				{
					echo $item['f_hotspot_sort'];
					echo '<br>';
					$t = (time() - 3600 * $i ) * 1000 ;
					$news = array('f_hotspot_sumbit_time'=>new MongoInt64($t));
					$this->mongo_gsk->hotspot->update(
		    				array('f_hotspot_id' => $item['f_hotspot_id'] ),
		    				array('$set' => $news) );
					$i++;

				// 	if(strlen($item['f_hotspot_first_image']) == 0 ||  strpos($item['f_hotspot_first_image'], "gsk-a") > 0)
				// 	{
				// 		print $item['f_hotspot_first_image'];

				// 		$news = array('f_hotspot_first_image'=>get_random_news_img($item['f_hotspot_id']));
				// 		var_dump($news);
				// // $this->mongo_gsk->hotspot->update(
    // // 				array('f_hotspot_id' => $item['f_hotspot_id'] ),
    // // 				array('$set' => $news) );
				// 	}
							// $newstr = str_replace('<br>', '<p></p>',  $item['f_hotspot_content']);
							// $news = array('f_hotspot_content'=>$newstr);
							// 					$this->mongo_gsk->hotspot->update(
		    	// 			array('f_hotspot_id' => $item['f_hotspot_id'] ),
		    	// 			array('$set' => $news) );

					// $newstr = str_replace('<br>', '<p></p>',  $item['f_hotspot_content']);
					// $news = array('f_hotspot_content'=>$newstr);
					// 					$this->mongo_gsk->hotspot->update(
    	// 			array('f_hotspot_id' => $item['f_hotspot_id'] ),
    	// 			array('$set' => $news) );
				}
				// $news = array();

				// $news['f_hotspot_publish_time'] = $item['f_hotspot_publish_time'];
				// if( $news['f_hotspot_publish_time'] <  1539975791 )
				// {
				// 	$news['f_hotspot_publish_time'] = $news['f_hotspot_publish_time'] * 1000;
				// }
				// $news['f_hotspot_snatch_time'] = $item['f_hotspot_snatch_time'];
				// if( $news['f_hotspot_snatch_time'] <  1539975791 )
				// {
				// 	$news['f_hotspot_snatch_time'] = $news['f_hotspot_snatch_time'] * 1000;
				// }
				// $news['f_hotspot_sumbit_time'] = $item['f_hotspot_sumbit_time'];
				// if( $news['f_hotspot_sumbit_time'] <  1539975791 )
				// {
				// 	$news['f_hotspot_sumbit_time'] = $news['f_hotspot_sumbit_time'] * 1000;
				// }
	

				// $this->mongo_gsk->hotspot->update(
    // 				array('f_hotspot_id' => $item['f_hotspot_id'] ),
    // 				array('$set' => $news) );
			
					// print $item['f_hotspot_title'] ;
					// print $item['f_hotspot_content'];
					// print '<------------------>';
					// print '<br>';
						// echo $value['title'];
						// echo "<BR>";
						if( $item['f_hotspot_title'] == "老技术员写给徒弟的笔记" )
						{
							var_dump($item['f_hotspot_content']);
							// $newstr = str_replace('<br>', '<p></p>',  $item['f_hotspot_content']);
							// $news = array('f_hotspot_content'=>$newstr);
							// 					$this->mongo_gsk->hotspot->update(
		    	// 			array('f_hotspot_id' => $item['f_hotspot_id'] ),
		    	// 			array('$set' => $news) );
						}
						// if( $value['title'] == trim( $item['f_hotspot_title'] )  )
						// {
						// 	echo "AAA";
						// 	echo "<BR>";
						// 	$news = array( 'f_hotspot_content' => strip_html_tags( $value['content'] ) );
						// 	$this->mongo_gsk->hotspot->update(
			   //  				array('f_hotspot_id' => $item['f_hotspot_id'] ),
			   //  				array('$set' => $news) );
						// 	break;
						// }
						// else
						// {
						// 	echo  $item['f_hotspot_title'];
						// }
			}
		}
		
		/*
		 	爬虫库
		*/
		public function get_spider($filter = array())
		{
		    $p = $f = $list = array();

		    /* 排序字段初始化 */
		    $f['order_fd']   = 'publishTimestamp';
		    $f['order_type'] = $filter['order_type'] == 1 ? 1 : -1; 

		    /* 设置分页信息 */
		    $p['cur_page'] = isset($filter['cur_page']) ? intval($filter['cur_page']) : 1;
		    $p['rows_page']  = 20;
		    $p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

		    /* 列表对象赋值 */
		    $list['data']  =  $this->mongo_spider->news->find( array('diggTimestamp' => array( '$exists' => False )) )->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) );
		    $list['filter'] = $f;
		    $list['page'] = $p;
		    /* 返回 */
		    return $list;
		}

		/*
		 	线上资讯库
		*/
		public function store_news($news_id)
		{
			/*
			f_hotspot_category	
			资讯分类 ：1.设计 2.施工 3.造价 4.设备 5.管理 6.招标 7.投标 8.BIM 9.考试 10.话题 11.专家说 12.风向标 13.企业 14.会议 15.培训讲座 16.工程信息
			f_hotspot_status 
			状态：1普通， 2 发布 ， 3 首页推荐 4 相关阅读
			*/
			$d = $this->mongo_spider->news->find( array('_id' => $news_id ) );
			foreach ($d as $item) 
			{
				if( $item['_id'] == $news_id )
				{
					$news = array(
						'f_hotspot_id' => $news_id,
						'f_hotspot_title' => is_null($item['title']) ? '' : trim($item['title']) ,
						// 'f_hotspot_first_image' => is_null($item['fristImg']) ? '' : trim($item['fristImg']) ,
						'f_hotspot_first_image' => '',
						'f_hotspot_source_site' => is_null($item['source']) ? '' : trim($item['source']) ,
						'f_hotspot_site' => is_null($item['target']) ? '' : trim($item['target']) ,
						'f_hotspot_author' => is_null($item['author']) ? '' : trim($item['author']) ,
						'f_hotspot_link' => is_null($item['link']) ? '' : trim($item['link']) ,
						'f_hotspot_content' => is_null($item['content']) ? '' :   strip_html_tags($item['content'])  ,
						'f_hotspot_publish_time' => new MongoInt64(time()),
						'f_hotspot_snatch_time' => new MongoInt64(time()),
						'f_hotspot_sumbit_time' => new MongoInt64(time()),
						'f_hotspot_keyword' => is_null($item['keywords']) ? '' : $item['keywords'] ,
						"f_hotspot_comment_count" => new MongoInt32(1),
						"f_hotspot_collection_count"  => new MongoInt32(1),
						"f_hotspot_view_count"  => new MongoInt32(1),
						"f_hotspot_good_count"  => new MongoInt32(1),
						"f_hotspot_sort"  => new MongoInt32(0),
						"f_hotspot_category"  => new MongoInt32(4),
						"f_hotspot_status"  => new MongoInt32(1),
						"f_hotspot_recmd_type"  => new MongoInt32(0)
					);
					if( $this->mongo_gsk->hotspot->count( array('f_hotspot_id' =>  $news_id ) ) == 0 )
		    		{
						$this->mongo_gsk->hotspot->insert( $news );
						$this->mongo_spider->news->update(
	    				array('_id' => $news_id ),
	    				array('$set' => array( 'diggTimestamp' => time() )) );

					    //点击上线，往groupMsg表中添加数据 ---lixd-a、erx
					    $msg = array();
					    $data = $this->get_a_news($news_id);
					    $msg['f_msg_id'] = "hotspot_".$news_id;
					    $msg['f_msg_type'] = 13;
					    $msg['f_sender_id'] = 10003;
					    $msg['f_sender_name'] = "筑友资讯";
					    $msg['f_sender_avatar'] = "";
					    $msg['f_group_id'] = "group_hotspot";
					    $msg['f_group_type'] = 6;
					    $msg['f_group_name'] = "筑友资讯";
					    $msg['f_send_time'] = $news['f_hotspot_publish_time'];
					    $msg['f_msg_seq_id'] = 1;
					    $msg['f_msg_body'] = "";
					    $msghotspot = array();
					    $msghotspot['f_hotspot_id'] = $data['f_hotspot_id'];
					    $msghotspot['f_hotspot_title'] = $data['f_hotspot_title'];
					    $msghotspot['f_hotspot_first_image'] = $data['f_hotspot_first_image'];
					    $msghotspot['f_hotspot_source_site'] = $data['f_hotspot_source_site'];
					    $msg['f_msg_hotspot'] = $msghotspot;
					    $this->mongo_gsk->groupMsg->update(array('f_msg_id' => $msg['f_msg_id'] ), array('$set'=>$msg), array('upsert'=>true));
		    		}
		    		return True;
				}
			}
		    return False;
		}

		public function edit_news($news_id , $news = array()){
			if( !empty($news) && strlen($news_id)  &&
				( $this->mongo_gsk->hotspot->count( array('f_hotspot_id' => $news_id ) ) == 1 )){

					$news['f_hotspot_comment_count'] = new MongoInt32($news['f_hotspot_comment_count']);
					$news['f_hotspot_collection_count'] = new MongoInt32($news['f_hotspot_collection_count']);
					$news['f_hotspot_view_count'] = new MongoInt32($news['f_hotspot_view_count']);
					$news['f_hotspot_good_count'] = new MongoInt32($news['f_hotspot_good_count']);
					$news['f_hotspot_sort'] = new MongoInt32($news['f_hotspot_sort']);
					$news['f_hotspot_sumbit_time'] = new MongoInt64(strtotime($news['f_hotspot_sumbit_time']));

	    			$this->mongo_gsk->hotspot->update(
	    				array('f_hotspot_id' => $news_id ),
	    				array('$set' => $news) );

				//点击上线，往groupMsg表中添加数据 ---lixd-a、erx
				$msg = array();
				$data = $this->get_a_news($news_id);
				$msg['f_msg_id'] = "hotspot_".$news_id;
				$msg['f_msg_type'] = 13;
				$msg['f_sender_id'] = 10003;
				$msg['f_sender_name'] = "筑友资讯";
				$msg['f_sender_avatar'] = "";
				$msg['f_group_id'] = "group_hotspot";
				$msg['f_group_type'] = 6;
				$msg['f_group_name'] = "筑友资讯";
				$msg['f_send_time'] = $data['f_hotspot_publish_time'];
				$msg['f_msg_seq_id'] = 1;
				$msg['f_msg_body'] = "";
				$msghotspot = array();
				$msghotspot['f_hotspot_id'] = $news_id;
				$msghotspot['f_hotspot_title'] = $data['f_hotspot_title'];
				$msghotspot['f_hotspot_first_image'] = $data['f_hotspot_first_image'];
				$msghotspot['f_hotspot_source_site'] = $data['f_hotspot_source_site'];
				$msg['f_msg_hotspot'] = $msghotspot;
				$this->mongo_gsk->groupMsg->update(array('f_msg_id' => $msg['f_msg_id'] ), array('$set'=>$msg), array('upsert'=>true));
	    			return True;
			}
		    return False;
		}

		public function add_news($news = array()){
			if(!empty($news['f_hotspot_title']) && !empty($news['f_hotspot_content']) ){
				$_id = new MongoId();
				$news['f_hotspot_id'] = $_id . '';
				$news['f_hotspot_status'] = new MongoInt32(1);
				$news['f_hotspot_content'] = strip_html_tags($news['f_hotspot_content']) ;
				$news['f_hotspot_comment_count'] = new MongoInt32($news['f_hotspot_comment_count']);
				$news['f_hotspot_collection_count'] = new MongoInt32($news['f_hotspot_collection_count']);
				$news['f_hotspot_view_count'] = new MongoInt32($news['f_hotspot_view_count']);
				$news['f_hotspot_good_count'] = new MongoInt32($news['f_hotspot_good_count']);
				$news['f_hotspot_sort'] = new MongoInt32($news['f_hotspot_sort']);

				$news['f_hotspot_snatch_time'] = new MongoInt64(time());
				$news['f_hotspot_sumbit_time'] = new MongoInt64(strtotime($news['f_hotspot_sumbit_time']));
				$news['f_hotspot_publish_time'] =  new MongoInt64(time());

    			$this->mongo_gsk->hotspot->insert( $news );

				//点击上线，往groupMsg表中添加数据 ---lixd-a、erx
				$msg = array();
				$data = $this->get_a_news($_id);
				$msg['f_msg_id'] = "hotspot_".$_id;
				$msg['f_msg_type'] = 13;
				$msg['f_sender_id'] = 10003;
				$msg['f_sender_name'] = "筑友资讯";
				$msg['f_sender_avatar'] = "";
				$msg['f_group_id'] = "group_hotspot";
				$msg['f_group_type'] = 6;
				$msg['f_group_name'] = "筑友资讯";
				$msg['f_send_time'] = $news['f_hotspot_publish_time'];
				$msg['f_msg_seq_id'] = 1;
				$msg['f_msg_body'] = "";
				$msghotspot = array();
				$msghotspot['f_hotspot_id'] = $_id;
				$msghotspot['f_hotspot_title'] = $news['f_hotspot_title'];
				$msghotspot['f_hotspot_first_image'] = $news['f_hotspot_first_image'];
				$msghotspot['f_hotspot_source_site'] = $news['f_hotspot_source_site'];
				$msg['f_msg_hotspot'] = $msghotspot;
				$this->mongo_gsk->groupMsg->update(array('f_msg_id' => $msg['f_msg_id'] ), array('$set'=>$msg), array('upsert'=>true));
    			return True;
			}
		    return False;
		}

		public function change_news_status($news_id, $news_status){
			if(!empty($news_id) && !empty($news_status) ){
				$news = array();
				$news['f_hotspot_status'] = new MongoInt32($news_status);
				if( $news_status == 2 )
				{
					$news['f_hotspot_publish_time'] =  new MongoInt64(time());
				}
    			$this->mongo_gsk->hotspot->update(
	    				array('f_hotspot_id' => $news_id ),
	    				array('$set' => $news) );
	    		return True;
			}
		    return False;
		}

		public function init_news_sort_id(){
			$this->mongo_gsk->hotspot->update(array(),array('$set' => array( "f_hotspot_sort"  => new MongoInt32(0) )) ,array("multiple" => true));
    		return True;
		}

		public function del_news($news_id){
			if(!empty($news_id)){
				$this->mongo_gsk->hotspot->remove(array('f_hotspot_id' => $news_id ));
				$this->mongo_spider->news->update(
	    				array('_id' => $news_id ),
	    				array('$unset' => array( 'diggTimestamp' => 1 )) );
	    		return True;
			}
		    return False;
		}

		public function get_news($filter = array())
		{
		    $p = $f = $list = array();

		    /* 排序字段初始化 */
		    $f['news_status'] = isset($filter['news_status']) ? intval($filter['news_status']) : 1;
		    $f['order_fd']   = 'f_hotspot_sumbit_time';
		    switch ($filter['f_hotspot_status']) {
		    	case 1:
		    		$f['order_fd']   = 'f_hotspot_sumbit_time';
		    		break;
		    	
		    	case 2:
	    		case 3:
		    		$f['order_fd']   = 'f_hotspot_sumbit_time';
		    		break;
		    }
		    $f['order_type'] = -1; 

		    /* 设置分页信息 */
		    $p['cur_page'] = isset($filter['cur_page']) ? intval($filter['cur_page']) : 1;
		    $p['rows_page']  = 20;
		    $p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

		    /* 列表对象赋值 */
		    $list['data']  =  $this->mongo_gsk->hotspot->find(array('f_hotspot_status' => $f['news_status'] ))->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) );
		    $list['filter'] = $f;
		    $list['page'] = $p;
		    
		    /* 返回 */
		    return $list;
		}

		public function get_a_news($news_id)
		{
			$news = NULL;
			$d = $this->mongo_gsk->hotspot->find( array('f_hotspot_id' => $news_id ) );
			foreach ($d as $item) {
				if( $item['f_hotspot_id'] == $news_id )
				{
				  $news = $item;
				  break;
				}
			}
		    return $news;
		}
	}

?>
