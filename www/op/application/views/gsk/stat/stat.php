<div class="page-header">
	<h1><span class="text-light-gray">渠道数据</h1>
</div> 

<script type="text/javascript">
  function morris_line(stats_dau, stats_dau_data , stats_dnu, stats_dnu_data, stats_hau, stats_hau_data , stats_hnu, stats_hnu_data){
    Morris.Line({
      element: stats_dau,
      data: stats_dau_data,
      xkey: 'day',
      ykeys: ['v'],
      labels: ['Value'],
      lineColors: ['#fff'],
      lineWidth: 2,
      pointSize: 2,
      gridLineColor: 'rgba(255,255,255,.5)',
      resize: true,
      hideHover:true,
      gridTextColor: '#fff',
      xLabels: "day",
      xLabelFormat: function(d) {
        return ['1','2','3','4','5','6','7','8','9','10','11', '12'][d.getMonth()] + '-' + d.getDate(); 
      },
    });

    Morris.Line({
      element: stats_dnu,
      data: stats_dnu_data,
      xkey: 'day',
      ykeys: ['v'],
      labels: ['Value'],
      lineColors: ['#fff'],
      lineWidth: 2,
      pointSize: 2,
      gridLineColor: 'rgba(255,255,255,.5)',
      resize: true,
      gridTextColor: '#fff',
      hideHover:true,
      xLabels: "day",
      xLabelFormat: function(d) {
        return ['1','2','3','4','5','6','7','8','9','10','11', '12'][d.getMonth()] + '-' + d.getDate(); 
      },
    });

    stats_hnu.pixelSparkline(
      stats_hnu_data, {
      type: 'line',
      width: '100%',
      height: '20px',
      fillColor: '',
      lineColor: '#fff',
      lineWidth: 2,
      spotColor: '#ffffff',
      minSpotColor: '#ffffff',
      maxSpotColor: '#ffffff',
      highlightSpotColor: '#ffffff',
      highlightLineColor: '#ffffff',
      spotRadius: 4,
      highlightLineColor: '#ffffff'
    });
    stats_hau.pixelSparkline(
      stats_hau_data, {
      type: 'line',
      width: '100%',
      height: '20px',
      fillColor: '',
      lineColor: '#fff',
      lineWidth: 2,
      spotColor: '#ffffff',
      minSpotColor: '#ffffff',
      maxSpotColor: '#ffffff',
      highlightSpotColor: '#ffffff',
      highlightLineColor: '#ffffff',
      spotRadius: 4,
      highlightLineColor: '#ffffff'
    });
    }
 

init.push(function () {

      <?php foreach ($channel as $item) :?>
      <?php
        if( !empty( $stats[$item['f_id']] )){
          $stat = $stats[$item['f_id']];
      ?>

      morris_line( 'stats_dau_<?=$item['f_id']?>' , <?=json_encode($stat['dau'])?>, 'stats_dnu_<?=$item['f_id']?>' , <?=json_encode($stat['dnu'])?>, 
      $('#stats_hau_<?=$item['f_id']?>') , <?=json_encode($stat['hau'])?>, $('#stats_hnu_<?=$item['f_id']?>') , <?=json_encode($stat['hnu'])?> );

      <?php };?>
      <?php endforeach;?>

});
  
</script>



<?php 
// $stat = $stats[11];
// var_dump($stat['ur']);
// var_dump($stat['ur3']);
// var_dump($stat['ur7']);
?>

<div class="panel">
  <div class="panel-heading">
    <span class="panel-title">DAU</span>
  </div>
  <div class="panel-body">
    <table class="table table-condensed">
      <thead>
      <?php $stat = $stats[1]; ?>
        <tr>
          <th colspan="2">#</th>
          <?php foreach (array_reverse($stat['dau']) as $dau) :?>
              <td><?=$dau['day'];?></td>
          <?php endforeach;?>
        </tr> 
      </thead>
      <tbody>
      <?php foreach ($channel as $item) :?>
      <?php
        if( !empty( $stats[$item['f_id']] )){
          $stat = $stats[$item['f_id']];
      ?>
        <tr>
          <td><?=$item['f_id']?></td>
          <td><?=$item['f_name']?></td>
          <?php foreach (array_reverse($stat['dau']) as $dau) :?>
              <td><?=intval($dau['v']);?></td>
          <?php endforeach;?>
        </tr> 
      <?php };?>
      <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>

