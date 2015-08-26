<?
class Approved_receive extends CI_Controller{
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
			'sData'        => !$this->input->get('sData')        ? '' : $this->input->get('sData')        ,
			'eData'        => !$this->input->get('eData')        ? '' : $this->input->get('eData')        ,
			'swData'       => !$this->input->get('swData')       ? '' : $this->input->get('swData')       ,
			'ewData'       => !$this->input->get('ewData')       ? '' : $this->input->get('ewData')       ,
			'part_sender'  => !$this->input->get('part_sender')  ? '' : $this->input->get('part_sender')  ,
			'menu_no'      => !$this->input->get('menu_no')      ? '' : $this->input->get('menu_no')      ,
			'name_sender'  => !$this->input->get('name_sender')  ? '' : $this->input->get('name_sender')  ,
			'doc_no'       => !$this->input->get('doc_no')       ? '' : $this->input->get('doc_no')       ,
			'title'        => !$this->input->get('title')        ? '' : $this->input->get('title')
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
				set_cookie('left_menu_open_cookie',site_url('approved_receive/lists/'.$this->PAGE_CONFIG['set_page']),'0');
				
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
			'approved.menu_no'    => $this->PAGE_CONFIG['params']['menu_no'],
			'approved.no'         => $this->PAGE_CONFIG['params']['doc_no'],
			'status.part_sender'  => $this->PAGE_CONFIG['params']['part_sender'],
			'status.receiver'     => $this->session->userdata('no'),
			'status.status'       => $this->PAGE_CONFIG['status']
		);
		
		$option['cus_where'] = "status.approved_no is not null ";

		$option['like'] = array(
			'user_sender.name'    => $this->PAGE_CONFIG['params']['name_sender'],
			'approved.title'      => $this->PAGE_CONFIG['params']['title'],
		);

		$offset   = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		$get_data = $this->approved_model->get_approved_list($option,PAGING_PER_PAGE,$offset);

		$data['total']         = $get_data['total'];   // 전체글수
		$data['list']          = $get_data['list'];    // 글목록
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['anchor_url']    = site_url('approved_receive/write/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('approved_receive/proc/' .$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page']);

		$config['base_url']    = site_url('approved_receive/lists/'.$this->PAGE_CONFIG['set_page']);
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['uri_segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('approved/list_receive_v',$data);
	}

	public function write(){
		$data['action_type'] = 'create';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']  = site_url('approved_send/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action
		$data['list_url']    = site_url('approved_send/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		
		$no = $this->input->get('no');
		$option = array(
			'approved.no'     => $no,
			'status.receiver' => $this->session->userdata('no'),
			'status.status'   => $this->PAGE_CONFIG['status']
		);
		$result = $this->approved_model->get_approved_detail($option);

		if ($result->num_rows() <= 0){
			alert('잘못된 접근입니다.');
		}
		
		$result = $result->row();


		$data['data'] = array(
			'no'         => $result->no,
			'kind'       => $result->kind,
			'project_no' => $result->project_no,
			'user_no'    => $result->user_no,
			'menu_no'    => $result->menu_no,
			'title'      => $result->title,
			'sData'      => substr($result->sData,0,10),
			'eData'      => substr($result->eData,0,10),
			'file'       => $result->file,
			'order'      => $result->order,
			'created'    => substr($result->created,0,10),
			'department'        => $result->sender_department,
			'sender'            => $result->sender_name,
			'project_title'     => $result->project_title,
			'project_sData'     => substr($result->project_sData,0,10),
			'project_eData'     => substr($result->project_eData,0,10),
			'pPoint'            => $result->pPoint,
			'mPoint'            => $result->mPoint,
			'order'             => $result->order,
			'project_menu_name' => $result->project_menu_name,
			'project_contents'  => nl2br($result->project_contents),
			'project_file'      => $result->project_file,
			'approved_contents' => $result->approved_contents,
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

		$this->load->view('approved/view_project_v',$data);
	}
}
/* End of file approved_receive.php */
/* Location: ./controllers/approved_receive.php */