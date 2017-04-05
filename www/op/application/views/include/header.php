<!-- $MAIN_NAVIGATION -->
  <div id="main-navbar" class="navbar navbar-inverse" role="navigation">
    <!-- Main menu toggle -->
    <button type="button" id="main-menu-toggle"><i class="navbar-icon fa fa-bars icon"></i><span class="hide-menu-text">HIDE MENU</span></button>
    
    <div class="navbar-inner">
      <!-- Main navbar header -->
      <div class="navbar-header">

        <!-- Logo -->
        <a href="" class="navbar-brand">
          <div>  <img alt="筑友" src="<?=base_url('assets/img/logo.png') ?>"></div>
          筑友
        </a>

        <!-- Main navbar toggle -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse"><i class="navbar-icon fa fa-bars"></i></button>

      </div> <!-- / .navbar-header -->

      <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
        <div>
          <ul class="nav navbar-nav">

<?php
  $link_list = array();
  $link_list[0] = array(
    'name' => '消息管理',
    'icon' => 'fa fa-file-text-o',
      'sub_menu' => array(
      array('url' => '/gsk/index.php/gsk_msg/msg_list' , 'icon' => 'fa fa-file-text-o',   'name' => '消息列表' ),
      array('url' => '/gsk/index.php/gsk_msg/msg_add' , 'icon' => 'fa fa-cube', 'name' => '添加消息'  ))
  );
  /*
  $link_list[0] = array( 
    'name' => '数据库',
    'icon' => 'fa fa-database',
    'sub_menu' => array(
    array('url' => 'http://192.168.164.199/op/db' , 'icon' => 'fa fa-database',   'name' => 'MySQL' ),
    array('url' => 'http://192.168.164.199/op/mongo' , 'icon' => 'fa fa-cube', 'name' => 'MongoDB'  ))
  );

  $link_list[1] = array( 
    'name' => '研发管理',
    'icon' => 'fa fa-adn',
    'sub_menu' => array(
    array('url' => 'http://192.168.164.200' , 'icon' => 'fa fa-github-alt', 'name' => 'GIT' ),
    array('url' => 'http://192.168.67.68:8080' , 'icon' => 'fa fa-bitcoin', 'name' => 'JENKINS'  ),
    array('url' => 'http://pm.glodon.com/jira/browse/JSKFB/?selectedTab=com.atlassian.jira.jira-projects-plugin:issues-panel' , 'icon' => 'fa fa-bug', 'name' => 'JIRA'  ),
    array('url' => 'http://192.168.164.199/op/log/index.php' ,  'icon' => 'fa fa-file-text-o', 'name' => '日志系统(LOG)' ),
    array('url' => 'http://192.168.164.199/op/sqm/index.php' ,  'icon' => 'fa fa-area-chart','name' => '服务质量(SQM)'  ),
    array('url' => 'http://192.168.164.199/op/moni/index.php' , 'icon' => 'fa fa-line-chart','name' => '监控系统(MONI)'  ),
    array('url' => 'http://192.168.164.199/doc' , 'icon' => 'fa fa-wikipedia-w', 'name' => '后台文档'  ))
  );

  $link_list[2] = array( 
    'name' => '测试工具',
    'icon' => 'fa fa-bug',
    'sub_menu' => array(
    array('url' => 'http://192.168.164.200:8007' , 'icon' => 'fa fa-gg',   'name' => 'API测试' ),
    array('url' => 'http://192.168.164.199/op/test' , 'icon' => 'fa fa-comment', 'name' => '消息测试'  ))
  );
  */
