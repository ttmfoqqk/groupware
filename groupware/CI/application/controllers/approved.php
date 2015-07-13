<?
class Approved extends CI_Controller{
	public function __construct() {
       parent::__construct();
    }

	public function _remap($method){
		login_check();
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				if( $method=='send' ){
					set_cookie('left_menu_open_cookie',site_url('approved/send'),'0');
					define('PAGE_TITLE', '보낸결재');
				}else{
					set_cookie('left_menu_open_cookie',site_url('approved/receive'),'0');
					define('PAGE_TITLE', '받은결재');
				}
				
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
		$this->send();
	}

	public function send(){
		$data['list'] = array();
		$this->load->view('approved/list_v',$data);
	}
	public function receive(){
		$data['list'] = array();
		$this->load->view('approved/list_v',$data);
	}
}
/* End of file approved.php */
/* Location: ./controllers/approved.php */