<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div> <!-- / .page-header -->



	<div class="row">
		<div class="">
			<div class="panel panel-info panel-dark widget-profile">
				<div class="panel-heading">
					<div class="widget-profile-bg-icon"><i class="fa fa-android"></i></div>
					<div class="widget-profile-header">
						<span><i class="fa fa-android"></i> 历史版本 </span>
					</div>
				</div> <!-- / .panel-heading -->
				<div class="list-group">
					<?php foreach( $versions as $item ) :
						$item['f_version'] = $item['f_android_version'] ? $item['f_android_version'] : $item['f_ios_version'];
						?>
						<p class="list-group-item">
							版本ID: <span class="label-tag label label-<?=get_random_state($item['f_version']);?>"><?=$item['f_version']?></span>

							<?php if( isset($version_channel[$item['f_version']]) ):?>
								<span class="label label-<?=get_random_state($item['f_version']+1);?>"><?=$version_channel[$item['f_version']]?></span>
							<?php endif;?>

							<span class="pull-right">
                            <i class="fa fa-calendar"></i> <?=$item['f_time'];?>
								<a data-toggle="collapse" href="#pub_android_collapse_<?=$item['f_version']?>" aria-expanded="false" aria-controls="pub_android_collapse_<?=$item['f_version']?>">详情</a>
                        </span>

						<div class="collapse" id="pub_android_collapse_<?=$item['f_version']?>">
                        <span class="text-muted">
                            <?=nl2br($item['f_desc']);?>
                        </span>
						</div>

						</p>
					<?php endforeach;?>
				</div>
			</div>
		</div>

		</div>

	</div>

<!-- Matter ends -->
<script type="text/javascript">
</script>

