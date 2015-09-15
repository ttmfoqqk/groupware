<?
class Approved_send extends CI_Controller{
	private $PAGE_CONFIG;

	public function __construct() {
		parent::__construct();
	  
		$this->load->model('approved_model');

		// page segment 위치
		$this->PAGE_CONFIG['uri_segment'] = 4;
		//현재 페이지 검색모드 all default
		$this->PAGE_CONFIG['set_page']    = $this->uri->segment(3,'all');
		//현재 페이지 
		$this->PAGE_CONFIG['cur_page']    = $this->uri->segment($this->PAGE_CONFIG['uri_segment'],1);
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array(
			'sData'         => !$this->input->get('sData')         ? '' : $this->input->get('sData')         ,
			'eData'         => !$this->input->get('eData')         ? '' : $this->input->get('eData')         ,
			'swData'        => !$this->input->get('swData')        ? '' : $this->input->get('swData')        ,
			'ewData'        => !$this->input->get('ewData')        ? '' : $this->input->get('ewData')        ,
			'part_receiver' => !$this->input->get('part_receiver') ? '' : $this->input->get('part_receiver') ,
			'menu_no'       => !$this->input->get('menu_no')       ? '' : $this->input->get('menu_no')       ,
			'name_receiver' => !$this->input->get('name_receiver') ? '' : $this->input->get('name_receiver') ,
			'doc_no'        => !$this->input->get('doc_no')        ? '' : $this->input->get('doc_no')        ,
			'title'         => !$this->input->get('title')         ? '' : $this->input->get('title')
		);

		$this->PAGE_CONFIG['status'] = '';
		switch( $this->PAGE_CONFIG['set_page'] ) {
			case "all" :
				$this->PAGE_CONFIG['status'] = '';
				break;
			case "ao" :
				$this->PAGE_CONFIG['status'] = 'a';
				// or created ? 결재를 미룬것 추가하기
				break;
			default :
				$this->PAGE_CONFIG['status'] = $this->PAGE_CONFIG['set_page'];
		}

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
				set_cookie('left_menu_open_cookie',site_url('approved_send/lists/'.$this->PAGE_CONFIG['set_page']),'0');
				
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
		//$eData  = !$eData ? '' : date("Y-m-d", strtotime($eData."+1 day"));
		$ewData = $this->PAGE_CONFIG['params']['ewData'];
		$ewData = !$ewData ? '' : date("Y-m-d", strtotime($ewData."+1 day"));
		
		
		$option['where'] = array(
			'approved.sData <='   => $this->PAGE_CONFIG['params']['sData'],
			'approved.eData >='   => $eData,
			'approved.created >=' => $this->PAGE_CONFIG['params']['swData'],
			'approved.created <'  => $ewData,
			'IF(approved.kind = 0, project.menu_no , document.menu_no) = ' => $this->PAGE_CONFIG['params']['menu_no'],
			'approved.no'         => $this->PAGE_CONFIG['params']['doc_no'],
			'status.part_receiver'=> $this->PAGE_CONFIG['params']['part_receiver'],
			'status.sender'       => $this->session->userdata('no'),
			'status.status'       => $this->PAGE_CONFIG['status'],
			'status.created >'    => $this->PAGE_CONFIG['set_page']=='a'  ? date('Y-m-d') : '',
			'status.created <'    => $this->PAGE_CONFIG['set_page']=='ao' ? date('Y-m-d') : ''
			
		);
		

		$option['like'] = array(
			'rrr.receiver_name' => $this->PAGE_CONFIG['params']['name_receiver'],
			'approved.title'    => $this->PAGE_CONFIG['params']['title'],
		);

		$offset   = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		$get_data = $this->approved_model->approved_send_list($option,PAGING_PER_PAGE,$offset);

		echo $this->db->last_query();

		$data['total']         = $get_data['total'];   // 전체글수
		$data['list']          = $get_data['list'];    // 글목록
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['anchor_url']    = site_url('approved_send/write/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('approved_send/proc/' .$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page']);

		$config['base_url']    = site_url('approved_send/lists/'.$this->PAGE_CONFIG['set_page']);
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['uri_segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('approved/list_send_v',$data);
	}

	public function write(){
		$data['action_type'] = 'edit';
		$data['app_type']    = 'send';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']  = site_url('approved_send/proc/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page']); // 폼 action
		$data['list_url']    = site_url('approved_send/lists/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		
		$no = $this->input->get('no');
		$option = array(
			'approved.no'   => $no,
			'status.sender' => $this->session->userdata('no'),
			'status.status' => $this->PAGE_CONFIG['status']
		);
		$result = $this->approved_model->approved_send_detail($option);

		if ($result->num_rows() <= 0){
			alert('잘못된 접근입니다.');
		}
		
		$result = $result->row();


		$data['data'] = array(
			'no'          => $result->no,
			'kind'        => $result->kind,
			'user_no'     => $result->user_no,
			'menu_no'     => $result->menu_no,
			'menu_name'   => $result->menu_name,
			'title'       => $result->title,
			'document_name' => $result->document_name,
			'sData'       => substr($result->sData,0,10),
			'eData'       => substr($result->eData,0,10),
			'file'        => $result->file,
			'order'       => $result->order,
			'created'     => substr($result->created,0,10),
			'sender_name' => $result->sender_name,
			'sender_menu' => $result->sender_part,
			'pPoint'      => $result->pPoint,
			'mPoint'      => $result->mPoint,
			'order'       => $result->order,
			'p_contents'  => nl2br($result->p_contents),
			'contents'    => $result->sender_contents,
			'status'      => $result->status
		);

		/* 결재자들 */
		$option = array(
			'approved_no' => $result->no
		);
		$data['approved_list'] = $this->approved_model->get_approved_staff_list($option);
		
		/* 내용들 */
		$option = array(
			'approved_no' => $result->no
		);
		$data['contents_list'] = $this->approved_model->get_approved_contents_list($option);
	
		if( $result->kind == '0' ){
			$this->load->view('approved/view_project_v',$data);
		}else{
			$this->load->view('approved/view_document_v',$data);
		}

		
	}

	public function proc(){
		$this->load->library('form_validation');
		$action_type = $this->input->post('action_type');
		$no          = $this->input->post('no');
		$contents    = $this->input->post('contents');
		$parameters  = urldecode($this->input->post('parameters'));
		
		if( $action_type == 'send' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('no','no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$option = array(
				'status' => 'b'
			);
			$where = array(
				'approved_no' =>$no,
				'sender'      =>$this->session->userdata('no')
			);

			$this->approved_model->set_approved_staff_update($option,$where);

			alert('결재 요청되었습니다.', site_url('approved_send/lists/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file approved_send.php */
/* Location: ./controllers/approved_send.php */