<?
class Board extends CI_Controller{
	public $board;
	public function __construct() {
		parent::__construct();
		$this->load->model('board_model');

		$option = array(
			'code' => $this->uri->segment(3) ,
			'activated' => 0
		);
		$result = $this->board_model->get_setting_detail($option);
		$board  = $result->row_array();

		if( !isset($board['no']) ){
			alert('잘못된 게시판 코드 입니다.');
		}
		define('BOARD_CODE', $board['code']);
		define('BOARD_TITLE', $board['name']);
		define('BOARD_REPLY', $board['reply']);
		define('BOARD_COMMENT', $board['comment']);
		set_cookie('left_menu_open_cookie',site_url('board/lists/'.BOARD_CODE),'0');		
	}

	public function _remap($method){
		login_check();

		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
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
		$option = array(
			'is_delete'=>0,
			'code'=>BOARD_CODE
		);
		$option_notice = array(
			'is_delete'=>0,
			'code'=>BOARD_CODE,
			'is_notice'=>0
		);
		$data['list'] = $this->board_model->get_board_list($option);
		$data['notice'] = $this->board_model->get_board_list($option_notice);
		$data['action_url'] = site_url('board/proc/'.BOARD_CODE);
		$data['action_type'] = 'delete';

		/*
			http://codeigniter-kr.org/user_guide_2.1.0/libraries/pagination.html
		*/
		$config['base_url'] = site_url('board/lists/'.BOARD_CODE);
		$config['total_rows'] = 200; // 전체 글갯수
		$config['per_page'] = 10;  // 보여질 갯수
		$config['uri_segment'] = 4;
		$config['num_links'] = 4; // 선택 페이지 좌우 링크 갯수
		

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';


		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li class="first">';
		$config['first_tag_close'] = '</li>';


		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li class="last">';
		$config['last_tag_close'] = '</li>';

		$config['prev_link'] = '<i class="fa fa-angle-left"></i>';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';

		$config['next_link'] = '<i class="fa fa-angle-right"></i>';
		$config['next_tag_open'] = '<li class="next">';
		$config['next_tag_close'] = '</li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="disabled"><a href="#" class="btn btn-primary disabled">';
		$config['cur_tag_close'] = '</a></li>';



		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('board/default/list_v',$data);
	}

	public function view(){
		$get_no = $page_method = $this->uri->segment(4);

		$option = array(
			'no'=>$get_no,
			'code'=>BOARD_CODE
		);
		$result = $this->board_model->get_board_detail($option,'view');
		
		if ($result->num_rows() <= 0){
			alert('잘못된 접근입니다.');
		}

		$result = $result->row();
		$data['data'] = array(
			'no' => $result->no,
			'user_no' => $result->user_no,
			'user_id' => $result->user_id,
			'user_name' => $result->user_name,
			'subject' => $result->subject,
			'contents' => nl2br($result->contents),
			'count_hit' => $result->count_hit,
			'ip' => $result->ip,
			'created' => $result->created
		);

		$data['action_url'] = site_url('board/proc/'.BOARD_CODE);
		$data['action_type'] = 'delete';
		$data['edit_url'] = site_url('board/edit/'.BOARD_CODE.'/'.$get_no);
		$data['reply_url'] = site_url('board/reply/'.BOARD_CODE.'/'.$get_no);

		$this->load->view('board/default/view_v',$data);
	}

	public function write(){
		$data['action_url'] = site_url('board/proc/'.BOARD_CODE);
		$data['action_type'] = 'create';

		$this->load->view('board/default/write_v',$data);
	}

	public function edit(){
		$get_no = $page_method = $this->uri->segment(4);
		$option = array(
			'no'=>$get_no,
			'code'=>BOARD_CODE
		);
		$result = $this->board_model->get_board_detail($option,'edit');
		
		if ($result->num_rows() <= 0){
			alert('잘못된 접근입니다.');
		}

		$result = $result->row();

		if($result->user_id == $this->session->userdata('user_id')){
			('잘못된 접근입니다.');
		}

		$data['data'] = array(
			'no' => $result->no,
			'user_no' => $result->user_no,
			'user_id' => $result->user_id,
			'user_name' => $result->user_name,
			'subject' => $result->subject,
			'contents' => nl2br($result->contents),
			'count_hit' => $result->count_hit,
			'ip' => $result->ip,
			'is_notice' => $result->is_notice,
			'created' => $result->created
		);

		$data['action_url'] = site_url('board/proc/'.BOARD_CODE);
		$data['action_type'] = 'edit';

		$this->load->view('board/default/edit_v',$data);
	}

