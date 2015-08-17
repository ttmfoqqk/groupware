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
		$this->md_company->setTable($this->TABLE_NAME);
		$where = NULL;
		$likes = NULL;
	
		$total = $this->md_company->getCount($where, $likes);
		$uri_segment = 3;
		$cur_page = !$this->uri->segment($uri_segment) ? 1 : $this->uri->segment($uri_segment); // 현재 페이지
		$offset    = (PAGING_PER_PAGE * $cur_page)-PAGING_PER_PAGE;
	
		//Pagination 설정
		$config['base_url'] = site_url($this->CATEGORY . '/lists/');
		$config['total_rows'] = $total; // 전체 글갯수
		$config['uri_segment'] = $uri_segment;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
	
		//테이블 정보 설정
		$data['list'] = array();
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		$result = $this->md_company->get($where, '*', PAGING_PER_PAGE, $offset, $likes);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			foreach ($result as $row)
			{
				$row['date'] = new DateTime($row['date']);
				$row['date'] = $row['date']->format('Y-m-d');
				array_push($data['list'], $row);
			}
		}
	
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
	
		//페이지 타이틀 설정
		$data['head_name'] = $this->PAGE_NAME;
		$data['page'] = $this->CATEGORY;
		$this->load->view('company/holiday_v',$data);
	
	}
	
}
/* End of file holiday.php */
/* Location: ./controllers/holiday.php */