<?
class Board_setting extends CI_Controller{
	private $PAGE_CONFIG;

	public function __construct() {
       parent::__construct();
	   
	   $this->load->model('board_model');

		//현재 페이지 
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment(3,1);
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array();
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
				set_cookie('left_menu_open_cookie',site_url('board_setting/lists'),'0');
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
		$option = array();
		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$get_board = $this->board_model->get_setting_list($option,PAGING_PER_PAGE,$offset);

		$data['total']         = $get_board['total'];
		$data['list']          = $get_board['list'];
		$data['anchor_url']    = site_url('board_setting/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('board_setting/write/'.$this->PAGE_CONFIG['params_string']);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('board_setting/proc/'.$this->PAGE_CONFIG['cur_page']);
		
		$config['base_url']    = site_url('board_setting/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('board/setting/list_v',$data);
	}

	public function write(){
		$no = $this->input->get('no');
		$option = array(
			'no'=>$no
		);

		$result = $this->board_model->get_setting_detail($option);

		$data['action_type'] = 'create';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']  = site_url('board_setting/proc/'.$this->PAGE_CONFIG['cur_page']);

		$data['data'] = array(
			'no'         => '',
			'code'       => '',
			'type'       => 'default',
			'name'       => '',
			'activated'  => '0',
			'permission' => '',
			'reply'      => '1',
			'comment'    => '1',
			'order'      => '0',
		);
		if ($result->num_rows() > 0){
			$result = $result->row();

			$data['action_type'] = 'edit';
			$data['data'] = array(
				'no'         => $result->no,
				'code'       => $result->code,
				'type'       => $result->type,
				'name'       => $result->name,
				'activated'  => $result->activated,
				'permission' => $result->permission,
				'reply'      => $result->reply,
				'comment'    => $result->comment,
				'order'      => $result->order,
			);
		}
		$data['list_url']  = site_url('board_setting/lists/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$this->load->view('board/setting/write_v',$data);
	}

	public function proc(){
		$this->load->library('form_validation');

		$action_type   = $this->input->post('action_type');
		$board_no      = $this->input->post('board_no');
		$board_code    = $this->input->post('board_code');
		$board_type    = $this->input->post('board_type');
		$board_name    = $this->input->post('board_name');
		$board_reply   = $this->input->post('board_reply')=='on'?0:1;
		$board_comment = $this->input->post('board_comment')=='on'?0:1;
		$board_order   = $this->input->post('board_order');
		$parameters   = urldecode($this->input->post('parameters'));
		
		if( $action_type == 'create' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('board_code','게시판 코드','required|max_length[20]|alpha_numeric|is_unique[sw_board_list.code]');
			$this->form_validation->set_rules('board_name','게시판 이름','required|max_length[20]');
			$this->form_validation->set_rules('board_order','게시판 순서','required|numeric');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			$option = array(
				'code'      =>$board_code,
				'type'      =>$board_type,
				'name'      =>$board_name,
				'activated' =>0,
				'reply'     =>$board_reply,
				'comment'   =>$board_comment,
				'order'     =>$board_order
			);
			$result = $this->board_model->set_setting_insert($option);
			alert('등록되었습니다.', site_url('board_setting/lists/') );

		}elseif( $action_type == 'edit' ){
			
			$this->form_validation->set_rules('board_no','게시판 no','required|is_natural_no_zero');
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('board_code','게시판 코드','required|max_length[20]|alpha_numeric');
			$this->form_validation->set_rules('board_name','게시판 이름','required|max_length[20]');
			$this->form_validation->set_rules('board_order','게시판 순서','required|numeric');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$option = array(
				'type'      =>$board_type,
				'name'      =>$board_name,
				'reply'     =>$board_reply,
				'comment'   =>$board_comment,
				'order'     =>$board_order
			);
			$this->board_model->set_setting_update($option,array('no'=>$board_no));

			alert('수정되었습니다.', site_url('board_setting/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$board_no) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('board_no','게시판 no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$set_no = is_array($board_no) ? implode(',',$board_no):$board_no;
			$option = array(
				'activated'=>1
			);
			$this->board_model->set_setting_update($option,'no in('.$set_no.')');
			
			alert('삭제되었습니다.', site_url('board_setting/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file board_setting.php */
/* Location: ./controllers/board_setting.php */