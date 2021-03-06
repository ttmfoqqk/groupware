<?
class Board extends CI_Controller{
	private $board;
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('common_model');
		
		$this->PAGE_CONFIG['tableName'] = 'sw_board_contents';
		$this->PAGE_CONFIG['tableName_file'] = 'sw_board_file';
		
		$this->PAGE_CONFIG['segment']  = 4;
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment($this->PAGE_CONFIG['segment'],1);
		$this->PAGE_CONFIG['params']   = array(
			'sData'     => !$this->input->get('sData')     ? '' : $this->input->get('sData')    ,
			'eData'     => !$this->input->get('eData')     ? '' : $this->input->get('eData')    ,
			'subject'   => !$this->input->get('subject')   ? '' : $this->input->get('subject')  ,
			'user_name' => !$this->input->get('user_name') ? '' : $this->input->get('user_name'),
			'menu_no'   => !$this->input->get('menu_no')   ? '' : $this->input->get('menu_no')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);

		$option['where'] = array(
			'code' => $this->uri->segment(3) ,
			'activated' => 0
		);
		$this->board = $this->common_model->detail('sw_board_list',NULL,$option);
		
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
		$option_notice['where'] = array(
				'code' => $this->board['code'] ,
				'is_delete' => '0',
				'is_notice' => '0'
		);
		
		$option['where'] = array(
			'code' => $this->board['code'] ,
			'is_delete' => '0',
			'date_format(created,"%Y-%m-%d") >=' => $this->PAGE_CONFIG['params']['sData'],
			'date_format(created,"%Y-%m-%d") <=' => $this->PAGE_CONFIG['params']['eData']
		);
		$option['like'] = array(
			'subject'   => $this->PAGE_CONFIG['params']['subject'],
			'user_name' => $this->PAGE_CONFIG['params']['user_name']
		);
		$array_menu = search_node($this->PAGE_CONFIG['params']['menu_no'],'children');
		$option['where_in'] = array(
			'menu_no' => $array_menu
		);
		
