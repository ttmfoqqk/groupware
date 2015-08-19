<?
class Member extends CI_Controller{
	private $TABLE_NAME = 'sw_user';
	private $CATEGORY = 'member';
	private $PAGE_NAME = '사원관리';
	
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('member'),'0');
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
		//$data['list'] = array();
	}
	
	public function write(){
		$data['action_url'] = site_url('member/proc');
		$data['action_type'] = 'delete';
		
		$data['head_name'] = '사원관리';
		$this->load->view('company/member_write',$data);
	}
	
	public function getListFilter(){
		$likes['name'] = !$this->input->get('ft_name') ? '' : $this->input->get('ft_name');
		$likes['phone'] = !$this->input->get('ft_phone') ? '' : $this->input->get('ft_phone');
		$likes['email'] = !$this->input->get('ft_email') ? '' : $this->input->get('ft_email');
		$likes['is_active'] = !$this->input->get('ft_iswork') ? '' : $this->input->get('ft_iswork');
		return $likes;
	}
	
	public function lists(){
		//필터 설정
		$likes = $this->getListFilter();
		$data['filter'] = $likes;		//페이지 필터 값  
		
		//Pagination, 테이블정보 필요 설정 세팅
		$tb_show_num = !$this->input->get('tb_num') ? PAGING_PER_PAGE : $this->input->get('tb_num');
		$where = NULL;
		
		$this->md_company->setTable($this->TABLE_NAME);
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
		$result = $this->md_company->get($where, '*', $tb_show_num, $offset, $likes);
		if (count($result) > 0){
			foreach ($result as $row)
			{
				array_push($data['list'], $row);
			}
		}
		$data['table_num'] = $offset + count($result) . ' / ' . $total;	
		$data['tb_num'] =  $tb_show_num;		//테이블 row 갯수
		
		//페이지 정보 설정
		$data['action_url'] = site_url('member/proc');
		$data['action_type'] = 'delete';
		$data['head_name'] = $this->PAGE_NAME;
		$data['page'] = $this->CATEGORY;
		
		//뷰 로딩
		$this->load->view('company/member_v',$data);
		
	}
	
	public function proc(){
		$this->load->library('form_validation');
		
		$category = $this->input->post('page_cate');
		$action_type = $this->input->post ( 'action_type' );
		$company_no = $this->input->post ( 'company_no' );
		$biz_name = $this->input->post ( 'biz_name' );
		$ceo_name = $this->input->post ( 'ceo_name' );
		$bizNumber = $this->input->post ( 'bizNumber' );
		$classify = $this->input->post ( 'classify' );
		$bizType = $this->input->post ( 'bizType' );
		$bizCondition = $this->input->post ( 'bizCondition' );
		$addr = $this->input->post ( 'addr' );
		$phone = $this->input->post ( 'phone' );
		$fax = $this->input->post ( 'fax' );
		$note = $this->input->post ( 'note' );
		$order = $this->input->post ( 'order' );
		if( $action_type == 'create' ){
			//$category = $this->uri->segment(2);
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('biz_name','상호명','required|max_length[20]');
			$this->form_validation->set_rules('classify','구분','required|max_length[20]');
			$this->form_validation->set_rules('phone','전화번호','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$cur = new DateTime();
			$cur = $cur->format('Y-m-d H:i:s');
			$data = $this->md_company->getSettingData($category, $biz_name, $ceo_name, $classify, $bizType, $bizCondition, 
					$addr, $phone, $fax, $note, $order, $cur, $bizNumber); //Y-m-d H:i:s
			$result = $this->md_company->create($data);
			alert('등록되었습니다.', site_url($category . '/index') );
			
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('biz_name','상호명','required|max_length[20]');
			$this->form_validation->set_rules('classify','구분','required|max_length[20]');
			$this->form_validation->set_rules('phone','전화번호','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$data = $this->md_company->getSettingData($category, $biz_name, $ceo_name, $classify, $bizType, $bizCondition,
					$addr, $phone, $fax, $note, $order, '', $bizNumber);
			
			$this->md_company->modify(array('no'=>$company_no), $data);
			alert('수정되었습니다.' . $category, site_url($category) );
			
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('company_no','','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$set_no = is_array($company_no) ? implode(',',$company_no):$company_no;
			$where = 'no in (' . $set_no . ')';
			$this->md_company->delete($where);
			alert('삭제되었습니다.' . $category , site_url($category) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file member.php */
/* Location: ./controllers/member.php */