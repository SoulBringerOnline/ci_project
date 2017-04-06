<?php
	namespace Goose\Libs;
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
		const ERROR_TOKEN_FAILURE = -3333;                //token失效
		const ERROR_HEADER_FAILURE = -9;               //Header中【zhuyou】解析失败
		const ERROR_NOT_EXIST      = -10;              //用户不存在
	}