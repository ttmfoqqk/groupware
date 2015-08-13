<?
class Partner extends CI_Controller{
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('partner'),'0');	
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
	public function index() {
		$this->lists ();
	}

	public function lists(){
		//필터 설정
		$start = $this->input->get('ft_start');
		$end = $this->input->get('ft_end');
		$classify = $this->input->get('ft_classify');
		$bizName = $this->input->get('ft_bizName');
		$bizNumber = $this->input->get('ft_bizNumber');
		$phone = $this->input->get('ft_phone');
	
		$likes['gubun'] = $likes['bizName'] =$likes['bizNumber'] =$likes['phone'] = $likes['created'] = '';
		$date['start'] = $date['end']= NULL;
		if($classify)
			$likes['gubun'] = $classify;
		if($bizName)
			$likes['bizName'] = $bizName;
		if($bizNumber)
			$likes['bizNumber'] = $bizNumber;
		if($phone)
			$likes['phone'] = $phone;
		$data['filter'] = $likes;
	
		//Pagination, 테이블정보 필요 설정 세팅
		if($start && $end){
			$start = new DateTime($start);
			$start = $start->format('Y-m-d');
			$end = new DateTime($end);
			date_modify($end, '+1 day');
			$end = $end->format('Y-m-d');
			$where = array('category'=>'company', 'created >='=>$start, 'created <'=>$end);
			$end_t = new DateTime($end);
			date_modify($end_t, '-1 day');
			$end_t = $end_t->format('Y-m-d');
			$date['start'] = $start;
			$date['end'] = $end_t;
		}
		else
			$where = array('category'=>'acount');
	
		$total = $this->md_company->getCount($where, $likes);
		$uri_segment = 3;
		$cur_page = !$this->uri->segment($uri_segment) ? 1 : $this->uri->segment($uri_segment); // 현재 페이지
		$offset    = (PAGING_PER_PAGE * $cur_page)-PAGING_PER_PAGE;
	
		//Pagination 설정
		$config['base_url'] = site_url('company/lists/');
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
				array_push($data['list'], $row);
			}
		}
	
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
	
		//페이지 타이틀 설정
		$data['head_name'] = "거래처";
		$data['date'] = $date;
		$this->load->view('company/company_v',$data);
	}
}
/* End of file partner.php */
/* Location: ./controllers/partner.php */