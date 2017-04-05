<!-- Page heading -->
<div class="page-head">
    <h2 class="pull-left">
        <?=$title?>
        <span class="page-meta"></span>
    </h2>
    <div class="clearfix"></div>
</div>
<!-- Page heading ends -->

<!-- Matter -->
<div class="matter">
    <div class="container">
        <form method="post" action="<?php echo site_url();?>/gsk_pub/do_pub_test"  name="form_id_do_pub_test" />

        <!-- Modal -->
        <div class="modal fade" id="modal_pub_test" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>发布到测试环境</h4>
              </div>

              <div class="modal-body">
                <span id="modal_pub_info"></span> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">放弃</button>
                <button type="submit"  class="btn btn-primary">确认发布</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div class="row">	
            <div class="col-md-12 bs-callout bs-callout-info" >
            <h1><code>测试环境</code><small>选择要发布的渠道</small></h1>
            <hr>
                <?php foreach ($channel as $index => $item) : ?>
                    <?php if( $index % 3 == 0 ):?>
                        <div class="row">
                    <?php endif;?>

                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                        <label class="checkbox">
                        <input type="checkbox" id="<?=$item['f_name']?>" name="checkbox_pub_test_versions[]"  value=<?=$item['f_id']?> >
                        <span style="font-size:16px;-webkit-text-stroke: 1px #000000;"><?=$item['f_id']?></span>
                            <?=$item['f_name']?>
                        <span class="<?=get_label_class( $item['f_test_version'] )?>"><?=$item['f_test_version'] ?></span>
                        </div>
                    </div>
                </div>
                <?php if( $index % 3 == 2 ):?>
                    </div><hr>
                <?php endif;?>
                <?php endforeach ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="row">   
            <div class="col-md-12 text-center" >
                    <button id="btn_pub_test"  class="btn btn-info" data-toggle="modal" data-target="#modal_pub_test">发  布</button>
            </div>
        </div>

        <div class="row">   

            <div class="col-md-12 bs-callout bs-callout-warning" >
                <h4>选择要发布的版本</h4>
                <hr>
                <?php foreach( $versions as $item ) :?>
                    <div class="itemdiv dialogdiv" >
                        <div class="body ">
                            <div class="time">
                                <i class="fa fa-calendar"></i>
                                <span>                            
                                <?=$item['f_time'];?>
                                </span>
                            </div>

                            <div class="name">
                            <label class="black">
                            <input type="radio" name="radio_pub_test_versions" id=<?=$item['f_version']?> value=<?=$item['f_version']?>>
                            版本ID: <code><?=$item['f_version']?></code> 
                            </label>
                            <a data-toggle="collapse" data-toggle="collapse" href="#collapse_pub_test_<?=$item['f_version']?>" class='lightblue'>
                                &nbsp;&nbsp;&nbsp;&nbsp;[更多GITLOG]
                            </a>
                            
                            </div>
                            <div class="text">
                            <?php
                                $item['f_desc'] = nl2br($item['f_desc']);
                                echo substr( $item['f_desc'] , 0 , strpos($item['f_desc'], "<br") ) . ' ...' ;
                            ?>

                            <div id="collapse_pub_test_<?=$item['f_version']?>" class="collapse">
                              <?=$item['f_desc'];?>
                            </div>
                            </div>
                            </div>
                    </div>
                <?php endforeach;?>
               
                <div class="clearfix"></div>
            </div>
        </div>

    </form>
    </div>
</div>

<!-- Matter ends -->
<script type="text/javascript">
$(document).ready(function(){
        $("#btn_pub_test").on('click', function(){ 
            var select_ver = $( "input[type=radio][name='radio_pub_test_versions']:checked" ).val()
            if ( select_ver === undefined || select_ver.length == 0 )
            {
                ( "版本号未选择" )
                location.reload() 
            }
            var select_channel = ""
            $( "input[type=checkbox][name='checkbox_pub_test_versions[]']:checked" ).each(function(){ 
                select_channel += "<span style='font-size:16px;-webkit-text-stroke: 1px #000000;'>" + this.value + "</span>" + this.id + "      "
            }); 
            if ( select_channel.length == 0 )
            {
                ( "渠道未选择" )
                location.reload() 
            }

            select_channel += "<hr>发布到版本 : <span class='label label-success'>" + select_ver + "</span><br>"
            $("#modal_pub_info").html(  select_channel )
    });
});

</script>
