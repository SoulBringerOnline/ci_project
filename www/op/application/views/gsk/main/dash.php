<?php
  $link_list = array();


  $link_list[0] = array( 
    'name' => '快捷入口',
    'icon' => 'fa fa-database',
    'sub_menu' => array(
    array('url' => 'http://192.168.164.199/op/db' , 'icon' => 'fa fa-database',   'name' => 'MySQL' ),
    array('url' => 'http://192.168.164.199/op/mongo/index.php' , 'icon' => 'fa fa-cube', 'name' => 'MongoDB'  ),
    array('url' => '&'),
    array('url' => 'http://192.168.164.200' , 'icon' => 'fa fa-github-alt', 'name' => 'GIT' ),
    array('url' => 'http://192.168.67.41:8080' , 'icon' => 'fa fa-bitcoin', 'name' => 'JENKINS'  ),
    array('url' => 'http://pm.glodon.com/jira/browse/JSKFB/?selectedTab=com.atlassian.jira.jira-projects-plugin:issues-panel' , 'icon' => 'fa fa-bug', 'name' => 'JIRA'  ),
    array('url' => 'http://192.168.164.199/doc' , 'icon' => 'fa fa-wikipedia-w', 'name' => '后台文档'  ),
    array('url' => '#'),
    array('url' => site_url('gsk_op/log') ,  'icon' => 'fa fa-file-text-o', 'name' => '日志系统' ),
    array('url' => 'http://192.168.164.199/op/sqm/index.php' ,  'icon' => 'fa fa-area-chart','name' => '服务质量'  ),
    array('url' => 'http://192.168.164.199/op/moni/index.php' , 'icon' => 'fa fa-line-chart','name' => '监控系统'  ),
    array('url' => 'http://192.168.164.199:8081/screen/954?start=-86400&cols=4&legend=on' , 'icon' => 'fa fa-line-chart','name' => '系统监控'  ),
    array('url' => '&'),
    array('url' => 'http://192.168.164.200:8007' , 'icon' => 'fa fa-gg',   'name' => 'API测试' ),
    array('url' => 'http://192.168.164.199/op/test' , 'icon' => 'fa fa-comment', 'name' => '消息测试'  ),
    array('url' => '&'),
    array('url' => 'http://192.168.77.87/' , 'icon' => 'fa fa-file',   'name' => '需求文档' ),
    array('url' => '&'),
    array('url' => site_url('gsk_pub/down_pkg')  , 'icon' => 'fa fa-file',   'name' => '安装包下载' ),
    array('url' => 'http://192.168.164.199/wiki/index.php' , 'icon' => 'fa fa-wikipedia-w', 'name' => 'WIKI'  ),
    )
  );
?>

<div class="page-header">
	<h1><span class="text-light-gray">Dashboard</h1>
</div> 


