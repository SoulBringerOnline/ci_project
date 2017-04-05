<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>下月档位</title>
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
<div id="content-wrapper" style="top:-20px;">
	<div class="row">
		<div class="col-sm-12">
			<form class="panel form-horizontal">
				<div class="panel-body">
					<div class="row form-group">
						<label class="col-sm-4 control-label">月份:</label>
						<div class="col-sm-8">
							<input type="text" value="<?=date("Y年m月", $date)?>" name="name" disabled class="form-control">
							<input type="hidden" value="<?=$date_id?>" name="dateid">
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-2 control-label">选择档位:</label>
						<div class="col-sm-6">
							<select name="grade" id="grade" class="form-control">
								<option <?php if($grade == 1){echo "selected";}?> value="1">标准档&nbsp;&nbsp;(0.1200 元/分钟, 无最低消费)</option>
								<option <?php if($grade == 2){echo "selected";}?> value="2">P1档&nbsp;&nbsp;(0.1140 元/分钟, 500元/月最低消费)</option>
								<option <?php if($grade == 3){echo "selected";}?> value="3">P2档&nbsp;&nbsp;(0.1056 元/分钟, 1000元/月最低消费)</option>
								<option <?php if($grade == 4){echo "selected";}?> value="4">P3档&nbsp;&nbsp;(0.1020 元/分钟, 2000元/月最低消费)</option>
								<option <?php if($grade == 5){echo "selected";}?> value="5">P4档&nbsp;&nbsp;(0.0960 元/分钟, 5000元/月最低消费)</option>
							</select>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-2 control-label">档位单价（元/分钟）:</label>
						<div class="col-sm-6">
							<input type="text" id="price" value="<?=$price?>" name="price" class="form-control" readonly>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-2 control-label">最低消费（元）:</label>
						<div class="col-sm-6">
							<input type="text" id="min_cost" value="<?=$min_cost?>" name="min_cost" class="form-control" readonly>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-2 control-label">副账户分钟数（分钟）:</label>
						<div class="col-sm-6">
							<input type="text" id="deputy_account_minus" value="<?=$deputy_account_minus?>"  name="deputy_account_minus" class="form-control">
						</div>
					</div>
				</div>
				<div class="panel-footer text-right">
					<button class="btn btn-primary" id="save">保&nbsp;&nbsp;存</button>
					<button class="btn btn-primary" id="cancel">取&nbsp;&nbsp;消</button>
				</div>
			</form>
		</div>
	</div>
	<script>
		$(function(){
			var index = parent.layer.getFrameIndex(window.name);
			$("#cancel").click(function(){
				parent.layer.close(index);
			});

			$("#grade").change(function(){
				$grade = $("#grade").val();
				switch (parseInt($grade)) {
					case 1:
					$("#price").val("0.1200");$("#min_cost").val("0");break;
					case 2:
						$("#price").val("0.1140");$("#min_cost").val("500");break;
					case 3:
						$("#price").val("0.1056");$("#min_cost").val("1000");break;
					case 4:
						$("#price").val("0.1020");$("#min_cost").val("2000");break;
					case 5:
						$("#price").val("0.0960");$("#min_cost").val("5000");break;
				}
			});

			$("#save").click(function(){
				$.ajax({
					url: "/gsk/index.php/gsk_news/call_date_combo_add",
					type: "GET",
					data: $("form").serialize(),
					dataType: "json",
					success: function (data) {
						if(data.state){
							parent.layer.close(index);
						}
					}
				});
				return false;
			});
		});
	</script>
</body>
</html>