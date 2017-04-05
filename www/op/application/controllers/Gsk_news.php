<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsk_news extends GSK_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->model('op_model');
        $this->load->model('help_model');
        $this->load->model('call_model');
        $this->load->model('user_model');
        $this->load->model('prize_model');
        $this->load->model('activity_model');
    }

    public function test()
    {
    	$test = $this->news_model->test();
    }

    //爬虫新闻
	public function spider()
	{
		$filter = array();
		$filter['cur_page'] =  $_GET['p'];

		$data['title'] = '资讯抓取';
        $data['data'] = $this->news_model->get_spider($filter);
		$data['content'] = 'gsk/news/spider';
		$this->load->view('include/layout' , $data );
	}

	public function do_store_news(){
		$news_id =  $_REQUEST['id'];
		$rsp = array('result' => 'failed' , 'msg' => '');
		if($this->news_model->store_news($news_id) == True){
			$rsp = array('result' => 'ok' , 'msg' => '');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($rsp));
	}


	//新闻编辑
	public function news()
	{
		$filter = array();
		$filter['cur_page'] =  $_GET['p'];
		$filter['news_status'] = $_GET['news_status'];

		$data['title'] = '资讯';
        $data['data'] = $this->news_model->get_news($filter);
		$data['content'] = 'gsk/news/news';
		$this->load->view('include/layout' , $data );
	}
	
	public function edit_news()
	{
		$data['title'] = '编辑资讯';
		$data['type'] = $_GET['type'];
		switch ($_GET['type']) {
			case 'edit':
        		$data['data'] = $this->news_model->get_a_news($_GET['news_id']);
				break;
		}
		$data['content'] = 'gsk/news/edit_news';
		$this->load->view('include/layout' , $data );
	}

	public function do_edit_news()
	{
		$url = 'zhuyou-news.oss-cn-hangzhou.aliyuncs.com';
		$rep_url = 'res.zy.glodon.com';
		$type = $_REQUEST['type'];
		$news_id = 	$_REQUEST['news_id'];
		
		$news = array();
		$news['f_hotspot_title'] =  $_REQUEST['news_title'];
		$news['f_hotspot_subtitle'] =  $_REQUEST['news_subtitle'];
		$news['f_hotspot_content'] =  $_REQUEST['news_content'];
		$news['f_hotspot_source_site'] =  $_REQUEST['news_source'];
		$news['f_hotspot_first_image'] =  str_replace($url, $rep_url, $_REQUEST['news_img']);
		$news['f_hotspot_big_image'] =  str_replace($url, $rep_url, $_REQUEST['news_big_img']);
		$news['f_hotspot_sumbit_time'] =  empty($_REQUEST['news_sumbit_time']) ? time() : $_REQUEST['news_sumbit_time'];
		$news['f_hotspot_classify'] = $_REQUEST['news_classify'];
		$news['f_hotspot_link'] = $_REQUEST['news_hotspot_link'];

		$news['f_hotspot_comment_count'] =  empty( $_REQUEST['news_comment_count'] ) ? rand(100 , 999) : intval( $_REQUEST['news_comment_count'] );
		$news['f_hotspot_collection_count'] =  empty( $_REQUEST['news_collection_count'] ) ? rand(100 , 999) : intval( $_REQUEST['news_collection_count'] );
		$news['f_hotspot_view_count'] =  empty( $_REQUEST['news_view_count'] ) ? rand(2000, 5000) : intval( $_REQUEST['news_view_count'] );
		$news['f_hotspot_good_count'] =  empty( $_REQUEST['news_good_count'] ) ? rand(1000 , 2000) : intval( $_REQUEST['news_good_count'] );
		$news['f_hotspot_sort'] =  empty( $_REQUEST['news_sort'] ) ? 0 : intval( $_REQUEST['news_sort'] );
		if($type == 'add')
		{
			$news['f_hotspot_status'] = intval($_REQUEST['news_timingsubmit'])==0 ? 1 : 2;
		}
		else
		{
			$new = $this->news_model->get_a_news($news_id);
			$status = $new['f_hotspot_status'];
			if($status == 1)
			{
				$news['f_hotspot_status'] = intval($_REQUEST['news_timingsubmit'])==0 ? 1 : 2;
			}
		}

		$rsp = array('result' => 'failed' , 'msg' => '');

		$result = False;
		switch ($type) {
			case 'add':
				$result = $this->news_model->add_news($news);
				break;
			case 'edit':
				$result = $this->news_model->edit_news($news_id, $news);
				break;
		}

		if($result == True){
			$rsp = array('result' => 'ok' , 'msg' => '');
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($rsp));
	}

	public function do_del_news()
	{
		$news_id =  $_REQUEST['id'];
		$rsp = array('result' => 'failed' , 'msg' => '');
		if($this->news_model->del_news($news_id) == True){
			$rsp = array('result' => 'ok' , 'msg' => '');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($rsp));
	}

	public function do_change_news_status()
	{
		$news_id =  $_REQUEST['id'];
		$news_status =  $_REQUEST['news_status'];
		$rsp = array('result' => 'failed' , 'msg' => '');
		if($this->news_model->change_news_status($news_id, $news_status) == True){
			$rsp = array('result' => 'ok' , 'msg' => '');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($rsp));
	}

	public function do_init_sort_id()
	{
		$rsp = array('result' => 'ok' , 'msg' => '');
		$this->news_model->init_news_sort_id();
		$this->output->set_content_type('application/json')->set_output(json_encode($rsp));
	}

	public function hot()
	{
		$data['content'] = 'include/content';
        $this->load->view('include/layout' , $data );
    }

	public function feedback()
	{
		$filter = array();
		$filter['cur_page'] =  $_REQUEST['p'];
		$filter['query'] =  $_REQUEST['query'];

		$data = $this->op_model->get_feedback($filter);
		$data['title'] = '用户反馈';
		$data['data'] = $data ;
		$data['content'] = 'gsk/op/feedback';
		$this->load->view('include/layout' , $data );
	}

	public function liumi_flowlog()
	{
		if(isset($_REQUEST['btn_export']))
		{
			$get = null;
			foreach($_REQUEST as $k=>$v)
			{
				$get .= $k.'='.$v.'&';
			}
			$get = rtrim($get, '&' );
			redirect('excel_export/excel_export_liumi/index?'.$get);
		}
		else
		{
			$result = $this->op_model->get_flowlog($_REQUEST);
			$data['title'] = '流量兑换流水信息';
			$data['data'] = $result ;
			$data['content'] = 'gsk/op/liumi_flowlog';
			$this->load->view('include/layout' , $data );
		}
	}

	public function help()
	{
		$filter = array();
		$filter['cur_page'] =  $_REQUEST['p'];
		$filter['f_help_title'] =  $_REQUEST['f_help_title'];
		$filter['f_help_classify'] =  intval($_REQUEST['f_help_classify']);
		$filter['f_comment_question'] =  $_REQUEST['f_comment_question'];

		$data['content'] = 'gsk/help/help';
		$data['title'] = '帮助文档';
		$data['data'] = $this->help_model->get_help($filter);
		$this->load->view('include/layout' , $data );
	}

	public function edit_help()
	{
		$data['content'] = 'gsk/help/edit_help';
		if($_REQUEST['type'] == "add")
		{
			$data['title'] = '添加帮助文档';
		}
		else
		{
			$data['title'] = '编辑帮助文档';
			$data['help'] = $this->help_model->get_a_help($_REQUEST['help_id']);;
		}
		$this->load->view('include/layout' , $data );
	}

	public function do_edit_help()
	{
		$type = $_REQUEST['type'];
		$result = False;
		switch ($type) {
			case 'add':
				$result = $this->help_model->add_help($_REQUEST);
				break;
			case 'edit':
				$result = $this->help_model->edit_help($_REQUEST);
				break;
		}

		$rsp = array('result' => 'failed' , 'msg' => '');
		if($result == true)
		{
			$rsp = array('result' => 'true' , 'msg' => '');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}

	public function do_del_help()
	{
		$help_id =  $_REQUEST['help_id'];
		$rsp = array('result' => 'failed' , 'msg' => '');
		if($this->help_model->del_news($help_id) == True){
			$rsp = array('result' => 'ok' , 'msg' => '');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($rsp));
	}

	public function user_integral()
	{
		if(isset($_REQUEST['btn_export']))
		{
			$get = null;
			foreach($_REQUEST as $k=>$v)
			{
				$get .= $k.'='.$v.'&';
			}
			$get = rtrim($get, '&' );
			redirect('excel_export/excel_export_user_integral/index?'.$get);
		}
		else
		{
			$filter = array();

			$data['content'] = 'gsk/integral/user_integral';
			$data['title'] = '用户积分数据';
			$data['data'] = $this->op_model->get_user_integral($_REQUEST);
			$this->load->view('include/layout' , $data );
		}
	}

	public function user_integral_ranking()
	{
		$filter = array();

		$data['content'] = 'gsk/integral/user_integral_ranking';
		$data['title'] = '用户积分排行榜、飙升榜';
		$data['rank_data'] = $this->op_model->get_user_integral_ranking();
		$data['soar_data'] = $this->op_model->get_user_integral_soaring();
		$this->load->view('include/layout' , $data );
	}

	public function call_balance_account()
	{
		//获取时间
		$year = isset($_REQUEST['year'])? $_REQUEST['year']: date("Y");
		$month = isset($_REQUEST['month'])? $_REQUEST['month']: date("m");
		$start = strtotime($year."-".$month."-01");
		if($month == 12){
			$end = strtotime(($year+1)."-01-01");
		}else{
			$end = strtotime($year."-".($month+1)."-01");
		}
		//获取本月档位
		$dateid = date("Ym", $start);
		$currentCombo = $this->call_model->get_recent_combo($dateid);
		//获取下月档位
		$dateid = date("Ym", $end);
		$nextCombo = $this->call_model->get_recent_combo($dateid);
		//获取本月所有用户流水
		$list = $this->call_model->flow_list_by_time($start*1000, $end*1000, 1);
		//本月发放人数及时间
		$grant= $this->call_model->free_time_count($start*1000, $end*1000, 0);
		//月度账单统计
		$return = array(
			'currentCombo' => $currentCombo,
			'nextCombo' => $nextCombo,
			'grant' => $grant,
			'flowlist' => $list,
			'year'=>$year,
			'month'=>$month,
		);
		$return['content'] = 'gsk/call/account';
		$return['title'] = '对账系统';
		$this->load->view('include/layout' , $return);
	}

	/**
	 * 主帐号信息查询
	 */
	private function queryAccountInfo()
	{
		$config = $this->config->item('call_zhuyou');
		//$this->load->library('CCPRestSDK');
		// 初始化REST SDK
		require_once(APPPATH."libraries/CCPRestSDK.php");
		$rest = new CCPRestSDK($config['base_url'],$config['port'],$config['version']);
		$rest->setAccount($config['account_sid'],$config['account_token']);
		$rest->setAppId($config['app_id']);

		// 调用主帐号信息查询接口
		$result = $rest->queryAccountInfo();
		if($result == NULL ) {
			echo "result error!";
			exit;
		}
		if($result->statusCode!=0) {
			echo "error code :" . $result->statusCode . "<br>";
			echo "error msg :" . $result->statusMsg . "<br>";exit;
			//TODO 添加错误处理逻辑
		}else{
			// 获取返回信息
			$account = $result->Account;
			$ret = array(
				'balance' => (string)$account->balance,
				'subBalance' => (string)$account->subBalance,
			);

			return $ret;
		}
	}

	public function call_change_combo() {
		if($this->input->get()){
			$dateid = intval(date("Ym"));
			$columns = array();
			$columns['f_deputy_account_minus'] = isset($_GET['deputy_account_minus'])? $_GET['deputy_account_minus']: 0;
			$this->call_model->update_combo(array('f_date_id'=>$dateid), $columns);

			$this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>true)));
		}else{
			$dateid = date("Ym");
			$result = $this->call_model->get_recent_combo($dateid);
			$return['date_id'] = $result['f_date_id'];
			$return['grade'] = $result['f_grade'];
			$return['price'] = $result['f_price'];
			$return['deputy_account_minus'] = $result['f_deputy_account_minus'];
			$return['min_cost'] = $result['f_min_cost'];

			$this->load->view('gsk/call/combo' , $return);
		}
	}

	public function call_date_combo_add() {
		if($this->input->get()){
			$dateid = date("Ym", strtotime("+1 month"));
			$columns = array();
			$columns['f_grade'] = intval($_GET['grade']);
			$columns['f_price'] = floatval($_GET['price']);
			$columns['f_deputy_account_minus'] = floatval($_GET['deputy_account_minus']);
			$columns['f_min_cost'] = intval($_GET['min_cost']);
			//查询是否已产生数据
			$result = $this->call_model->get_combo_by_dateid($dateid);
			if($result['f_date_id']){
				$this->call_model->update_combo(array('f_date_id'=>new MongoInt32($dateid)), $columns);
			}else{
				$columns['f_create_time'] = time();
				$columns['f_date_id'] = new MongoInt32(date("Ym", strtotime("+1 month")));
				$this->call_model->add_combo($columns);
			}

			$this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>true)));
		}else{
			$return = array();
			$return['date'] = strtotime(date("Y-m-01", strtotime("+1 month")));
			//查询最近记录
			$dateid = date("Ym", strtotime("+1 month"));
			$result = $this->call_model->get_recent_combo($dateid);
			if($result['f_date_id'] == $dateid){
				$return['date_id'] = $result['f_date_id'];
			}
			$return['grade'] = $result['f_grade'];
			$return['price'] = $result['f_price'];
			$return['deputy_account_minus'] = $result['f_deputy_account_minus'];
			$return['min_cost'] = $result['f_min_cost'];

			$this->load->view('gsk/call/combo_add' , $return);
		}
	}

	public function call_record()
	{
		$data['content'] = 'gsk/call/call';
		$data['title'] = '通话记录';
		$page = isset($_GET['page'])? $_GET['page']: 1;
		$size = isset($_GET['size'])? $_GET['size']: 18;
		$start = strtotime($_GET['start']);
		$end = strtotime($_GET['end']);
		$status = $_GET['status'];
		$uid = $_GET['uid'];
		$phone = $_GET['phone'];
		$username = $_GET['username'];
		$where = array();
		$condition = array();
		if($start){
			$where['f_create_time']['$gte'] = new MongoInt64($start*1000);
			$condition['start'] = $_GET['start'];
		}
		if($end){
			$where['f_create_time']['$lte'] = new MongoInt64($end*1000);
			$condition['start'] = $_GET['end'];
		}
		if(isset($status) && in_array($status, array(0, 1))){
			$where['f_status'] = new MongoInt32($status);
			$condition['start'] = $_GET['status'];
		}
		if($uid){
			$where['f_uin'] = new MongoInt32($uid);
			$condition['uid'] = $_GET['uid'];
		}
		if($phone){
			//获取uid
			$uid = $this->user_model->get_uin(array('f_phone'=>$phone));
			if(!$uid){
				echo "该用户不存在";exit;
			}
			$where['f_uin'] = new MongoInt32($uid);
			$condition['phone'] = $_GET['phone'];
		}
		if($username){
			$uid = $this->user_model->get_uin(array('f_name'=>$username));
			if(!$uid){
				echo "该用户不存在";exit;
			}
			$where['f_uin'] = $uid;
			$condition['username'] = $_GET['username'];
		}

		$list = $this->call_model->call_list($where, $page, $size);
		foreach ($list['list'] as $key=>$val) {
			$list['list'][$key]['f_create_time'] = date("Y-m-d H:i:s", floor($val['f_create_time']/1000));
			$list['list'][$key]['f_status'] = ($val['f_status'] == 1)? "成功": "异常";
		}
		$data['data'] = $list;
		$url = $_SERVER['_SERVER["PATH_INFO"]'];
		$params = preg_replace("/[\?&]?page=\d+/", "", $_SERVER['QUERY_STRING']);
		if($params){
			$url .= "?".$params."&page=p%";
		} else {
			$url .= "?page=p%";
		}
		$data['url'] = $url;
		$data['condition'] = $condition;
		$this->load->view('include/layout' , $data );
	}

	public function call_convert_record()
	{
		$data['content'] = 'gsk/call/convert';
		$data['title'] = '通话兑换记录';
		$page = isset($_GET['page'])? $_GET['page']: 1;
		$size = isset($_GET['size'])? $_GET['size']: 18;
		$start = strtotime($_GET['start']);
		$end = strtotime($_GET['end']);
		$order_id = $_GET['order_id'];
		$uid = $_GET['uid'];
		$phone = $_GET['phone'];
		$where = array();
		$condition = array();
		if($start){
			$where['f_create_time']['$gte'] = new MongoInt64($start*1000);
			$condition['start'] = $_GET['start'];
		}
		if($end){
			$where['f_create_time']['$lte'] = new MongoInt64($end*1000);
			$condition['start'] = $_GET['end'];
		}
		if($uid){
			$where['f_uin'] = new MongoInt32($uid);
			$condition['uid'] = $_GET['uid'];
		}
		if($order_id){
			$where['f_order_id'] = $order_id;
			$condition['order_id'] = $_GET['order_id'];
		}
		if($phone){
			//获取uid
			$uid = $this->user_model->get_uin(array('f_phone'=>$phone));
			if(!$uid){
				echo "该用户不存在";exit;
			}
			$where['f_uin'] = new MongoInt32($uid);
			$condition['phone'] = $_GET['phone'];
		}

		$list = $this->call_model->point_to_free_list($where, $page, $size);
		foreach ($list['list'] as $key=>$val) {
			$list['list'][$key]['f_create_time'] = date("Y-m-d H:i:s", floor($val['f_create_time']/1000));
		}
		$data['data'] = $list;
		$url = $_SERVER['_SERVER["PATH_INFO"]'];
		$params = preg_replace("/[\?&]?page=\d+/", "", $_SERVER['QUERY_STRING']);
		if($params){
			$url .= "?".$params."&page=p%";
		} else {
			$url .= "?page=p%";
		}
		$data['url'] = $url;
		$data['condition'] = $condition;
		$this->load->view('include/layout' , $data );
	}

	public function call_record_fix() {
		$sid = $_GET['sid'];
		if(!$sid){
			$this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>false, 'msg'=>"Sid不能为空")));
		}

		$state = $this->QueryCallState($sid);
		if($state == 1){
			$this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>false, 'msg'=>"通话进行中")));
		}

		$data = $this->CallResult($sid);
		$duration = $data['callTime'];
		$release = ceil($duration / 60);
		//用户时间递减
		if($duration > 0){
			//获取用户信息
			$user = $this->call_model->get_user_fields(array('f_call_id'=>$data['callSid']), array('f_uin'));
			if(!$user){
				$this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>false, 'msg'=>"获取用户失败")));
			}
			$uid = $user['f_uin'];
			//获取用户时间
			$free_time = $this->call_model->get_user_call_time($uid);

			$free_time = ($free_time - $release)<0? 0: ($free_time - $release);
			$update = array();
			$update['f_free_time_num'] = $free_time;
			$update['f_last_update_time'] = $this->microtime_float();

			$result = $this->call_model->update_user_call_time($uid, $update);
			if(! $result){
				$this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>false, 'msg'=>"用户通话时间更新失败")));
			}
		}

		//修改流水
		$flow = array();
		$flow['f_order_no'] = $data['orderid'];
		$flow['f_free_time_point'] = new \MongoInt32($release);
		$flow['f_status'] = new \MongoInt32(1);
		$result = $this->call_model->update_user_free_time_log($sid, $flow);
		if(! $result){
			$this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>false, 'msg'=>"用户修改流水失败")));
		}

		//修改通话流水
		$flow = array();
		$flow['f_duration'] = new \MongoInt32($duration);
		$flow['f_status'] = new \MongoInt32(1);
		$result = $this->call_model->update_user_call_flow_log($sid, $flow);

		$this->output->set_content_type('application/json')->set_output(json_encode(array("state"=>true, 'msg'=>"修复成功")));
	}


	/**
	 * 呼叫结果查询
	 * @param callSid 呼叫Id
	 */
	private function CallResult($callSid)
	{
		$config = $this->config->item('call_zhuyou');
		// 初始化REST SDK
		require_once(APPPATH."libraries/CCPRestSDK.php");
		$rest = new CCPRestSDK($config['base_url'],$config['port'],$config['version']);
		$rest->setAccount($config['account_sid'],$config['account_token']);
		$rest->setAppId($config['app_id']);

		// 调用呼叫结果查询接口
		$result = $rest->CallResult($callSid);
		if($result == NULL ) {
			echo "result error!";
			exit;
		}
		if($result->statusCode!=0) {
			echo "error code :" . $result->statusCode . "<br>";
			echo "error msg :" . $result->statusMsg . "<br>";exit;
			//TODO 添加错误处理逻辑
		}else {
			// 获取返回信息
			$callResult = $result->CallResult;
			$ret = array(
				'callTime' => (string)$callResult->callTime,
				'state' => (string)$callResult->state,
			);
		}

		return $ret;
	}

	/**
	 * 呼叫状态查询
	 * @param callid     呼叫Id
	 * @param action   查询结果通知的回调url地址
	 */
	private function QueryCallState($callid){
		$config = $this->config->item('call_zhuyou');
		// 初始化REST SDK
		require_once(APPPATH."libraries/CCPRestSDK.php");
		$rest = new CCPRestSDK($config['base_url'],$config['port'],$config['version']);
		$rest->setAccount($config['account_sid'],$config['account_token']);
		$rest->setAppId($config['app_id']);

		// 调用呼叫状态查询接口
		$result = $rest->QueryCallState($callid, null);
		if($result == NULL ) {
			echo "result error!";
			exit;
		}
		if($result->statusCode!=0) {
			echo "error code :" . $result->statusCode . "<br>";
			echo "error msg :" . $result->statusMsg . "<br>";
			//TODO 添加错误处理逻辑
		}else{
			return intval($result->state);
		}
	}

	private  function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return floor(((float)$usec + (float)$sec) * 1000);
	}

	public function activity_position()
	{
		$data['title'] = '内容运营管理';
		$data['data'] =  $this->op_model->get_activity_position();
		$data['content'] = 'gsk/op/activity_position';
		$this->load->view('include/layout' , $data );
	}

	public function update_position_state()
	{
		$state = $_REQUEST['state'];
		if($state == 0)
		{
			$_REQUEST['state'] = 1;
		}
		else
		{
			$_REQUEST['state'] = 0;
		}
		$this->op_model->update_position_state($_REQUEST);
		$url = $url = site_url('gsk_news/activity_position');
		if($state == 0)
		{
``		}
		else
		{
			echo "<script>('下线成功！');"."location.href='"."$url"."'</script>";
		}
	}

	public function add_activity_pt()
	{
		$data['title'] = '添加活动位/'.$_REQUEST['name'];
		$data['content'] = 'gsk/op/add_activity_position';
		$this->load->view('include/layout' , $data );
	}

	public function do_add_activity_pt()
	{
		$result = $this->op_model->do_add_activity_pt($_REQUEST);
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}

	public function change_activity_pt()
	{
		$data['title'] = '修改活动位/'.$_REQUEST['name'];
		$data['data'] =  $this->op_model->get_activity_pt_history($_REQUEST['id']);
		$data['content'] = 'gsk/op/change_activity_position';
		$this->load->view('include/layout' , $data );
	}

	public function do_change_activity_pt()
	{
		$result = $this->op_model->do_change_activity_pt($_REQUEST);
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}

	public function pc_activity_pos()
	{
		if($this->input->post()){
			$data = array();
			$data['f_name'] = trim($_POST['f_name']);
			$data['f_url'] = trim($_POST['f_url']);
			$result = $this->activity_model->update_pc_activity_pos(array('f_pos_id'=>"1"), $data);
			if($result){
				$ret = array('status'=>1);
			}else{
				$ret = array('status'=>0);
			}

			$this->output->set_content_type('application/json')->set_output(json_encode($ret));
		}else{
			$data['title'] = 'PC活动运营位';
			$data['data'] =  $this->activity_model->get_pc_activity_pos(array('f_pos_id'=>"1"));
			$data['content'] = 'gsk/activity/pc_activity_pos';
			$this->load->view('include/layout' , $data );
		}
	}

	public function import_card()
	{
		set_time_limit(0);
		$data['title'] = '导入京东卡';
		//  $data['data'] =  $this->op_model->get_activity_pt_history($_REQUEST['id']);
		$data['content'] = 'gsk/activity/import';
		$this->load->view('include/layout' , $data );
	}

	public  function import() {
		$file = $this->do_upload("upfile", "txt");
		$path = BASEPATH.'../'.$file;
		$success = array();
		$failed = array();
		$total = 0;
		$handle = fopen($path, "r");
		while (! feof($handle)) {
			$line = fgets($handle);
			list($money, $number, $password) = explode(',', trim($line,"\r\n"));
			$total ++;
			if(!($money && $number && $password)){
				$failed[] = array('money'=>$money, 'number'=>$number, 'password'=>$password);
				continue;
			}
			switch(intval($money)) {
				case 50:
					$prize_id = "8";break;
				case 100:
					$prize_id = "9";break;
				case 300:
					$prize_id = "10";break;
			}
			if(!$prize_id){
				$failed[] = array('money'=>$money, 'number'=>$number, 'password'=>$password);
				continue;
			}
			//查看是否已经存在
			$res = $this->prize_model->checkCard(array('f_card_number'=>$number, 'f_card_password'=>$password));
			if($res){
				$failed[] = array('money'=>$money, 'number'=>$number, 'password'=>$password);
				continue;
			}
			$pool = array();
			$pool['f_pool_id'] = uniqid(mt_rand(1000000, 9999999));
			$pool['f_prize_id'] = $prize_id;
			$pool['f_card_number'] = $number;
			$pool['f_card_money'] = $money;
			$pool['f_card_password'] = $password;
			$pool['f_status'] = 0;
			$pool['f_create_time'] = time();
			$res = $this->prize_model->addCard($pool);
			if(!$res){
				$failed[] = array('money'=>$money, 'number'=>$number, 'password'=>$password);
				continue;
			}
			$update = $this->prize_model->updatePrize(array('f_prize_id'=>$prize_id), array('$inc' => array("f_num" =>new MongoInt32(1), 'f_origin_total'=>new MongoInt32(1))));
			$success[] = array('money'=>$money, 'number'=>$number, 'password'=>$password);
		}
		$return = array('success'=>$success, 'failed'=>$failed, 'total'=>$total);
		echo json_encode($return);
	}

	private function do_upload($file, $ext="png")
	{
		$dir = BASEPATH.'../upload/';
		$file_name = date("Ymd").'_'.time().'.'.$ext;

		$config['upload_path'] = $dir;
		$config['allowed_types'] = '*';
		$config['max_size'] = 5000;
		//$config['max_width'] = 1024;
		//$config['max_height'] = 768;
		$config['file_name'] = $file_name;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($file)) {
			$error = array('error' => $this->upload->display_errors());

			return false;
		}

		$data = $this->upload->data();
		return "upload/".$data['orig_name'];
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
