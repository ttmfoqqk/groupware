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
			'no'           => !$this->input->get('no')           ? '' : $this->input->get('no')           ,
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
		//검색 파라미터
		$eData  = $this->PAGE_CONFIG['params']['eData'];
		$eData  = !$eData ? '' : date("Y-m-d", strtotime($eData."+1 day"));
		$ewData = $this->PAGE_CONFIG['params']['ewData'];
		$ewData = !$ewData ? '' : date("Y-m-d", strtotime($ewData."+1 day"));

		$option['where'] = array(
			'sw_project.sData >='   => $this->PAGE_CONFIG['params']['sData'],
			'sw_project.eData <'    => $eData,
			'sw_project.created >=' => $this->PAGE_CONFIG['params']['swData'],
			'sw_project.created <'  => $ewData,
			'menu_part_no'          => $this->PAGE_CONFIG['params']['menu_part_no'],
			'menu_no'               => $this->PAGE_CONFIG['params']['menu_no']
		);
		$option['like'] = array(
			'c.name' => $this->PAGE_CONFIG['params']['userName'],
			'title'  => $this->PAGE_CONFIG['params']['title']
		);

		$offset   = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		$get_data = $this->approved_model->get_approved_list($option,PAGING_PER_PAGE,$offset);

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
}
/* End of file approved_archive.php */
/* Location: ./controllers/approved_archive.php */