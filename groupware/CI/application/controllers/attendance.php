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