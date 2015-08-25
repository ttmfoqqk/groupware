<?
class _Common{
	function index(){
		$CI =& get_instance();

		// 게시판 메뉴 목록
		$CI->db->select('no,code,name');
		$CI->db->where('activated','0');
		$CI->db->order_by('order','ASC');
		$CI->db->order_by('no','DESC');
		$result = $CI->db->get('sw_board_list');
		
		if( $result->num_rows() > 0 ){
			define('BOARD_LIST_JSON', json_encode($result->result_array()) );
		}else{
			define('BOARD_LIST_JSON', json_encode('{}') );
		}
		unset($result);
		

		
		// 결재 메뉴 카운트
		// 미결재 카운트용 추가 작성 요망
		$count_approved['sender']   = array('a'=>'0','b'=>'0','c'=>'0','d'=>'0','ao'=>'0');
		$count_approved['receiver'] = array('a'=>'0','b'=>'0','c'=>'0','d'=>'0','ao'=>'0');
		
		//sender
		$CI->db->select('status');
		$CI->db->select('count(*) as count');
		$CI->db->from('sw_approved_status');
		$CI->db->where( 'sender' , $CI->session->userdata('no') );
		$CI->db->group_by('status');
		$query = $CI->db->get();
		$result['sender'] = $query->result_array();

		foreach( $result['sender'] as $lt ){
			$count_approved['sender'][$lt['status']] = $lt['count'];
		}
		
		//receiver
		$CI->db->select('status');
		$CI->db->select('count(*) as count');
		$CI->db->from('sw_approved_status');
		$CI->db->where( 'receiver' , $CI->session->userdata('no') );
		$CI->db->group_by('status');
		$query = $CI->db->get();
		$result['receiver'] = $query->result_array();

		foreach( $result['receiver'] as $lt ){
			$count_approved['receiver'][ $lt['status'] ] = $lt['count'];
		}

		define('APPROVED_COUNT_JSON', json_encode($count_approved) );

		unset($count_approved);
		unset($result);
		unset($query);
	}
}
?>