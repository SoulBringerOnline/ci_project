<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Gsk_user_op extends GSK_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('gsk_user');
		}

		public function user_list() {
			$current_user = $this->session->get_userdata();
			if($current_user['priv'] != 10) {
				$data['content'] = 'gsk/user/error';
				$this->load->view('include/layout' , $data );
				return false;
			}
			$users = $this->gsk_user->get_user_list();
			$user_infos = array();
			foreach($users as $key=>$user) {
				$users[$key]['status'] = Gsk_user::$user_status[$user['status']];
				$users[$key]['group'] = Gsk_user::$user_group[$user['group']];
			}
			$data = array();
			$data['title'] = '用户列表';
			$data['data'] = $users ;
			$data['content'] = 'gsk/user/list';
			$this->load->view('include/layout' , $data );
		}

		public function add_user() {
			$current_user = $this->session->get_userdata();
			if($current_user['priv'] != 10) {
				$data['content'] = 'gsk/user/error';
				$this->load->view('include/layout' , $data );
				return false;
			}
			$data = array();
			$data['title'] = '用户添加';
			$data['data'] = $data ;
			$data['content'] = 'gsk/user/add';
			$this->load->view('include/layout' , $data );
		}

		public function edit_user() {
			$current_user = $this->session->get_userdata();
			if($current_user['priv'] != 10) {
				$data['content'] = 'gsk/user/error';
				$this->load->view('include/layout' , $data );
				return false;
			}
			$account = $_REQUEST['account'] ? $_REQUEST['account'] : "";
			if(empty($account)) {
				//
			}
			$data = $this->gsk_user->get_user_by_account($account);
			$data['title'] = '用户编辑';
			$data['data'] = $data ;
			$data['content'] = 'gsk/user/edit';
			$this->load->view('include/layout' , $data );
		}

		public function do_edit_user() {
			$current_user = $this->session->get_userdata();
			if($current_user['priv'] != 10) {
				$data['content'] = 'gsk/user/error';
				$this->load->view('include/layout' , $data );
				return false;
			}
			//$id = $_REQUEST['id'] ? $_REQUEST['id'] : 0;
			$account = $_REQUEST['account'];
			$priv = intval($_REQUEST['priv']);
			$group = intval($_REQUEST['group']);
			$status = intval($_REQUEST['status']);

			$data = array();

			$account && $data['account'] = $account;
			$priv && $data['priv'] = $priv;
			$group && $data['group'] = $group;
			$status && $data['status'] = $status;

			$this->gsk_user->edit_user($account,$data);
			$ret = array("code"=>0, msg=>"ok");
			echo json_encode($ret);
		}

		public function do_add_user() {
			$current_user = $this->session->get_userdata();
			if($current_user['priv'] != 10) {
				$data['content'] = 'gsk/user/error';
				$this->load->view('include/layout' , $data );
				return false;
			}
			$account = $_REQUEST['account'];
			$priv = intval($_REQUEST['priv']);
			$group = intval($_REQUEST['group']);
			$status = intval($_REQUEST['status']);

			$data = array();

			$account && $data['account'] = $account;
			$priv && $data['priv'] = $priv;
			$group && $data['group'] = $group;
			$status && $data['status'] = $status;
			$this->gsk_user->add_user($data);

			$ret = array("code"=>0, msg=>"ok");
			echo json_encode($ret);
		}

		public function do_delete_user() {
			$current_user = $this->session->get_userdata();
			if($current_user['priv'] != 10) {
				$data['content'] = 'gsk/user/error';
				$this->load->view('include/layout' , $data );
				return false;
			}
			$account = $_REQUEST['account'] ? $_REQUEST['account'] : "";
			$this->gsk_user->delete_user($account);
			$ret = array("code"=>0, msg=>"ok");
			echo json_encode($ret);
		}
	}