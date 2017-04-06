<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/12/25
 * Time: 16:28
 */
	namespace Goose\Modules\Callback;
	use \Goose\Package\Oauth\QzoneOauth;
	class Qqauth extends \Goose\Libs\Wmodule{

		public function run() {
			$aKey = "1104837198";
			$sKey = "VAarLytF8blLLarp";
			$callbackUrl = "http://api.zy.glodon.com/2.0/a/b";
			$type = 'code';
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$state = NULL;

			$o = new QzoneOauth($aKey, $sKey, NULL, NULL, "");
			$time_start = microtime();
			$accessKeys = $o->getAccessToken($callbackUrl, $type, $keys, $state);
			/*
			 * <code>
			 *    Array (
			 * 	      [access_token] => C77B688321C4B99A1F4314C0F56E4470
			 *        [expires_in] => 7776000
			 *    )
			 * </code>
			 */
			$params['qzone_access_keys'] = $accessKeys;
			$openIdKeys = $o->getOpenId();
			$time_end = microtime();

			var_dump($openIdKeys);
		}
	}