<div class="page-header">
	<h1><span class="text-light-gray">PV数据统计/</h1>
</div>

<?php
	// $stat = $stats[11];
	// var_dump($stat['ur']);
	// var_dump($stat['ur3']);
	// var_dump($stat['ur7']);
?>

<script>
	$(function () { $("[data-toggle='tooltip']").tooltip(); });
</script>

<div class="panel">
	<div class="panel-body">
		<table class="table table-condensed">
			<thead>
			<tr>
				<th style="text-align: center">发布时间</th>
				<th style="text-align: center">标题</th>
				<th style="text-align: center">总浏览量</th>
				<?php foreach ($data['time'] as $item) :?>
					<td><?=$item;?></td>
				<?php endforeach;?>
			</tr>
			</thead>
			<?$i = 0?>
			<?php foreach ($data['titles'] as $title) :?>
				<tr>
					<td style="width: 150px"><?=$title['publish_time']?></td>
					<td style="width: 190px">
						<?=mb_strlen($title['title'])>=10?mb_substr($title['title'],0,10,'utf-8')."...":$title['title']?>
					</td>
					<?php foreach ($data['counts'][$i] as $count) :?>
						<td><?=$count?></td>
					<?php endforeach;?>
				</tr>
				<? $i++ ?>
			<?php endforeach;?>
			<tbody>
			</tbody>
		</table>
	</div>
</div>