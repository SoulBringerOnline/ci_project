<?php
	namespace Goose\Package\Account\Helper;


	class Errorcode_Model_Account {
		const MODULE_NAME          = 'ACCOUNT';

		const ERROR_USER_EMPTY          = -2001;           //用户名或者密码为空
		const ERROR_ERROR_PHONE         = -2002;           //手机号码不正确
		const ERROR_PWD_FALID           = -2003;           //密码长度只能为8-16个字符
		const ERROR_USER_EXIST          = -2004;           //该用户已经存在
		const ERROR_UID_FAILD           = -2005;           //获取uid失败
		const ERROR_REGISTER_FAILD      = -2006;           //注册用户失败
		const ERROR_ACCOUNT_PASSWORD    = -2007;           //账户密码错误
		const ERROR_LOGIN_FAILD         = -2008;           //登录失败
		const ERROR_REFRESH_TOKEN_FAILD = -2009;           //换票失败
		const ERROR_TGTOKEN_FORMAT      = -2010;           //tg_token格式有误,应该是48位字符串

		const ERROR_NAME_EMPTY          = -3001;           //用户名或者密码为空
		const ERROR_USER_NOT_EXIST      = -3002;           //该用户不存在
		const ERROR_FORGET_PWD_FAILD    = -3003;           //修改密码失败
		const ERROR_WRONG_PWD           = -3004;           //密码错误
	}