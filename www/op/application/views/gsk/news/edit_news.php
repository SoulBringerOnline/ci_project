<div class="page-header">
  <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div>

<link rel="stylesheet" href="<?=base_url('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')?>"  type="text/css">
<script src="<?=base_url('bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')?>" type="text/javascript"></script>


<link rel="stylesheet" type="text/css" href="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.css')?>"></link>
<script src="<?=base_url('bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all.js')?>"></script>




<?php 
  $news = $data;
  if( is_null($news) )
  {
    $news = array();
    $news['f_hotspot_comment_count'] = rand(100 , 999);
    $news['f_hotspot_collection_count'] = rand(100 , 999);
    $news['f_hotspot_view_count'] =  rand( 2000, 5000);
    $news['f_hotspot_good_count'] =  rand(1000 , 2000);
    $news['f_hotspot_source_site'] = '筑友';
  }
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
      if($('#news_content').val().length <= 0)
      {
        ('请录入资讯内容')
        return;
      }
      if($('#news_img').val().length <= 0)
      {
        ('请录入资讯小图')
        return;
      }

      $.ajax({
          type:"POST",
          url:"<?=site_url('gsk_news/do_edit_news');?>",
          data:{'type' : '<?=$type?>' , 'news_id':'<?=$news["f_hotspot_id"]?>' , 
          'news_title': $('#news_title').val() ,
	      'news_subtitle':$('#news_subtitle').val() ,
          'news_img': $('#news_img').val() ,
          'news_big_img': $('#news_big_img').val() , 
          'news_comment_count': $('#news_comment_count').val() , 
          'news_collection_count': $('#news_collection_count').val() , 
          'news_view_count': $('#news_view_count').val() , 
          'news_good_count': $('#news_good_count').val() , 
          'news_content': $('#news_content').val(), 
          'news_sort': $('#news_sort').val(),
          'news_sumbit_time': $('#news_sumbit_time').val(),
	      'news_timingsubmit': document.getElementById('news_timingsubmit').checked ? 1 : 0,
          'news_source': $('#news_source').val(),
          'news_classify': $('#news_classify').val(),
          'news_hotspot_link': $('#news_hotspot_link').val()
          },
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
    <input type="text" maxlength="13" class="form-control" id="news_title" value="<?=$news['f_hotspot_title']?>">
  </div>
</div>

<div class="form-group">
	<label class="col-sm-1 control-label">副标题</label>
	<div class="col-sm-11">
		<input type="text" class="form-control" id="news_subtitle" maxlength="26" value="<?= empty($news['f_hotspot_subtitle'])?$news['f_hotspot_title']:$news['f_hotspot_subtitle']?>">
	</div>
</div>

<div class="form-group">
  <div class="col-sm-1"></div>
  <div class="col-sm-2">
    <div class="input-group">
      <span class="input-group-addon" id="news-addon1">来源</span>
      <input type="text" class="form-control" id="news_source" placeholder="筑友" value="<?=$news['f_hotspot_source_site']?>" aria-describedby="news-addon1">
    </div>
  </div>

  <div class="col-sm-2">
    <div class="input-group">
      <span class="input-group-addon" id="news-addon6">排序</span>
      <input type="text" class="form-control" id="news_sort" value="<?=$news['f_hotspot_sort']?>" aria-describedby="news-addon6">
    </div>
  </div>
	<div class="col-sm-2">
		<div class="input-group">
			<span class="input-group-addon" id="news-addon10">id</span>
			<input type="text" class="form-control" id="news_sort" value="<?=$news["f_hotspot_id"]?>" aria-describedby="news-addon10">
		</div>
	</div>
  <div class="col-sm-2">
			<div class="input-group">
				<span class="input-group-addon" >分类</span>
				<select class="form-control form-group-margin" id="news_classify" style="margin-bottom: 0px!important;">
					<option value="1" <?php if($news['f_hotspot_classify'] == 1){echo 'selected="selected"';} ?>>前沿</option>
					<option value="2" <?php if($news['f_hotspot_classify'] == 2){echo 'selected="selected"';} ?>>经验</option>
					<option value="3" <?php if($news['f_hotspot_classify'] == 3){echo 'selected="selected"';} ?>>新闻</option>
					<option value="4" <?php if($news['f_hotspot_classify'] == 4){echo 'selected="selected"';} ?>>官方</option>
					<option value="5" <?php if($news['f_hotspot_classify'] == 5){echo 'selected="selected"';} ?>>考试</option>
					<option value="6" <?php if($news['f_hotspot_classify'] == 6){echo 'selected="selected"';} ?>>杂谈</option>
					<option value="7" <?php if($news['f_hotspot_classify'] == 7){echo 'selected="selected"';} ?>>BIM</option>
					<option value="8" <?php if($news['f_hotspot_classify'] == 8){echo 'selected="selected"';} ?>>推广</option>
				</select>
			</div>
		</div>
<div class="col-sm-2">
	<div class="input-group">
		<span class="input-group-addon" id="news-addon7">发布时间</span>
     <div class="input-append date form_datetime pull-right"  data-date="<?= empty($news['f_hotspot_sumbit_time']) ? date("Y-m-d H:i:s"): human_time($news['f_hotspot_sumbit_time']);?>">
			<input size="16" id="news_sumbit_time" type="text" style="height: 30px; width: 200px;" value="<?= empty($news['f_hotspot_sumbit_time']) ? date("Y-m-d H:i:s"): human_time($news['f_hotspot_sumbit_time']);?>" readonly style="height: 30px; width: 250px">
			<span class="add-on" style="height: 30px"><i class="icon-remove"></i></span>
			<span class="add-on" style="height: 30px"><i class="icon-th"></i></span>
		</div>
			<script type="text/javascript">
				$(".form_datetime").datetimepicker({
					format: "yyyy-mm-dd hh:ii:ss",
					language: 'zh-CN',
			//			  showMeridian: true,
					autoclose: true,
					todayBtn: true
				});
			</script>
	</div>
</div>
<div class="col-sm-2" style="height: 30px">
	<div class="input-group" >
		<span class="input-group-addon" id="news-addon8" style="line-height:16px">是否定时发布</span>
		<input type="checkbox" class="form-control" id="news_timingsubmit" style="height:15px; width:15px;margin-top:7px" aria-describedby="news-addon8">
	</div>
</div>
</div> 

<div class="form-group">
  <div class="col-sm-1"></div>

  <div class="col-sm-2">
    <div class="input-group">
      <span class="input-group-addon" id="news-addon2">评论数</span>
      <input type="text" class="form-control" id="news_comment_count" value="<?=$news['f_hotspot_comment_count']?>" aria-describedby="news-addon2">
    </div>
  </div>
  <div class="col-sm-2">
    <div class="input-group">
      <span class="input-group-addon" id="news-addon3">收藏数</span>
      <input type="text" class="form-control" id="news_collection_count" value="<?=$news['f_hotspot_collection_count']?>" aria-describedby="news-addon3">
    </div>
  </div>
  <div class="col-sm-2">
    <div class="input-group">
      <span class="input-group-addon" id="news-addon4">浏览量</span>
      <input type="text" class="form-control" id="news_view_count" value="<?=$news['f_hotspot_view_count']?>" aria-describedby="news-addon4">
    </div>
  </div>
  <div class="col-sm-2">
    <div class="input-group">
      <span class="input-group-addon" id="news-addon5">点赞数</span>
      <input type="text" class="form-control" id="news_good_count" value="<?=$news['f_hotspot_good_count']?>" aria-describedby="news-addon5">
    </div>
  </div>
	<div class="col-sm-2">
    <div class="input-group">
      <span class="input-group-addon">信息来源</span>
      <input type="text" class="form-control" id="news_hotspot_link" value="<?=$news['f_hotspot_link']?>" style="width: 300px">
    </div>
  </div>



  </div>
</div> 
<div class="form-group">
<hr>
</div>
<?php
  $news_img = $news["f_hotspot_first_image"] ;
  if( empty($news_img) )
  {
    $news_img = get_random_news_img($news["f_hotspot_id"]);
  }

  $news_big_img = $news["f_hotspot_big_image"] ;
  if( empty($news_big_img) )
  {
    $news_big_img = get_random_news_img($news["f_hotspot_id"]);
  }
?>
<div class="form-group">
  <label class="col-sm-1 control-label">资讯小图</label>
  <div class="col-sm-4">
  <span class="pull-left">
    <img src="<?=$news_img?>"  class="img-thumbnail">
  </span>
  <span >
    <input type="text" class="form-control" id="news_img" value="<?=$news_img?>" style="width:80%;vertical-align:baseline;">
  </span>
  </div>

  <label class="col-sm-1 control-label">资讯海报</label>
  <div class="col-sm-4">
  <span class="pull-left">
    <img src="<?=$news_big_img?>"  class="img-thumbnail">
  </span>
  <span >
    <input type="text" class="form-control" id="news_big_img" value="<?=$news_big_img?>" style="width:80%;vertical-align:baseline;">
  </span>
  </div>
</div> 

<div class="form-group">
<hr>
</div>

<div class="form-group">
  <label class="col-sm-1 control-label">正文</label>
  <div class="col-sm-11">
      <textarea class="textarea" id="news_content" style="width: 100%; height: 600px; "><?=$news['f_hotspot_content']?></textarea>
      <script type="text/javascript">
        $('#news_content').wysihtml5();
      </script>
  </div>
</div> 

<div class="form-group">
  <label class="col-sm-1 control-label"></label>
  <div class="col-sm-11">
  <?php
    $btn_title = '添加咨询';
    if( $type == 'edit' )
    {
      $btn_title = '确认编辑';
    }
  ?>
    <button type="submit" id="btn_news_edit" class="btn btn-danger"><?=$btn_title;?></button>
  </div>
</div> 

</div>
</div>







