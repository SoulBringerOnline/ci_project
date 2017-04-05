<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>


<div class="panel colourable">
	<!-- Default panel contents -->
	<div class="panel-heading">
		<span class="panel-title"></span>
	</div>

	<!-- List group -->
	<ul class="list-group">
		<?php foreach ($data['data'] as $item): ?>
		<li class="list-group-item">
			<?=$item['user_feedback']?> <span class="text-muted"><?= $item['f_user_name']?>(<?= $item['f_user_phone']?>)</span>
			&nbsp;&nbsp;&nbsp;<?=isset($item['f_feedback_time'])?human_time($item['f_feedback_time']):""?>
		</li>
		<?php endforeach;?>
	</ul>
</div>

<div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], array('query'=>$_REQUEST['query']));?>
	</div>
</div>
