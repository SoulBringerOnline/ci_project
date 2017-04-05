<?php
	class Menu_Model extends CI_Model {
		public function __construct() {
			$this->load->database();
		}

		// 获取列表
		public function getMenus() {
			$this->db->select('*');
			$this->db->from('gsk_menu');

			return $this->db->get()->result_array();
		}

		// 获取列表
		public function getMenuById($id) {
			$this->db->select('*');
			$this->db->from('gsk_menu');
			$this->db->where('id',$id);

			return $this->db->get()->result_array();
		}
		// 增加菜单
		public function addMenu($param) {
			$this->db->insert("gsk_menu", $param);
			return true;
		}
		// 删除菜单

		public function deleteMenu($id) {
			$this->db->where('id', $id);
			$this->db->delete('gsk_menu');
			return true;
		}

		// 修改菜单
		public function editMenu($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('gsk_menu', $data);
			return true;
		}
	}
