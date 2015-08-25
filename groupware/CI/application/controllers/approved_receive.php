<?
class Approved_receive extends CI_Controller{
	private $PAGE_CONFIG;

	public function __construct() {
		parent::__construct();
		
		$this->load->model('approved_model');

		// page segment 위치
		$this->PAGE_CONFIG['uri_segment'] = 4;
		//현재 페이지 검색모드 all default
		$this->PAGE_CONFIG['set_page']    = $this->uri->segment(3,'all');
		//현재 페이지 
		$this->PAGE_CONFIG['cur_page']    = $this->uri->segment($this->PAGE_CONFIG['uri_segment'],1);
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array(
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
				set_cookie('left_menu_open_cookie',site_url('approved_receive/lists/'.$this->PAGE_CONFIG['set_page']),'0');
				
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

		$status = '';
		switch( $this->PAGE_CONFIG['set_page'] ) {
			case "all" :
				$status = '';
				break;
			case "ao" :
				$status = 'a';
				// or created ? 결재를 미룬것 추가하기
				break;
			default :
				$status = $this->PAGE_CONFIG['set_page'];
		}

		$option['where'] = array(
			'approved.sData <='   => $this->PAGE_CONFIG['params']['sData'],
			'approved.eData >='   => $eData,
			'approved.created >=' => $this->PAGE_CONFIG['params']['swData'],
			'approved.created <'  => $ewData,
			'approved.menu_no'    => $this->PAGE_CONFIG['params']['menu_no'],
			'approved.no'         => $this->PAGE_CONFIG['params']['doc_no'],
			'status.part_sender'  => $this->PAGE_CONFIG['params']['part_sender'],
			'status.receiver'     => $this->session->userdata('no'),
			'status.status'       => $status
		);
		
		$option['cus_where'] = "status.approved_no is not null ";

		$option['like'] = array(
			'user_sender.name'    => $this->PAGE_CONFIG['params']['name_sender'],
			'approved.title'      => $this->PAGE_CONFIG['params']['title'],
		);

		$offset   = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		$get_data = $this->approved_model->get_approved_list($option,PAGING_PER_PAGE,$offset);

		$data['total']         = $get_data['total'];   // 전체글수
		$data['list']          = $get_data['list'];    // 글목록
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['anchor_url']    = site_url('approved_receive/write/'.$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page'].$data['parameters']);
		$data['action_url']    = site_url('approved_receive/proc/' .$this->PAGE_CONFIG['set_page'].'/'.$this->PAGE_CONFIG['cur_page']);

		$config['base_url']    = site_url('approved_receive/lists/'.$this->PAGE_CONFIG['set_page']);
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['uri_segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('approved/list_receive_v',$data);
	}
}
/* End of file approved_receive.php */
/* Location: ./controllers/approved_receive.php */