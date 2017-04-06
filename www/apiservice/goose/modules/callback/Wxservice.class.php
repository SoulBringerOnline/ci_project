<?php
/**
 * Created by PhpStorm.
 * User: yaojj-a
 * Date: 2015/12/16
 * Time: 19:37
 */
	namespace Goose\Modules\Callback;
	use \Goose\Package\Outservice\WeChatServer;
	use \Goose\Package\Outservice\WeChatClient;

	class Wxservice extends \Goose\Libs\Wmodule{
		const APP_ID = "wx755a176a07f52614";
		const APP_SECRET = "385bb22c122ec25ae18b15a54c0de6f5";
		const APP_TOKEN ="gsk20151216gsk";

		private $client = null;
		function handle( $postData ){ # ### 这是一个 Hook 相应函数
			// your code here...
		}

		public function run() {
			$this->client = new WeChatClient( self::APP_ID, self::APP_SECRET );

			$svr = new WeChatServer(
				self::APP_TOKEN,  #### 在公众平台设置的 token，用于公众平台接入校验
				array(
					'receiveMsg::text'     => array($this,"onReceiveMsg"),
					//'receiveMsg::location' => handle,
					//'receiveMsg::image'    => function( $postData ){ /* your code here */ }
				)
			);
		}

		public function onReceiveMsg($postData) {
			file_put_contents("/tmp/test_1", print_r($postData, true), FILE_APPEND);
			$from = $postData['from'];
			$this->client->sendTextMsg( $from, "收到你的消息" );

			$this->client->pay($from);
		}

	}