?>
            <?php foreach ($link_list as $item):?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="<?=$item['icon']?>"></i> <?=$item['name']?></a>
              <ul class="dropdown-menu">
                <?php foreach ($item['sub_menu'] as $menu):?>
                <li><a target="_blank" href="<?=$menu['url'];?>"><i class="<?=$menu['icon']?>"></i> <?=$menu['name'];?></a></li>
                <?php endforeach;?>
              </ul>
            </li>
            <?php endforeach;?>
          </ul> <!-- / .navbar-nav -->

          <div class="right clearfix">
            <ul class="nav navbar-nav pull-right right-navbar-nav">
              <li class="nav-icon-btn nav-icon-btn-danger dropdown">
                <a href="#notifications" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="label">5</span>
                  <i class="nav-icon fa fa-bullhorn"></i>
                  <span class="small-screen-text">Notifications</span>
                </a>

                <!-- NOTIFICATIONS -->
                <!-- Javascript -->
                <script>
                  init.push(function () {
                    $('#main-navbar-notifications').slimScroll({ height: 250 });
                  });
                </script>
                <!-- / Javascript -->

                <div class="dropdown-menu widget-notifications no-padding" style="width: 300px">
                  <div class="notifications-list" id="main-navbar-notifications">

                    <div class="notification">
                      <div class="notification-title text-danger">SYSTEM</div>
                      <div class="notification-description"><strong>Error 500</strong>: 服务器故障 <strong>404</strong>.</div>
                      <div class="notification-ago">12小时前</div>
                      <div class="notification-icon fa fa-hdd-o bg-danger"></div>
                    </div> <!-- / .notification -->

                  </div> <!-- / .notifications-list -->
                  <a href="#" class="notifications-link">更多</a>
                </div> <!-- / .dropdown-menu -->
              </li>
              <li class="nav-icon-btn nav-icon-btn-success dropdown">
                <a href="#messages" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="label">10</span>
                  <i class="nav-icon fa fa-envelope"></i>
                  <span class="small-screen-text">Income messages</span>
                </a>

                <!-- MESSAGES -->
                <!-- Javascript -->
                <script>
                  init.push(function () {
                    $('#main-navbar-messages').slimScroll({ height: 250 });
                  });
                </script>
                <!-- / Javascript -->

                <div class="dropdown-menu widget-messages-alt no-padding" style="width: 300px;">
                  <div class="messages-list" id="main-navbar-messages">

                    <div class="message">
                      <img src="<?=base_url('assets/img/user2.jpg');?>" alt="" class="message-avatar">
                      <a href="#" class="message-subject">有新消息</a>
                      <div class="message-description">
                        FROM <a href="#">MT</a>
                        &nbsp;&nbsp;·&nbsp;&nbsp;
                        5分钟前
                      </div>
                    </div> <!-- / .message -->

                  </div> <!-- / .messages-list -->
                  <a href="#" class="messages-link">更多</a>
                </div> <!-- / .dropdown-menu -->
              </li>
              <!-- / $END_NAVBAR_ICON_BUTTONS -->

	            <?php if(!empty($this->session->userdata['user_id'])) {?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
                  <img src="<?=base_url('assets/img/user2.jpg') ?>" alt="">
                  <span>
                    <?=$this->session->user_name;?>
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="#"><span class="label label-warning pull-right">New</span>个人信息</a></li>
                  <li><a href="#"><i class="dropdown-icon fa fa-cog"></i>&nbsp;&nbsp;设置</a></li>
                  <li class="divider"></li>
                  <li><a href="<?=site_url('main/logout');?>"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;注销</a></li>
                </ul>
              </li>
	            <?php } else {?>
		            <li class="dropdown">
			            <pre style="display: none;">
				            <?php var_dump($this->session->userdata['account']);?>
			            </pre>
			            <a href="<?=site_url('main/signin');?>" >
		                  <span>
		                    登录
		                  </span>
			            </a>
		            </li>
	            <?php }?>
            </ul> <!-- / .navbar-nav -->
          </div> <!-- / .right -->
        </div>
      </div> <!-- / #main-navbar-collapse -->
    </div> <!-- / .navbar-inner -->
  </div> <!-- / #main-navbar -->
<!-- / $END_MAIN_NAVIGATION -->




