<?
	require_once('../includes/init.php');
	/* 文件头信息 */
	header('Content-Type: text/html; charset=utf-8');
    $data = array();
	if(isset($_REQUEST['f_help_id']))
	{
		$help_id = $_REQUEST['f_help_id'];
		try
		{
			$connection = new MongoClient($_CFG['mongodb_gsk_ol']);
			$mongo_ol = new MongoDB($connection, 'gsk_ol');
			$data = $mongo_ol->help_document->findOne(array('f_help_id' => $help_id));
			$connection->close();

		}
		catch(Exception $e)
		{
			$data['f_help_content'] = $e;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=320,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no, email=no">
	<title>问题详情</title>
	<link rel="stylesheet" href="http://zhuyou-mat.oss-cn-beijing.aliyuncs.com/help%2Fdest%2Fcss%2Fhelp.min_1123.css" />
	<script src="http://zhuyou-mat.oss-cn-beijing.aliyuncs.com/help%2Fjs%2FjsBridge.js"></script>
</head>
<body>

<div class="question">
	<p class="title"><?=isset($data['f_help_title'])?$data['f_help_title']:"help_id不存在"?></p>
	<?=isset($data['f_help_content'])?str_replace('<p></p>', '', str_replace('&nbsp;', "", $data['f_help_content'])):""?>
	<p class="feedback">意见反馈</p>
</div>

</body>
</html>