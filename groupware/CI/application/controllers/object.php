<?
class Object extends CI_Controller{
	private $TABLE_NAME = 'sw_object';
	private $CATEGORY = 'object';
	private $PAGE_NAME = '물품정보';
	
	public function __construct() {
		parent::__construct();
		set_cookie('left_menu_open_cookie',site_url('object'),'0');
		login_check();
		$this->load->model('md_object');
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
		$start = $this->input->get('ft_start');
		$end = $this->input->get('ft_end');
		$kind = $this->input->get('ft_kind');
		$itemName = $this->input->get('ft_itemName');
		$area = $this->input->get('ft_area');
		$userName = $this->input->get('ft_usrName');
		$tb_show_num = $this->input->get('tb_num');
		
		$likes['m.no'] = $likes['o.name'] =$likes['o.area'] =$likes['u.name'] = $likes['created'] = '';
		$date['start'] = $date['end']= $data['tb_num'] = NULL;
		if($kind)
			$likes['m.no'] = $kind;
		if($itemName)
			$likes['o.name'] = $itemName;
		if($area)
			$likes['o.area'] = $area;
		if($userName)
			$likes['u.name'] = $userName;
		$data['filter'] = $likes;
		
		//Pagination, 테이블정보 필요 설정 세팅
		if($start && $end){
			$start = new DateTime($start);
			$start = $start->format('Y-m-d');
			$end = new DateTime($end);
			date_modify($end, '+1 day');
			$end = $end->format('Y-m-d');
			$where = array('o.created >='=>$start, 'o.created <'=>$end);
			$end_t = new DateTime($end);
			date_modify($end_t, '-1 day');
			$end_t = $end_t->format('Y-m-d');
			$date['start'] = $start;
			$date['end'] = $end_t;
		}
		else
			$where = null;//array('category'=>$this->CATEGORY);
		
		//테이블 한번에 보일 행 개수 설정
		if($tb_show_num){
			$data['tb_num'] = $page_per_num = $tb_show_num;
		}
		else{
			$data['tb_num'] = $page_per_num = PAGING_PER_PAGE;
		}
		$total = $this->md_object->getObjectCount($where, $likes);
		$uri_segment = 3;
		$cur_page = !$this->uri->segment($uri_segment) ? 1 : $this->uri->segment($uri_segment); // 현재 페이지
		$offset    = ($page_per_num * $cur_page)-$page_per_num;
		
		//Pagination 설정
		$config['base_url'] = site_url($this->CATEGORY . '/lists/');
		$config['total_rows'] = $total; // 전체 글갯수
		$config['uri_segment'] = $uri_segment;
		$config['per_page'] = $page_per_num;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//테이블 정보 설정
		$data['list'] = array();
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		$result = $this->md_object->getObject($where, $likes, $page_per_num, $offset);

		if (count($result) > 0){
			foreach ($result as $row)
			{
				array_push($data['list'], $row);
			}
		}
		
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		
		
		//페이지 타이틀 설정
		$data['head_name'] = "물품정보";
		$data['page'] = $this->CATEGORY;
		$data['date'] = $date;
		$this->load->view('company/object_v',$data);
	}
}
/* End of file object.php */
/* Location: ./controllers/object.php */