<?
class Schedule extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();
		$this->load->model('project_model');
		
		//검색 파라미터
		$this->PAGE_CONFIG['params'] = array(
				'sData'        => !$this->input->get('sData')        ? date('Y-m') : $this->input->get('sData'),
				'eData'        => !$this->input->get('eData')        ? date('Y-m') : $this->input->get('eData'),
				'menu_part_no' => !$this->input->get('menu_part_no') ? '' : $this->input->get('menu_part_no')  ,
				'menu_no'      => !$this->input->get('menu_no')      ? '' : $this->input->get('menu_no')       ,
				'userName'     => !$this->input->get('userName')     ? '' : $this->input->get('userName')      ,
				'title'        => !$this->input->get('title')        ? '' : $this->input->get('title')
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
				set_cookie('left_menu_open_cookie',site_url('schedule'),'0');
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
		$data['sData'] = $this->PAGE_CONFIG['params']['sData'];
		$data['eData'] = $this->PAGE_CONFIG['params']['eData'];
		
		$option['where'] = array(
			'project.menu_no' => $this->PAGE_CONFIG['params']['menu_no'],
			'staff.menu_no' => $this->PAGE_CONFIG['params']['menu_part_no']
		);
		$option['like'] = array(
			'project.title' => $this->PAGE_CONFIG['params']['title'],
			'user.name' => $this->PAGE_CONFIG['params']['userName'],
		);
		$option['custom'] = '((date_format(project.sData,"%Y-%m") >= "'.$data['sData'].'" and date_format(project.sData,"%Y-%m") <= "'.$data['sData'].'") or (date_format(project.eData,"%Y-%m") >= "'.$data['eData'].'" and date_format(project.eData,"%Y-%m") <= "'.$data['sData'].'"))';
		
		$get_data = $this->project_model->get_schedule($option);
		
		//echo $this->db->last_query();
		
		
		$data['data'] = $get_data['user'];
		$data['list'] = $get_data['list'];
		
		$i = 0;
		foreach($data['data'] as $user){
			$k = 0;
			$list_array = array();
			foreach($data['list'] as $list){
				if (in_array($list['id'] , $user) ){
					array_push($list_array, $list);
					unset($data['list'][$k]);
				}
				$k++;
			}
			$data['data'][$i]['list'] = $list_array;
			$i++;
		}
		
		/*
		 * 목록없는 회원 지우기
		 */
		$j = 0;
		foreach($data['data'] as $test){
			if( count($test['list']) ==0 ){
				unset($data['data'][$j]);
			}
			$j++;
		}
		//echo json_encode($data['data']);
		
		$this->load->view('project/project_schedule_v',$data);
	}
}
/* End of file Schedule.php */
/* Location: ./controllers/Schedule.php */