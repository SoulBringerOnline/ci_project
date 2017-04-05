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

	<?php $this->load->view('include/header'); ?>
	<?php $this->load->view('include/sidebar'); ?>
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
		<?php $this->load->view( $content ); ?>
	</div> <!-- / #content-wrapper -->

	<div id="main-menu-bg"></div>

</div> <!-- / #main-wrapper -->
<?php $this->load->view('include/footer'); ?>
