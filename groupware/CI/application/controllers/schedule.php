<?
class Schedule extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('project_model');
		
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array(
				'sData'        => !$this->input->get('sData')        ? '' : $this->input->get('sData')       ,
				'eData'        => !$this->input->get('eData')        ? '' : $this->input->get('eData')       ,
				'menu_part_no' => !$this->input->get('menu_part_no') ? '' : $this->input->get('menu_part_no'),
				'menu_no'      => !$this->input->get('menu_no')      ? '' : $this->input->get('menu_no')     ,
				'userName'     => !$this->input->get('userName')     ? '' : $this->input->get('userName')    ,
				'title'        => !$this->input->get('title')        ? '' : $this->input->get('title')
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
				set_cookie('left_menu_open_cookie',site_url('schedule'),'0');
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
		$option['where'] = array();
		$option['like'] = array();
		
		$get_data = $this->project_model->get_schedule($option);
		
		$data['user'] = $get_data['user'];
		
		/*
		 * json return ?
		 * view javascript append 생성
		 */
		$data['list'] = $get_data['list'];
		
		$this->load->view('project/project_schedule_v',$data);
	}
}
/* End of file Schedule.php */
/* Location: ./controllers/Schedule.php */