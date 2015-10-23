<?
class Information extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('information_model');
		
		$this->PAGE_CONFIG['segment']  = 4;
		$this->PAGE_CONFIG['set_page'] = $this->uri->segment(3,'company');
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment($this->PAGE_CONFIG['segment'],1);
		$this->PAGE_CONFIG['params']   = array(
			'sData'     => !$this->input->get('sData')     ? '' : $this->input->get('sData')     ,
			'eData'     => !$this->input->get('eData')     ? '' : $this->input->get('eData')     ,
			'gubun'     => !$this->input->get('gubun')     ? '' : $this->input->get('gubun')     ,
			'bizName'   => !$this->input->get('bizName')   ? '' : $this->input->get('bizName')   ,
			'bizNumber' => !$this->input->get('bizNumber') ? '' : $this->input->get('bizNumber') ,
			'phone'     => !$this->input->get('phone')     ? '' : $this->input->get('phone')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
		
		$page_title = '';
		switch ($this->PAGE_CONFIG['set_page']) {
			case 'company':
				$page_title = "회사정보";
				break;
			case 'partner':
				$page_title = "거래처 정보";
				break;
			case 'develop':
				$page_title = "고객사 정보 [develop]";
				break;
			case 'marketing':
				$page_title = "고객사 정보 [marketing]";
				break;
		}

		define('PAGE_TITLE' , $page_title);
		define('PAGE_FORM'  , site_url('information/proc/'.$this->PAGE_CONFIG['set_page']) );
	}

	public function _remap($method){
		login_check();
		if( $method == 'write' or $method == 'proc' ){
			permission_check('info-'.$this->PAGE_CONFIG['set_page'],'W');
		}else{
			permission_check('info-'.$this->PAGE_CONFIG['set_page'],'R');
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
					set_cookie('left_menu_open_cookie',site_url('information/lists/'.$this->PAGE_CONFIG['set_page'] ),'0');
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
				'category' => $this->PAGE_CONFIG['set_page']
		);
		$option['like'] = array(
				'gubun'     => $this->PAGE_CONFIG['params']['gubun'],
				'bizName'   => $this->PAGE_CONFIG['params']['bizName'],
				'bizNumber' => $this->PAGE_CONFIG['params']['bizNumber'],
				'phone'     => $this->PAGE_CONFIG['params']['phone']
		);
		return $option;
	}
	
	

	public function index(){
		$this->lists();
	}

	public function lists(){
		$option = $this->getListOption();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		
		$data['total']         = $this->information_model->get_list($option,null,null,'count');
		$data['list']          = $this->information_model->get_list($option,PAGING_PER_PAGE,$offset);
		
		
		$data['action_type']   = 'delete';
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['anchor_url']    = site_url('information/write/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('information/write/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page']);
		
		$data['action_url']    = site_url('information/lists/'.$this->PAGE_CONFIG['set_page']);
		
		$data['excel_url']     = site_url('information/excel/'.$this->PAGE_CONFIG['set_page'].$this->PAGE_CONFIG['params_string']);

		// pagination option
		$config['base_url']    = site_url('information/lists/'.$this->PAGE_CONFIG['set_page']);
		$config['total_rows']  = $data['total'];
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination']    = $this->pagination->create_links();

		$this->load->view('information/list_v',$data);
	}
	
	public function excel(){
		$this->load->library('Excel');
		$excel = new Excel();
		$option = $this->getListOption();
		
		$data['total'] = $this->information_model->get_list($option,null,null,'count');
		$data['list']  = $this->information_model->get_list($option,$data['total'],0);

		$title = PAGE_TITLE;
		$labels = array(
			'A' => '구분',
			'B' => '상호명',
			'C' => '사업자번호',
			'D' => '전화번호',
			'E' => '팩스번호',
			'F' => '등록일자'
		);
		
		$values=array();

		foreach ( $data['list'] as $lt ) {
			$item = array(
				'A' => $lt['gubun'],
				'B' => $lt['bizName'],
				'C' => $lt['bizNumber'],
				'D' => $lt['phone'],
				'E' => $lt['fax'],
				'F' => $lt['created']
			);
			array_push($values, $item);
		}
		
		$excel->printExcel($title,$labels,$values);
	}

	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'no'=>$no,
			'category'=>$this->PAGE_CONFIG['set_page']
		);

		$data['data']  = $this->information_model->get_detail($option);
		
		if( !$data['data']['no'] ){
			$data['action_type'] = 'create';
		}else{
			$data['action_type'] = 'edit';
		}
		
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']);
		$data['list_url']    = site_url('information/lists/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$this->load->view('information/write_v',$data);
	}

	public function proc(){
		$this->load->library('form_validation');

		$action_type  = $this->input->post('action_type');
		$no           = $this->input->post('no');
		$category     = $this->PAGE_CONFIG['set_page'];
		$bizName      = $this->input->post('bizName');
		$ceoName      = $this->input->post('ceoName');
		$gubun        = $this->input->post('gubun');
		$bizType      = $this->input->post('bizType');
		$bizCondition = $this->input->post('bizCondition');
		$addr         = $this->input->post('addr');
		$phone        = $this->input->post('phone');
		$fax          = $this->input->post('fax');
		$bigo         = $this->input->post('bigo');
		$order        = $this->input->post('order')=='' ? 0 : $this->input->post('order');
		$bizNumber    = $this->input->post('bizNumber');

		$parameters   = urldecode($this->input->post('parameters'));

		if( $action_type == 'create' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('bizName','상호명','required|max_length[20]');
			$this->form_validation->set_rules('gubun','구분','required|max_length[20]');
			$this->form_validation->set_rules('phone','전화번호','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			$option = array(
				'category'     => $category,
				'bizName'      => $bizName,
				'ceoName'      => $ceoName,
				'gubun'        => $gubun,
				'bizType'      => $bizType,
				'bizCondition' => $bizCondition,
				'addr'         => $addr,
				'phone'        => $phone,
				'fax'          => $fax,
				'bigo'         => $bigo,
				'order'        => $order,
				'bizNumber'    => $bizNumber,
			);
			$result = $this->information_model->set_insert($option);
			alert('등록되었습니다.', site_url('information/lists/'.$this->PAGE_CONFIG['set_page']) );

		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('bizName','상호명','required|max_length[20]');
			$this->form_validation->set_rules('gubun','구분','required|max_length[20]');
			$this->form_validation->set_rules('phone','전화번호','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			$values = array(
				'ceoName'      => $ceoName,
				'gubun'        => $gubun,
				'bizType'      => $bizType,
				'bizCondition' => $bizCondition,
				'addr'         => $addr,
				'phone'        => $phone,
				'fax'          => $fax,
				'bigo'         => $bigo,
				'order'        => $order,
				'bizNumber'    => $bizNumber,
			);
			$option['where'] = array(
				'no'       => $no,
				'category' => $this->PAGE_CONFIG['set_page']
			);
			$this->information_model->set_update($values,$option);

			alert('수정되었습니다.', site_url('information/write/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$no ) );

		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no','no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$option['where_in'] = array(
				'no' => $no
			);
			$this->information_model->set_delete($option);
			
			unset($option);
			$option['where_in'] = array(
				'information_no' => $no
			);
			$this->information_model->set_staff_delete($option);
			$this->information_model->set_site_delete($option);
			
			alert('삭제되었습니다.', site_url('information/lists/'.$this->PAGE_CONFIG['set_page']) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
	
	
	/* 담당자 */
	public function _staff_lists(){
		$no = $this->input->post('no');
		$option['where'] = array(
			'information_no' => $no
		);
		$result = $this->information_model->get_staff_list($option);
		echo json_encode($result);
	}
	
	public function _staff_insert(){
		$no = $this->input->post('no');
		$json_data  = json_decode($this->input->post('json_data'));
	
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$values = array();
			$i = 1;
			foreach($json_data as $key) {
				array_push($values,array(
					'information_no' => $no,
					'name'     => $key->name,
					'part'     => $key->part,
					'position' => $key->position,
					'phone'    => $key->phone,
					'ext'      => $key->ext,
					'email'    => $key->email,
					'order'    => (is_numeric($key->order) ? $key->order : 0)
				));
				$i++;
			}
			$option['where'] = array(
					'information_no' => $no
			);
			$result = $this->information_model->set_staff_insert($values,$option);
			$return = array(
					'result' => 'ok',
					'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}
	
	
	/* 사이트 */
	public function _site_lists(){
		$no = $this->input->post('no');
		$option['where'] = array(
			'information_no'=>$no
		);
		$result = $this->information_model->get_site_list($option);
		echo json_encode($result);
	}
	
	public function _site_insert(){
		$no = $this->input->post('no');
		$json_data  = json_decode($this->input->post('json_data'));
	
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$values = array();
			$i = 1;
			foreach($json_data as $key) {
				array_push($values,array(
					'information_no' => $no,
					'url'            => $key->url,
					'id'             => $key->id,
					'pwd'            => $key->pwd,
					'bigo'           => $key->bigo,
					'order'          => (is_numeric($key->order) ? $key->order : 0)
				));
				$i++;
			}
			$option['where'] = array(
				'information_no' => $no
			);
			$result = $this->information_model->set_site_insert($values,$option);
			$return = array(
					'result' => 'ok',
					'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}
}
/* End of file Information.php */
/* Location: ./controllers/Information.php */