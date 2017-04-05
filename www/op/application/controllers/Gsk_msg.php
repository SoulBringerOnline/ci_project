<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsk_msg extends GSK_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('msg_model');
    }
	
    public function msg_list() {
    	$filter = array();
		$filter['cur_page'] = $_REQUEST['p'];
		$filter['query'] =  $_REQUEST['query'];

		$data = $this->msg_model->get_msg($filter);
		$data['title'] = '消息列表';
        $data['data'] = $data ;
		$data['content'] = 'gsk/msg/list';
		$this->load->view('include/layout' , $data );
    }
    
	public function msg_add() {
		if ($this->input->post()) {
			if ($this->msg_model->get_msg_by_baseid($_REQUEST['baseid'])) {
				exit("BaseId已存在");
			}
			$BaseSystemMsg = array();
			if ($_REQUEST['type'] == 12) {
				$MsgCard = array();
				$MsgCard['f_card_img'] = $_REQUEST['f_card_img'];
				$MsgCard['f_card_title'] = $_REQUEST['f_card_title'];
		        $MsgCard['f_card_desc'] = $_REQUEST['f_card_desc'];
		        $MsgCard['f_card_atction'] = $_REQUEST['f_card_atction'];
		        $MsgCard['f_card_frominfo'] = $_REQUEST['f_card_frominfo'];
		        $MsgCard['f_card_finish'] = $_REQUEST['f_card_finish'];
		        $MsgCard['f_card_finish_action'] = $_REQUEST['f_card_finish_action'];
		        $MsgCard['f_card_extrc'] = $_REQUEST['f_card_extrc'];
		        
		        $BaseSystemMsg['f_msgcard'] = $MsgCard;
			}
			$BaseSystemMsg['f_msgflag'] = intval($_REQUEST['msgflag']);
	        $BaseSystemMsg['f_baseid'] = $_REQUEST['baseid'];
	        $BaseSystemMsg['f_begintime'] = strtotime($_REQUEST['begintime']);
	        $BaseSystemMsg['f_finishtime'] = strtotime($_REQUEST['finishtime']);
	        $BaseSystemMsg['f_type'] = intval($_REQUEST['type']);
	        $BaseSystemMsg['f_msginfo'] = $_REQUEST['msginfo'];
			if(trim($_REQUEST['joinChannels'])){
				$BaseSystemMsg['f_join_channels'] = trim($_REQUEST['joinChannels']);
			} else {
				$BaseSystemMsg['f_join_channels'] = null;
			}
			
	        $this->msg_model->add_msg($BaseSystemMsg);
	        
	        redirect('/gsk_msg/msg_list');
		} else {
			$data['title'] = '添加消息';
			$data['content'] = 'gsk/msg/add';
			$this->load->view('include/layout', $data);
		}
	}
	
	public function msg_edit() {
		if ($this->input->post()) {
			$_baseid = $_REQUEST['baseid'];
			$BaseSystemMsg = array();
			if ($_REQUEST['type'] == 12) {
				$MsgCard = array();
				$MsgCard['f_card_img'] = $_REQUEST['f_card_img'];
				$MsgCard['f_card_title'] = $_REQUEST['f_card_title'];
		        $MsgCard['f_card_desc'] = $_REQUEST['f_card_desc'];
		        $MsgCard['f_card_atction'] = $_REQUEST['f_card_atction'];
		        $MsgCard['f_card_frominfo'] = $_REQUEST['f_card_frominfo'];
		        $MsgCard['f_card_finish'] = $_REQUEST['f_card_finish'];
		        $MsgCard['f_card_finish_action'] = $_REQUEST['f_card_finish_action'];
		        $MsgCard['f_card_extrc'] = $_REQUEST['f_card_extrc'];
		        
		        $BaseSystemMsg['f_msgcard'] = $MsgCard;
			}
			$BaseSystemMsg['f_msgflag'] = intval($_REQUEST['msgflag']);
	        $BaseSystemMsg['f_baseid'] = $_REQUEST['baseid'];
	        $BaseSystemMsg['f_begintime'] = strtotime($_REQUEST['begintime']);
	        $BaseSystemMsg['f_finishtime'] = strtotime($_REQUEST['finishtime']);
	        $BaseSystemMsg['f_type'] = intval($_REQUEST['type']);
	        $BaseSystemMsg['f_msginfo'] = $_REQUEST['msginfo'];
	        $BaseSystemMsg['f_joinChannels'] = $_REQUEST['joinChannels'];
			
	        $this->msg_model->edit_msg($BaseSystemMsg, $_baseid);
	        
	        redirect('/gsk_msg/msg_list');
		} else {
			$baseid = $_REQUEST['baseid'];
			$data = $this->msg_model->get_msg_by_baseid($baseid);
			$data['title'] = '编辑消息';
			$data['data'] = $data;
			$data['content'] = 'gsk/msg/edit';
			$this->load->view('include/layout', $data);
		}
	}
	
	public function msg_send() {
		$baseid = $_REQUEST['baseid'];
		$data = $this->msg_model->get_msg_by_baseid($baseid);
		$data['title'] = '发送消息';
		$data['data'] = $data;
		$data['content'] = 'gsk/msg/send';
		$this->load->view('include/layout', $data);
	}
	
	public function msg_del() {
		$id = $_REQUEST['id'];
		$this->msg_model->del_Msg($id);
		
		$return = array('state'=>1);
		echo json_encode($return);
	}

	public function do_upload($file="f_card_img")
    {
    	$dir = BASEPATH.'../upload/';
    	$file_name = date("Ymd").'_'.time().'.png';

        $config['upload_path'] = $dir;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 5000;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $config['file_name'] = $file_name;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($file)) {
            $error = array('error' => $this->upload->display_errors());

            return false;
        }

        $data = $this->upload->data();
        return base_url()."upload/".$data['orig_name'];
    }

}