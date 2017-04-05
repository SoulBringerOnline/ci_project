<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/ajaxfileupload.js') ?>"></script>
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
<div class="bs-example">
	<strong>消息内容：</strong><?=$data['f_msginfo']?>
</div><br /><br />
<form class="form-inline">
  <div class="form-group">
    <label for="exampleInputName2">测试地址:</label>
  </div><br/>
  <div class="form-group">
    <label for="exampleInputEmail2">http://192.168.164.200:5000/send_msg/<?=$data['f_baseid']?>/</label>
    <input type="text" class="form-control" id="off_user_id" name="off_user_id" placeholder="用户ID">/<input type="text" style="width:500px;" class="form-control" id="off_params" name="off_params" placeholder="参数1/参数2/参数n...">
  </div>
  <input type="button" onclick="send(0)" class="btn btn-warning btn-primary active" value="发  送">
</form><br /><br />
<form class="form-inline">
  <div class="form-group">
    <label for="exampleInputName2">线上地址:</label>
  </div><br/>
  <div class="form-group">
    <label for="exampleInputEmail2">http://10.128.63.250:5000/send_msg/<?=$data['f_baseid']?>/</label>
    <input type="text" class="form-control" id="on_user_id" name="on_user_id" placeholder="用户ID">/<input type="text" style="width:500px;" class="form-control" id="on_params" name="on_params" placeholder="参数1/参数2/参数n...">
  </div>
  <input type="button" onclick="send(1)" class="btn btn-warning btn-danger active" value="发  送">
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
//var lock = 0;
function send(type) {
	/*if (lock) {
		return false;
	}*/
	if (type == 1) {
		var url = "http://10.128.63.250:5000/send_msg/<?=$data['f_baseid']?>/";
		var uid = $("#on_user_id").val();
		var params = $("#on_params").val();
	} else {
		var url = "http://192.168.164.200:5000/send_msg/<?=$data['f_baseid']?>/";
		var uid = $("#off_user_id").val();
		var params = $("#off_params").val();
	}
	if (!uid) {
		layer.msg('Uid不能为空');return false;
	}
	url = url + uid + "/" + params;
	//var loding = layer.load(0, {shade: false});
	//lock = 1;
	/*$.get(url, function(ret){
		layer.close(loding);
		lock = 0;
		if (ret.error==0) {
			("发送成功")
		} else {
			("发送失败");
		}
	});*/
	layer.open({
	    type: 2,
	    title: '发送消息',
	    shadeClose: true,
	    shade: 0.8,
	    area: ['380px', '18%'],
	    content: url //iframe的url
	}); 
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