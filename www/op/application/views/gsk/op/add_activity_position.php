<link href="<?=base_url('assets/css/jquery.datetimepicker.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('assets/js/jquery.datetimepicker.js') ?>"></script>
<script src="<?=base_url('assets/js/ajaxfileupload.js') ?>"></script>
<style type="text/css">
	.bigImage {width: 600px; width: 600px; margin: 0px auto;}
	.smallImage {width: 200px; width: 200px}
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

		$('#btn_add').click(function () {
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
				url:"<?=site_url('gsk_news/do_add_activity_pt');?>",
				data:{
					'position_id':<?=$_REQUEST['id']?>,
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
					if(data == false)
					{
						('生效日期存在重叠，请检查历史数据，并修改！');
						window.close();
					}
					else
					{
						('添加成功');
						window.close();
					}
				} ,
				error: function(){
					('添加失败');
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
				</div>
			</div>
			<div class="form-group <?php if($_REQUEST['id'] == 3){echo 'hidden';} ?>" >
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
					<input type="radio" name="apply" id="yse" value="1" class="left orange" style="height:25px; width:25px" >
					<span style="font-size: 14px" class="orange">是</span>
					<input type="radio" name="apply" id="no"  value="0" checked class="right orange" style="height:25px; width:25px;margin-left: 50px" >
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
			<div class="form-group text-center">
				<button type="reset" class="btn-warning" id="btn_add" style="width: 100px">添 加</button>
				<button type="submit" class="btn-primary" id="btn_cancel" style="margin-left:20px; width: 100px">取 消</button>
			</div>
		</form>
	</div>
</div>
<script>
	function imgUpload(id, order){
		$.ajaxFileUpload({
//			url:'http://192.168.92.70/api/api/aliyun/upload_pic.php',
			url:'http://api.niutou.com/file/uploads',
			secureuri:false,
			fileElementId:id,

			dataType:'json',
			data: {'filename':id},
			success:function(data){
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