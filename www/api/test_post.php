<html>
<title>php+jquery+ajax+json简单小例子</title>
<?php
	require_once('includes/init.php');
	$connection = new MongoClient($_CFG['mongodb_op']);
	$mongo_op = new MongoDB($connection, 'gsk_ol');
	$postpackage = $mongo_op->hotspot->findOne(array(),array("f_hotspot_title"));
	header("Content-Type:text/html;charset=utf-8");
?>
<head>
	<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#subbtn").click(function() {
				var params = $("input").serialize();
				var url = "test.php";
				$.ajax({
					type: "post",
					url: url,
					dataType: "json",
					contentType: "application/json",
					data: params,
					success: function(msg){
						alert("success");
						var backdata = "您提交的姓名为：" + msg.name +
							"<br /> 您提交的密码为：" + msg.password + "<a href='http://www.baidu.com'>百度</a>";
						$("#backdata").html(backdata);
						$("#backdata").css({color: "green"});
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert(XMLHttpRequest.status);
						alert(XMLHttpRequest.readyState);
						alert(textStatus);
					}
				});
			});

		});

	</script>
</head>
<body>
<p><label for="name">姓名：</label>
	<input id="name" name="name" type="text" />
</p>

<p><label for="password">密码：</label>
	<input id="password" name="password" type="password" />
</p>

<span id="backdata"></span>
<p><input id="subbtn" type="button" value="提交数据" /></p>
</body>
</html> 