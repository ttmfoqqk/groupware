<?
class Company extends CI_Controller{
	private $TABLE_NAME = 'sw_information';
	private $CATEGORY = 'company';
	
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
	
	public function getListFilter(){
		$likes['gubun'] = !$this->input->get('ft_classify') ? '' : $this->input->get('ft_classify');
		$likes['bizName'] = !$this->input->get('ft_bizName') ? '' : $this->input->get('ft_bizName');
		$likes['bizNumber'] = !$this->input->get('ft_bizNumber') ? '' : $this->input->get('ft_bizNumber');
		$likes['phone'] = !$this->input->get('ft_phone') ? '' : $this->input->get('ft_phone');
		return $likes;
	}
	
	public function _marketingList(){
		$this->load->library('common');
		
		$result = $this->md_company->get(array('category'=>'marketing'), 'no, bizName');	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			echo $this->common->getRet(true, $result);
		}else
			echo $this->common->getRet(false, 'No data');
	}
	
	public function lists(){
		//필터 설정
		$likes = $this->getListFilter();
		$start = !$this->input->get('ft_start') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_start')));
		$end = !$this->input->get('ft_end') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_end')."+1 day"));
		
		//Pagination, 테이블정보 필요 설정 세팅
		$tb_show_num = !$this->input->get('tb_num') ? PAGING_PER_PAGE : $this->input->get('tb_num');
		
		if($start && $end)
			$where = array('category'=>$this->CATEGORY, 'created >='=>$start, 'created <'=>$end);
		else
			$where = array('category'=>$this->CATEGORY);
		
		$total = $this->md_company->getCount($where, $likes);
		$uri_segment = 3;
		$cur_page = !$this->uri->segment($uri_segment) ? 1 : $this->uri->segment($uri_segment); // 현재 페이지
		$offset    = ($tb_show_num * $cur_page)-$tb_show_num;
		
		//Pagination 설정
		$config['base_url'] = site_url($this->CATEGORY . '/lists/');
		$config['total_rows'] = $total; // 전체 글갯수
		$config['uri_segment'] = $uri_segment;
		$config['per_page'] = $tb_show_num;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		//테이블 정보 설정
		$data['list'] = array();
		$result = $this->md_company->get($where, '*', $tb_show_num, $offset, $likes);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			$data['list'] = $result;
		}
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		
		//페이지 타이틀 설정
		$data['action_url'] = site_url('company_setting/proc');
		$data['action_type'] = 'delete';
		$data['head_name'] = "회사정보";
		$data['page'] = $this->CATEGORY;
		
		//뷰 로딩
		$this->load->view('company/company_v',$data);
	}

	/* 담당자 */
	public function _staff_lists(){
		$no = $this->input->post('no');
		$option = array(
			'information_no'=>$no
		);
		$result = $this->md_company->get_staff_list($option);
		echo json_encode($result);
	}

	public function _staff_insert(){
		$no = $this->input->post('no');
		$json_data  = json_decode($this->input->post('json_data'));
		
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$option = array();
			$i = 1;
			foreach($json_data as $key) {
				array_push($option,array(
					'information_no' => $no,
					'name'     => $key->name,
					'part'     => $key->part,
					'position' => $key->position,
					'phone'    => $key->phone,
					'ext'      => $key->ext,
					'email'    => $key->email,
					'order'    => (is_numeric($key->order) ? $key->order : 0)
				));
				$i++;
			}
			$result = $this->md_company->set_staff_insert($option,array('information_no'=>$no));
			$return = array(
				'result' => 'ok',
				'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}


	/* 사이트 */
	public function _site_lists(){
		$no = $this->input->post('no');
		$option = array(
			'information_no'=>$no
		);
		$result = $this->md_company->get_site_list($option);
		echo json_encode($result);
	}

	public function _site_insert(){
		$no = $this->input->post('no');
		$json_data  = json_decode($this->input->post('json_data'));
		
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			$option = array();
			$i = 1;
			foreach($json_data as $key) {
				array_push($option,array(
					'information_no' => $no,
					'url'            => $key->url,
					'id'             => $key->id,
					'pwd'            => $key->pwd,
					'bigo'           => $key->bigo,
					'order'          => (is_numeric($key->order) ? $key->order : 0)
				));
				$i++;
			}
			$result = $this->md_company->set_site_insert($option,array('information_no'=>$no));
			$return = array(
				'result' => 'ok',
				'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}
}
/* End of file company.php */
/* Location: ./controllers/company.php */