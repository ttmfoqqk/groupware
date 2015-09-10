<?
class Purpose extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		
		$this->load->model('purpose_model');
		//현재 페이지 
		$this->PAGE_CONFIG['set_page'] = $this->uri->segment(2,'search');
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array(
			'd_year'     => !$this->input->get('d_year')     ? date('Y') : $this->input->get('d_year')   ,
			'd_type'     => !$this->input->get('d_type')     ? 'm' : $this->input->get('d_type')         ,
			'd_option'   => !$this->input->get('d_option')   ? date('m') : $this->input->get('d_option') ,
			'department' => !$this->input->get('department') ? '' : $this->input->get('department')      ,
			'user_name'  => !$this->input->get('user_name')  ? '' : $this->input->get('user_name')
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
				set_cookie('left_menu_open_cookie',site_url('purpose/'.$this->PAGE_CONFIG['set_page']),'0');
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
		$this->search();
	}
	public function search(){
		$data['list'] = array();
		$this->load->view('purpose/search_v',$data);
	}
	public function appraisal(){
		$d_year   = $this->PAGE_CONFIG['params']['d_year'];
		$d_type   = $this->PAGE_CONFIG['params']['d_type'];
		$d_option = $this->PAGE_CONFIG['params']['d_option'];

		if( $d_type == 'm' ){
			$sDate = $d_year .'-'. $d_option;
			$eDate = $d_year .'-'. $d_option;
		}else{
			switch ($d_option) {
			case 1:
				$sDate = $d_year .'-01';
				$eDate = $d_year .'-03';
				break;
			case 2:
				$sDate = $d_year .'-04';
				$eDate = $d_year .'-06';
				break;
			case 3:
				$sDate = $d_year .'-07';
				$eDate = $d_year .'-09';
				break;
			case 4:
				$sDate = $d_year .'-10';
				$eDate = $d_year .'-12';
				break;
			case 5:
				$sDate = $d_year .'-01';
				$eDate = $d_year .'-06';
				break;
			case 6:
				$sDate = $d_year .'-01';
				$eDate = $d_year .'-09';
				break;
			case 7:
				$sDate = $d_year .'-01';
				$eDate = $d_year .'-12';
				break;
			}
		}

		/*
		$department = $this->PAGE_CONFIG['params']['department'];
		$user_name = $this->PAGE_CONFIG['params']['user_name'];
		*/

		// approved point
		$option['where'] = array(
			'DATE_FORMAT(status.created,"%Y-%m") >=' => $sDate,
			'DATE_FORMAT(status.created,"%Y-%m") <=' => $eDate,
			'status.part_sender' => $this->PAGE_CONFIG['params']['department']
			//'status.sender' => $this->session->userdata('no') //개인용
		);
		$option['like'] = array(
			'sender.name' => $this->PAGE_CONFIG['params']['user_name']
		);

		$option['annual']['where'] = array(
			'DATE_FORMAT(annual.data,"%Y-%m") >=' => $sDate,
			'DATE_FORMAT(annual.data,"%Y-%m") <=' => $eDate,
			'department.no' => $this->PAGE_CONFIG['params']['department']
			//'annual.user_no' => $this->session->userdata('no')
		);
		$option['annual']['like'] = array(
			'user.name' => $this->PAGE_CONFIG['params']['user_name']
		);
		$data['approved'] = $this->purpose_model->get_point_approved($option);



		
		// chc point
		$option['where'] = array(
			'staff.user_no' => $this->session->userdata('no')
		);
		$option['like'] = array();
		$data['chc'] = $this->purpose_model->get_point_chc($option);




		// other point
		$option['where'] = array(
			'user_no' => $this->session->userdata('no')
		);
		$option['like'] = array();
		$data['others'] = $this->purpose_model->get_point_other($option);
		

		$data['all_point'] = array(
			'point_total' => $data['approved']['point_avg'] * ($data['chc']['point_total']<=1?1:$data['chc']['point_total']) + $data['others']['sum'],
			'percent_total' => 0
		);
		$this->load->view('purpose/appraisal_v',$data);
	}
}
/* End of file purpose.php */
/* Location: ./controllers/purpose.php */