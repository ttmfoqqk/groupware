<?
class Document extends CI_Controller{
	private $TABLE_NAME = 'sw_document';
	private $PAGE_NAME = '회사서식 정보';
	private $CATEGORY = 'document';
	
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('document'),'0');
		$this->load->model('md_company');
		$this->load->model('md_document');
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
	
	public function getListFilter(){
		$likes['d.name'] = !$this->input->get('ft_title') ? '' : $this->input->get('ft_title');
		$likes['u.name'] = !$this->input->get('ft_userName') ? '' : $this->input->get('ft_userName');
		return $likes;
	}
	
	public function lists(){
		//필터 설정
		$likes = $this->getListFilter();
		
		$menu = !$this->input->get('ft_document') ? NULL : $this->input->get('ft_document');
		$is_active = !$this->input->get('ft_active') ? NULL : $this->input->get('ft_active');
		
		$start = !$this->input->get('ft_start') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_start')));
		$end = !$this->input->get('ft_end') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_end')."+1 day"));
		
		//Pagination, 테이블정보 필요 설정 세팅
		$tb_show_num = !$this->input->get('tb_num') ? PAGING_PER_PAGE : $this->input->get('tb_num');
		
		if(($start && $end) || $menu || $is_active){
			$where = array();
			if($start && $end){
				$where['d.created <'] = $end;
				$where['d.created >='] = $start;
			}
			if($menu)
				$where['d.menu_no'] = $menu;
			if($is_active)
				$where['d.is_active'] = $is_active;
		}
		else
			$where = NULL;
		
		$total = $this->md_document->getCount($where, $likes);
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
		$result = $this->md_document->get($where, $likes, $tb_show_num, $offset);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			$data['list'] = $result;
		}
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		
		
		
		//페이지 타이틀 설정
		$data['action_url'] = site_url('document/proc');
		$data['action_type'] = 'delete';
		$data['head_name'] = "회사 서식정보";
		$data['page'] = $this->CATEGORY;
		$this->load->view('document/document_v',$data);
	}
	
	public function write(){
		$get_no = $this->uri->segment(3);
		$where = array(
				'no'=>$get_no
		);
		$result = $this->md_company->get($where);
		
		$data['action_url'] = site_url('document/proc');
		
		if (count($result) > 0){
			$data['action_type'] = 'edit';
			$result = $result[0];
			$data['data'] = $result;
		}else{
			$data['action_type'] = 'create';
			$data['data'] = $this->md_company->getEmptyData();
			$data['data']['order'] = 0;
		}
		$data['head_name'] = '회사서식 관리';
		
		$this->load->view('company/document_write',$data);
	}
	
	public function proc(){
		$this->load->library('form_validation');
		
		$no = $this->input->post('no');
		$action_type = $this->input->post ( 'action_type' );
		$menu_no = $this->input->post('document');
		$name = $this->input->post('name');
		$contents = $this->input->post('contents');
		$order = $this->input->post('order');
		$is_active = $this->input->post('is_active');
		
		$config['upload_path'] = 'upload/document/';
		$config['remove_spaces'] = true;
		$config['encrypt_name'] = true;
		$config['allowed_types'] = FILE_ALL_TYPE;
		if( $action_type == 'create' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('document','분류','required');
			$this->form_validation->set_rules('name','서식명','required');
			$this->form_validation->set_rules('contents','서식','required');
				
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			
			$file = $origin_file = NULL;
			if( $_FILES['userfile']['name'] ) {
					
				$this->load->library('upload', $config);
					
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file = $upload_data['file_name'];
					$origin_file = $_FILES['userfile']['name'];
				}
			}
				
			$cur = new DateTime();
			$cur = $cur->format('Y-m-d H:i:s');
			$data = array(
					'menu_no'=>$menu_no,
					'user_no'=>$this->session->userdata('no'),
					'name'=>$name,
					'contents'=>$contents,
					'file'=>$file,
					'order'=>$order,
					'is_active'=>$is_active,
					'created'=>$cur,
					'origin_file' => $origin_file
			);
				
			$result = $this->md_company->create($data);
			alert('등록되었습니다.', site_url('document' . '/index') );
			
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('document','분류','required');
			$this->form_validation->set_rules('name','서식명','required');
			$this->form_validation->set_rules('contents','서식','required');
			
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			
			$file = $origin_file = NULL;
			if( $_FILES['userfile']['name'] ) {
				$this->load->library('upload', $config);
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					//이전파일 삭제하고 업로드
					$exUserFile = $this->md_company->get(array('no'=>$no), 'file');
					if(count($exUserFile) > 0 && $exUserFile[0]['file'] != NULL)
						unlink(realpath($config['upload_path']) . '/' . $exUserFile[0]['file']);
					$upload_data = $this->upload->data();
					$file = $upload_data['file_name'];
					$origin_file = $_FILES['userfile']['name'];
				}
			}
			
			$data = array(
					'menu_no'=>$menu_no,
					'name'=>$name,
					'contents'=>$contents,
					'file'=>$file,
					'order'=>$order,
					'is_active'=>$is_active,
					'origin_file' => $origin_file
			);
			
			$this->md_company->modify(array('no'=>$no), $data);
			alert('수정되었습니다.', site_url('document' . '/index') );
			
			
		}elseif( $action_type == 'delete'){
			$this->form_validation->set_rules('no', 'no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
				
			$set_no = is_array($no) ? implode(',',$no):$no;
			$where = 'no in (' . $set_no . ')';
				
			//프로필 삭제
			$exUserFile = $this->md_company->get($where, 'file');
			if(count($exUserFile) > 0){
				for ($i=0 ; $i < count($exUserFile); $i++){
					if($exUserFile[$i]['file'] != '')
						unlink(realpath($config['upload_path']) . '/' . $exUserFile[$i]['file']);
				}
			}
			$set_no = is_array($no) ? implode(',',$no):$no;
			$where = 'no in (' . $set_no . ')';
			$this->md_company->delete($where);
			alert('삭제되었습니다.', site_url('document' . '/index') );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file document.php */
/* Location: ./controllers/document.php */