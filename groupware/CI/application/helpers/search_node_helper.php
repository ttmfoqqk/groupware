<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function search_node($no=0,$mode='parent'){
	if($no==0)return ;
	if( $mode == 'parent'){
		$array = _search_node_parent($no);
		$result['no'] = explode('『',$array['no']);
		$result['name'] = explode('『',$array['name']);
		
		$result['name'] = join(' > ', $result['name']);
	}elseif( $mode == 'children' ){
		$result = _search_node_children($no);
		array_unshift($result,$no);
	}
	return $result;
}

function _search_node_parent($no){
	$CI =& get_instance();
	
	$result['no']   = '';
	$result['name'] = '';

	$CI->db->select('no,parent_no,name,count(*) as cnt');
	$CI->db->from('sw_menu');
	$CI->db->where('no',$no);
	$CI->db->where('is_active',0);
	$query = $CI->db->get();
	$query = $query->row();

	if( $query->cnt > 0 ){
		$result['no']   = $query->no;
		$result['name'] = $query->name;
		if( $query->parent_no > 0 ){
			$sub_result = _search_node_parent($query->parent_no);
			$result['no']   = $sub_result['no'] .'『'. $result['no'];
			$result['name'] = $sub_result['name'] .'『'. $result['name'];
		}
	}
	return $result;
}

function _search_node_children($no){
	$CI =& get_instance();

	$result = array();

	$CI->db->select('no');
	$CI->db->from('sw_menu');
	$CI->db->where('parent_no',$no);
	$CI->db->where('is_active',0);
	$query = $CI->db->get();
	$query = $query->result_array();

	foreach($query as $lt){
		array_push($result, $lt['no']);
		$sub_result = _search_node_children($lt['no']);
		$result = array_merge($result, $sub_result);
	}
	return $result;
}
?>
