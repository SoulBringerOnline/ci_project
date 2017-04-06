<?php
namespace Goose\Package\Sms;
class SMSUtil {
	function flow_exchange_status($status) {
		if (empty($status)) {
			return '支付中...';
		} else {
			switch ($status) {
				case -10:
				case -30:
				case 0:
					return "支付中...";
					break;
				case 1:
					return "成功";
					break;
				case -1:
				case -20:
					return "失败";
					break;
				default:
					return $status;
			}
		}
	}
}