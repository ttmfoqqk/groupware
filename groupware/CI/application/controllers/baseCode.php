<?
class BaseCode extends CI_Controller{
	private $TABLE_NAME = 'sw_base_code';
	private $PAGE_NAME = '기초코드';
	
	public function __construct() {
		parent::__construct();
		$this->load->model("md_company");
		$this->md_company->setTable($this->TABLE_NAME);
    }

	public function _remap($method){
		login_check();
		permission_check('baseCode','R');
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				set_cookie('left_menu_open_cookie',site_url('baseCode'),'0');
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
		
		$no = $this->input->post('no') ? $this->input->post('no') : '';
// 		$ret = $this->md_company->get('parent_key IS NOT NULL');
		$ret = $this->md_company->get(array('parent_key'=>$no));
	
		if(count($ret) > 0)
			echo $this->common->getRet(true, $ret);
		else
			echo $this->common->getRet(false, "No data");
	}
	
	public function _createKey(){
		$this->load->library('common');
		
		$datas = $this->input->post('data');
		if($datas['method'] == 'create'){
			unset($datas['method']);
			$this->md_company->create($datas);
			
			echo $this->common->getRet(true, '등록 하였습니다.');
		}else if($datas['method'] == 'modify'){
			$no = $datas['no'];
			unset($datas['method']);
			unset($datas['no']);
			
			$this->md_company->modify(array('no'=>$no), $datas);
			echo $this->common->getRet(true, '변경 하였습니다.');
		}else 
			echo $this->common->getRet(false, '잘못된 입력입니다');
	}
	
	public function _createCode(){
		$this->load->library('common');
		
		$datas = $this->input->post('data');
		if($datas['method'] == 'create'){
			unset($datas['method']);
			$this->md_company->create($datas);
			
			echo $this->common->getRet(true, '등록 하였습니다.');
		}else if($datas['method'] == 'modify'){
			$no = $datas['no'];
			unset($datas['method']);
			unset($datas['no']);
			
			$this->md_company->modify(array('no'=>$no), $datas);
			echo $this->common->getRet(true, '변경 하였습니다.');
		}else if($datas['method'] == 'remove'){
			if(!isset($datas['ids']) || empty($datas['ids']))
				echo $this->common->getRet(false, '삭제 대상이 없습니다.');
			else{
				$this->md_company->deleteIn('no', $datas['ids']);
				echo $this->common->getRet(true, '삭제 하였습니다.');
			}
		}else
			echo $this->common->getRet(false, '잘못된 입력입니다');
	}
}
/* End of file baseCode.php */
/* Location: ./controllers/baseCode.php */