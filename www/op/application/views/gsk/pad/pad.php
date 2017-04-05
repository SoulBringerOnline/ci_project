<div class="page-header">
  <h1><span class="text-light-gray"><?=$title?> /<span> <?=$sub_title?> </h1>
  

   <span class="pull-right">
     <a target="_blank"  href="http://192.168.164.199:8082" class="btn btn-danger btn-labeled" ><span class="btn-label icon fa fa-plus"></span>创建</a>
   </span>  

</div> 


<?php 
// var_dump($data);
?>

<div class="row">
<div class="col-sm-12">
<ul class="nav nav-pills text-lg">
      <?php foreach ($data as $item) :?>
      <?php
        if( count( explode(":",$item['key']) ) == 2 ){
          list($pre, $key) = explode(":",$item['key']);
      ?>
        <li>
          <a  href="http://192.168.164.199:8082/p/<?=$key?>" target="_blank"><?=$key?></a>
        </li>
      <?php };?>
      <?php endforeach;?>
</ul>
</div>
</div>


