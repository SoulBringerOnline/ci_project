<!-- $MAIN_MENU -->
<?php
    function push_menu(&$sidebar_tree, $uri , $icon , $name, $sub_menu){
      $menu = array();
      $menu['uri'] = $uri;
      $menu['icon'] = $icon;
      $menu['name'] = $name;
      $menu['sub_menu'] = $sub_menu;
      array_push($sidebar_tree, $menu); 
    }

    function push_sub_menu(&$sub_menu, $uri , $icon , $name){
      $menu = array();
      $menu['uri'] = $uri;
      $menu['icon'] = $icon;
      $menu['name'] = $name;
      array_push($sub_menu, $menu); 
    }
    $sidebar_tree = array();

    //DASHBOARD
    push_menu($sidebar_tree, 'main', 'fa fa-tachometer', 'Dashboard', array() );

    //看点发布
    if (in_array($this->session->user_role, array('admin', 'op', 'guest'))) {
	$sub_menu = array();
	push_sub_menu($sub_menu , 'spider', 'fa fa-hand-grab-o', '新闻抓取');
	push_sub_menu($sub_menu , 'news', 'fa fa-globe', '行业资讯');
	push_sub_menu($sub_menu , 'hot', 'fa fa-slack', '热门话题');
    push_sub_menu($sub_menu , 'help', 'fa fa-question-circle', '帮助文档');
    push_sub_menu($sub_menu , 'feedback', 'fa fa-users', '用户反馈');
    push_sub_menu($sub_menu , 'liumi_flowlog', 'fa fa-retweet', '流量兑换');
    push_sub_menu($sub_menu , 'user_integral', 'fa fa-user-md', '用户积分');
    push_sub_menu($sub_menu , 'user_integral_ranking', 'fa fa-sort-alpha-asc', '积分排行榜');
    push_sub_menu($sub_menu , 'call_record', 'fa fa-money', '通话记录');
    push_sub_menu($sub_menu , 'call_convert_record', 'fa fa-money', '通话兑换记录');
    push_sub_menu($sub_menu , 'call_balance_account', 'fa fa-money', '对账系统');
    push_sub_menu($sub_menu , 'activity_position', 'fa fa-leaf', '运营活动位');
    push_sub_menu($sub_menu , 'import_card', 'fa fa-leaf', '京东卡导入');
    push_sub_menu($sub_menu , 'pc_activity_pos', 'fa fa-leaf', 'PC活动运营位');
    push_menu($sidebar_tree, 'gsk_news', 'fa fa-flickr', '内容运营', $sub_menu);
    }
    //消息列表
    if (in_array($this->session->user_role, array('admin', 'op', 'guest'))) {
	$sub_menu = array();
    push_sub_menu($sub_menu , 'msg_list', 'fa fa-file-text-o', '消息列表');
    push_sub_menu($sub_menu , 'msg_add', 'fa fa-retweet', '添加消息');
    push_menu($sidebar_tree, 'gsk_msg', 'fa fa-flickr', '消息管理', $sub_menu);
    }

    //新春活动
    if (in_array($this->session->user_role, array('admin'))) {
        $sub_menu = array();
        push_sub_menu($sub_menu , 'show_manage', 'fa fa-retweet', '奖品管理');
        push_sub_menu($sub_menu , 'print_record', 'fa fa-file-text-o', '领取记录');
        push_sub_menu($sub_menu , 'prize_detail', 'fa fa-retweet', '奖品明细');
        push_menu($sidebar_tree, 'gsk_prize', 'fa fa-flickr', '新春活动', $sub_menu);
    }
    //版本发布
    if (in_array($this->session->user_role, array('admin', 'stat', 'guest'))) {
    $sub_menu = array();
    push_sub_menu($sub_menu , 'pub_ios', 'fa fa-apple', 'iOS');
    push_sub_menu($sub_menu , 'pub_android', 'fa fa-android', 'android');
    push_sub_menu($sub_menu , 'pub_pc', 'fa fa-windows', 'PC');
    push_sub_menu($sub_menu , 'down_pkg', 'fa fa-qrcode', '安装包');
    push_menu($sidebar_tree, 'gsk_pub', 'fa fa-cloud-upload', '版本发布', $sub_menu );
    }


    //运维系统
    if (in_array($this->session->user_role, array('admin','guest'))) {
    $sub_menu = array();
    push_sub_menu($sub_menu , 'log', 'fa fa-file-text-o', '日志系统');
    push_sub_menu($sub_menu , 'sqm', 'fa fa-area-chart', '服务质量');
    push_sub_menu($sub_menu , 'moni', 'fa fa-line-chart', '监控系统');
    push_sub_menu($sub_menu , 'user_info', 'fa fa-user', '用户信息');
    push_sub_menu($sub_menu , 'project_info', 'fa fa-magnet', '项目信息');
    push_menu($sidebar_tree, 'gsk_op', 'fa fa-adn', '运维系统', $sub_menu );
    }
	else if(in_array($this->session->user_role, array('asist'))) {
		$sub_menu = array();
		push_sub_menu($sub_menu , 'project_info', 'fa fa-magnet', '项目信息');
		push_menu($sidebar_tree, 'gsk_op', 'fa fa-adn', '运维系统', $sub_menu );
	}

    //统计系统
    $sub_menu = array();
    if (in_array($this->session->user_role, array('admin', 'stat', 'op'))) {
    if (in_array($this->session->user_role, array('admin', 'stat'))) {
      push_sub_menu($sub_menu , 'stat', 'fa fa-line-chart', '渠道数据');
    }
    if (in_array($this->session->user_role, array('admin', 'op'))) {
      push_sub_menu($sub_menu , 'stat_PV', 'fa fa-rebel', 'PV数据');
    }
    push_menu($sidebar_tree, 'gsk_stat', 'fa fa-bar-chart-o', '统计系统', $sub_menu );
    }
    //推广系统
    if (in_array($this->session->user_role, array('admin', 'stat', 'guest'))) {
    $sub_menu = array();
    push_sub_menu($sub_menu , 'audit', 'fa fa-file-o', '推广审核');
	  push_sub_menu($sub_menu , 'data', 'fa fa-binoculars', '推广数据');
    push_menu($sidebar_tree, 'gsk_promotion', 'fa fa-trello', '推广系统', $sub_menu );
    }

	// 文件系统
	if (in_array($this->session->user_role, array('admin', 'stat', 'guest',"asist"))) {
		$sub_menu = array();
		push_sub_menu($sub_menu , 'index', 'fa fa-file-o', '大礼包文件管理');
		push_menu($sidebar_tree, 'gsk_file', 'fa fa-trello', '文件系统', $sub_menu );
	}

    // IM设置
    if (in_array($this->session->user_role, array('admin', 'stat', 'guest'))) {
        $sub_menu = array();
        push_sub_menu($sub_menu , 'navigation', 'fa fa-file-o', '群组导航');
        push_menu($sidebar_tree, 'gsk_set', 'fa fa-trello', 'IM设置', $sub_menu );
    }
