<?php
/**
 *  * 公用错误码
 *   */
class ErrorCode_Model_Common
{
    const MODULE_NAME = 'COMMON';
    const SUCCESS             = 0;                 //成功
    const FAILD               = 1;                 //失败
    const ERROR_NOT_LOGIN     = -1;                //未登录
    const ERROR_NOT_REGISTER  = -2;                //未注册用户
    const ERROR_SYSTEM_ERROR  = -3;                //系统繁忙
    const ERROR_REFERER_ERROR = -4;                //REFER错误
    const ERROR_NO_PRIVILEDGE = -5;                //没有权限
    const ERROR_WRONG_PARAM   = -6;                //参数错误
    const ERROR_UNKNOWN       = -7;                //未知错误
}
/**
 * 短信验证错误码
 */
class ErrorCode_Model_VerifySMS
{
    const MODULE_NAME          = 'VERIFY_SMS';
    const ERROR_MT_COUNT       = 101;           //手机号1天内验证超过规定次数
    const ERROR_LACK_PARAMETER = 102;           //缺失部分参数或为空
    const ERROR_SMS_FALID      = 103;           //短信平台返回失败
	const ERROR_SMS_FAILURE    = 105;           //短信验证码失效
}


