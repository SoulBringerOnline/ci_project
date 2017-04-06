<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/11/10
 * Time: 10:59
 */

namespace Goose\Package\User;

use \Libs\Mq\AmqpManager;
use \Goose\Package\User\Helper\PbReportBaseInfo;
use \Goose\Package\User\Helper\PbReportReport;
use \Goose\Package\User\Helper\PbReportClient;
use \Goose\Package\User\Helper\PbReportMsgItem;
use \Libs\Mongo\MongoDB;

class User {
	const AMQP_EXCHANE_NAME = 'gsk-mq-exchange';
	const AMQP_ROUTE_KEY = 'gsk_register_key';
	private static $instance = null;
	private function __construct () {

	}

	public static function instance() {
		if(empty(self::$instance)) {
			self::$instance = new User();
		}

		return self::$instance;
	}
	/**
	 * 通知积分系统，用户的加积分时间
	 */
	public function addUserPointTask($cmd, $userInfo) {
		$baseInfo = new PbReportBaseInfo();
		$report = new PbReportReport();

		$baseInfo = new PbReportBaseInfo();
		$report = new PbReportReport();

		$baseInfo->setUin($userInfo['f_uin']);
		$baseInfo->setCity($userInfo['f_city']);
		$baseInfo->setCompany($userInfo['f_company']);
		$baseInfo->setCompanyType($userInfo['f_company_type']);
		$baseInfo->setJobTitle($userInfo['f_company_type']);//f_job_title
		$baseInfo->setJobType($userInfo['f_job_type']);//f_job_type
		$baseInfo->setName($userInfo['f_name']);//
		$baseInfo->setPhone($userInfo['f_phone']);//
		$baseInfo->setProvince($userInfo['f_province']);//
		$baseInfo->setYearsOfWorking($userInfo['f_years_of_working']);//
		$baseInfo->setContinuityDayNum($userInfo['f_go_on_day']);//

		$report->setICmd($cmd);
		$report->setInfo($baseInfo);

		$packed = $report->serializeToString();
		AmqpManager::instance()->sendMsgToExchange(self::AMQP_EXCHANE_NAME, self::AMQP_ROUTE_KEY, $packed);
		return TRUE;
	}

	public function getUserBaseInfo($uid, $option=array()) {
		$mongo = \Libs\Mongo\MongoDB::getMongoDB("online","gsk_ol");
		$where = array("f_uin"=>$uid);
		$result = $mongo->user->find($where, array());
		$result = iterator_to_array($result);

		if($result ) {
			return array_pop($result);
		}
		return array();
	}
}