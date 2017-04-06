<?php
namespace Goose\Libs\Util;


class Util extends \Libs\Util\Utilities  {

	const TYPE_ENC = 1; // #加密
	const TYPE_DEC = 2; // #解密

    public static function getUniqueId() {
        return md5(uniqid(mt_rand(), TRUE) . $_SERVER['REQUEST_TIME'] . mt_rand());
    }

    public static function DataToArray($dbData, $keyword, $allowEmpty = FALSE) {
        $retArray = array ();
        if (is_array ( $dbData ) == false or empty ( $dbData )) {
            return $retArray;
        }
        foreach ( $dbData as $oneData ) {
            if (isset ( $oneData [$keyword] ) and empty ( $oneData [$keyword] ) == false or $allowEmpty) {
                $retArray [] = $oneData [$keyword];
            }
        }
        return $retArray;
    }

    public static function changeDataKeys($data, $keyName, $toLowerCase=false) {
        $resArr = array ();
        if(empty($data)){
            return false;
        }
        foreach ( $data as $v ) {
            $k = $v [$keyName];
            if( $toLowerCase === true ) {
                $k = strtolower($k);
            }
            $resArr [$k] = $v;
        }
        return $resArr;
    }

    public static function sortArray($array, $order_by, $order_type = 'ASC') {
        if (!is_array($array)) {
            return array();
        }
        $order_type = strtoupper($order_type);
        if ($order_type != 'DESC') {
            $order_type = SORT_ASC;
        } else {
            $order_type = SORT_DESC;
        }

        $order_by_array = array ();
        foreach ( $array as $k => $v ) {
            $order_by_array [] = $array [$k] [$order_by];
        }
        array_multisort($order_by_array, $order_type, $array);
        return $array;
    }

    public static function nginx_userid_decode($str) {
        $str_unpacked = unpack('h*', base64_decode(str_replace(' ', '+', $str)));
        $str_split = str_split(current($str_unpacked), 8);
        $str_map = array_map('strrev', $str_split);
        $str_dedoded = strtoupper(implode('', $str_map));

        return $str_dedoded;
    }

	public static function split_str($str)
	{
		$result = array();
		$tempdata = explode("&", $str);
		foreach($tempdata as $data)
		{
			list($key, $value) = explode("=", $data);
			$result[$key] = $value;
		}
		return $result;
	}

	public static function client_ip(){
		$ip = getenv('REMOTE_ADDR');
		$ip1 = getenv('HTTP_X_FORWARDED_FOR');
		$ip2 = getenv('HTTP_CLIENT_IP');
		$ip1 ? $ip = $ip1 : '';
		$ip2 ? $ip = $ip2 : '';
		return $ip;
	}


	/**
	 * gsk通用的加密算法
	 * @param $key
	 * @param $info
	 * @return mixed
	 */
	public static function gsk_encrypt($key,$origin) {
		//
		$type = self::TYPE_ENC;
		$body = self::packGskBody($type, $key,$origin);
		$des = self::getNetData($body);
		$des = self::unpackGskBody($des);
		return $des;
	}

	/**
	 * gsk 通用的解密算法
	 * @param $key
	 * @param $des
	 * @return mixed
	 */
	public static function gsk_decrypt($key, $des) {
		$type = self::TYPE_DEC;
		$body = self::packGskBody($type, $key, $des);
		// 发udp包
		$origin = self::getNetData($body);
		$origin = self::unpackGskBody($origin);
		return $origin;
	}

	private static function packGskBody($type, $key, $str) {
		//STX(0x2) + TLV(1,4,type) + TLV(2,len(key),key) + TLV(3,len(msg),msg) + ETX(0x3)
		$body = "";
		$body .= pack("C",2);
		$body .= pack("CnN", 1, 4, $type);
		$len = strlen($key);
		$body .= pack("Cn", 2, $len);
		$body .= $key;
		$len = strlen($str);
		$body .= pack("Cn", 3, $len);
		$body .= $str;
		$body .= pack("C",3);
		return $body;
	}

	private static function unpackGskBody($str) {
		// STX(0x2) + msg + ETX(0x3)
		$start = 0;
		$len = strlen($str);
		$getStr = substr($str,1,$len-2);

		//$getStr = substr($getStr, 1, -1);
		return $getStr;
	}

	private static function getNetData($data) {
		$ip = "192.168.164.200";
		$port = 10000;
		$errno = 0;
		$errstr = '';
		$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

		$sendTimeOutSec = 0;
		$sendTimeOutUsec = 500000; //500毫秒
		$recvTimeOutSec = 0;
		$recvTimeOutUsec = 500000; //500毫秒

		//设置发送数据超时时间
		socket_set_option($sock,SOL_SOCKET,SO_SNDTIMEO, array('sec' => $sendTimeOutSec, 'usec' => $sendTimeOutUsec));
		//设置接收数据超时时间
		socket_set_option($sock,SOL_SOCKET,SO_RCVTIMEO, array('sec' => $recvTimeOutSec, 'usec' => $recvTimeOutUsec));

		$sendLen = socket_sendto($sock, $data, strlen($data), 0, $ip , $port);

		if ($sendLen == -1) {
			// send 失败
			$ret = FALSE;
		}
		else {
			$rspBuffer = "";
			$from = "";
			$port = 0;
			socket_recvfrom($sock, $ret, 1024, 0, $from, $port);
		}


		socket_close($sock);
		return $ret;
	}

	//发送http get方式请求
	public static function send_request_get($url)
	{
		if(empty($url)) exit;

		//初始化
		$ch = curl_init();

		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		//执行并获取HTML文档内容
		$output = curl_exec($ch);

		//释放curl句柄
		curl_close($ch);

		return $output;
	}


	public static function dumpBuffer($buffer)
	{
		$len = strlen($buffer);
		$retStr = "buffer len:{$len}\n";
		for($i=0;$i<$len;$i++)
		{
			$retStr .= sprintf("%02x",ord($buffer[$i]));
			if(($i+1)%16 == 0)
			{
				$retStr .= "\n";
			}else if(($i+1)%2 == 0)
			{
				$retStr .= " ";
			}
		}
		$retStr .= "\n";
		return $retStr;
	}
}
