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
		$approved_json = json_decode(APPROVED_COUNT_JSON);
		foreach($approved_json->sender as $key=>$value){
			$data['sender'][$key]=$value;
			$data['anchor_s'][$key] = site_url('approved_send/lists/'.$key);
			$data['class_s'][$key] = $value > 0 ? 'text-danger' : '';
		}
		foreach($approved_json->receiver as $key=>$value){
			$data['receiver'][$key]=$value;
			$data['anchor_r'][$key] = site_url('approved_receive/lists/'.$key);
			$data['class_r'][$key] = $value > 0 ? 'text-danger' : '';
		}

		$this->load->view('main/main_v',$data);
	}
}
/* End of file main.php */
/* Location: ./controllers/main/main.php */