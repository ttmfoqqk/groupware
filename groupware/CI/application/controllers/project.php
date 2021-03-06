<?
class Project extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('common_model');
		$this->load->model('project_model');
		
		$this->PAGE_CONFIG['tableName']       = 'sw_project';
		$this->PAGE_CONFIG['tableName_staff'] = 'sw_project_staff';
		
		$this->PAGE_CONFIG['segment']  = 3; 
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment( $this->PAGE_CONFIG['segment'] ,1);
		$this->PAGE_CONFIG['params'] = array(
			'sData'        => !$this->input->get('sData')        ? '' : $this->input->get('sData')       ,
			'eData'        => !$this->input->get('eData')        ? '' : $this->input->get('eData')       ,
			'swData'       => !$this->input->get('swData')       ? '' : $this->input->get('swData')      ,
			'ewData'       => !$this->input->get('ewData')       ? '' : $this->input->get('ewData')      ,
			'menu_part_no' => !$this->input->get('menu_part_no') ? '' : $this->input->get('menu_part_no'),
			'menu_no'      => !$this->input->get('menu_no')      ? '' : $this->input->get('menu_no')     ,
			'userName'     => !$this->input->get('userName')     ? '' : $this->input->get('userName')    ,
			'title'        => !$this->input->get('title')        ? '' : $this->input->get('title')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();
		
		if( $method == 'write' or $method == 'proc' ){
			permission_check('project','W');
		}else{
			permission_check('project','R');
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
					set_cookie('left_menu_open_cookie',site_url('project/'),'0');
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
			'date_format(sw_project.created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['swData'],
			'date_format(sw_project.created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['ewData'],
		);
		$option['like'] = array(
			'c.name' => $this->PAGE_CONFIG['params']['userName'],
			'title'  => $this->PAGE_CONFIG['params']['title']
		);
		
		$array_part = search_node($this->PAGE_CONFIG['params']['menu_part_no'],'children');
		$array_menu = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
		
		$option['where_in'] = array(
			'sw_project.menu_part_no' => $array_part,
			'sw_project.menu_no'      => $array_menu
		);
		
		$sData  = $this->PAGE_CONFIG['params']['sData'];
		$eData  = $this->PAGE_CONFIG['params']['eData'];
		
		$custom_sData = '';
		$custom_eData = '';
		$custom_query = '';
		if($sData){
			$custom_sData = '(sw_project.sData >= "'.$sData.'" or sw_project.eData >= "'.$sData.'")';
		}
		if($eData){
			$custom_eData = '(sw_project.sData <= "'.$eData.'" or sw_project.eData <= "'.$eData.'")';
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

		$data['total']         = $this->project_model->get_project_list($option,null,null,'count');
		$data['list']          = $this->project_model->get_project_list($option,PAGING_PER_PAGE,$offset);
		
		$data['anchor_url']    = site_url('project/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('project/write/'.$this->PAGE_CONFIG['params_string']);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('project/proc/' .$this->PAGE_CONFIG['cur_page']);
		$data['excel_url']     = site_url('project/excel/'.$this->PAGE_CONFIG['params_string']);		
		
		$config['base_url']    = site_url('project/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('project/project_v',$data);
	}
	
	public function excel(){
		$this->load->library('Excel');
		$excel = new Excel();
		
		$option = $this->getListOption();
		/*
		 * 엑셀 라이브러리 생성중
		 */
		$data['total'] = $this->project_model->get_project_list($option,null,null,'count');
		$data['list']  = $this->project_model->get_project_list($option,$data['total'],0);
		
		$title = '업무 정보';
		$labels = array(
				'A' => '담당부서',
				'B' => '분류',
				'C' => '제목',
				'D' => '진행기간',
				'E' => '결재점수',
				'F' => '누락점수',
				'G' => '기안일자',
				'H' => '기안자'
		);
		
		$values=array();
		foreach ( $data['list'] as $lt ) {
			$part = search_node($lt['part_no'],'parent');
			$menu = search_node($lt['menu_no'],'parent');

			$item = array(
				'A' => $part['name'],
				'B' => $menu['name'],
				'C' => $lt['title'],
				'D' => $lt['sData'].' ~ '.$lt['eData'],
				'E' => $lt['pPoint'],
				'F' => $lt['mPoint'],
				'G' => $lt['created'],
				'H' => $lt['user_name'] 
			);
			array_push($values, $item);		}
		
		$excel->printExcel($title,$labels,$values);
	}
	
	
	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'no'=>$no
		);
		$setVla = array(
			'pPoint' => '0',
			'mPoint' => '0',
			'order'  => '0',
			'cnt'    => '0'
		);
		$data['data'] = $this->project_model->get_project_detail($option,$setVla);
		
		if( !$data['data']['no'] ){
			$data['action_type'] = 'create';
		}else{
			$data['action_type'] = 'edit';
		}

		$data['parameters'] = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url'] = site_url('project/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action
		$data['list_url']   = site_url('project/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		
		$this->load->view('project/project_write_v',$data);
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
		$oldFile      = $this->input->post('oldFile');
		$parameters   = urldecode($this->input->post('parameters'));
		
		$config['upload_path']   = 'upload/project/';
		$config['allowed_types'] = FILE_ALL_TYPE;
		$config['encrypt_name']  = false;
		
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
			
			$file_name = '';
			if( $_FILES['userfile']['name'] ) {
				
			
				$this->load->library('upload', $config);
			
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_name = $upload_data['file_name'];
				}
			}

			$set = array(
				'menu_part_no' => $menu_part_no,
				'menu_no'      => $menu_no,
				'user_no'      => $this->session->userdata('no'),
				'title'        => $title,
				'contents'     => $contents,
				'sData'        => $sData,
				'eData'        => $eData,
				'pPoint'       => $pPoint,
				'mPoint'       => $mPoint,
				'file'         => $file_name,
				'order'        => $order,
				'created'      => 'NOW()'
			);
			$result = $this->common_model->insert($this->PAGE_CONFIG['tableName'],$set);
			//$result = $this->project_model->set_project_insert($option);
			//alert('등록되었습니다.', site_url('project/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
			alert('등록되었습니다.', site_url('project/lists/') ); //신규 등록 첫페이지로

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
			
			$file_name = $oldFile;
			if( $_FILES['userfile']['name'] ) {
				
				$this->load->library('upload', $config);
					
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_name = $upload_data['file_name'];
					
					if( $oldFile ){
						if( is_file($config['upload_path'].$oldFile) ){
							unlink($config['upload_path'].$oldFile);
						}
					}
				}
			}
			
			$set = array(
				'menu_part_no' =>$menu_part_no,
				'menu_no'      =>$menu_no,
				'title'        =>$title,
				'contents'     =>$contents,
				'sData'        =>$sData,
				'eData'        =>$eData,
				'pPoint'       =>$pPoint,
				'mPoint'       =>$mPoint,
				'file'         =>$file_name,
				'order'        =>$order
			);
			$option['where'] = array('no'=>$no);
			$this->common_model->update($this->PAGE_CONFIG['tableName'],$set,$option);
			//$this->project_model->set_project_update($values,$option);

			alert('수정되었습니다.', site_url('project/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no) );
		}elseif( $action_type == 'delete' ){
			/*
				결재 상태 count check
			*/
			$this->form_validation->set_rules('no','no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$option['where_in'] = array('no'=>$no);
			$this->common_model->delete($this->PAGE_CONFIG['tableName'],$option);
			
			$option['where_in'] = array('project_no'=>$no);
			$this->common_model->delete($this->PAGE_CONFIG['tableName_staff'],$option);
			
			alert('삭제되었습니다.', site_url('project/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}


	public function _lists(){
		$option['where'] = array(
			'date_format(sw_project.created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['swData'],
			'date_format(sw_project.created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['ewData'],
			'd.user_no' => $this->session->userdata('no')
		);
		$option['like'] = array(
			'c.name' => $this->PAGE_CONFIG['params']['userName'],
			'title'  => $this->PAGE_CONFIG['params']['title']
		);
		
		$array_part = search_node($this->PAGE_CONFIG['params']['menu_part_no'],'children');
		$array_menu = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
		
		$option['where_in'] = array(
			'sw_project.menu_part_no' => $array_part,
			'sw_project.menu_no'      => $array_menu
		);
		
		$sData  = $this->PAGE_CONFIG['params']['sData'];
		$eData  = $this->PAGE_CONFIG['params']['eData'];
		
		$custom_sData = '';
		$custom_eData = '';
		$custom_query = '';
		if($sData){
			$custom_sData = '(sw_project.sData >= "'.$sData.'" or sw_project.eData >= "'.$sData.'")';
		}
		if($eData){
			$custom_eData = '(sw_project.sData <= "'.$eData.'" or sw_project.eData <= "'.$eData.'")';
		}
		if($sData && $eData){
			$option['custom'] = '( '.$custom_sData.' and '.$custom_eData.' )';
		}else{
			$option['custom'] = $custom_sData . $custom_eData;
		}

		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->project_model->get_project_list($option,null,null,'count');
		$data['list']          = $this->project_model->get_project_list($option,PAGING_PER_PAGE,$offset);

		$config['base_url']    = '';
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		echo json_encode($data);
	}


	/* 담당자 */
	public function _staff_lists(){
		$project_no = $this->input->post('project_no');
		$option = array(
			'project_no'=>$project_no
		);
		$result = $this->project_model->get_project_staff_list($option);
		echo json_encode($result);
	}

	public function _staff_insert(){
		$project_no = $this->input->post('project_no');
		$json_data  = json_decode($this->input->post('json_data'));
		
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$set = array();
			$i = 1;
			foreach($json_data as $key) {
				array_push($set,array(
					'project_no' => $project_no,
					'menu_no'    => $key->menu_no,
					'user_no'    => $key->user_no,
					'bigo'       => $key->bigo,
					'order'      => $i
				));
				$i++;
			}
			
			$option['where'] = array('project_no'=>$project_no);
			$this->common_model->delete($this->PAGE_CONFIG['tableName_staff'],$option);
			$result = $this->common_model->insert_batch($this->PAGE_CONFIG['tableName_staff'],$set);			
			//$result = $this->project_model->set_project_staff_insert($option,array('project_no'=>$project_no));
			$return = array(
				'result' => 'ok',
				'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}
}
/* End of file project.php */
/* Location: ./controllers/project.php */