<style>
	.col-sm-pull-6 {
		right: 0px;
	}
</style>
<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span>  </h1>
</div> <!-- / .page-header -->

<form>
	<div class="row">

		<div class="col-sm-6 col-sm-pull-6">
			<div class="panel panel-success panel-dark widget-profile">
				<div class="panel-heading">
					<div class="widget-profile-bg-icon"><i class="fa fa-apple"></i></div>
					<div class="widget-profile-header">
						<span><i class="fa fa-sort-alpha-desc"></i> 用户列表</span>
						<a style="float: right;" href="<?=site_url('gsk_user_op/add_user');?>"> 添加用户</a>
					</div>
				</div> <!-- / .panel-heading -->
				<div class="list-group">
					<div class="table-responsive">
						<table class="table">
							<thead>
							<tr>
								<th>用户账号</th>
								<th>权限</th>
								<th>群组</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($data as $item): ?>
								<tr>
									<td><?=$item['account']?></td>
									<td><?=$item['priv']?></td>
									<td><?=$item['group']?></td>
									<td><?=$item['status']?></td>
									<td><a href="<?=site_url('gsk_user_op/edit_user');?>?account=<?=$item['account']?>" >编辑</a></td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>