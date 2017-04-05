<?php

	function get_dl_url($filter){
		if( $filter['clt'] == 2 )
		{
			//return "itms-services://?action=download-manifest&amp;url=https://yun.glodon.com/iosplist/gsk_d.plist.do%3Fs%3Dhttp:%2F%2Fgxtmobile.glodon.com:8888%26p%3d%2fClientFiles%2fgsk%2f%26n%3dgsk_" . $filter['c'] .  "_" . $filter['version'] .  ".ipa";
			return "https://itunes.apple.com/cn/app/zhu-you/id1035260271?l=zh&mt=8";
		}
		else if( $filter['clt'] == 3 )
		{
			return "http://ugc.zy.glodon.com/dl%2Fandroid%2F" . $filter['version'] . "%2Fgsk_" . $filter['c'] .  "_" . $filter['version'] .  ".apk";
			//return "http://gxtmobile.glodon.com:8888/clientfiles/gsk/gsk_" . $filter['c'] .  "_" . $filter['version'] .  ".apk";
		}
		else if( $filter['clt'] == 4 )
		{
			return "http://ugc.zy.glodon.com/dl%2Fpc%2Fgsk_". $filter['c'] .  "_" . $filter['version'] . ".exe";
		}
		return "";
	}

	function get_dl_page( $filter ){
		if( $filter['c'] == 998 ){
			return 'http://zy.glodon.com/download?c=' . $filter['c'] ;
		}
		return 'http://zy.glodon.com/download?c=99';
		//return 'http://zy.glodon.com/download?c=' . $filter['c'] ;
	}

	function get_qrcode_png( $filter ){
		//       return 'http://img.zy.glodon.com/qrcode%2Fgsk_qrcode_' . $filter['c'] . '.png' ;
		if( $filter['c'] == 2 || $filter['c'] == 998 || $filter['c'] == 3 ){
			return 'http://img.zy.glodon.com/qrcode%2Fgsk_qrcode_' . $filter['c'] . '.png' ;
		}
		return 'http://img.zy.glodon.com/qrcode%2Fgsk_qrcode_99.png' ;
	}


	function get_cdn_url( $filter ){
		return 'http://ugc.zy.glodon.com/';
	}

	function get_news_url( $filter ){
		if( $filter['white_user'] || $filter['c'] == 2 || $filter['c'] == 3)
		{
			return  'http://zy.work.glodon.com/news/index.html';
		}
		else if($filter['c'] == 1) {
			return 'http://zy.dev.glodon.com/news/index.html';
		}
		return 'http://zy.glodon.com/news/index.html';
	}

	function get_site( $filter ){
		if( $filter['white_user'] || $filter['c'] == 2 || $filter['c'] == 3 )
		{
			return  'http://zy.work.glodon.com/';
		}
		else if($filter['c'] == 1) {
			return  'http://zy.dev.glodon.com/';
		}
		return 'http://zy.glodon.com/';
	}

	function get_update_type( $filter ){
		if( $filter['v'] != $filter['version'] )
		{
			if( $filter['clt'] == 2 )
			{
				if( $filter['c'] == 2  )
				{
					return 1;
				}
				return 0;
			}
			elseif( $filter['clt'] == 3 )
			{
				if($filter['c'] == 3) {
					return 1;
				}
				return 1;
			}
			elseif( $filter['clt'] == 4 )
			{
				return 1;
			}
		}
		return 0;
	}

	function get_update_tips( $filter ){
		if( $filter['c'] == 800 ){
			return "";
		}
// 	return '1.  海量的规范、工艺，高效分类查看，支持搜索精准定位
// 2.  行业化的项目协同，让团队协作高效便捷
// 3.  丰富的行业资讯，帮助拓展视野领域
// 4.  顺畅的沟通，与同事好友畅聊无阻';
//     return '版本更新内容：
// 1、修复了创建项目闪退的问题；
// 2、修复了第三方账号登录筑友，手机号绑定不成功的问题；
// 3、新增了用户反馈渠道，可在筑友内反馈产品意见和建议；
// 3、优化及修复了部分遗留bug。';
		if( $filter['clt'] == 4 ){
			/*
			return '1. 便捷创建项目，你也可以直接搜索加入自己正在参与的项目
	2. 任务卡片轻松指派，日程展现方式一目了然
	3. 每个项目都有自己的云文件存储空间，团队成员共享工作资料
	4. 所有与我相关的工作事项及时提醒，不错漏任何一点细节
	5. 与移动端筑友无缝打通，无论面对电脑还是拿着手机，都能轻松工作 ';
			 */

			return '1. 强化了项目中任务的查看与操作能力
2. 调优产品界面，提升了各细节体验
3. 优化了版本稳定性';
		}

		/*
		return '新增功能特性如下：
	1、新增个人活跃度及积分功能
	2、优化公司、岗位及职称信息
	3、会话搜索功能扩展，支持搜索规范和看点
	4、项目协同功能优化
	5、会话消息支持置顶，群组功能优化，支持踢人、转让
	6、规范、看点支持收藏和转发
	7、加好友权限设置功能扩展
	8、修复因弱网导致启动闪退的BUG
	9、修复了其他BUG';
		 */
//         return '新增功能特性如下：
// 1、修复了对话模块中的消息提醒问题和显示问题。
// 2、修复了发送多张图片时的消息提示问题。
// 3、修复了收藏功能的显示问题和转发功能中导致程序闪退的问题。
// 4、优化了项目模块中转移管理员身份的流程。';

		/*
			return '筑友V1.2版本功能特性
		奉上不一样的筑友1.2版本~
		1.全新的视觉体验，有格调的工作APP
		2.图片支持打标记，快速追踪工地实况信息
		3.规范工艺支持离线下载，摆脱无网无流量困扰
		4.项目支持部门组织，管理更加清晰有序
		5.修复完善了各种体验问题';
		*/

		/*
		return '筑友V1.3版本功能特性
	奉上不一样的筑友1.3版本~
	1.项目支持加V认证，让团队协作更加高效
	2.免费打电话给同事，沟通更快速更省时间
	3.赠送免费知识礼包，轻轻松松搞定海量干货
	4.优化登录注册模块，一步轻松完成账户登录
	5.修复完善各种问题，提升使用体验';
		*/
		return '本次更新
1.修复bug，完善体验问题

最近更新
1.项目支持加V认证，让团队协作更加高效
2.免费打电话给同事，沟通更快速更省时间
3.赠送免费知识礼包，轻轻松松搞定海量干货
4.优化登录注册模块，一步轻松完成账户登录';
	}

	function get_api_url( $filter ){
		if( $filter['c'] == 2 || $filter['c'] == 3 )
		{
			return 'http://api.zhuyou.glodon.com/';
		}
		else if( $filter['c'] == 1 )
		{
			return 'http://api.zy.dev.glodon.com/';
		}
		return  'http://api.zy.glodon.com/';
	}


	function get_debug( $filter ){
		if( $filter['c'] == 2 || $filter['c'] == 3)
		{
			return 1;
		}
		return 0;
	}



	function get_splash( $filter ){
		$_CFG = array();
		$_CFG['mongodb_op'] = "mongodb://10.128.63.250:27017"; //运营MONGO
		$splash = array();
		if($filter['c'] == 1)
		{
				$star_time = strtotime("2015-11-29");
	            $end_time = strtotime("2016-12-29");
                $splash = array('url' => 'http://ugc.zy.glodon.com/op%2Fadv.png' , 'duration' => 3, 'start_time'=>$star_time, 'end_time'=>$end_time , 'display_type'=>1);
			    return $splash;
		}
//	$star_time = strtotime("2015-11-29");
//	$end_time = strtotime("2015-12-29");
//    $splash[0] = array('url' => 'http://ugc.zy.glodon.com/op%2Fadv.png' , 'duration' => 3, 'start_time'=>$star_time, 'end_time'=>$end_time , 'display_type'=>1);
		//$splash[1] = array('url' => 'http://mat.zy.glodon.com/splash%2F2.png' , 'duration' => 3, 'start_time'=>$star_time, 'end_time'=>$end_time );
		$connection = new MongoClient($_CFG['mongodb_op']);
		$mongo_op = new MongoDB($connection, 'gsk');
		$main_switch = $mongo_op->activity_position->findOne(array('f_position_id'=>2));
		if(is_null($main_switch)||$main_switch['f_position_state']==0)//总开关
		{
			return array();
		}
		else
		{
			$time = time();
			$where = array(
				'f_activity_pt_id'=>2,
				'f_begin_time'=>array('$lte' => $time),
				'f_finish_time'=>array('$gte' => $time)
			);
			$need = array(
				"_id"=>0,
				"f_big_pic_url"=>1,
				"f_begin_time"=>1,
				"f_finish_time"=>1,
				"f_continue_time"=>1,
			);
			$res = $mongo_op->activity_pt_history->findOne($where, $need);
			if(is_null($res))
			{
				return array();
			}
			else
			{
				$splash = array(
					'url' => $res['f_big_pic_url'],
					'duration' => $res['f_continue_time'],
					'start_time'=> $res['f_begin_time'],
					'end_time'=> $res['f_finish_time'] ,
					'display_type'=> 1
				);

				return $splash;
			}
		}
	}


	function get_host( $filter ){
		$host_online = array();
		$host_online[0] = array('ip' => '103.227.79.82' , 'port' => 18100);
		$host_online[1] = array('ip' => '103.227.79.82' , 'port' => 18101);
		shuffle($host_online);

		$host_test = array();
		$host_test[0] = array('ip' => '182.48.117.13' , 'port' => 18100);
		$host_test[1] = array('ip' => '182.48.117.13' , 'port' => 18100);
		//$host_test[0] = array('ip' => '103.227.79.82' , 'port' => 18101);
		//$host_test[1] = array('ip' => '103.227.79.82' , 'port' => 18101);

		if( $filter['c'] == 2  || $filter['c'] == 3 )
		{
			return $host_test;
		}

		return $host_online;
	}

	function get_active($filter) {
		 $active_info = array();
         $active_info["url"] = "newyear/game/home.html?type=loadding";
         $active_info["start_time"] = strtotime("2015-11-30");
         $active_info["end_time"] = strtotime("2015-12-30");
         $active_info["url_type"] = 2;

         if( $filter['c'] == 2  || $filter['c'] == 3 )
         {
             return $active_info;
         }
         return $active_info;
     }



