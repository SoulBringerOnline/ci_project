<?php
	/**
	 * Created by PhpStorm.
	 * User: lixd-a
	 * Date: 2015/12/25
	 * Time: 17:36
	 */

	namespace Goose\Package\Newyeargame\Helper;

	class Errorcode_Model_NewyearGame {

		const MODULE_NAME                 = 'NewYearGame';
		const ERROR_BINDED_PHONE          = 101;           //该手机号已经验证过了，无法重新验证哦！请使用该手机号直接使用筑友领取奖品吧~
		const ERROR_BINDED_UNIONID        = 102;           //你已经验证过手机号，无法重复验证哦！请使用验证手机号直接使用筑友领取奖品吧~
		const ERROR_FAILE_OPENID          = -999;           //cookie中openid失效
		const ERROR_EMPTY_COOKIE          = -444;           //没种cookie
	}



