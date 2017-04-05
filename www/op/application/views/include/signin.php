<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>筑友</title>
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
	<style>
		.warning_word{color: #DE0B0B;display: block;
			margin-bottom: 5px;
			margin-top: 5px;
			position: relative;
			text-align: center;}
	</style>
</head>

<body class="page-signin theme-default">


	<div id="page-signin-bg">
		<!-- Background overlay -->
		<!-- <div class="overlay"></div> -->
		<!-- Replace this with your bg image -->
		<img src="<?=base_url('assets/img/sign/' . rand(1,9) . '.jpg');?>" alt="">
	</div>
	<!-- / Page background -->

	<!-- Container -->
	<div class="signin-container">
		<!-- Left side -->
		<div class="signin-info text-center">
			<a href="#" class="logo">
				<img src="<?=base_url('assets/img/logo.png');?>" alt="" style="width:60px;margin-top: -5px;">&nbsp;&nbsp;&nbsp;筑友
			</a> <!-- / .logo -->
			<hr>
			<div class="slogan ">
				建筑人的工作社交圈
			</div> <!-- / .slogan -->
		</div>
		<!-- / Left side -->

		<!-- Right side -->
		<div class="signin-form">
			<!-- Form -->
			<form method="post" action="<?php echo site_url('main/signin');?>"  id="signin-form_id">
				<div class="signin-text">
					<span>登 录</span>
				</div> <!-- / .signin-text -->
				<div class="warning_word">
					<span>请用广联达账号登录，权限开通请邮件管理员</span>
				</div>

				<div class="form-group w-icon">
					<input type="text" name="signin_username" id="username_id" class="form-control input-lg" placeholder="用户名">
					<span class="fa fa-user signin-form-icon"></span>
				</div> <!-- / Username -->

				<div class="form-group w-icon">
					<input type="password" name="signin_password" id="password_id" class="form-control input-lg" placeholder="密码">
					<span class="fa fa-lock signin-form-icon"></span>
				</div> <!-- / Password -->

				<div class="signin-with form-actions text-center">
					<input type="submit" value="登 录" class="signin-btn bg-primary">
				</div>
			</form>
		</div>
		<!-- Right side -->
	</div>
	<!-- / Container -->


<script type="text/javascript">
	// Resize BG
	init.push(function () {
		var $ph  = $('#page-signin-bg'),
		    $img = $ph.find('> img');

		$(window).on('resize', function () {
			$img.attr('style', '');
			if ($img.height() < $ph.height()) {
				$img.css({
					height: '100%',
					width: 'auto'
				});
			}
		});
	});


	// Setup Sign In form validation
	init.push(function () {
		$("#signin-form_id").validate({ focusInvalid: true, errorPlacement: function () {} });
		
		// Validate username
		$("#username_id").rules("add", {
			required: true,
			minlength: 3
		});

		// Validate password
		$("#password_id").rules("add", {
			required: true,
			minlength: 6
		});
	});


</script>

</body>
</html>


