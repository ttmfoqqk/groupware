<?
class Other_point extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('other_point_model');

		$this->PAGE_CONFIG['segment']  = 3;
		$this->PAGE_CONFIG['cur_page'] = $this->uri->segment($this->PAGE_CONFIG['segment'],1);
		$this->PAGE_CONFIG['params'] = array(
			'sData'      => !$this->input->get('sData')      ? '' : $this->input->get('sData')      ,
			'eData'      => !$this->input->get('eData')      ? '' : $this->input->get('eData')      ,
			'department' => !$this->input->get('department') ? '' : $this->input->get('department') ,
			'user_name'  => !$this->input->get('user_name')  ? '' : $this->input->get('user_name')  ,
			'title'      => !$this->input->get('title')      ? '' : $this->input->get('title')      ,
			'point'      => !$this->input->get('point')      ? '' : $this->input->get('point')
		);
		$this->PAGE_CONFIG['params_string'] = '?'.http_build_query($this->PAGE_CONFIG['params']);
    }

	public function _remap($method){
		login_check();
		permission_check('other_point','R');
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				set_cookie('left_menu_open_cookie',site_url('other_point/lists/'),'0');
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
		$array_menu = search_node($this->PAGE_CONFIG['params']['department'],'children');
		$option['where'] = array(
			'other.date <=' => $this->PAGE_CONFIG['params']['sData'],
			'other.date >=' => $this->PAGE_CONFIG['params']['eData'],
			//'menu.no'       => $this->PAGE_CONFIG['params']['department'],
			'other.point'   => $this->PAGE_CONFIG['params']['point']
		);
		$option['like'] = array(
			'user.name'   => $this->PAGE_CONFIG['params']['user_name'],
			'other.title' => $this->PAGE_CONFIG['params']['title']
		);
		$option['where_in'] = array(
			'menu.no' => $array_menu
		);

		$offset = (PAGING_PER_PAGE * $this->PAGE_CONFIG['cur_page'])-PAGING_PER_PAGE;

		$data['total']         = $this->other_point_model->get_list($option,null,null,'count');
		$data['list']          = $this->other_point_model->get_list($option,PAGING_PER_PAGE,$offset);
		$data['parameters']    = urlencode($this->PAGE_CONFIG['params_string']); // form proc parameters
		$data['action_url']    = site_url('other_point/proc/'.$this->PAGE_CONFIG['cur_page']); // 폼 action

		$config['base_url']    = site_url('other_point/lists');
		$config['total_rows']  = $data['total'];
		$config['per_page']    = PAGING_PER_PAGE;
		$config['cur_page']    = $this->PAGE_CONFIG['cur_page'];
		$config['uri_segment'] = $this->PAGE_CONFIG['segment'];

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view('purpose/add_v',$data);
	}

	public function proc(){
		$this->load->library('form_validation');

		$action_type = $this->input->post('action_type');
		$title       = $this->input->post('in_title');
		$operator    = $this->input->post('in_operator');
		$point       = $this->input->post('in_point');
		$department  = $this->input->post('in_department');
		$user        = $this->input->post('in_user');
		$date        = $this->input->post('in_date');
		$parameters  = urldecode($this->input->post('parameters'));
		
		if( $action_type == 'create' ){
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('in_title','제목','required|max_length[200]');
			$this->form_validation->set_rules('in_operator','연산자','required');
			$this->form_validation->set_rules('in_point','점수','required');
			$this->form_validation->set_rules('in_department','부서','required');
			$this->form_validation->set_rules('in_user','사원','required');
			$this->form_validation->set_rules('in_date','일자','required');

			if ($this->form_validation->run() == FALSE){
				alert('잘못된 접근입니다.');
			}

			$option = array(
				'menu_no'   => $department,
				'user_no'   => $user,
				'title'     => $title,
				'operator'  => $operator,
				'point'     => $point,
				'date'      => $date,
				'w_user_no' => $this->session->userdata('no')
			);
			$result = $this->other_point_model->set_insert($option);
			alert('등록되었습니다.', site_url('other_point/lists/'.$this->PAGE_CONFIG['cur_page'].$parameters) );
		}else{
			alert('잘못된 접근입니다.');
		}
	}
}
/* End of file purpose.php */
/* Location: ./controllers/purpose.php */