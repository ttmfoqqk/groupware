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
	
	public function getLikeFilter(){
		$likes['id'] = !$this->input->get('ft_id') ? '' : $this->input->get('ft_id');
		$likes['name'] = !$this->input->get('ft_name') ? '' : $this->input->get('ft_name');
		$likes['type'] = !$this->input->get('ft_type') ? '' : $this->input->get('ft_type');
		$likes['grade'] = !$this->input->get('ft_grade') ? '' : $this->input->get('ft_grade');
		$likes['is_using_question'] = !$this->input->get('ft_use') ? '' : $this->input->get('ft_use');
		return $likes;
	}
	
	public function lists(){
		//필터 설정
		$likes = $this->getLikeFilter();
		
		//Pagination, 테이블정보 필요 설정 세팅
		$tb_show_num = !$this->input->get('tb_num') ? PAGING_PER_PAGE : $this->input->get('tb_num');
		
		$where = null;//array('category'=>$this->CATEGORY);
		
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
			$data['list'] = $result;
		}
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		
		//페이지 타이틀 설정
		$data['action_url'] = site_url('account/proc');
		$data['action_type'] = 'delete';
		$data['head_name'] = "계정관리";
		$data['page'] = $this->CATEGORY;

		//뷰 로딩
		$this->load->view('marketing/account_v',$data);
	}
	
	public function write(){
		$this->md_company->setTable('sw_account');
		$get_no =  $this->uri->segment(3);
		$where = array(
				'no'=>$get_no
		);
		$result = $this->md_company->get($where);
		
		$data['action_url'] = site_url('account/proc');
		
		if (count($result) > 0){
			$data['action_type'] = 'edit';
			$result = $result[0];
			$data['data'] = $result;
		}else{
			$data['action_type'] = 'create';
			$data['data'] = $this->md_company->getEmptyData();
			$data['data']['order'] = 0;
		}
		$data['head_name'] = '계정관리';
		//뷰 로딩
		$this->load->view('marketing/account_write',$data);
	}
	
	public function proc(){
		$this->load->library('form_validation');
		
		$action_type = $this->input->post ( 'action_type' );
		$no = $this->input->post('no');
		$id = $this->input->post('id');
		$passwd = $this->input->post('pass');
		$name = $this->input->post('name');
		$grade = $this->input->post('grade');
		$email = $this->input->post('email');
		$birthday = $this->input->post('birthday');
		$sex = $this->input->post('sex');
		$kind = $this->input->post('kind');
		$use = $this->input->post('use');
		$order = $this->input->post('order');
		
		if( $action_type == 'create' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('id','아이디','required');
			$this->form_validation->set_rules('pass','비밀번호','required');
			$this->form_validation->set_rules('name','이름','required');
			$this->form_validation->set_rules('birthday','생일','required');
			$this->form_validation->set_rules('sex','성별','required');
				
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
				
			$data = array(
					'id'=>$id,
					'pwd'=>$passwd,
					'name'=>$name,
					'grade'=>$grade,
					'email'=>$email,
					'birth'=>$birthday,
					'type'=>$kind,
					'gender'=>$sex,
					'order'=>$order,
			);
				
			$result = $this->md_company->create($data);
			alert('등록되었습니다.', site_url('account' . '/index') );
				
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('no','no.','required');
			$this->form_validation->set_rules('id','아이디','required');
			$this->form_validation->set_rules('pass','비밀번호','required');
			$this->form_validation->set_rules('name','이름','required');
			$this->form_validation->set_rules('birthday','생일','required');
			$this->form_validation->set_rules('sex','성별','required');
				
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
				
			$data = array(
					'id'=>$id,
					'pwd'=>$passwd,
					'name'=>$name,
					'grade'=>$grade,
					'email'=>$email,
					'birth'=>$birthday,
					'type'=>$kind,
					'gender'=>$sex,
					'order'=>$order,
			);
				
			$this->md_company->modify(array('no'=>$no), $data);
			alert('수정되었습니다.', site_url('account' . '/index') );
				
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no', 'no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
				
			$set_no = is_array($no) ? implode(',',$no):$no;
			$where = 'no in (' . $set_no . ')';
			$this->md_company->delete($where);
			alert('삭제되었습니다.', site_url('account' . '/index') );
		}else{
			alert('잘못된 접근입니다.');
		}
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