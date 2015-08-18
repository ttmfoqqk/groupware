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
	
	public function lists(){
		$this->CATEGORY = 'attendance';
		$this->TABLE_NAME = 'sw_attendance_history';
		$this->PAGE_NAME = '근태현황';
		
		//필터 설정
		$start = $this->input->get('ft_start');
		$end = $this->input->get('ft_end');
		$department = $this->input->get('ft_department');
		if($department == "전체")
			$department = NULL;
		$userName = $this->input->get('ft_userName');
		$likes['m.name'] = $likes['u.name']  = '';
		$date['start'] = $date['end']= NULL;
		if($department)
			$likes['m.name'] = $department;
		if($userName)
			$likes['u.name'] = $userName;
		$data['filter'] = $likes;
		
		//Pagination, 테이블정보 필요 설정 세팅
		if($start && $end){
			$start = new DateTime($start);
			$start = $start->format('Y-m-d');
			$end = new DateTime($end);
			date_modify($end, '+1 day');
			$end = $end->format('Y-m-d');
			$where1 = array('created >='=>$start, 'created <'=>$end);
			$where2 = array('h.created >='=>$start, 'h.created <'=>$end); //array('category'=>$this->CATEGORY, 'created >='=>$start, 'created <'=>$end);
			$end_t = new DateTime($end);
			date_modify($end_t, '-1 day');
			$end_t = $end_t->format('Y-m-d');
			$date['start'] = $start;
			$date['end'] = $end_t;
		}
		else
			$where2 = NULL; //array('category'=>$this->CATEGORY);
		$total = $this->md_attendance->getAttendanceCount($where2, $likes);
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
		$result = $this->md_attendance->getAttendance($where2, $likes);//$this->md_company->get($where, '*', PAGING_PER_PAGE, $offset, $likes);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
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