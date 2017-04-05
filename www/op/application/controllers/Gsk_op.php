<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsk_op extends GSK_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('op_model');
    }
    public function dau() {
        $data = $this->op_model->getDayActiveUsers();
    }
    public function log()
    {
        $filter = array();
        $filter['cur_page'] =  $_REQUEST['p'];
        $filter['query'] =  $_REQUEST['query'];

        $data = $this->op_model->get_log($filter);
        $map = array();
        foreach ($data['data'] as $index => $item) {
            $key = $item['f_uin'] . '_' . $item['f_time'] . '_' . $item['f_cmd'];
            $map[$key][$item['f_msg_type']] = $index;
        }
        $data['title'] = '系统日志';
        $data['data'] = $data ;
        $data['map'] = $map ;
        $data['content'] = 'gsk/op/log';
        $this->load->view('include/layout' , $data );
    }

    public function flowlog()
    {
        $filter = array();
        $filter['cur_page'] =  $_REQUEST['p'];
        $filter['query'] =  $_REQUEST['query'];

        $data = $this->op_model->get_flowlog($filter);
        $data['title'] = '流量兑换流水信息';
        $data['data'] = $data ;
        $data['content'] = 'gsk/op/liumi_flowlog';
        $this->load->view('include/layout' , $data );
    }

    public function moni()
    {
        $data['content'] = 'include/content';
        $this->load->view('include/layout' , $data );
    }
    public function sqm()
    {
        $data['content'] = 'include/content';
        $this->load->view('include/layout' , $data );
    }

	public function user_info()
	{
		if(isset($_REQUEST['btn_export']))
		{
			$get = null;
			foreach($_REQUEST as $k=>$v)
			{
				$get .= $k.'='.$v.'&';
			}
			$get = rtrim($get, '&' );
			redirect('excel_export/excel_export_user_info/index?'.$get);
		}
		else
		{
			$data = $this->op_model->get_user_info($_REQUEST);
			$data['title'] = '用户个人信息';
			$data['data'] = $data ;
			$data['content'] = 'gsk/op/user_info';
			$this->load->view('include/layout' , $data );
		}
	}

	public function operate_user_points()
	{
		$rsp = array('result' => 'failed' , 'msg' => '');
		if($this->op_model->operate_user_points($_REQUEST) == True){
			$rsp = array('result' => 'ok' , 'msg' => '');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($rsp));
	}

	public function project_info()
	{
		if(isset($_REQUEST['btn_export']))
		{
			$get = null;
			foreach($_REQUEST as $k=>$v)
			{
				$get .= $k.'='.$v.'&';
			}
			$get = rtrim($get, '&' );
			redirect('excel_export/excel_export_proj_info/index?'.$get);
		}
		else
		{
			$data = $this->op_model->get_project_info($_REQUEST);
			$data['title'] = '项目信息';
			$data['data'] = $data ;
			$data['content'] = 'gsk/op/project_info';
			$this->load->view('include/layout' , $data );
		}
	}

	public function project_details()
	{
		$data = $this->op_model->get_project_details($_REQUEST['project_id']);
		$data['title'] = '项目详细信息';
		$data['data'] = $data ;
		$data['content'] = 'gsk/op/project_details';
		$this->load->view('include/layout' , $data );
	}

	public function project_apply()
	{
		$rsp = array('result' => 'failed' , 'msg' => '');
		if($this->op_model->project_apply($_REQUEST) == True){
			$rsp = array('result' => 'ok' , 'msg' => '');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($rsp));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
