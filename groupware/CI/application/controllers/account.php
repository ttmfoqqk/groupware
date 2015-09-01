<?
class Account extends CI_Controller{
	private $TABLE_NAME = 'sw_account';
	private $CATEGORY = 'account';

	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('account'),'0');
		$this->load->model('md_company');
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

		$config['base_url']    = site_url('purpose/add/');
		$config['total_rows']  = 0;
		$config['cur_page']    = $this->uri->segment(3,1);
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);
		$data['pagination']    = $this->pagination->create_links();


		$this->load->view('marketing/account_v',$data);
	}
	
	public function _selectList(){
		$this->load->library('common');
		
		$no = !$this->input->post('accountNo') ? NULL : $this->input->post('accountNo');
		$chc_no = !$this->input->post('chcNo') ? false : $this->input->post('chcNo');
		
		if($no == NULL)
			if($chc_no){
				$where = "chc_no =" . $chc_no . " OR chc_no is NULL";
			}
			else
				$where = "chc_no is NULL";
		else{
			$where = array('no'=>$no);
		}
		
		$ret = $this->md_company->get($where);
		if(count($ret) > 0){
			echo $this->common->getRet(true, $ret);
		}else 
			echo $this->common->getRet(false, 'No ID');
	}
	
	public function _usedlist(){
		$this->load->library('common');
		$chcNo = !$this->input->post('chcNo') ? NULL : $this->input->post('chcNo');
		
		if($chcNo != NULL)
			$where = "chc_no =" . $chcNo;// . " OR chc_no is NULL";
		else{
			echo $this->common->getRet(false, 'No Id List');
			return;
		}
		
		$ret = $this->md_company->get($where);
		if(count($ret) > 0){
			echo $this->common->getRet(true, $ret);
		}else
			echo $this->common->getRet(false, 'No data');
	}
}
/* End of file account.php */
/* Location: ./controllers/account.php */