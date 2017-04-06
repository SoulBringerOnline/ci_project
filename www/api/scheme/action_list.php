<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/12/4
 * Time: 17:14
 */

$list = array(
	array(
		"title"=>"代理请求数据（get）",
		"content"=>array("name"=>"request.forward",
		"path"=>"http://api.zy.glodon.com/event/point/flow/get",
		"method"=>"get")
	),

	array(
		"title"=>"代理请求数据（post）",
		"content"=>array("name"=>"request.forward",
			"path"=>"http://api.fdfjsdi.glodon.com/2.0/feedback/Feedback",
			"method"=>"post")
	),
	array(
		"title"=>"用户详情页",
		"content"=>array("name"=>"view.user.detail",
			"uin"=>"109614"
		)
	),
	array(
		"title"=>"群聊",
		"content"=>array("name"=>"view.group.chat",
			"groupid"=>"5641b029e4b082c77cad98b8",
			)
	),
	array(
		"title"=>"项目主页",
		"content"=>array("name"=>"view.project.main",
			)
	),
	array(
		"title"=>"项目详情页",
		"content"=>array("name"=>"view.project.detail",
			"projectid"=>"98f21035924345e8836d41e01200db92",
			"method"=>"post")
	),
	array(
		"title"=>"看点主页",
		"content"=>array("name"=>"view.news.main",
			)
	),
	array(
		"title"=>"看点详情页",
		"content"=>array("name"=>"view.news.detail",
			"newsid"=>"55dd28ee7c8af8c1666cb2b3")
	),
	array(
		"title"=>"关闭当前webview",
		"content"=>array("name"=>"action.closeWindow",
			)
	),
	array(
		"title"=>"打开新webview",
		"content"=>array("name"=>"action.openURLInWindow",
			"url"=>'http://zy.work.glodon.com/news/index.html',
		"hideToolbar"=> '1'
		)
	),
	array(
		"title"=>"setTitle",
		"content"=>array("name"=>"action.setTitle",
			"title"=> 'title'
		)
	),
	array(
		"title"=>"分享",
		"content"=>array("name"=>"action.share",
			"url"=> "http://zy.glodon.com",
			"imgurl"=> "http://ugc.zy.glodon.com/head/59527802277DAFF552522AB4FD264BE4.png",
			"title"=>"dddddd",
			"content"=> urlencode("分享内容")
		)
	),

	array(
		"title"=>"feedback",
		"content"=>array("name"=>"view.myself.feedback",
			"url"=> 'http://zy.glodon.com/feedback/index.php'
		)
	),
	array(
		"title"=>"绑定手机号",
		"content"=>array("name"=>"view.bind.phone",
			"refresh"=> true,
		)
	),
	array(
		"title"=>"获取头信息",
		"content"=>array("name"=>"action.getHeaderParams",
			"title"=> 'title'
		)
	),
);

$schemes = array(
	array(
		"title"=>"添加好友",
		"url"=>"zhuyou://contact?type=1",
	),
	array(
		"title"=>"活动H5页",
		"url"=>"zhuyou://activity?type=1&url=",
	),
	array(
		"title"=>"任务详情页",
		"url"=>"zhuyou://task?id=1&type=1",
	),
	array(
		"title"=>"规范首页",
		"url"=>"zhuyou://knowledge?type=1&id=231",
	),
);



	echo json_encode(array('jsbridge'=>$list, 'scheme'=>$schemes));