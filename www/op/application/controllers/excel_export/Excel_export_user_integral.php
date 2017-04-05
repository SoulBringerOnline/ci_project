<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Excel_export_user_integral extends CI_Controller {

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

			$objPHPExcel->setActiveSheetIndex(0)->setTitle("用户积分数据")
				->setCellValue('A1', '用户id')
				->setCellValue('B1', '用户昵称')
				->setCellValue('C1', '注册账号')
				->setCellValue('D1', '手机号')
				->setCellValue('E1', '积分变动')
				->setCellValue('F1', '时间')
				->setCellValue('G1', '变动原因');
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(15);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(30);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(15);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(30);
			//获取用户积分数据
			$data = $this->op_model->get_user_integral($_REQUEST, true);

			$num = 2;
			foreach($data['data'] as $k=>$v)
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$num, $v['f_log_uid'])
					->setCellValueExplicit('B'.$num, strval($v['f_name']),  PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('C'.$num, strval($v['f_uin']))
					->setCellValueExplicit('D'.$num, strval($v['f_phone']), PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('E'.$num, intval($v['f_log_point']))
					->setCellValue('F'.$num, human_time($v['f_log_in_time']/1000))
					->setCellValueExplicit('G'.$num, $v['f_log_desc'], PHPExcel_Cell_DataType::TYPE_STRING);
				$num++;
			}
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			//发送标题强制用户下载文件
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.'user_point_'.date("Y-m-d H:i:s").'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}
	}
?>