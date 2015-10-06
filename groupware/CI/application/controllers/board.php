<?
class Board extends CI_Controller{
	private $board;
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('board_model');
		
		$this->PAGE_CONFIG['segment']  = 4;
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment($this->PAGE_CONFIG['segment'],1);
		$this->PAGE_CONFIG['params']   = array(
			'sData'     => !$this->input->get('sData')     ? '' : $this->input->get('sData')    ,
			'eData'     => !$this->input->get('eData')     ? '' : $this->input->get('eData')    ,
			'subject'   => !$this->input->get('subject')   ? '' : $this->input->get('subject')  ,
			'user_name' => !$this->input->get('user_name') ? '' : $this->input->get('user_name')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);

		$option['where'] = array(
			'code' => $this->uri->segment(3) ,
			'activated' => 0
		);
		$this->board = $this->board_model->get_setting_detail($option);
		
		if( !$this->board['no'] ){
			alert('잘못된 게시판 코드 입니다.');
		}
		define('BOARD_TITLE'  , $this->board['name']);
		define('BOARD_FORM'   , site_url('board/proc/'.$this->board['code']) );
	}

	public function _remap($method){
		login_check();
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				set_cookie('left_menu_open_cookie',site_url('board/lists/'.$this->board['code']),'0');
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
		$option['where'] = array(
			'date_format(created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['sData'],
			'date_format(created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['eData']
		);
		$option['like'] = array(
			'subject'   => $this->PAGE_CONFIG['params']['subject'],
			'user_name' => $this->PAGE_CONFIG['params']['user_name']
		);

		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;
		
		$data['board_name']    = $this->board['name'];
		$data['total']         = $this->board_model->get_board_list($option,$this->board['code'],null,null,'count');
		$data['notice']        = $this->board_model->get_board_list($option,$this->board['code'],null,null,'notice');
		$data['list']          = $this->board_model->get_board_list($option,$this->board['code'],PAGING_PER_PAGE,$offset);
		
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['anchor_url']    = site_url('board/view/' .$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('board/write/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page']);
		$data['action_url']    = site_url('board/proc/' .$this->board['code']);

		// pagination option
		$config['base_url']    = site_url('board/lists/'.$this->board['code']);
		$config['total_rows']  = $data['total'];
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination']    = $this->pagination->create_links();

		$this->load->view('board/default/list_v',$data);
	}

	/*
	 * 상세내용
	 */
	private function views($setVla=array(),$mode=null){
		$no = !$this->input->get('no') ? 0 : $this->input->get('no');
		$option['where'] = array(
			'no'=>$no,
			'code'=>$this->board['code']
		);
		$data['data']  = $this->board_model->get_board_detail($option,$setVla,$mode);
		
		$option['where'] = array(
			'parent_no'=>$no,
			'code'=>$this->board['code']
		);
		$data['files'] = $this->board_model->get_board_file_list($option);

		return $data;
	}

	public function view(){
		$data = $this->views(null,'view');
		if( !$data['data']['no'] ){
			alert('잘못된 접근입니다.');
		}
		
		$data['parameters'] = urlencode($this->PAGE_CONFIG['params_string']);
		$data['list_url']   = site_url('board/lists/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['edit_url']   = site_url('board/edit/' .$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string'].'&no='.$data['data']['no']);
		$data['reply_url']  = site_url('board/reply/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string'].'&no='.$data['data']['no']);
		$data['reply_fg']   = $this->board['reply'];

		$this->load->view('board/default/view_v',$data);
	}

	public function write(){
		$setVla = array(
			'no'          => 0,
			'original_no' => 0,
			'depth'       => 0,
			'order'       => 0,
			'user_no'     => 0,
			'count_hit'   => 0,
			'is_notice'   => 1
		);
		$data = $this->views($setVla);

		$data['action_type'] = 'create';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']);
		$data['list_url']    = site_url('board/lists/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$this->load->view('board/default/write_v',$data);
	}

	public function edit(){
		$data = $this->views();
		$data['action_type'] = 'edit';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']);
		$data['list_url']    = site_url('board/lists/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$this->load->view('board/default/write_v',$data);
	}

	public function reply(){
		$data = $this->views();
		$data['data']['subject'] = '답변: '.$data['data']['subject'];
		$data['data']['contents'] = $data['data']['contents'];
		
		$data['action_type'] = 'reply';
		$data['parameters']  = urlencode($this->PAGE_CONFIG['params_string']);
		$data['list_url']    = site_url('board/lists/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$this->load->view('board/default/write_v',$data);
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
		$oldFile     = $this->input->post('oldFile');

		$parameters  = urldecode($this->input->post('parameters'));

		if( $action_type == 'create' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('subject','제목','required|max_length[200]');
			$this->form_validation->set_rules('contents','내용','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$file_insert_fg = false;
			if( $_FILES['userfile']['name'] ) {
				$config['upload_path']   = 'upload/board/';
				$config['allowed_types'] = FILE_ALL_TYPE;
				$config['encrypt_name']  = true;

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
				'code'          =>$this->board['code'],
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
					'code'          => $this->board['code'],
					'parent_no'     => $result ,
					'original_name' => $upload_data['orig_name'],
					'upload_name'   => $upload_data['file_name']
				);
				$this->board_model->set_board_file_insert($option_filse);
			}

			alert('등록되었습니다.', site_url('board/lists/'.$this->board['code']) );

		}elseif( $action_type == 'edit' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('subject','제목','required|max_length[200]');
			$this->form_validation->set_rules('contents','내용','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$file_insert_fg = false;
			if( $_FILES['userfile']['name'] ) {
				$config['upload_path']   = 'upload/board/';
				$config['allowed_types'] = FILE_ALL_TYPE;
				$config['encrypt_name']  = true;
			
				$this->load->library('upload', $config);
			
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_insert_fg = true;
					
					if( $oldFile ){
						unlink($config['upload_path'].$oldFile);
						$this->board_model->set_board_file_delete(array('code'=>$this->board['code'],'parent_no'=>$contents_no));
					}
				}
			}
			if( $file_insert_fg ){
				$option_filse = array(
						'code'          => $this->board['code'],
						'parent_no'     => $contents_no ,
						'original_name' => $upload_data['orig_name'],
						'upload_name'   => $upload_data['file_name']
				);
				$this->board_model->set_board_file_insert($option_filse);
			}

			$option = array(
				'subject'   =>$subject,
				'contents'  =>$contents,
				'is_notice' =>$is_notice
			);
			$where = array(
				'no'=>$contents_no,
				'code'=>$this->board['code']
			);
			$this->board_model->set_board_update($option,$where);

			alert('수정되었습니다.', site_url('board/view/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$parameters.'?no='.$contents_no ) );

		}elseif( $action_type == 'reply' ){
			if($this->board['reply'] == 1)alert('답글이 제한된 게시판입니다.');

			$this->form_validation->set_rules('contents_no','게시판 no','required');
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('subject','제목','required|max_length[200]');
			$this->form_validation->set_rules('contents','내용','required');

			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$file_insert_fg = false;
			if( $_FILES['userfile']['name'] ) {
				$config['upload_path']   = 'upload/board/';
				$config['allowed_types'] = FILE_ALL_TYPE;
				$config['encrypt_name']  = true;
			
				$this->load->library('upload', $config);
			
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_insert_fg = true;
				}
			}
			
			// 원본글 no,depth
			// 원본글 no =  parent_no update order-1;

			$sql = "update `sw_board_contents` set `order`=`order`+1 where original_no = ".$original_no." and `order` >= " . ($order+1) . " ";
			$this->db->query($sql);

			$option = array(
				'code'          =>$this->board['code'],
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
			
			if( $file_insert_fg ){
				$option_filse = array(
						'code'          => $this->board['code'],
						'parent_no'     => $result ,
						'original_name' => $upload_data['orig_name'],
						'upload_name'   => $upload_data['file_name']
				);
				$this->board_model->set_board_file_insert($option_filse);
			}
			
			
			alert('등록되었습니다.', site_url('board/lists/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$parameters ) );
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
			$this->board_model->set_board_update($option,'code = "'.$this->board['code'].'" and no in('.$set_no.')');
			
			alert('삭제되었습니다.', site_url('board/lists/'.$this->board['code']) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file Board.php */
/* Location: ./controllers/Board.php */