<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsk_stat extends GSK_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('op_model');
        $this->load->model('gsk_model');
	    $this->output->cache(5);
    }
	
	public function stat()
	{
		$data['title'] = '系统日志';
		foreach (array(1,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,99,500,501,502,503,504,505,506,507,508,509,510,511,512,513,514,515,516,517,518,519,520,521,522,523,800,997,998,1000) as $channel) {
        	$data['stats'][$channel] = $this->op_model->get_dash_stat($channel);
		}
		$data['channel'] = $this->gsk_model->get_channel();
		$data['content'] = 'gsk/stat/stat';
		$this->load->view('include/layout' , $data );
	}

	public function stat_PV()
	{
		$data['title'] = 'PV数据';
		$data['data'] = $this->op_model->get_PV_data();
		$data['content'] = 'gsk/stat/stat_PV';
		$this->load->view('include/layout' , $data );
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
