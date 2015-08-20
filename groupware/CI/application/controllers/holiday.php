<?
class Holiday extends CI_Controller{
	private $CATEGORY = 'holiday';
	private $TABLE_NAME = 'sw_holiday';
	private $PAGE_NAME = '휴일설정';
	
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('holiday'),'0');
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
		$data['head_name'] = $this->PAGE_NAME;
		$this->load->view('company/holiday_v', $data);
	}
	
	function _list(){
		$year = !$this->input->post('year') ? '' : $this->input->post('year');
		$like = array('date'=>$year);
		$this->load->library("Common");
		$this->md_company->setTable($this->TABLE_NAME);
		$result = $this->md_company->get(NULL, '*',  NULL, NULL, $like, 'date', false);
		echo $this->common->getRet(true, $result);
	}
	
	function _save(){
		$this->load->library("Common");
		
		$datas = $this->input->post('data');
		if(count($datas) <= 0){
			echo $this->common->getRet(false, 'No Data');
		}else{
			$this->md_company->setTable($this->TABLE_NAME);
			$this->md_company->deleteAll();
			foreach ($datas as $data)
				$this->md_company->create($data);
			echo $this->common->getRet(true);
		}
	}
	
}
/* End of file holiday.php */
/* Location: ./controllers/holiday.php */