<script type="text/javascript">
          init.push(function () {
            Morris.Line({
              element: 'stats-dau',
              data: <?=json_encode($stats['dau'])?>,
              xkey: 'day',
              ykeys: ['v','v2'],
              labels: ['当前',"30天前"],
              lineColors:['#fff','#993'],
	            lineWidth: [2,1],
	            pointSize: [2,1],
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
              element: 'stats-dnu',
              data: <?=json_encode($stats['dnu'])?>,
              xkey: 'day',
	            ykeys: ['v','v2'],
	            labels: ['当前',"30天前"],
              lineColors: ['#fff','#993'],
              lineWidth: [2,1],
              pointSize: [2,1],
              gridLineColor: 'rgba(255,255,255,.5)',
              resize: true,
              gridTextColor: '#fff',
              hideHover:true,
              xLabels: "day",
              xLabelFormat: function(d) {
                return ['1','2','3','4','5','6','7','8','9','10','11', '12'][d.getMonth()] + '-' + d.getDate();
              },
            });
	          //console.log(<?=json_encode($stats['hau'])?>);
	          //console.log(<?=json_encode($stats['hnu'])?>);
	          Morris.Line({
		          element: 'stats-hau',
		          data: <?=json_encode($stats['hau'])?>,
		          xkey: 't_time',
		          ykeys: ['v','v2','v3'],
		          labels:['今天','昨天','一周前'],
		          lineColors: ['#fff','#edc240','#838B8B'],
		          lineWidth: [3,1,1],
		          pointSize: [2,1,1],
		          gridLineColor: 'rgba(255,255,255,.5)',
		          resize: true,
		          hoverOpacity:"0.7",
		          hideHover:true,
		          gridTextColor: '#fff',
		          xLabels: "s_time",
		          xLabelFormat: function(d) {
			         // return "1";
			          return ['0','1','2','3','4','5','6','7','8','9','10','11', '12','13','14','15','16','17','18','19','20','21','22','23'][d.getHours()] ;
		          },
	          });



	          Morris.Line({
		          element: 'stats-hnu',
		          data: <?=json_encode($stats['hnu'])?>,
		          xkey: 't_time',
		          ykeys: ['v','v2','v3'],
		          labels:['今天','昨天','一周前'],
		          lineColors: ['#fff','#edc240','#838B8B'],
		          lineWidth: [3,1,1],
		          pointSize: [2,1,1],
		          gridLineColor: 'rgba(255,255,255,.5)',
		          resize: true,
		          gridTextColor: '#fff',
		          hideHover:true,
		          xLabels: "s_time",
		          xLabelFormat: function(d) {
			          //return "1";
			          console.log(d);
			          return ['0','1','2','3','4','5','6','7','8','9','10','11', '12','13','14','15','16','17','18','19','20','21','22','23'][d.getHours()];
		          }
	          });




            $("#stats-h5nu").pixelSparkline(
              <?=json_encode($stats['h5nu_prev'])?>, {
              type: 'line',
              width: '100%',
              height: '100px',
              fillColor: '',
              lineColor: '#993',
              lineWidth: 1,
              spotColor: '#838B8B',
              minSpotColor: '#838B8B',
              maxSpotColor: '#838B8B',
              highlightSpotColor: '#838B8B',
              highlightLineColor: '#838B8B',
              spotRadius: 4,
              highlightLineColor: '#838B8B'
            });
	          $("#stats-h5nu").pixelSparkline(
		          <?=json_encode($stats['h5nu'])?>, {
			          type: 'line',
			          width: '100%',
			          height: '100px',
			          fillColor: '',
			          lineColor: '#fff',
			          lineWidth: 2,
			          spotColor: '#ffffff',
			          minSpotColor: '#ffffff',
			          maxSpotColor: '#ffffff',
			          highlightSpotColor: '#ffffff',
			          highlightLineColor: '#ffffff',
			          spotRadius: 4,
			          composite:true,
			          highlightLineColor: '#ffffff'
		          });
            $("#stats-h5au").pixelSparkline(
              <?=json_encode($stats['h5au_prev'])?>, {
              type: 'line',
              width: '100%',
              height: '100px',
              fillColor: '',
              lineColor: '#993',
              lineWidth: 1,
              spotColor: '#edc240',
              minSpotColor: '#edc240',
              maxSpotColor: '#edc240',
              highlightSpotColor: '#edc240',
              highlightLineColor: '#edc240',
              spotRadius: 4,

              highlightLineColor: '#edc240'
            });
	          $("#stats-h5au").pixelSparkline(
		          <?=json_encode($stats['h5au'])?>, {
			          type: 'line',
			          width: '100%',
			          height: '100px',
			          fillColor: '',
			          lineColor: '#fff',
			          lineWidth: 2,
			          spotColor: '#ffffff',
			          minSpotColor: '#ffffff',
			          maxSpotColor: '#ffffff',
			          highlightSpotColor: '#ffffff',
			          highlightLineColor: '#ffffff',
			          spotRadius: 4,
			          composite:true,
			          highlightLineColor: '#ffffff'
		          });



          });
</script>

