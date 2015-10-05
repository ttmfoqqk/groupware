<?
class Board_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	/* 게시판 설정 */
	public function get_setting_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('*');
			$this->db->order_by('order','ASC');
			$this->db->order_by('no','DESC');
		}
		$this->db->from('sw_board_list');
		$this->db->where('activated',0);
		set_options($option);
		$query = $this->db->get();
		
		if($type == 'count'){
			$query  = $query->row();
			$result = $query->total;
		}else{
			$result = $query->result_array();
		}
		
		return $result;
	}
	public function get_setting_detail($option=NULL,$setVla=array()){
		set_options($option);
		$query  = $this->db->get('sw_board_list');
		$result = set_detail_field($query,$setVla);
		
		return $result;
	}
	public function set_setting_insert($option){
		$this->db->insert('sw_board_list',$option);
		//$no = $this->db->insert_id();
	}
	public function set_setting_update($option,$where){
		$this->db->update('sw_board_list',$option,$where);
	}


	/* 게시판 */
	public function get_board_list($option=NULL,$code=0,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}elseif($type == 'notice'){
			$this->db->select('*');
			$this->db->where('is_notice',0);
		}else{
			$this->db->select('*');
			$this->db->limit($limit,$offset);
		}
		if($type != 'count'){
			$this->db->order_by('original_no','DESC');
			$this->db->order_by('order','ASC');
		}
		if($type != 'notice'){
			set_options($option);
		}
		$this->db->from('sw_board_contents');
		$this->db->where('code',$code);
		$this->db->where('is_delete',0);
		
		$query = $this->db->get();
		
		if($type == 'count'){
			$query  = $query->row();
			$result = $query->total;
		}else{
			$result = $query->result_array();
		}
		
		return $result;
	}
	public function get_board_detail($option=NULL,$setVla=array(),$method=null){
		if( $method == 'view' ){
			$sql = "update `sw_board_contents` set count_hit=count_hit+1 where no = '".$option['where']['no']."' ";
			$this->db->query($sql);
		}
		$this->db->select('*');
		$this->db->from('sw_board_contents');
		$this->db->where('is_delete',0);
		set_options($option);
		
		$query  = $this->db->get();
		$result = set_detail_field($query,$setVla);
		
		return $result;
	}
	
	public function set_board_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_board_contents',$option);
		$result = $this->db->insert_id();
		return $result;
	}
	public function set_board_update($option,$where){
		$this->db->update('sw_board_contents',$option,$where);
	}
	/* 임시 파일 insert */
	public function set_board_file_insert($option){
		$this->db->insert('sw_board_file',$option);
		$result = $this->db->insert_id();
		return $result;
	}
	public function set_board_file_update($option,$where){
		$this->db->update('sw_board_file',$option,$where);
	}
	public function set_board_file_delete($option){
		$this->db->delete('sw_board_file',$option);
	}
	public function get_board_file_list($option){
		set_options($option);
		$query = $this->db->get('sw_board_file');
		$result = $query->result_array();
		return $result;
	}
	/*
		파일 보기
		sw_board_file where code , parent_no
	*/
}
/* End of file board_model.php */
/* Location: ./models/board_model.php */