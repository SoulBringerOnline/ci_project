<?php
	header('Access-Control-Allow-Origin: *');
	require_once('../includes/init.php');

	try{
		$connection = new MongoClient($_CFG['mongodb_gsk_ol']);
		$mongo_ol = new MongoDB($connection, 'gsk_ol');

		$help_type = array();
		$help_type1 = array("知识", "项目", "看点");
		$help_type2 = array("账号与登录", "对话", "我的");
		$help_type3 = array("其他");
		array_push($help_type, $help_type1);
		array_push($help_type, $help_type2);
		array_push($help_type, $help_type3);

		$url = 'http://zy.glodon.com/api/help/help_model.php?f_help_id=';
		$data = array();
		$f=$first=$where = array();
		/* 排序字段初始化 */
		$f['order_fd']   = 'f_help_sort';
		$f['order_type'] = 1;

		//常见问题
		$where['f_comment_question'] = "1";
		$msg = $mongo_ol->help_document->find($where)->sort( array( $f['order_fd'] => $f['order_type'] ) );
		foreach($msg as $item)
		{
			$second = array();
			$second['range'] = "second";
			$second['title'] = $item['f_help_title'];
			$second['link'] = $url.$item['f_help_id'];
			$second['parentTitle'] = "常见问题";
			array_push($first, $second);
		}
		$data['commonQue'] = $first;

		$first_temp = array();
		$type = 0;
		for($t=0; $t<count($help_type); $t++)
		{
			$first = $help_temp = array();
			$help_temp = $help_type[$t];
			for ($i = 0; $i < count($help_temp); $i++)
			{
				$type++;
				$second = array();
				$second['tag'] = "all";
				$second['range'] = "first";
				$second['title'] = $help_temp[$i];
				$where['f_help_classify'] = $type;//数据库中从1开始存的help_type
				$where['f_comment_question'] = "0";//同时，不是常见问题
				$msg = $mongo_ol->help_document->find($where)->sort(array($f['order_fd'] => $f['order_type']));
				$temp = array();
				foreach ($msg as $item) {
					$third = array();
					$third['range'] = "second";
					$third['title'] = $item['f_help_title'];
					$third['link'] = $url . $item['f_help_id'];
					$third['parentTitle'] = $help_temp[$i];
					array_push($temp, $third);
				}
				$second['children'] = $temp;
				array_push($first, $second);
			}
			array_push($first_temp, $first);
		}
		$data['allQue'] = $first_temp;
		echo json_encode($data);
		$connection->close();
	}
	catch(Exception $e)
	{
		$log->logInfo('[ERROR]' , $e);
		make_json_fail();
	}

?>
