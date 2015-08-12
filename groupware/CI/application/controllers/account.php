<?
class Account extends CI_Controller{
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('account'),'0');
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

		$config['base_url']    = site_url('purpose/add/');
		$config['total_rows']  = 0;
		$config['cur_page']    = $this->uri->segment(3,1);
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);
		$data['pagination']    = $this->pagination->create_links();


		$this->load->view('marketing/account_v',$data);
	}
}
/* End of file account.php */
/* Location: ./controllers/account.php */