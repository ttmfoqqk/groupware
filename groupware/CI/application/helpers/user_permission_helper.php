<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 로그인 체크 리다이렉트
function login_check() {
	$CI =& get_instance();

	if( !$CI->session->userdata('is_login') ){
		if( $CI->input->is_ajax_request() ){
			$return = array(
				'result' => 'error',
				'msg' => 'is_login'
			);
			echo json_encode($return);
			exit;
		}else{
			redirect( 'login?goUrl=' . urlencode($CI->uri->ruri_string()) );
			exit;
		}
	}
}
// 권한 체크

function permission_check($category,$per){
	$CI     =& get_instance();
	$result = permission_search($category,$per);

	if($result == FALSE){
		if( $CI->input->is_ajax_request() ){
			$return = array(
				'result' => 'error',
				'msg'    => 'no permission'
			);
			echo json_encode($return);
			exit;
		}else{
			alert('권한이 없습니다.');
			exit;
		}
	}
}

function permission_search($category=NULL,$per='R'){
	$plug = FALSE;
	$json = json_decode(PERMISSION_JSON);
	
	foreach($json as $item){
		if($item->category == $category){
			$permission = explode('|',$item->permission);
			foreach($permission as $lt){
				if($lt == $per){
					$plug = TRUE;
					break;
				}
			}
			break;
		}
	}
	
	return $plug;
}

?>