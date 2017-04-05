<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<link rel="stylesheet" href="<?=base_url('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')?>"  type="text/css">
<script src="<?=base_url('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')?>" type="text/javascript"></script>


<link rel="stylesheet" type="text/css" href="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.css')?>"></link>
<script src="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all.js')?>"></script>

<!-- / Javascript -->

<form method="get" action="<?php echo site_url('gsk_promotion/data');?>"  name="form_statis">
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
		<div class="col-sm-1" style="width: 200px"></div>
		<div class="form-group">
			<div class="col-sm-1"></div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon1">推广人员</span>
					<input type="text" class="form-control" id="news_source" name="f_worker_name" aria-describedby="news-addon1"
					       value="<?=isset($data['filter']['f_worker_name'])?$data['filter']['f_worker_name']:''?>">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon2">推广码</span>
					<input type="text" class="form-control" name="f_worker_code" aria-describedby="news-addon2"
					       value="<?=isset($data['filter']['f_worker_code'])?$data['filter']['f_worker_code']:''?>">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon3">手机号</span>
					<input type="text" class="form-control" name="f_worker_phone" aria-describedby="news-addon3"
					       value="<?=isset($data['filter']['f_worker_phone'])?$data['filter']['f_worker_phone']:''?>">
				</div>
			</div>
			<button type="submit" id="btn_query" class="btn btn-danger" style="float: left; margin-left: 20px; width: 80px">查 询</button>
			<button type="submit"  name="btn_export" class="btn btn-danger pull-right" style=" margin-right: 10px;  width: 80px">导出Excel</button>
		</div>
	</div>
	</div>
</form>
<div class="table-responsive">
	<table class="table">
		<thead>
		<tr>
			<th>日期</th>
			<th>推广员</th>
			<th>推广码</th>
			<th>手机号</th>
			<th>推广用户数</th>
			<th>有效用户数</th>
			<th>7日内登录3天的用户数</th>
			<th>推广系数</th>
			<th>收入</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($data['data'] as $item): ?>
			<tr>
				<td><?= date('Y-m-d',$item['f_date']) ?></td>
				<td><?=$item['f_name']?></td>
				<td><?=$item['f_invite_code']?></td>
				<td><?=$item['f_phone']?></td>
				<td><?=$item['f_pro_count']?></td>
				<td><?=$item['f_valid_count']?></td>
				<td><?=$item['f_h7au_num']?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>

<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], $data['filter'], $data['page']['max']['max_page'], $data['page']['max']['max_count']);?>
	</div>
</div>








