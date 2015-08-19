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
		$this->md_company->setTable('sw_user');
		$get_no = $page_method = $this->uri->segment(3);
		$where = array(
				'no'=>$get_no
		);
		$result = $this->md_company->get($where);
		
		$data['action_url'] = site_url('member/proc');
		
		if (count($result) > 0){
			$data['action_type'] = 'edit';
			$result = $result[0];
			$data['data'] = $result;
		}else{
			$data['action_type'] = 'create';
			$data['data'] = $this->md_company->getEmptyData();
			$data['data']['order'] = 0;
		}
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
		print_r($this->md_company->getUserByDepartment(2));
		//뷰 로딩
		$this->load->view('company/member_v',$data);
		
	}
	
	public function _lists(){
		$dptNum = $this->input->post('menu_no');
		echo $this->md_company->getUsersByDepartment($dptNum);
	}
	
	public function proc(){
		$this->load->library('form_validation');
		$this->md_company->setTable($this->TABLE_NAME);
		
		$action_type = $this->input->post ( 'action_type' );
		//$file = $this->input->post('userfile');
		$no = $this->input->post('no');
		$id = $this->input->post('id');
		$passwd = $this->input->post('pass');
		$name = $this->input->post('name');
		$position = $this->input->post('position');
		$phone = $this->input->post('phone');
		$selPhone = $this->input->post('sel_phone');
		$email = $this->input->post('email');
		$addr = $this->input->post('addr');
		$anual = $this->input->post('annual');
		$anual_start = $this->input->post('anual_start');
		$anual_end = $this->input->post('anual_end');
		$birthday = $this->input->post('birthday');
		$joinDate = $this->input->post('join_date');
		$sex = $this->input->post('sex');
		$color = $this->input->post('color');
		$order = $this->input->post('order');
		$inOffice = $this->input->post('in_office');
		
		if( $action_type == 'create' ){
			//$category = $this->uri->segment(2);
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('id','아이디','required|max_length[5]');
			$this->form_validation->set_rules('pass','비밀번호','required|max_length[5]');
			$this->form_validation->set_rules('name','이름','required');
			$this->form_validation->set_rules('position','직급','required');
			$this->form_validation->set_rules('sel_phone','핸드폰 번호','required');
			$this->form_validation->set_rules('email','직급','required');
			$this->form_validation->set_rules('annual','연차','required');
			$this->form_validation->set_rules('anual_start','연차적용일 시작','required');
			$this->form_validation->set_rules('anual_end','연차적용일 끝','required');
			$this->form_validation->set_rules('birthday','생일','required');
			$this->form_validation->set_rules('join_date','입사일','required');
			$this->form_validation->set_rules('color','색상','required');
			
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			
			$file = NULL;
			if( isset($_FILES['userfile']) ) {
				$config['upload_path'] = 'upload/member/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = true;
					
				$this->load->library('upload', $config);
					
				if ( !$this->upload->do_upload() ){
					$file = NULL;
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file = $upload_data['file_name'];
				}
			}
			
			$cur = new DateTime();
			$cur = $cur->format('Y-m-d H:i:s');
			$data = array(
					'id'=>$id,
					'pwd'=>$passwd,
					'name'=>$name,
					'phone'=>$phone,
					'mobile'=>$selPhone,
					'email'=>$email,
					'addr'=>$addr,
					'annual'=>$anual,
					'sDate'=>$anual_start,
					'eDate'=>$anual_end,
					'birth'=>$birthday,
					'gender'=>$sex,
					'inDate'=>$joinDate,
					'color'=>$color,
					'file'=>$file,
					'order'=>$order,
					'is_active'=>$inOffice,
					'created'=>$cur,
					'position'=>$position,
			);
			
			$result = $this->md_company->create($data);
			alert('등록되었습니다.', site_url('member' . '/index') );
			
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('id','아이디','required|max_length[5]');
			$this->form_validation->set_rules('pass','비밀번호','required|max_length[5]');
			$this->form_validation->set_rules('name','이름','required');
			$this->form_validation->set_rules('position','직급','required');
			$this->form_validation->set_rules('sel_phone','핸드폰 번호','required');
			$this->form_validation->set_rules('email','직급','required');
			$this->form_validation->set_rules('annual','연차','required');
			$this->form_validation->set_rules('anual_start','연차적용일 시작','required');
			$this->form_validation->set_rules('anual_end','연차적용일 끝','required');
			$this->form_validation->set_rules('birthday','생일','required');
			$this->form_validation->set_rules('join_date','입사일','required');
			$this->form_validation->set_rules('color','색상','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			alert('goood');
			return;
			
			$file = NULL;
			if( isset($_FILES['userfile']) ) {
				$config['upload_path'] = 'upload/member/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['remove_spaces'] = true;
					
				$this->load->library('upload', $config);
					
				if ( !$this->upload->do_upload() ){
					$file = NULL;
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file = $upload_data['file_name'];
				}
			}
			
			$data = array(
					'id'=>$id,
					'pwd'=>$passwd,
					'name'=>$name,
					'phone'=>$phone,
					'mobile'=>$selPhone,
					'email'=>$email,
					'addr'=>$addr,
					'annual'=>$anual,
					'sDate'=>$anual_start,
					'eDate'=>$anual_end,
					'birth'=>$birthday,
					'gender'=>$sex,
					'inDate'=>$joinDate,
					'color'=>$color,
					'file'=>$file,
					'order'=>$order,
					'is_active'=>$inOffice,
			);
			
			$this->md_company->modify(array('no'=>$no), $data);
			alert('수정되었습니다.', site_url('member' . '/index') );
			
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('company_no','','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			alert('goood');
			return;
			
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