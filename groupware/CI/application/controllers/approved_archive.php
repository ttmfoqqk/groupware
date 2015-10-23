<?
class Approved_archive extends CI_Controller{
	private $PAGE_CONFIG;

	public function __construct() {
		parent::__construct();

		$this->load->model('approved_model');
		
		$this->PAGE_CONFIG['segment']  = 3;
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment($this->PAGE_CONFIG['segment'],1);
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
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();
		if( $method == 'write' or $method == 'proc' ){
			permission_check('app-archive','W');
		}else{
			permission_check('app-archive','R');
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
					set_cookie('left_menu_open_cookie',site_url('approved_archive/lists/'),'0');
					
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
			'date_format(approved.created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['swData'],
			'date_format(approved.created,"%Y-%m-%d") <'  => $this->PAGE_CONFIG['params']['ewData'],
			'approved.no'         => $this->PAGE_CONFIG['params']['doc_no'],
			'approved.user_no'    => $this->session->userdata('no') // 나의 결재 정보
		);
		$option['like'] = array(
			'user.name'      => $this->PAGE_CONFIG['params']['name_sender'],
			'approved.title' => $this->PAGE_CONFIG['params']['title'],
			'IF(approved.kind = 0, project_user.name , document_user.name )' => $this->PAGE_CONFIG['params']['name_receiver']
		);
		
		$array_sender   = search_node($this->PAGE_CONFIG['params']['part_sender'],'children');
		$array_receiver = search_node($this->PAGE_CONFIG['params']['part_receiver'],'children');
		$array_menu     = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
		
		$option['where_in'] = array(
			'approved.menu_no' => $array_sender,
			'IF(approved.kind = 0, project_staff.menu_no , document_staff.menu_no )' => $array_receiver,
			'IF(approved.kind = 0, project.menu_no , document.menu_no)' => $array_menu
		);
		
		$sData  = $this->PAGE_CONFIG['params']['sData'];
		$eData  = $this->PAGE_CONFIG['params']['eData'];
		
		$custom_sData = '';
		$custom_eData = '';
		$custom_query = '';
		if($sData){
			$custom_sData = '(approved.sData >= "'.$sData.'" or approved.eData >= "'.$sData.'")';
		}
		if($eData){
			$custom_eData = '(approved.sData <= "'.$eData.'" or approved.eData <= "'.$eData.'")';
		}
		if($sData && $eData){
			$option['custom'] = '( '.$custom_sData.' and '.$custom_eData.' )';
		}else{
			$option['custom'] = $custom_sData . $custom_eData;
		}
		return $option;
	}
	
	
	
	

	public function index(){
		$this->lists();
	}

	public function lists(){
		$option = $this->getListOption();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->approved_model->approved_archive_list($option,null,null,'count');
		$data['list']          = $this->approved_model->approved_archive_list($option,PAGING_PER_PAGE,$offset);
		$data['anchor_url']    = site_url('approved_archive/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']); // 글 링크
		$data['write_url']     = site_url('approved_archive/write/'.$this->PAGE_CONFIG['params_string']); // 글 링크
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']    = site_url('approved_archive/proc/' .$this->PAGE_CONFIG['cur_page']); // 폼 action
		$data['excel_url']     = site_url('approved_archive/excel/'.$this->PAGE_CONFIG['params_string']);

		$config['base_url']    = site_url('approved_archive/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('approved/list_archive_v',$data);
	}
	
	public function excel(){
		$this->load->library('Excel');
		$excel = new Excel();
		$option = $this->getListOption();
	
		$data['total'] = $this->approved_model->approved_archive_list($option,null,null,'count');
		$data['list']  = $this->approved_model->approved_archive_list($option,$data['total'],0);
		
		$title = '결재 보관함';
		$labels = array(
			'A' => '분류',
			'B' => '제목',
			'C' => '진행기간',
			'D' => '결재',
			'E' => '누락',
			'F' => '등록일자',
			'G' => '담당자'
		);
		
		$values=array();
		
		foreach ( $data['list'] as $lt ) {
			$menu = search_node($lt['menu_no'],'parent');
			$item = array(
				'A' => $menu['name'],
				'B' => $lt['title'],
				'C' => $lt['sData'].' ~ '.$lt['eData'],
				'D' => ($lt['kind']=='0' ? '+'.$lt['pPoint'] : ''),
				'E' => ($lt['kind']=='0' ? '-'.$lt['mPoint'] : ''),
				'F' => $lt['created'],
				'G' => $lt['user_name']
			);
			array_push($values, $item);
		}
		
		$excel->printExcel($title,$labels,$values);
	}
	
	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
				'approved.no'=>$no
		);		
		$setVla = array(
			'created'   => Date('Y-m-d'),
			'user_name' => $this->session->userdata('name')
		);
		
		$data['data'] = $this->approved_model->approved_archive_detail($option,$setVla);
		
		if( !is_numeric($data['data']['no']) ){
			$data['action_type'] = 'create';
		}else{
			$data['action_type'] = 'edit';
			
			if($data['data']['file']){
				if($data['data']['kind']=='0'){
					$data['data']['file_link'] = '<a href="'.site_url('download?path=upload/project/&oname='.$data['data']['file'].'&uname='.$data['data']['file']).'">'.$data['data']['file'].'</a>';
				}else{
					$data['data']['file_link'] = '<a href="'.site_url('download?path=upload/approved/&oname='.$data['data']['file'].'&uname='.$data['data']['file']).'">'.$data['data']['file'].'</a>';
				}
			}
		}

		$data['action_type'] = 'create';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']  = site_url('approved_archive/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action
		$data['list_url']    = site_url('approved_archive/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		
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
		$oldFile    = $this->input->post('oldFile');
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
			
			if( $approved_kind == '1' ){
				$file_name = '';
				if( $_FILES['d_file']['name'] ) {
					$config['upload_path']   = 'upload/approved/';
					$config['allowed_types'] = FILE_ALL_TYPE;
					$config['encrypt_name']  = false;
						
					$this->load->library('upload', $config);
						
					if ( !$this->upload->do_upload('d_file') ){
						$upload_error = $this->upload->display_errors('','') ;
						alert($upload_error);
					}else{
						$upload_data = $this->upload->data();
						$file_name = $upload_data['file_name'];
					}
				}
			}

			

			$option = array(
				'kind'       =>$approved_kind,
				'project_no' =>$task_no,
				'user_no'    =>$this->session->userdata('no'),
				'menu_no'    =>$department,
				'title'      =>$title,
				'sData'      =>$sData,
				'eData'      =>$eData,
				'file'       =>$file_name,
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
			
			if( $approved_kind == '1' ){
				$file_name = $oldFile;
				if( $_FILES['d_file']['name'] ) {
					$config['upload_path']   = 'upload/approved/';
					$config['allowed_types'] = FILE_ALL_TYPE;
					$config['encrypt_name']  = false;
						
					$this->load->library('upload', $config);
						
					if ( !$this->upload->do_upload('d_file') ){
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
			}
			
			$option = array(
				'project_no' =>$task_no,
				'menu_no'    =>$department,
				'title'      =>$title,
				'sData'      =>$sData,
				'eData'      =>$eData,
				'file'       =>$file_name,
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