<?php
	class GSK_User extends CI_Model {

		public static $user_status = array(
			0=>"正常",
			-1=>"封禁"
		);

		public static $user_group = array(
			1=>"管理员",
			2=>"运营",
			3=>"产品",
			4=>"开发",
		);
		public function __construct() {
			$this->load->database();
		}

		public function get_user_list($param=array()) {
			$this->db->select('*');
			$this->db->from('t_user_priv_list');
			!empty($param) && $this->db->where($param);

			return $this->db->get()->result_array();
		}

		public function get_user_by_account($account) {
			$param = array('account'=>$account);
			$this->db->select('*');
			$this->db->from('t_user_priv_list');
			$this->db->where($param);

			$user_info = $this->db->get()->result_array();

			return $user_info[0];
		}


		public function add_user($param) {
			$this->db->insert("t_user_priv_list", $param);
			return true;
		}

		public function edit_user($account, $data) {
			$this->db->where('account', $account);
			$this->db->update('t_user_priv_list', $data);
			return true;
		}

		public function delete_user($account) {
			$this->db->where('account', $account);
			$this->db->delete('t_user_priv_list');
			return true;
		}

	}