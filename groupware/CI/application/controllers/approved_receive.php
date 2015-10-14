<?
class Approved_receive extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('approved_model');

		$this->PAGE_CONFIG['segment']  = 4;
		$this->PAGE_CONFIG['set_page'] = $this->uri->segment(3,'all');
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment($this->PAGE_CONFIG['segment'],1);
		$this->PAGE_CONFIG['params']   = array(
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
		
		$page_title = '받은 결재';
		switch( $this->PAGE_CONFIG['set_page'] ) {
			case 'a' :
				$page_title .= ' - 작업중'; 
				break;
			case 'b' :
				$page_title .= ' - 결재대기';
				break;
			case 'c' :
				$page_title .= ' - 결재완료';
				break;
			case 'd' :
				$page_title .= ' - 반려';
				break;
			case 'ao' :
				$page_title .= ' - 미결재';
				break;
			default :
				$page_title = $page_title;
		}
		
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
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
		
		define('APPROVED_TITLE'  , $page_title);
    }

	public function _remap($method){
		login_check();
		if( $method == 'write' or $method == 'proc' ){
			permission_check('app-receive','W');
		}else{
			permission_check('app-receive','R');
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
					set_cookie('left_menu_open_cookie',site_url('approved_receive/lists/'.$this->PAGE_CONFIG['set_page']),'0');
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
			'date_format(approved.created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['ewData'],
			'IF(approved.kind = 0, project.menu_no , document.menu_no) = ' => $this->PAGE_CONFIG['params']['menu_no'],
			'approved.no'        => $this->PAGE_CONFIG['params']['doc_no'],
			'status.part_sender' => $this->PAGE_CONFIG['params']['part_sender'],
			'status.receiver'    => $this->session->userdata('no'),
			'status.status'      => $this->PAGE_CONFIG['status'],
			'status.created >'   => $this->PAGE_CONFIG['set_page']=='a'  ? date('Y-m-d') : '',
			'status.created <'   => $this->PAGE_CONFIG['set_page']=='ao' ? date('Y-m-d') : ''
		);
		
		$option['like'] = array(
			'sss.sender_name' => $this->PAGE_CONFIG['params']['name_sender'],
			'approved.title'  => $this->PAGE_CONFIG['params']['title'],
		);
		
		$array_sender = search_node($this->PAGE_CONFIG['params']['part_sender'],'children');
		$array_menu   = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
		
		$option['where_in'] = array(
			'status.part_sender' => $array_sender,
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
		$offset   = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->approved_model->approved_send_list($option,null,null,'count');
		$data['list']          = $this->approved_model->approved_send_list($option,PAGING_PER_PAGE,$offset);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['anchor_url']    = site_url('approved_receive/write/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('approved_receive/proc/' .$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page']);
		$data['excel_url']     = site_url('approved_receive/excel/'.$this->PAGE_CONFIG['set_page'].$this->PAGE_CONFIG['params_string']);

		$config['base_url']    = site_url('approved_receive/lists/'.$this->PAGE_CONFIG['set_page']);
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('approved/list_receive_v',$data);
	}
	
	public function excel(){
		$option = $this->getListOption();
	
		$data['total'] = $this->approved_model->approved_send_list($option,null,null,'count');
		$data['list']  = $this->approved_model->approved_send_list($option,$data['total'],0);
	
		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();
	
		$objPHPExcel->getProperties()->setCreator("groupware");
		$objPHPExcel->getProperties()->setLastModifiedBy("groupware");
		$objPHPExcel->getProperties()->setTitle("받은 결재");
		$objPHPExcel->setActiveSheetIndex(0);
	
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
		foreach (range('A', 'G') as $column){
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
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '진행기간');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '결재');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '누락');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '등록일자');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '담당자');
	
		$row = 2;
		foreach ( $data['list'] as $lt ) {
			$menu = search_node($lt['menu_no'],'parent');
	
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $menu['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $lt['title']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $lt['sData'].' ~ '.$lt['eData']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, ($lt['kind']=='0' ? '+'.$lt['pPoint'] : ''));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, ($lt['kind']=='0' ? '-'.$lt['mPoint'] : ''));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $lt['created']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $lt['user_name']);
			$row ++;
		}
	
		$filename = '받은 결재_' . date('Y년 m월 d일 H시 i분 s초', time()) . '.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'approved.no'     => $no,
			'status.receiver' => $this->session->userdata('no'),
			'status.status'   => $this->PAGE_CONFIG['status']
		);
		
		$data['data'] = $this->approved_model->approved_send_detail($option);
		
		if( !$data['data']['no'] ){
			alert('잘못된 접근입니다.');
		}
		
		$data['action_type']   = 'edit';
		$data['app_type']      = 'receive';
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']    = site_url('approved_receive/proc/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page']); // 폼 action
		$data['list_url']      = site_url('approved_receive/lists/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['fg_btn_send']   = false;
		$data['fg_btn_receiv'] = ($data['data']['status']=='b' && $data['data']['status_created'] > date("Y-m-d", strtotime(date('Y-m-d')."-3 day")) ? true : false);

		/* 결재자들 */
		$option = array(
			'approved_no' => $data['data']['no']
		);
		$data['approved_list'] = $this->approved_model->get_approved_staff_list($option);
		
		/* 내용들 */
		$option = array(
			'approved_no' => $data['data']['no']
		);
		$data['contents_list'] = $this->approved_model->get_approved_contents_list($option);

		if( $data['data']['kind'] == '0' ){
			$this->load->view('approved/view_project_v',$data);
		}else{
			$this->load->view('approved/view_document_v',$data);
		}
	}

	public function proc(){
		$this->load->library('form_validation');
		$action_type = $this->input->post('action_type');
		$no          = $this->input->post('no');
		$status      = $this->input->post('status');
		$contents    = $this->input->post('contents');
		$parameters  = urldecode($this->input->post('parameters'));
		
		if( $action_type == 'receive' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('no','no','required');
			$this->form_validation->set_rules('status','결재 상태','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			// 히스토리 , 날짜등 업데이트 여부 확인
			// 결재 완료/반려
			$option = array(
				'status' => $status
			);
			$where = array(
				'approved_no' =>$no,
				'receiver'    =>$this->session->userdata('no')
			);
			$this->approved_model->set_approved_staff_update($option,$where);
			
			if( $status == 'c' ){
				// 결재 신청
				$option = array(
					'status' => 'a'
				);
				$where = array(
					'approved_no' =>$no,
					'sender'      =>$this->session->userdata('no')
				);
				$this->approved_model->set_approved_staff_update($option,$where);
			}

			alert('결재 되었습니다.', site_url('approved_receive/lists/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}elseif ( $action_type == 'edit' ){
			if($contents){
				$option = array(
					'approved_no' =>$no,
					'user_no'     =>$this->session->userdata('no'),
					'contents'    =>$contents
				);
				$result = $this->approved_model->set_approved_contents_insert($option);
			}
			alert('수정 되었습니다.', site_url('approved_receive/write/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file approved_receive.php */
/* Location: ./controllers/approved_receive.php */