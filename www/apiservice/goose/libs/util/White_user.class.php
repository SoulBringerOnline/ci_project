<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/10/30
 * Time: 17:26
 */

namespace Goose\Libs\Util;


class White_user {

	private  static $white_user = array( "18106529696" ,"15201189594", "13810245007","18310797560","18740503813",
							"15633168106","18513953360", "18670358095","18519100579","13581747678", "13681172962",
							"13426314687", "18504391766", "13732254159", "18601989066", "15957132529", "18811179323",
							"18813048857", "13241784250", "18519698398", "18758127876", "18613373227");
	public static function white_user_sms($phone)
	{
		return in_array($phone, self::$white_user);
	}

} 