		$order = array(
			'original_no'=>'DESC',
			'order'=>'ASC'
		);

		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option,null,null,null,'count');
		$data['notice']        = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option_notice,null,null,$order);
		$data['list']          = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option,PAGING_PER_PAGE,$offset,$order);
		
		$data['board_name']    = $this->board['name'];
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']);
		$data['anchor_url']    = site_url('board/view/' .$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$this->PAGE_CONFIG['params_string']);
		$data['write_url']     = site_url('board/write/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page']);
		$data['action_url']    = site_url('board/lists/'.$this->board['code']);

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
			'no'   => $no,
			'code' => $this->board['code']
		);
		if($mode=='view'){
			$sql = "update `sw_board_contents` set count_hit=count_hit+1 where no = '".$no."' ";
			$this->db->query($sql);
		}
		$data['data'] = $this->common_model->detail($this->PAGE_CONFIG['tableName'],NULL,$option,$setVla);
		
		$option['where'] = array(
			'parent_no'=>$no,
			'code'=>$this->board['code']
		);
		$data['files'] = $this->common_model->lists($this->PAGE_CONFIG['tableName_file'],NULL,$option);

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
		$menu_no     = $this->input->post('menu_no');
		$parameters  = urldecode($this->input->post('parameters'));
		
		$config['upload_path']   = 'upload/board/';
		$config['allowed_types'] = FILE_ALL_TYPE;
		$config['encrypt_name']  = true;
		
		$this->load->library('upload', $config);

		if( $action_type == 'create' ){
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('subject','제목','required|max_length[200]');
			$this->form_validation->set_rules('contents','내용','required');
			
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			
			$file_insert_fg = false;
			if( $_FILES['userfile']['name'] ) {
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_insert_fg = true;
				}
			}

			$set = array(
				'code'          => $this->board['code'],
				'depth'         => 0,
				'order'         => 0,
				'user_no'       => $this->session->userdata('no'),
				'user_id'       => $this->session->userdata('id'),
				'user_name'     => $this->session->userdata('name'),
				'subject'       => $subject,
				'contents'      => $contents,
				'is_notice'     => $is_notice,
				'is_delete'     => 0,
				'count_hit'     => 0,
				'count_reply'   => 0,
				'count_comment' => 0,
				'ip'            => $this->input->ip_address(),
				'menu_no'       => $menu_no,
				'created'       => 'NOW()'
			);
			$result = $this->common_model->insert($this->PAGE_CONFIG['tableName'],$set);
			
			$set = array(
				'original_no' => $result,
				'parent_no'   => $result
			);
			$option['where'] = array('no'=>$result);
			$this->common_model->update($this->PAGE_CONFIG['tableName'],$set,$option);
			
			if( $file_insert_fg ){
				$set_filse = array(
					'code'          => $this->board['code'],
					'parent_no'     => $result ,
					'original_name' => $upload_data['orig_name'],
					'upload_name'   => $upload_data['file_name']
				);
				$this->common_model->insert($this->PAGE_CONFIG['tableName_file'],$set_filse);
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
				if ( !$this->upload->do_upload() ){
					$upload_error = $this->upload->display_errors('','') ;
					alert($upload_error);
				}else{
					$upload_data = $this->upload->data();
					$file_insert_fg = true;
					
					if( $oldFile ){
						unlink($config['upload_path'].$oldFile);
						$set = array('code'=>$this->board['code'],'parent_no'=>$contents_no);
						$this->common_model->delete($this->PAGE_CONFIG['tableName_file'],$set);
					}
				}
			}
			if( $file_insert_fg ){
				$set_filse = array(
					'code'          => $this->board['code'],
					'parent_no'     => $contents_no ,
					'original_name' => $upload_data['orig_name'],
					'upload_name'   => $upload_data['file_name']
				);
				$this->common_model->insert($this->PAGE_CONFIG['tableName_file'],$set_filse);
			}

			$set = array(
				'subject'   => $subject,
				'contents'  => $contents,
				'is_notice' => $is_notice,
				'menu_no'   => $menu_no
			);
			$option['where'] = array(
				'no'=>$contents_no,
				'code'=>$this->board['code']
			);
			$this->common_model->update($this->PAGE_CONFIG['tableName'],$set,$option);

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

			$set = array(
				'code'          => $this->board['code'],
				'original_no'   => $original_no,
				'parent_no'     => $contents_no,
				'depth'         => $depth+1,
				'order'         => $order+1,
				'user_no'       => $this->session->userdata('no'),
				'user_id'       => $this->session->userdata('id'),
				'user_name'     => $this->session->userdata('name'),
				'subject'       => $subject,
				'contents'      => $contents,
				'is_notice'     => $is_notice,
				'is_delete'     => 0,
				'count_hit'     => 0,
				'count_reply'   => 0,
				'count_comment' => 0,
				'ip'            => $this->input->ip_address(),
				'menu_no'       => $menu_no,
				'created'       => 'NOW()'
			);
			$result = $this->common_model->insert($this->PAGE_CONFIG['tableName'],$set);
			
			if( $file_insert_fg ){
				$set_filse = array(
					'code'          => $this->board['code'],
					'parent_no'     => $result ,
					'original_name' => $upload_data['orig_name'],
					'upload_name'   => $upload_data['file_name']
				);
				$this->common_model->insert($this->PAGE_CONFIG['tableName_file'],$set_filse);
			}

			alert('등록되었습니다.', site_url('board/lists/'.$this->board['code'].'/'.$this->PAGE_CONFIG['cur_page'].$parameters ) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('contents_no','게시판 no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			// 게시판 db삭제
			$option['where_in'] = array('no' => $contents_no);
			$this->common_model->delete($this->PAGE_CONFIG['tableName'],$option);
			
			// 게시판 파일 리스트
			$option['where_in'] = array('parent_no' => $contents_no);
			$list = $this->common_model->lists($this->PAGE_CONFIG['tableName_file'],array('upload_name'=>TRUE),$option);
			
			// 게시판 파일 삭제
			foreach( $list as $lt ){
				if($lt['upload_name'] != ''){
					if( is_file(realpath($config['upload_path']) . '/' . $lt['upload_name']) ){
						unlink(realpath($config['upload_path']) . '/' . $lt['upload_name']);
					}
				}
			}
			// 게시판 파일 db삭제
			$this->common_model->delete($this->PAGE_CONFIG['tableName_file'],$option);
			alert('삭제되었습니다.', site_url('board/lists/'.$this->board['code']) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file Board.php */
/* Location: ./controllers/Board.php */