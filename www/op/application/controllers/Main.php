<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends GSK_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('gsk_model');
        $this->load->model('gsk_user');
        $this->load->model('op_model');
	    $this->load->model('promotion_model');
	    $this->output->cache(5);
    }

	public function index()
	{
        $data['content'] = 'gsk/main/dash';
        $data['channel'] = $this->gsk_model->get_channel();
        $data['stats'] = $this->op_model->get_dash_stat_main(0);
		if($_GET['debug'] == 1) {
			var_dump($data['stats']);
			exit;
		}

		$this->load->view('include/layout' , $data );
	}

	public function ajax_dashdata() {
		$data = $this->op_model->get_dash_stat_main(0);

		echo json_encode(array("code"=>0, "data"=>$data));
	}
    public function signin()
    {
        $username = trim( $_REQUEST['signin_username'] );
        $password = trim( $_REQUEST['signin_password'] );

		$priv = $this->config->item('priv');

		if(!empty($this->session->userdata['user_id'])){
			redirect('/main/index');
		}

        if( !empty( $priv[ $username ] ) )  
	    {
		    if($password == $priv[ $username ]['pswd'] )
		    {
			    $this->session->set_userdata('user_id', $username);
			    $this->session->set_userdata('user_role', $priv[ $username ]['role']);
			    $this->session->set_userdata('user_name', $priv[ $username ]['name']);

			    redirect('/main/index');

		    }
		    else
		    {
			    $this->load->view('include/signin');
		    }
	    }
	    else//推广人员用自己的账号登录
	    {
		    if(is_null($username)||empty($username))
		    {
			    $this->load->view('include/signin');
		    }
		    else {
			    // 判断用户名是否存在？
			    $user_info = $this->gsk_user->get_user_by_account($username);
			    if(empty($user_info)) {
				    $url = site_url('include/signin');
				    echo "<script>('"."glodon账号无访问权限"."');"."</script>";
				    $this->load->view('include/signin');
				    return false;
			    }
			    else if($user_info['status'] != 0) {
				    $url = site_url('include/signin');
				    echo "<script>('"."glodon账号权限已收回"."');"."</script>";
				    $this->load->view('include/signin');
				    return false;
			    }
			    else {
				    // 通过 公用api 判断否为glodon用户
				    $url = "http://home.glodon.com:7783/Utilities/Account/ProxyService/SsoAuthUserProcess?wsdl";
				    $client = new SoapClient($url);

				    $client->soap_defencoding = "utf-8";
				    $client->decode_utf8 = false;
				    $client->xml_encoding = "utf-8";
				    $param = array("userCode"=>$username, "password"=>$password);
				    $result = $client->process($param);
                    if ($result->result == "Y") {
					    $priv_user = $this->config->item('priv_user');
					    $this->session->set_userdata('user_id', $username);
					    $this->session->set_userdata('user_role', $priv_user[$user_info['group']]['role']);
					    $this->session->set_userdata('user_name', $username);
					    $this->session->set_userdata($user_info);
					    // TODO:: 用户权限管理

					    redirect('/main/index');
				    } else {
					    $rsp = $this->promotion_model->signin($_REQUEST);
					    if ($rsp['result'] == true) {
						    $this->session->set_userdata('signin_username', $username);
						    redirect('/Gsk_pro_register/workers_query');
					    } else {
						    $url = site_url('include/signin');
						    echo "<script>('" . $rsp['msg'] . "');" . "</script>";
						    $this->load->view('include/signin');
					    }
				    }
			    }
		    }
	    }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/main/signin');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
