<?
class Attendance extends CI_Controller{
	private $TABLE_NAME = 'sw_attendance';
	private $PAGE_NAME = '근태설정';
	
	public function __construct() {
		parent::__construct();
		login_check();
		$param = $this->uri->segment(2);
		set_cookie('left_menu_open_cookie',site_url('attendance/'.$param),'0');
		$this->load->model('md_company');
		$this->load->model('md_attendance');
		$this->lang->load('company_info', 'korean');
    }

	public function _remap($method){
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				$this->load->view('inc/header_v');
				$this->load->view('inc/side_v');
				$this->$method();
				$this->load->view('inc/footer_v');
			}else{
				show_error('에러');
			}
		}
	}
	public function index(){
		$this->lists();
	}
	
	public function getListFilter(){
		$likes['m.name'] = !$this->input->get('ft_department') ? '' : $this->input->get('ft_department');
		$likes['u.name'] =!$this->input->get('ft_userName') ? '' : $this->input->get('ft_userName');
		return $likes;
	}
	
	public function lists(){
		$this->CATEGORY = 'attendance';
		$this->TABLE_NAME = 'sw_attendance_history';
		$this->PAGE_NAME = '근태현황';
		
		//필터 설정
		$likes = $this->getListFilter();
		$data['filter'] = $likes;		//페이지 필터 값
		$date['start'] = !$this->input->get('ft_start') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_start')));
		$date['end'] = !$this->input->get('ft_end') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_end')));
		$data['date'] = $date;
		
		
		//Pagination, 테이블정보 필요 설정 세팅
		$tb_show_num = !$this->input->get('tb_num') ? PAGING_PER_PAGE : $this->input->get('tb_num');
		if($date['start'] && $date['end']){
			$end = $date['end'];
			$end = date("Y-m-d", strtotime($end."+1 day"));
			$where = array('h.created >='=>$date['start'], 'h.created <'=>$end);
		}else
			$where = NULL;
		
		$total = $this->md_attendance->getAttendanceCount($where, $likes);
		$uri_segment = 3;
		$cur_page = !$this->uri->segment($uri_segment) ? 1 : $this->uri->segment($uri_segment); // 현재 페이지
		$offset    = ($tb_show_num * $cur_page)-$tb_show_num;
		
		//Pagination 설정
		$config['base_url'] = site_url($this->CATEGORY . '/lists/');
		$config['total_rows'] = $total; // 전체 글갯수
		$config['uri_segment'] = $uri_segment;
		$config['per_page'] = $tb_show_num;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//테이블 정보 설정
		$data['list'] = array();
		$result = $this->md_attendance->getAttendance($where, $likes, PAGING_PER_PAGE, $offset);
		if (count($result) > 0){
			$data['list'] = $result;
		}
		
		$data['department'] = array();
		$this->md_company->setTable('sw_menu');
		$department = $this->md_company->get(array('category'=>'department'));
		//array_push($data['department'], $this->lang->line('all'));
		if (count($department) > 0){
			$data['department'] = $department;
		}
		
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		$data['tb_num'] =  $tb_show_num;		//테이블 row 갯수
		
		//페이지 타이틀 설정
		$data['head_name'] = "회사정보";
		$data['page'] = $this->CATEGORY;
		$data['date'] = $date;
		
		
		$this->load->view('company/attendance_v',$data);
	}


	public function set(){
		$this->TABLE_NAME = 'sw_attendance';
		$this->PAGE_NAME = '근태설정';
		$this->md_company->setTable($this->TABLE_NAME);
		$where = NULL;
		$likes = NULL;
		
		//테이블 정보 설정
		$data['list'] = array();
		$data['action_url'] = site_url('attendance/save');
		$result = $this->md_company->get($where);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			$data['list'] = $result;
		}
		
		//지각 시간
		$this->md_company->setTable('sw_attendance_history');
		$ret = $this->md_company->get(array('user_no'=>$this->session->userdata('no')), 'oData');
		$tHour = $tMinute = $tSec = 0;
		if(count($ret) > 0){
			foreach ($ret as $oDate){
				if($oDate != null){
					$mt = explode(':', $oDate['oData']);
					$tHour = $tHour + $mt[0];
					$tMinute = $tMinute + $mt[1];
					$tSec = $tSec + $mt[2];
				}
			}
		}
		$tH = floor($tMinute/60);
		$tMinute = $tMinute%60;
		$tHour = $tHour + $tH;
		$data['late_time'] = $tHour . '시간 ' . $tMinute . '분 '. $tSec . '초';
		
		//업무시간
		$ret = $this->md_company->get(array('user_no'=>$this->session->userdata('no'), 'sData >='=>date('Y') . "-01-01 00:00:00", 'eData >='=>date('Y') . "-01-01 00:00:00"));
		$tHour = $tMinute = $tSec = 0;
		if(count($ret) > 0){
			foreach ($ret as $oDate){
				$sDate = strtotime($oDate['sData']);
				$eDate = strtotime($oDate['eData']);
				$diff_in = gmdate("H:i:s", ($eDate - $sDate));
				
				$mt = explode(':', $diff_in);
				$tHour = $tHour + $mt[0];
				$tMinute = $tMinute + $mt[1];
				$tSec = $tSec + $mt[2];
			}
		}
		$tH = floor($tMinute/60);
		$tMinute = $tMinute%60;
		$tHour = $tHour + $tH;
		$data['working_time'] = $tHour . '시간 ' . $tMinute . '분 '. $tSec . '초';
		
		//누적 지각 옵션 값
		$this->md_company->setTable('sw_base_code');
		$ret = $this->md_company->get(array('key'=>'accrue_lateness_time'), 'name');
		print_r($ret);
		$data['accure_lateness'] = isset($ret[0]['name']) ? $ret[0]['name'] : '';
		
		//페이지 타이틀 설정
		$data['head_name'] = $this->PAGE_NAME;
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
		$this->md_company->modify(array("no"=>0), array('sDate'=>$start1, 'eDate'=>$end1, 'point'=>$late1, 'is_active'=>$use1));
		$this->md_company->modify(array("no"=>1), array('sDate'=>$start2, 'eDate'=>$end2, 'point'=>$late2, 'is_active'=>$use2));
		$this->md_company->modify(array("no"=>2), array('sDate'=>$start3, 'eDate'=>$end3, 'point'=>$late3, 'is_active'=>$use3));
		
		alert('수정되었습니다.', site_url('attendance/set') );
	}

}
/* End of file attendance.php */
/* Location: ./controllers/attendance.php */