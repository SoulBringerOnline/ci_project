<?php

	namespace Goose\Scripts;
	use \Libs\Mongo\MongoDB;

	class ActUserNumDailyForSaler extends \Frame\Script {

		public function run() {
			$current_time = date("Y-m-d",time());


			if(isset($this->app->request->arg['arg'][0])) {
				$current_time = $this->app->request->arg['arg'][0];
			}

			$keep_time = strtotime($current_time) - 7*24*60*60;
			$keep_time_end = strtotime($current_time) - 6*24*60*60;
			// 获得所有的注册销售人员
			$mongodb = MongoDB::getMongoDB("test_ol", "gsk_ol");
			$workers = $mongodb->extension_workers->find(array(),array());
			$workers = iterator_to_array($workers);


			//$worker_uins = array_values($worker_uins);
			// 获取worker的uin
			//$invite_users = \Goose\Libs\Util\Util::changeDataKeys($worker_uins,'f_rem_uin');

			$this->app->log->log('script_actuserNumDailyforSaler',"......start");
			// 通过手机号获得7天前 邀请到的 用户  salesperson_flow

			$user_task = array();
			$daus = $this->getDaus($keep_time);
			$d7au_users = array();
			foreach($workers as $worker) {
				$invite_code = $worker['f_invite_code'];
				$worker_phone = $worker['f_phone'];

				$worker_uin=$mongodb->user->find(array("f_phone"=>$worker['f_phone']),array("f_phone","f_uin"));
				$worker_uin  =iterator_to_array($worker_uin);

				$worker_uin = array_values($worker_uin);
				$worker_uin = $worker_uin[0]['f_uin'];
				//$worker_uin = 107376;

				// 当天 邀请过来的用户
				$invite_users = $mongodb->salesperson_flow->find(array("f_sp_uin"=>$worker_uin, "f_flow_time"=>array('$gte'=>$keep_time, '$lt'=> $keep_time_end)), array("f_rem_uin", "f_flow_time"));
				$invite_users = iterator_to_array($invite_users);
				$user_uins=array_map(function($v){return $v["f_rem_uin"];},$invite_users);

				$user_uins = array_values($user_uins);
				//$invite_users = array_map(function($v){ $v[$v['f_rem_uin']] = $v["f_flow_time"] ; return $v;},$invite_users);

				$invite_users = \Goose\Libs\Util\Util::changeDataKeys($invite_users,'f_rem_uin');
				//var_dump($invite_users);
				//  获取这些用户的 账号信息
				$user_infos = $mongodb->user->find(array("f_uin"=>array('$in'=>$user_uins)), array("f_uin","f_create_time","f_last_req_time"));
				$user_infos = iterator_to_array($user_infos);
				// 比较账号信息里边的最后登录时间 和 发邀请码的时间是否 在 大于 1天
				//var_dump($user_infos);
				$arr = array();

				foreach($user_infos as $key=>$info) {
					$info['time'] = strtotime(date("Y-m-d H:i:s", $info["f_last_req_time"] ));
					$invite_users[$info["f_uin"]]['time'] = strtotime(date("Y-m-d H:i:s", $invite_users[$info["f_uin"]]['f_flow_time'] ));
					if($info["time"] - $invite_users[$info["f_uin"]]['time'] > 60*60*24 ) {
						$user_task[$worker_uin][] = array($info,$invite_users[$info["f_uin"]] );
					}

					$create_time = $info["f_create_time"];

					if($this->checkActIn7Days($info["f_uin"],$create_time,  $daus)) {
						$d7au_users[$worker_uin][] = $info;
					}

					$arr[] = array($info, $invite_users[$info["f_uin"]]);
				}

				//var_dump($arr);

				// 手机号  邀请码  count
				$cnt = isset($user_task[$worker_uin]) ? count($user_task[$worker_uin]):0;
				$cnt_d7au  = isset($d7au_users[$worker_uin]) ? count($d7au_users[$worker_uin]):0;
				//var_dump($invite_code, $worker_phone, $keep_time, $cnt);
				//$cnt = isset($user_task[$worker_uin]) &&  ? count($user_task[$worker_uin]):0;
				if($cnt > 0 || $cnt_d7au > 0) {
					$sql = "replace into t_actusernum_sales (`f_uin`,`f_phone`,`f_time`,`num`,`invite_code`,`h7au_num`) values ($worker_uin, '$worker_phone', $keep_time, $cnt, '$invite_code', $cnt_d7au)";
					$params = array();
					var_dump($sql);
					$result = GskDB::getConn()->write($sql, array());
				}
			}

			//var_dump($user_task);
		}
		public function getDaus($keep_time) {
			$start = intval($keep_time/86400);
			$daus = array();

			$redis_gsk = new \Redis();
			$redis_gsk->connect( "10.128.63.250", 6380 );
			for($i=-7; $i<9; $i++) {
				$key = "V_DAU#".($start+$i);
				$daus[$start+$i] = $redis_gsk->sMembers($key);
			}
			$redis_gsk->close();
			return $daus;
		}

		public function checkActIn7Days($uin,$reg_time, &$daus) {
			$cnt = 0;
			$reg_time = intval($reg_time/86400);
			if(!isset($daus[$reg_time])) {
				return false;
			}
			for($i = 0; $i < 7; $i++) {
				if(in_array($uin, $daus[($reg_time + $i)])) {
					$cnt ++;
				}
			}
			if($cnt >= 3) {
				return true;
			}
			else {
				return false;
			}
		}
	}


	class GskDB extends \Libs\DB\DBConnManager {
		const _DATABASE_ = 'gsk_srv';
		static $readretry = 100;
	}