<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// select option 적용
function set_options($option=null) {
	$CI =& get_instance();

	if( isset($option['where']) ){
		foreach($option['where'] as $key=>$val){
			if($val!=''){
				$CI->db->where($key,$val);
			}
		}
	}
	
	if( isset($option['where_in']) ){
		foreach($option['where_in'] as $key=>$val){
			if($val!=''){
				$CI->db->where_in($key,$val);
			}
		}
	}
	
	if( isset($option['like']) ){
		foreach($option['like'] as $key=>$val){
			if($val!=''){
				$CI->db->like($key,$val);
			}
		}
	}
	
	if( isset($option['custom']) ){
		if( $option['custom'] ){
			$CI->db->where($option['custom']);
		}
	}
}
?>