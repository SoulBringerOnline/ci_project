<link rel="stylesheet" href="<?=base_url('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')?>"  type="text/css">
<script src="<?=base_url('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')?>" type="text/javascript"></script>

<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<form action="<?php echo site_url('gsk_news/liumi_flowlog');?>" method="post">
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
			<div class="col-sm-2" style="margin-left: 60px">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon1">运营商</span>
					<input type="text" class="form-control" name="f_carrieroperator" aria-describedby="news-addon1"
					       value="<?=isset($data['filter']['f_carrieroperator'])?$data['filter']['f_carrieroperator']:''?>">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon2">流量包</span>
					<input type="text" class="form-control" name="f_post_package" aria-describedby="news-addon2"
					       value="<?=isset($data['filter']['f_post_package'])?$data['filter']['f_post_package']:''?>">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon6">订单号</span>
					<input type="text" class="form-control"  name="f_order_no" aria-describedby="news-addon6"
					       value="<?=isset($data['filter']['f_order_no'])?$data['filter']['f_order_no']:''?>">
				</div>
			</div>
			<button type="submit" name="btn_query" class="btn btn-danger " style="float: left; margin-left: 60px; width: 100px">查 询</button>
		</div>
		<div class="form-group">
			<div class="col-sm-2">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon3">用户id</span>
					<input type="text" class="form-control" id="news_source" name="f_uin" aria-describedby="news-addon3"
					       value="<?=isset($data['filter']['f_uin'])?$data['filter']['f_uin']:''?>">
				</div>
			</div>
			<div class="col-sm-2" style="margin-left: 100px">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon4">用户昵称</span>
					<input type="text" class="form-control" name="f_name" aria-describedby="news-addon4"
					       value="<?=isset($data['filter']['f_name'])?$data['filter']['f_name']:''?>">
				</div>
			</div>
			<div class="col-sm-2" style="margin-left: 100px">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon5">手机号</span>
					<input type="text" class="form-control" name="f_mobile" aria-describedby="news-addon5"
					       value="<?=isset($data['filter']['f_mobile'])?$data['filter']['f_mobile']:''?>">
				</div>
			</div>
			<div class="col-sm-2" style="margin-left: 100px">
				<div class="input-group">
					<span class="input-group-addon" id="news-addon7">兑换结果</span>
					<input type="text" class="form-control" name="f_status" aria-describedby="news-addon7"
					       value="<?=isset($data['filter']['f_status'])?$data['filter']['f_status']:''?>">
				</div>
			</div>
			<button type="submit"  name="btn_export" class="btn btn-danger " style="float: left; margin-left: 98px;  width: 100px">导出Excel</button>
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
				活动类型 :<?=action_type($item['f_action_id'])?> <span class="text-muted">订单号：<?= $item['f_order_no']?></span>
					用户昵称:<?=$item['f_name']?>
					用户id:<?=$item['f_uin']?>
					手机号:<?=$item['f_mobile']?>
					<span class="label label-<?=get_random_state($item['f_post_package'])?>">兑换流量：<?=$item['f_post_package'];?> </span>
						<?=carrieroperator_type($item['f_carrieroperator'])?> 兑换时间:<?=human_time($item['f_pay_time']/1000)?> 流米返回时间:<?=human_time($item['f_callBack_time'])?>
					<span class="pull-right label label-<?=get_random_state($item['f_status'])?>">兑换结果:<?=flow_exchange_status($item['f_status'])?></span>
			</li>
		<?php endforeach;?>
	</ul>
</div>

<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'],  $data['filter'], $data['page']['max']['max_page'], $data['page']['max']['max_count']);?>
	</div>
</div>
