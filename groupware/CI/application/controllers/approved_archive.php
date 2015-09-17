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
			'approved.no'         => $this->PAGE_CONFIG['params']['doc_no'],
			'approved.menu_no'    => $this->PAGE_CONFIG['params']['part_sender'],
			'IF(approved.kind = 0, project_staff.menu_no , document_staff.menu_no ) = ' => $this->PAGE_CONFIG['params']['part_receiver'],
			'IF(approved.kind = 0, project.menu_no , document.menu_no) = ' => $this->PAGE_CONFIG['params']['menu_no'],

			'approved.user_no'    => $this->session->userdata('no') // 나의 결재 정보
		);
		$option['like'] = array(
			'user.name'      => $this->PAGE_CONFIG['params']['name_sender'],
			'approved.title' => $this->PAGE_CONFIG['params']['title'],
			'IF(approved.kind = 0, project_user.name , document_user.name )' => $this->PAGE_CONFIG['params']['name_receiver']
		);

		$offset   = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		$get_data = $this->approved_model->approved_archive_list($option,PAGING_PER_PAGE,$offset);

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
		$option = array('approved.no'=>$no);
		$result = $this->approved_model->approved_archive_detail($option);

		$data['action_type'] = 'create';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']  = site_url('approved_archive/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action
		$data['list_url']    = site_url('approved_archive/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);

		$data['data'] = array(
			'no'            => '-',
			'kind'          => '',
			'project_no'    => '',
			'user_no'       => '',
			'menu_no'       => '',
			'title'         => '',
			'sData'         => '',
			'eData'         => '',
			'file'          => '',
			'order'         => 0,
			'created'       => Date('Y-m-d'),
			'user_name'     => $this->session->userdata('name'),
			'part_name'     => '',
			'category_name' => '',
			'pPoint'        => '',
			'mPoint'        => '',
			'contents'      => '',
			'p_contents'    => '',
			'document_name' => ''
		);

		if ($result->num_rows() > 0){
			$result = $result->row();

			$data['action_type'] = 'edit';
			$data['data'] = array(
				'no'            => $result->no,
				'kind'          => $result->kind,
				'project_no'    => $result->project_no,
				'user_no'       => $result->user_no,
				'menu_no'       => $result->menu_no,
				'title'         => $result->title,
				'sData'         => substr($result->sData,0,10),
				'eData'         => substr($result->eData,0,10),
				'file'          => $result->file,
				'order'         => $result->order,
				'created'       => substr($result->created,0,10),
				'user_name'     => $result->user_name,
				'part_name'     => $result->part_name,
				'category_name' => $result->category_name,
				'pPoint'        => $result->pPoint,
				'mPoint'        => $result->mPoint,
				'contents'      => $result->contents,
				'p_contents'    => nl2br($result->p_contents),
				'document_name' => $result->document_name
			);
		}
		
		$this->load->view('approved/write_archive_v',$data);
	}
	public function proc(){
		$this->load->library('form_validation');


		$action_type   = $this->input->post('action_type');
		$no            = $this->input->post('no');
		$approved_kind = $this->input->post('approved_kind');
		$task_no       = $this->input->post('task_no'); // 불러온 문서번호
		
		if( $approved_kind == '0' ){
			$department  = $this->input->post('p_department');
			$title       = $this->input->post('p_title');
			$contents    = $this->input->post('p_contents');
			$sData       = $this->input->post('p_sData');
			$eData       = $this->input->post('p_eData');
			$file        = $this->input->post('p_file');
			$order       = $this->input->post('p_order');
		}else{
			$department  = $this->input->post('d_department');
			$title       = $this->input->post('d_title');
			$contents    = $this->input->post('d_contents');
			$sData       = $this->input->post('d_sData');
			$eData       = $this->input->post('d_eData');
			$file        = $this->input->post('d_file');
			$order       = $this->input->post('d_order');
		}
		$parameters = urldecode($this->input->post('parameters'));
		
		if( $action_type == 'create' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('approved_kind','결재 종류','required');
			$this->form_validation->set_rules('task_no','업무/문서 no','required');
			if( $approved_kind == '0' ){
				$this->form_validation->set_rules('p_department','담당부서','required');
				$this->form_validation->set_rules('p_title','제목','required|max_length[200]');
				//$this->form_validation->set_rules('p_contents','내용','required');
				$this->form_validation->set_rules('p_sData','진행기간','required');
				$this->form_validation->set_rules('p_eData','진행기간','required');
				$this->form_validation->set_rules('p_order','순서','required|numeric');
			}else{
				$this->form_validation->set_rules('d_department','담당부서','required');
				$this->form_validation->set_rules('d_title','제목','required|max_length[200]');
				//$this->form_validation->set_rules('d_contents','내용','required');
				$this->form_validation->set_rules('d_sData','진행기간','required');
				$this->form_validation->set_rules('d_eData','진행기간','required');
				$this->form_validation->set_rules('d_order','순서','required|numeric');
			}

			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			

			$option = array(
				'kind'       =>$approved_kind,
				'project_no' =>$task_no,
				'user_no'    =>$this->session->userdata('no'),
				'menu_no'    =>$department,
				'title'      =>$title,
				'sData'      =>$sData,
				'eData'      =>$eData,
				'order'      =>$order
			);
			$insert_id = $this->approved_model->set_approved_insert($option);

			if($approved_kind == '1'){
				// 일반업무 등록시 담당자 self insert -> sw_document_staff;
				$option = array();
				array_push($option,array(
					'approved_no' => $insert_id,
					'menu_no'     => $department,
					'user_no'     => $this->session->userdata('no'),
					'order'       => 1
				));
				$result = $this->approved_model->temp_document_staff_insert($option);
			}

			if($contents){
				$option = array(
					'approved_no' =>$insert_id,
					'user_no'     =>$this->session->userdata('no'),
					'contents'    =>$contents
				);
				$result = $this->approved_model->set_approved_contents_insert($option);
			}

			alert('등록되었습니다.', site_url('approved_archive/lists/') );
		}elseif( $action_type == 'edit' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('approved_kind','결재 종류','required');
			$this->form_validation->set_rules('task_no','업무/문서 no','required');

			if( $approved_kind == '0' ){
				$this->form_validation->set_rules('no','결재 no','required');
				$this->form_validation->set_rules('p_department','담당부서','required');
				$this->form_validation->set_rules('p_title','제목','required|max_length[200]');
				//$this->form_validation->set_rules('p_contents','내용','required');
				$this->form_validation->set_rules('p_sData','진행기간','required');
				$this->form_validation->set_rules('p_eData','진행기간','required');
				$this->form_validation->set_rules('p_order','순서','required|numeric');
			}else{
				$this->form_validation->set_rules('no','결재 no','required');
				$this->form_validation->set_rules('d_department','담당부서','required');
				$this->form_validation->set_rules('d_title','제목','required|max_length[200]');
				//$this->form_validation->set_rules('d_contents','내용','required');
				$this->form_validation->set_rules('d_sData','진행기간','required');
				$this->form_validation->set_rules('d_eData','진행기간','required');
				$this->form_validation->set_rules('d_order','순서','required|numeric');
			}

			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$option = array(
				'project_no' =>$task_no,
				'menu_no'    =>$department,
				'title'      =>$title,
				'sData'      =>$sData,
				'eData'      =>$eData,
				'order'      =>$order
			);
			$this->approved_model->set_approved_update($option,array('no'=>$no));

			//if($contents){
				$option = array(
					'approved_no' =>$no,
					'user_no'     =>$this->session->userdata('no'),
					'contents'    =>$contents
				);
				$result = $this->approved_model->set_approved_contents_insert($option);
			//}

			alert('수정되었습니다.', site_url('approved_archive/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no) );
		}elseif( $action_type == 'delete' ){

			$this->form_validation->set_rules('no','no','required');

			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			$set_no = is_array($no) ? implode(',',$no):$no;
			
			/* 데이터 삭제 */
			$this->approved_model->set_approved_delete($set_no);
			
			alert('삭제되었습니다.', site_url('approved_archive/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}

	/* 담당자 */
	public function _staff_lists(){
		$approved_no = $this->input->post('approved_no');
		$option = array(
			'approved_no'=>$approved_no
		);
		$result = $this->approved_model->get_approved_staff_list($option);
		echo json_encode($result);
	}

	public function _staff_insert(){
		$approved_no = $this->input->post('approved_no');
		
		$json_data   = json_decode($this->input->post('json_data'));
		
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$count_check = $this->approved_model->get_check($approved_no);
			if( $count_check > 0 ){
				$return = array(
					'result' => 'error',
					'msg' => '이미 등록된 결재가 있습니다.'
				);
			}else{
			
				$option = array();
				for( $i=0; $i < count($json_data)-1; $i++ ){
					if($i == 0){
						$status = 'a';
					}else{
						$status = NULL;
					}
					array_push($option,array(
						'approved_no'   => $approved_no,
						'sender'        => $json_data[$i]->user_no,
						'receiver'      => $json_data[$i+1]->user_no,
						'part_sender'   => $json_data[$i]->menu_no,
						'part_receiver' => $json_data[$i+1]->menu_no,
						'status'        => $status,
						'order'         => $json_data[$i]->order,
						'created'       => Date('Y-m-d H:i:s')
					));
				}

				$result = $this->approved_model->set_approved_staff_insert($option,array('approved_no'=>$approved_no));
				$return = array(
					'result' => 'ok',
					'msg' => 'ok'
				);

			}
		}
		echo json_encode($return);
	}


	/* 일반업무 담당자 임시테이블 등록 */
	public function _temp_doc_staff_lists(){
		$approved_no = $this->input->post('approved_no');
		$option = array(
			'approved_no'=>$approved_no
		);
		$result = $this->approved_model->temp_document_staff_list($option);
		echo json_encode($result);
	}

	public function _temp_doc_staff_insert(){
		$approved_no = $this->input->post('approved_no');
		$json_data  = json_decode($this->input->post('json_data'));
		
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$count_check = $this->approved_model->get_check($approved_no);
			if( $count_check > 0 ){
				$return = array(
					'result' => 'error',
					'msg' => '이미 등록된 결재가 있습니다.'
				);
			}else{
				
				$option = array();
				$i = 1;
				foreach($json_data as $key) {
					array_push($option,array(
						'approved_no' => $approved_no,
						'menu_no'     => $key->menu_no,
						'user_no'     => $key->user_no,
						'bigo'        => $key->bigo,
						'order'       => $i
					));
					$i++;
				}
				$result = $this->approved_model->temp_document_staff_insert($option,array('approved_no'=>$approved_no));
				$return = array(
					'result' => 'ok',
					'msg' => 'ok'
				);

			}
		}
		echo json_encode($return);
	}
}
/* End of file approved_archive.php */
/* Location: ./controllers/approved_archive.php */