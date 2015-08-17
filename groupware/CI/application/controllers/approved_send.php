<?
class Approved_send extends CI_Controller{
	private $GLOBAL;
	public function __construct() {
       parent::__construct();
	   $this->GLOBAL['cur_page'] = $this->uri->segment(4,1);
	   $this->GLOBAL['set_page'] = $this->uri->segment(3,'all');
    }

	public function _remap($method){
		login_check();
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				set_cookie('left_menu_open_cookie',site_url('approved_send/lists/'.$this->GLOBAL['set_page']),'0');
				
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

		$config['base_url']    = site_url('approved_send/lists/');
		$config['total_rows']  = 0;
		$config['cur_page']    = $this->uri->segment(3,1);
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);
		$data['pagination']    = $this->pagination->create_links();
		$this->load->view('approved/list_send_v',$data);
	}
}
/* End of file approved_send.php */
/* Location: ./controllers/approved_send.php */