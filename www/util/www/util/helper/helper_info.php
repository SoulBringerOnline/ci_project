<?php

function get_dl_url($filter){
    if( $filter['clt'] == 2 )
    {
        //return "itms-services://?action=download-manifest&amp;url=https://yun.glodon.com/iosplist/gsk_d.plist.do%3Fs%3Dhttp:%2F%2Fgxtmobile.glodon.com:8888%26p%3d%2fClientFiles%2fgsk%2f%26n%3dgsk_" . $filter['c'] .  "_" . $filter['version'] .  ".ipa";
        return "https://itunes.apple.com/cn/app/zhu-you/id1035260271?l=zh&mt=8";
    }
    else if( $filter['clt'] == 3 )
    {
        return "http://gxtmobile.glodon.com:8888/clientfiles/gsk/gsk_" . $filter['c'] .  "_" . $filter['version'] .  ".apk";
    }
    else if( $filter['clt'] == 4 )
    {
        return "http://zhuyou-f.oss-cn-beijing.aliyuncs.com/dl%2Fpc%2Fgsk_". $filter['c'] .  "_" . $filter['version'] . ".exe";
    }
    return "";
}

function get_dl_page( $filter ){
    return 'http://zy.glodon.com/download?c=' . $filter['c'] ;
}

function get_qrcode_png( $filter ){
	return 'http://img.zy.glodon.com/qrcode%2Fgsk_qrcode_' . $filter['c'] . '.png' ;
}

function get_cdn_url( $filter ){
	return 'http://ugc.zy.glodon.com/';
}

function get_news_url( $filter ){
    if( $filter['white_user'] || $filter['c'] == 2 )
    {
        return  'http://zy.work.glodon.com/news/index.html';
    }
    return 'http://zy.glodon.com/news/index.html';
}

function get_update_type( $filter ){
    if( $filter['v'] != $filter['version'] )
    {
        if( $filter['clt'] == 2 )
        {
            if( $filter['c'] == 2 )
            { 
                return 1; 
            }
            return 0;
        }
        elseif( $filter['clt'] == 3 )
        {
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
        return '1. 便捷创建项目，你也可以直接搜索加入自己正在参与的项目
2. 任务卡片轻松指派，日程展现方式一目了然
3. 每个项目都有自己的云文件存储空间，团队成员共享工作资料
4. 所有与我相关的工作事项及时提醒，不错漏任何一点细节 
5. 与移动端筑友无缝打通，无论面对电脑还是拿着手机，都能轻松工作 ';
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
        return '新增功能特性如下：
1、修复了对话模块中的消息提醒问题和显示问题。
2、修复了发送多张图片时的消息提示问题。
3、修复了收藏功能的显示问题和转发功能中导致程序闪退的问题。
4、优化了项目模块中转移管理员身份的流程。';

}

function get_api_url( $filter ){
    if( $filter['c'] == 2 )
    {
        return 'http://zy.work.glodon.com/';
    }
    return  'http://api.zy.glodon.com/';
}


function get_debug( $filter ){
    if( $filter['c'] == 2 )
    {
        return 1;
    }
    return 0;
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

    if( $filter['c'] == 2 )
    {
        return $host_test;
    }

    return $host_online;
}


