<div class="page-header">
  <h1 style="width:100%;">
  	<span class="text-light-gray"  style="width:100%;">
  		<?=$title?> /
  		<span class="input-group-btn pull-right" style="margin-right:45pt;">
	      <a href="<?php echo site_url('gsk_msg/msg_add');?>"><button class="btn">添加消息</button></a>
	    </span>
  	<span>
  </h1>
</div> 

<?php
  // function gsk_log_page($cur_page){
  //   echo '<ul class="pagination pagination-sm">';
  //   foreach (range(1, 20) as $p){
  //     if($cur_page == $p){echo '<li class="active">';}else{echo '<li>';}
  //     if( isset( $_REQUEST['query'] ) )
  //     {
  //       echo '<a href="' . current_url() . '?p=' . $p . '&query=' .  urlencode($_REQUEST['query']) . '">';
  //     }
  //     else
  //     {
  //       echo '<a href="' . current_url() . '?p=' . $p . '">';
  //     }
  //     echo $p ;
  //     echo '</a>';
  //     echo '</li>';
  //   }
  //   echo '</ul>';
  // }

  $j = new Json_pretty('&nbsp;&nbsp;&nbsp;&nbsp;', "<br>");
?>

<div class="row">
<div class="col-md-12" style="margin-bottom:16px;">
<form action="<?php echo site_url('gsk_msg/msg_list');?>" class="bg-primary">
  <div class="input-group input-group-lg">
    <span class="input-group-addon no-background"><i class="fa fa-search"></i></span>
    <input type="text" name="query" value="<?=$_REQUEST['query']?>" class="form-control" placeholder="搜索技巧( baseid=answer_event_old type=12 )">
    <span class="input-group-btn">
      <button class="btn" type="submit">搜索</button>
    </span>
  </div> 
</form>
</div>
</div>

<div class="panel-group" id="accordion_gsk_log">
<?php foreach ($data['data'] as $item): ?>
<div class="panel">
  <div class="panel-heading">
    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_gsk_log" href="#collapse_gsk_log_<?=$item['_id']?>">
        
      <span class="label label-<?=get_random_state($item['f_type'])?>">
        <?php
        switch ($item['f_type']) {
          case 1:
            echo '文本';
            break;
          case 12:
            echo 'Card';
            break;
          default:
            echo '未知';
            break;
        }
        ?>
      </span>

      <span class="text-<?=get_random_state(1);?>">
        <kbd><?=$item['f_msgflag'];?></kbd>  <?=$item['f_baseid']?>  <?=$item['f_uin']?>  <?=$item['f_ip']?>  
      </span>
     
      
      <span class="pull-right"><?=date("Y-m-d H:i:s", $item['f_begintime']);?>--<?=date("Y-m-d H:i:s", $item['f_finishtime']);?></span>  
    </a>

    <p style="padding:0px 35px 0px 20px;" class="text-muted">
      <?=$item['f_msginfo'];?>
    </p>
    <a href="<?php echo site_url('gsk_msg/msg_send');?>?baseid=<?=$item['f_baseid']?>">
    <span style="margin:auto 4px 5px auto; cursor: pointer;" class="pull-right label label-success">
    	发送
    </span>
    </a>
    <a href="<?php echo site_url('gsk_msg/msg_edit');?>?baseid=<?=$item['f_baseid']?>">
    <span style="margin:auto 4px 5px auto; cursor: pointer;" class="pull-right label label-primary">
    	修改
    </span>
    </a>
    <span style="margin:auto 8px 5px auto; cursor: pointer;" class="pull-right label label-danger" onclick="del('<?=$item['f_baseid']?>')">
    	删除
    </span>
    
  </div>
  <div id="collapse_gsk_log_<?=$item['_id']?>" class="panel-collapse collapse" style="height: 0px;">
  <div class="panel-body">
    <span style="width: 60000px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">
      <?php
          echo $j->format( $item, true);
      ?> 
    </span>
  </div>
  <div class="panel-footer text-center">
      

  </div>
  </div>
</div>
<?php endforeach;?>
</div>

<div class="row">
<div class="col-md-12">
<?=pages($data['page']['cur_page'], array('query'=>$_REQUEST['query']));?>
</div>
</div>
<script>
function del(id) {
	if (!confirm("確定刪除該數據？")) {
		return false;
	}
	$.get("<?php echo site_url('gsk_msg/msg_del');?>"+"?id="+id, function(json){
		json = JSON.parse(json);
		if (json.state) {
			("删除成功");
			location.href = location.href;
		}
	});
}
</script>
