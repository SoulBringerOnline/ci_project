<div class="page-header">
  <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div> 

<link href="<?=base_url('umeditor/themes/default/css/umeditor.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('umeditor/umeditor.config.js') ?>"></script>
<script src="<?=base_url('umeditor/umeditor.min.js') ?>"></script>
<script src="<?=base_url('umeditor/lang/zh-cn/zh-cn.js') ?>"></script>

<?php 
  $news = $data;
?>

<script type="text/javascript">
  init.push(function () {
    $('body').toggleClass('mmc');

    $('#btn_news_edit').click(function () {
      var btn = $(this);

      if($('#news_title').val().length <= 0)
      {
        ('请录入资讯标题')
        return;
      }
      if(UM.getEditor('news_content').getContent().length <= 0)
      {
        ('请录入资讯内容')
        return;
      }

      $.ajax({
          type:"POST",
          url:"<?=site_url('gsk_news/do_edit_news');?>",
          data:{'type' : '<?=$type?>' , 'news_id':'<?=$news["_id"]?>' , 'news_content': UM.getEditor('news_content').getContent() , 'news_title': $('#news_title').val() ,'news_source': $('#news_source').val() },
          datatype: "json",
          beforeSend:function(){
            btn.button('loading');
          },
          success:function(data){
            btn.button('reset');  
            $('#panel_news_edit').fadeOut('fast',function(){
              ('成功')
              window.close(); 
            });

          } ,
          error: function(){
            btn.button('reset');   
            btn.html('失败')
          }         
       });
    });

  });
</script>
<!-- / Javascript -->

<div class="row" id="panel_news_edit">
<div class="col-sm-12">

<div class="form-group">
  <label class="col-sm-1 control-label">标题</label>
  <div class="col-sm-11">
    <input type="text" class="form-control" id="news_title" value="<?=$news['title']?>">
  </div>
</div> 

<div class="form-group">
  <label class="col-sm-1 control-label">来源</label>
  <div class="col-sm-11">
    <input type="text" class="form-control" id="news_source" placeholder="筑友" value="<?=$news['source']?>">
  </div>
</div> 

<div class="form-group">
  <label class="col-sm-1 control-label">正文</label>
  <div class="col-sm-11">
    <script type="text/plain" id="news_content" style="width:100%;height:360px;"> <?=$news['content']?> </script>
  </div>
</div> 

<div class="form-group">
  <label class="col-sm-1 control-label"></label>
  <div class="col-sm-11">
    <button type="submit" id="btn_news_edit" class="btn btn-danger">确认编辑</button>
  </div>
</div> 

</div>
</div>


<script type="text/javascript">
  var um = UM.getEditor('news_content');
</script>





