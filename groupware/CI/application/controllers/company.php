<?
class Company extends CI_Controller{
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('company'),'0');
		$this->load->model('md_company');
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

		/*
			http://codeigniter-kr.org/user_guide_2.1.0/libraries/pagination.html
			test

			공통 함수 작성 요망
		*/
		$config['base_url'] = site_url('company/lists/');
		$config['total_rows'] = 200; // 전체 글갯수
		$config['per_page'] = 10;  // 보여질 갯수
		$config['uri_segment'] = 3;
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

		$data['list'] = array();
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		$result = $this->md_company->get(NULL, 'no, order, gubun, bizName, bizNumber, phone, fax, created');
		if (count($result) > 0){
			foreach ($result as $row)
			{
				array_push($data['list'], $row);
			}
		}
		$this->load->view('company/company_v',$data);
	}
}
/* End of file company.php */
/* Location: ./controllers/company.php */