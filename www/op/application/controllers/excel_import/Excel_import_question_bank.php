<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Excel_import_question_bank extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			// Here you should add some sort of user validation
			// to prevent strangers from pulling your table data
			$this->load->library('PHPExcel/IOFactory');
			$this->load->model('excel_model');
		}

		public function index() {
			if(!isset($_REQUEST['type'])) exit;

			$index = null;
			if($_REQUEST['type'] == 0)
			{
				$index = 0;
			}
			else
			{
				$index = 1;
			}
			$path = dirname(__FILE__).'/question.xlsx';
			$objPHPExcel = IOFactory::load($path);

			$sheet = $objPHPExcel->getSheet($index);//读取第一个sheet
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数

			$data = array();
			//循环读取excel文件,读取一条,插入一条
			for ($j = 2; $j <= $highestRow; $j++) {
				$temp = array();
				for ($k = 'A'; $k <= $highestColumn; $k++) {
					$str =  $objPHPExcel->getSheet($index)->getCell("$k$j")->getValue();//读 取单元格
					array_push($temp, $str);
				}
				array_push($data, $temp);
			}

			$this->excel_model->insert_question_bank($data, $index);

			echo "<script>('导入成功！');</script>";
		}
	}
?>