<?
class Develop extends CI_Controller{
	private $TABLE_NAME = 'sw_information';
	private $CATEGORY = 'develop';
	
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('develop'),'0');
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
		$this->lists();
	}
	
public function getListFilter(){
		$likes['gubun'] = !$this->input->get('ft_classify') ? '' : $this->input->get('ft_classify');
		$likes['bizName'] = !$this->input->get('ft_bizName') ? '' : $this->input->get('ft_bizName');
		$likes['bizNumber'] = !$this->input->get('ft_bizNumber') ? '' : $this->input->get('ft_bizNumber');
		$likes['phone'] = !$this->input->get('ft_phone') ? '' : $this->input->get('ft_phone');
		return $likes;
	}
	
	public function lists(){
		//필터 설정
		$likes = $this->getListFilter();
		$data['filter'] = $likes;		//페이지 필터 값
		$date['start'] = !$this->input->get('ft_start') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_start')));
		$date['end'] = !$this->input->get('ft_end') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_end')));
		$data['date'] = $date;
		
		//Pagination, 테이블정보 필요 설정 세팅
		$tb_show_num = !$this->input->get('tb_num') ? PAGING_PER_PAGE : $this->input->get('tb_num');
		
		if($date['start'] && $date['end']){
			$end = $date['end'];
			$end = date("Y-m-d", strtotime($end."+1 day"));
			$where = array('category'=>$this->CATEGORY, 'created >='=>$date['start'], 'created <'=>$end);
		}
		else
			$where = array('category'=>$this->CATEGORY);
		
		$total = $this->md_company->getCount($where, $likes);
		$uri_segment = 3;
		$cur_page = !$this->uri->segment($uri_segment) ? 1 : $this->uri->segment($uri_segment); // 현재 페이지
		$offset    = ($tb_show_num * $cur_page)-$tb_show_num;
		
		//Pagination 설정
		$config['base_url'] = site_url($this->CATEGORY . '/lists/');
		$config['total_rows'] = $total; // 전체 글갯수
		$config['uri_segment'] = $uri_segment;
		$config['per_page'] = $tb_show_num;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//테이블 정보 설정
		$data['list'] = array();
		$result = $this->md_company->get($where, '*', $tb_show_num, $offset, $likes);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			foreach ($result as $row)
			{
				array_push($data['list'], $row);
			}
		}
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		$data['tb_num'] =  $tb_show_num;		//테이블 row 갯수
		
		//페이지 타이틀 설정
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		$data['head_name'] = "고객사 정보";
		$data['page'] = $this->CATEGORY;
		
		//뷰 로딩
		$this->load->view('company/company_v',$data);
	}
}
/* End of file develop.php */
/* Location: ./controllers/develop.php */