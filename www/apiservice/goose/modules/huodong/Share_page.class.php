<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/11/3
 * Time: 17:20
 */
namespace Goose\Modules\Huodong;

/* 文件头信息 */
header('Content-Type: application/json; charset=utf-8');

class Share_page extends \Goose\Libs\Wmodule{

	public function run()
	{
		$type = empty( $_GET['type'] ) ? 1 : intval($_GET['type']);

		$share_page_default = array(
//    'title' => '筑友，为建筑而生',

			'title' => '分享筑友给好友，
    和好友一起赚积分，兑换免费流量！',

			'info' => '【推荐好友送积分】：
1、每成功推荐一个好友注册筑友，且好友在5日内通过小秘书回复推荐人的邀请码，推荐人和好友均可获得200积分奖励（=10M流量）。每人每天最多可得1000积分（=50M）流量奖励。
2、积分将会在好友回复邀请码后一个工作日内累计到您的积分中，您可以在“积分换流量”处进行流量兑换。
3、活动时间：2015年10月15日至2016年2月29日。
4、活动仅支持最新版本筑友，安卓为1.1.0及以上版本，IOS为1.0及以上版本。',
			'img' => 'http://mat.zy.glodon.com/huodong%2Fhuodong_tuiguang_0.png',
			'share_title' => '筑友-建筑人的工作社交圈',
			'share_info' => '免费查规范，轻松做工作，结交工作伙伴，阅尽建筑行业新鲜资讯，认证项目还有免费电话随心打，一切尽在【筑友APP】，赶紧下载吧~',
		);


		$share_page_marketing  = array(
			'title' => '感谢您！
让更多建筑行业精英加入筑友！',
			'info' => '【推荐规则】：
1、分享您的专属邀请码给好友，被推荐人在注册后2日内通过小秘书回复邀请码即可计入推广记录；
2、关于推广用户统计，同一设备注册多个账号，计为一个用户。',
			'img' => 'http://mat.zy.glodon.com/huodong%2Fhuodong_tuiguang_1.png',
			'share_title' => '筑友-建筑人的工作社交圈',
			'share_info' => '免费查规范，轻松做工作，结交工作伙伴，阅尽建筑行业新鲜资讯，认证项目还有免费电话随心打，一切尽在【筑友APP】，赶紧下载吧~',
		);

		if( $type == 2 ){
			$this->response->make_json_ok( '' ,  $share_page_marketing );
		}
		else
		{
			$this->response->make_json_ok( '' ,  $share_page_default );
		}
	}
} 