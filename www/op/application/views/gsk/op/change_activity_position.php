<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/ajaxfileupload.js') ?>"></script>
<style type="text/css">
	.table th{text-align: center}
	.bigImage {width: 600px; margin: 0px auto;}
	.smallImage {width: 200px;}
</style>

<div class="page-header">
	<h1 style="width:100%;">
  	<span class="text-light-gray"  style="width:100%;">
	    <?=$title?>
	    <span>
	</h1>
</div>

<script type="text/javascript">
	init.push(function () {
		$('body').toggleClass('mmc');
		function GetRadioValue(RadioName)
		{
			var obj;
			obj=document.getElementsByName(RadioName);
			if(obj!=null){
				var i;
				for(i=0;i<obj.length;i++){
					if(obj[i].checked){
						return obj[i].value;
					}
				}
			}
			return null;
		}
		function date(time)
		{
			time = new Date(Date.parse(time.replace(/-/g, "/")));
			time = time.getTime();
			return time;
		}

		$('#btn_save').click(function () {
			var btn = $(this);
			var check = GetRadioValue('apply');
//			if($('#pic_link_3').val().length <= 0)
//			{
//				('请上传图片')
//				return;
//			}
			if($('#begintime').val().length <= 0)
			{
				('请录入生效日期')
				return;
			}
			if($('#finishtime').val().length <= 0)
			{
				('请录入失效日期')
				return;
			}
			if($('#continue_time').val().length <= 0)
			{
				('请录入持续时长')
				return;
			}


			if(date($('#begintime').val()) >= date($('#finishtime').val()))
			{
				('失效日期不能小于生效日期');
				return;
			}

			$.ajax({
				type:"POST",
				url:"<?=site_url('gsk_news/do_change_activity_pt');?>",
				data:{
					'position_id':<?=$_REQUEST['id']?>,
					'pic_order': $('#pic_order').val(),
					'pic_size': $('#pic_size').val(),
					'bigpic_link': $('#pic_link_3').val() ,
					'smallpic_link_one': $('#pic_link_1').val() ,
					'smallpic_link_two': $('#pic_link_2').val() ,
					'need_dynamic': check,
					'jump_link': $('#jump_link').val() ,
					'begintime': $('#begintime').val(),
					'finishtime': $('#finishtime').val(),
					'continue_time': $('#continue_time').val(),
					'text_big': $('#text_big').val(),
					'text_middle': $('#text_middle').val(),
					'text_small': $('#text_small').val(),
					'back_color': $('#back_color').val()
				},
				datatype: "json",
				beforeSend:function(){
					btn.button('loading');
				},
				success:function(data){
					if(data == 101)
					{
						('生效日期存在重叠，请检查历史数据，并修改！');
					}
					else if(data == 102)
					{
						('失效日期存在重叠，请检查历史数据，并修改！');
					}
					else
					{
						('修改成功');
						window.close();
					}
				} ,
				error: function(){
					('修改失败');
					window.close();
				}
			});
		});

		$('#btn_cancel').click(function () {
			window.close();
		})
	});
</script>
<!-- / Javascript -->

