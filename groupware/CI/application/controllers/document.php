<?
class Document extends CI_Controller{
	private $PAGE_CONFIG;	
	public function __construct() {
		parent::__construct();
		$this->load->model('md_document');
		
		$this->PAGE_CONFIG['segment']  = 3;
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment( $this->PAGE_CONFIG['segment'] ,1);
		$this->PAGE_CONFIG['params'] = array(
			'sData'     => !$this->input->get('sData')     ? '' : $this->input->get('sData')     ,
			'eData'     => !$this->input->get('eData')     ? '' : $this->input->get('eData')     ,
			'menu_no'   => !$this->input->get('menu_no')   ? '' : $this->input->get('menu_no')   ,
			'name'      => !$this->input->get('name')      ? '' : $this->input->get('name')      ,
			'userName'  => !$this->input->get('userName')  ? '' : $this->input->get('userName')  ,
			'is_active' => !$this->input->get('is_active') ? '' : $this->input->get('is_active')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();

		if( $method == 'write' or $method == 'proc' ){
			permission_check('document','W');
		}else{
			permission_check('document','R');
		}
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				if($method == 'excel'){
					$this->$method();
				}else{
					set_cookie('left_menu_open_cookie',site_url('document'),'0');
					$this->load->view('inc/header_v');
					$this->load->view('inc/side_v');
					$this->$method();
					$this->load->view('inc/footer_v');
				}
			}else{
				show_error('에러');
			}
		}
	}
	
	private function getListOption(){
		$option['where'] = array(
			'date_format(document.created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['sData'],
			'date_format(document.created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['eData'],
			'document.is_active' => $this->PAGE_CONFIG['params']['is_active']
		);
		$option['like'] = array(
			'document.name' => $this->PAGE_CONFIG['params']['name'],
			'user.name'     => $this->PAGE_CONFIG['params']['userName']
		);
	
		$array_menu = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
		$option['where_in'] = array(
			'document.menu_no' => $array_menu
		);
		return $option;
	}
	
	public function index(){
		$this->lists();
	}

	
	public function lists(){
		$option = $this->getListOption();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->md_document->get_document_list($option,null,null,'count');
		$data['list']          = $this->md_document->get_document_list($option,PAGING_PER_PAGE,$offset);
		
		$data['anchor_url']    = site_url('document/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('document/write/'.$this->PAGE_CONFIG['params_string']);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('document/proc/' .$this->PAGE_CONFIG['cur_page']);
		$data['excel_url']     = site_url('document/excel/'.$this->PAGE_CONFIG['params_string']);		
		
		$config['base_url']    = site_url('document/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$this->load->view('document/document_v',$data);
	}
	
	public function excel(){
		$this->load->library('Excel');
		$excel = new Excel();
		$option = $this->getListOption();
	
		$data['total'] = $this->md_document->get_document_list($option,null,null,'count');
		$data['list']  = $this->md_document->get_document_list($option,$data['total'],0);
		
		$title = '회사서식';
		$labels = array(
			'A' => '분류',
			'B' => '서식명',
			'C' => '사용여부',
			'D' => '등록일자',
			'E' => '등록자'
		);
		
		$values=array();
		
		foreach ( $data['list'] as $lt ) {
			$menu = search_node($lt['menu_no'],'parent');
			$item = array(
				'A' => $menu['name'],
				'B' => $lt['name'],
				'C' => $lt['active'],
				'D' => $lt['created'],
				'E' => $lt['user_name']
			);
			array_push($values, $item);
		}
		
		$excel->printExcel($title,$labels,$values);
	}
	
	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'no'=>$no
		);
		$setVla = array(
			'order'  => '0'
		);
		$data['data'] = $this->md_document->get_document_detail($option,$setVla);
		
		if( !$data['data']['no'] ){
			$data['action_type'] = 'create';
		}else{
			$data['action_type'] = 'edit';
		}

		$data['parameters'] = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url'] = site_url('document/proc/' .$this->PAGE_CONFIG['cur_page']);
		$data['list_url']   = site_url('document/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		
		$this->load->view('document/document_write',$data);
	}
	
	public function proc(){
		$this->load->library('form_validation');
		
		$no          = $this->input->post('no');
		$action_type = $this->input->post('action_type');
		$menu_no     = $this->input->post('menu_no');
		$name        = $this->input->post('name');
		$contents    = $this->input->post('contents');
		$order       = $this->input->post('order');
		$is_active   = $this->input->post('is_active');
		$parameters  = urldecode($this->input->post('parameters'));
		
		$config['upload_path'] = 'upload/document/';
		$config['remove_spaces'] = true;
		$config['encrypt_name'] = true;
		$config['allowed_types'] = FILE_ALL_TYPE;
		
		if( $action_type == 'create' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('menu_no','분류','required');
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
			$data = array(
				'menu_no'     => $menu_no,
				'user_no'     => $this->session->userdata('no'),
				'name'        => $name,
				'contents'    => $contents,
				'file'        => $file,
				'order'       => $order,
				'is_active'   => $is_active,
				'origin_file' => $origin_file
			);
				
			$result = $this->md_document->set_document_insert($data);
			alert('등록되었습니다.', site_url('document') );
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('menu_no','분류','required');
			$this->form_validation->set_rules('name','서식명','required');
			$this->form_validation->set_rules('contents','서식','required');
			
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			$option['where'] = array(
					'no'=>$no
			);
			$getData = $this->md_document->get_document_detail($option);
			
			$file = $origin_file = NULL;
			if( $_FILES['userfile']['name'] ) {
				$this->load->library('upload', $config);
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					if($getData['file']){
						if( is_file(realpath($config['upload_path']) . '/' . $getData['file']) ){
							unlink(realpath($config['upload_path']) . '/' . $getData['file']);
						}
					}
					$upload_data = $this->upload->data();
					$file = $upload_data['file_name'];
					$origin_file = $_FILES['userfile']['name'];
				}
			}
			
			$values = array(
				'menu_no'   => $menu_no,
				'name'      => $name,
				'contents'  => $contents,
				'order'     => $order,
				'is_active' => $is_active
			);
			if($file != null){
				$values['file'] = $file;
				$values['origin_file'] = $origin_file;
			}
			$this->md_document->set_document_update($values, $option);
			alert('수정되었습니다.', site_url('document/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no ) );
		}elseif( $action_type == 'delete'){
			$this->form_validation->set_rules('no', 'no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$option['where_in'] = array(
					'no' => $no
			);
				
			$list = $this->md_document->get_document_list($option,count($no),0);
			
			foreach( $list as $lt ){
				if($lt['file'] != ''){
					if(is_file(realpath($config['upload_path']) . '/' . $lt['file'])){
						unlink(realpath($config['upload_path']) . '/' . $lt['file']);
					}
				}
			}
			
			$this->md_document->set_document_delete($option);
			alert('삭제되었습니다.', site_url('document') );
		}else{
			alert('잘못된 접근입니다.');
		}
	}

	public function _lists(){
		$option = $this->getListOption();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->md_document->get_document_list($option,null,null,'count');
		$data['list']          = $this->md_document->get_document_list($option,PAGING_PER_PAGE,$offset);
		
		$config['base_url']    = site_url('document/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		echo json_encode($data);
	}
}
/* End of file document.php */
/* Location: ./controllers/document.php */