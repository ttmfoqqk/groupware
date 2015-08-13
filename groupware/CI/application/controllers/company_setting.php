<?
class Company_setting extends CI_Controller{
	public function __construct() {
       parent::__construct();
	   set_cookie('left_menu_open_cookie',site_url('company_setting/lists'),'0');
	   $this->load->model('md_company');
	   $this->lang->load('company_info', 'korean');
    }

	public function _remap($method){
		login_check();

			if(method_exists($this, $method)){
				$this->load->view('inc/header_v');
				$this->load->view('inc/side_v');
				$this->$method();
				$this->load->view('inc/footer_v');
			}else{
				show_error('에러');
			}
	}

	public function index(){
		$this->lists();
	}

	public function lists(){
		$option = array(
			'activated'=>0
		);
		$data['list'] = $this->board_model->get_setting_list($option);
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		
		/*
			http://codeigniter-kr.org/user_guide_2.1.0/libraries/pagination.html
		*/
		$config['base_url'] = site_url('company_setting/lists');
		$config['total_rows'] = 200; // 전체 글갯수
		$config['per_page'] = 10;  // 보여질 갯수
		$config['uri_segment'] = 3;
		$config['num_links'] = 4; // 선택 페이지 좌우 링크 갯수

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('board/setting/list_v',$data);
	}

	public function write(){
		$get_no = $page_method = $this->uri->segment(3); //$this->input->post ( 'no' ); //
		$where = array(
			'no'=>$get_no
		);
		$result = $this->md_company->get($where);

		$data['action_url'] = site_url('company_setting/proc');
		
		if (count($result) > 0){
			$data['action_type'] = 'edit';
			$result = $result[0];
			$data['data'] = $result;
		}else{
			$data['action_type'] = 'create';
			$data['data'] = $this->md_company->getEmptyData();
			$data['data']['order'] = 0;
		}
		
		$data['head_name'] = '회사정보';
		$data['head_sub_name'] = '회사 관리';
		$this->load->view('company/company_write',$data);
	}
	
	public function proc(){
		$this->load->library('form_validation');
		
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
				
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('biz_name','상호명','required|max_length[20]');
			$this->form_validation->set_rules('classify','구분','required|max_length[20]');
			$this->form_validation->set_rules('phone','전화번호','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$cur = new DateTime();
			$cur = $cur->format('Y-m-d H:i:s');
			$data = $this->md_company->getSettingData('company', $biz_name, $ceo_name, $classify, $bizType, $bizCondition, 
					$addr, $phone, $fax, $note, $order, $cur, $bizNumber); //Y-m-d H:i:s
			$result = $this->md_company->create($data);
			alert('등록되었습니다.', site_url('company/index') );
			
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('biz_name','상호명','required|max_length[20]');
			$this->form_validation->set_rules('classify','구분','required|max_length[20]');
			$this->form_validation->set_rules('phone','전화번호','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$data = $this->md_company->getSettingData('', $biz_name, $ceo_name, $classify, $bizType, $bizCondition,
					$addr, $phone, $fax, $note, $order, '', $bizNumber);
			
			$this->md_company->modify(array('no'=>$company_no), $data);
			alert('수정되었습니다.', site_url('company') );
			
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('company_no','','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$set_no = is_array($company_no) ? implode(',',$company_no):$company_no;
			$where = array('no'=>$set_no);
			$this->md_company->delete($where);
				
			alert('삭제되었습니다.', site_url('company') );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
	
}
/* End of file board_setting.php */
/* Location: ./controllers/board_setting.php */