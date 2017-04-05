<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div> <!-- / .page-header -->

<form>
	<div class="row">

		<div class="col-sm-6 col-sm-push-6">
			<div class="panel panel-info panel-dark widget-profile">
				<div class="panel-heading">
					<div class="widget-profile-bg-icon"><i class="fa fa-apple"></i></div>
					<div class="widget-profile-header">
						<span><i class="fa fa-sort-alpha-desc"></i> 积分飙升榜</span>
					</div>
				</div> <!-- / .panel-heading -->
				<div class="list-group">
					<div class="table-responsive">
						<table class="table">
							<thead>
							<tr>
								<th>用户id</th>
								<th>用户昵称</th>
								<th>手机号</th>
								<th>时间</th>
								<th>昨日飙分</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($soar_data['data'] as $item): ?>
								<tr>
									<td><?=$item['f_pds_uid']?></td>
									<td><?=$item['f_name']?></td>
									<td><?=$item['f_phone']?></td>
									<td><?= date('Y-m-d H:i',$item['f_pds_day_time']/1000) ?></td>
									<td><?=$item['f_pds_day_point']?></td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-sm-pull-6">
			<div class="panel panel-success panel-dark widget-profile">
				<div class="panel-heading">
					<div class="widget-profile-bg-icon"><i class="fa fa-apple"></i></div>
					<div class="widget-profile-header">
						<span><i class="fa fa-sort-alpha-desc"></i> 积分排行榜</span>
					</div>
				</div> <!-- / .panel-heading -->
				<div class="list-group">
					<div class="list-group">
						<div class="table-responsive">
							<table class="table">
								<thead>
								<tr>
									<th>用户id</th>
									<th>用户昵称</th>
									<th>手机号</th>
									<th>用户积分</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($rank_data['data'] as $item): ?>
									<tr>
										<td><?=$item['f_uin']?></td>
										<td><?=$item['f_name']?></td>
										<td><?=$item['f_phone']?></td>
										<td><?=$item['f_points']?></td>
									</tr>
								<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div> <!-- / .panel -->
		</div>

	</div>
</form>