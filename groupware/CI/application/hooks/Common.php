<?
class _Common{
	function index(){
		$CI =& get_instance();

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
	}
}
?>