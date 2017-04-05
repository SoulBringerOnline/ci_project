<div class="page-header">
	<h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div> <!-- / .page-header -->

<form method="post" action="<?php echo site_url('gsk_pub/do_pub_pc');?>"  name="form_id_do_pub_pc" >
	<!-- Modal -->
	<div id="modal_pub_pc" method="post" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4>发布PC版本</h4>
				</div>

				<div class="modal-body">
					<span id="modal_pub_info"></span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">放弃</button>
					<button type="submit"  class="btn btn-primary">确认发布</button>

				</div>

			</div> <!-- / .modal-content -->
		</div> <!-- / .modal-dialog -->
	</div>
	<!-- / Modal -->

	<?php
		$version_channel = array();
		foreach ($channel as $item) :
			$version_channel[$item['f_pc_version']] = $version_channel[$item['f_pc_version']] . $item['f_id'] . ' ' .  $item['f_name'] . '  ';
		endforeach;?>

	<div class="row">

		<div class="col-sm-8 col-sm-push-4">
			<div class="panel panel-info panel-dark widget-profile">
				<div class="panel-heading">
					<div class="widget-profile-bg-icon"><i class="fa fa-windows"></i></div>
					<div class="widget-profile-header">
						<span><i class="fa fa-windows"></i> 选择版本</span>
                    </div>
                </div> <!-- / .panel-heading -->
                <div class="list-group">
                    <p style="margin:10px">
                     版本  <input type="text" name="text_pub_pc" id="text_pub_pc" >
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-sm-pull-8">
            <div class="panel panel-success panel-dark widget-profile">
                <div class="panel-heading">
                    <div class="widget-profile-bg-icon"><i class="fa fa-windows"></i></div>
                    <div class="widget-profile-header">
                        <span><i class="fa fa-windows"></i>  选择渠道</span>
                    </div>
                </div> <!-- / .panel-heading -->
                <div class="list-group">
                    <?php foreach ($channel as $index => $item) : ?>
                        <?php if($item['f_os'] == 0 || $item['f_os'] == 4) :?>
                            <p class="list-group-item">
                                <input type="checkbox" id="<?=$item['f_name']?>" name="checkbox_pub_pc[]"  value=<?=$item['f_id']?> />
                                <?=$item['f_name']?><span>[<?=$item['f_id']?>]</span>
                        <span class="pull-right">
                        <a target="_blank" href="<?php echo site_url('gsk_pub/pub_list?type=pc&c=');?><?=$item['f_id']?>"><span class="label label-info">历史版本</span></a>
                        <a target="_blank" href="http://gxtmobile.glodon.com:8888/clientfiles/gsk/gsk.html?c=<?=$item['f_id']?>"><i class="fa fa-html5"></i></a>
                        <a target="_blank" href="<?=site_url( 'gsk_pub/down_pkg' );?>"><i class="fa fa-qrcode"></i></a>
                        <a target="_blank" href="http://zy.glodon.com/util/info.php?c=<?=$item['f_id']?>&clt=4"><i class="fa fa-cog"></i></a>
                        <span class="label label-<?=get_random_state($item['f_pc_version']);?>"><?=$item['f_pc_version'] ?></span>
                        </span>
                            </p>
                        <?php endif;?>
                    <?php endforeach ?>
                </div>
            </div> <!-- / .panel -->


            <div class="panel panel-dark">
                <div class="panel-body text-center">
                    <a href="#" class="btn btn-lg btn-primary btn-flat" data-toggle="modal" data-target="#modal_pub_pc">发  布</a>
                </div>
            </div>
        </div>

    </div>
</form>

<!-- Matter ends -->
        <script type="text/javascript">
        $(document).ready(function(){
            $("#modal_pub_pc").on('show.bs.modal', function (e) {
                var select_ver = $( "#text_pub_pc" ).val()
                    if ( select_ver === undefined || select_ver.length == 0 )
                    {
                            ( "版本号未选择" )
                            return e.preventDefault()
                    }
                var select_channel = ""
                    $( "input[type=checkbox][name='checkbox_pub_pc[]']:checked" ).each(function(){
                        select_channel += "<span style='font-size:16px;-webkit-text-stroke: 1px #000000;'>" + this.value + "</span>" + this.id + "      "
                    });
                if ( select_channel.length == 0 )
                {
                    ( "渠道未选择" )
                        return e.preventDefault()
                }
                select_channel += "<hr>发布到版本 : <span class='label label-success'>" + select_ver + "</span><br>"
                    $("#modal_pub_info").html(  select_channel )
            });
        });
        </script>

