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
		//$likes['p.menu_part_no'] = !$this->input->get('ft_department') ? '' : $this->input->get('ft_department');
		$likes['u.name'] = !$this->input->get('ft_userName') ? '' : $this->input->get('ft_userName');
		$likes['i.bizName'] = !$this->input->get('ft_customer') ? '' : $this->input->get('ft_customer');
		$likes['c.keyword'] = !$this->input->get('ft_keyword') ? '' : $this->input->get('ft_keyword');
		$likes['m.name'] = !$this->input->get('ft_title') ? '' : $this->input->get('ft_title');
		return $likes;
	}
	
	public function getWhereFilter(){
		$where['p.menu_part_no'] = !$this->input->get('ft_department') ? '' : $this->input->get('ft_department');
		
		$start = !$this->input->get('ft_start') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_start')));
		$end = !$this->input->get('ft_end') ? NULL : date("Y-m-d", strtotime($this->input->get('ft_end')."+1 day"));
		$rank = !$this->input->get('ft_rank') ? NULL : $this->input->get('ft_rank');
		
		if($start && $end){
			$where['c.created >='] = $start;
			$where['c.created <'] = $end;
		}
		
		if($rank){
			if($rank == 6){
				$where['c.rank >='] = $rank;
			}else if($rank == 7){
				$where['c.rank >'] = 5;
				$where['c.rank <='] = 10;
			}else if($rank == 8){
				$where['c.rank'] = '0';			//0 처리, 11위 이상 랭킹 처리
			}
			else 
				$where['c.rank'] = $rank;
		}
		return $where;
	}
	
	public function lists(){
		//필터 설정
		$likes = $this->getLikeFilter();
		
		//Pagination, 테이블정보 필요 설정 세팅
		$tb_show_num = !$this->input->get('tb_num') ? PAGING_PER_PAGE : $this->input->get('tb_num');
		
		$where = $this->getWhereFilter();
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
	
	public function checkKind($kind){
		if($kind == '지식인')
			$kind = 'kin';
		else if($kind == '블로그')
			$kind = 'blog';
		else if($kind == '카페')
			$kind = 'cafe';
		else if($kind == '모바일')
			$kind = 'm';
		else{
			$kind = null;
		}
		return $kind;
	}
	
	function proc(){
		$this->load->library('form_validation');
		$this->load->model('md_company');
		$this->md_company->setTable($this->TABLE_NAME);
		
		$no = $this->input->post('no');
		$action_type = $this->input->post ( 'action_type' );
		$projectNo = $this->input->post('project_no');
		$customerNo = $this->input->post('ft_commpany');
		$keyword = $this->input->post('keyword');
		$url = $this->input->post('url');
		$ip = $this->input->post('ip');
		$order = $this->input->post('order');
		$accountNos = $this->input->post('selIdd');
		$idUsed = $this->input->post('is_request');
		$kind = $this->input->post('menu_k');
		$is_active = $this->input->post('is_active');
		
		
		if( $action_type == 'create' ){
			if($kind = $this->checkKind($kind) != null){
			}else
				alert("분류가 잘 못 되었습니다");
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('project_no','프로젝트','required');
			$this->form_validation->set_rules('is_active','프로젝트 진행여부','required');
			$this->form_validation->set_rules('ft_commpany','고객사','required');
			$this->form_validation->set_rules('keyword','키워드','required');
			$this->form_validation->set_rules('url','URL','required');
			$this->form_validation->set_rules('ip','IP','required');
				
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
			
			//chc 등록
			$cur = new DateTime();
			$cur = $cur->format('Y-m-d H:i:s');
			$data = array(
					'project_no' => $projectNo,
					'keyword' => $keyword,
					'url' => $url,
					'ip' => $ip,
					'kind' => $kind,
					'order' => $order,
					'status' => $is_active,
					'customer_no' => $customerNo,
					'created' => $cur,
			);
			$chcNo = $this->md_company->create($data);
			
			//chc_history 등록
			$this->md_company->setTable("sw_chc_history");
			$data = array(
					'chc_no'=>$chcNo, 
					'url'=>$url, 
					'created'=>$cur
			);
			$this->md_company->create($data);
			
			if($kind == 'kin'){
				//account 등록 (지식인 일떄만)
				$this->md_company->setTable("sw_account");
				$i = 0;
				foreach ($accountNos as $accountNo){
					if($accountNo != "" && $accountNo != null)
						$this->md_company->modify(array('no'=>$accountNo), array('chc_no'=>$chcNo, 'is_using_question'=>$idUsed[$i], 'used'=>$cur));
					$i = $i +1 ;
				}
			}
			alert('등록되었습니다.', site_url('chc') );
				
		}elseif( $action_type == 'edit' ){
			if($kind = $this->checkKind($kind) != null){
			}else
				alert("분류가 잘 못 되었습니다");
			
			$this->form_validation->set_rules('action_type','폼 액션','required');
			$this->form_validation->set_rules('url','URL','required');
			$this->form_validation->set_rules('ip','IP','required');
				
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
		
			//chc 변경
			$cur = new DateTime();
			$cur = $cur->format('Y-m-d H:i:s');
			$data = array(
					'url' => $url,
					'ip' => $ip,
					'order' => $order,
			);
			$this->md_company->modify(array('no'=>$no), $data);
			
			//chc_history 등록
			$this->md_company->setTable("sw_chc_history");
			$data = array(
					'chc_no'=>$no, 
					'url'=>$url, 
					'created'=>$cur
			);
			$rets = $this->md_company->get(array('chc_no'=>$no));
			$isSame = false;
			if(count($rets) > 0){
				foreach ($rets as $ret){
					if($ret['url'] == $url){
						$isSame = true;
						break;
					}
				}
			}
			if($isSame == false)
				$this->md_company->create($data);
			
			if($kind == 'kin' && !empty($accountNos)){
				//account 등록 (지식인 일떄만)
				$this->md_company->setTable("sw_account");
				$i = 0;
				foreach ($accountNos as $accountNo){
					if($accountNo != "" && $accountNo != null)
						$this->md_company->modify(array('no'=>$accountNo), array('chc_no'=>$no, 'is_using_question'=>isset($idUsed[$i])?$idUsed[$i] : 1, 'used'=>$cur));
					$i = $i +1 ;
				}
			}
			alert('수정되었습니다.', site_url('chc') );
			//echo '<script>javascript:window.history.go(-3);</script>';
				
		}elseif( $action_type == 'delete' ){
			$this->form_validation->set_rules('no', 'no','required');
			if ($this->form_validation->run() == FALSE){
				echo validation_errors();
				alert('잘못된 접근입니다.');
			}
		
			$set_no = is_array($no) ? implode(',',$no):$no;
			$where = 'no in (' . $set_no . ')';
		
			$this->md_company->deleteIn('no', $no);
			alert('삭제되었습니다.', site_url('chc') );
		}else{
			alert('잘못된 접근입니다.');
		}
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