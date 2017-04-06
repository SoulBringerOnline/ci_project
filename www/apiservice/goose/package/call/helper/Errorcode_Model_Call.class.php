<?php
	namespace Goose\Package\Call\Helper;


	class Errorcode_Model_Call {
		const MODULE_NAME = 'CALL';

		const EMPTY_CALL_TIME   = -4001;           //没有剩余通话时长
		const EMPTY_FROM_PHONE  = -4002;          //主叫用户未绑定手机号
		const EMPTY_TO_PHONE    = -4003;            //被叫用户未绑定手机号
		const EMPTY_CANCEL_CALL = -4004;            //不存在的通话
		const ERROR_CANCEL_FAILED = -4005;            //取消失败
		const FAILED_WRITE_STATUS_LOG = -4006;            //日志写入失败
		const ERROR_RONGLIAN_REQUEST = -4007;            //荣联云请求失败
	}