<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/ajaxfileupload.js') ?>"></script>
<div class="page-header">
	<h1 style="width:100%;">
  	<span class="text-light-gray"  style="width:100%;">
  		<a href="<?php echo site_url('gsk_news/import_card');?>"></a> /
	    <?=$title?> /
  	<span>
	</h1>
</div>

<div class="row">
	<div class="col-md-12" style="margin-bottom:16px;">
		<form action="<?php echo site_url('gsk_msg/msg_add');?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
			<div class="form-group card">
				<label for="f_card_img" class="col-sm-2 control-label">导入文件:</label>
				<div class="col-sm-7">
					<input type="file" class="form-control" id="upfile" name="upfile" placeholder="上传文件...">
				</div>
				<div class="col-sm-2">
					<input type="button" class="btn btn-primary btn-lg active" onclick="javascript:imgUpload('upfile');" style="margin-left:20px;" value="导入">
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-12" id="showBox" style="display:none;">
		<div class="col-sm-2">
		</div>
		<div class="col-sm-7">
		<div class="panel panel-info panel-dark widget-profile">
			<div class="panel-heading">
				<div class="widget-profile-bg-icon"><i class="fa fa-twitter"></i></div>
				<div class="widget-profile-header">
					<span>导入明细</span><br>
				</div>
			</div> <!-- / .panel-heading -->
			<div class="widget-profile-counters">
				<div class="col-xs-4"><span id="totalNum">131</span><br>TOTAL</div>
				<div class="col-xs-4"><span id="successNum">230</span><br>SUCCESS</div>
				<div class="col-xs-4"><span id="failedNum">56</span><br>FAILED</div>
			</div>
			<div class="widget-profile-text" id="infoBox">

			</div>
		</div>
		</div>
	</div>
</div>
</div>
<script>
	function imgUpload(id){
		$.ajaxFileUpload({
			url:'/gsk/index.php/gsk_news/import',
			secureuri:false,
			fileElementId:id,
			dataType:'json',
			data: {'filename':id},
			success:function(data){
				$("#totalNum").html(data.total);
				$("#successNum").html(data.success.length);
				$("#failedNum").html(data.failed.length);
				var success = data.success;
				var failed = data.failed;
				$("#infoBox").empty();
				for (var i= 0,item;item=failed[i++];) {
					var html = '卡号：<a href="#" class="badge badge-warn">'+item['number']+'</a> 卡密：<a href="#" class="badge badge-warn">'+item['password']+'</a> 状态：<a href="#" class="badge badge-warn">失败</a><br /><br />';
					$("#infoBox").append(html);
				}
				for (var i= 0,item;item=success[i++];) {
					var html = '卡号：<a href="#" class="badge badge-success">'+item['number']+'</a> 卡密：<a href="#" class="badge badge-success">'+item['password']+'</a> 状态：<a href="#" class="badge badge-success">成功</a><br /><br />';
					$("#infoBox").append(html);
				}
				$("#showBox").show();
			},
		})
	}
</script>