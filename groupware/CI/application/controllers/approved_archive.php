<?
class Approved_archive extends CI_Controller{
	private $PAGE_CONFIG;

	public function __construct() {
		parent::__construct();

		$this->load->model('approved_model');
		
		//현재 페이지 
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment(3,1);
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array(
			'sData'        => !$this->input->get('sData')        ? '' : $this->input->get('sData')        ,
			'eData'        => !$this->input->get('eData')        ? '' : $this->input->get('eData')        ,
			'swData'       => !$this->input->get('swData')       ? '' : $this->input->get('swData')       ,
			'ewData'       => !$this->input->get('ewData')       ? '' : $this->input->get('ewData')       ,
			'part_sender'  => !$this->input->get('part_sender')  ? '' : $this->input->get('part_sender')  ,
			'part_receiver'=> !$this->input->get('part_receiver')? '' : $this->input->get('part_receiver'),
			'menu_no'      => !$this->input->get('menu_no')      ? '' : $this->input->get('menu_no')      ,
			'name_sender'  => !$this->input->get('name_sender')  ? '' : $this->input->get('name_sender')  ,
			'name_receiver'=> !$this->input->get('name_receiver')? '' : $this->input->get('name_receiver'),
			'doc_no'       => !$this->input->get('doc_no')       ? '' : $this->input->get('doc_no')       ,
			'title'        => !$this->input->get('title')        ? '' : $this->input->get('title')
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
				set_cookie('left_menu_open_cookie',site_url('approved_archive/lists/'),'0');
				
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
			'status.part_receiver'=> $this->PAGE_CONFIG['params']['part_receiver']
		);
		$option['like'] = array(
			'user_sender.name'    => $this->PAGE_CONFIG['params']['name_sender'],
			'user_receiver.name'  => $this->PAGE_CONFIG['params']['name_receiver'],
		);
		$option['or_like'] = array(
			'project.title'       => $this->PAGE_CONFIG['params']['title'],
			'document.name'       => $this->PAGE_CONFIG['params']['title']
		);
		$temp_title = addslashes($this->PAGE_CONFIG['params']['title']);
		$option['cus_where'] = "(project.title like '%".$temp_title."%' OR document.name like '%".$temp_title."%' )";

		$offset   = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		$get_data = $this->approved_model->get_approved_list($option,PAGING_PER_PAGE,$offset);

		$data['total']         = $get_data['total'];   // 전체글수
		$data['list']          = $get_data['list'];    // 글목록
		$data['anchor_url']    = site_url('approved_archive/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']); // 글 링크
		$data['write_url']     = site_url('approved_archive/write/'.$this->PAGE_CONFIG['params_string']); // 글 링크
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']    = site_url('approved_archive/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action

		$config['base_url']    = site_url('approved_archive/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('approved/list_archive_v',$data);
	}
	public function write(){
		$no     = $this->input->get('no');
		$option = array('no'=>$no);
		$result = $this->approved_model->get_approved_detail($option);

		$data['action_type'] = 'create';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']  = site_url('approved_archive/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action

		$data['data'] = array(
			'no'           => '',
			'menu_part_no' => '',
			'menu_no'      => '',
			'user_no'      => '',
			'title'        => '',
			'contents'     => '',
			'sData'        => '',
			'eData'        => '',
			'pPoint'       => '0',
			'mPoint'       => '0',
			'file'         => '',
			'order'        => '0',
			'created'      => ''
		);
		if ($result->num_rows() > 0){
			$result = $result->row();

			$data['action_type'] = 'edit';
			$data['data'] = array(
				'no'           => $result->no,
				'menu_part_no' => $result->menu_part_no,
				'menu_no'      => $result->menu_no,
				'user_no'      => $result->user_no,
				'title'        => $result->title,
				'contents'     => $result->contents,
				'sData'        => substr($result->sData,0,10),
				'eData'        => substr($result->eData,0,10),
				'pPoint'       => $result->pPoint,
				'mPoint'       => $result->mPoint,
				'file'         => $result->file,
				'order'        => $result->order,
				'created'      => $result->created,
			);
		}
		$data['list_url']  = site_url('approved_archive/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$this->load->view('approved/write_archive_v',$data);
	}
	public function proc(){
		$this->load->library('form_validation');


		$action_type  = $this->input->post('action_type');
		$no           = $this->input->post('no');
		$menu_part_no = $this->input->post('menu_part_no');
		$menu_no      = $this->input->post('menu_no');
		$title        = $this->input->post('title');
		$contents     = $this->input->post('contents');
		$sData        = $this->input->post('sData');
		$eData        = $this->input->post('eData');
		$pPoint       = $this->input->post('pPoint');
		$mPoint       = $this->input->post('mPoint');
		$order        = $this->input->post('order');
		$parameters   = urldecode($this->input->post('parameters'));
		
		if( $action_type == 'create' ){
			// 파일 업로드 처리 추가
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('menu_part_no','부서','required');
			$this->form_validation->set_rules('menu_no','분류','required');
			$this->form_validation->set_rules('title','제목','required|max_length[200]');
			$this->form_validation->set_rules('sData','진행기간','required');
			$this->form_validation->set_rules('eData','진행기간','required');
			$this->form_validation->set_rules('pPoint','결재점수','required|numeric');
			$this->form_validation->set_rules('mPoint','누락점수','required|numeric');
			$this->form_validation->set_rules('order','순서','required|numeric');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			$option = array(
				'menu_part_no' =>$menu_part_no,
				'menu_no'      =>$menu_no,
				'user_no'      =>$this->session->userdata('no'),
				'title'        =>$title,
				'contents'     =>$contents,
				'sData'        =>$sData,
				'eData'        =>$eData,
				'pPoint'       =>$pPoint,
				'mPoint'       =>$mPoint,
				'order'        =>$order
			);
			$result = $this->approved_model->get_approved_insert($option);
			alert('등록되었습니다.', site_url('approved/lists/') ); //신규 등록 첫페이지로

		}elseif( $action_type == 'edit' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('no','코드','required');
			$this->form_validation->set_rules('menu_part_no','부서','required');
			$this->form_validation->set_rules('menu_no','분류','required');
			$this->form_validation->set_rules('title','제목','required|max_length[200]');
			$this->form_validation->set_rules('sData','진행기간','required');
			$this->form_validation->set_rules('eData','진행기간','required');
			$this->form_validation->set_rules('pPoint','결재점수','required|numeric');
			$this->form_validation->set_rules('mPoint','누락점수','required|numeric');
			$this->form_validation->set_rules('order','게시판 순서','required|numeric');

			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$option = array(
				'menu_part_no' =>$menu_part_no,
				'menu_no'      =>$menu_no,
				'title'        =>$title,
				'contents'     =>$contents,
				'sData'        =>$sData,
				'eData'        =>$eData,
				'pPoint'       =>$pPoint,
				'mPoint'       =>$mPoint,
				'order'        =>$order
			);
			$this->approved_model->get_approved_update($option,array('no'=>$no));

			alert('수정되었습니다.', site_url('approved_archive/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no','no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$set_no = is_array($no) ? implode(',',$no):$no;
			
			/* 데이터 삭제 */
			$this->approved_model->get_approved_delete($set_no);
			
			alert('삭제되었습니다.', site_url('approved_archive/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file approved_archive.php */
/* Location: ./controllers/approved_archive.php */