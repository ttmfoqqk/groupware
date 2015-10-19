<?
class Object extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('md_object');
		
		$this->PAGE_CONFIG['segment']  = 3;
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment( $this->PAGE_CONFIG['segment'] ,1);
		$this->PAGE_CONFIG['params'] = array(
			'sData'    => !$this->input->get('sData')    ? '' : $this->input->get('sData')    ,
			'eData'    => !$this->input->get('eData')    ? '' : $this->input->get('eData')    ,
			'menu_no'  => !$this->input->get('menu_no')  ? '' : $this->input->get('menu_no')  ,
			'name'     => !$this->input->get('name')     ? '' : $this->input->get('name')     ,
			'area'     => !$this->input->get('area')     ? '' : $this->input->get('area')     ,
			'userName' => !$this->input->get('userName') ? '' : $this->input->get('userName')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();

		if( $method == 'write' or $method == 'proc' ){
			permission_check('object','W');
		}else{
			permission_check('object','R');
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
					set_cookie('left_menu_open_cookie',site_url('object'),'0');
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
			'date_format(object.created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['sData'],
			'date_format(object.created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['eData'],
		);
		$option['like'] = array(
			'object.name' => $this->PAGE_CONFIG['params']['name'],
			'user.name'   => $this->PAGE_CONFIG['params']['userName']
		);
		$array_menu = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
	
		$option['where_in'] = array(
			'object.menu_no' => $array_menu
		);
		return $option;
	}
	
	
	public function index(){
		$this->lists();
	}
	public function lists(){
		$option = $this->getListOption();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		
		$data['total']         = $this->md_object->get_object_list($option,null,null,'count');
		$data['list']          = $this->md_object->get_object_list($option,PAGING_PER_PAGE,$offset);
		
		$data['anchor_url']    = site_url('object/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('object/write/'.$this->PAGE_CONFIG['params_string']);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('object/proc/' .$this->PAGE_CONFIG['cur_page']);
		$data['excel_url']     = site_url('object/excel/'.$this->PAGE_CONFIG['params_string']);		
		
		$config['base_url']    = site_url('object/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('company/object_v',$data);
	}
	
	public function excel(){
		$option = $this->getListOption();
	
		$data['total'] = $this->md_object->get_object_list($option,null,null,'count');
		$data['list']  = $this->md_object->get_object_list($option,$data['total'],0);
	
	
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getProperties()->setCreator("groupware");
		$objPHPExcel->getProperties()->setLastModifiedBy("groupware");
		$objPHPExcel->getProperties()->setTitle("물품 정보");
		$objPHPExcel->setActiveSheetIndex(0);
	
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
		foreach (range('A', 'E') as $column){
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
	
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '분류');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '물품명');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '물품위치');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '등록일자');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '관리자');
	
		$row = 2;
		foreach ( $data['list'] as $lt ) {
			$menu = search_node($lt['menu_no'],'parent');
	
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $menu['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $lt['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $lt['area']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $lt['created']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $lt['user_name']);
			$row ++;
		}
	
		$filename = '물품 정보_' . date('Y년 m월 d일 H시 i분 s초', time()) . '.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'object.no'=>$no
		);
		$setVla = array(
			'order'  => '0'
		);
		$data['data'] = $this->md_object->get_object_detail($option,$setVla);
		
		if( !$data['data']['no'] ){
			$data['action_type'] = 'create';
		}else{
			$data['action_type'] = 'edit';
		}

		$data['parameters'] = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url'] = site_url('object/proc/' .$this->PAGE_CONFIG['cur_page']);
		$data['list_url']   = site_url('object/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		
		$this->load->view('company/object_write',$data);
	}
	
	public function proc(){
		$this->load->library('form_validation');
		$this->load->model('md_company');
		
		$no          = $this->input->post('no');
		$action_type = $this->input->post('action_type');
		$menu_no     = $this->input->post('menu_no');
		$name        = $this->input->post('name');
		$area        = $this->input->post('area');
		$user_no     = $this->input->post('user_no');
		$bigo        = $this->input->post('bigo');
		$order       = $this->input->post('order');
		$is_active   = $this->input->post('is_active');
		$parameters  = urldecode($this->input->post('parameters'));
		
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

			$data = array(
				'menu_no'     => $menu_no,
				'user_no'     => $user_no,
				'name'        => $name,
				'area'        => $area,
				'bigo'        => $bigo,
				'file'        => $file,
				'order'       => $order,
				'file'        => $file,
				'is_active'   => $is_active,
				'origin_file' => $origin_file
			);

			$result = $this->md_object->set_object_insert($data);
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
			
			$option['where'] = array(
				'object.no'=>$no
			);
			$getData = $this->md_object->get_object_detail($option);
			
			$file = $origin_file = NULL;				
			if( $_FILES['userfile']['name'] ) {
				$this->load->library('upload', $config);
					
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
					$origin_file = null;
				}else{
					//이전파일 삭제하고 업로드
					if($getData['file']){
						unlink(realpath($config['upload_path']) . '/' . $getData['file']);
					}
					$upload_data = $this->upload->data();
					$file = $upload_data['file_name'];
					$origin_file = $_FILES['userfile']['name'];
				}
			}
			
			$values = array(
				'menu_no'     => $menu_no,
				'user_no'     => $user_no,
				'name'        => $name,
				'area'        => $area,
				'bigo'        => $bigo,
				'order'       => $order,
				'is_active'   => $is_active
			);
			
			if($file){
				$values['file'] = $file;
				$values['origin_file'] = $origin_file;
			}
			
			$option['where'] = array(
				'no'=>$no
			);
			
			$this->md_object->set_object_update($values, $option);
			alert('수정되었습니다.', site_url('object/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no', 'no','required');
			
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			
			$option['where_in'] = array(
				'object.no' => $no
			);
			
			$list = $this->object_model->get_user_list($option,count($no),0);

			foreach( $list as $lt ){
				if($lt['file'] != ''){
					unlink(realpath($config['upload_path']) . '/' . $lt['file']);
				}
			}
			
			$option['where_in'] = array(
				'no' => $no
			);
			$this->object_model->set_object_delete($option);
			alert('삭제되었습니다.', site_url('object') );
		}else{
			echo $action_type;
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file object.php */
/* Location: ./controllers/object.php */