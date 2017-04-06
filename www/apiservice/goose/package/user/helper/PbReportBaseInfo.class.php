<?php
namespace Goose\Package\User\Helper;


class PbReportBaseInfo extends \ProtobufMessage
{
	/* Field index constants */
	const UIN = 1;
	const CLT = 2;
	const PHONE = 3;
	const DYE = 4;
	const IP = 5;
	const NAME = 6;
	const PROVINCE = 7;
	const CITY = 8;
	const COMPANYTYPE = 9;
	const YEARSOFWORKING = 10;
	const JOBTYPE = 11;
	const JOBTITLE = 12;
	const INVITERUID = 13;
	const FRIENDNUM = 14;
	const CONTINUITYDAYNUM = 15;
	const PROJECTMEMBERSNUM = 16;
	const COMPANY = 17;

	/* @var array Field descriptors */
	protected static $fields = array(
		self::UIN => array(
			'name' => 'Uin',
			'required' => false,
			'type' => 5,
		),
		self::CLT => array(
			'name' => 'Clt',
			'required' => false,
			'type' => 5,
		),
		self::PHONE => array(
			'name' => 'Phone',
			'required' => false,
			'type' => 7,
		),
		self::DYE => array(
			'name' => 'Dye',
			'required' => false,
			'type' => 5,
		),
		self::IP => array(
			'name' => 'Ip',
			'required' => false,
			'type' => 7,
		),
		self::NAME => array(
			'name' => 'Name',
			'required' => false,
			'type' => 7,
		),
		self::PROVINCE => array(
			'name' => 'Province',
			'required' => false,
			'type' => 7,
		),
		self::CITY => array(
			'name' => 'City',
			'required' => false,
			'type' => 7,
		),
		self::COMPANYTYPE => array(
			'name' => 'CompanyType',
			'required' => false,
			'type' => 7,
		),
		self::YEARSOFWORKING => array(
			'name' => 'YearsOfWorking',
			'required' => false,
			'type' => 7,
		),
		self::JOBTYPE => array(
			'name' => 'JobType',
			'required' => false,
			'type' => 7,
		),
		self::JOBTITLE => array(
			'name' => 'JobTitle',
			'required' => false,
			'type' => 7,
		),
		self::INVITERUID => array(
			'name' => 'InviterUid',
			'required' => false,
			'type' => 5,
		),
		self::FRIENDNUM => array(
			'name' => 'FriendNum',
			'required' => false,
			'type' => 5,
		),
		self::CONTINUITYDAYNUM => array(
			'name' => 'ContinuityDayNum',
			'required' => false,
			'type' => 5,
		),
		self::PROJECTMEMBERSNUM => array(
			'name' => 'ProjectMembersNum',
			'required' => false,
			'type' => 5,
		),
		self::COMPANY => array(
			'name' => 'Company',
			'required' => false,
			'type' => 7,
		),
	);

	/**
	 * Constructs new message container and clears its internal state
	 *
	 * @return null
	 */
	public function __construct()
	{
		$this->reset();
	}

	/**
	 * Clears message values and sets default ones
	 *
	 * @return null
	 */
	public function reset()
	{
		$this->values[self::UIN] = null;
		$this->values[self::CLT] = null;
		$this->values[self::PHONE] = null;
		$this->values[self::DYE] = null;
		$this->values[self::IP] = null;
		$this->values[self::NAME] = null;
		$this->values[self::PROVINCE] = null;
		$this->values[self::CITY] = null;
		$this->values[self::COMPANYTYPE] = null;
		$this->values[self::YEARSOFWORKING] = null;
		$this->values[self::JOBTYPE] = null;
		$this->values[self::JOBTITLE] = null;
		$this->values[self::INVITERUID] = null;
		$this->values[self::FRIENDNUM] = null;
		$this->values[self::CONTINUITYDAYNUM] = null;
		$this->values[self::PROJECTMEMBERSNUM] = null;
		$this->values[self::COMPANY] = null;
	}

	/**
	 * Returns field descriptors
	 *
	 * @return array
	 */
	public function fields()
	{
		return self::$fields;
	}

	/**
	 * Sets value of 'Uin' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setUin($value)
	{
		return $this->set(self::UIN, $value);
	}

	/**
	 * Returns value of 'Uin' property
	 *
	 * @return int
	 */
	public function getUin()
	{
		return $this->get(self::UIN);
	}

	/**
	 * Sets value of 'Clt' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setClt($value)
	{
		return $this->set(self::CLT, $value);
	}

	/**
	 * Returns value of 'Clt' property
	 *
	 * @return int
	 */
	public function getClt()
	{
		return $this->get(self::CLT);
	}

