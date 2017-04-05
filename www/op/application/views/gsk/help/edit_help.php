
<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<link rel="stylesheet" type="text/css" href="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.css')?>"></link>
<script src="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all.js')?>"></script>

<?$type = $_REQUEST['type']?>
<script type="text/javascript">
	function transferStr(str) {
		var createEle=document.createElement("div");
		$(createEle).html(str);
		if($(createEle).find("img"))
		{
			$(createEle).find("img").parent().addClass("imgCon");
			$(createEle).find(".imgCon").each(function(){
				if($(this).find("img").length==1){
					$(this).find("img").addClass("centerImg")
				}else if($(this).find("img").length==2){
					$($(this).find("img")[0]).addClass("leftImg");
					$($(this).find("img")[1]).addClass("rightImg")
				}
			});
		}
		return "<div class='qustionCon'>"+$(createEle).html()+"</div>";
	}
	init.push(function () {
		$('body').toggleClass('mmc');

		$('#btn_help_edit').click(function () {
			var btn = $(this);

			if($('#help_title').val().length <= 0)
			{
				('请录入资讯标题')
				return;
			}
			if($('#help_content').val().length <= 0)
			{
				('请录入资讯内容')
				return;
			}

			$.ajax({
				type:"POST",
				url:"<?=site_url('gsk_news/do_edit_help');?>",
				data:{
					'type':'<?=$type?>',
					'help_id': $('#help_id').val() ,
					'help_title': $('#help_title').val() ,
					'help_content': transferStr($('#help_content').val()),
					'help_sort': $('#help_sort').val(),
					'comment_question': document.getElementById('comment_question').checked ? 1 : 0,
					'help_classify': $('#help_classify').val()
				},
				datatype: "json",
				beforeSend:function(){
					btn.button('loading');
				},
				success:function(data){
					btn.button('reset');
					$('#panel_help_edit').fadeOut('fast',function(){
						('成功')
						window.close();
					});

				} ,
				error: function(){
					btn.button('reset');
					btn.html('失败')
				}
			});
		});

	});

</script>
<!-- / Javascript -->

<div class="row" id="panel_help_edit">
	<div class="col-sm-12">

		<div class="form-group">
			<label class="col-sm-1 control-label">标题</label>
			<div class="col-sm-11">
				<input type="text" class="form-control" id="help_title" value="<?=empty($help['f_help_title'])?"":$help['f_help_title']?>">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-1"></div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="help-addon2">ID</span>
					<input type="text" class="form-control" id="help_id" value="<?=empty($help['f_help_id'])?"":$help['f_help_id']?>" aria-describedby="help-addon1" readonly placeholder="自动生成">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" >问题分类</span>
					<select class="form-control form-group-margin" id="help_classify" style="margin-bottom: 0px!important;">
						<option value="1" <?php if($help['f_help_classify'] == 1){echo 'selected="selected"';} ?>>知识</option>
						<option value="2" <?php if($help['f_help_classify'] == 2){echo 'selected="selected"';} ?>>项目</option>
						<option value="3" <?php if($help['f_help_classify'] == 3){echo 'selected="selected"';} ?>>看点</option>
						<option value="4" <?php if($help['f_help_classify'] == 4){echo 'selected="selected"';} ?>>账号与登录</option>
						<option value="5" <?php if($help['f_help_classify'] == 5){echo 'selected="selected"';} ?>>对话</option>
						<option value="6" <?php if($help['f_help_classify'] == 6){echo 'selected="selected"';} ?>>我的</option>
						<option value="7" <?php if($help['f_help_classify'] == 7){echo 'selected="selected"';} ?>>其他</option>
					</select>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="help-addon2">排序</span>
					<input type="text" class="form-control" id="help_sort" value="<?= empty($help['f_help_sort'])?"0":$help['f_help_sort']?>" aria-describedby="help-addon2">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="input-group" >
					<span class="input-group-addon" id="help-addon3" style="line-height:20px">常见问题</span>
					<input type="checkbox" class="form-control" id="comment_question" style="height:20px; width:20px;margin-top:7px" aria-describedby="help-addon3"
						<?php if($help['f_comment_question'] == 1){echo 'checked';} ?>>
				</div>
			</div>
	</div>
	<div class="form-group">
		<hr>
	</div>

	<div class="form-group">
		<label class="col-sm-1 control-label">正文</label>
		<div class="col-sm-11">
			<textarea class="textarea" id="help_content" style="width: 100%; height: 600px; "><?=$help['f_help_content']?></textarea>
			<script type="text/javascript">
				$('#help_content').wysihtml5();
			</script>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-1 control-label"></label>
		<div class="col-sm-11">
			<?php
				$btn_title = '添加帮助';
				if( $type == 'edit' )
				{
					$btn_title = '确认编辑';
				}
			?>
			<button type="submit" id="btn_help_edit" class="btn btn-danger"><?=$btn_title;?></button>
		</div>
	</div>

</div>
</div>







