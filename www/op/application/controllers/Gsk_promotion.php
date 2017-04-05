<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Gsk_promotion extends GSK_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('promotion_model');
		}

		public function audit()
		{
			$data['title'] = '推广人员审核';
			$data['data'] = $this->promotion_model->get_workers($_REQUEST);
			$data['content'] = 'gsk/promotion/promotion_audit';
			$this->load->view('include/layout' , $data );
		}

		public  function change_workers_status()
		{
			$data = $_REQUEST;
			$result = $this->promotion_model->change_workers_status($data);
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
		}

		public function data()
		{
			if(isset($_REQUEST['start_time'])&&isset($_REQUEST['end_time']))
			{
				if(strtotime($_REQUEST['start_time']) > strtotime($_REQUEST['end_time']))
				{
					$url = $url = site_url('Gsk_promotion/data');
					echo "<script>('开始时间不能大于结束时间！');"."location.href='"."$url"."'</script>";
				}
			}

			if(isset($_REQUEST['btn_export']))
			{
				$get = null;
				foreach($_REQUEST as $k=>$v)
				{
					$get .= $k.'='.$v.'&';
				}
				$get = rtrim($get, '&' );
				redirect('excel_export/excel_export_promotion/index?'.$get);
			}
			else
			{
				$data['title'] = '推广统计';
				$data['data'] = $this->promotion_model->get_promotion_info($_REQUEST);
				$data['content'] = 'gsk/promotion/promotion_statistical';
				$this->load->view('include/layout' , $data );
			}
		}
	}
