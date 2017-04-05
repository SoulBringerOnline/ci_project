<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Excel_export_pv_data extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			$this->load->model('op_model');
		}

		public function index()
		{
			$fp = fopen('file.csv', 'w');
			//获取用户积分数据
			$data = $this->op_model->get_PV_data_excel();

			$first = array();
			array_push($first, iconv('utf-8','gb2312',"发布时间"));
			array_push($first, iconv('utf-8','gb2312',"标题"));
			array_push($first, iconv('utf-8','gb2312',"总浏览量"));
			$first = array_merge($first, $data['time'] );
			fputcsv($fp, $first);

			$t = 0;
			foreach ($data['titles'] as $title)
			{
				$temp =array();
				$name = mb_strlen($title['title'])>=10?mb_substr($title['title'],0,10,'utf-8')."...":$title['title'];
				$name =iconv('utf-8','gb2312',$name);//转换编码
				array_push($temp, $title['publish_time']);
				array_push($temp, $name);
				$item = $data['counts'][$t];
				$temp = array_merge($temp, $item);
				$t++;
				fputcsv($fp, $temp);
			};
			fclose($fp);
		}
	}
?>