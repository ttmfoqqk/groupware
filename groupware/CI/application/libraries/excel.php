<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel {
	public function __construct(){
		$CI =& get_instance();
		$CI->load->library('PHPExcel');
	}
	
	/**
	 * 엑셀 출력
	 * @param string $title
	 * @param array $labels
	 * @param array $values
	 */
	function printExcel($title='제목없음',$labels=array(),$values=array()){
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("groupware");
		$objPHPExcel->getProperties()->setLastModifiedBy("groupware");
		$objPHPExcel->getProperties()->setTitle($title);
		$objPHPExcel->setActiveSheetIndex(0);

		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);

		foreach($labels as $key => $value){
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(20);
			$objPHPExcel->getActiveSheet()->getStyle($key.'1')->getFont()->setBold(true);

			$objPHPExcel->getActiveSheet()->getStyle($key.'1')->applyFromArray(
				array(
					'font' => array(
						'bold' => true,
						'size' => 14
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						'wrap'       => true
					)
				)
			);
			$objPHPExcel->getActiveSheet()->setCellValue($key.'1', $value);
		}

		$row = 2;
		foreach( $values as $value ){
			foreach($value as $key=>$value){
				$objPHPExcel->getActiveSheet()->setCellValueExplicit($key.$row, $value,PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$row ++;
		}

		$filename = $title . '_' . date('Y년 m월 d일 H시 i분 s초', time()) . '.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}    
}

/* End of file excel.php */
/* Location: ./application/libraries/excel.php */