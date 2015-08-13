<?
class Project extends CI_Controller{
	private $GLOBAL;

	public function __construct() {
		parent::__construct();
		login_check();

		$this->GLOBAL['cur_page'] = $this->uri->segment(3,1);
		set_cookie('left_menu_open_cookie',site_url('project/'),'0');
		$this->load->model('project_model');
    }

	public function _remap($method){
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
		$option   = array();
		$offset   = (PAGING_PER_PAGE * $this->GLOBAL['cur_page'])-PAGING_PER_PAGE;

		$get_data = $this->project_model->get_project_list($option,PAGING_PER_PAGE,$offset);

		$data['total']         = $get_data['total'];   // 전체글수
		$data['list']          = $get_data['list'];    // 글목록
		$data['anchor_url']    = site_url('project/write/'); // 글 링크
		
		
		$config['base_url']    = site_url('project/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->GLOBAL['cur_page'];
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('project/project_v',$data);
	}
	public function write(){
		$no = $this->input->get('no');
		$option = array(
			'no'=>$no
		);

		$result = $this->project_model->get_project_detail($option);

		$data['action_type'] = 'create';

		$data['data'] = array(
			'no'           => '',
			'menu_part_no' => '',
			'menu_no'      => '',
			'user_no'      => '',
			'title'        => '',
			'sData'        => '',
			'eData'        => '',
			'pPoint'       => '0',
			'mPoint'       => '0',
			'file'         => '',
			'order'        => '0',
			'created'      => ''
		);
		if ($result->num_rows() > 0){
			$result = $result->row();

			$data['action_type'] = 'edit';
			
			$data['data'] = array(
				'no'           => $result->no,
				'menu_part_no' => $result->menu_part_no,
				'menu_no'      => $result->menu_no,
				'user_no'      => $result->user_no,
				'title'        => $result->title,
				'sData'        => $result->sData,
				'eData'        => $result->eData,
				'pPoint'       => $result->pPoint,
				'mPoint'       => $result->mPoint,
				'file'         => $result->file,
				'order'        => $result->order,
				'created'      => $result->created,
			);
		}
		$data['list_url']  = site_url('project/lists/'.$this->GLOBAL['cur_page']);
		$this->load->view('project/project_write_v',$data);
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
			$result = $this->board_model->get_project_insert($option);
			alert('등록되었습니다.', site_url('project/lists/'.BOARD_PAGE) );

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
			$this->board_model->get_setting_update($option,array('no'=>$board_no));

			alert('수정되었습니다.', site_url('project/lists/'.BOARD_PAGE) );
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('board_no','게시판 no','required');
			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}
			$set_no = is_array($board_no) ? implode(',',$board_no):$board_no;
			$option = array(
				'activated'=>1
			);
			$this->board_model->get_setting_update($option,'no in('.$set_no.')');
			
			alert('삭제되었습니다.', site_url('project/lists/'.BOARD_PAGE) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file project.php */
/* Location: ./controllers/project.php */