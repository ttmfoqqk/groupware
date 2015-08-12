<?
class Company extends CI_Controller{
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('company'),'0');
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
		//필터 설정
		$classify = $this->input->get('ft_classify');
		$bizName = $this->input->get('ft_bizName');
		$bizNumber = $this->input->get('ft_bizNumber');
		$phone = $this->input->get('ft_phone');
		
		$likes['gubun'] = $likes['bizName'] =$likes['bizNumber'] =$likes['phone'] = '';
		if($classify)
			$likes['gubun'] = $classify;
		if($bizName)
			$likes['bizName'] = $bizName;
		if($bizNumber)
			$likes['bizNumber'] = $bizNumber;
		if($phone)
			$likes['phone'] = $phone;
		$data['filter'] = $likes;
		
		//Pagination, 테이블정보 필요 정보 세팅
		$where = array('category'=>'company');
		$total = $this->md_company->getCount($where, $likes);
		$cur_page = !$this->uri->segment(3) ? 1 : $this->uri->segment(3); // 현재 페이지
		$per_page  = 10; // 글 갯수
		$offset    = ($per_page * $cur_page)-$per_page;
		
		//Pagination 설정
		$config['base_url'] = site_url('company/lists/');
		$config['total_rows'] = $total; // 전체 글갯수
		$config['per_page'] = $per_page;  // 보여질 갯수
		$config['uri_segment'] = 3;
		$config['num_links'] = 4; // 선택 페이지 좌우 링크 갯수
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//테이블 정보 설정
		$data['list'] = array();
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		$result = $this->md_company->get($where, '*', $per_page, $offset, $likes);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			foreach ($result as $row)
			{
				array_push($data['list'], $row);
			}
		}
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		
		//페이지 타이틀 설정
		$data['head_name'] = "회사정보";
		
		$this->load->view('company/company_v',$data);
	}
}
/* End of file company.php */
/* Location: ./controllers/company.php */