<div class="panel">
  <div class="panel-heading">
    <span class="panel-title">DNU</span>
  </div>
  <div class="panel-body">
    <table class="table table-condensed">
      <thead>
      <?php $stat = $stats[1]; ?>
        <tr>
          <th colspan="2">#</th>
          <?php foreach (array_reverse($stat['dau']) as $dau) :?>
              <td><?=$dau['day'];?></td>
          <?php endforeach;?>
        </tr> 
      </thead>
      <tbody>
      <?php foreach ($channel as $item) :?>
      <?php
        if( !empty( $stats[$item['f_id']] )){
          $stat = $stats[$item['f_id']];
      ?>
        <tr>
          <td><?=$item['f_id']?></td>
          <td><?=$item['f_name']?></td>
          <?php foreach (array_reverse($stat['dnu']) as $dnu) :?>
              <td><?=intval($dnu['v']);?></td>
          <?php endforeach;?>
        </tr> 
      <?php };?>
      <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>


<div class="panel">
  <div class="panel-heading">
    <span class="panel-title">次留</span>
  </div>
  <div class="panel-body">
    <table class="table table-condensed">
      <thead>
      <?php $stat = $stats[1]; ?>
        <tr>
          
          <?php foreach (array_reverse($stat['dau']) as $dau) :?>
              <td><?=$dau['day'];?></td>
          <?php endforeach;?>
        </tr> 
      </thead>
      <tbody>
    
        <tr>
          <?php foreach (array_reverse($stat['ur']) as $ur) :?>
              <td><?php
                list($n , $a) = explode('#', $ur);
                echo round(($n/$a)*100).'%'.'('.$n.'/'.$a.')';
              ?></td>
          <?php endforeach;?>
        </tr> 
      </tbody>
    </table>
  </div>
</div>


<div class="panel">
  <div class="panel-heading">
    <span class="panel-title"><i class="fa fa-dashboard"></i> </i>&nbsp;&nbsp;<strong>Dashboard</strong></span>
  </div>

<?php foreach ($channel as $item) :?>
<?php
  if( !empty( $stats[$item['f_id']] )){
    $stat = $stats[$item['f_id']];
?>
  <div class="panel-body">
    <div class="stat-panel">
      <div class="stat-cell col-xs-2 bordered no-border-r text-right">
        <i class="fa fa-trophy bg-icon bg-icon-left"></i>
        <span class="text-xs"><strong><?=$item['f_name']?></strong>(<?=$item['f_id']?>)</span><br>
        <span class="text-muted"><sub>用户量</sub></span>
        <span class="text-xlg"><strong><?=$stat['users_num']?></strong></span>
      </div> 

      <div class="stat-cell col-xs-5 bg-info no-padding valign-bottom">
          <span class="text-lg"><strong>DAU</strong></span>
          <div class="graph" id="stats_dau_<?=$item['f_id']?>" style="width: 100%;height: 120px;"></div>
          <hr>
          <span class="text-sm"><strong>HAU</strong></span>
          <div class="stats-sparklines" id="stats_hau_<?=$item['f_id']?>" style="width: 100%;height: 30px;"></div>
      </div>

      <div class="stat-cell col-xs-5 bg-success no-padding valign-bottom">
          <span class="text-lg"><strong>DNU</strong></span>
          <div class="graph" id="stats_dnu_<?=$item['f_id']?>" style="width: 100%;height: 120px;"></div>
          <hr>
          <span class="text-sm"><strong>HAU</strong></span>
          <div class="stats-sparklines" id="stats_hnu_<?=$item['f_id']?>" style="width: 100%;height: 30px;"></div>
      </div>
    </div>
  </div>
<?php };?>
<?php endforeach;?>

</div>


