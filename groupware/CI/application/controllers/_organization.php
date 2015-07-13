<?
class Organization extends CI_Controller{
	public function __construct() {
       parent::__construct();
	   $this->load->model('organization_model');
	   set_cookie('left_menu_open_cookie',site_url('organization/'),'0');
    }

	public function _remap($method){
		login_check();
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
		$this->organization();
	}
	/* 리스트 생성 */
	public function organization(){
		$options = array();
		$data['list'] = $this->organization_model->get_organization($options);

		$menuData = array(
			'items' => array(),
			'parents' => array()
		);
        
		foreach ($data['list'] as $menuItem ){
			$menuData['items'][$menuItem['no']] = $menuItem;
			$menuData['parents'][$menuItem['parent_no']][] = $menuItem['no'];
		}

		//echo json_encode($menuData);

        // html 생성
        $data['tree'] = $this->buildMenu(0, $menuData); 
		$this->load->view('member/organization_list_v',$data);
	}

	
	/* 리스트 이동 업데이트 */
	public function _moves(){
		$json_data = json_decode( $this->input->post('json_data') );
		
		if( count($json_data) <= 0){
			$return = array(
				'result' => 'error',
				'msg' => 'no data'
			);
		}else{
			// 업데이트 재귀 호출
			$this->move_update(0,$json_data);
			$return = array(
				'result' => 'ok',
				'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}

	
	/* 아이템 이름 변경 */
	public function _update(){
		$no   = $this->input->post('no');
		$name = $this->input->post('rename');

		if(!$no || !$name){
			$return = array(
				'result' => 'error',
				'msg' => 'form'
			);
		}else{
			$option['name'] = $name;
			$where['no'] = $no;
			$this->organization_model->set_organization_update($option,$where);
			
			$return = array(
				'result' => 'ok',
				'msg' => 'ok'
			);
		}		
		echo json_encode($return);
	}

	
	/* 삭제 */
	public function _delete(){
		$no = $this->input->post('no');
		
		$cnt_children = $this->organization_model->get_organization_children($no);
		if( $cnt_children > 0 ){
			$return = array(
				'result' => 'error',
				'msg' => '하위분류 있을때 삭제불가'
			);
		}else{
			$option['activated'] = 1;
			$where['no'] = $no;
			$this->organization_model->set_organization_update($option,$where);
			$return = array(
				'result' => 'ok',
				'msg' => 'ok'
			);
		}
		echo json_encode($return);
	}


	/* 추가 */
	public function _create(){
		$parent_no = $this->input->post('parent_no');
		$name      = $this->input->post('name');
		
		//입력
		$option = array(
			'parent_no'=>$parent_no,
			'name'=>$name
		);
		$this->organization_model->set_organization_insert($option);


		// 리스트 html 다시 파싱후 리턴
		$options = array();
		$data['list'] = $this->organization_model->get_organization($options);

		$menuData = array(
			'items' => array(),
			'parents' => array()
		);
        
		foreach ($data['list'] as $menuItem ){
			$menuData['items'][$menuItem['no']] = $menuItem;
			$menuData['parents'][$menuItem['parent_no']][] = $menuItem['no'];
		}

        // html 생성
        $data['tree'] = $this->buildMenu(0, $menuData); 
		echo $data['tree'];
	}
	/* 추가 */








	/* 리스트 html 리턴 */
	function buildMenu($parentId, $menuData){
        $html = '';        

		if (isset($menuData['parents'][$parentId])){   
            
            $html = '<ol class="dd-list">';
            foreach ($menuData['parents'][$parentId] as $itemId){
                $html .= '<li class="dd-item dd3-item" data-id="'.$menuData['items'][$itemId]['no'].'">';
				$html .=	'<div class="dd-handle dd3-handle">Drag</div>';
				$html .=	'<div class="dd3-content">';
				$html .=		'<button type="button" class="btn btn-danger btn-xs mr5 mb10 pull-right delete">삭제</button> ';
				$html .=		'<button type="button" class="btn btn-primary btn-xs mr5 mb10 pull-right add">추가</button> ';
				$html .=		'<span class="update-item" style="cursor:pointer;">' . $menuData['items'][$itemId]['name'] . '</span>';
				$html .=	'</div>';
                // find childitems recursively
                $html .= $this->buildMenu($itemId, $menuData);
                $html .= '</li>';
            }
            $html .= '</ol>';
        }
        return $html;
    }
	
	/* 리스트 무브 업데이트 재귀 */
	function move_update($parent_id,$json_data){
		$i=0;
		foreach($json_data as $key) {
			$option = array(
				'parent_no'=>$parent_id,
				'order'=>$i
			);
			$where['no'] = $key->id;

			$this->organization_model->set_organization_update($option,$where);

			if( array_key_exists('children',$key) ){
				$this->move_update($key->id,$key->children);
			}
			$i++;
		}
	}
	
}
/* End of file member.php */
/* Location: ./controllers/member.php */