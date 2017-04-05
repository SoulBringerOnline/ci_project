<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Excel_export_proj_info extends CI_Controller {

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

			$objPHPExcel->setActiveSheetIndex(0)->setTitle("项目信息")
				->setCellValue('A1', '项目id')
				->setCellValue('B1', '项目名称')
				->setCellValue('C1', '创建时间')
				->setCellValue('D1', '项目类型')
				->setCellValue('E1', '项目人数')
				->setCellValue('F1', '用户昵称')
				->setCellValue('G1', '用户id')
				->setCellValue('H1', '手机号')
				->setCellValue('I1', '注册时间')
				->setCellValue('J1', '公司名称')
				->setCellValue('K1', '状态')
				->setCellValue('L1', '提交认证时间');

			for($i='A'; $i<='L'; $i++)
			{
				$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setWidth(25);
			}
			//获取用户积分数据
			$data = $this->op_model->get_project_info($_REQUEST, true);

			$num = 2;
			foreach($data['data'] as $k=>$v)
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueExplicit('A'.$num, $v['f_prj_id'],  PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('B'.$num, $v['f_prj_name'])
					->setCellValue('C'.$num, human_time($v['f_add_time']))
					->setCellValue('D'.$num, project_type($v['f_prj_type']))
					->setCellValue('E'.$num, intval($v['f_member_count']))
					->setCellValue('F'.$num, $v['f_name'])
					->setCellValue('G'.$num, $v['f_c_uin'])
					->setCellValue('H'.$num, $v['f_phone'])
					->setCellValue('I'.$num, human_time($v['f_create_time']))
					->setCellValue('J'.$num, $v['f_company'])
					->setCellValue('K'.$num, proj_state_type($v['f_auth_status']))
					->setCellValue('L'.$num, human_time($v['f_auth_time']));
				$num++;
			}
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			//发送标题强制用户下载文件
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.'proj_info_'.date("Y-m-d H:i:s").'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}
	}
?>