<?php
namespace Goose\Package\Sms\Helper;


class Errorcode_Model_VerifySMS {
	const MODULE_NAME          = 'VERIFY_SMS';
	const ERROR_MT_COUNT       = 101;           //手机号1天内验证超过规定次数
	const ERROR_LACK_PARAMETER = 102;           //缺失部分参数或为空
	const ERROR_SMS_FALID      = 103;           //短信平台返回失败
	const ERROR_SMS_FAILURE    = 105;           //短信验证码失效
}