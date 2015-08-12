<?
class Menu extends CI_Controller{
	public function __construct() {
		parent::__construct();
		login_check();
		$param = !$this->uri->segment(3) ? 'department' : $this->uri->segment(3);
		set_cookie('left_menu_open_cookie',site_url('menu/lists/'.$param),'0');

		switch ($param) {
		case 'department':
			$menu_name = "부서 분류관리";
			break;
		case 'object':
			$menu_name = "물품 분류관리";
			break;
		case 'rule':
			$menu_name = "회사규정 분류관리";
			break;
		case 'document':
			$menu_name = "회사서식 분류관리";
			break;
		case 'meeting':
			$menu_name = "회의관리 분류관리";
			break;
		case 'project':
			$menu_name = "업무 분류관리";
			break;
		}

		define('MENU_NAME' , $menu_name);
		$this->load->model('organization_model');
    }

	/* 
		비동기 리스트 호출 작성요망 : 다른 페이지에서의 메뉴 호출
		분류 전체경로 select
	*/

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
		$category = !$this->uri->segment(3) ? 'department' : $this->uri->segment(3);
		$options = array(
			'category' => $category
		);
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
        $data['tree'] = $this->buildMenu(0, $menuData, 'html');
		$data['key']  = $category;
		
		$this->load->view('menu/menu_v',$data);
	}

	public function _lists(){
		$category = !$this->uri->segment(3) ? 'department' : $this->uri->segment(3);
		$options = array(
			'category' => $category
		);
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
        $data['tree'] = $this->buildMenu(0, $menuData, 'json');
		echo json_encode($data['tree']);
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
			$option['is_active'] = 1;
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
		$category  = $this->input->post('category');
		
		//입력
		$option = array(
			'parent_no'=>$parent_no,
			'category' => $category,
			'name'=>$name
		);
		$this->organization_model->set_organization_insert($option);


		// 리스트 html 다시 파싱후 리턴
		$options = array(
			'category' => $category
		);
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
        $data['tree'] = $this->buildMenu(0, $menuData, 'html'); 
		echo $data['tree'];
	}
	/* 추가 */








	/* 리스트 html 리턴 */
	function buildMenu($parentId, $menuData, $type){
		$html = '';        
		
		if (isset($menuData['parents'][$parentId])){   
			if( $type == 'html' ){
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
					$html .= $this->buildMenu($itemId, $menuData, $type);
					$html .= '</li>';
				}
				$html .= '</ol>';
			}elseif( $type == 'json' ){
				$cnt  = 0;
				$html = '[';
				foreach ($menuData['parents'][$parentId] as $itemId){
					$cnt ++;
					$children = $this->buildMenu($itemId, $menuData, $type);

					$html .= '{';
					$html .=	'"id":"'.$menuData['items'][$itemId]['no'].'"';
					$html .=	',"name":"'.$menuData['items'][$itemId]['name'].'"';
					if($children){
						$html .=	',"children":' . $this->buildMenu($itemId, $menuData, $type);
					}					
					$html .= '}'. ( $cnt < count($menuData['parents'][$parentId]) ? ',' : '');
				}
				$html .= ']';
			}
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
/* End of file Menu.php */
/* Location: ./controllers/Menu.php */