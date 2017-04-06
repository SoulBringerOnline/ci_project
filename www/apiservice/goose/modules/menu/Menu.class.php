<?php
	namespace Goose\Modules\Navigation;


	class Menu extends \Goose\Libs\Wmodule{
		public function run() {
			$menuInfo = array (
				"button" =>array(
					array (
						"type"=> "click",
						"name"=> "我的" ,
						"key"=> "V1001_MY_OPT" ,
						"sub_button"=> array(
							array(
								"type"=>"click" ,
								"name"=>"常见问题" ,
								"key"=>"V1001_USUAL_PROBLEM" ,
								"sub_button"=>array ()
							)
						)
					),
					array (
						"type"=> "url",
						"name"=> "美丽精选" ,
						"key"=> "V1001_FIND_NEW" ,
						"url" =>"http://open.show.qq.com/cgi-bin/login_state_auth_redirect?appid=210915&redirect_uri=http%3A%2F%2Fmobapi.meilishuo.com%2F2.0%2Fqq%2Fauth%3Fredirect%3Dhttp%253A%252F%252Fm.meilishuo.com%252Fsq%252Fmall%252Fclothes%253Ffrom%253Dqqservice&_wv=1027 " ,
						"sub_button"=> array()
					),
					array (
						"name"=> "每日团购" ,
						"type"=> "url",
						"key"=> "V1001_TODAY_GOODS" ,
						"url" =>"http://open.show.qq.com/cgi-bin/login_state_auth_redirect?appid=210915&redirect_uri=http%3A%2F%2Fmobapi.meilishuo.com%2F2.0%2Fqq%2Fauth%3Fredirect%3Dhttp%253A%252F%252Fm.meilishuo.com%252Fsq%253ffrom%253dqqservice&_wv=1027" ,
						"sub_button"=> array()
					),
				),
				"action" =>array(
					"event" =>"NUMBER_TIP_EVENT",
					"url" =>"http://api.zy.glodon.com/a/b"
				)
			);

			$this->response->make_json_ok("", $menuInfo);
		}
	}