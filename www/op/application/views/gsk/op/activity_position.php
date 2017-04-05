<div class="page-header" xmlns="http://www.w3.org/1999/html">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<link rel="stylesheet" href="<?=base_url('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')?>"  type="text/css">
<script src="<?=base_url('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')?>" type="text/javascript"></script>

<style type="text/css">
	.table th{text-align: center}
</style>
<!-- / Javascript -->

<div class="table-responsive">
	<table class="table" style="text-align: center">
		<thead>
		<tr style="background-color: #1169EE; color: #ffffff">
			<th>运营位id</th>
			<th>运营位名称</th>
			<th>状态</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody>

		<?php foreach ($data as $item): ?>
			<tr>
				<td><?=$item['f_position_id']?></td>
				<td><?=$item['f_position_name']?></td>
				<td>
					<span class="label label-<?=get_random_state($item['f_position_state'])?>">
						<?=activity_position_state($item['f_position_state'])?>
					</span>
				</td>
				<td>
					<a href="<?=site_url('gsk_news/update_position_state?id='.$item['f_position_id'].'&state='.$item['f_position_state'])?>">
						<?php if($item['f_position_state'] == 1){echo '下线';} else {echo '上线';} ?></a>|
					<a target="_blank" href="<?=site_url('gsk_news/add_activity_pt?id='.$item['f_position_id'].'&name='.$item['f_position_name'])?>">
						添加</a>|
					<a target="_blank" href="<?=site_url('gsk_news/change_activity_pt?id='.$item['f_position_id'].'&name='.$item['f_position_name'])?>">修改</a>
				</td>
			</tr>
		<?php endforeach;?>


		</tbody>
	</table>
</div>