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

<body>

<div class="page-header">
	<h1><span class="text-light-gray">菜单添加 <span>  </h1>
</div>


<div class="panel colourable">
	<div class="panel-heading">
		<span class="panel-title"></span>
	</div>

	<!-- List group -->
	<ul class="list-group">
		<li class="list-group-item"><label>title:</label><input type="text" id="menu_title" /></li>
		<li class="list-group-item"><label>父节点:</label><input type="text" id="menu_parent_id" /></li>
		<li class="list-group-item"><label>节点类型:</label><input type="text" id="menu_type" /></li>
		<li class="list-group-item"><label>点击链接:</label><input type="text" id="menu_url" /></li>
		<li class="list-group-item"><label>权限:</label><input type="text" id="menu_priv_level" /></li>
		<li class="list-group-item"><label>排序:</label><input type="text" id="menu_morder" /></li>
		<li class="list-group-item">
			<input type="button" id="menu_submit" value="添加菜单"/>
		</li>
	</ul>
</div>
<script type="text/javascript">
	var btn = $("#menu_submit")[0];

	btn.onclick = function() {

		var title = $("#menu_title")[0].value;
		var parent_id = $("#menu_parent_id")[0].value;
		var type = $("#menu_type")[0].value;
		var url = $("#menu_url")[0].value;
		var priv_level = $("#menu_priv_level")[0].value;
		var morder = $("#menu_morder")[0].value;
		$.ajax({
			type:"POST",
			url:"<?=site_url('gsk_menu/do_add_menu');?>",
			data:'title=' + title +'&parent_id='+ parent_id+'&type=' + type+ '&url'+url+'&priv_level='+priv_level+ '&morder='+morder,
			datatype: "json",
			success:function(data){
				console.log(data);
			} ,
			error: function(){
				console.log("error");
			}
		});
	}

</script>
</body>