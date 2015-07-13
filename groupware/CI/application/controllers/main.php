<?
class Main extends CI_Controller{
	public function __construct() {
       parent::__construct();
	   delete_cookie('left_menu_open_cookie');
    }

	public function _remap($method){
		login_check();
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
		$this->load->model('main_model','',TRUE);
		$data['user'] = $this->main_model->get_list();
		$this->load->view('main/main_v',$data);
	}
}
/* End of file main.php */
/* Location: ./controllers/main/main.php */