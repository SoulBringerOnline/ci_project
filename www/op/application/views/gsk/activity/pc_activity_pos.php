<script src="<?=base_url('assets/js/layer/layer.js') ?>"></script>
<div class="page-header">
	<h1 style="width:100%;">
  	<span class="text-light-gray"  style="width:100%;">
  		<a href="<?php echo site_url('gsk_msg/msg_list');?>">消息列表</a> /
	    <?=$title?> /
  	<span>
	</h1>
</div>

<div class="row">
	<div class="col-md-12" style="margin-bottom:16px;">
		<form>
			<div class="form-group">
				<label for="msgflag" class="col-sm-2 control-label">活动名:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="f_name" value="<?=$data['f_name']?>">
				</div>
				<!--<div class="col-sm-5">
				  <strong>主要用于控制只发一条，这个标记会在所有接受过该消息的用户同步，（重复发送 0，否则为 偶数）</strong>
				</div>-->
			</div>
			<div class="form-group">
				<label for="msgflag" class="col-sm-2 control-label">活动地址:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="f_url" value="<?=$data['f_url']?>">
				</div>
				<!--<div class="col-sm-5">
				  <strong>主要用于控制只发一条，这个标记会在所有接受过该消息的用户同步，（重复发送 0，否则为 偶数）</strong>
				</div>-->
			</div>
			<div class="form-group text-center">
				<input type="button" id="save" class="btn btn-primary btn-lg active" style="margin-left:20px;" value="保   存">
			</div>
		</form>
	</div>
</div>
</div>
<script>
	$("#save").click(function(){
		$.ajax({
			url: "/gsk/index.php/gsk_news/pc_activity_pos",
			type: "POST",
			data: $("form").serialize(),
			dataType: "json",
			success: function (data) {
				if(data.status){
					layer.msg('保存成功');
				}
			}
		});
		return false;
	});
</script>
