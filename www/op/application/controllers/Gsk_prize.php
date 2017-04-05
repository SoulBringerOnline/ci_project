<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class  Gsk_prize extends GSK_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(prize_model);
    }
    public  function prize_detail()//奖品明细管理
    {
        if($_GET['insert_jingdong'])
        {


        }
        else
        {
            $data['content'] = 'gsk/prize/detail';
            $data['title'] = '奖品明细管理';
            $data['list'] = $this->prize_model->get_all_detail($_REQUEST);
            $url = $_SERVER['_SERVER["PATH_INFO"]'];
            $params = preg_replace("/[\?&]?page=\d+/", "", $_SERVER['QUERY_STRING']);
            if($params){
                $url .= "?".$params."&page=p%";
            } else {
                $url .= "?page=p%";
            }
            $data['url'] = $url;
            //$return['url'] = $url;
            $this->load->view('include/layout' , $data );

        }

    }

    public function print_record()//奖品领取记录
    {
        if(isset($_REQUEST['btn_export']))
        {
            $get = null;
            foreach($_REQUEST as $key=>$val)
            {
                $get.=$key.'='.$val.'&';

            }
            $get = rtrim($get,'&');
            redirect('excel_export/excel_export_prize_record/index?'.$get);

        }
        else
        {//$filter = array();
            $data['content'] = 'gsk/prize/category';
            $data['title'] = '奖品领取记录';
            $data['list'] = $this->prize_model->get_all_prize($_REQUEST);
            $url = $_SERVER['_SERVER["PATH_INFO"]'];
            $params = preg_replace("/[\?&]?page=\d+/", "", $_SERVER['QUERY_STRING']);
            if($params){
                $url .= "?".$params."&page=p%";
            } else {
                $url .= "?page=p%";
            }
            $data['url'] = $url;
            //$return['url'] = $url;
            $this->load->view('include/layout' , $data );
        }
        }
   /* public function print_record()
    {
        $data = $this->prize_model->get_all_prize($_REQUEST);
        foreach($data as $item){
           print_r($item);
        }

    }*/
    public  function show_manage()//奖品管理
    {
        $data['content'] = 'gsk/prize/manage';
        $data['title'] = '奖品管理';
        $data['list'] = $this->prize_model->get_manage();
        $this->load->view('include/layout' , $data );
    }
public function  prize_change()
    //编辑更新
{
    $prize_id = $this->input->get();
    //$tmp = array();
    $tmp["prizeId"] = strval($prize_id["prizeId"]);
    $result=$this->prize_model->get_manage($tmp);
    $return = array();
    $return["prizeId"] = $tmp["prizeId"];
    foreach($result as $val) {
        $return['f_num'] = $val['f_num'] > 0 ? $val['f_num'] : 0;
        $return['f_origin_total'] = $val['f_origin_total'];
        $return['f_prize_name'] = $val['f_prize_name'];
        $return['f_prize_desc'] = $val['f_prize_desc'];
        $return['f_prize_img'] = $val['f_prize_img'];
    }
    //$prize_id = $prize_id['prizeId'];
    $this->load->view("gsk/prize/edit_prize",$return);
}

public function show_change(){
    //保存更新
    if($this->input->get()){
        $getarr = $this->input->get();
        //print_r($getarr);
        //$prizeId = $getarr['prizeId'];
        //$where = array();
        //$where["f_prize_id"] = $prizeId;
        $this->prize_model->updatepPrize($getarr);
        $this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>true)));
    }else{
        $this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>true)));
    }
}




}




?>


