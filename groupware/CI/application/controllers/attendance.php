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
		if($date['start'] && $date['end']){
			$end = $date['end'];
			$end = date("Y-m-d", strtotime($end."+1 day"));
			$where = array('h.created >='=>$date['start'], 'h.created <'=>$end);
		}else
			$where = NULL;
		
		$total = $this->md_attendance->getAttendanceCount($where, $likes);
		$uri_segment = 3;
		$cur_page = !$this->uri->segment($uri_segment) ? 1 : $this->uri->segment($uri_segment); // 현재 페이지
		$offset    = (PAGING_PER_PAGE * $cur_page)-PAGING_PER_PAGE;
		
		//Pagination 설정
		$config['base_url'] = site_url($this->CATEGORY . '/lists/');
		$config['total_rows'] = $total; // 전체 글갯수
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//테이블 정보 설정
		$data['list'] = array();
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		$result = $this->md_attendance->getAttendance($where, $likes, PAGING_PER_PAGE, $offset);
		if (count($result) > 0){
			foreach ($result as $row)
			{
				array_push($data['list'], $row);
			}
		}
		
		$data['department'] = array();
		$this->md_company->setTable('sw_menu');
		$department = $this->md_company->get(array('category'=>'department'));
		//array_push($data['department'], $this->lang->line('all'));
		if (count($department) > 0){
			foreach ($department as $row)
			{
				array_push($data['department'], $row);
			}
		}
		
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		
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
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		$result = $this->md_company->get($where);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			foreach ($result as $row)
			{
				array_push($data['list'], $row);
			}
		}
		
		//페이지 타이틀 설정
		$data['head_name'] = $this->PAGE_NAME;
		$this->load->view('company/attendance_set_v',$data);
	}

}
/* End of file attendance.php */
/* Location: ./controllers/attendance.php */