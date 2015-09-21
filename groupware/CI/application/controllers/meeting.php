<?
class Meeting extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();

		$this->load->model('meeting_model');
		
		//현재 페이지 
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment(3,1);
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array(
			'sData'     => !$this->input->get('sData')     ? '' : $this->input->get('sData')    ,
			'eData'     => !$this->input->get('eData')     ? '' : $this->input->get('eData')    ,
			'menu_no'   => !$this->input->get('menu_no')   ? '' : $this->input->get('menu_no')  ,
			'active'    => $this->input->get('active'),
			'title'     => !$this->input->get('title')     ? '' : $this->input->get('title')    , 
			'user_name' => !$this->input->get('user_name') ? '' : $this->input->get('user_name')
			
		);
		//링크용 파라미터 쿼리
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){

				set_cookie('left_menu_open_cookie',site_url('meeting/'),'0');

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
		// 검색 파라미터
		// 해당 일자가 포함된 진행기간 검색 sData,eData
		$eData  = $this->PAGE_CONFIG['params']['eData'];
		$eData  = !$eData ? '' : date("Y-m-d", strtotime($eData."+1 day"));
		
		$option['where'] = array(
			'meeting.created >=' => $this->PAGE_CONFIG['params']['sData'],
			'meeting.created <'  => $eData,
			'meeting.menu_no'    => $this->PAGE_CONFIG['params']['menu_no'],
			'meeting.is_active'  => $this->PAGE_CONFIG['params']['active']
		);
		$option['like'] = array(
			'user.name'     => $this->PAGE_CONFIG['params']['user_name'],
			'meeting.name' => $this->PAGE_CONFIG['params']['title']
		);

		$offset   = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		$get_data = $this->meeting_model->get_meeting_list($option,PAGING_PER_PAGE,$offset);

		//echo $this->db->last_query();

		$data['total']         = $get_data['total'];   // 전체글수
		$data['list']          = $get_data['list'];    // 글목록
		$data['anchor_url']    = site_url('meeting/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']); // 글 링크
		$data['write_url']     = site_url('meeting/write/'.$this->PAGE_CONFIG['params_string']); // 글 링크
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']    = site_url('meeting/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action
		
		
		$config['base_url']    = site_url('meeting/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('meeting/list_meeting_v',$data);
	}
	public function write(){
		$no     = $this->input->get('no');
		$option = array('meeting.no'=>$no);
		$result = $this->meeting_model->get_meeting_detail($option);
		//echo $this->db->last_query();

		$data['action_type'] = 'create';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']  = site_url('meeting/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action

		$data['data'] = array(
			'no'        => '',
			'user_name' => $this->session->userdata('name'),
			'menu_no'   => '',
			'user_no'   => '',
			'title'      => '',
			'contents'  => '',
			'file'      => '',
			'order'     => '0',
			'is_active' => '0',
			'created'   => date('Y-m-d')
		);
		if ($result->num_rows() > 0){
			$result = $result->row();

			$data['action_type'] = 'edit';
			$data['data'] = array(
				'no'        => $result->no,
				'user_name' => $result->user_name,
				'menu_no'   => $result->menu_no,
				'user_no'   => $result->user_no,
				'title'     => $result->name,
				'contents'  => $result->contents,
				'file'      => $result->file,
				'order'     => $result->order,
				'is_active' => $result->is_active,
				'created'   => substr($result->created,0,10)
			);
		}
		$data['list_url']  = site_url('meeting/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$this->load->view('meeting/write_meeting_v',$data);
	}
	public function proc(){
		$this->load->library('form_validation');

		$action_type = $this->input->post('action_type');
		$no          = $this->input->post('no');
		$menu_no     = $this->input->post('menu_no');
		$title       = $this->input->post('title');
		$contents    = $this->input->post('contents');
		$order       = $this->input->post('order');
		$is_active   = $this->input->post('is_active');
		$oldFile     = $this->input->post('oldFile');
		$parameters  = urldecode($this->input->post('parameters'));
		
		if( $action_type == 'create' ){
			// 파일 업로드 처리 추가
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('menu_no','분류','required');
			$this->form_validation->set_rules('title','제목','required|max_length[200]');
			$this->form_validation->set_rules('order','순서','required|numeric');
			$this->form_validation->set_rules('is_active','사용여부','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$file_name = '';
			if( $_FILES['userfile']['name'] ) {
				$config['upload_path']   = 'upload/meeting/';
				$config['allowed_types'] = FILE_ALL_TYPE;
				$config['encrypt_name']  = false;
			
				$this->load->library('upload', $config);
			
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_name = $upload_data['file_name'];
				}
			}

			$option = array(
				'menu_no'   => $menu_no,
				'user_no'   => $this->session->userdata('no'),
				'name'      => $title,
				'contents'  => $contents,
				'order'     => $order,
				'is_active' => $is_active,
				'file'      => $file_name
			);
			$result = $this->meeting_model->get_meeting_insert($option);
			alert('등록되었습니다.', site_url('meeting/lists/') ); //신규 등록 첫페이지로

		}elseif( $action_type == 'edit' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('no','코드','required');
			$this->form_validation->set_rules('menu_no','분류','required');
			$this->form_validation->set_rules('title','제목','required|max_length[200]');
			$this->form_validation->set_rules('order','순서','required|numeric');
			$this->form_validation->set_rules('is_active','사용여부','required');

			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$file_name = $oldFile;
			if( $_FILES['userfile']['name'] ) {
				$config['upload_path']   = 'upload/meeting/';
				$config['allowed_types'] = FILE_ALL_TYPE;
				$config['encrypt_name']  = false;
			
				$this->load->library('upload', $config);
			
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_name = $upload_data['file_name'];
						
					if( $oldFile ){
						unlink($config['upload_path'].$oldFile);
					}
				}
			}
			
			$option = array(
				'menu_no'   => $menu_no,
				'name'      => $title,
				'contents'  => $contents,
				'order'     => $order,
				'is_active' => $is_active,
				'file'      => $file_name
			);
			$this->meeting_model->get_meeting_update($option,array('no'=>$no));

			alert('수정되었습니다.', site_url('meeting/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no','no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$set_no = is_array($no) ? implode(',',$no):$no;
			
			/* 데이터 삭제 */
			$this->meeting_model->get_meeting_delete($set_no);
			
			alert('삭제되었습니다.', site_url('meeting/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file meeting.php */
/* Location: ./controllers/meeting.php */