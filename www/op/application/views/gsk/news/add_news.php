<div class="page-header">
  <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div> 

<link href="<?=base_url('umeditor/themes/default/css/umeditor.css') ?>" rel="stylesheet" type="text/css">
<script src="<?=base_url('umeditor/umeditor.config.js') ?>"></script>
<script src="<?=base_url('umeditor/umeditor.min.js') ?>"></script>
<script src="<?=base_url('umeditor/lang/zh-cn/zh-cn.js') ?>"></script>


<script type="text/javascript">
  init.push(function () {
    $('body').toggleClass('mmc');

    $('#btn_news_add').click(function () {
      var btn = $(this);
      if($('#news_add_title').val().length <= 0)
      {
        ('请录入资讯标题')
        return;
      }
      if(UM.getEditor('news_add_content').getContent().length <= 0)
      {
        ('请录入资讯内容')
        return;
      }

      $.ajax({
          type:"POST",
          url:"<?=site_url('gsk_news/do_add_news');?>",
          data:{'news_content': UM.getEditor('news_add_content').getContent() , 'news_title': $('#news_add_title').val() , 'news_source': $('#news_add_source').val() },
          datatype: "json",
          beforeSend:function(){
            btn.button('loading');
          },
          success:function(data){
            console.log(data)
            btn.button('reset');  
            $('#panel_news_add').fadeOut('fast',function(){
              ('添加成功')
              window.close(); 
            });
          } ,
          error: function(){
            ('添加失败')
            btn.button('reset');   
            btn.html('添加失败')
          } 
       });
    });

  });
</script>
<!-- / Javascript -->


<div class="row" id="panel_news_add">
<div class="col-sm-12">

<div class="form-group">
  <label class="col-sm-1 control-label">标题</label>
  <div class="col-sm-11">
    <input type="text" class="form-control" id="news_add_title">
  </div>
</div> 

<div class="form-group">
  <label class="col-sm-1 control-label">来源</label>
  <div class="col-sm-11">
    <input type="text" class="form-control" id="news_add_source" placeholder="筑友">
  </div>
</div> 

<div class="form-group">
  <label class="col-sm-1 control-label">正文</label>
  <div class="col-sm-11">
    <script type="text/plain" id="news_add_content" style="width:100%;height:360px;"></script>
  </div>
</div> 

<div class="form-group">
  <label class="col-sm-1 control-label"></label>
  <div class="col-sm-11">
   <button type="submit" id="btn_news_add" class="btn btn-primary">确认发布</button>
  </div>
</div> 

</div>
</div>


<script type="text/javascript">
  var um = UM.getEditor('news_add_content');
</script>