?>

<div id="main-menu" role="navigation">
  <div id="main-menu-inner">
    <ul class="navigation">
      <?php foreach ($sidebar_tree as $item):?>
        <?php if(empty($item['sub_menu'])) : ?>
          <li <?php if($this->uri->segment(1) == $item['uri'] ){ echo 'class="active"';}?> >
            <a href="<?=site_url( $item['uri']);?>"><i class="menu-icon <?=$item['icon'];?>"></i> <span class="mm-text"><?=$item['name'];?></span></a>
          </li>
        <?php else:?>
          <li class="mm-dropdown mm-dropdown-root <?php if($this->uri->segment(1) == $item['uri'] ){ echo " open active";}?>">
            <a href="#"><i class="menu-icon <?=$item['icon'];?>"></i> <span class="mm-text"><?=$item['name'];?></span></a>
            <ul>
              <?php foreach ($item['sub_menu'] as $sub_item):?>
              <li <?php if($this->uri->segment(1) == $item['uri'] && $this->uri->segment(2) == $sub_item['uri']){ echo ' class="active"'; }?> >
                <a tabindex="-1" href="<?=site_url( $item['uri'] . '/' . $sub_item['uri']  );?>">
                  <i class="menu-icon <?=$sub_item['icon'];?>"></i>  <span class="mm-text"><?=$sub_item['name'];?></span>
                </a>
              </li>
              <?php endforeach;?>
            </ul>
          </li>
        <?php endif;?>
      <?php endforeach;?>
    </ul> <!-- / .navigation -->
  </div> <!-- / #main-menu-inner -->
</div> <!-- / #main-menu -->
<!-- / $MAIN_MENU -->


