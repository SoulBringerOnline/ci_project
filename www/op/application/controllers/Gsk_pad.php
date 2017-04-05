<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsk_pad extends GSK_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pad_model');
    }
	
	public function pad()
	{
		$data['title'] = '文档协作';
        $data['data'] = $this->pad_model->get_pads();
		$data['content'] = 'gsk/pad/pad';
		$this->load->view('include/layout' , $data );
	}
}
