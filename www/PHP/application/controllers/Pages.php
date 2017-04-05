<?php
/**
 * Created by PhpStorm.
 * User: lixd-a
 * Date: 2015/8/31
 * Time: 17:57
 */
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Pages extends CI_Controller {

		public function index()
		{
			$data['title'] = '真的可以穿过阿里？';
			$this->load->view('page', $data);
		}
	}
