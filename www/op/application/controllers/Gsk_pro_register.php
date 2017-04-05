<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Gsk_pro_register extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('promotion_model');
		}

		public  function  workers_register()
		{
			$data='';
			$this->load->view('gsk/promotion/extension_workers_register' , $data );
		}

		public function submit_register()
		{
			$name =  $_REQUEST['name'];
			$IDcard =  $_REQUEST['IDcard'];
			$phone =  $_REQUEST['phone'];
			$rsp = array();
			if(isset($_REQUEST['submit']))
			{
				if(empty($name)||empty($IDcard)||empty($phone))
				{
					$rsp['msg'] = '部分字段不能为空！';
				}
				else
				{
					$data = array(
						'f_name' => $name,
						'f_IDcard' => $IDcard,
						'f_phone' => $phone
					);
					$rsp = $this->promotion_model->save_register($data);
				}

				$url = site_url('Gsk_pro_register/workers_register');
				echo "<script>('".$rsp['msg']."');"."location.href='"."$url"."'</script>";
			}
			else
			{
				redirect('/main/signin');
			}
		}

		public  function  workers_query()
		{
			$data['title'] ="推广人员信息查询" ;
			$query = array('f_phone'=>$this->session->userdata['signin_username']);
			$data['data'] = $this->promotion_model->get_worker_personal_info($query);
			if($data['data'] == false)
			{
				redirect('/main/signin');
			}
			$this->load->view('gsk/promotion/promotion_query' , $data );
		}

		public function logout()
		{
			$this->session->sess_destroy();
			redirect('/main/signin');
		}
	}
