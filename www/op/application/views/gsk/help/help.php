<div class="page-header">

	<form method="get" action="<?php echo site_url('gsk_news/help');?>"  name="form_id_news" >
		<div class="row">
			<div class="col-md-12">

				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon" id="help-addon1">标题</span>
						<input type="text" class="form-control"  name="f_help_title" aria-describedby="help-addon1"
						       value="<?=isset($data['filter']['f_help_title'])?$data['filter']['f_help_title']:''?>">
					</div>
				</div>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon" >问题分类</span>
						<select class="form-control form-group-margin" name="f_help_classify" style="margin-bottom: 0px!important;">
							<option value="1" <?php if($data['filter']['f_help_classify'] == 1){echo 'selected="selected"';} ?>>知识</option>
							<option value="2" <?php if($data['filter']['f_help_classify'] == 2){echo 'selected="selected"';} ?>>项目</option>
							<option value="3" <?php if($data['filter']['f_help_classify'] == 3){echo 'selected="selected"';} ?>>看点</option>
							<option value="4" <?php if($data['filter']['f_help_classify'] == 4){echo 'selected="selected"';} ?>>账号与登录</option>
							<option value="5" <?php if($data['filter']['f_help_classify'] == 5){echo 'selected="selected"';} ?>>对话</option>
							<option value="6" <?php if($data['filter']['f_help_classify'] == 6){echo 'selected="selected"';} ?>>我的</option>
							<option value="7" <?php if($data['filter']['f_help_classify'] == 7){echo 'selected="selected"';} ?>>其他</option>
							<option value="8" <?php if(!isset($data['filter']['f_help_classify'])){echo 'selected="selected"';} ?>>全部</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="input-group">
						<span class="input-group-addon" id="help-addon3">常见问题</span>
						<input type="text" class="form-control" name="f_comment_question" aria-describedby="help-addon3"
						       value="<?=isset($data['filter']['f_comment_question'])?$data['filter']['f_comment_question']:''?>">
					</div>
				</div>

				<div class="col-md-2">
					<button type="submit"  class="btn btn-primary">查询</button>
				</div>
				<div class="col-md-2">

				</div>
				<div class="col-md-2"></div>
				<div class="col-md-2"></div>
				<div class="col-md-2">
					<a target="_blank"  href="<?=site_url('gsk_news/edit_help?type=add')?>" class="btn btn-danger btn-labeled" style="width: 100%;">
						<span class="btn-label icon fa fa-plus"></span>添加帮助
					</a>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	init.push(function () {
		$('.btn_news_del').click(function () {
			var btn = $(this);
			var panel = $('#panel_news_' + btn.val())
			$.ajax({
				type:"POST",
				url:"<?=site_url('gsk_news/do_del_help');?>",
				data:{'help_id':btn.val()},
				datatype: "json",
				beforeSend:function(){
					btn.button('loading');
				},
				success:function(data){
					panel.fadeOut('fast',function(){
						panel.remove();
					});
				} ,
				error: function(){
					btn.button('reset');
					btn.html('删除失败')
				}
			});
		});
	});
</script>
<!-- / Javascript -->

<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], $data['filter']);?>
	</div>
</div>

<div class="panel-group" id="accordion_news">
	<?php foreach ($data['data'] as $item): ?>
		<div class="panel" id="panel_news_<?=$item['f_help_id']?>">
			<div class="panel-heading">
				<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_news" href="#collapse_news_<?=$item['f_help_id']?>">
					<?=$item['f_help_title']?>
				</a>
				<p class="text-muted" style="padding:0px 35px 0px 20px;">
					类型: <?=help_exchange_type($item['f_help_classify']);?>&nbsp;&nbsp;&nbsp;&nbsp;
					排序ID: <?=$item['f_help_sort'];?>&nbsp;&nbsp;&nbsp;&nbsp;
					常见问题: <?=$item['f_comment_question'];?>&nbsp;&nbsp;&nbsp;&nbsp;
					创建时间: <?=human_time($item['f_help_submit_time']);?>
				</p>
			</div>
			<div id="collapse_news_<?=$item['f_help_id']?>" class="panel-collapse collapse">
				<div class="panel-body">
					<?=$item['f_help_content'];?>
				</div>
				<div class="panel-footer text-center">
					<a target="_blank" href="<?=site_url('gsk_news/edit_help?type=edit&help_id='.$item['f_help_id'])?>" class="btn btn-primary" role="button">修 改</a>
					<button type="button" data-loading-text="Loading..." value="<?=$item['f_help_id']?>" class="btn btn-danger btn_news_del">删 除</button>
				</div>
			</div>
		</div>
	<?php endforeach;?>
</div>


<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], $data['filter']);?>
	</div>
</div>


