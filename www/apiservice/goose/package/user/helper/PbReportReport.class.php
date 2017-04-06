<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/11/10
 * Time: 11:26
 */
namespace Goose\Package\User\Helper;
use \Goose\Package\User\Helper\PbReportMsgItem as PbReportMsgItem;
use \Goose\Package\User\Helper\PbReportClient as PbReportClient;
use \Goose\Package\User\Helper\PbReportBaseInfo as PbReportBaseInfo;
/**
 * PbReportReport message
 */
class PbReportReport extends \ProtobufMessage
{
	/* Field index constants */
	const MSGTYPE = 1;
	const ICMD = 2;
	const SCMD = 3;
	const TIME = 4;
	const LOG = 5;
	const INFO = 6;
	const CLIENT = 7;
	const MSGITEM = 8;

	/* @var array Field descriptors */
	protected static $fields = array(
		self::MSGTYPE => array(
			'name' => 'MsgType',
			'required' => false,
			'type' => 5,
		),
		self::ICMD => array(
			'name' => 'ICmd',
			'required' => false,
			'type' => 5,
		),
		self::SCMD => array(
			'name' => 'SCmd',
			'required' => false,
			'type' => 7,
		),
		self::TIME => array(
			'name' => 'Time',
			'required' => false,
			'type' => 5,
		),
		self::LOG => array(
			'name' => 'Log',
			'required' => false,
			'type' => 7,
		),
		self::INFO => array(
			'name' => 'Info',
			'required' => false,
			'type' => '\Goose\Package\User\Helper\PbReportBaseInfo'
		),
		self::CLIENT => array(
			'name' => 'Client',
			'required' => false,
			'type' => '\Goose\Package\User\Helper\PbReportClient'
		),
		self::MSGITEM => array(
			'name' => 'MsgItem',
			'required' => false,
			'type' => '\Goose\Package\User\Helper\PbReportMsgItem'
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
		$this->values[self::MSGTYPE] = null;
		$this->values[self::ICMD] = null;
		$this->values[self::SCMD] = null;
		$this->values[self::TIME] = null;
		$this->values[self::LOG] = null;
		$this->values[self::INFO] = null;
		$this->values[self::CLIENT] = null;
		$this->values[self::MSGITEM] = null;
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
	 * Sets value of 'MsgType' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setMsgType($value)
	{
		return $this->set(self::MSGTYPE, $value);
	}

	/**
	 * Returns value of 'MsgType' property
	 *
	 * @return int
	 */
	public function getMsgType()
	{
		return $this->get(self::MSGTYPE);
	}

	/**
	 * Sets value of 'ICmd' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setICmd($value)
	{
		return $this->set(self::ICMD, $value);
	}

	/**
	 * Returns value of 'ICmd' property
	 *
	 * @return int
	 */
	public function getICmd()
	{
		return $this->get(self::ICMD);
	}

	/**
	 * Sets value of 'SCmd' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setSCmd($value)
	{
		return $this->set(self::SCMD, $value);
	}

	/**
	 * Returns value of 'SCmd' property
	 *
	 * @return string
	 */
	public function getSCmd()
	{
		return $this->get(self::SCMD);
	}

	/**
	 * Sets value of 'Time' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setTime($value)
	{
		return $this->set(self::TIME, $value);
	}

	/**
	 * Returns value of 'Time' property
	 *
	 * @return int
	 */
	public function getTime()
	{
		return $this->get(self::TIME);
	}

	/**
	 * Sets value of 'Log' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setLog($value)
	{
		return $this->set(self::LOG, $value);
	}

	/**
	 * Returns value of 'Log' property
	 *
	 * @return string
	 */
	public function getLog()
	{
		return $this->get(self::LOG);
	}

	/**
	 * Sets value of 'Info' property
	 *
	 * @param PbReportBaseInfo $value Property value
	 *
	 * @return null
	 */
	public function setInfo(\Goose\Package\User\Helper\PbReportBaseInfo $value)
	{
		return $this->set(self::INFO, $value);
	}

	/**
	 * Returns value of 'Info' property
	 *
	 * @return PbReportBaseInfo
	 */
	public function getInfo()
	{
		return $this->get(self::INFO);
	}

	/**
	 * Sets value of 'Client' property
	 *
	 * @param PbReportClient $value Property value
	 *
	 * @return null
	 */
	public function setClient(\Goose\Package\User\Helper\PbReportClient $value)
	{
		return $this->set(self::CLIENT, $value);
	}

	/**
	 * Returns value of 'Client' property
	 *
	 * @return PbReportClient
	 */
	public function getClient()
	{
		return $this->get(self::CLIENT);
	}

	/**
	 * Sets value of 'MsgItem' property
	 *
	 * @param PbReportMsgItem $value Property value
	 *
	 * @return null
	 */
	public function setMsgItem(\Goose\Package\User\Helper\PbReportMsgItem $value)
	{
		return $this->set(self::MSGITEM, $value);
	}

	/**
	 * Returns value of 'MsgItem' property
	 *
	 * @return PbReportMsgItem
	 */
	public function getMsgItem()
	{
		return $this->get(self::MSGITEM);
	}
}
