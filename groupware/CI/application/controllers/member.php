<?
class Member extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('common_model');
		$this->load->model('member_model');
		
		$this->PAGE_CONFIG['tableName']            = 'sw_user';
		$this->PAGE_CONFIG['tableName_department'] = 'sw_user_department';
		$this->PAGE_CONFIG['tableName_annual']     = 'sw_user_annual';
		$this->PAGE_CONFIG['tableName_permission'] = 'sw_user_permission';
		
		
		
		$this->PAGE_CONFIG['segment']  = 3;
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment( $this->PAGE_CONFIG['segment'] ,1);
		$this->PAGE_CONFIG['params'] = array(
			'sData'     => !$this->input->get('sData')     ? '' : $this->input->get('sData')     ,
			'eData'     => !$this->input->get('eData')     ? '' : $this->input->get('eData')     ,
			'name'      => !$this->input->get('name')      ? '' : $this->input->get('name')      ,
			'phone'     => !$this->input->get('phone')     ? '' : $this->input->get('phone')     ,
			'email'     => !$this->input->get('email')     ? '' : $this->input->get('email')     ,
			'is_active' => !$this->input->get('is_active') ? '' : $this->input->get('is_active')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
	}

	public function _remap($method){
		login_check();

		if( $method == 'write' or $method == 'proc'){
			permission_check('member','W');
		}else{
			permission_check('member','R');
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
					set_cookie('left_menu_open_cookie',site_url('member'),'0');
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
			'date_format(created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['sData'],
			'date_format(created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['eData'],
			'is_active' => $this->PAGE_CONFIG['params']['is_active']
		);
		$option['like'] = array(
			'name'  => $this->PAGE_CONFIG['params']['name'],
			'phone' => $this->PAGE_CONFIG['params']['phone'],
			'email' => $this->PAGE_CONFIG['params']['email']
		);
		return $option;
	}
	
	public function index(){
		$this->lists();
	}
	
	public function lists(){
		$option = $this->getListOption();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		
		$select = array(
			'*' => TRUE,
			'IF(is_active=0,"재직","퇴사") as active'       => FALSE,
			'date_format(sDate,"%Y-%m-%d") as sDate'     => FALSE,
			'date_format(eDate,"%Y-%m-%d") as eDate'     => FALSE,
			'date_format(birth,"%Y-%m-%d") as birth'     => FALSE,
			'date_format(created,"%Y-%m-%d") as created' => FALSE
		);
		$order = array(
			'order' => 'ASC',
			'no'    => 'DESC'
		);
		$data['total']         = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option,NULL,NULL,NULL,'count');
		$data['list']          = $this->common_model->lists($this->PAGE_CONFIG['tableName'],$select,$option,PAGING_PER_PAGE,$offset,$order);
	
		//$data['total']         = $this->member_model->get_user_list($option,null,null,'count');
		//$data['list']          = $this->member_model->get_user_list($option,PAGING_PER_PAGE,$offset);
	
		$data['anchor_url']    = site_url('member/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('member/write/'.$this->PAGE_CONFIG['params_string']);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['search_url']    = site_url('member/');
		$data['action_url']    = site_url('member/proc/');
		$data['excel_url']     = site_url('member/excel/'.$this->PAGE_CONFIG['params_string']);
	
		$config['base_url']    = site_url('member/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];
	
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
	
		$this->load->view('member/user_list_v',$data);
	}
	
	public function excel(){
		$this->load->library('Excel');
		$excel = new Excel();
		$option = $this->getListOption();
		
		$select = array(
			'*' => TRUE,
			'IF(is_active=0,"재직","퇴사") as active'       => FALSE,
			'date_format(sDate,"%Y-%m-%d") as sDate'     => FALSE,
			'date_format(eDate,"%Y-%m-%d") as eDate'     => FALSE,
			'date_format(birth,"%Y-%m-%d") as birth'     => FALSE,
			'date_format(created,"%Y-%m-%d") as created' => FALSE
		);
		$order = array(
			'order' => 'ASC',
			'no'    => 'DESC'
		);
		$data['total'] = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option,NULL,NULL,NULL,'count');
		$data['list']  = $this->common_model->lists($this->PAGE_CONFIG['tableName'],$select,$option,$data['total'],0,$order);
	
		//$data['total'] = $this->member_model->get_user_list($option,null,null,'count');
		//$data['list']  = $this->member_model->get_user_list($option,$data['total'],0);
		
		$title = '사원 관리';
		$labels = array(
			'A' => '이름',
			'B' => '휴대폰번호',
			'C' => '이메일',
			'D' => '재직여부',
			'E' => '등록일자'
		);
		
		$values=array();
		
		foreach ( $data['list'] as $lt ) {
			$item = array(
					'A' => $lt['name'],
					'B' => $lt['mobile'],
					'C' => $lt['email'],
					'D' => $lt['active'],
					'E' => $lt['created']
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
		$data['data'] = $this->common_model->detail($this->PAGE_CONFIG['tableName'],NULL,$option,$setVla);
		//$data['data'] = $this->member_model->get_user_detail($option,$setVla);
		
		if( !$data['data']['no'] ){
			$data['action_type'] = 'create';
		}else{
			$data['action_type'] = 'edit';
		}

		$data['parameters'] = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url'] = site_url('member/proc/' .$this->PAGE_CONFIG['cur_page']);
		$data['list_url']   = site_url('member/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		
		$this->load->view('member/user_write_v',$data);
	}

	
	public function proc(){
		$this->load->library('form_validation');
		
		$action_type = $this->input->post ( 'action_type' );
		$no          = $this->input->post('no');
		$id          = $this->input->post('id');
		$pass        = $this->input->post('pass');
		$name        = $this->input->post('name');
		$position    = $this->input->post('position');
		$phone       = $this->input->post('phone');
		$mobile      = $this->input->post('mobile');
		$email       = $this->input->post('email');
		$addr        = $this->input->post('addr');
		$annual      = $this->input->post('annual');
		$sDate       = $this->input->post('sDate');
		$eDate       = $this->input->post('eDate');
		$birth       = $this->input->post('birth');
		$inDate      = $this->input->post('inDate');
		$gender      = $this->input->post('gender');
		$color       = $this->input->post('color');
		$order       = $this->input->post('order');
		$is_active   = $this->input->post('is_active');
		$parameters  = urldecode($this->input->post('parameters'));
		
		$config['upload_path'] = 'upload/member/';
		$config['remove_spaces'] = true;
		$config['encrypt_name'] = true;
		$config['allowed_types'] = FILE_IMAGE_TYPE;
		if( $action_type == 'create' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('id','아이디','required|max_length[20]');
			$this->form_validation->set_rules('pass','비밀번호','required|max_length[20]');
			$this->form_validation->set_rules('name','이름','required');
			$this->form_validation->set_rules('position','직급','required');
			$this->form_validation->set_rules('mobile','핸드폰 번호','required');
			$this->form_validation->set_rules('email','이메일','required');
			$this->form_validation->set_rules('annual','연차','required');
			$this->form_validation->set_rules('sDate','연차적용일 시작','required');
			$this->form_validation->set_rules('eDate','연차적용일 끝','required');
			$this->form_validation->set_rules('birth','생일','required');
			$this->form_validation->set_rules('inDate','입사일','required');
			$this->form_validation->set_rules('color','색상','required');
			
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			
			$pass = $this->member_model->encryp($pass);
			
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

			$set = array(
				'id'          => $id,
				'pwd'         => $pass,
				'name'        => $name,
				'phone'       => $phone,
				'mobile'      => $mobile,
				'email'       => $email,
				'addr'        => $addr,
				'annual'      => $annual,
				'sDate'       => $sDate,
				'eDate'       => $eDate,
				'birth'       => $birth,
				'gender'      => $gender,
				'inDate'      => $inDate,
				'color'       => $color,
				'file'        => $file,
				'order'       => $order,
				'is_active'   => $is_active,
				'position'    => $position,
				'origin_file' => $origin_file,
				'created'     => 'NOW()'
			);
			
			$result = $this->common_model->insert($this->PAGE_CONFIG['tableName'],$set);
			//$result = $this->member_model->set_user_insert($option);
			alert('등록되었습니다.', site_url('member') );
			
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('pass','비밀번호','required|max_length[20]');
			$this->form_validation->set_rules('name','이름','required');
			$this->form_validation->set_rules('position','직급','required');
			$this->form_validation->set_rules('mobile','핸드폰 번호','required');
			$this->form_validation->set_rules('email','직급','required');
			$this->form_validation->set_rules('annual','연차','required');
			$this->form_validation->set_rules('sDate','연차적용일 시작','required');
			$this->form_validation->set_rules('eDate','연차적용일 끝','required');
			$this->form_validation->set_rules('birth','생일','required');
			$this->form_validation->set_rules('inDate','입사일','required');
			$this->form_validation->set_rules('color','색상','required');
			
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}

			$option['where'] = array(
				'no'=>$no
			);
			
			$pass    = $this->member_model->encryp($pass);
			$getData = $this->common_model->detail($this->PAGE_CONFIG['tableName'],NULL,$option);
			//$getData = $this->member_model->get_user_detail($option);
			
			
			$file = $origin_file = NULL;
			if( $_FILES['userfile']['name'] ) {
				
				$this->load->library('upload', $config);
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					//이전파일 삭제하고 업로드
					
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
			
			$set = array(
				'pwd'         => $pass,
				'name'        => $name,
				'phone'       => $phone,
				'mobile'      => $mobile,
				'email'       => $email,
				'addr'        => $addr,
				'annual'      => $annual,
				'sDate'       => $sDate,
				'eDate'       => $eDate,
				'birth'       => $birth,
				'gender'      => $gender,
				'inDate'      => $inDate,
				'color'       => $color,
				'order'       => $order,
				'is_active'   => $is_active,
				'position'    => $position
			);
			if($file != null){
				$set['file'] = $file;
				$set['origin_file'] = $origin_file;
			}

			$this->common_model->update($this->PAGE_CONFIG['tableName'],$set, $option);
			//$this->member_model->set_user_update($values, $option);
			
			alert('수정되었습니다.', site_url('member/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no ) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no', 'no','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$option['where_in'] = array(
				'no' => $no
			);
			$select = array('file' => TRUE);
			$list = $this->common_model->lists($this->PAGE_CONFIG['tableName'],$select,$option,count($no),0);
			//$list = $this->member_model->get_user_list($option,count($no),0);
			
			foreach( $list as $lt ){
				if($lt['file'] != ''){
					if( is_file(realpath($config['upload_path']) . '/' . $lt['file']) ){
						unlink(realpath($config['upload_path']) . '/' . $lt['file']);
					}
				}
			}
			$this->common_model->delete($this->PAGE_CONFIG['tableName'],$option);
			//$this->member_model->set_user_delete($option);
			alert('삭제되었습니다.', site_url('member') );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
	
	public function _lists(){
		$dptNum = $this->input->post('menu_no');
		$array_menu = search_node($dptNum,'children');
		echo $this->member_model->getUsersByDepartment($array_menu);
	}
	
	public function _allList(){
		$select = array(
				'*' => TRUE,
				'IF(is_active=0,"재직","퇴사") as active'       => FALSE,
				'date_format(sDate,"%Y-%m-%d") as sDate'     => FALSE,
				'date_format(eDate,"%Y-%m-%d") as eDate'     => FALSE,
				'date_format(birth,"%Y-%m-%d") as birth'     => FALSE,
				'date_format(created,"%Y-%m-%d") as created' => FALSE
		);
		$order = array(
				'order' => 'ASC',
				'no'    => 'DESC'
		);
		$data['list']  = $this->common_model->lists($this->PAGE_CONFIG['tableName'],$select,NULL,NULL,NULL,$order);
		
		//$data['total'] = $this->member_model->get_user_list(null,null,null,'count');
		//$data['list']  = $this->member_model->get_user_list(null,$data['total'],0);
		
		if (count($data['list']) > 0){
			$return = array(
				'result' => 'true',
				'data'   => json_encode($data['list'])
			);
		}else{
			$return = array(
				'result' => 'false',
				'data'   => 'no data'
			);
		}
		echo json_encode($return);
	}


	/* 부서 */
	public function _department_lists(){
		$no = $this->input->post('no');
		$option['where'] = array(
			'user_no'=>$no
		);
		$order = array(
			'order' => 'ASC',
			'position' => 'ASC'
		);
		$result = $this->common_model->lists($this->PAGE_CONFIG['tableName_department'],NULL,$option,NULL,NULL,$order);
		//$result = $this->member_model->get_department_list($option);
		echo json_encode($result);
	}

	public function _department_insert(){
		$no = $this->input->post('no');
		$json_data  = json_decode($this->input->post('json_data'));
		
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$set = array();
			foreach($json_data as $key) {
				array_push($set,array(
					'user_no'  => $no,
					'menu_no'  => $key->menu_no,
					'position' => $key->position,
					'bigo'     => $key->bigo,
					'order'    => $key->order,
				));
			}
			
			$option['where'] = array('user_no'=>$no);
			
			$this->common_model->delete($this->PAGE_CONFIG['tableName_department'],$option);
			$result = $this->common_model->insert_batch($this->PAGE_CONFIG['tableName_department'],$set);
			//$result = $this->member_model->set_department_insert($option,array('user_no'=>$no));
			$return = array(
				'result' => 'ok',
				'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}

	/* 연차 */
	public function _annual_lists(){
		$no = $this->input->post('no');

		// 사용가능한,사용한 연차 카운트
		$data = $this->member_model->get_annual_count(array('no'=>$no));
		$result['cnt'] = array(
			'annual'  => 0,
			'use_cnt' => 0
		);
		if ($data->num_rows() > 0){
			$data = $data->row();
			$result['cnt'] = array(
				'annual'  => $data->annual,
				'use_cnt' => $data->use_cnt
			);
		}

		// 등록된 업무 일자 리스트
		$result['no_data'] = $this->member_model->get_no_list(array('B.user_no'=>$no));
		
		$option['where'] = array('user_no'=>$no);
		$order = array(
			'order' => 'ASC',
			'data'  => 'DESC'
		);
		$result['list'] = $this->common_model->lists($this->PAGE_CONFIG['tableName_annual'],NULL,$option,NULL,NULL,$order);
		//$result['list'] = $this->member_model->get_annual_list(array('user_no'=>$no));
		echo json_encode($result);
	}

	public function _annual_insert(){
		$no = $this->input->post('no');
		$json_data  = json_decode($this->input->post('json_data'));
		
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$set = array();
			foreach($json_data as $key) {
				array_push($set,array(
					'user_no' => $no,
					'name'    => $key->name,
					'data'    => $key->data,
					'order'   => $key->order
				));
			}
			$option['where'] = array('user_no'=>$no);
			$this->common_model->delete($this->PAGE_CONFIG['tableName_annual'],$option);
			$result = $this->common_model->insert_batch($this->PAGE_CONFIG['tableName_annual'],$set);
			//$result = $this->member_model->set_annual_insert($option,array('user_no'=>$no));
			$return = array(
				'result' => 'ok',
				'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}

	/* 권한 */
	public function _permission_lists(){
		$no = $this->input->post('no');
		$option = array(
			//'user_no'=>$no
		);
		$result = $this->member_model->get_permission_list($option,$no);
		echo json_encode($result);
	}

	public function _permission_insert(){
		$no = $this->input->post('no');
		$json_data  = json_decode($this->input->post('json_data'));
		
		$option['where'] = array('user_no'=>$no);
		if( count($json_data) <= 0){
			$this->common_model->delete($this->PAGE_CONFIG['tableName_permission'],$option);
			//$this->member_model->set_permission_delete(array('user_no'=>$no));
		}else{
			$set = array();
			foreach($json_data as $key) {
				array_push($set,array(
					'user_no'    => $no,
					'category'   => $key->category,
					'permission' => $key->permission
				));
			}
			$this->common_model->delete($this->PAGE_CONFIG['tableName_permission'],$option);
			$this->common_model->insert_batch($this->PAGE_CONFIG['tableName_permission'],$set);
			//$this->member_model->set_permission_insert($option,array('user_no'=>$no));
		}
		$return = array(
			'result' => 'ok',
			'msg' => 'ok'
		);
		
		echo json_encode($return);
	}
}
/* End of file member.php */
/* Location: ./controllers/member.php */