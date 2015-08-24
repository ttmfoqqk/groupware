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
	
	public function getListFilter(){
		$likes['m.no'] = !$this->input->get('ft_kind') ? '' : $this->input->get('ft_kind');
		$likes['o.name'] = !$this->input->get('ft_itemName') ? '' : $this->input->get('ft_itemName');
		$likes['o.area'] = !$this->input->get('ft_area') ? '' : $this->input->get('ft_area');
		$likes['u.name'] = !$this->input->get('ft_usrName') ? '' : $this->input->get('ft_usrName');
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
			$where = array('o.created >='=>$date['start'], 'o.created <'=>$end);
		}
		else
			$where = null;
		
		$total = $this->md_object->getObjectCount($where, $likes);
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
		$result = $this->md_object->getObject($where, $likes, $tb_show_num, $offset);

		if (count($result) > 0){
			$data['list'] = $result;
		}
		
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		$data['tb_num'] =  $tb_show_num;		//테이블 row 갯수
		
		//페이지 타이틀 설정
		$data['action_url'] = site_url('object/proc');
		$data['action_type'] = 'delete';
		$data['head_name'] = "물품정보";
		$data['page'] = $this->CATEGORY;
		
		//뷰 로딩
		$this->load->view('company/object_v',$data);
	}
	
	public function write(){
		$this->load->model('md_company');
		$this->md_company->setTable('sw_object');
		$get_no = $page_method = $this->uri->segment(3);
		$where = array(
				'no'=>$get_no
		);
		$result = $this->md_company->get($where);
		
		$data['action_url'] = site_url('object/proc');
		
		if (count($result) > 0){
			$data['action_type'] = 'edit';
			$result = $result[0];
			$data['data'] = $result;
		}else{
			$data['action_type'] = 'create';
			$data['data'] = $this->md_company->getEmptyData();
			$data['data']['order'] = 0;
		}
		$data['head_name'] = '물품관리';
		$this->load->view('company/object_write',$data);
	}
	
	public function proc(){
		$this->load->library('form_validation');
		$this->load->model('md_company');
		$this->md_company->setTable($this->TABLE_NAME);
		
		$no = $this->input->post('no');
		$action_type = $this->input->post ( 'action_type' );
		$menuNo = $this->input->post('menu_no');
		$name = $this->input->post('name');
		$area = $this->input->post('area');
		$userNo = $this->input->post('user_no');
		$bigo = $this->input->post('bigo');
		$order = $this->input->post('order');
		$isActive = $this->input->post('is_active');
		
		$config['upload_path'] = 'upload/object/';
		$config['remove_spaces'] = true;
		$config['encrypt_name'] = true;
		$config['allowed_types'] = FILE_ALL_TYPE;
		if( $action_type == 'create' ){
			//$category = $this->uri->segment(2);
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('menu_no','분류','required');
			$this->form_validation->set_rules('name','물품명','required');
			$this->form_validation->set_rules('area','물품위치','required');
			$this->form_validation->set_rules('user_no','관리자','required');
			
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
					'menu_no' => $menuNo,
					'user_no' => $userNo,
					'name' => $name,
					'area' => $area,
					'bigo' => $bigo,
					'file' => $file,
					'order' => $order,
					'file' => $file,
					'is_active' => $isActive,
					'created' => $cur, 
					'origin_file' => $origin_file
			);
				
			$result = $this->md_company->create($data);
			alert('등록되었습니다.', site_url('object') );
			
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('menu_no','분류','required');
			$this->form_validation->set_rules('name','물품명','required');
			$this->form_validation->set_rules('area','물품위치','required');
			$this->form_validation->set_rules('user_no','관리자','required');
				
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
				
			$file = NULL;
				
			if( $_FILES['userfile']['name'] ) {
				$this->load->library('upload', $config);
					
				if ( !$this->upload->do_upload() ){
					$file=NULL;
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
					$origin_file = null;
				}else{
					//이전파일 삭제하고 업로드
					$exUserFile = $this->md_company->get(array('no'=>$no), 'file');
					if(count($exUserFile) > 0 && $exUserFile[0]['file'] != NULL )
						unlink(realpath($config['upload_path']) . '/' . $exUserFile[0]['file']);
					$upload_data = $this->upload->data();
					$file = $upload_data['file_name'];
					$origin_file = $_FILES['userfile']['name'];
				}
			}
			
			$data = array(
					'menu_no' => $menuNo,
					'user_no' => $userNo,
					'name' => $name,
					'area' => $area,
					'bigo' => $bigo,
					'file' => $file,
					'order' => $order,
					'file' => $file,
					'is_active' => $isActive,
					'origin_file' => $origin_file
					
			);
			
			$this->md_company->modify(array('no'=>$no), $data);
			alert('수정되었습니다.', site_url('object' . '/index') );
			
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no', 'no','required');
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
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
			$this->md_company->deleteIn('no', $no);
			alert('삭제되었습니다.', site_url('object' . '/index') );
		}else{
			echo $action_type;
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file object.php */
/* Location: ./controllers/object.php */