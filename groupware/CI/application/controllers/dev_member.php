<?
class Dev_member extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('member_model');
		
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
	
		$data['total']         = $this->member_model->get_user_list($option,null,null,'count');
		$data['list']          = $this->member_model->get_user_list($option,PAGING_PER_PAGE,$offset);
	
		$data['anchor_url']    = site_url('dev_member/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('dev_member/write/'.$this->PAGE_CONFIG['params_string']);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['search_url']    = site_url('dev_member/');
		$data['action_url']    = site_url('dev_member/proc/');
		$data['excel_url']     = site_url('dev_member/excel/'.$this->PAGE_CONFIG['params_string']);
	
		$config['base_url']    = site_url('dev_member/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];
	
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
	
		$this->load->view('member/user_list_v',$data);
	}
	
	public function excel(){
		$option = $this->getListOption();
	
		$data['total'] = $this->member_model->get_user_list($option,null,null,'count');
		$data['list']  = $this->member_model->get_user_list($option,$data['total'],0);
	
	
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getProperties()->setCreator("groupware");
		$objPHPExcel->getProperties()->setLastModifiedBy("groupware");
		$objPHPExcel->getProperties()->setTitle("사원 관리");
		$objPHPExcel->setActiveSheetIndex(0);
	
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
		foreach (range('A', 'H') as $column){
			$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth(20);
			$objPHPExcel->getActiveSheet()->getStyle($column.'1')->getFont()->setBold(true);
	
			$objPHPExcel->getActiveSheet()->getStyle($column.'1')->applyFromArray(
				array(
					'font' => array(
						'bold' => true,
						'size' => 14
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						'wrap'       => true
					)
				)
			);
		}
	
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '이름');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '휴대폰번호');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '이메일');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '재직여부');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '등록일자');
	
		$row = 2;
		foreach ( $data['list'] as $lt ) {
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $lt['name']);
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$row, $lt['mobile'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $lt['email']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $lt['active']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $lt['created']);
			$row ++;
		}
	
		$filename = '사원 관리_' . date('Y년 m월 d일 H시 i분 s초', time()) . '.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'no'=>$no
		);
		$setVla = array(
			'order'  => '0'
		);
		$data['data'] = $this->member_model->get_user_detail($option,$setVla);
		
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
		$this->md_company->setTable($this->TABLE_NAME);
		
		$action_type = $this->input->post ( 'action_type' );
		//$file = $this->input->post('userfile');
		$no = $this->input->post('no');
		$id = $this->input->post('id');
		$passwd = $this->input->post('pass');
		$name = $this->input->post('name');
		$position = $this->input->post('position');
		$phone = $this->input->post('phone');
		$selPhone = $this->input->post('sel_phone');
		$email = $this->input->post('email');
		$addr = $this->input->post('addr');
		$anual = $this->input->post('annual');
		$anual_start = $this->input->post('anual_start');
		$anual_end = $this->input->post('anual_end');
		$birthday = $this->input->post('birthday');
		$joinDate = $this->input->post('join_date');
		$sex = $this->input->post('sex');
		$color = $this->input->post('color');
		$order = $this->input->post('order');
		$inOffice = $this->input->post('in_office');
		
		$parameters   = urldecode($this->input->post('parameters'));
		
		$config['upload_path'] = 'upload/member/';
		$config['remove_spaces'] = true;
		$config['encrypt_name'] = true;
		$config['allowed_types'] = FILE_IMAGE_TYPE;
		if( $action_type == 'create' ){
			//$category = $this->uri->segment(2);
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('id','아이디','required|max_length[20]');
			$this->form_validation->set_rules('pass','비밀번호','required|max_length[20]');
			$this->form_validation->set_rules('name','이름','required');
			$this->form_validation->set_rules('position','직급','required');
			$this->form_validation->set_rules('sel_phone','핸드폰 번호','required');
			$this->form_validation->set_rules('email','직급','required');
			$this->form_validation->set_rules('annual','연차','required');
			$this->form_validation->set_rules('anual_start','연차적용일 시작','required');
			$this->form_validation->set_rules('anual_end','연차적용일 끝','required');
			$this->form_validation->set_rules('birthday','생일','required');
			$this->form_validation->set_rules('join_date','입사일','required');
			$this->form_validation->set_rules('color','색상','required');
			
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			
			$passwd = $this->encryp($passwd);
			
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
					'id'=>$id,
					'pwd'=>$passwd,
					'name'=>$name,
					'phone'=>$phone,
					'mobile'=>$selPhone,
					'email'=>$email,
					'addr'=>$addr,
					'annual'=>$anual,
					'sDate'=>$anual_start,
					'eDate'=>$anual_end,
					'birth'=>$birthday,
					'gender'=>$sex,
					'inDate'=>$joinDate,
					'color'=>$color,
					'file'=>$file,
					'order'=>$order,
					'is_active'=>$inOffice,
					'created'=>$cur,
					'position'=>$position,
					'origin_file' => $origin_file
			);
			
			$result = $this->md_company->create($data);
			alert('등록되었습니다.', site_url('member' . '/index') );
			
		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('pass','비밀번호','required|max_length[20]');
			$this->form_validation->set_rules('name','이름','required');
			$this->form_validation->set_rules('position','직급','required');
			$this->form_validation->set_rules('sel_phone','핸드폰 번호','required');
			$this->form_validation->set_rules('email','직급','required');
			$this->form_validation->set_rules('annual','연차','required');
			$this->form_validation->set_rules('anual_start','연차적용일 시작','required');
			$this->form_validation->set_rules('anual_end','연차적용일 끝','required');
			$this->form_validation->set_rules('birthday','생일','required');
			$this->form_validation->set_rules('join_date','입사일','required');
			$this->form_validation->set_rules('color','색상','required');
			
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			$file = $origin_file = NULL;
			
			$passwd = $this->encryp($passwd);
			
			if( $_FILES['userfile']['name'] ) {
				
				$this->load->library('upload', $config);
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					//이전파일 삭제하고 업로드
					$exUserFile = $this->md_company->get(array('no'=>$no), 'file');
					if(count($exUserFile) > 0 && $exUserFile[0]['file'] != NULL)
						unlink(realpath($config['upload_path']) . '/' . $exUserFile[0]['file']);
					$upload_data = $this->upload->data();
					$file = $upload_data['file_name'];
					$origin_file = $_FILES['userfile']['name'];
				}
			}
			
			$data = array(
					'pwd'=>$passwd,
					'name'=>$name,
					'phone'=>$phone,
					'mobile'=>$selPhone,
					'email'=>$email,
					'addr'=>$addr,
					'annual'=>$anual,
					'sDate'=>$anual_start,
					'eDate'=>$anual_end,
					'birth'=>$birthday,
					'gender'=>$sex,
					'inDate'=>$joinDate,
					'color'=>$color,
					'order'=>$order,
					'is_active'=>$inOffice,
					'position'=>$position,
					'origin_file' => $origin_file,
			);
			if($file != null)
				$data['file'] = $file;
			
			$this->md_company->modify(array('no'=>$no), $data);
			alert('수정되었습니다.', site_url('member' . '/index') );
			
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no', 'no','required');
			if ($this->form_validation->run() == FALSE){
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
			$set_no = is_array($no) ? implode(',',$no):$no;
			$where = 'no in (' . $set_no . ')';
			$this->md_company->delete($where);
			alert('삭제되었습니다.', site_url('member' . '/index') );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
	
	public function _lists(){
		$dptNum = $this->input->post('menu_no');
		echo $this->md_company->getUsersByDepartment($dptNum);
	}
	
	public function _allList(){
		$this->md_company->setTable('sw_user');
		$this->load->library("common");
		$ret = $this->md_company->get(NULL, 'no, name');
		if (count($ret) > 0){
			echo $this->common->getRet(true, $ret);
		}else
			echo $this->common->getRet(false, 'no data');
	}


	/* 부서 */
	public function _department_lists(){
		$no = $this->input->post('no');
		$option = array(
			'user_no'=>$no
		);
		$result = $this->md_company->get_department_list($option);
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
			$option = array();
			foreach($json_data as $key) {
				array_push($option,array(
					'user_no'  => $no,
					'menu_no'  => $key->menu_no,
					'position' => $key->position,
					'bigo'     => $key->bigo,
					'order'    => $key->order,
				));
			}
			$result = $this->md_company->set_department_insert($option,array('user_no'=>$no));
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
		$data = $this->md_company->get_annual_count(array('no'=>$no));
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
		$result['no_data'] = $this->md_company->get_no_list(array('B.user_no'=>$no));

		$result['list'] = $this->md_company->get_annual_list(array('user_no'=>$no));
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
			$option = array();
			foreach($json_data as $key) {
				array_push($option,array(
					'user_no' => $no,
					'name'    => $key->name,
					'data'    => $key->data,
					'order'   => $key->order
				));
			}
			$result = $this->md_company->set_annual_insert($option,array('user_no'=>$no));
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
		$result = $this->md_company->get_permission_list($option,$no);
		echo json_encode($result);
	}

	public function _permission_insert(){
		$no = $this->input->post('no');
		$json_data  = json_decode($this->input->post('json_data'));
		
		if( count($json_data) <= 0){
			$this->md_company->set_permission_delete(array('user_no'=>$no));
		}else{
			$option = array();
			foreach($json_data as $key) {
				array_push($option,array(
					'user_no'    => $no,
					'category'   => $key->category,
					'permission' => $key->permission
				));
			}
			$this->md_company->set_permission_insert($option,array('user_no'=>$no));
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