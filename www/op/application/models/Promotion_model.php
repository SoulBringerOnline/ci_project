<?php
	class Promotion_Model extends CI_Model {
		public function __construct() {
			$this->connection_op = new MongoClient($this->config->item('mongodb_op'));
			$this->mongo_op = new MongoDB($this->connection_op, 'gsk');

			$this->connection_ol = new MongoClient($this->config->item('mongodb_gsk_ol'));
			$this->mongo_ol = new MongoDB($this->connection_ol, 'gsk_ol');

			$this->load->database();
		}

		function __destruct() {
			$this->connection_op->close();
			$this->connection_ol->close();
		}

		public  function get_workers($filter = array())
		{
			$p = $f = $list = array();

			$f['order_fd']   = 'f_status';
			$f['order_type'] = 1;

			/* 设置分页信息 */
			$rows_page = 50;
			$p['cur_page'] = isset($filter['p']) ? intval($filter['p']) : 1;
			$p['rows_page']  = $rows_page;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];
			$where = array( );
			if(!empty($filter['f_worker_name']))
			{
				$where['f_name'] =  trim($filter['f_worker_name']);
			}
			if(!empty($filter['f_worker_phone']))
			{
				$where['f_phone'] = trim($filter['f_worker_phone']);
			}
			/* 列表对象赋值 */
			$list['data']  = iterator_to_array( $this->mongo_ol->extension_workers->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] )->sort( array( $f['order_fd'] => $f['order_type'] ) ));
			$p['max'] = array(
				'max_page'=>ceil($this->mongo_ol->extension_workers->count($where)/$rows_page),
				'max_count'=>$this->mongo_ol->extension_workers->count($where)
			);
			$list['filter'] = $filter;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		public function change_workers_status($data)
		{
			$this->mongo_ol->extension_workers->update(array('f_phone'=>$data['f_phone']), array('$set'=>array('f_status'=>$data['f_status'])));
			if($data['f_status'] == "1")//审核通过
			{
				$this->mongo_ol->user->update(array('f_phone'=>$data['f_phone']), array('$set'=>array('f_user_type'=>"2")));
			}
			else//未通过
			{
				$this->mongo_ol->user->update(array('f_phone'=>$data['f_phone']), array('$set'=>array('f_user_type'=>"1")));
			}

			return true;
		}

		public function save_register($data)
		{
			$rsp = array('result' => true , 'msg' => '注册成功，请等待审批.');
			if(!is_null($data))
			{
				$count = $this->mongo_ol->extension_workers->find(array('f_phone'=>$data['f_phone']))->count();
				if($count != 0)
				{
					$rsp = array('result' => false , 'msg' => '该手机号已被注册！');
				}
				else
				{
					//先到user表中找到用户手机号对应的邀请码，没绑定手机号就没办法了
					$user = $this->mongo_ol->user->findOne(array('f_phone'=>$data['f_phone']), array('f_code_id'=>1, 'f_uin'=>1));
					if(is_null($user))
					{
						$rsp = array('result' => false , 'msg' => '请先用该手机号注册筑友APP！');
					}
					else if(empty($user['f_code_id'])||!isset($user['f_code_id']))
					{
						$rsp = array('result' => false , 'msg' => '该账号还无邀请码，请联系运营人员！');
					}
					else
					{
						$data['f_uin'] = $user['f_uin'];
						$data['f_invite_code'] = "yq&".$user['f_code_id'];
						$data['f_type'] ='';
						$data['f_status'] = 0;
						$this->mongo_ol->extension_workers->insert($data);
					}
				}
			}
			return $rsp;
		}

		//推广人员登录
		public function signin($data)
		{
			//临时用推广员的手机号当做账号、密码，以后数据多了，再完善
			$rsp = array('result' => true , 'msg' => '');
			$count = $this->mongo_ol->extension_workers->count(array('f_phone'=>$data['signin_username']));
			if($count == 0)
			{
				$rsp = array('result' => false , 'msg' => '用户不存在！');
			}
			else
			{
				$msg = $this->mongo_ol->extension_workers->findOne(array('f_phone'=>$data['signin_username']), array('f_phone'=>1, 'f_status'=>1));
				if($msg['f_phone'] == $data['signin_password'])
				{
					if($msg['f_status'] == "0")
					{
						$rsp = array('result' => false , 'msg' => '该账号正在审核中。。。！可以联系运营人员！');
					}
					else if($msg['f_status'] == "-1")
					{
						$rsp = array('result' => false , 'msg' => '该账号审核未通过！');
					}
					else
					{
						$rsp = array('result' => true , 'msg' => '登陆成功！');
					}

				}
				else
				{
					$rsp = array('result' => false , 'msg' => '密码错误！');
				}
			}

			return $rsp;
		}

		//获得推广人员的详细记录
		public function get_promotion_info($filter, $is_export_excel = false)
		{
			$p = $f = $list = array();

			$f['order_fd']   = '';
			$f['order_type'] = -1;

			/* 设置分页信息 */
			$rows_page = 25;
			$p['cur_page'] = isset($filter['p']) ? intval($filter['p']) : 1;
			$p['rows_page']  = $rows_page;
			$p['row_start']  = ($p['cur_page']-1) * $p['rows_page'];

			$where = array( );
			if(!empty($filter['f_worker_name']))
			{
				$where['f_name'] = $filter['f_worker_name'];
			}
			if(!empty($filter['f_worker_code']))
			{
				$where['f_invite_code'] = $filter['f_worker_code'];
			}
			if(!empty($filter['f_worker_phone']))
			{
				$where['f_phone'] = $filter['f_worker_phone'];
			}

			if(!isset($filter['start_time']) || !isset($filter['end_time']))
			{
				$filter['start_time'] = date("Y-m-d",strtotime("-1 day"));
				$filter['end_time'] = date("Y-m-d");
			}
			//导出excel需要所有数据，不需设置分页
			if($is_export_excel)
			{
				$workers = iterator_to_array( $this->mongo_ol->extension_workers->find($where));
			}
			else
			{
				$workers = iterator_to_array( $this->mongo_ol->extension_workers->find($where)->limit( $p['rows_page'] )->skip( $p['row_start'] ));
			}
			$workers = array_values($workers);
			$result = array();
			$day_count = 0;
			for($start=strtotime($filter['end_time']); $start >= strtotime($filter['start_time']);$start -= 86400)  //按天遍历
			{
				$day_count ++;
				$temp = $workers;
				foreach($temp as $key=>$worker)
				{
					$condition = array(
						'f_sp_uin' => $worker['f_uin'],
						'f_flow_time' => array('$gte' => $start,'$lt' => $start + 86400)
					);
					$temp[$key]['f_date'] = $start;
					$temp[$key]['f_pro_count'] = $this->mongo_ol->salesperson_flow->count($condition);
					//有效用户的数据使用脚本统计的，需要从mysql数据库中统计
					$sql = "SELECT num, h7au_num FROM t_actusernum_sales WHERE f_uin = ? AND f_time = ?";
					$msg = $this->db->query($sql, array($worker['f_uin'], $start))->row_array();
					$temp[$key]['f_valid_count'] = $msg['num'];
					$temp[$key]['f_h7au_num'] = $msg['h7au_num'];
				}
				$result = array_merge($result, $temp);
			}

			/* 列表对象赋值 */
			$list['data']  = $result;
			$max_count = $this->mongo_ol->extension_workers->count($where) * $day_count;
			$p['max'] = array(
				'max_count'=>$max_count,
				'max_page'=>ceil($this->mongo_ol->extension_workers->count($where)/$rows_page)
			);
			$list['filter'] = $filter;
			$list['page'] = $p;

			/* 返回 */
			return $list;
		}

		public function get_worker_personal_info($query)
		{
			$result = null;
			$data = $this->mongo_ol->extension_workers->find($query);
			foreach($data as $d)
			{
				$result = $d;
			}
			return $result;
		}
	}