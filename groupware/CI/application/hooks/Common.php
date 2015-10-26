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
		

		$date_limit = date('Y-m-d',strtotime(date('Y-m-d').'-5 days'));
		// 결재 메뉴 카운트
		// 미결재 카운트용 추가 작성 요망
		$count_approved['sender']   = array('a'=>'0','b'=>'0','c'=>'0','d'=>'0','ao'=>'0');
		$count_approved['receiver'] = array('a'=>'0','b'=>'0','c'=>'0','d'=>'0','ao'=>'0');
		
		//sender
		$CI->db->select('status');
		$CI->db->select('count(*) as count');
		$CI->db->from('sw_approved_status');
		$CI->db->where( 'sender' , $CI->session->userdata('no') );
		$CI->db->where( 'date_format(created,"%Y-%m-%d") >=', '"'.date('Y-m-d').'"' ,FALSE);
		$CI->db->group_by('status');
		$query = $CI->db->get();
		$result['sender'] = $query->result_array();
		//echo $date_limit;

		foreach( $result['sender'] as $lt ){
			$count_approved['sender'][$lt['status']] = $lt['count'];
		}

		//sender - 미결재
		$CI->db->select('count(*) as count');
		$CI->db->from('sw_approved_status');
		$CI->db->where( 'sender' , $CI->session->userdata('no') );
		$CI->db->where( 'date_format(created,"%Y-%m-%d") < ', '"'.date('Y-m-d').'"' ,FALSE );
		$CI->db->where( 'date_format(created,"%Y-%m-%d") >= ', '"'.$date_limit.'"' ,FALSE );
		$CI->db->where( 'status', 'a' );
		$query = $CI->db->get();
		$result['sender_ao'] = $query->row();
		$count_approved['sender']['ao'] = $result['sender_ao']->count;
		
		//receiver
		$CI->db->select('status');
		$CI->db->select('count(*) as count');
		$CI->db->from('sw_approved_status');
		$CI->db->where( 'receiver' , $CI->session->userdata('no') );
		$CI->db->where( 'date_format(created,"%Y-%m-%d") >=', '"'.date('Y-m-d').'"' ,FALSE);
		$CI->db->group_by('status');
		$query = $CI->db->get();
		$result['receiver'] = $query->result_array();

		foreach( $result['receiver'] as $lt ){
			$count_approved['receiver'][ $lt['status'] ] = $lt['count'];
		}

		//receiver - 미결재
		$CI->db->select('count(*) as count');
		$CI->db->from('sw_approved_status');
		$CI->db->where( 'receiver' , $CI->session->userdata('no') );
		$CI->db->where( 'date_format(created,"%Y-%m-%d") < ', '"'.date('Y-m-d').'"' ,FALSE );
		$CI->db->where( 'date_format(created,"%Y-%m-%d") >= ', '"'.$date_limit.'"' ,FALSE );
		$CI->db->where( 'status', 'a' );
		$query = $CI->db->get();
		$result['receiver_ao'] = $query->row();
		$count_approved['receiver']['ao'] = $result['receiver_ao']->count;

		define('APPROVED_COUNT_JSON', json_encode($count_approved) );

		unset($count_approved);
		unset($result);
		unset($query);
		
		// 권한 목록
		$CI->db->select('category,permission');
		$CI->db->from('sw_user_permission');
		$CI->db->where('user_no',$CI->session->userdata('no'));
		$query = $CI->db->get();
		$query = $query->result_array();
		
		define('PERMISSION_JSON', json_encode($query) );
		unset($query);
	}
}
?>