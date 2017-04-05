<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_export_liumi extends CI_Controller {

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

	$objPHPExcel->setActiveSheetIndex(0)->setTitle("流米流量兑换数据")
		->setCellValue('A1', '活动类型')
		->setCellValue('B1', '订单号')
		->setCellValue('C1', '用户昵称')
		->setCellValue('D1', '用户id')
		->setCellValue('E1', '手机号')
		->setCellValue('F1', '兑换流量')
		->setCellValue('G1', '运营商')
		->setCellValue('H1', '兑换时间')
		->setCellValue('I1', '流米返回时间')
		->setCellValue('J1', '兑换结果');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(30);
	//获取流量兑换流水数据
	$data = $this->op_model->get_flowlog($_REQUEST, true);
	$num = 2;
	foreach($data['data'] as $k=>$v)
	{
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$num, action_type($v['f_action_id']))
			->setCellValueExplicit('B'.$num, strval($v['f_order_no']),  PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('C'.$num, strval($v['f_name']))
			->setCellValue('D'.$num, strval($v['f_uin']))
			->setCellValueExplicit('E'.$num, strval($v['f_mobile']), PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('F'.$num, strval($v['f_post_package']))
			->setCellValue('G'.$num, carrieroperator_type($v['f_carrieroperator']))
			->setCellValueExplicit('H'.$num, human_time($v['f_pay_time']/1000), PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValueExplicit('I'.$num, human_time($v['f_callBack_time']), PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('J'.$num, flow_exchange_status($v['f_status']));
		$num++;
	}
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
	//发送标题强制用户下载文件
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.'liumi_'.date("Y-m-d H:i:s").'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter->save('php://output');
	}
}
?>