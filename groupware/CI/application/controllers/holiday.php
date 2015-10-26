<?
class Holiday extends CI_Controller{
	private $PAGE_CONFIG;
	public function __construct() {
		parent::__construct();

		$this->load->model('common_model');
		$this->PAGE_CONFIG['tableName'] = 'sw_holiday';
    }

	public function _remap($method){
		login_check();

		if( $method == 'save'){
			permission_check('holiday','W');
		}else{
			permission_check('holiday','R');
		}
		
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				set_cookie('left_menu_open_cookie',site_url('holiday'),'0');
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
		$this->load->view('company/holiday_v');
	}
	
	function _list(){
		$order = array('date'=>'ASC');
		$result = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,NULL,NULL,NULL,$order);
		$return = array(
				'result' => 'true',
				'data'   => json_encode($result)
		);
		echo json_encode($return);
	}
	
	function _save(){
		permission_check('holiday','W');

		$datas = json_decode($this->input->post('datas'));
		
		if(count($datas) <= 0){
			$return = array(
				'result' => 'false',
				'msg' => 'No Data'
			);
		}else{
			$set = array();
			foreach($datas as $key) {
				array_push($set,array(
					'name' => $key->name,
					'date' => $key->date
				));
			}
			
			$this->common_model->delete($this->PAGE_CONFIG['tableName']);
			$this->common_model->insert_batch($this->PAGE_CONFIG['tableName'],$set);
			$return = array(
				'result' => 'true',
				'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}
	
}
/* End of file holiday.php */
/* Location: ./controllers/holiday.php */