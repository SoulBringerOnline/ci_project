<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/12/21
 * Time: 19:46
 */

namespace Goose\Package\Weixin\Helper;

class Errorcode_Model_Weixin {
	const MODULE_NAME          = 'WEIXIN';
	const ERROR_EMPTY_CODE       = 101;           //code码为空
	const ERROR_EMPTY_OPENID     = 102;           //微信返回openid为空
	const ERROR_OPENID_BINDED    = 103;           //该uin已经绑定过微信号
	const ERROR_UNIONID_BINDED   = 104;           //该微信号已经绑定过uin
	const ERROR_FAILURE_CODE     = 105;           //code码失效
}


class Errorcode_Model_QQ {
	const MODULE_NAME          = 'QQ';
	const ERROR_FAILURE_OPENID       = 201;           //openid无效
}