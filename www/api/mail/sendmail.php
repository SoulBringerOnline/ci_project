<?php
	/* 文件头信息 */
	header('Content-Type: text/html; charset=utf-8');
	require_once "email.class.php";
	//******************** 配置信息 ********************************
	$smtpserver = "smtp.qq.com";//SMTP服务器
	$smtpserverport =25;//SMTP服务器端口
	$smtpusermail = "718826339@qq.com";//SMTP服务器的用户邮箱
	$smtpemailto = '853985414@qq.com';//发送给谁
	$smtpuser = "718826339";//SMTP服务器的用户帐号
	$smtppass = "numojkxvqkozbcdf";//SMTP服务器的用户密码
	$mailtitle = 'test邮件';//邮件主题
	$mailcontent = file_get_contents("content.php");//邮件内容
	$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
	//************************ 配置信息 ****************************
	$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
	$smtp->debug = true;//是否显示发送的调试信息
	$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

	echo "<div style='width:300px; margin:36px auto;'>";
	if($state==""){
		echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
		echo "<a href='index.html'>点此返回</a>";
		exit();
	}
	echo "恭喜！邮件发送成功！！";
	echo "<a href='index.html'>点此返回</a>";
	echo "</div>";
?>