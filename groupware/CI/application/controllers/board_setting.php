<?
class Board_setting extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('common_model');
		
		$this->PAGE_CONFIG['tableName'] = 'sw_board_list';
		$this->PAGE_CONFIG['segment']   = 3;
		$this->PAGE_CONFIG['cur_page']  = $this->uri->segment(3,1);
		$this->PAGE_CONFIG['params']    = array();
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();
		if( $method == 'write' or $method == 'proc' ){
			permission_check('board_setting','W');
		}else{
			permission_check('board_setting','R');
		}
		
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
		$order  = array(
			'order' => 'ASC',
			'no'    => 'DESC'
		);
		
		$data['total']         = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option,NULL,NULL,NULL,'count');
		$data['list']          = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option,PAGING_PER_PAGE,$offset,$order);
		
		$data['anchor_url']    = site_url('board_setting/write/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('board_setting/write/'.$this->PAGE_CONFIG['params_string']);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']    = site_url('board_setting/proc/'.$this->PAGE_CONFIG['cur_page']);
		
		$config['base_url']    = site_url('board_setting/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('board/setting/list_v',$data);
	}

	public function write(){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'no'=>$no
		);
		
		$setVla = array(
			'type'       => 'default',
			'activated'  => '0',
			'reply'      => '1',
			'comment'    => '1',
			'order'      => '0'
		);

		$data['data'] = $this->common_model->detail($this->PAGE_CONFIG['tableName'],NULL,$option,$setVla);
		
		if( !$data['data']['no'] ){
			$data['action_type'] = 'create';
		}else{
			$data['action_type'] = 'edit';
		}

		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']);
		$data['action_url']  = site_url('board_setting/proc/'.$this->PAGE_CONFIG['cur_page']);

		
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

			$set = array(
				'code'      => $board_code,
				'type'      => $board_type,
				'name'      => $board_name,
				'activated' => 0,
				'reply'     => $board_reply,
				'comment'   => $board_comment,
				'order'     => $board_order
			);
			$result = $this->common_model->insert($this->PAGE_CONFIG['tableName'],$set);
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
			
			$set = array(
				'type'      =>$board_type,
				'name'      =>$board_name,
				'reply'     =>$board_reply,
				'comment'   =>$board_comment,
				'order'     =>$board_order
			);
			$option['where'] = array('no'=>$board_no);
			$this->common_model->update($this->PAGE_CONFIG['tableName'],$set,$option);

			alert('수정되었습니다.', site_url('board_setting/write/'.$this->PAGE_CONFIG['cur_page'].$parameters.'&no='.$board_no) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('board_no','게시판 no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$option['where_in'] = array('no'=>$board_no);
			$list = $this->common_model->lists($this->PAGE_CONFIG['tableName'],array('code'=>'TRUE'),$option);
			
			$this->common_model->delete($this->PAGE_CONFIG['tableName'],$option);
			
			// 관련 게시글 삭제
			foreach($list as $lt){
				$option['where'] = array('code'=>$lt['code']);
				$this->common_model->delete('sw_board_contents',$option);
			}
			alert('삭제되었습니다.', site_url('board_setting/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file board_setting.php */
/* Location: ./controllers/board_setting.php */