<div class="row">
	<div class="col-md-12" style="margin-bottom:16px;">
		<form>
			<div class="form-group">
				<label for="baseid" class="col-sm-2 control-label">图片尺寸：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="pic_size" id="pic_size">
					<input type="text" class="form-control hidden" id="pic_id" name="pic_id">
					<input type="text" class="form-control hidden" id="pic_order" name="pic_order">
				</div>
			</div>
			<div class="form-group <?php if($_REQUEST['id'] == 3){echo 'hidden';} ?>">
				<label for="f_card_img" class="col-sm-2 control-label">图片（大）链接：</label>
				<div class="col-sm-8">
					<input type="file" class="form-control" id="upfile" name="upfile" onchange="javascript:imgUpload('upfile', '3');">
					<input class="form-control hidden" id="pic_link_3" name="pic_link_3">
					<div id="show_card_img_3" class="bigImage hidden"></div>
				</div>
			</div>
			<div class="form-group <?php if($_REQUEST['id'] != 3){echo 'hidden';} ?>">
				<div style="margin-left: 335px">
					<label class="col-sm-1 control-label">图片（小）链接①：</label>
					<div class="col-sm-4">
					<span class="pull-left">
						<div id="show_card_img_1" class="smallImage hidden"></div>
					</span>
						<div class="col-sm-8">
							<input type="file" class="form-control" id="upfile1" name="upfile1" onchange="javascript:imgUpload('upfile1', '1');">
							<input class="form-control hidden" id="pic_link_1" name="pic_link_1">
						</div>
					</div>

					<label class="col-sm-1 control-label">图片（小）链接②：</label>
					<div class="col-sm-4">
					<span class="pull-left">
						<div id="show_card_img_2" class="smallImage hidden"></div>
					</span>
						<div class="col-sm-8">
							<input type="file" class="form-control" id="upfile2" name="upfile2" onchange="javascript:imgUpload('upfile2', '2');">
							<input class="form-control hidden" id="pic_link_2" name="pic_link_2">
						</div>
					</div>
				</div>
			</div>

			<div class="form-group <?php if($_REQUEST['id'] != 3){echo 'hidden';} ?>">
				<label class="col-sm-1 control-label">是否需要动态：</label>
				<div class="col-sm-4 text-center" >
					<input type="radio" name="apply" id="yse" value="1" class="left orange" style="height:25px; width:25px" <?php if($data['f_pic_need_dynamic'] == 1){echo 'checked';} ?>>
					<span style="font-size: 14px" class="orange">是</span>
					<input type="radio" name="apply" id="no"  value="0" checked class="right orange" style="height:25px; width:25px;margin-left: 50px" <?php if($data['f_pic_need_dynamic'] == 0){echo 'checked';} ?>>
					<span style="font-size: 14px" class="orange">否</span>
				</div>
			</div>

			<div class="form-group">
				<label  class="col-sm-2 control-label">跳转链接：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="jump_link">
				</div>
			</div>
			<div class="form-group">
				<label for="msginfo" class="col-sm-2 control-label">生效日期：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="begintime" name="begintime">
				</div>
			</div>
			<div class="form-group">
				<label for="msginfo" class="col-sm-2 control-label">失效日期：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="finishtime" name="finishtime">
				</div>
			</div>
			<div class="form-group card">
				<label for="f_card_title" class="col-sm-2 control-label">持续时长：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" value="3" id="continue_time" name="continue_time">
				</div>
			</div>
			<div class="form-group card">
				<label for="f_card_title" class="col-sm-2 control-label">文字描述（大）：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="text_big" name="text_big">
				</div>
			</div>
			<div class="form-group card">
				<label for="f_card_title" class="col-sm-2 control-label">文字描述（中）：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="text_middle" name="text_middle">
				</div>
			</div>
			<div class="form-group card">
				<label for="f_card_title" class="col-sm-2 control-label">文字描述（小）：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="text_small" name="text_small">
				</div>
			</div>
			<div class="form-group card">
				<label for="f_card_title" class="col-sm-2 control-label">背景颜色：</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="back_color" name="back_color" placeholder="请录入格式为 #FFFAFA 的颜色数值">
				</div>
			</div>
		</form>
	</div>
</div>

<div class="table-responsive">
	<table class="table" style="text-align: center">
		<thead>
		<tr>
			<th>图片序号</th>
			<th>图片链接</th>
			<th>图片尺寸</th>
			<th>跳转链接</th>
			<th>生效日期</th>
			<th>失效日期</th>
			<th>持续时长</th>
			<th>操作类型</th>
			<th>操作人</th>
			<th>操作时间</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($data as $item): ?>
			<tr>
				<td class="hidden" id="pic_small_one_<?=$item['_id']?>"><?=$item['f_small_pic_url_one']?></td>
				<td class="hidden" id="pic_small_two_<?=$item['_id']?>"><?=$item['f_small_pic_url_two']?></td>
				<td class="hidden" id="text_big_<?=$item['_id']?>"><?=$item['f_text_big']?></td>
				<td class="hidden" id="text_middle_<?=$item['_id']?>"><?=$item['f_text_middle']?></td>
				<td class="hidden" id="text_small_<?=$item['_id']?>"><?=$item['f_text_small']?></td>
				<td class="hidden" id="back_color_<?=$item['_id']?>"><?=$item['f_back_color']?></td>
				<td id="pic_order_<?=$item['_id']?>"><?=$item['f_pic_order']?></td>
				<td id="pic_big_url_<?=$item['_id']?>"><?=$item['f_big_pic_url']?></td>
				<td id="pic_size_<?=$item['_id']?>"><?=$item['f_pic_size']?></td>
				<td id="jump_url_<?=$item['_id']?>"><?=$item['f_jump_url']?></td>
				<td id="begin_time_<?=$item['_id']?>"><?=human_time($item['f_begin_time'])?></td>
				<td id="finish_time_<?=$item['_id']?>"><?=human_time($item['f_finish_time'])?></td>
				<td id="continue_time_<?=$item['_id']?>"><?=$item['f_continue_time']?></td>
				<td><?=acticity_operater_type($item['f_operater_type'])?></td>
				<td id="operater_<?=$item['_id']?>"><?=$item['f_operater']?></td>
				<td id="operater_time_<?=$item['_id']?>"><?=human_time($item['f_operater_time'])?></td>
				<td>
					<a href="#" class="integrationBtn" id="tag<?=$item['_id']?>">修改</a>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<div class="form-group text-center">
	<button type="reset" class="btn-warning" id="btn_save" style="width: 100px">保 存</button>
	<button type="submit" class="btn-primary" id="btn_cancel" style="margin-left:20px; width: 100px" >取 消</button>
