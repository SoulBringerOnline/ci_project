<div class="page-header">
  <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
</div> <!-- / .page-header -->


<div class="row">
<div class="col-md-12"> 
<?php foreach ($channel as $item) : ?>
<?php if( $item['f_os'] != 2 ) : ?>
<div class="panel colourable col-md-3">
    <div class="panel-heading  text-center">
    <a target="_blank" href="http://gxtmobile.glodon.com:8888/clientfiles/gsk/gsk.html?c=<?=$item['f_id']?>">
        <span style="color: #f00;text-shadow: 1px 1px 0px #212121;"><?=$item['f_id']?></span> <span class="panel-title"> <?=$item['f_name']?></span>
    </a>
    </div> 
                    
    <div class="panel-body text-center">
        <img src="http://img.zy.glodon.com/qrcode/gsk_qrcode_<?=$item['f_id'];?>.png" style="width:140px">
    </div>

    <div class="panel-footer text-center">
        <a target="_blank" href="http://gxtmobile.glodon.com:8888/clientfiles/gsk/gsk.html?c=<?=$item['f_id']?>">
        <?php if($item['f_os'] == 0 || $item['f_os'] == 2) :?>
        <i class="fa fa-apple"></i> 
        <span class="text-muted"><?=$item['f_ios_version'] ?></span>
        <?php endif;?>

        <?php if($item['f_os'] == 0 || $item['f_os'] == 3) :?>
        <i class="fa fa-android"></i> 
        <span class="text-muted"><?=$item['f_android_version'] ?></span>
        <?php endif;?>
        </a>
    </div>
</div>
<?php endif;?>
<?php endforeach;?>
</div>
</div>
