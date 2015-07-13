<?
class Attendance extends CI_Controller{
	public function __construct() {
		parent::__construct();
		login_check();
		$param = $this->uri->segment(2);
		set_cookie('left_menu_open_cookie',site_url('attendance/'.$param),'0');
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
		$data['list'] = array();
		$this->load->view('company/attendance_v',$data);
	}


	public function set(){
		$data['list'] = array();
		$this->load->view('company/attendance_set_v',$data);
	}

}
/* End of file attendance.php */
/* Location: ./controllers/attendance.php */