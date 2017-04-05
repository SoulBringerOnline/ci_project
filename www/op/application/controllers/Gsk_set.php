<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Gsk_set extends GSK_Controller {

		public function __construct() {
			parent::__construct();
		}

		public function navigation() {
			$data['title'] = '底部导航';
			$data['data'] = $data ;
			$data['content'] = 'gsk/set/navigation';
			$this->load->view('include/layout' , $data );
		}
	}