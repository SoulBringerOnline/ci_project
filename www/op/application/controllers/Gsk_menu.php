<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gsk_menu extends GSK_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('menu_model');
	}
	public function menu_list() {
		$data = $this->menu_model->getMenus();
		$data = $this->rebuildmenu($data);
		$data = array('data'=>$data);
		$this->load->view('menu/list', $data);
	}

	public function menu_edit() {
		$id = $_REQUEST['id'] ? $_REQUEST['id'] : 0;
		$data = $this->menu_model->getMenuById($id);
		$data = $data[0];
		$data = array('data'=>$data);
		$this->load->view('menu/edit', $data);
	}

	public function do_edit_menu() {
		$id = $_REQUEST['id'] ? $_REQUEST['id'] : 0;
		$title = $_REQUEST['title'];
		$parent_id = intval($_REQUEST['parent_id']);
		$type = intval($_REQUEST['type']);
		$priv_level = intval($_REQUEST['priv_level']);
		$url = ($_REQUEST['url']);
		$url_code = md5(($_REQUEST['url']));
		$order = intval($_REQUEST['order']);
		$icon = ($_REQUEST['icon']);

		$data = array(
		);

		$title && $data['title'] = $title;
		$parent_id && $data['parent_id'] = $parent_id;
		$type && $data['type'] = $type;
		$priv_level && $data['priv_level'] = $priv_level;
		$url && $data['url'] = $url;
		$url_code && $data['url_code'] = $url_code;
		$order && $data['order'] = $order;
		$icon && $data['icon'] = $icon;
		$this->menu_model->editMenu($id,$data);
	}

	private function rebuildmenu($menus, $parent_id=0) {
		$sort_menus = array();
		foreach($menus as $menu) {
			if($menu['parent_id'] == $parent_id) {
				$menu['sub'] = $this->rebuildmenu($menus, $menu['id']);
				$sort_menus[] = $menu;
			}
		}
		return $sort_menus;
	}

	public function add_menu() {
		$data = $this->menu_model->getMenus();
		$data = array('data'=>$data);
		$this->load->view('menu/add', $data);
	}

	public function do_add_menu() {
		$title = $_REQUEST['title'];
		$parent_id = intval($_REQUEST['parent_id']);
		$type = intval($_REQUEST['type']);
		$priv_level = intval($_REQUEST['priv_level']);
		$url = ($_REQUEST['url']);
		$url_code = md5(($_REQUEST['url']));
		$order = intval($_REQUEST['order']);
		$icon = ($_REQUEST['icon']);

		$data = array(
		);

		$title && $data['title'] = $title;
		$parent_id && $data['parent_id'] = $parent_id;
		$type && $data['type'] = $type;
		$priv_level && $data['priv_level'] = $priv_level;
		$url && $data['url'] = $url;
		$url_code && $data['url_code'] = $url_code;
		$order && $data['order'] = $order;
		$icon && $data['icon'] = $icon;
		$this->menu_model->addMenu($data);
	}
}