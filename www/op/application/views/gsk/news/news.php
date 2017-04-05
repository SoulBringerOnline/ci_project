<div class="page-header">
<form method="get" action="<?php echo site_url('gsk_news/news');?>"  name="form_id_news" >
  <div class="row">
  <div class="col-md-12">

    <div class="col-md-2">
      <select class="form-control form-group-margin" name="news_status">
        <option value="1" <?php if($data['filter']['news_status'] == 1){echo 'selected="selected"';} ?>>待发布</option>
        <option value="2" <?php if($data['filter']['news_status'] == 2){echo 'selected="selected"';} ?>>已发布</option>
        <option value="3" <?php if($data['filter']['news_status'] == 3){echo 'selected="selected"';} ?>>首页推荐</option>
        <option value="4" <?php if($data['filter']['news_status'] == 4){echo 'selected="selected"';} ?>>相关阅读</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit"  class="btn btn-primary">查询</button>
    </div>
    <div class="col-md-2">
      
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-2">
      <a target="_blank"  href="<?=site_url('gsk_news/do_init_sort_id')?>" class="btn btn-danger btn-labeled" style="width: 100%;"><span class="btn-label icon fa fa-plus"></span>清空排序</a>
    </div>
    <div class="col-md-2">
      <a target="_blank"  href="<?=site_url('gsk_news/edit_news?type=add')?>" class="btn btn-primary btn-labeled" style="width: 100%;"><span class="btn-label icon fa fa-plus"></span>添加资讯</a>
    </div>
  </div>
  </div>  
  </form>
</div> 

<script type="text/javascript">
  init.push(function () {
    $('.btn_news_del').click(function () {
      var btn = $(this);
      var panel = $('#panel_news_' + btn.val())
      $.ajax({
          type:"POST",
          url:"<?=site_url('gsk_news/do_del_news');?>",
          data:{'id':btn.val()},
          datatype: "json",
          beforeSend:function(){
            btn.button('loading');
          },
          success:function(data){
            panel.fadeOut('fast',function(){
              panel.remove();
            });
          } ,
          error: function(){
            btn.button('reset');   
            btn.html('删除失败')
          }         
       });
    });

    function change_news_status(btn , status) {
      var panel = $('#panel_news_' + btn.val())
      $.ajax({
          type:"POST",
          url:"<?=site_url('gsk_news/do_change_news_status');?>",
          data:{'id':btn.val() , 'news_status': status },
          datatype: "json",
          beforeSend:function(){
            btn.button('loading');
          },
          success:function(data){
            panel.fadeOut('fast',function(){
              panel.remove();
            });
          } ,
          error: function(){
            btn.button('reset');   
            btn.html('失败')
          }         
       });
    }

    $('.btn_news_online').click(function () {
      change_news_status( $(this), 2 ) 
    });
    $('.btn_news_offline').click(function () {
      change_news_status( $(this), 1 ) 
    });
    $('.btn_news_rec').click(function () {
      change_news_status( $(this), 3 ) 
    });
    $('.btn_news_related').click(function () {
      change_news_status( $(this), 4 ) 
    });
    $('.btn_news_unrec').click(function () {
      change_news_status( $(this), 2 ) 
    });

  });
</script>
<!-- / Javascript -->

<div class="row">
<div class="col-md-12">
<?=pages($data['page']['cur_page'], $data['filter']);?>
</div>
</div>

<div class="panel-group" id="accordion_news">
<?php foreach ($data['data'] as $item): ?>
<div class="panel" id="panel_news_<?=$item['f_hotspot_id']?>">
  <div class="panel-heading">
    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_news" href="#collapse_news_<?=$item['f_hotspot_id']?>">
      <?=$item['f_hotspot_title']?>
      <span class="pull-right label label-<?=get_random_state($item['f_hotspot_source_site'])?>"><?=$item['f_hotspot_source_site'];?></span>  
      <span class="pull-right label label-<?=get_random_state($item['f_hotspot_site'])?>"><?=$item['f_hotspot_site'];?></span>
    </a>
    <p class="text-muted" style="padding:0px 35px 0px 20px;">
        入库时间:<?=human_time($item['f_hotspot_publish_time']);?>&nbsp;&nbsp;&nbsp;&nbsp;
        编辑时间:<?=human_time($item['f_hotspot_sumbit_time']);?>&nbsp;&nbsp;&nbsp;&nbsp;
        排序ID:<?=$item['f_hotspot_sort'];?>
	    <a href="<?=$item['f_hotspot_link']?>" target="_blank"><span class="pull-right text-muted"><?=$item['f_hotspot_link']?></span></a>

    </p>
  </div>
  <div id="collapse_news_<?=$item['f_hotspot_id']?>" class="panel-collapse collapse">
	  <div class="panel-footer text-center">
		  <a target="_blank" href="<?=site_url('gsk_news/edit_news?type=edit&news_id='.$item['f_hotspot_id'])?>" class="btn btn-primary" role="button">修 改</a>
		  <button type="button" data-loading-text="Loading..." value="<?=$item['f_hotspot_id']?>" class="btn btn-danger btn_news_del">删 除</button>
		  &nbsp;&nbsp;&nbsp;&nbsp;
		  <?php if($item['f_hotspot_status'] == 1):?>
			  <button type="button" data-loading-text="Loading..." value="<?=$item['f_hotspot_id']?>" class="btn btn-success btn_news_online">上 线</button>
		  <?php elseif($item['f_hotspot_status'] == 2):?>
			  <button type="button" data-loading-text="Loading..." value="<?=$item['f_hotspot_id']?>" class="btn btn-warning btn_news_offline">下 线</button>
			  <button type="button" data-loading-text="Loading..." value="<?=$item['f_hotspot_id']?>" class="btn btn-success btn_news_rec">首页推荐</button>
			  <button type="button" data-loading-text="Loading..." value="<?=$item['f_hotspot_id']?>" class="btn btn-success btn_news_related">相关阅读</button>
		  <?php elseif($item['f_hotspot_status'] == 3 || $item['f_hotspot_status'] == 4):?>
			  <button type="button" data-loading-text="Loading..." value="<?=$item['f_hotspot_id']?>" class="btn btn-warning btn_news_offline">下 线</button>
			  <button type="button" data-loading-text="Loading..." value="<?=$item['f_hotspot_id']?>" class="btn btn-warning btn_news_unrec">取消推荐</button>
		  <?php endif;?>
	  </div>
  <div class="panel-body">
    <?=$item['f_hotspot_content'];?>
  </div>

  </div>
</div>
<?php endforeach;?>
</div>


<div class="row">
<div class="col-md-12">
<?=pages($data['page']['cur_page'], $data['filter']);?>
</div>
</div>


