<?
class Purpose extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		
		$this->load->model('purpose_model');
		//현재 페이지 
		$this->PAGE_CONFIG['set_page'] = $this->uri->segment(2,'search');
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array(
			'sData'      => !$this->input->get('sData')      ? '' : $this->input->get('sData')      ,
			'eData'      => !$this->input->get('eData')      ? '' : $this->input->get('eData')      ,
			'department' => !$this->input->get('department') ? '' : $this->input->get('department') ,
			'user_name'  => !$this->input->get('user_name')  ? '' : $this->input->get('user_name')  ,
			'title'      => !$this->input->get('title')      ? '' : $this->input->get('title')      ,
			'point'      => !$this->input->get('point')      ? '' : $this->input->get('point')
		);
		//링크용 파라미터 쿼리
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				set_cookie('left_menu_open_cookie',site_url('purpose/'.$this->PAGE_CONFIG['set_page']),'0');
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
		$this->search();
	}
	public function search(){
		$data['list'] = array();
		$this->load->view('purpose/search_v',$data);
	}
	public function appraisal(){
		$option['where'] = array(
			'' => ''
		);
		$option['like'] = array(
			'' => ''
		);
		$data['list'] = $this->purpose_model->get_point_approved($option);
		$this->load->view('purpose/appraisal_v',$data);
	}
}
/* End of file purpose.php */
/* Location: ./controllers/purpose.php */