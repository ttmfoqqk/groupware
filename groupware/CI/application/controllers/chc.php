<?
class Chc extends CI_Controller{
	private $TABLE_NAME = 'sw_chc';
	private $CATEGORY = 'chc';
	private $PAGE_NAME = 'CHC';
	
	public function __construct() {
		parent::__construct();
		login_check();
		set_cookie('left_menu_open_cookie',site_url('chc'),'0');
		$this->load->model('md_company');
		$this->md_company->setTable($this->TABLE_NAME);
		$this->load->model('md_chc');
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
	
	public function getLikeFilter(){
		$likes['i.bizName'] = !$this->input->get('ft_userName') ? '' : $this->input->get('ft_userName');
		$likes['i.bizName'] = !$this->input->get('ft_customer') ? '' : $this->input->get('ft_customer');
		$likes['c.keyword'] = !$this->input->get('ft_keyword') ? '' : $this->input->get('ft_keyword');
		$likes['p.name'] = !$this->input->get('ft_title') ? '' : $this->input->get('ft_title');
		return $likes;
	}
	
	public function getWhereFilter(){
		
	}
	
	public function lists(){
		//필터 설정
		$likes = $this->getLikeFilter();
		$start = !$this->input->get('ft_start') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_start')));
		$end = !$this->input->get('ft_end') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_end')."+1 day"));
		
		//Pagination, 테이블정보 필요 설정 세팅
		$tb_show_num = !$this->input->get('tb_num') ? PAGING_PER_PAGE : $this->input->get('tb_num');
		
		if($start && $end)
			$where = array('category'=>$this->CATEGORY, 'created >='=>$start, 'created <'=>$end);
		else
			$where = NULL;
		
		$total = $this->md_chc->getCount($where, $likes);
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
		$result = $this->md_chc->get($where, $likes, $tb_show_num, $offset);	//'no, order, gubun, bizName, bizNumber, phone, fax, created'
		if (count($result) > 0){
			$data['list'] = $result;
		}
		$data['table_num'] = $offset + count($result) . ' / ' . $total;
		
		//페이지 타이틀 설정
		$data['action_url'] = site_url('chc/proc');
		$data['action_type'] = 'delete';
		$data['head_name'] = "CHC";
		$data['page'] = $this->CATEGORY;
		$this->_expData();
		//뷰 로딩
		$this->load->view('marketing/chc_v',$data);
	}
	
	public function write(){
		$get_no = $this->uri->segment(3);
		$where = array(
				'c.no'=>$get_no
		);
		$result = $this->md_chc->get($where);
		
		$data['action_url'] = site_url('chc/proc');
		
		if (count($result) > 0){
			$data['action_type'] = 'edit';
			$result = $result[0];
			$data['data'] = $result;
		}else{
			$data['action_type'] = 'create';
			$data['data'] = $this->md_company->getEmptyData();
			$data['data']['order'] = 0;
		}
		$data['head_name'] = 'CHC 관리';
		
		//뷰 로딩
		$this->load->view('marketing/chc_write',$data);
	}
	
	function _expData(){
		$this->load->model('md_company');
		$this->md_company->setTable("sw_chc_log");
		$this->load->library('common');
	
		$chc_no = !$this->input->post('no') ? NULL : $this->input->post('no'); //391;//
		$tday = !$this->input->post('date') ? NULL : date("Y-m-d", strtotime($this->input->post('date'))); //'2015-08-25'; //
		$end = !$tday ? NULL : date("Y-m-d", strtotime($tday."+1 day"));
		
		if($chc_no == NULL || $tday == NULL){
			echo $this->common->getRet(false, 'Wrong args');
			return;
		}
		
		$where = array('chc_no'=>$chc_no, 'date >='=>$tday, 'date <'=>$end);
		
		$chcDatas = $this->md_company->get($where);;
		
		$ret = array();
		if(count($chcDatas) > 0){
			foreach ($chcDatas as $chcData){
				$time = $chcData['date'];
				$rank = $chcData['rank'];
				array_push($ret, array(strtotime($time . 'UTC')*1000, $chcData['rank']));
			}
			echo $this->common->getRet(true, $ret);
		}else
			echo $this->common->getRet(false, 'No data');
	
	}
	
}
/* End of file chc.php */
/* Location: ./controllers/chc.php */