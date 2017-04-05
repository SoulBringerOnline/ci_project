<?php 
	class Op_Model extends CI_Model
	{
		public function __construct()
		{
			$this->connection_op = new MongoClient( $this->config->item('mongodb_op') );
			$this->mongo_op = new MongoDB($this->connection_op, 'gsk');

			$this->connection_ol = new MongoClient( $this->config->item('mongodb_gsk_ol') );
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');

			$this->redis_gsk = new Redis();
			$this->redis_gsk->connect( $this->config->item('redis_host'), $this->config->item('redis_port') );
		}

		function __destruct(){
		    $this->connection_op->close();
		    $this->connection_ol->close();
		    $this->redis_gsk->close();
		}

		public function get_log($filter = array())
		{
		    $p = $f = $list = array();
			
		    $f['order_fd']   = 'f_time';
		    $f['order_type'] = -1; 
			$f['query'] = isset($filter['query']) ? trim($filter['query']) : '';

			/* 设置分页信息 */
		    $p['cur_page'] = isset($filter['cur_page']) ? intval($filter['cur_page']) : 1;
		    $p['rows_page']  = 50;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array( );
			if( strlen($f['query']) )
			{
				$filter_param = explode(" ", $f['query']);
				foreach ($filter_param as $q)
				{
					$key = '';
					$value = '';
					$flag = false;
                    $item = explode("=", $q);
                    if( $flag == false AND count($item) == 2 )
                    {
                    	$key = 'f_' . $item[0];
                    	if( $item[0] == 'channel' )
                    	{
                    		$key = 'f_client_channel';
                    	}
                        $value = $item[1];
                        $flag = '$eq';

                        switch( $key )
                        {
	                        case 'f_name':
	                        case 'f_cmd_info':
	                            $where[$key] = new MongoRegex('/' .  $value . '/');  
	                            break;
	                        case 'f_uin':
	                        case 'f_dye':
	                       	case 'f_client_channel':
	                            $value = intval($value);
	                            
	                        default:
	                            $where[$key] = array( $flag => $value );
	                            break;
                        }
                    }
                }
            }

		    /* 列表对象赋值 */
		    $list['data']  = iterator_to_array( $this->mongo_op->log->find($where, array('f_pb' => 0) )->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
		    $list['filter'] = $f;
		    $list['page'] = $p;

		    /* 返回 */
		    return $list;
		}
	
		function redis_get($key){
		    $s = $this->redis_gsk->get($key);
		    return is_numeric($s) ? intval($s) : 0;
		} 

		public function get_dash_stat($channel = 0)
		{
			$cur_time = time();
			$cur_day = intval( $cur_time / 86400 );
			$cur_hour = intval( $cur_time / 3600 );

			$cur_t5 = intval( $cur_time / 300 );
			$base_time = $cur_t5 + 96;
			$key = "#DASH_DATA_ALL_STAT#".$channel."#".$base_time;

			$cache_data = $this->redis_gsk->get($key);

			$cache_data = json_decode($cache_data,true);
			if(!empty($cache_data))
			{
				$stat = $cache_data;
				$key = 'STAT_CHANNEL_USERS#' . $channel ;
				$stat['users_num'] = $this->redis_get($key);
			}
			else {
				$stat = array();
				$stat['dau'] = $stat['dnu'] = $stat['hau'] = $stat['hnu'] = $stat['h5nu'] = $stat['h5au'] = array();

				foreach (range(0, 8) as $k) {
					$day = human_date($cur_time - (8 - $k) * 86400 );

					$key = 'STAT_CHANNEL_DAU#' . $channel . '#' . ($cur_day - 8 + $k);
					array_push( $stat['dau'], array('day' => $day , 'v' => $this->redis_get($key)) );

					$key = 'STAT_CHANNEL_DNU#' . $channel . '#' . ($cur_day - 8 + $k);
					array_push( $stat['dnu'], array('day' => $day , 'v' => $this->redis_get($key) ) );
				}

				$key = 'STAT_CHANNEL_USERS#' . $channel ;
				$stat['users_num'] = $this->redis_get($key);

				/*
				$base_hour = $cur_hour - $cur_hour % 24;
				foreach (range(0, 24) as $h) {
					$key = 'STAT_CHANNEL_HAU#' . $channel . '#' . ($base_hour + $h) ;
					   array_push( $stat['hau'],  $this->redis_get($key) );

					$key = 'STAT_CHANNEL_HNU#' . $channel . '#' . ($base_hour + $h) ;
					   array_push( $stat['hnu'],  $this->redis_get($key) );
				}
				*/

				$base_hour = $cur_hour - $cur_hour % 24;
				foreach (range(0, 24) as $h) {
					$key = 'STAT_CHANNEL_HAU#' . $channel . '#' . ($base_hour + $h) ;
					$t_time = date("Y-m-d H:i:s",($base_hour + $h - 8) * 3600);
					$s_time = date("H:i:s",($base_hour + $h - 8) * 3600);
					array_push( $stat['hau'],  array('t_time' => ($t_time) ,'s_time'=>$s_time , 'v' => $this->redis_get($key) )  );

					$key = 'STAT_CHANNEL_HNU#' . $channel . '#' . ($base_hour + $h) ;
					array_push( $stat['hnu'],  array('t_time' => ($t_time) ,'s_time'=>$s_time , 'v' => $this->redis_get($key) ) );
				}
			}



		    $key = 'STAT_UR';
		    $stat['ur'] = $this->redis_gsk->hgetall($key);

		    $key = 'STAT_UR3';
		    $stat['ur3'] = $this->redis_gsk->hgetall($key);

		    $key = 'STAT_UR7';
		    $stat['ur7'] = $this->redis_gsk->hgetall($key);

		    return $stat;
		}

		public function get_dash_stat_main($channel = 0) {


			$cur_time = time();
			$cur_day = intval( $cur_time / 86400 );
			$cur_hour = intval( $cur_time / 3600 );




			$cur_t5 = intval( $cur_time / 300 );
			$base_time = $cur_t5 + 96;
			$key = "#DASH_DATA_ALL#".$channel."#".$base_time;

			//file_put_contents("/tmp/test_1", $key."\n", FILE_APPEND);
			$cache_data = $this->redis_gsk->get($key);

			if(empty($cache_data)) {
				$key = "#DASH_DATA_ALL#".$channel."#".($base_time-1);
				$cache_data = $this->redis_gsk->get($key);
			}
			//file_put_contents("/tmp/test1", array($key, $cache_data), FILE_APPEND);
			$cache_data = json_decode($cache_data,true);
			if(!empty($cache_data))
			{
				$key = 'STAT_CHANNEL_USERS#' . $channel ;
				$cache_data['users_num'] = $this->redis_get($key);
				return $cache_data;
			}

			//$stat = $this->get_dash_stat();
			$stat['dau'] = $stat['dnu'] = array();
			foreach (range(0, 14) as $k) {
				$day = human_date($cur_time - (14 - $k) * 86400 );

				$key = 'STAT_CHANNEL_DAU#' . $channel . '#' . ($cur_day - 14 + $k);
				$key2 = 'STAT_CHANNEL_DAU#' . $channel . '#' . ($cur_day - 7 + $k - 30);
				array_push( $stat['dau'], array('day' => $day , 'v' => $this->redis_get($key), 'v2'=> $this->redis_get($key2)));

				$key = 'STAT_CHANNEL_DNU#' . $channel . '#' . ($cur_day - 14 + $k);
				$key2 = 'STAT_CHANNEL_DNU#' . $channel . '#' . ($cur_day - 7 + $k - 30);
				array_push( $stat['dnu'], array('day' => $day , 'v' => $this->redis_get($key) , 'v2'=> $this->redis_get($key2)) );
			}


			$cur_time = date("Y-m-d", time());
			$cur_time = strtotime($cur_time);
			$cur_t5 = intval( $cur_time / 300 );
			$base_time = $cur_t5 + 96;
			$cnt = 0;
			$stat['h5nu'] = $stat['h5au'] = array();
			$stat['h5nu_prev'] = $stat['h5au_prev'] = array();
/*
			foreach(range(0, 288) as $t) {
				//if(($cnt ++)%6 == 0 ) {
				//STAT_CHANNEL_5AU#0#4823824
				$key = 'STAT_CHANNEL_5AU#' . $channel . '#' . ($base_time + $t) ;
				$key2 = 'STAT_CHANNEL_5AU#' . $channel . '#' . ($base_time + $t - 288) ;
				$t_time = date("Y-m-d H:i", ($base_time + $t)*300);
				//array_push( $stat['h5au'],  array('t_time' => (($base_time + $t)*300) , 'v' => $this->redis_get($key),'v2' => $this->redis_get($key2)));
				array_push( $stat['h5au'],  $this->redis_get($key));
				array_push( $stat['h5au_prev'],  $this->redis_get($key2));



				$key = 'STAT_CHANNEL_5NU#' . $channel . '#' . ($base_time + $t) ;
				$key2 = 'STAT_CHANNEL_5NU#' . $channel . '#' . ($base_time + $t - 288) ;
				$t_time = date("Y-m-d H:i", ($base_time + $t)*300);
				//array_push( $stat['h5nu'],  array('t_time' => (($base_time + $t)*300) , 'v' => $this->redis_get($key), 'v2' => $this->redis_get($key) ) );
				array_push( $stat['h5nu'],   $this->redis_get($key)  );
				array_push( $stat['h5nu_prev'],  $this->redis_get($key2));
				//}
			}
*/


			$cur_time = time();
			$cur_day = intval( $cur_time / 86400 );
			$cur_hour = intval( $cur_time / 3600 );

			$stat['hau'] = $stat['hnu'] = array();
			$base_hour = $cur_hour - $cur_hour % 24;
			foreach (range(0, 24) as $h) {
				$key = 'STAT_CHANNEL_HAU#' . $channel . '#' . ($base_hour + $h) ;
				$key1 = 'STAT_CHANNEL_HAU#' . $channel . '#' . ($base_hour + $h-24) ;
				$key2 = 'STAT_CHANNEL_HAU#' . $channel . '#' . ($base_hour + $h-24*7) ;
				$t_time = date("Y-m-d H:i:s",($base_hour + $h - 8) * 3600);
				$s_time = date("H:i:s",($base_hour + $h - 8) * 3600);
				array_push( $stat['hau'],  array('t_time' => ($t_time) ,'s_time'=>$s_time , 'v' => $this->redis_get($key),'v2'=>$this->redis_get($key1),'v3'=>$this->redis_get($key2) )  );

				$key = 'STAT_CHANNEL_HNU#' . $channel . '#' . ($base_hour + $h) ;
				$key1 = 'STAT_CHANNEL_HNU#' . $channel . '#' . ($base_hour + $h-24) ;
				$key2 = 'STAT_CHANNEL_HNU#' . $channel . '#' . ($base_hour + $h-24*7) ;
				array_push( $stat['hnu'],  array('t_time' => ($t_time) ,'s_time'=>$s_time , 'v' => $this->redis_get($key),'v2'=>$this->redis_get($key1),'v3'=>$this->redis_get($key2) ) );
			}

			$key = 'STAT_CHANNEL_USERS#' . $channel ;
			$cache_data['users_num'] = $this->redis_get($key);
			return $stat;
		}

		//获取用户反馈信息
		public function get_feedback($filter = array())
		{
			$users = $this->mongo_op->feedback->find( array('f_user_name' => array( '$exists' => 0 )), array( 'u' => 1 ));
			foreach ($users as $user) {
				$uinfo = iterator_to_array($this->mongo_ol->user->find( array( 'f_uin' => intval($user['u']) ) ,  array('f_name' => 1, 'f_phone' => 1 )));
				foreach ($uinfo as $k => $v) 
				{
					$this->mongo_op->feedback->update(
	    				array('_id' => $user['_id'] ),
	    				array('$set' => array( 'f_user_name' => $v['f_name'], 'f_user_phone' =>  $v['f_phone'] )) );
				}
			}


			$p = $f = $list = array();

			$f['order_fd']   = '_id';
			$f['order_type'] = -1;
			$f['query'] = isset($filter['query']) ? trim($filter['query']) : '';

			/* 设置分页信息 */
			$p['cur_page'] = isset($filter['cur_page']) ? intval($filter['cur_page']) : 1;
			$p['rows_page']  = 50;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array( );
			/* 列表对象赋值 */
			$list['data']  = iterator_to_array( $this->mongo_op->feedback->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ));
			$list['filter'] = $f;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		//获取流米流量兑换流水信息
		public function get_flowlog($filter = array(), $is_export_excel = false)
		{
			$p = $f = $list = array();
			$res = array(
				'_id'=>0,
				'_class'=>0,
				'f_event_id'=>0,
			);
			$f['order_fd']   = 'f_pay_time';
			$f['order_type'] = -1;

			/* 设置分页信息 */
			$rows_page = 50;
			$p['cur_page'] = isset($filter['p']) ? intval($filter['p']) : 1;
			$p['rows_page']  = $rows_page;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array( );
			if(!empty($filter['f_name']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_name'=>$filter['f_name']), array('f_uin'));
				$where['f_uin'] =  intval($name['f_uin']);
			}
			if(!empty($filter['f_uin']))
			{
				$where['f_uin'] = intval($filter['f_uin']);
			}
			if(!empty($filter['f_carrieroperator']))
			{
				$where['f_carrieroperator'] = exchange_type($filter['f_carrieroperator']);
			}
			if(!empty($filter['f_post_package']))
			{
				$where['f_post_package'] = intval($filter['f_post_package']);
			}
			if(!empty($filter['f_mobile']))
			{
				$where['f_mobile'] = $filter['f_mobile'];
			}
			if(!empty($filter['f_order_no']))
			{
				$where['f_order_no'] = $filter['f_order_no'];
			}
			if(!empty($filter['f_status']))
			{
				$where['f_status'] = exchange_status($filter['f_status']);
			}
			if(!empty($filter['start_time']) || !empty($filter['end_time']))
			{
				$where['f_pay_time'] = array('$gte' => strtotime($filter['start_time'])*1000,'$lt' => strtotime($filter['end_time'])*1000);
			}
			/* 列表对象赋值 */
			//导出excel需要所有数据，不需设置分页
			if($is_export_excel)
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->flowLog->find($where, $res)->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}
			else
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->flowLog->find($where, $res)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}
			//添加用户昵称
			foreach($list['data'] as &$item)
			{
				$name= $this->mongo_ol->user->findOne(array('f_uin'=>$item['f_uin']), array('f_name'));
				$item['f_name'] = $name['f_name'];;
			}
			$list['filter'] = $filter;
			$p['max'] = array(
				'max_page'=>ceil($this->mongo_ol->flowLog->count($where)/$rows_page),
				'max_count'=>$this->mongo_ol->flowLog->count($where)
		);
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		/**
		 * 获取PV最近两周内每日数据
		 * @return array
		 */
		public function get_PV_data()
		{
			$count=$titles = $time = $list = array();
			$end_time = strtotime(date("Y-m-d"));
			$start_time = $end_time - 86400*7;//当前时间上两个周的数据
			$where =array('f_hpvh_day_time'=>array('$gte' => $start_time,'$lte' => $end_time));
			$msg = $this->mongo_ol->hotspot_pv_history->find($where, array('f_hpvh_id'=>1,'_id'=>0));
			foreach($msg as $item)
			{
				array_push($titles, $item['f_hpvh_id']);
			}
			//title去重
			$titles=array_flip($titles);
			$titles=array_flip($titles);
			$titles = array_values($titles);
			//对标题按照时间先进行排序
			$sort_titles = array();
			$titles = $this->mongo_ol->hotspot->find(array('f_hotspot_id' => array('$in'=>$titles)), array('f_hotspot_id', 'f_hotspot_sumbit_time'));
			foreach($titles as $title)
			{
				$sort_titles[$title['f_hotspot_id']] = intval($title['f_hotspot_sumbit_time']);
			}
			arsort($sort_titles);
			$sort_titles = array_keys($sort_titles);
			$flag = true;
			foreach($sort_titles as &$title)
			{
				$temp = array();
				$title_id = strval($title);//标题转换为字符串，有些标题为int会影响mongo查询
				//资讯标题和总浏览量
				$hotspot_title = $this->mongo_ol->hotspot->find(array('f_hotspot_id'=>$title_id), array('f_hotspot_pv_count'=>1, 'f_hotspot_title'=>1, 'f_hotspot_sumbit_time'=>1));
				if(count($hotspot_title) == 0)
				{
					array_push($temp, 0);
				}
				else
				{
					foreach($hotspot_title as $item)
					{
						array_push($temp, $item['f_hotspot_pv_count']);
						$title = array('title'=>$item['f_hotspot_title'], 'publish_time'=>human_time($item['f_hotspot_sumbit_time']));
						break;
					}
				}

				//按天查询标题每日浏览量，查不到就为0
				for($start=$end_time; $start > $start_time; $start -= 86400)  //按天遍历
				{
					if($flag)
					{
						array_push($time, date("Y/m/d", $start));
					}

					$condition = array(
						'f_hpvh_id' => $title_id,
						'f_hpvh_day_time' => array('$gte' => $start,'$lt' => $start + 86400)
					);
					$data = iterator_to_array($this->mongo_ol->hotspot_pv_history->find($condition, array('f_hpvh_count'=>1,'_id'=>0)));
					if(count($data) == 0)
					{
						array_push($temp, 0);
					}
					else
					{
						foreach($data as $d)
						{
							array_push($temp, $d['f_hpvh_count']);
							break;
						}
					}

				}
				array_push($count, $temp);
				$flag = false;
			}
			$list['time'] = $time;
			$list['titles'] = $sort_titles;
			$list['counts'] = $count;
			return $list;
		}

		public function get_PV_data_excel()
		{
			$count=$titles = $time = $list = array();
			$end_time = strtotime(date("Y-m-d"));
			$start_time = $end_time - 86400*10;//
			$where =array('f_hpvh_day_time'=>array('$gte' => $start_time,'$lte' => $end_time));
			$msg = $this->mongo_ol->hotspot_pv_history->find($where, array('f_hpvh_id'=>1,'_id'=>0));
			foreach($msg as $item)
			{
				array_push($titles, $item['f_hpvh_id']);
			}
			//title去重
			$titles=array_flip($titles);
			$titles=array_flip($titles);
			$titles = array_values($titles);
			//对标题按照时间先进行排序
			$sort_titles = array();
			$titles = $this->mongo_ol->hotspot->find(array('f_hotspot_id' => array('$in'=>$titles)), array('f_hotspot_id', 'f_hotspot_sumbit_time'));
			foreach($titles as $title)
			{
				$sort_titles[$title['f_hotspot_id']] = intval($title['f_hotspot_sumbit_time']);
			}
			arsort($sort_titles);
			$sort_titles = array_keys($sort_titles);
			$flag = true;
			foreach($sort_titles as &$title)
			{
				$temp = array();
				$title_id = strval($title);//标题转换为字符串，有些标题为int会影响mongo查询
				//资讯标题和总浏览量
				$hotspot_title = $this->mongo_ol->hotspot->find(array('f_hotspot_id'=>$title_id), array('f_hotspot_pv_count'=>1, 'f_hotspot_title'=>1, 'f_hotspot_sumbit_time'=>1));
				if(count($hotspot_title) == 0)
				{
					array_push($temp, 0);
				}
				else
				{
					foreach($hotspot_title as $item)
					{
						array_push($temp, $item['f_hotspot_pv_count']);
						$title = array('title'=>$item['f_hotspot_title'], 'publish_time'=>human_time($item['f_hotspot_sumbit_time']));
						break;
					}
				}

				//按天查询标题每日浏览量，查不到就为0
				for($start=$end_time; $start > $start_time; $start -= 86400)  //按天遍历
				{
					if($flag)
					{
						array_push($time, date("Y/m/d", $start));
					}

					$condition = array(
						'f_hpvh_id' => $title_id,
						'f_hpvh_day_time' => array('$gte' => $start,'$lt' => $start + 86400)
					);
					$data = iterator_to_array($this->mongo_ol->hotspot_pv_history->find($condition, array('f_hpvh_count'=>1,'_id'=>0)));
					if(count($data) == 0)
					{
						array_push($temp, 0);
					}
					else
					{
						foreach($data as $d)
						{
							array_push($temp, $d['f_hpvh_count']);
							break;
						}
					}

				}
				array_push($count, $temp);
				$flag = false;
			}
			$list['time'] = $time;
			$list['titles'] = $sort_titles;
			$list['counts'] = $count;
			return $list;
		}

		public function get_user_integral($filter = array(), $is_export_excel = false)
		{
			$p = $f = $list = array();

			$f['order_fd']   = 'f_log_in_time';
			$f['order_type'] = -1;

			/* 设置分页信息 */
			$rows_page = 50;
			$p['cur_page'] = isset($filter['p']) ? intval($filter['p']) : 1;
			$p['rows_page']  = $rows_page;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array( );
			if(!empty($filter['f_name']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_name'=>$filter['f_name']), array('f_uin'));
				$where['f_log_uid'] =  intval($name['f_uin']);
			}
			if(!empty($filter['f_uin']))
			{
				$where['f_log_uid'] = intval($filter['f_uin']);
			}
			if(!empty($filter['f_log_desc']))
			{
				$where['f_log_desc'] = $filter['f_log_desc'];
			}
			if(!empty($filter['f_phone']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_phone'=>$filter['f_phone']), array('f_uin'));
				$where['f_log_uid'] = intval($name['f_uin']);
			}

			if(!empty($filter['start_time']) || !empty($filter['end_time']))
			{
				$where['f_log_in_time'] = array('$gte' => strtotime($filter['start_time'])*1000,'$lt' => strtotime($filter['end_time'])*1000);
			}
			/* 列表对象赋值 */
			//导出excel需要所有数据，不需设置分页
			if($is_export_excel)
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->pointsLog->find($where)->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}
			else
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->pointsLog->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}
			//添加用户昵称
			foreach($list['data'] as &$item)
			{
				$name= $this->mongo_ol->user->findOne(array('f_uin'=>$item['f_log_uid']), array('f_name', 'f_phone'));
				$item['f_name'] = $name['f_name'];
				$item['f_phone'] = $name['f_phone'];;
			}

			$p['max'] = array(
				'max_page'=>ceil($this->mongo_ol->pointsLog->count($where)/$rows_page),
				'max_count'=>$this->mongo_ol->pointsLog->count($where)
			);
			$list['filter'] = $filter;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}


		public function get_user_integral_ranking($filter = array())
		{
			$p = $f = $list = array();

			$f['order_fd']   = 'f_points';
			$f['order_type'] = -1;

			/* 设置分页信息 */
			$p['cur_page'] = 1;
			$p['rows_page']  = 50;
			$p['row_start']  = 0;

			/* 列表对象赋值 */
			$list['data']  = iterator_to_array( $this->mongo_ol->user->find(array(), array('f_uin','f_phone','f_name','f_points'))->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			$list['filter'] = $f;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		public function get_user_integral_soaring($filter = array())
		{
			$p = $f = $list = array();

			$f['order_fd']   = 'f_pds_day_point';
			$f['order_type'] = -1;

			/* 设置分页信息 */
			$p['cur_page'] = 1;
			$p['rows_page']  = 50;
			$p['row_start']  = 0;

			$where['f_pds_day_time'] = array('$gte' => strtotime(date("Y-m-d",strtotime("-1 day")))*1000,'$lt' => strtotime(date("Y-m-d",time()))*1000);

			/* 列表对象赋值 */
			$list['data']  = iterator_to_array( $this->mongo_ol->points_day_statistics->find($where, array('f_pds_uid','f_pds_day_point','f_pds_day_time'))->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			//添加用户昵称
			foreach($list['data'] as &$item)
			{
				$name= $this->mongo_ol->user->findOne(array('f_uin'=>$item['f_pds_uid']), array('f_name', 'f_phone'));
				$item['f_name'] = $name['f_name'];
				$item['f_phone'] = $name['f_phone'];;
			}
			$list['filter'] = $f;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		public function getDayActiveUsers($day=0) {
			$key = 'V_DAU#16747';
			$users = $this->redis_gsk->sMembers($key);
			return $users;
		}

		public function get_user_info($filter = array(), $is_export_excel = false)
		{
			$p = $f = $list = array();

			$f['order_fd']   = 'f_account_create_time';
			$f['order_type'] = -1;

			/* 设置分页信息 */
			$rows_page = 50;
			$p['cur_page'] = isset($filter['p']) ? intval($filter['p']) : 1;
			$p['rows_page']  = $rows_page;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array( );
			if(!empty($filter['user_name']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_name'=>$filter['user_name']), array('f_uin'));
				$where['f_account_id'] =  intval($name['f_uin']);
			}
			if(!empty($filter['user_id']))
			{
				$where['f_account_id'] = intval($filter['user_id']);
			}
			if(!empty($filter['user_phone']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_phone'=>$filter['user_phone']), array('f_uin'));
				$where['f_account_id'] = intval($name['f_uin']);
			}
			if(!empty($filter['user_company']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_company'=>$filter['user_company']), array('f_uin'));
				$where['f_account_id'] = intval($name['f_uin']);
			}
			if(!empty($filter['user_code']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_code_id'=>$filter['user_code']), array('f_uin'));
				$where['f_account_id'] = intval($name['f_uin']);
			}


			if(!empty($filter['promotion_code']))
			{
				$uins =array();
				$name = $this->mongo_ol->user->findOne(array('f_code_id'=>$filter['promotion_code']), array('f_uin', 'f_user_type'));
				if($name['f_user_type'] == 2)
				{
					$users = $this->mongo_ol->salesperson_flow->find(array('f_sp_uin'=>intval($name['f_uin'])));
					foreach($users as $item)
					{
						array_push($uins, $item['f_rem_uin']);
					}
				}
				else
				{
					$body = "yq&".$filter['promotion_code'];
					$users = $this->mongo_ol->groupMsg->find(array('f_msg_body'=>$body));
					foreach($users as $item)
					{
						array_push($uins, $item['f_sender_id']);
					}
				}

				$uins = array_values($uins);
				$where['f_account_id'] = array('$in'=>$uins);
			}

			if(!empty($filter['start_time']) || !empty($filter['end_time']))
			{
				$where['f_account_create_time'] = array('$gte' => strtotime($filter['start_time'])*1000,'$lt' => strtotime($filter['end_time'])*1000);
			}
			/* 列表对象赋值 */
			//导出excel需要所有数据，不需设置分页
			if($is_export_excel)
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->account->find($where)->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}
			else
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->account->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}
			//添加用户昵称
			foreach($list['data'] as &$item)
			{
				$user = $this->mongo_ol->user->findOne(array('f_uin'=>$item['f_account_id']));
				$promotion_user = $this->mongo_ol->salesperson_flow->findOne(array('f_rem_uin'=>$item['f_account_id']));
				$item['f_name'] = $user['f_name'];
				$item['f_register_channel'] = $this->register_channel($item);
				$item['f_company'] = $user['f_company'];
				$item['f_company_type'] = $user['f_company_type'];
				$item['f_job_type'] = $user['f_job_type'];
				$item['f_years_of_working'] = $user['f_years_of_working'];
				$item['f_job_title'] = $user['f_job_title'];
				$item['f_last_req_time'] = $user['f_last_req_time'];
				$item['f_project_count'] = $this->project_count($user);
				$item['f_points'] = $user['f_points'];
				$item['f_code_id'] = $user['f_code_id'];
				$item['f_promotion_id'] = $promotion_user['f_sp_uin'];
				if(isset($promotion_user['f_sp_uin']) && !empty($promotion_user['f_sp_uin']))
				{
					$pro_user = $this->mongo_ol->user->findOne(array('f_uin'=>$promotion_user['f_sp_uin']), array('f_code_id'));
					$item['f_promotion_code_id'] = $pro_user['f_code_id'];
				}
			}

			$p['max'] = array(
				'max_page'=>ceil($this->mongo_ol->account->count($where)/$rows_page),
				'max_count'=>$this->mongo_ol->account->count($where)
			);
			$list['filter'] = $filter;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		private  function register_channel($user)
		{
			if(!empty($user['f_account_gly_id']))
			{
				return '广联云';
			}
			else if(!empty($user['f_account_weixin_id']))
			{
				return '微信';
			}
			else if(!empty($user['f_account_qq_id']))
			{
				return 'QQ';
			}
			else if(!empty($user['f_account_phone']))
			{
				return '手机';
			}
			else
			{
				return '其他';
			}
		}

		private  function project_count($user)
		{
			$count = 0;
			if(count($user['f_project_list']) > 0)
			{
				foreach($user['f_project_list'] as $item)
				{
					if($item['f_status'] == true)
					{
						$count ++;
					}
				}
			}
			return $count;
		}

		public function get_project_info($filter = array(), $is_export_excel = false)
		{
			$p = $f = $list = array();
			$need = array(
				'f_prj_id',
				'f_prj_name',
				'f_auth_time',
				'f_add_time',
				'f_prj_type',
				'f_member_count',
				'f_auth_status',
				'f_c_uin'
			);
			$f['order_fd']   = 'f_add_time';
			$f['order_type'] = -1;

			/* 设置分页信息 */
			$rows_page = 50;
			$p['cur_page'] = isset($filter['p']) ? intval($filter['p']) : 1;
			$p['rows_page']  = $rows_page;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array( );
			if(!empty($filter['project_name']))
			{
				$where['f_prj_name'] =  $filter['project_name'];
			}
			if(!empty($filter['project_id']))
			{
				$where['f_prj_id'] = $filter['project_id'];
			}

			if(!empty($filter['member_count']))
			{
				$where['f_member_count'] = array('$gte' => intval($filter['member_count']));
			}

			if(!empty($filter['project_id']))
			{
				$where['f_prj_id'] = $filter['project_id'];
			}

			if(!empty($filter['user_phone']))
			{
				$name = $this->mongo_ol->user->findOne(array('f_phone'=>$filter['user_phone']), array('f_uin'));
				$where['f_c_uin'] = intval($name['f_uin']);
			}
			if(!empty($filter['user_id']))
			{
				$where['f_c_uin'] = intval($filter['user_id']);
			}
			if(!empty($filter['project_state']))
			{
				if($filter['project_state'] != 5)
				{
					$where['f_auth_status'] = intval($filter['project_state']);
				}
			}

			if(!empty($filter['start_time']) || !empty($filter['end_time']))
			{
				$where['f_add_time'] = array('$gte' => strtotime($filter['start_time']),'$lt' => strtotime($filter['end_time']));
			}
			/* 列表对象赋值 */
			//导出excel需要所有数据，不需设置分页
			if($is_export_excel)
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->project->find($where, $need)->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}
			else
			{
				$list['data']  = iterator_to_array( $this->mongo_ol->project->find($where, $need)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			}

			for($i=count($list['data'])-1; $i>0; $i--)
			{

			}
			//添加用户昵称
			foreach($list['data'] as &$item)
			{
				$user = $this->mongo_ol->user->findOne(array('f_uin'=>$item['f_c_uin']), array('f_name', 'f_phone', 'f_create_time', 'f_company'));
				$item['f_name'] = $user['f_name'];
				$item['f_company'] = $user['f_company'];
				$item['f_phone'] = $user['f_phone'];
				$item['f_create_time'] = $user['f_create_time'];
			}

			$p['max'] = array(
				'max_page'=>ceil($this->mongo_ol->project->count($where)/$rows_page),
				'max_count'=>$this->mongo_ol->project->count($where)
			);
			$list['filter'] = $filter;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		/**
		 * 获取某个项目的详细信息
		 * @param array $filter
		 * @return array
		 */
		public function get_project_details($proj_id)
		{
			$need = array('f_uin', 'f_phone', 'f_name');
			$list = $this->mongo_ol->project->findOne(array('f_prj_id' => $proj_id));

			if(!empty($list['f_c_uin']))
			{
				$user = $this->mongo_ol->user->findOne(array('f_uin'=>$list['f_c_uin']), $need);
				$list['f_c_uin'] = array(
					'f_uin' => $user['f_uin'],
					'f_name' => $user['f_name'],
					'f_phone' => $user['f_phone']
				);
			}

			$proj_auth_history = $this->mongo_ol->project_auth->findOne(array('f_prj_id' => $proj_id));
			$list['proj_history'] = $proj_auth_history;
			/* 返回 */
			return $list;
		}

		public function project_apply($data)
		{
			$prj_id = $data['prj_id'];
			$reason ="";
			$url_online_freetime = 'http://api.zy.glodon.com/event/freetime/project/get?projectId='.$prj_id;
			$url_offline_freetime = 'http://api.zhuyou.glodon.com/event/freetime/project/get?projectId='.$prj_id;

			$url_online_search = 'http://api.zy.glodon.com/search/project/index/update/'.$prj_id;
			$url_offline_search = 'http://api.zhuyou.glodon.com/search/project/index/update/'.$prj_id;

			$url_online_xiaomishu_succ = 'http://10.128.63.250:5000/send_msg/prj_auth_succ/%s/&&cardatctionscheme|%s|&&cardfinishactionscheme|%s|%s|&&carddesc|%s/';;
			$url_offline_xiaomishu_succ ='http://192.168.164.200:5000/send_msg/prj_auth_succ/%s/&&cardatctionscheme|%s|&&cardfinishactionscheme|%s|%s|&&carddesc|%s/';

			$url_online_xiaomishu_fail ='http://10.128.63.250:5000/send_msg/prj_auth_fail/%s/&&cardatctionscheme|%s|&&cardfinishactionscheme|%s|&&carddesc|%s/';
			$url_offline_xiaomishu_fail ='http://192.168.164.200:5000/send_msg/prj_auth_fail/%s/&&cardatctionscheme|%s|&&cardfinishactionscheme|%s|&&carddesc|%s/';

			$operater = $this->session->user_id;
			$audit = $data['prj_audit'];

			$time_limit = empty($data['prj_phone_litmit'])? 100:$data['prj_phone_litmit'];
			//先判断是否有进行审核操作
			if(!empty($audit) || $audit==0)
			{
				$reason = $data['prj_reject_reason'];
				$data = array();//修改项目状态数据
				$state = null;
				if($audit == 1)
				{
					$state = 3;//认证通过
					$data['f_prj_level'] = 0;
					$data['f_prj_all_freetime_num'] = 100;
					$data['f_prj_max_freetime'] = $time_limit;//正式上线改为100
					$data['f_auth_status'] = $state;
					$data['f_auth_time'] = time();
					$this->mongo_ol->project->update(array('f_prj_id'=>$prj_id), array('$set'=>$data), array('upsert'=>true));
					send_request_get($url_online_freetime);
					send_request_get($url_online_search);

					//推送小秘书
					$c_uin = $this->mongo_ol->project->findOne(array('f_prj_id'=>$prj_id), array('f_c_uin'));
					$url_online_xiaomishu_succ = sprintf($url_online_xiaomishu_succ, $c_uin['f_c_uin'], $prj_id, $prj_id, $time_limit, $time_limit);
					send_request_get($url_online_xiaomishu_succ);
				}
				else
				{
					$state = 4;//认证未通过
					//更新project表
					$data['f_auth_status'] = $state;
					$data['f_auth_time'] = time();
					$this->mongo_ol->project->update(array('f_prj_id'=>$prj_id), array('$set'=>$data), array('upsert'=>true));
					$this->mongo_ol->project_auth->update(array('f_prj_id'=>$prj_id), array('$set'=>array('f_unpass_desc'=>$reason)));

					//推送小秘书
					$c_uin = $this->mongo_ol->project->findOne(array('f_prj_id'=>$prj_id), array('f_c_uin'));
					$encode_reason = rawurlencode($reason);
					$url_online_xiaomishu_fail = sprintf($url_online_xiaomishu_fail, $c_uin['f_c_uin'], $prj_id, $prj_id, $encode_reason);
					send_request_get($url_online_xiaomishu_fail);
				}
				//项目审核历史
				$history = array(
					'f_audit_name' => $operater,
					'f_auth_status' => $state,
					'f_desc' => $reason,
					'f_time' => time()
				);
				$this->mongo_ol->project_auth->update(array('f_prj_id'=>$prj_id), array('$push'=>array('f_auth_list'=>$history)));
			}

			//修改通话额度,单独的历史记录，跟审核区别开
			if(!empty($data['prj_phone_litmit']))
			{
				//项目审核历史
				$history = array(
					'f_audit_name' => $operater,
					'f_auth_status' => 999,
					'f_desc' => $data['prj_phone_litmit']."分钟",
					'f_time' => time()
				);
				$this->mongo_ol->project_auth->update(array('f_prj_id'=>$prj_id), array('$push'=>array('f_auth_list'=>$history)));
				$this->mongo_ol->project->update(array('f_prj_id'=>$prj_id), array('$set'=>array('f_prj_max_freetime'=>$data['prj_phone_litmit'])), array('upsert'=>true));
			}
		}

		/**
		 * 操作用户积分
		 * @param $data
		 */
		public function operate_user_points($data)
		{
			$uin = intval($data['uid']);
			$jifen = intval($data['points']);
			$reason = '【'.$this->session->user_id.'】'.$data['reason'];
			$user = $this->mongo_ol->user->findOne(array('f_uin'=> $uin), array('f_points'));
			$points = $user['f_points'] + $jifen;
			$this->mongo_ol->user->update(array('f_uin' => $uin), array('$set'=>array('f_points'=>$points)));

			//写入用户积分操作log
			$log =array(
				'f_log_uid' => $uin,
				'f_log_desc' => $reason,
				'f_log_point' => $jifen,
				'f_log_type' => 23,
				'f_log_in_time' => time()
			);
			$this->mongo_ol->pointsLog->insert($log);
			return true;
		}

		public function get_activity_position()
		{
			$f['order_fd']   = 'f_position_id';
			$f['order_type'] = 1;
			$res = iterator_to_array($this->mongo_op->activity_position->find()->sort( array( $f['order_fd'] => $f['order_type'] ) ) );
			return $res;
		}

		public function update_position_state($data)
		{
			$count = $this->mongo_op->activity_position->count(array('f_position_id'=>intval($data['id'])));
			if($count != 0)
			{
				$this->mongo_op->activity_position->update(array('f_position_id'=>intval($data['id'])),
					array('$set'=>array('f_position_state'=>intval($data['state']))));
			}
		}

		public function do_add_activity_pt($data)
		{
			if(!$this->check_begintime_overlap_add(intval($data['position_id']), strtotime($data['begintime'])))
			{
				$count = $this->mongo_op->activity_pt_history->count(array('f_activity_pt_id'=>intval($data['position_id'])));
				$operater = $this->session->user_id;
				$pic = array(
					'f_pic_order'=>$count + 1,
					'f_activity_pt_id'=>intval($data['position_id']),
					'f_activity_pt_name'=>activity_position_name($data['position_id']),
					'f_big_pic_url'=>$data['bigpic_link'],
					'f_small_pic_url_one'=>$data['smallpic_link_one'],
					'f_small_pic_url_two'=>$data['smallpic_link_two'],
					'f_pic_need_dynamic'=>intval($data['need_dynamic']),
					'f_pic_size'=>$data['pic_size'],
					'f_jump_url'=>$data['jump_link'],
					'f_begin_time'=>strtotime($data['begintime']),
					'f_finish_time'=>strtotime($data['finishtime']),
					'f_continue_time'=>intval($data['continue_time']),
					'f_text_big'=>$data['text_big'],
					'f_text_middle'=>$data['text_middle'],
					'f_text_small'=>$data['text_small'],
					'f_back_color'=>$data['back_color'],
					'f_operater'=>$operater,
					'f_operater_type'=>1,//添加
					'f_operater_time'=>new MongoInt64(time())
				);
				$this->mongo_op->activity_pt_history->insert($pic);
				return true;
			}
			else
			{
				return false;
			}
		}

		/**
		 * 检查生效时间有没有重叠——添加
		 * @param $time
		 */
		private function check_begintime_overlap_add($pt_id, $time)
		{
			$res = false;
			$f = array();
			$f['order_fd']   = 'f_pic_order';
			$f['order_type'] = -1;
			$all_time = iterator_to_array($this->mongo_op->activity_pt_history->find(array('f_activity_pt_id'=>intval($pt_id)),
				array('_id'=>0, 'f_finish_time'=>1))->sort( array( $f['order_fd'] => $f['order_type']))->limit(1));
			if(count($all_time) != 0)
			{
				if(intval($all_time[0]['f_finish_time']) >= intval($time))
				{
					$res = true;
				}
			}
			return $res;
		}

		public function get_activity_pt_history($pt_id)
		{
			$f = array();
			$f['order_fd']   = 'f_pic_order';
			$f['order_type'] = 1;
			$res = iterator_to_array($this->mongo_op->activity_pt_history->find(array('f_activity_pt_id'=>intval($pt_id)))->
			sort( array( $f['order_fd'] => $f['order_type'])));
			return $res;
		}

		public function do_change_activity_pt($data)
		{
			//检查生效日期是否重叠
			if($this->check_begintime_overlap_change($data['position_id'], $data['pic_order'], strtotime($data['begintime'])))
			{
				return 101;
			}
			//检查失效日期是否重叠
			if($this->check_finishtime_overlap_change($data['position_id'], $data['pic_order'], strtotime($data['finishtime'])))
			{
				return 102;
			}

			$operater = $this->session->user_id;
			$pic = array(
				'f_big_pic_url'=>$data['bigpic_link'],
				'f_small_pic_url_one'=>$data['smallpic_link_one'],
				'f_small_pic_url_two'=>$data['smallpic_link_two'],
				'f_pic_need_dynamic'=>intval($data['need_dynamic']),
				'f_pic_size'=>$data['pic_size'],
				'f_jump_url'=>$data['jump_link'],
				'f_begin_time'=>strtotime($data['begintime']),
				'f_finish_time'=>strtotime($data['finishtime']),
				'f_continue_time'=>intval($data['continue_time']),
				'f_text_big'=>$data['text_big'],
				'f_text_middle'=>$data['text_middle'],
				'f_text_small'=>$data['text_small'],
				'f_back_color'=>$data['back_color'],
				'f_operater'=>$operater,
				'f_operater_type'=>2,//修改
				'f_operater_time'=>new MongoInt64(time())
			);
			$this->mongo_op->activity_pt_history->update(array('f_activity_pt_id'=>intval($data['position_id']), 'f_pic_order'=>intval($data['pic_order'])), array('$set'=>$pic));
			return 100;
		}

		public function check_begintime_overlap_change($pt_id, $pic_order, $time)
		{
			$res = false;
			$pic = $this->mongo_op->activity_pt_history->findOne(array('f_activity_pt_id'=>intval($pt_id), 'f_pic_order'=>intval($pic_order)-1), array('f_finish_time'));

			if(count($pic) != 0)
			{
				if(intval($pic['f_finish_time']) >= intval($time))
				{
					$res = true;
				}
			}
			return $res;
		}

		public function check_finishtime_overlap_change($pt_id, $pic_order, $time)
		{
			$res = false;
			$pic = $this->mongo_op->activity_pt_history->findOne(array('f_activity_pt_id'=>intval($pt_id), 'f_pic_order'=>intval($pic_order)+1), array('f_begin_time'));

			if(count($pic) != 0)
			{
				if(intval($pic['f_begin_time']) <= intval($time))
				{
					$res = true;
				}
			}
			return $res;
		}

		/**
		 * 2016年新年活动统计数据--脚本
		 */
		public function newyear_game_pv_data()
		{
			$today = strtotime(date("Y-m-d")." 19:00:00");
			$yesterday = strtotime(date("Y-m-d",strtotime("-1 day"))." 19:00:00");

			$in_data = $this->inside_pv_data($today, $yesterday);
			$out_data = $this->outside_pv_data($today, $yesterday);
			$data = array_merge($in_data, $out_data);
			$data['f_time'] = new MongoInt64(time());
			$this->mongo_op->newyear_game_stat->insert($data);
		}

		private function inside_pv_data($today, $yesterday)
		{

			$res= $where= $uv_visit= $uv_play= array();
			$where['page']="GamePage";
			$where['event']='Visit';
			$where['env']="1";
			$where['timestamp'] = array('$gte' => $yesterday,'$lt' => $today);
			//内部用户--游戏页访问量PV
			$in_Visit_pv =$this->mongo_op->report->count($where);
			$msg = $this->mongo_ol->report->find($where, array('u'=>1,'_id'=>0));
			foreach($msg as $item)
			{
				array_push($uv_visit, $item['u']);
			}
			$uv=array_flip($uv_visit);
			$uv=array_flip($uv_visit);
			//内部用户--游戏页访问量UV
			$in_Visit_uv = count($uv_visit);

			//内部用户--参与游戏量PV
			$where['event']='Play';
			$in_Play_pv =$this->mongo_op->report->count($where);

			$msg = $this->mongo_ol->report->find($where, array('u'=>1,'_id'=>0));
			foreach($msg as $item)
			{
				array_push($uv_play, $item['u']);
			}
			$uv=array_flip($uv_play);
			$uv=array_flip($uv_play);
			//内部用户--参与游戏量UV
			$in_Play_uv = count($uv_play);

			$where =array();
			$where['f_uin'] = array('$gt' => 0);
			$where['f_create_time'] = array('$gte' => $yesterday,'$lt' => $today);
			//内部用户--抽奖量PV
			$in_draw_pv = $this->mongo_ol->newYearGameLog->count($where);
			//内部用户--中奖量PV
			$where['f_prize_code'] = array('$ne'=>0);
			$in_winning_pv = $this->mongo_ol->newYearGameLog->count($where);

			//内部用户--分享必中分享量PV
			$where =array();
			$where['f_type'] = 1;
			$where['f_create_time'] = array('$gte' => $yesterday,'$lt' => $today);
			$in_share_winning_pv = $this->mongo_ol->newYearGameLog->count($where);

			$where =array();
			$where['env']="1";
			$where['timestamp'] = array('$gte' => $yesterday,'$lt' => $today);
			//内部用户--游戏结果页分享量PV
			$where['page']="GameResultPage";
			$where['event']='ShareGameResult';
			$in_GameResultPage_pv =$this->mongo_op->report->count($where);

			//内部用户--中奖页分享量PV
			$where['page']="GameBonusPage";
			$where['event']='Share';
			$in_GameBonusPage_pv =$this->mongo_op->report->count($where);

			//内部用户--奖品包分享量PV
			$where['page']="BonusListPage";
			$where['event']='Share';
			$in_BonusListPage_pv =$this->mongo_op->report->count($where);

			$res = array(
				'f_GamePage_Visit_inside_uv'=>$in_Visit_uv,
				'f_GamePage_Visit_inside_pv'=>$in_Visit_pv,
				'f_GamePage_Play_inside_uv'=>$in_Play_uv,
				'f_GamePage_Play_inside_pv'=>$in_Play_pv,
				'f_Draw_inside_pv'=>$in_draw_pv,
				'f_Wininng_inside_pv'=>$in_winning_pv,
				'f_Share_winning_inside_pv'=>$in_share_winning_pv,
				'f_GameResultPage_inside_pv'=>$in_GameResultPage_pv,
				'f_GameBonusPage_inside_pv'=>$in_GameBonusPage_pv,
				'f_BonusListPage_inside_pv'=>$in_BonusListPage_pv
			);

			return $res;
		}

		private function outside_pv_data($today, $yesterday)
		{

			$res= $where= $uv= array();
			$where['timestamp'] = array('$gte' => $yesterday,'$lt' => $today);
			$where['page']="GamePage";
			$where['event']='Visit';
			$where['env']="2";
			//外部用户--游戏页访问量PV
			$out_Visit_pv =$this->mongo_op->report->count($where);

			//外部用户--参与游戏量PV
			$where['event']='Play';
			$out_Play_pv =$this->mongo_op->report->count($where);

			$where =array();
			$where['f_uin'] = -1;
			$where['f_create_time'] = array('$gte' => $yesterday,'$lt' => $today);
			//外部用户--抽奖量PV
			$out_draw_pv = $this->mongo_ol->newYearGameLog->count($where);
			//外部用户--中奖量PV
			$where['f_prize_code'] = array('$ne'=>0);
			$out_winning_pv = $this->mongo_ol->newYearGameLog->count($where);

			//H5验证手机号（去老用户）
			$where =array();
			$where['f_union_id']=array('$ne'=>"");
			$where['f_phone']=array('$ne'=>"");
			$where['f_phone']=array('$ne'=>null);
			$where['f_is_old_user']=0;
			$where['f_verify_time'] = array('$gte' => $yesterday,'$lt' => $today);
			$out_verify_phone_pv = $this->mongo_ol->newYearGameToUser->count($where);

			//新增用户量(验证手机号中的新增用户)
			$where['f_uin'] = array('$ne'=>-1);
			$where['f_uin'] = array('$ne'=>null);
			$out_verify_register_user_pv =$this->mongo_ol->newYearGameToUser->count($where);

			$where =array();
			$where['timestamp'] = array('$gte' => $yesterday,'$lt' => $today);
			//title更多分享量PV
			$where['page']="WeixinPage";
			$where['event']='Share';
			$WeixinPage_Share_pv =$this->mongo_op->report->count($where);


			$where =array();
			$where['env']="2";
			$where['timestamp'] = array('$gte' => $yesterday,'$lt' => $today);
			//外部用户--游戏结果页分享量PV
			$where['page']="GameResultPage";
			$where['event']='ShareGameResult';
			$out_GameResultPage_pv =$this->mongo_op->report->count($where);

			//外部用户--中奖页分享量PV
			$where['page']="GameBonusPage";
			$where['event']='Share';
			$out_GameBonusPage_pv =$this->mongo_op->report->count($where);

			$res = array(
				'f_GamePage_Visit_outside_pv'=>$out_Visit_pv,
				'f_GamePage_Play_outside_pv'=>$out_Play_pv,
				'f_Draw_outside_pv'=>$out_draw_pv,
				'f_Winning_outside_pv'=>$out_winning_pv,
				'f_Verify_phone_pv'=>$out_verify_phone_pv,
				'f_Verify_register_pv'=>$out_verify_register_user_pv,
				'f_WeixinPage_pv'=>$out_verify_register_user_pv,
				'f_GameResultPage_outside_pv'=>$out_GameResultPage_pv,
				'f_GameBonusPage_outside_pv'=>$out_GameBonusPage_pv,
			);

			return $res;
		}
	}
?>
