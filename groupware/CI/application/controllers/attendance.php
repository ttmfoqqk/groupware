<?
class Attendance extends CI_Controller{
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
					set_cookie('left_menu_open_cookie',site_url('attendance/'.$this->PAGE_CONFIG['set_page']),'0');
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
		$data['search_url']    = site_url('attendance/lists/');
		$data['excel_url']     = site_url('attendance/excel/'.$this->PAGE_CONFIG['params_string']);		
		
		$config['base_url']    = site_url('attendance/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->load->view('company/attendance_v',$data);
	}
	
	public function excel(){
		$this->load->library('Excel');
		$excel = new Excel();
		$option = $this->getListOption();
	
		$data['total'] = $this->md_attendance->attendance_history_list($option,null,null,'count');
		$data['list']  = $this->md_attendance->attendance_history_list($option,$data['total'],0);
		
		$title = '근태현황';
		$labels = array(
			'A' => '부서',
			'B' => '사원명',
			'C' => '출근',
			'D' => '퇴근',
			'E' => '지각',
			'F' => '지각점수',
			'G' => '근태누적',
			'H' => '등록일자'
		);
		
		$values=array();
		
		foreach ( $data['list'] as $lt ) {
			$item = array(
					'A' => $lt['menu_name'],
					'B' => $lt['name'],
					'C' => $lt['sData'],
					'D' => $lt['eData'],
					'E' => $lt['oData'],
					'F' => $lt['point'],
					'G' => '',
					'H' => $lt['created']
			);
			array_push($values, $item);
		}
		
		$excel->printExcel($title,$labels,$values);
	}


	public function set(){
		permission_check('att-set','R');
		$this->load->model('baseCode_model');

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
			'parent_key' => 'accrue_lateness_time',
			'is_active'  => 0
		);
		$result = $this->baseCode_model->get_code_list($option);
		if(count($result)>0){
			$accure_lateness = $result[0]['name'];
		}else{
			$accure_lateness = 0;
		}
		$data['accure_lateness'] = $accure_lateness;

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