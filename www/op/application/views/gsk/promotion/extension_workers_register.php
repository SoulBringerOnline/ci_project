<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>筑友_推广人员注册</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

	<link href="<?=base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?=base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?=base_url('assets/css/pixel-admin.min.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?=base_url('assets/css/widgets.min.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?=base_url('assets/css/pages.min.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?=base_url('assets/css/rtl.min.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?=base_url('assets/css/themes.min.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?=base_url('assets/css/style.css') ?>" rel="stylesheet" type="text/css">

	<script src="<?=base_url('assets/js/jquery.min.js') ?>"></script>
	<script src="<?=base_url('assets/js/bootstrap.min.js') ?>"></script>
	<script src="<?=base_url('assets/js/pixel-admin.min.js') ?>"></script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?=base_url('favicon.ico') ?>" >

</head>
<body class="page-signin theme-default">
<div id="page-signin-bg">
	<!-- Background overlay -->
	<!-- <div class="overlay"></div> -->
	<!-- Replace this with your bg image -->
	<img src="<?=base_url('assets/img/sign/' . rand(1,9) . '.jpg');?>" alt="">
</div>
<<div class="signin-container">
<form class="form-horizontal "  role="form" method="post" action="<?php echo site_url('Gsk_pro_register/submit_register');?>">
		<div class="signin-form">
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">姓名：</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="name" name="name">
				</div>
			</div>
			<div class="form-group">
				<label for="IDcard" class="col-sm-2 control-label">身份证号：</label>
				<div class="col-sm-10" >
					<input type="text" class="form-control" id="IDcard" name=" IDcard">
				</div>
			</div>
			<div class="form-group">
				<label for="phone" class="col-sm-2 control-label">手机号：</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="phone" name="phone">
				</div>
			</div>
			<div class="form-inline form-group" style="float: right;margin-right: 20px">
				<div class="form-group" style="margin: 10px 5px 0 0">
						<button type="submit" class="btn btn-default" name="submit">提交</button>
				</div>
				<div class="form-group">
						<button type="submit" class="btn btn-default" name="login">登录</button>
				</div>
			</div>

		</div>

	</form>
	</div>
</body>