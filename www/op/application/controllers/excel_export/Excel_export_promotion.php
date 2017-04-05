<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Excel_export_promotion extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			// Here you should add some sort of user validation
			// to prevent strangers from pulling your table data
			$this->load->library('PHPExcel');
			$this->load->library('PHPExcel/IOFactory');
			$this->load->model('promotion_model');
		}

		public function index()
		{
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");

			$objPHPExcel->setActiveSheetIndex(0)->setTitle("用户推广数据")
				->setCellValue('A1', '日期')
				->setCellValue('B1', '推广人员')
				->setCellValue('C1', '推广码')
				->setCellValue('D1', '手机号')
				->setCellValue('E1', '推广用户数')
				->setCellValue('F1', '有效用户数')
				->setCellValue('G1', '7日内登录3天的用户数')
				->setCellValue('H1', '推广系数')
				->setCellValue('I1', '收入');
			for($i='A'; $i<='I'; $i++)
			{
				$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setWidth(25);
			}
			//获取用户积分数据
			$data = $this->promotion_model->get_promotion_info($_REQUEST, true);

			$num = 2;
			foreach($data['data'] as $k=>$v)
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$num, date('Y-m-d', $v['f_date']))
					->setCellValueExplicit('B'.$num, strval($v['f_name']),  PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('C'.$num, strval($v['f_invite_code']))
					->setCellValueExplicit('D'.$num, strval($v['f_phone']), PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('E'.$num, intval($v['f_pro_count']))
					->setCellValue('F'.$num, intval($v['f_valid_count']))
					->setCellValueExplicit('G'.$num, intval($v['f_h7au_num']));
				$num++;
			}
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			//发送标题强制用户下载文件
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.'user_promotion_'.date("Y-m-d H:i:s").'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}
	}
?>