	/**
	 * Sets value of 'Phone' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setPhone($value)
	{
		return $this->set(self::PHONE, $value);
	}

	/**
	 * Returns value of 'Phone' property
	 *
	 * @return string
	 */
	public function getPhone()
	{
		return $this->get(self::PHONE);
	}

	/**
	 * Sets value of 'Dye' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setDye($value)
	{
		return $this->set(self::DYE, $value);
	}

	/**
	 * Returns value of 'Dye' property
	 *
	 * @return int
	 */
	public function getDye()
	{
		return $this->get(self::DYE);
	}

	/**
	 * Sets value of 'Ip' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setIp($value)
	{
		return $this->set(self::IP, $value);
	}

	/**
	 * Returns value of 'Ip' property
	 *
	 * @return string
	 */
	public function getIp()
	{
		return $this->get(self::IP);
	}

	/**
	 * Sets value of 'Name' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setName($value)
	{
		return $this->set(self::NAME, $value);
	}

	/**
	 * Returns value of 'Name' property
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->get(self::NAME);
	}

	/**
	 * Sets value of 'Province' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setProvince($value)
	{
		return $this->set(self::PROVINCE, $value);
	}

	/**
	 * Returns value of 'Province' property
	 *
	 * @return string
	 */
	public function getProvince()
	{
		return $this->get(self::PROVINCE);
	}

	/**
	 * Sets value of 'City' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setCity($value)
	{
		return $this->set(self::CITY, $value);
	}

	/**
	 * Returns value of 'City' property
	 *
	 * @return string
	 */
	public function getCity()
	{
		return $this->get(self::CITY);
	}

	/**
	 * Sets value of 'CompanyType' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setCompanyType($value)
	{
		return $this->set(self::COMPANYTYPE, $value);
	}

	/**
	 * Returns value of 'CompanyType' property
	 *
	 * @return string
	 */
	public function getCompanyType()
	{
		return $this->get(self::COMPANYTYPE);
	}

	/**
	 * Sets value of 'YearsOfWorking' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setYearsOfWorking($value)
	{
		return $this->set(self::YEARSOFWORKING, $value);
	}

	/**
	 * Returns value of 'YearsOfWorking' property
	 *
	 * @return string
	 */
	public function getYearsOfWorking()
	{
		return $this->get(self::YEARSOFWORKING);
	}

	/**
	 * Sets value of 'JobType' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setJobType($value)
	{
		return $this->set(self::JOBTYPE, $value);
	}

	/**
	 * Returns value of 'JobType' property
	 *
	 * @return string
	 */
	public function getJobType()
	{
		return $this->get(self::JOBTYPE);
	}

	/**
	 * Sets value of 'JobTitle' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setJobTitle($value)
	{
		return $this->set(self::JOBTITLE, $value);
	}

	/**
	 * Returns value of 'JobTitle' property
	 *
	 * @return string
	 */
	public function getJobTitle()
	{
		return $this->get(self::JOBTITLE);
	}

	/**
	 * Sets value of 'InviterUid' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setInviterUid($value)
	{
		return $this->set(self::INVITERUID, $value);
	}

	/**
	 * Returns value of 'InviterUid' property
	 *
	 * @return int
	 */
	public function getInviterUid()
	{
		return $this->get(self::INVITERUID);
	}

	/**
	 * Sets value of 'FriendNum' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setFriendNum($value)
	{
		return $this->set(self::FRIENDNUM, $value);
	}

	/**
	 * Returns value of 'FriendNum' property
	 *
	 * @return int
	 */
	public function getFriendNum()
	{
		return $this->get(self::FRIENDNUM);
	}

	/**
	 * Sets value of 'ContinuityDayNum' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setContinuityDayNum($value)
	{
		return $this->set(self::CONTINUITYDAYNUM, $value);
	}

	/**
	 * Returns value of 'ContinuityDayNum' property
	 *
	 * @return int
	 */
	public function getContinuityDayNum()
	{
		return $this->get(self::CONTINUITYDAYNUM);
	}

	/**
	 * Sets value of 'ProjectMembersNum' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setProjectMembersNum($value)
	{
		return $this->set(self::PROJECTMEMBERSNUM, $value);
	}

	/**
	 * Returns value of 'ProjectMembersNum' property
	 *
	 * @return int
	 */
	public function getProjectMembersNum()
	{
		return $this->get(self::PROJECTMEMBERSNUM);
	}

	/**
	 * Sets value of 'Company' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setCompany($value)
	{
		return $this->set(self::COMPANY, $value);
	}

	/**
	 * Returns value of 'Company' property
	 *
	 * @return string
	 */
	public function getCompany()
	{
		return $this->get(self::COMPANY);
	}
}