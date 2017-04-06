<?php
	/**
	 * 返回上一天的每个时段的注册用户数
	 */
	namespace Goose\Scripts;

	use \Libs\Mongo\MongoDB;
	class UserRegisterNum extends \Frame\Script {

		public function run() {
			$mongodb = MongoDB::getMongoDB("online", "gsk_ol");

			$current_time = date("Y-m-d",strtotime("-1 day"));

			$start_time = strtotime($current_time);
			$end_time = $start_time + 24*60*60;

			$output = "";
			//for($i = 0; $i<23; $i++) {
				$reg_users_hour = $mongodb->user->find(array("f_create_time"=>array('$gte'=>$start_time, '$lt'=>$end_time)),array("f_uin","f_phone","f_name","f_company","f_create_time","f_last_req_time"))->sort(array("f_uin"=>1));
				$reg_users_hour = iterator_to_array($reg_users_hour);


			foreach($reg_users_hour as $user) {
				$user['f_create_time'] = date("Y-m-d",$user['f_create_time']);
				$user['f_last_req_time'] = date("Y-m-d",$user['f_last_req_time']);

				$output .= implode(",",$user)."\n";
			}
			//}

			$file_name = "/tmp/register_users_".$current_time;
			@file_put_contents($file_name, $output, FILE_APPEND);
		}
	}