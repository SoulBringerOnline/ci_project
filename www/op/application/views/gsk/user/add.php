<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/ajaxfileupload.js') ?>"></script>
<div class="page-header">
	<h1 style="width:100%;">
  	<span class="text-light-gray"  style="width:100%;">
  		<a href="<?php echo site_url('gsk_msg/msg_list');?>">用户列表</a> /
	    <?=$title?> /
  	<span>
	</h1>
</div>

<div class="row">
	<div class="col-md-12" style="margin-bottom:16px;">
		<form action="<?php echo site_url('gsk_user/add_user');?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
			<div class="form-group">
				<label for="msgflag" class="col-sm-2 control-label">用户glodon账号:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="account" name="account" placeholder="用户的glodon账号">
				</div>

			</div>
			<div class="form-group">
				<label for="baseid" class="col-sm-2 control-label">权限标记位:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="priv" name="priv" placeholder="权限level，说明">
				</div>
			</div>
			<div class="form-group">
				<label for="begintime" class="col-sm-2 control-label">角色群组</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="group" name="group" placeholder="用户的角色群组 1: 管理员  2：运营   3： 产品  4：开发">
				</div>
			</div>
			<div class="form-group">
				<label for="finishtime" class="col-sm-2 control-label">用户状态</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="status" name="status" placeholder="用户状态，0正常 -1 封禁">
				</div>
			</div>
			<div class="form-group text-center">
				<input type="reset" class="btn btn-warning btn-lg active" value="重  置">
				<input type="button" id="btn" class="btn btn-primary btn-lg active" style="margin-left:20px;" value="提  交">
			</div>
		</form>
	</div>
</div>
</div>
<script>
	btn.onclick = function() {
		var account = $("#account")[0].value;
		var priv = $("#priv")[0].value;
		var group = $("#group")[0].value;
		var status = $("#status")[0].value;
		var data = {
			"account":account,
			"priv":priv,
			"group":group,
			"status":status
		}
		$.ajax({
			type:"POST",
			url:"<?=site_url('gsk_user_op/do_add_user');?>",
			data:data,
			datatype: "json",
			success:function(data){
				("添加成功！");
				window.location.href="<?=site_url('gsk_user_op/user_list');?>";
			} ,
			error: function(){
				("添加失败！")
			}
		});
	}
</script>