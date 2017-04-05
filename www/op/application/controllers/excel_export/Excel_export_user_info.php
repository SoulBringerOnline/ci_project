<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Excel_export_user_info extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			// Here you should add some sort of user validation
			// to prevent strangers from pulling your table data
			$this->load->library('PHPExcel');
			$this->load->library('PHPExcel/IOFactory');
			$this->load->model('op_model');
		}

		public function index()
		{
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

			$objPHPExcel->setActiveSheetIndex(0)->setTitle("用户个人信息")
				->setCellValue('A1', '用户昵称')
				->setCellValue('B1', '用户id')
				->setCellValue('C1', '注册渠道')
				->setCellValue('D1', '手机号')
				->setCellValue('E1', '公司名称')
				->setCellValue('F1', '公司类型')
				->setCellValue('G1', '工作岗位')
				->setCellValue('H1', '工作年限')
				->setCellValue('I1', '职称')
				->setCellValue('J1', '注册时间')
				->setCellValue('K1', '最近登录时间')
				->setCellValue('L1', '拥有项目数')
				->setCellValue('M1', '当前积分')
				->setCellValue('N1', '邀请码');

			for($i='A'; $i<='N'; $i++)
			{
				$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setWidth(15);
			}
			//获取用户积分数据
			$data = $this->op_model->get_user_info($_REQUEST, true);

			$num = 2;
			foreach($data['data'] as $k=>$v)
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$num, $v['f_name'])
					->setCellValue('B'.$num, strval($v['f_account_id']))
					->setCellValue('C'.$num, strval($v['f_register_channel']))
					->setCellValue('D'.$num, strval($v['f_account_phone']))
					->setCellValue('E'.$num, $v['f_company'])
					->setCellValue('F'.$num, $v['f_company_type'])
					->setCellValue('G'.$num, $v['f_job_type'])
					->setCellValue('H'.$num, $v['f_years_of_working'])
					->setCellValue('I'.$num, $v['f_job_title'])
					->setCellValue('J'.$num, human_time(substr($v['f_account_create_time'], 0, -3)))
					->setCellValue('K'.$num, human_time($v['f_last_req_time']))
					->setCellValue('L'.$num, intval($v['f_project_count']))
					->setCellValue('M'.$num, intval($v['f_points']))
					->setCellValue('N'.$num, $v['f_code_id']);
				$num++;
			}
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			//发送标题强制用户下载文件
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.'user_info_'.date("Y-m-d H:i:s").'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}
	}
?>