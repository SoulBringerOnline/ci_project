<?php
/**
* Created by PhpStorm.
* User: yaojj-a
* Date: 2015/11/10
* Time: 11:35
*/
namespace Goose\Package\User\Helper;
/**
 * PbReportMsgItem message
 */
class PbReportMsgItem extends \ProtobufMessage
{
	/* Field index constants */
	const MSGID = 1;
	const MSGTYPE = 2;
	const MSGBODY = 3;
	const SENDERID = 4;
	const SENDERNAME = 5;
	const SENDERREMARK = 6;
	const SENDERAVATAR = 7;
	const GROUPID = 8;
	const GROUPTYPE = 9;
	const GROUPNAME = 10;
	const GROUPDESC = 11;
	const SENDTIME = 12;
	const MSGSEQID = 13;

	/* @var array Field descriptors */
	protected static $fields = array(
		self::MSGID => array(
			'name' => 'MsgId',
			'required' => false,
			'type' => 7,
		),
		self::MSGTYPE => array(
			'name' => 'MsgType',
			'required' => false,
			'type' => 5,
		),
		self::MSGBODY => array(
			'name' => 'MsgBody',
			'required' => false,
			'type' => 7,
		),
		self::SENDERID => array(
			'name' => 'SenderId',
			'required' => false,
			'type' => 5,
		),
		self::SENDERNAME => array(
			'name' => 'SenderName',
			'required' => false,
			'type' => 7,
		),
		self::SENDERREMARK => array(
			'name' => 'SenderRemark',
			'required' => false,
			'type' => 7,
		),
		self::SENDERAVATAR => array(
			'name' => 'SenderAvatar',
			'required' => false,
			'type' => 7,
		),
		self::GROUPID => array(
			'name' => 'GroupId',
			'required' => false,
			'type' => 7,
		),
		self::GROUPTYPE => array(
			'name' => 'GroupType',
			'required' => false,
			'type' => 5,
		),
		self::GROUPNAME => array(
			'name' => 'GroupName',
			'required' => false,
			'type' => 7,
		),
		self::GROUPDESC => array(
			'name' => 'GroupDesc',
			'required' => false,
			'type' => 7,
		),
		self::SENDTIME => array(
			'name' => 'SendTime',
			'required' => false,
			'type' => 5,
		),
		self::MSGSEQID => array(
			'name' => 'MsgSeqId',
			'required' => false,
			'type' => 5,
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
		$this->values[self::MSGID] = null;
		$this->values[self::MSGTYPE] = null;
		$this->values[self::MSGBODY] = null;
		$this->values[self::SENDERID] = null;
		$this->values[self::SENDERNAME] = null;
		$this->values[self::SENDERREMARK] = null;
		$this->values[self::SENDERAVATAR] = null;
		$this->values[self::GROUPID] = null;
		$this->values[self::GROUPTYPE] = null;
		$this->values[self::GROUPNAME] = null;
		$this->values[self::GROUPDESC] = null;
		$this->values[self::SENDTIME] = null;
		$this->values[self::MSGSEQID] = null;
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
	 * Sets value of 'MsgId' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setMsgId($value)
	{
		return $this->set(self::MSGID, $value);
	}

	/**
	 * Returns value of 'MsgId' property
	 *
	 * @return string
	 */
	public function getMsgId()
	{
		return $this->get(self::MSGID);
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
	 * Sets value of 'MsgBody' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setMsgBody($value)
	{
		return $this->set(self::MSGBODY, $value);
	}

	/**
	 * Returns value of 'MsgBody' property
	 *
	 * @return string
	 */
	public function getMsgBody()
	{
		return $this->get(self::MSGBODY);
	}

	/**
	 * Sets value of 'SenderId' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setSenderId($value)
	{
		return $this->set(self::SENDERID, $value);
	}

	/**
	 * Returns value of 'SenderId' property
	 *
	 * @return int
	 */
	public function getSenderId()
	{
		return $this->get(self::SENDERID);
	}

	/**
	 * Sets value of 'SenderName' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setSenderName($value)
	{
		return $this->set(self::SENDERNAME, $value);
	}

	/**
	 * Returns value of 'SenderName' property
	 *
	 * @return string
	 */
	public function getSenderName()
	{
		return $this->get(self::SENDERNAME);
	}

	/**
	 * Sets value of 'SenderRemark' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setSenderRemark($value)
	{
		return $this->set(self::SENDERREMARK, $value);
	}

	/**
	 * Returns value of 'SenderRemark' property
	 *
	 * @return string
	 */
	public function getSenderRemark()
	{
		return $this->get(self::SENDERREMARK);
	}

	/**
	 * Sets value of 'SenderAvatar' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setSenderAvatar($value)
	{
		return $this->set(self::SENDERAVATAR, $value);
	}

	/**
	 * Returns value of 'SenderAvatar' property
	 *
	 * @return string
	 */
	public function getSenderAvatar()
	{
		return $this->get(self::SENDERAVATAR);
	}

	/**
	 * Sets value of 'GroupId' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setGroupId($value)
	{
		return $this->set(self::GROUPID, $value);
	}

	/**
	 * Returns value of 'GroupId' property
	 *
	 * @return string
	 */
	public function getGroupId()
	{
		return $this->get(self::GROUPID);
	}

	/**
	 * Sets value of 'GroupType' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setGroupType($value)
	{
		return $this->set(self::GROUPTYPE, $value);
	}

	/**
	 * Returns value of 'GroupType' property
	 *
	 * @return int
	 */
	public function getGroupType()
	{
		return $this->get(self::GROUPTYPE);
	}

	/**
	 * Sets value of 'GroupName' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setGroupName($value)
	{
		return $this->set(self::GROUPNAME, $value);
	}

	/**
	 * Returns value of 'GroupName' property
	 *
	 * @return string
	 */
	public function getGroupName()
	{
		return $this->get(self::GROUPNAME);
	}

	/**
	 * Sets value of 'GroupDesc' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setGroupDesc($value)
	{
		return $this->set(self::GROUPDESC, $value);
	}

	/**
	 * Returns value of 'GroupDesc' property
	 *
	 * @return string
	 */
	public function getGroupDesc()
	{
		return $this->get(self::GROUPDESC);
	}

	/**
	 * Sets value of 'SendTime' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setSendTime($value)
	{
		return $this->set(self::SENDTIME, $value);
	}

	/**
	 * Returns value of 'SendTime' property
	 *
	 * @return int
	 */
	public function getSendTime()
	{
		return $this->get(self::SENDTIME);
	}

	/**
	 * Sets value of 'MsgSeqId' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setMsgSeqId($value)
	{
		return $this->set(self::MSGSEQID, $value);
	}

	/**
	 * Returns value of 'MsgSeqId' property
	 *
	 * @return int
	 */
	public function getMsgSeqId()
	{
		return $this->get(self::MSGSEQID);
	}
}
