<?
class Meeting extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('meeting_model');
		
		$this->PAGE_CONFIG['segment']  = 3;
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment( $this->PAGE_CONFIG['segment'] ,1);
		$this->PAGE_CONFIG['params']   = array(
			'sData'     => !$this->input->get('sData')     ? '' : $this->input->get('sData')    ,
			'eData'     => !$this->input->get('eData')     ? '' : $this->input->get('eData')    ,
			'menu_no'   => !$this->input->get('menu_no')   ? '' : $this->input->get('menu_no')  ,
			'active'    => $this->input->get('active'),
			'title'     => !$this->input->get('title')     ? '' : $this->input->get('title')    , 
			'user_name' => !$this->input->get('user_name') ? '' : $this->input->get('user_name')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();
		if( $method == 'write' or $method == 'proc' ){
			permission_check('meeting','W');
		}else{
			permission_check('meeting','R');
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
					set_cookie('left_menu_open_cookie',site_url('meeting/'),'0');
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
		$array_menu = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
		
		$option['where'] = array(
			'date_format(meeting.created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['sData'],
			'date_format(meeting.created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['eData'],
			'meeting.is_active'  => $this->PAGE_CONFIG['params']['active']
		);		
		$option['where_in'] = array(
			'meeting.menu_no' => $array_menu
		);
		$option['like'] = array(
			'user.name'    => $this->PAGE_CONFIG['params']['user_name'],
			'meeting.name' => $this->PAGE_CONFIG['params']['title']
		);
		return $option;
	}
	
	
	
	public function index(){
		$this->lists();
	}
	public function lists(){
		$option = $this->getListOption();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->meeting_model->get_meeting_list($option,NULL,NULL,'count');
		$data['list']          = $this->meeting_model->get_meeting_list($option,PAGING_PER_PAGE,$offset);
		
		$data['anchor_url']    = site_url('meeting/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('meeting/write/'.$this->PAGE_CONFIG['params_string']);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('meeting/proc/' .$this->PAGE_CONFIG['cur_page']);
		$data['excel_url']     = site_url('meeting/excel/'.$this->PAGE_CONFIG['params_string']);

		$config['base_url']    = site_url('meeting/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination']    = $this->pagination->create_links();

		$this->load->view('meeting/list_meeting_v',$data);
	}
	
	public function excel(){
		$option = $this->getListOption();
		$data['total'] = $this->meeting_model->get_meeting_list($option,NULL,NULL,'count');
		$data['list']  = $this->meeting_model->get_meeting_list($option,$data['total'],0);
	
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getProperties()->setCreator("groupware");
		$objPHPExcel->getProperties()->setLastModifiedBy("groupware");
		$objPHPExcel->getProperties()->setTitle("회의 정보");
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '제목');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '사용여부');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '등록일자');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '등록자');
	
		$row = 2;
		foreach ( $data['list'] as $lt ) {
			$menu = search_node($lt['menu_no'],'parent');
				
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $menu['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $lt['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $lt['active']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $lt['created']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $lt['user_name']);
			$row ++;
		}
	
		$filename = '회의 정보_' . date('Y년 m월 d일 H시 i분 s초', time()) . '.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'meeting.no'=>$no
		);
		
		$setVla = array(
			'user_name' => $this->session->userdata('name'),
			'order'     => '0',
			'is_active' => '0',
			'created'   => date('Y-m-d')
		);
		
		$data['data'] = $this->meeting_model->get_meeting_detail($option,$setVla);
		
		if( !$data['data']['no'] ){
			$data['action_type'] = 'create';
		}else{
			$data['action_type'] = 'edit';
		}
		
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']  = site_url('meeting/proc/'.$this->PAGE_CONFIG['cur_page']);
		$data['list_url']    = site_url('meeting/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
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
			$result = $this->meeting_model->set_insert($option);
			alert('등록되었습니다.', site_url('meeting/lists/') );

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
						if( is_file($config['upload_path'].$oldFile) ){
							unlink($config['upload_path'].$oldFile);
						}
					}
				}
			}
			
			$values = array(
				'menu_no'   => $menu_no,
				'name'      => $title,
				'contents'  => $contents,
				'order'     => $order,
				'is_active' => $is_active,
				'file'      => $file_name
			);
			$option['where'] = array(
				'no'=>$no
			);
			$this->meeting_model->set_update($values,$option);

			alert('수정되었습니다.', site_url('meeting/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no','no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$option['where_in'] = array(
				'object.no' => $no
			);
				
			$list = $this->meeting_model->get_meeting_detail($option,count($no),0);
			
			foreach( $list as $lt ){
				if($lt['file'] != ''){
					if( is_file(realpath($config['upload_path']) . '/' . $getData['file']) ){
						unlink(realpath($config['upload_path']) . '/' . $lt['file']);
					}
				}
			}
			
			$option['where_in'] = array(
				'no'=>$no
			);
			$this->meeting_model->set_delete($option);
			alert('삭제되었습니다.', site_url('meeting/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file meeting.php */
/* Location: ./controllers/meeting.php */