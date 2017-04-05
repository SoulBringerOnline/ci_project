<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Newyear_game_PV_data extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			$this->load->model('op_model');
		}

		public function index()
		{
			$this->op_model->newyear_game_pv_data();
		}
	}
?>