<div class="page-header">
  <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div> 

<script type="text/javascript">
  init.push(function () {
    $('.btn_spider_news_to_op').click(function () {
      var btn = $(this);
      var panel = $('#panel_spider_news_' + btn.val())
      $.ajax({
          type:"POST",
          url:"<?=site_url('gsk_news/do_store_news');?>",
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
            btn.html('入库失败')
          }         
       });
    });
  });
</script>

<div class="row">
<div class="col-md-12">
<?=pages($data['page']['cur_page']);?>
</div>
</div>


<div class="panel-group" id="accordion_spider_news">
<?php foreach ($data['data'] as $item): ?>
<div class="panel" id="panel_spider_news_<?=$item['_id']?>">
  <div class="panel-heading">
    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_spider_news" href="#collapse_spider_news_<?=$item['_id']?>">
      <?=$item['title']?>
      <span class="pull-right label label-<?=get_random_state($item['target'])?>"><?=$item['target'];?></span>  
      <span class="pull-right label label-<?=get_random_state($item['source'])?>"><?=$item['source'];?></span>
    </a>
    <p class="text-muted" style="padding:0px 35px 0px 20px;">
        原文时间:<?=$item['publishTime'];?>&nbsp;&nbsp;&nbsp;&nbsp;
        抓取时间:<?=$item['snatchTime'];?>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="<?=$item['link']?>" target="_blank"><span class="pull-right text-muted"><?=$item['link']?></span></a>
    </p>
  </div>
  <div id="collapse_spider_news_<?=$item['_id']?>" class="panel-collapse collapse" style="height: 0px;">
  <div class="panel-body">
<!-- <?=$item['_id']?> -->
    <?=$item['content'];?>
  </div>
  <div class="panel-footer text-center">
      <button type="button" data-loading-text="Loading..." value="<?=$item['_id']?>" class="btn btn-danger btn_spider_news_to_op">入 库</button>
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
