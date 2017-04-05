<link rel="stylesheet" href="<?=base_url('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')?>"  type="text/css">
<script src="<?=base_url('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')?>" type="text/javascript"></script>

<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<form action="<?php echo site_url('gsk_news/user_integral');?>" method="post">
	<div class="row">
		<div class="form-group">
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon">时间</span>
					<div class="input-append date form_datetime"  data-date="<?=isset($data['filter']['start_time'])?date("Y-m-d",strtotime($data['filter']['start_time'])):date("Y-m-d",strtotime("-1 day"))?>">
						<input size="16" name="start_time" type="text" value="<?=isset($data['filter']['start_time'])?date("Y-m-d",strtotime($data['filter']['start_time'])):date("Y-m-d",strtotime("-1 day"))?>" readonly style="height: 30px; width: 250px">
						<span class="add-on" style="height: 30px"><i class="icon-remove"></i></span>
						<span class="add-on" style="height: 30px"><i class="icon-th"></i></span>
					</div>
					<span class="input-group-addon">至</span>
					<div class="input-append date form_datetime"  data-date="<?=isset($data['filter']['end_time'])?date("Y-m-d",strtotime($data['filter']['end_time'])):date("Y-m-d")?>">
						<input size="16" name="end_time" type="text"  value="<?=isset($data['filter']['end_time'])?date("Y-m-d",strtotime($data['filter']['end_time'])):date("Y-m-d")?>" readonly style="height: 30px; width: 250px">
						<span class="add-on" style="height: 30px"><i class="icon-remove"></i></span>
						<span class="add-on" style="height: 30px"><i class="icon-th"></i></span>
					</div>
					<script type="text/javascript">
						$(".form_datetime").datetimepicker({
							startView: 2,
							minView: 2,
							format: "yyyy-mm-dd",
							language: 'zh-CN',
							//			  showMeridian: true,
							autoclose: true,
							todayBtn: true
						});
					</script>
				</div>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-1"></div>
			<div class="col-sm-1"></div>
			<div class="col-sm-1"></div>
			<button type="submit" name="btn_query" class="btn btn-danger pull-right" style="margin-right: 10px; width: 120px">查 询</button>
		</div>
		<div class="form-group">
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon1">用户id</span>
					<input type="text" class="form-control" name="f_uin" aria-describedby="news-addon1"
					       value="<?=isset($data['filter']['f_uin'])?$data['filter']['f_uin']:''?>">
				</div>
			</div>
			<div class="col-sm-2" >
				<div class="input-group">
					<span class="input-group-addon" id="news-addon2">用户昵称</span>
					<input type="text" class="form-control" name="f_name" aria-describedby="news-addon2"
					       value="<?=isset($data['filter']['f_name'])?$data['filter']['f_name']:''?>">
				</div>
			</div>
			<div class="col-sm-2" >
				<div class="input-group">
					<span class="input-group-addon" id="news-addon3">变动原因</span>
					<input type="text" class="form-control" name="f_log_desc" aria-describedby="news-addon3"
					       value="<?=isset($data['filter']['f_log_desc'])?$data['filter']['f_log_desc']:''?>">
				</div>
			</div>
			<div class="col-sm-2" >
				<div class="input-group">
					<span class="input-group-addon" id="news-addon4">手机号</span>
					<input type="text" class="form-control" name="f_phone" aria-describedby="news-addon4"
					       value="<?=isset($data['filter']['f_phone'])?$data['filter']['f_phone']:''?>">
				</div>
			</div>
			<button type="submit"  name="btn_export" class="btn btn-danger pull-right" style=" margin-right: 10px;  width: 120px">导出Excel</button>
		</div>
	</div>
</form>

<div class="panel colourable">
	<div class="panel-heading">
		<span class="panel-title"></span>
	</div>

	<!-- List group -->
	<ul class="list-group">
		<?php foreach ($data['data'] as $item): ?>
			<li class="list-group-item">
				用户id :<?=action_type($item['f_log_uid'])?>&nbsp;&nbsp;&nbsp;
				<span class="text-muted">用户昵称：<?= $item['f_name']?></span> &nbsp;&nbsp;&nbsp;
				注册账号 :<?=$item['f_uin']?>&nbsp;&nbsp;&nbsp;
				<span class="text-muted">手机号:<?=$item['f_phone']?></span>&nbsp;&nbsp;&nbsp;
				<span class="label label-<?=get_random_state($item['f_log_point'])?>">积分变动：<?=$item['f_log_point'];?> </span>&nbsp;&nbsp;&nbsp;
				 时间:<?=human_time($item['f_log_in_time']/1000)?>
				<span class="pull-right label label-<?=get_random_state($item['f_log_desc'])?>">变动原因:<?=$item['f_log_desc']?></span>
			</li>
		<?php endforeach;?>
	</ul>
</div>

<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], $data['filter'], $data['page']['max']['max_page'], $data['page']['max']['max_count']);?>
	</div>
</div>
