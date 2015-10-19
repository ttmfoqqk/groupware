<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/***
 * @param string $option
 * where , where_in , like = array ,
 * custom = text
 */
function set_options($option=null) {
	$CI =& get_instance();

	if( isset($option['where']) ){
		foreach($option['where'] as $key=>$val){
			if($val !== ''){
				$CI->db->where($key,$val);
			}
		}
	}
	
	if( isset($option['where_in']) ){
		foreach($option['where_in'] as $key=>$val){
			if($val !== ''){
				$CI->db->where_in($key,$val);
			}
		}
	}
	
	if( isset($option['like']) ){
		foreach($option['like'] as $key=>$val){
			if($val !== ''){
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

/***
 * 
 * @param unknown $query
 * @param unknown $setVla - 기본값 셋팅
 * @return unknown
 */
function set_detail_field($query,$setVla=array()){
	$count = $query->num_rows();
	if($count <= 0){
		foreach ($query->list_fields() as $field){
			if( array_key_exists($field,$setVla) ){
				$result[$field]=$setVla[$field];
			}else{
				$result[$field]='';
			}
		}
	}else{
		$query = $query->row();
		foreach ($query as $key=>$val){
			$result[$key]=$val;
		}
	}
	
	return $result;
}
?>