<div class="panel">
  <div class="panel-heading">
    <span class="panel-title"><i class="fa fa-dashboard"></i> </i>&nbsp;&nbsp;<strong>Dashboard</strong></span>
  </div>
  <div class="panel-body">
    <div class="stat-panel">
      <div class="stat-cell col-xs-2 bordered no-border-r text-right">
        <i class="fa fa-trophy bg-icon bg-icon-left"></i>
        <span class="text-xs">总用户量</span><br>
        <span class="text-xlg"><strong><?=$stats['users_num']?></strong></span>
      </div> 

      <div class="stat-cell col-xs-5 bg-info no-padding valign-bottom">
          <span class="text-lg"><strong>DAU日活</strong></span>
          <div class="graph" id="stats-dau" style="width: 100%;height: 140px;"></div>
	      <hr>
	      <span class="text-lg"><strong>HAU新增/小时</strong></span>
	      <div class="graph" id="stats-hau" style="width: 100%;height: 140px;"></div>
	      <hr>
	      <span class="text-sm"><strong>HAU活跃/实时(5分钟)</strong></span>
	      <div class="graph" id="stats-h5au" style="width: 100%;height: 120px;"></div>
	      <!--div class="stats-sparklines" id="stats-h5au" style="width: 100%;height: 100px;"></div-->
      </div>

      <div class="stat-cell col-xs-5 bg-success no-padding valign-bottom">
          <span class="text-lg"><strong>DNU日增</strong></span>
          <div class="graph" id="stats-dnu" style="width: 100%;height: 140px;"></div>
	      <hr>
	      <span class="text-lg"><strong>HNU新增/小时</strong></span>
	      <div class="graph" id="stats-hnu" style="width: 100%;height: 140px;"></div>
	      <hr>
	      <span class="text-sm"><strong>HNU活跃/实时(5分钟)</strong></span>
	      <div class="graph" id="stats-h5nu" style="width: 100%;height: 120px;"></div>
	      <!--div class="stats-sparklines" id="stats-h5nu" style="width: 100%;height: 100px;"></div-->
      </div>
    </div>
  </div>
</div>

<?php $index = 2; ?>
<?php foreach ($link_list as $item):?>
<div class="panel">
	<div class="panel-heading">
		<span class="panel-title"><i class="<?=$item['icon']?>"></i> </i>&nbsp;&nbsp;<strong><?=$item['name']?></strong></span>
	</div>
	<div class="panel-body buttons-with-margins">
		<?php foreach ($item['sub_menu'] as $menu):?>
      
      <?php if( $menu['url'] == '&' || $menu['url'] == '#' ): ?>
        <?php if( $menu['url'] == '#' ): ?>
        <hr>
        <?php endif; ?>
        <?php $index++;?>
      <?php else: ?>
        <a href="<?=$menu['url']?>" target="_blank" class="btn btn-flat  btn-<?=get_random_state($index);?>"><i class="<?=$menu['icon']?>"></i>  <?=$menu['name'];?></a>
      <?php endif; ?>
		<?php endforeach;?>
	</div>
</div>
<?php endforeach;?>


<?php if($this->session->user_role == 'admin'): ?>
<div class="panel">
  <div class="panel-heading">
    <span class="panel-title"><i class="fa fa-users"></i> </i>&nbsp;&nbsp;<strong>在线用户</strong></span>
    <span class="text-danger" id="dash_stat_ol_title"></span>
  </div>
  <div class="panel-body" id="dash_stat_ol_users">

  </div>
</div>

<script type="text/javascript">
function update_ol_user() {
  $.ajax({
      type: "get",
      dataType: 'json',
      url: 'http://192.168.164.199/gsk/api/stat.php?t=ol' ,
      timeout:800,
      success: function(data, textStatus){
          if( textStatus == 'success' )
          {
            var myDate = new Date();
            var tbody = '<p class="text-danger">' + myDate.getHours() + "点" + myDate.getMinutes() + "分" + myDate.getSeconds() + "秒" + "</p>"; 
            $.each(data, function (n, v) {  
                tbody += '<p class="text-success">' + v + "</p>";  
            });  
            
            $("#dash_stat_ol_users").html( tbody )
          }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
      },
  });
  setTimeout(update_ol_user, 2000);
}
update_ol_user()
</script>
<?endif;?>
