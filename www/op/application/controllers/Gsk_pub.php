<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsk_pub extends GSK_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gsk_model');
    }

    public function down_pkg()
    {
        $data['title'] = '版本下载';
        $data['content'] = 'gsk/pub/pkg';
        $data['channel'] = $this->gsk_model->get_channel();

        $this->load->view('include/layout', $data);  
    }

    public function pub_ios()
    {
        $data['title'] = '版本发布';
        $data['sub_title'] = 'iOS';
        $data['content'] = 'gsk/pub/ios';
        $data['channel'] = $this->gsk_model->get_channel();
        $data['versions'] = $this->gsk_model->get_versions(OS_TYPE_IOS);

        $this->load->view('include/layout', $data);  
    }

    public function pub_list() {
        $channel = intval($_REQUEST["c"]);
        $data['title'] = '版本发布列表';
        $data['sub_title'] = '渠道号 : '."$channel";
        $data['content'] = 'gsk/pub/list';
        $os = OS_TYPE_ANDROID;
        if($_REQUEST['type'] == 'ios') {
            $os = OS_TYPE_IOS;
        }
        else if($_REQUEST['type'] == 'pc')
        {
            $os = OS_TYPE_PC;
        }

        $data['versions'] = $this->gsk_model->get_pub_version($os,$channel);

        $this->load->view('include/layout', $data);
    }

    public function do_pub_ios()
    {
        if( isset( $_REQUEST['radio_pub_ios'] )  && is_array( $_REQUEST['checkbox_pub_ios']  ))
        {
            $pub_version = $_REQUEST['radio_pub_ios'];
            foreach ($_REQUEST['checkbox_pub_ios'] as $key => $value)
            {
                $data[$key] = array('f_ios_version' => $pub_version , 'f_id' => $value ); 
            }

            if( count( $data ) ){
                $this->gsk_model->update_pub_info( $data );
                // system("/usr/bin/sudo sh /opt/gitlab-5.2.0-1/apache2/htdocs/laki/laki_pub/php.sh");
            }

        }
        redirect(site_url( '/gsk_pub/pub_ios' ), 'refresh');
    }


    public function pub_android()
    {
        $data['title'] = '版本发布';
        $data['sub_title'] = 'android';
        $data['content'] = 'gsk/pub/android';
        $data['channel'] = $this->gsk_model->get_channel();
        $data['versions'] = $this->gsk_model->get_versions(OS_TYPE_ANDROID);

        if($_GET['debug'] == 1) {
            var_dump($data);
            exit;
        }
        $this->load->view('include/layout', $data);  
    }

    public function do_pub_android()
    {
        if( isset( $_REQUEST['radio_pub_android'] )  && is_array( $_REQUEST['checkbox_pub_android']  ))
        {
            $pub_version = $_REQUEST['radio_pub_android'];
            foreach ($_REQUEST['checkbox_pub_android'] as $key => $value)
            {
                $data[$key] = array('f_android_version' => $pub_version , 'f_id' => $value ); 
            }

            if( count( $data ) ){
                $this->gsk_model->update_pub_info( $data );
                // system("/usr/bin/sudo sh /opt/gitlab-5.2.0-1/apache2/htdocs/laki/laki_pub/php.sh");
                // 记录系统log

            }

        }
        redirect(site_url( '/gsk_pub/pub_android' ), 'refresh');
    }

    public function do_check_oss(){
        $channel = $_REQUEST['channel'];
        $version = $_REQUEST['version'];
        $ch = curl_init(); 
        $url = 'http://ugc.zy.glodon.com/dl%2Fandroid%2F' . $version . '%2Fgsk_' . $channel . '_' . $version . '.apk';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE); 
        curl_setopt($ch, CURLOPT_NOBODY, TRUE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_exec($ch); 
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);      
        echo $http_code;    
    }

    public function pub_pc()
    {
        $data['title'] = '版本发布';
        $data['sub_title'] = 'PC';
        $data['content'] = 'gsk/pub/pc';
        $data['channel'] = $this->gsk_model->get_channel();
        $data['versions'] = $this->gsk_model->get_versions(OS_TYPE_PC);

        $this->load->view('include/layout', $data);
    }

    public function do_pub_pc()
    {
        if( isset( $_REQUEST['text_pub_pc'] )  && is_array( $_REQUEST['checkbox_pub_pc']  ))
        {
            $pub_version = $_REQUEST['text_pub_pc'];
            foreach ($_REQUEST['checkbox_pub_pc'] as $key => $value)
            {
                $data[$key] = array('f_pc_version' => $pub_version , 'f_id' => $value );
            }

            if( count( $data ) ){
                $this->gsk_model->update_pub_info( $data );
                // system("/usr/bin/sudo sh /opt/gitlab-5.2.0-1/apache2/htdocs/laki/laki_pub/php.sh");
                // 记录系统log

            }

        }
        redirect(site_url( '/gsk_pub/pub_pc' ), 'refresh');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
