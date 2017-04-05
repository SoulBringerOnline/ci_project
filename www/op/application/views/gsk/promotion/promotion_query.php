<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
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

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?=base_url('favicon.ico') ?>" >

</head>
<body class="theme-asphalt">
<script>var init = [];</script>
<div id="main-wrapper">

	<!-- header -->
	<div id="main-navbar" class="navbar navbar-inverse" role="navigation">
		<!-- Main menu toggle -->
		<button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i><span class="hide-menu-text">HIDE MENU</span></button>

		<div class="navbar-inner">
			<!-- Main navbar header -->
			<div class="navbar-header">

				<!-- Logo -->
				<a href="" class="navbar-brand">
					<div>  <img alt="筑友" src="<?=base_url('assets/img/logo.png') ?>"></div>
					筑友
				</a>

				<!-- Main navbar toggle -->
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse"><i class="navbar-icon fa fa-bars"></i></button>

			</div> <!-- / .navbar-header -->

			<div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
				<div>
					<div class="right clearfix">
						<ul class="nav navbar-nav pull-right right-navbar-nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
									<img src="<?=base_url('assets/img/user2.jpg') ?>" alt="">
									<span>推广员</span>
								</a>
								<ul class="dropdown-menu">
									<li class="divider"></li>
									<li><a href="<?=site_url('Gsk_pro_register/logout');?>"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;注销</a></li>
								</ul>
							</li>
						</ul> <!-- / .navbar-nav -->
					</div> <!-- / .right -->
				</div>
			</div> <!-- / #main-navbar-collapse -->
		</div> <!-- / .navbar-inner -->
	</div>
	<!-- / header -->
	<div id="main-menu" role="navigation">
		<div id="main-menu-inner">
			<ul class="navigation" style="color: #ffffff; font-size: 13px">

				<li>姓名：
					<span ><?= $data['f_name']?></span>
				</li>
				<li></li>
				<li>身份证：
					<span ><?= $data['f_IDcard']?></span>
				</li>
				<li>手机：
					<span ><?= $data['f_phone']?></span>
				</li>
				<li>邀请码：
					<span ><?= $data['f_invite_code']?></span>
				</li>
			</ul>
		</div>
		</div>
	<!--	--><?php //$this->load->view('include/sidebar'); ?>
	<div id="content-wrapper">
		<script>
			init.push(function () {
				$('#toggle-mme').click(function () {
					$('body').toggleClass('mme');
				});
				$('#toggle-mmc').click(function () {
					$('body').toggleClass('mmc');
				});
			});
		</script>
		<div class="page-header">
			<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
		</div>
		<div>
			<h1><span class="text-light-gray">敬请期待。。。 </h1>
		</div>
	</div> <!-- / #content-wrapper -->

	<div id="main-menu-bg"></div>

</div> <!-- / #main-wrapper -->
<?php $this->load->view('include/footer'); ?>
