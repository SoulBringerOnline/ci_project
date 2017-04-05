<div class="page-header">
  <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div> 

<script type="text/javascript">
  init.push(function () {
    $('.btn_ol_news_del').click(function () {
      var btn = $(this);
      var panel = $('#panel_ol_news_' + btn.val())
      $.ajax({
          type:"POST",
          url:"<?=site_url('gsk_news/do_del_ol_news');?>",
          data:{'id':btn.val()},
          datatype: "json",
          beforeSend:function(){
            btn.button('loading');
          },
          <?=$item['_id']?>
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

  });
</script>
<!-- / Javascript -->

<div class="row">
<div class="col-md-12">
<?=pages($data['page']['cur_page']);?>
</div>
</div>

<div class="panel-group" id="accordion_ol_news">
<?php foreach ($data['data'] as $item): ?>
<div class="panel" id="panel_ol_news_<?=$item['_id']?>">
  <div class="panel-heading">
    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_ol_news" href="#collapse_ol_news_<?=$item['_id']?>">
      <?=$item['f_hotspot_title']?>
      <span class="pull-right label label-<?=get_random_state($item['f_hotspot_source_site'])?>"><?=$item['f_hotspot_source_site'];?></span>
      <span class="pull-right label label-<?=get_random_state($item['f_hotspot_site'])?>"><?=$item['f_hotspot_site'];?></span>  
    </a>
    <p class="text-muted" style="padding:0px 35px 0px 20px;">
        <!-- 原文时间:<?=$item['publishTime'];?>&nbsp;&nbsp;&nbsp;&nbsp;
        抓取时间:<?=$item['snatchTime'];?>&nbsp;&nbsp;&nbsp;&nbsp;
        入库时间:<?=$item['diggTime'];?>&nbsp;&nbsp;&nbsp;&nbsp;
        编辑时间:<?=$item['editTime'];?>&nbsp;&nbsp;&nbsp;&nbsp;    -->
        <a href="<?=$item['f_hotspot_link']?>" target="_blank"><span class="pull-right text-muted"><?=$item['f_hotspot_link']?></span></a>
    </p>
  </div>
  <div id="collapse_ol_news_<?=$item['_id']?>" class="panel-collapse collapse" style="height: 0px;">
  <div class="panel-body">
    <?=$item['f_hotspot_content'];?>
  </div>
  <div class="panel-footer text-center">
    <button type="button" data-loading-text="Loading..." value="<?=$item['_id']?>" class="btn btn-danger btn_ol_news_del">删 除</button>
  </div>
  </div>
</div>
<?php endforeach;?>
</div>


<div class="row">
<div class="col-md-12">
<?=pages($data['page']['cur_page']);?>
</div>
</div>