	public function reply(){
		if(BOARD_REPLY == 1)alert('답글이 제한된 게시판입니다.');

		$get_no = $page_method = $this->uri->segment(4);
		$option = array(
			'no'=>$get_no,
			'code'=>BOARD_CODE
		);
		$result = $this->board_model->get_board_detail($option,'edit');
		
		if ($result->num_rows() <= 0){
			alert('잘못된 접근입니다.');
		}

		$result = $result->row();
		$data['data'] = array(
			'no'          => $result->no,
			'original_no' => $result->original_no,
			'depth'       => $result->depth,
			'order'       => $result->order,
			'user_no'     => $result->user_no,
			'user_id'     => $result->user_id,
			'user_name'   => $result->user_name,
			'subject'     => $result->subject,
			'contents'    => nl2br($result->contents),
			'count_hit'   => $result->count_hit,
			'ip'          => $result->ip,
			'created'     => $result->created
		);

		$data['action_url'] = site_url('board/proc/'.BOARD_CODE);
		$data['action_type'] = 'reply';

		$this->load->view('board/default/reply_v',$data);
	}

	public function proc(){
		$this->load->library('form_validation');

		$action_type = $this->input->post('action_type');
		$contents_no = $this->input->post('contents_no');
		$original_no = $this->input->post('original_no');
		$subject     = $this->input->post('subject');
		$contents    = $this->input->post('contents');
		$is_notice   = $this->input->post('is_notice')=='on'?0:1;
		$depth       = $this->input->post('depth')==''?0:$this->input->post('depth');
		$order       = $this->input->post('order')==''?0:$this->input->post('order');

		if( $action_type == 'create' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('subject','제목','required|max_length[200]');
			$this->form_validation->set_rules('contents','내용','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			$option = array(
				'code'          =>BOARD_CODE,
				'depth'         =>0,
				'order'         =>0,
				'user_no'       =>$this->session->userdata('user_no'),
				'user_id'       =>$this->session->userdata('user_id'),
				'user_name'     =>$this->session->userdata('user_name'),
				'subject'       =>$subject,
				'contents'      =>$contents,
				'is_notice'     =>$is_notice,
				'is_delete'     =>0,
				'count_hit'     =>0,
				'count_reply'   =>0,
				'count_comment' =>0,
				'ip'            =>$this->input->ip_address()
			);
			$result = $this->board_model->get_board_insert($option);
			$this->board_model->get_board_update(array('original_no'=>$result,'parent_no'=>$result),array('no'=>$result));

			alert('등록되었습니다.', site_url('board/lists/'.BOARD_CODE) );

		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('subject','제목','required|max_length[200]');
			$this->form_validation->set_rules('contents','내용','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			$option = array(
				'subject'   =>$subject,
				'contents'  =>$contents,
				'is_notice' =>$is_notice
			);
			$where = array(
				'no'=>$contents_no,
				'code'=>BOARD_CODE
			);
			$this->board_model->get_board_update($option,$where);

			alert('수정되었습니다.', site_url('board/view/'.BOARD_CODE.'/'.$contents_no) );

		}elseif( $action_type == 'reply' ){
			if(BOARD_REPLY == 1)alert('답글이 제한된 게시판입니다.');

			$this->form_validation->set_rules('contents_no','게시판 no','required');
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('subject','제목','required|max_length[200]');
			$this->form_validation->set_rules('contents','내용','required');

			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			// 원본글 no,depth
			// 원본글 no =  parent_no update order-1;

			$sql = "update `sw_board_contents` set `order`=`order`+1 where original_no = ".$original_no." and `order` >= " . ($order+1) . " ";
			$this->db->query($sql);

			$option = array(
				'code'          =>BOARD_CODE,
				'original_no'   =>$original_no,
				'parent_no'     =>$contents_no,
				'depth'         =>$depth+1,
				'order'         =>$order+1,
				'user_no'       =>$this->session->userdata('user_no'),
				'user_id'       =>$this->session->userdata('user_id'),
				'user_name'     =>$this->session->userdata('user_name'),
				'subject'       =>$subject,
				'contents'      =>$contents,
				'is_notice'     =>$is_notice,
				'is_delete'     =>0,
				'count_hit'     =>0,
				'count_reply'   =>0,
				'count_comment' =>0,
				'ip'            =>$this->input->ip_address()
			);
			$result = $this->board_model->get_board_insert($option);
			
			alert('등록되었습니다.', site_url('board/lists/'.BOARD_CODE) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('contents_no','게시판 no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			// 답변 체크 -> 삭제 방지
			$set_no = is_array($contents_no) ? implode(',',$contents_no):$contents_no;
			$option = array(
				'is_delete'=>1
			);
			$this->board_model->get_board_update($option,'code = "'.BOARD_CODE.'" and no in('.$set_no.')');
			
			alert('삭제되었습니다.', site_url('board/lists/'.BOARD_CODE) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file Board.php */
/* Location: ./controllers/Board.php */