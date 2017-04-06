<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/11/10
 * Time: 11:34
 */
namespace Goose\Package\User\Helper;

/**
 * PbReportClient message
 */
class PbReportClient extends \ProtobufMessage
{
	/* Field index constants */
	const PHONEINFO = 1;
	const OS = 2;
	const SP = 3;
	const NETWORK = 4;
	const VERSION = 5;
	const CLIENTID = 6;
	const CHANNELID = 7;

	/* @var array Field descriptors */
	protected static $fields = array(
		self::PHONEINFO => array(
			'name' => 'PhoneInfo',
			'required' => false,
			'type' => 7,
		),
		self::OS => array(
			'name' => 'Os',
			'required' => false,
			'type' => 7,
		),
		self::SP => array(
			'name' => 'Sp',
			'required' => false,
			'type' => 7,
		),
		self::NETWORK => array(
			'name' => 'Network',
			'required' => false,
			'type' => 7,
		),
		self::VERSION => array(
			'name' => 'Version',
			'required' => false,
			'type' => 5,
		),
		self::CLIENTID => array(
			'name' => 'ClientId',
			'required' => false,
			'type' => 5,
		),
		self::CHANNELID => array(
			'name' => 'ChannelId',
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
		$this->values[self::PHONEINFO] = null;
		$this->values[self::OS] = null;
		$this->values[self::SP] = null;
		$this->values[self::NETWORK] = null;
		$this->values[self::VERSION] = null;
		$this->values[self::CLIENTID] = null;
		$this->values[self::CHANNELID] = null;
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
	 * Sets value of 'PhoneInfo' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setPhoneInfo($value)
	{
		return $this->set(self::PHONEINFO, $value);
	}

	/**
	 * Returns value of 'PhoneInfo' property
	 *
	 * @return string
	 */
	public function getPhoneInfo()
	{
		return $this->get(self::PHONEINFO);
	}

	/**
	 * Sets value of 'Os' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setOs($value)
	{
		return $this->set(self::OS, $value);
	}

	/**
	 * Returns value of 'Os' property
	 *
	 * @return string
	 */
	public function getOs()
	{
		return $this->get(self::OS);
	}

	/**
	 * Sets value of 'Sp' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setSp($value)
	{
		return $this->set(self::SP, $value);
	}

	/**
	 * Returns value of 'Sp' property
	 *
	 * @return string
	 */
	public function getSp()
	{
		return $this->get(self::SP);
	}

	/**
	 * Sets value of 'Network' property
	 *
	 * @param string $value Property value
	 *
	 * @return null
	 */
	public function setNetwork($value)
	{
		return $this->set(self::NETWORK, $value);
	}

	/**
	 * Returns value of 'Network' property
	 *
	 * @return string
	 */
	public function getNetwork()
	{
		return $this->get(self::NETWORK);
	}

	/**
	 * Sets value of 'Version' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setVersion($value)
	{
		return $this->set(self::VERSION, $value);
	}

	/**
	 * Returns value of 'Version' property
	 *
	 * @return int
	 */
	public function getVersion()
	{
		return $this->get(self::VERSION);
	}

	/**
	 * Sets value of 'ClientId' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setClientId($value)
	{
		return $this->set(self::CLIENTID, $value);
	}

	/**
	 * Returns value of 'ClientId' property
	 *
	 * @return int
	 */
	public function getClientId()
	{
		return $this->get(self::CLIENTID);
	}

	/**
	 * Sets value of 'ChannelId' property
	 *
	 * @param int $value Property value
	 *
	 * @return null
	 */
	public function setChannelId($value)
	{
		return $this->set(self::CHANNELID, $value);
	}

	/**
	 * Returns value of 'ChannelId' property
	 *
	 * @return int
	 */
	public function getChannelId()
	{
		return $this->get(self::CHANNELID);
	}
}
