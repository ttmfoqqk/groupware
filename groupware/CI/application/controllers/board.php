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
		define('BOARD_CODE'   , $board['code']);
		define('BOARD_TITLE'  , $board['name']);
		define('BOARD_REPLY'  , $board['reply']);
		define('BOARD_COMMENT', $board['comment']);
		define('BOARD_PAGE'   , $this->uri->segment(4,1) );
		define('BOARD_FORM'   , site_url('board/proc/'.BOARD_CODE) );

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
		// option search 추가
		$option = array(
			'where'=>array( 'code'=>BOARD_CODE ),
			'like'=>array( 'subject'=>'' )
		);

		$offset    = (PAGING_PER_PAGE * BOARD_PAGE) - PAGING_PER_PAGE;
		$get_board = $this->board_model->get_board_list($option,PAGING_PER_PAGE,$offset);
		
		$data['total']         = $get_board['total'];   // 전체글수
		$data['notice']        = $get_board['notice'];  // 공지
		$data['list']          = $get_board['list'];    // 글목록

		$data['parameters']    = ''; // parameters , search 추가시 수정
		$data['anchor_url']    = site_url('board/view/'.BOARD_CODE.'/'.BOARD_PAGE); // 글 링크
		$data['write_url']     = site_url('board/write/'.BOARD_CODE.'/'.BOARD_PAGE); // 쓰기버튼 링크

		// pagination option
		$config['base_url']    = site_url('board/lists/'.BOARD_CODE);
		$config['total_rows']  = $data['total'];
		$config['cur_page']    = BOARD_PAGE;
		$config['uri_segment'] = 4;

		$this->pagination->initialize($config);
		$data['pagination']    = $this->pagination->create_links();
		// pagination option

		$this->load->view('board/default/list_v',$data);
	}

	public function view(){
		$no = $this->input->get('no');

		$option = array(
			'no'=>$no,
			'code'=>BOARD_CODE
		);
		$result = $this->board_model->get_board_detail($option,'view');
		
		if ($result['data']->num_rows() <= 0){
			alert('잘못된 접근입니다.');
		}

		$result_data = $result['data']->row();
		$data['data'] = array(
			'no' => $result_data->no,
			'user_no' => $result_data->user_no,
			'user_id' => $result_data->user_id,
			'user_name' => $result_data->user_name,
			'subject' => $result_data->subject,
			'contents' => nl2br($result_data->contents),
			'count_hit' => $result_data->count_hit,
			'ip' => $result_data->ip,
			'created' => $result_data->created
		);
		$data['files']      = $result['files'];

		$data['parameters'] = '';
		$data['list_url']   = site_url('board/lists/'.BOARD_CODE.'/'.BOARD_PAGE.'?'.$data['parameters']);
		$data['edit_url']   = site_url('board/edit/'.BOARD_CODE.'/'.BOARD_PAGE.'?no='.$result_data->no.'&'.$data['parameters']);
		$data['reply_url']  = site_url('board/reply/'.BOARD_CODE.'/'.BOARD_PAGE.'?no='.$result_data->no.'&'.$data['parameters']);

		$this->load->view('board/default/view_v',$data);
	}

	public function write(){
		$data['parameters'] = '';
		$data['list_url']  = site_url('board/lists/'.BOARD_CODE.'/'.BOARD_PAGE.'?'.$data['parameters']);
		$this->load->view('board/default/write_v',$data);
	}

	public function edit(){
		$no = $this->input->get('no');
		$option = array(
			'no'=>$no,
			'code'=>BOARD_CODE
		);
		$result = $this->board_model->get_board_detail($option,'edit');
		
		if ($result['data']->num_rows() <= 0){
			alert('잘못된 접근입니다.');
		}

		$result_data = $result['data']->row();

		if($result_data->user_id == $this->session->userdata('id')){
			('잘못된 접근입니다.');
		}

		$data['data'] = array(
			'no' => $result_data->no,
			'user_no' => $result_data->user_no,
			'user_id' => $result_data->user_id,
			'user_name' => $result_data->user_name,
			'subject' => $result_data->subject,
			'contents' => $result_data->contents,
			'count_hit' => $result_data->count_hit,
			'ip' => $result_data->ip,
			'is_notice' => $result_data->is_notice,
			'created' => $result_data->created
		);
		$data['files']      = $result['files'];
		$data['parameters'] = '';
		$data['list_url']   = site_url('board/lists/'.BOARD_CODE.'/'.BOARD_PAGE.'?'.$data['parameters']);
		$this->load->view('board/default/edit_v',$data);
	}

	public function reply(){
		if(BOARD_REPLY == 1)alert('답글이 제한된 게시판입니다.');

		$no = $this->input->get('no');
		$option = array(
			'no'=>$no,
			'code'=>BOARD_CODE
		);
		$result = $this->board_model->get_board_detail($option,'edit');
		
		if ($result['data']->num_rows() <= 0){
			alert('잘못된 접근입니다.');
		}

		$result_data = $result['data']->row();
		$data['data'] = array(
			'no'          => $result_data->no,
			'original_no' => $result_data->original_no,
			'depth'       => $result_data->depth,
			'order'       => $result_data->order,
			'user_no'     => $result_data->user_no,
			'user_id'     => $result_data->user_id,
			'user_name'   => $result_data->user_name,
			'subject'     => $result_data->subject,
			'contents'    => $result_data->contents,
			'count_hit'   => $result_data->count_hit,
			'ip'          => $result_data->ip,
			'created'     => $result_data->created
		);
		$data['files']      = $result['files'];

		$data['parameters'] = '';
		$data['list_url']   = site_url('board/lists/'.BOARD_CODE.'/'.BOARD_PAGE.'?'.$data['parameters']);
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

		$parameters  = $this->input->post('parameters');

		if( $action_type == 'create' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('subject','제목','required|max_length[200]');
			$this->form_validation->set_rules('contents','내용','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$file_insert_fg = false;
			if( $_FILES['userfile']['name'] ) {
				$config['upload_path'] = 'upload/board/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['encrypt_name'] = true;				

				$this->load->library('upload', $config);
			
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_insert_fg = true;
				}
			}

			$option = array(
				'code'          =>BOARD_CODE,
				'depth'         =>0,
				'order'         =>0,
				'user_no'       =>$this->session->userdata('no'),
				'user_id'       =>$this->session->userdata('id'),
				'user_name'     =>$this->session->userdata('name'),
				'subject'       =>$subject,
				'contents'      =>$contents,
				'is_notice'     =>$is_notice,
				'is_delete'     =>0,
				'count_hit'     =>0,
				'count_reply'   =>0,
				'count_comment' =>0,
				'ip'            =>$this->input->ip_address()
			);
			$result = $this->board_model->set_board_insert($option);
			$this->board_model->set_board_update(array('original_no'=>$result,'parent_no'=>$result),array('no'=>$result));
			
			if( $file_insert_fg ){
				$option_filse = array(
					'code'          => BOARD_CODE,
					'parent_no'     => $result ,
					'original_name' => $upload_data['orig_name'],
					'upload_name'   => $upload_data['file_name']
				);
				$this->board_model->set_board_file_insert($option_filse);
			}

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
			$this->board_model->set_board_update($option,$where);

			alert('수정되었습니다.', site_url('board/view/'.BOARD_CODE.'/'.BOARD_PAGE.'?no='.$contents_no .'&'. $parameters ) );

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
				'user_no'       =>$this->session->userdata('no'),
				'user_id'       =>$this->session->userdata('id'),
				'user_name'     =>$this->session->userdata('name'),
				'subject'       =>$subject,
				'contents'      =>$contents,
				'is_notice'     =>$is_notice,
				'is_delete'     =>0,
				'count_hit'     =>0,
				'count_reply'   =>0,
				'count_comment' =>0,
				'ip'            =>$this->input->ip_address()
			);
			$result = $this->board_model->set_board_insert($option);
			alert('등록되었습니다.', site_url('board/lists/'.BOARD_CODE.'/'.BOARD_PAGE.'?'. $parameters ) );
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
			$this->board_model->set_board_update($option,'code = "'.BOARD_CODE.'" and no in('.$set_no.')');
			
			alert('삭제되었습니다.', site_url('board/lists/'.BOARD_CODE) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file Board.php */
/* Location: ./controllers/Board.php */