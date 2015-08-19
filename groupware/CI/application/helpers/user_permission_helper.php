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
// 권한 체크??? true/false 리턴
?>