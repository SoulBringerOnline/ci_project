<?php
	namespace Goose\Modules\Navigation;


	class Menu extends \Goose\Libs\Wmodule{
		public function run() {
			$menuInfo = array (
				"button" =>array(
					array (
						"type"=> "url",
						"name"=> "创建提料单" ,
						"key"=> "V1_BILL_MATERIAL_CREATE" ,
						"url" =>"http://www.weibo.com" ,
						"sub_button"=> array()
					),
					array (
						"type"=> "click",
						"name"=> "我的提料单" ,
						"key"=> "V1_BILL_MATERIAL_MY" ,
						"url" =>"" ,
						"sub_button"=> array(
								array(
										"type"=> "url",
										"name"=> "创建的" ,
										"key"=> "V1_BILL_MATERIAL_MY_CREATED" ,
										"url" =>"http://www.weibo.com" ,
										"sub_button"=> array()
								),
								array(
										"type"=> "url",
										"name"=> "未处理的" ,
										"key"=> "V1_BILL_MATERIAL_MY_UNTREATED" ,
										"url" =>"http://www.weibo.com" ,
										"sub_button"=> array()
								),
						)
					),
					array (
						"name"=> "全部提料单" ,
						"type"=> "click",
						"key"=> "V1_BILL_MATERIAL_ALL" ,
						"url" =>"" ,
						"sub_button"=> array(
								array(
										"type"=> "url",
										"name"=> "已入库" ,
										"key"=> "V1_BILL_MATERIAL_ALL_IN_STORAGE" ,
										"url" =>"http://www.weibo.com" ,
										"sub_button"=> array()
								),
								array(
										"type"=> "url",
										"name"=> "全部" ,
										"key"=> "V1_BILL_MATERIAL_ALL_SUBALL" ,
										"url" =>"http://www.weibo.com" ,
										"sub_button"=> array()
								),
						)
					),
				),
				"action" =>array(
					"event" =>"NUMBER_TIP_EVENT",
					"url" =>"http://api.zhuyou.glodon.com/2.0/test/point"
				)
			);

			$this->response->make_json_ok("", $menuInfo);
		}
	}
