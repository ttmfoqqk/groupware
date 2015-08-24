<?
class BaseCode extends CI_Controller{
	private $TABLE_NAME = 'sw_base_code';
	private $PAGE_NAME = '기초코드';
	
	public function __construct() {
		parent::__construct();
		set_cookie('left_menu_open_cookie',site_url('baseCode'),'0');
		login_check();
		$this->load->model("md_company");
		$this->md_company->setTable($this->TABLE_NAME);
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
		
		//페이지 정보 설정
		$data['head_name'] = $this->PAGE_NAME;
		
		//뷰 로딩
		$this->load->view('company/baseCode_v',$data);
	}
	
	
	public function _keyList(){
		$this->load->library('common');
		$ret = $this->md_company->get(array('parent_key'=>NULL));
		
		if(count($ret) > 0)
			echo $this->common->getRet(true, $ret);
		else
			echo $this->common->getRet(false, "No data");
	}
	
	public function _codeList(){
		$this->load->library('common');
		$ret = $this->md_company->get('parent_key IS NOT NULL');
	
		if(count($ret) > 0)
			echo $this->common->getRet(true, $ret);
		else
			echo $this->common->getRet(false, "No data");
	}
}
/* End of file baseCode.php */
/* Location: ./controllers/baseCode.php */