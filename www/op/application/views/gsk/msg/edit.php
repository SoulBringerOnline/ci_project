<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/ajaxfileupload.js') ?>"></script>
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
<form action="<?php echo site_url('gsk_msg/msg_edit');?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
	 <div class="form-group">
		<label for="msgflag" class="col-sm-2 control-label">Msg FLag:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="msgflag" value="<?=$data['f_msgflag']?>" readonly>
		</div>
		<!--<div class="col-sm-5">
		  <strong>主要用于控制只发一条，这个标记会在所有接受过该消息的用户同步，（重复发送 0，否则为 偶数）</strong>
		</div>-->
	</div>
	<div class="form-group">
		<label for="baseid" class="col-sm-2 control-label">Baseid:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" name="baseid" value="<?=$data['f_baseid']?>" readonly>
		  <input type="hidden" name="_id" value="<?=$data['_id']?>">
		</div>
	</div>
	<div class="form-group">
		<label for="begintime" class="col-sm-2 control-label">Begin Time:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="begintime" name="begintime" value="<?=date("Y-m-d H:i:s", $data['f_begintime'])?>" placeholder="msg的有效开始时间">
		</div>
	</div>
	<div class="form-group">
		<label for="finishtime" class="col-sm-2 control-label">Finish Time:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="finishtime" name="finishtime" value="<?=date("Y-m-d H:i:s", $data['f_finishtime'])?>" placeholder="msg的有效结束时间">
		</div>
	</div>
	<div class="form-group">
		<label for="type" class="col-sm-2 control-label">Msg Type:</label>
		<div class="col-sm-8">
		<select name="type" id="type" class="form-control">
			<option value="1" <?php if($data['f_type'] == 1){ echo 'selected'; }?>>文本类型</option>
			<option value="12" <?php if($data['f_type'] == 12){ echo 'selected'; }?>>Card类型</option>
		</select>
		</div>
	</div>
	<div class="form-group">
		<label for="msginfo" class="col-sm-2 control-label">Msg Content:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="msginfo" value="<?=$data['f_msginfo']?>" name="msginfo" placeholder="msg的内容">
		</div>
	</div>
	<div class="form-group">
		<label for="msginfo" class="col-sm-2 control-label">Msg Join Channels:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="joinChannels" value="<?=$data['f_join_channels']?>" name="joinChannels" placeholder="允许渠道 null表示全渠道都可以">
		</div>
	</div>
	<div class="form-group card">
		<label for="f_card_title" class="col-sm-2 control-label">Card Title:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="f_card_title" value="<?=$data['f_msgcard']['f_card_title']?>" name="f_card_title" placeholder="卡片标题">
		</div>
	</div>
	<div class="form-group card">
		<label for="f_card_img" class="col-sm-2 control-label">Card Image:</label>
		<div class="col-sm-8">
		  <input type="file" class="form-control" id="upfile" name="upfile" onchange="javascript:imgUpload('upfile');" placeholder="卡片图片">
		  <input type="hidden" class="form-control" id="f_card_img" value="<?=$data['f_msgcard']['f_card_img']?>" name="f_card_img">
		  <div id="show_card_img">
		  <?php if ($data['f_msgcard']['f_card_img']){?> <img src="<?=$data['f_msgcard']['f_card_img']?>" /> <?php };?>
		  </div>
		</div>
	</div>
	<div class="form-group card">
		<label for="f_card_desc" class="col-sm-2 control-label">Card Des:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="f_card_desc" value="<?=$data['f_msgcard']['f_card_desc']?>" name="f_card_desc" placeholder="卡片描述">
		</div>
	</div>
	<div class="form-group card">
		<label for="f_card_atction" class="col-sm-2 control-label">Card Action:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="f_card_atction" value="<?=$data['f_msgcard']['f_card_atction']?>" name="f_card_atction" placeholder="卡片动作">
		</div>
	</div>
	<div class="form-group card">
		<label for="f_card_frominfo" class="col-sm-2 control-label">Card Content:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="f_card_frominfo" value="<?=$data['f_msgcard']['f_card_frominfo']?>" name="f_card_frominfo" placeholder="卡片来自于信息">
		</div>
	</div>
	<div class="form-group card">
		<label for="f_card_finish" class="col-sm-2 control-label">Card Finish:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="f_card_finish" value="<?=$data['f_msgcard']['f_card_finish']?>" name="f_card_finish" placeholder="卡片完成内容">
		</div>
	</div>
	<div class="form-group card">
		<label for="f_card_finish_action" class="col-sm-2 control-label">Card Finish Action:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="f_card_finish_action" value="<?=$data['f_msgcard']['f_card_finish_action']?>" name="f_card_finish_action" placeholder="卡片完成动作">
		</div>
	</div>
	<div class="form-group card">
		<label for="f_card_extrc" class="col-sm-2 control-label">Card Extrc:</label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="f_card_extrc" value="<?=$data['f_msgcard']['f_card_extrc']?>" name="f_card_extrc" placeholder="卡片扩展">
		</div>
	</div>
	<div class="form-group text-center">
		<input type="reset" class="btn btn-warning btn-lg active" value="重  置">
		<input type="submit" class="btn btn-primary btn-lg active" style="margin-left:20px;" value="提  交">
	</div>
</form>
</div>
</div>
</div>
<script>
function imgUpload(id){
	$.ajaxFileUpload({
		url:'http://192.168.164.199/api/aliyun/upload_pic.php',
		secureuri:false,
		fileElementId:id,
		dataType:'json',
		data: {'filename':id},
		success:function(data){
			if(data.ret == 0){
				$("#f_card_img").val(data.pic_url);
				$("#show_card_img").html("<img src="+data.pic_url+">");
			}else{
				(data.msg);
			}
		},
	})
}
$(function(){
	$('#begintime').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d H:i:s',
	});
	$('#finishtime').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d H:i:s',
	});
	function showCard() {
		if ($("#type").val() == '12') {
			$(".card").show();
		} else {
			$(".card").hide();
		}
	}
	
	showCard();
	$("#type").change(showCard);
})
</script>