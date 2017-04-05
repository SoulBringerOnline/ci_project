<div class="page-header">
  <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
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
<form action="<?php echo site_url('gsk_op/log');?>" class="bg-primary">
  <div class="input-group input-group-lg">
    <span class="input-group-addon no-background"><i class="fa fa-search"></i></span>
    <input type="text" name="query" value="<?=$_REQUEST['query']?>" class="form-control" placeholder="搜索技巧( cmd=获取用户资料 name=kk666666 phone=13401031602 uin=85288528 dye=123456 ip=10.232.42.78 )">
    <span class="input-group-btn">
      <button class="btn" type="submit">搜索</button>
    </span>
  </div> 
</form>
</div>
</div>

<div class="panel-group" id="accordion_gsk_log">
<?php foreach ($data['data'] as $item): 
  if($item['f_msg_type']==2){continue;}
?>

<div class="panel">
  <div class="panel-heading">
    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_gsk_log" href="#collapse_gsk_log_<?=$item['_id']?>">
        
      <span class="label label-<?=get_random_state($item['f_msg_type'])?>">
        <?php
        switch ($item['f_msg_type']) {
          case 1:
            echo '请求';
            break;
          case 2:
            echo '响应';
            break;
          case 3:
            echo 'PUSH';
            break;
          default:
            echo '未知';
            break;
        }
        ?>
      </span>

      <span class="text-<?=get_random_state($item['f_uin']);?>">
        <strong><?=$item['f_name']?></strong>   <kbd><?=$item['f_cmd_info'];?></kbd>  <?=$item['f_phone']?>  <?=$item['f_uin']?>  <?=$item['f_ip']?>  
      </span>
     
      
      <span class="pull-right label label-<?=get_random_state($item['f_dye'])?>"><?=$item['f_dye'];?></span>  
    </a>

      <p style="padding:0px 35px 0px 20px;" class="text-muted">
      <?php 
        switch($item['f_client_id'])
        {
          case 2:
          echo '<span class="text-info"><i class="fa fa-apple" ></i> </span>' ;
          break;
          case 3:
          echo '<span class="text-success"><i class="fa fa-android" ></i> </span>' ;
          break;
        }
      ?>
      <?=$item['f_phone_info'];?>  <?=$item['f_phone_os'];?>   
      <?=$item['f_phone_sp'];?>
      <?=$item['f_phone_network'];?>
      <?php if(strlen($item['f_client_version'])):?>
        客户端版本(<?=$item['f_client_version'];?>)
      <?php endif;?>
      <?php if(strlen($item['f_client_channel'])):?>
        渠道(<?=$item['f_client_channel'];?>)
      <?php endif;?>
      <?=human_time($item['f_time'] - 28800 );?>
      <?=$item['f_ip'];?>
    </p>
  </div>
  <div id="collapse_gsk_log_<?=$item['_id']?>" class="panel-collapse collapse" style="height: 0px;">
  <div class="panel-body">
    <span style="width: 60000px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">
      <?php
        if( $item['f_cmd'] != 259 && strlen($item['f_log']) ) { 
          echo $j->format( $item['f_log'], true); 
        }
        $key = $item['f_uin'] . '_' . $item['f_time'] . '_' . $item['f_cmd'];
        if(isset($map[$key])){
          echo '<hr>';
          echo $j->format( $data['data'][$map[$key][2]]['f_log'], true); 
        }

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
