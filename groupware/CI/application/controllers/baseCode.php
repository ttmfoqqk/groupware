<?
class BaseCode extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model("baseCode_model");
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
		$data = $this->baseCode_model->get_code_list($option);
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
				$data = $this->baseCode_model->get_code_list($option);
				if(count($data) > 0 ){
					$return = array(
						'result' => 'error',
						'msg'    => '이미 등록된 KEY 입니다.'
					);
					echo json_encode($return);
					exit;
				}
			}
			
			$option = array(
				'key'        => $key,
				'parent_key' => $parent_key,
				'name'       => $name,
				'order'      => $order,
				'is_active'  => $is_active
			);
			
			$this->baseCode_model->set_code_insert($option);
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

			$this->baseCode_model->set_code_update($values,$option);
			$return = array(
				'result' => 'ok',
				'msg'    => 'update'
			);
		}elseif($action_type == 'delete'){
			$option['where_in'] = array(
				'no' => $no
			);
			$this->baseCode_model->set_code_delete($option);
			
			if($code_type == 'key'){
				$data = $this->baseCode_model->get_code_list($option);
				foreach($data as $lt){
					$option_sub['where'] = array(
						'parent_key' => $lt['key']
					);
					$this->baseCode_model->set_code_delete($option_sub);
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
	
	
	
	
	
	
	
	public function _keyList(){
		$this->load->library('common');
		$ret = $this->md_company->get(array('parent_key'=>NULL));
		
		if(count($ret) > 0)
			echo $this->common->getRet(true, $ret);
		else
			echo $this->common->getRet(false, "No data");
	}
	
	public function _codeList(){
		$this->load->library('common');
		
		$no = $this->input->post('no') ? $this->input->post('no') : '';
// 		$ret = $this->md_company->get('parent_key IS NOT NULL');
		$ret = $this->md_company->get(array('parent_key'=>$no));
	
		if(count($ret) > 0)
			echo $this->common->getRet(true, $ret);
		else
			echo $this->common->getRet(false, "No data");
	}
	
	public function _createKey(){
		$this->load->library('common');
		
		$datas = $this->input->post('data');
		if($datas['method'] == 'create'){
			unset($datas['method']);
			$this->md_company->create($datas);
			
			echo $this->common->getRet(true, '등록 하였습니다.');
		}else if($datas['method'] == 'modify'){
			$no = $datas['no'];
			unset($datas['method']);
			unset($datas['no']);
			
			$this->md_company->modify(array('no'=>$no), $datas);
			echo $this->common->getRet(true, '변경 하였습니다.');
		}else 
			echo $this->common->getRet(false, '잘못된 입력입니다');
	}
	
	public function _createCode(){
		$this->load->library('common');
		
		$datas = $this->input->post('data');
		if($datas['method'] == 'create'){
			unset($datas['method']);
			$this->md_company->create($datas);
			
			echo $this->common->getRet(true, '등록 하였습니다.');
		}else if($datas['method'] == 'modify'){
			$no = $datas['no'];
			unset($datas['method']);
			unset($datas['no']);
			
			$this->md_company->modify(array('no'=>$no), $datas);
			echo $this->common->getRet(true, '변경 하였습니다.');
		}else if($datas['method'] == 'remove'){
			if(!isset($datas['ids']) || empty($datas['ids']))
				echo $this->common->getRet(false, '삭제 대상이 없습니다.');
			else{
				$this->md_company->deleteIn('no', $datas['ids']);
				echo $this->common->getRet(true, '삭제 하였습니다.');
			}
		}else
			echo $this->common->getRet(false, '잘못된 입력입니다');
	}
}
/* End of file baseCode.php */
/* Location: ./controllers/baseCode.php */