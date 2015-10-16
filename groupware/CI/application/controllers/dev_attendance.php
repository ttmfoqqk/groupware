<?
class Dev_attendance extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('md_attendance');
		
		$this->PAGE_CONFIG['segment']  = 3;
		$this->PAGE_CONFIG['set_page'] = $this->uri->segment(2);
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment($this->PAGE_CONFIG['segment'],1);
		$this->PAGE_CONFIG['params']   = array(
			'sData'   => !$this->input->get('sData')   ? '' : $this->input->get('sData')   ,
			'eData'   => !$this->input->get('eData')   ? '' : $this->input->get('eData')   ,
			'menu_no' => !$this->input->get('menu_no') ? '' : $this->input->get('menu_no') ,
			'name'    => !$this->input->get('name')    ? '' : $this->input->get('name')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				if($method == 'excel'){
					$this->$method();
				}else{
					set_cookie('left_menu_open_cookie',site_url('attendance/'.$this->PAGE_CONFIG['segment']),'0');
					$this->load->view('inc/header_v');
					$this->load->view('inc/side_v');
					$this->$method();
					$this->load->view('inc/footer_v');
				}
			}else{
				show_error('에러');
			}
		}
	}
	private function getListOption(){
		$option['where'] = array(
			'date_format(h.created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['sData'],
			'date_format(h.created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['eData']
		);
		$option['like'] = array(
			'u.name' => $this->PAGE_CONFIG['params']['name']
		);

		$array_menu = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
		$option['where_in'] = array(
			'ud.menu_no' => $array_menu
		);
		return $option;
	}
	
	
	public function index(){
		$this->lists();
	}
	
	public function lists(){
		permission_check('att-list','R');
		
		$option = $this->getListOption();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->md_attendance->attendance_history_list($option,null,null,'count');
		$data['list']          = $this->md_attendance->attendance_history_list($option,PAGING_PER_PAGE,$offset);
		
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['search_url']    = site_url('dev_attendance/lists/');
		$data['excel_url']     = site_url('dev_attendance/excel/'.$this->PAGE_CONFIG['params_string']);		
		
		$config['base_url']    = site_url('dev_attendance/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->load->view('company/attendance_v',$data);
	}
	
	public function excel(){
		$option = $this->getListOption();
	
		$data['total'] = $this->md_attendance->attendance_history_list($option,null,null,'count');
		$data['list']  = $this->md_attendance->attendance_history_list($option,$data['total'],0);
	
	
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getProperties()->setCreator("groupware");
		$objPHPExcel->getProperties()->setLastModifiedBy("groupware");
		$objPHPExcel->getProperties()->setTitle("근태현황");
		$objPHPExcel->setActiveSheetIndex(0);
	
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
		foreach (range('A', 'H') as $column){
			$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
			$objPHPExcel->getActiveSheet()->getStyle($column.'1')->getFont()->setBold(true);
	
			$objPHPExcel->getActiveSheet()->getStyle($column.'1')->applyFromArray(
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
		}
	
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '부서');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '사원명');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '출근');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '퇴근');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '지각');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '지각점수');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '근태누적');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '등록일자');
	
		$row = 2;
		foreach ( $data['list'] as $lt ) {

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $lt['menu_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $lt['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $lt['sData']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $lt['eData']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $lt['oData']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $lt['point']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, '');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $lt['created']);
			$row ++;
		}
	
		$filename = '근태현황_' . date('Y년 m월 d일 H시 i분 s초', time()) . '.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}


	public function set(){
		permission_check('att-set','R');

		$data['action_url'] = site_url('attendance/save');
		$data['list'] = $this->md_attendance->attendance_list();
		
		//누적 지각,업무 시간
		$option['where'] = array(
			'user_no' => $this->session->userdata('no'),
			'date_format(created,"%Y")' => date('Y')
		);
		$setVla = array(
			'oData' => '00:00:00'
		);
		$result = $this->md_attendance->attendance_history_sum($option,$setVla);
		$data['late_time']    = $result['late_time'];
		$data['working_time'] = $result['working_time'];
		
		
		//누적 지각 옵션 값
		$option['where'] = array(
			'parent_key' => 1
		);
		$result = $this->md_attendance->get_temp_baseCode($option);
		$data['accure_lateness'] = $result['name'];

		$this->load->view('company/attendance_set_v',$data);
	}
	
	public function save(){
		$this->TABLE_NAME = 'sw_attendance';
		$this->md_company->setTable($this->TABLE_NAME);
		
		$this->load->library('form_validation');
		
		$start1 = $this->input->post('start-time1');
		$end1 = $this->input->post ( 'end-time1' );
		$late1 = $this->input->post ( 'late_1' );
		$use1 = $this->input->post ( 'isUsed_1' );
		
		$start2 = $this->input->post('start-time2');
		$end2 = $this->input->post ( 'end-time2' );
		$late2 = $this->input->post ( 'late_2' );
		$use2 = $this->input->post ( 'isUsed_2' );
		
		$start3 = $this->input->post('start-time3');
		$end3 = $this->input->post ( 'end-time3' );
		$late3 = $this->input->post ( 'late_3' );
		$use3 = $this->input->post ( 'isUsed_3' );
		
		$this->form_validation->set_rules('start-time1','주중 출근시간','required');
		$this->form_validation->set_rules('end-time1','주중 퇴근시간','required');
		$this->form_validation->set_rules('late_1','주중 지각','required');
		$this->form_validation->set_rules('isUsed_1','주중 사용여부','required');
		$this->form_validation->set_rules('start-time2','주중 출근시간','required');
		$this->form_validation->set_rules('end-time2','주중 퇴근시간','required');
		$this->form_validation->set_rules('late_2','주중 지각','required');
		$this->form_validation->set_rules('isUsed_2','주중 사용여부','required');
		$this->form_validation->set_rules('start-time3','주중 출근시간','required');
		$this->form_validation->set_rules('end-time3','주중 퇴근시간','required');
		$this->form_validation->set_rules('late_3','주중 지각','required');
		$this->form_validation->set_rules('isUsed_3','주중 사용여부','required');
		
		if ($this->form_validation->run() == FALSE){
			echo validation_errors();
			alert('잘못된 접근입니다.');
		}
		
		
		//배열로 가져와서 순서대로 no 넣기.
		
		$option['where'] = array("no"=>0);
		$values = array('sDate'=>$start1, 'eDate'=>$end1, 'point'=>$late1, 'is_active'=>$use1);
		$this->md_attendance->set_attendance_update($option, $values);
		unset($option);
		unset($values);
		
		$option['where'] = array("no"=>1);
		$values = array('sDate'=>$start2, 'eDate'=>$end2, 'point'=>$late2, 'is_active'=>$use2);
		$this->md_attendance->set_attendance_update($option, $values);
		unset($option);
		unset($values);
		
		$option['where'] = array("no"=>2);
		$values = array('sDate'=>$start3, 'eDate'=>$end3, 'point'=>$late3, 'is_active'=>$use3);
		$this->md_attendance->set_attendance_update($option, $values);
		unset($option);
		unset($values);
		
		alert('수정되었습니다.', site_url('attendance/set') );
	}

}
/* End of file attendance.php */
/* Location: ./controllers/attendance.php */