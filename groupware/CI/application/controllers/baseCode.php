<?
class BaseCode extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model("common_model");
		
		$this->PAGE_CONFIG['tableName'] = 'sw_base_code';
    }

	public function _remap($method){
		login_check();
		
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				permission_check('baseCode','R');
				
				set_cookie('left_menu_open_cookie',site_url('baseCode'),'0');
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
		$this->load->view('company/baseCode_v');
	}
	
	public function _lists(){
		$parent_key = $this->input->get('key') ? $this->input->get('key') : NULL;
		$option['where'] = array(
			'parent_key' => $parent_key
		);
		$order = array(
			'order'=>'ASC',
			'no'=>'DESC'
		);
		$data = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option,NULL,NULL,$order);
		echo json_encode($data);
	}
	
	public function _proc(){
		$code_type   = $this->input->post('code_type');
		
		$no          = $this->input->post('modal_no');
		$action_type = $this->input->post('modal_action');
		$key         = $this->input->post('modal_key');
		$parent_key  = $code_type == 'key' ? NULL : $this->input->post('modal_key');
		$name        = $this->input->post('modal_name');
		$order       = $this->input->post('modal_order');
		$is_active   = $this->input->post('modal_active');
		
		
		
		if($action_type == 'create'){
			if($code_type == 'key'){
				$option['where'] = array(
					'key' => $key
				);
				$data = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option);
				if(count($data) > 0 ){
					$return = array(
						'result' => 'error',
						'msg'    => '이미 등록된 KEY 입니다.'
					);
					echo json_encode($return);
					exit;
				}
			}
			
			$set = array(
				'key'        => $key,
				'parent_key' => $parent_key,
				'name'       => $name,
				'order'      => $order,
				'is_active'  => $is_active
			);
			
			$this->common_model->insert($this->PAGE_CONFIG['tableName'],$set);
			$return = array(
				'result' => 'ok',
				'msg'    => 'create'
			);
		}elseif($action_type == 'update'){
			$values = array(
				'name'       => $name,
				'order'      => $order,
				'is_active'  => $is_active
			);
			$option['where'] = array(
				'no' => $no
			);
			
			$this->common_model->update($this->PAGE_CONFIG['tableName'],$values,$option);
			$return = array(
				'result' => 'ok',
				'msg'    => 'update'
			);
		}elseif($action_type == 'delete'){
			$option['where_in'] = array(
				'no' => $no
			);
			$this->common_model->delete($this->PAGE_CONFIG['tableName'],$option);
			
			if($code_type == 'key'){
				$data = $this->common_model->lists($this->PAGE_CONFIG['tableName'],NULL,$option);
				foreach($data as $lt){
					$option_sub['where'] = array(
						'parent_key' => $lt['key']
					);
					$this->common_model->delete($this->PAGE_CONFIG['tableName'],$option_sub);
				}
			}
			
			$return = array(
				'result' => 'ok',
				'msg'    => 'delete'
			);
		}else{
			$return = array(
				'result' => 'error',
				'msg'    => '잘못된 접근입니다.'
			);
		}

		echo json_encode($return);
	}
}
/* End of file baseCode.php */
/* Location: ./controllers/baseCode.php */