</div>



<script>
	//修改显示
	$(".integrationBtn").bind("click",function(){
		$("#pic_link_3").removeClass("hidden");
		$("#show_card_img_3").removeClass("hidden");

		var id = $(this).attr("id").substring(3);
		var pic_small_one = document.getElementById("pic_small_one_"+id).innerHTML;
		var pic_small_two = document.getElementById("pic_small_two_"+id).innerHTML;
		var text_big = document.getElementById("text_big_"+id).innerHTML;
		var text_middle = document.getElementById("text_middle_"+id).innerHTML;
		var text_small = document.getElementById("text_small_"+id).innerHTML;
		var back_color = document.getElementById("back_color_"+id).innerHTML;
		var pic_big_url = document.getElementById("pic_big_url_"+id).innerHTML;
		var pic_order = document.getElementById("pic_order_"+id).innerHTML;
		var pic_size = document.getElementById("pic_size_"+id).innerHTML;
		var jump_url = document.getElementById("jump_url_"+id).innerHTML;
		var begin_time = document.getElementById("begin_time_"+id).innerHTML;
		var finish_time = document.getElementById("finish_time_"+id).innerHTML;
		var continue_time = document.getElementById("continue_time_"+id).innerHTML;
		if(pic_small_one != "")
		{
			$("#pic_link_1").removeClass("hidden");
			$("#show_card_img_1").removeClass("hidden");
		}

		if(pic_small_two != "")
		{
			$("#pic_link_2").removeClass("hidden");
			$("#show_card_img_2").removeClass("hidden");
		}
		$("#pic_id").val(id);
		$("#pic_order").val(pic_order);
		$("#pic_size").val(pic_size);
		$("#pic_link_3").val(pic_big_url);
		$("#pic_link_1").val(pic_small_one);
		$("#pic_link_2").val(pic_small_two);
		$("#jump_link").val(jump_url);
		$("#begintime").val(begin_time);
		$("#finishtime").val(finish_time);
		$("#continue_time").val(continue_time);
		$("#text_big").val(text_big);
		$("#text_middle").val(text_middle);
		$("#text_small").val(text_small);
		$("#back_color").val(back_color);
		$("#show_card_img_3").html("<img src="+pic_big_url+' class="img-thumbnail">');
		$("#show_card_img_1").html("<img src="+pic_small_one+' class="img-thumbnail">');
		$("#show_card_img_2").html("<img src="+pic_small_two+' class="img-thumbnail">');
	});

	function imgUpload(id, order){
		$.ajaxFileUpload({
//			url:'http://192.168.92.70/api/api/aliyun/upload_pic.php',
			url:'http://192.168.164.199/api/aliyun/upload_pic.php',
			secureuri:false,
			fileElementId:id,
			dataType:'json',
			data: {'filename':id},
			success:function(data){
				console.info(data);
				if(data.ret == 0){
					$("#pic_link_"+order).removeClass("hidden");
					$("#pic_link_"+order).val(data.pic_url);
					$("#show_card_img_"+order).removeClass("hidden");
					$("#show_card_img_"+order).html("<img src="+data.pic_url+' class="img-thumbnail" >');
				}else{
					(data.msg);
				}
			},
		})
	}
	$(function(){
		$('#begintime').datetimepicker({
			startView: 2,
			minView: 2,
			lang:'ch',
//			timepicker:false,
			format:'Y-m-d H:i:s'
		});
		$('#finishtime').datetimepicker({
			startView: 2,
			minView: 2,
			lang:'ch',
//			timepicker:false,
			format:'Y-m-d H:i:s'
		});
	})
</script>
