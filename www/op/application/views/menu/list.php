<div class="page-header">
	<h1><span class="text-light-gray">菜单设置 <span>  </h1>
</div>

<div><a href="<?=site_url('gsk_menu/add_menu');?>">+添加菜单项</a></div>
<div class="panel colourable">
	<div class="panel-heading">
		<span class="panel-title"></span>
	</div>

	<!-- List group -->
	<ul class="list-group">
		<?php foreach ($data as $item): ?>
			<li class="list-group-item">
				<span><a href="<?=site_url('gsk_menu/menu_edit');?>?id=<?php echo $item['id'];?>" ><?php echo $item['title'];?></a></span>
				<span><?php echo $item['id'];?></span>
				<span><?php echo $item['type'];?></span>
			</li>
			<?php foreach ($item["sub"] as $item): ?>
				<li class="list-group-item sub">
					<span>+----</span>
					<span><a href="<?=site_url('gsk_menu/menu_edit');?>?id=<?php echo $item['id'];?>" ><?php echo $item['title'];?></a></span>
					<span><?php echo $item['id'];?></span>
					<span><?php echo $item['type'];?></span>
				</li>
			<?php endforeach;?>
		<?php endforeach;?>
	</ul>
</div>

<!-- div class="row">
	<div class="col-md-12">
		<?=pages($data['page']['cur_page'], array('query'=>$_REQUEST['query']));?>
	</div>
</div -->
