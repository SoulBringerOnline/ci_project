<script src="<?=base_url('assets/js/layer/layer.js') ?>"></script>
<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>
<div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-9">
				<!-- Success darker background -->
				<div class="stat-cell darker">
					<!-- Stat panel bg icon -->
					<i class="fa fa-lightbulb-o bg-icon" style="font-size:60px;line-height:80px;height:80px;"></i>
					<div class="DT-lf-left" style="border: none;">
						<div class="DT-per-page">
							<div class="dataTables_length" id="jq-datatables-example_length">
								选择月份:
								<label>
									<select name="jq-datatables-example_length" id="year" aria-controls="jq-datatables-example" class="form-control input-sm">
										<option value="2015" <?php if($year == 2015){echo "selected";}?>>2015</option>
										<option value="2016" <?php if($year == 2016){echo "selected";}?>>2016</option>
									</select>&nbsp;&nbsp;年
								</label>
							</div>
						</div>
						<div class="DT-per-page" style="border: none;">
							<div class="dataTables_length" id="jq-datatables-example_length">
								<label>
									<select name="jq-datatables-example_length" id="month" aria-controls="jq-datatables-example" class="form-control input-sm">
										<?php
											if($year < date("Y")){
												$loop = 12;
											}else{
												$loop = date("m");
											}
										?>
										<?php
											for ($i=1; $i<=12; $i++) {
										?>
										<option <?php if($month == $i){echo "selected";}?> value="<?=$i?>"><?=$i?></option>
										<?php
											}
										?>
									</select>
								</label>&nbsp;&nbsp;月
							</div>
						</div>
						<div class="DT-per-page" style="border: none;">
						<a href="#" class="btn btn-success btn-labeled" style="width: 100%;" id="combo"><span class="btn-label icon fa fa-cogs"></span>调整本月档位</a>
						</div>
						<div class="DT-per-page" style="border: none;">
							<a href="#" class="btn btn-info btn-labeled" style="width: 100%;" id="next_month_combo"><span class="btn-label icon fa fa-cogs"></span>修改下月档位</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3">
				<div class="stat-panel">
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>当月档位</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>当月档位单价</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>当月最低消费</strong></span>
							</div>
						</div>
					</div>
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<div class="stat-counters bg-success no-border-b no-padding text-center">
								<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
									<span class="text-bg"><strong><?=$currentCombo['f_grade']?></strong></span>
								</div>
								<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
									<span class="text-bg"><strong><?=$currentCombo['f_price']?>元/分钟</strong></span>
								</div>
								<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
									<span class="text-bg"><strong><?=$currentCombo['f_min_cost']?>元</strong></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="stat-panel">
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>主账户余额</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>副账户余额</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>本月消费总金额</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>本月总通话时长</strong></span>
							</div>
						</div>
					</div>
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$currentCombo['f_balance']?></strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$currentCombo['f_sub_balance']?></strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$flowlist['totalMoney']?></strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$flowlist['totalTime']?></strong></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<div class="stat-panel">
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>本月发放人数</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>本月发放时长</strong></span>
							</div>
						</div>
					</div>
					<div class="stat-row">
						<div class="stat-counters bg-success no-border-b no-padding text-center">
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$grant['totalUser']?></strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$grant['totalTime']?>分钟</strong></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="stat-panel">
					<div class="stat-row">
						<div class="stat-counters bg-info no-border-b no-padding text-center">
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>下月档位</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>下月档位单价</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong>下月最低消费</strong></span>
							</div>
						</div>
					</div>
					<div class="stat-row">
						<div class="stat-counters bg-info no-border-b no-padding text-center">
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$nextCombo['f_grade']?></strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$nextCombo['f_price']?>元/分钟</strong></span>
							</div>
							<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
								<span class="text-bg"><strong><?=$nextCombo['f_min_cost']?>元</strong></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="table-primary">
			<div role="grid" id="jq-datatables-example_wrapper" class="dataTables_wrapper form-inline no-footer">
				<div class="table-header clearfix">
					<div class="table-caption"><?=date("Y年m月j日", $flowlist['start'])?>-<?=date("Y年m月j日", $flowlist['end'])?>通话账单</div>
				</div>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="jq-datatables-example" aria-describedby="jq-datatables-example_info">
					<thead>
					<tr role="row">
						<th tabindex="0" rowspan="1" colspan="1" style="width: 200px;">时间</th>
						<th tabindex="0" rowspan="1" colspan="1" style="width: 200px;">筑友总通话时长（分钟）</th>
						<th tabindex="0" rowspan="1" colspan="1" style="width: 200px;">筑友通话记录数</th>
						<!--<th tabindex="0" rowspan="1" colspan="1" style="width: 318px;">容联总通话时长</th>
						<th tabindex="0" rowspan="1" colspan="1" style="width: 218px;">容联通话记录数</th>-->
						<th tabindex="0" rowspan="1" colspan="1" style="width: 200px;">实际费用（元）</th>
						<!--<th tabindex="0" rowspan="1" colspan="1" style="width: 170px;">账户余额</th>-->
						<th tabindex="0" rowspan="1" colspan="1" style="width: 200px;">异常订单</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($flowlist['list'] as $key=>$val):?>
					<tr class="gradeA odd">
						<td class="sorting_1"><?=date("Y-m-d",strtotime($key))?></td>
						<td><?=$val['duration']?></td>
						<td><?=$val['count']?></td>
						<!--<td class="center">100</td>
						<td class="center">100</td>-->
						<td class="center"><?=$val['money']?></td>
						<!--<td class="center">100</td>-->
						<td class="center"><a href="/gsk/index.php/gsk_news/call_record?start=<?=date("Y-m-d",strtotime($key))?>&end=<?=date("Y-m-d", strtotime($key)+86400)?>&status=0">查看详情(<?=$val['error']?>)</a></td>
					</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(function(){
		$("#combo").click(function(){
			layer.open({
				title: "调整本月档位",
				type: 2,
				area: ['450px', '605px'],
				fix: false, //不固定
				maxmin: true,
				content: '/gsk/index.php/gsk_news/call_change_combo'
			});
		});

		$("#next_month_combo").click(function(){
			layer.open({
				title: "修改下月档位",
				type: 2,
				area: ['450px', '605px'],
				fix: false, //不固定
				maxmin: true,
				content: '/gsk/index.php/gsk_news/call_date_combo_add'
			});
		});

		$("#month").change(function(){
			var year = $("#year").val();
			var month = $("#month").val();

			location.href = "/gsk/index.php/gsk_news/call_balance_account/?year="+year+"&month="+month;
		});
	})
</script>