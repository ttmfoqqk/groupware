<?
class Common_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @param string $table
	 * @param array $select
	 * @param array $option
	 * @param string $limit
	 * @param string $offset
	 * @param array $order
	 * @param string $type
	 */
	public function lists($table=NULL,$select=array(),$option=array(),$limit=NULL,$offset=NULL,$order=array(),$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			if( count($select) > 0 ){
				foreach($select as $key=>$val){
					$this->db->select($key,$val);
				}
			}else{
				$this->db->select('*');
			}
			if( count($order) > 0 ){
				foreach($order as $key=>$val){
					$this->db->order_by($key,$val);
				}
			}
			if( !is_null($offset) && !is_null($limit) ){
				$this->db->limit($limit,$offset);
			}
		}

		$this->db->from($table);
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

	public function detail($table=NULL,$select=array(),$option=array(),$setVla=array()){
		if( count($select) > 0 ){
			foreach($select as $key=>$val){
				$this->db->select($key,$val);
			}
		}else{
			$this->db->select('*');
		}
		$this->db->from($table);
		set_options($option);
		
		$query  = $this->db->get();
		$result = set_detail_field($query,$setVla);
		return $result;
	}
	
	
	public function insert($table=NULL,$set=array()){
		if( count($set) > 0 ){
			foreach($set as $key=>$val){
				$setFg = TRUE;
				if( strtoupper($val) == 'NOW()' ){
					$setFg = FALSE;
				}
				$this->db->set($key,$val,$setFg);
			}
		}
		$this->db->insert($table);
		return $this->db->insert_id();
	}
	public function insert_batch($table=NULL,$set=array()){
		$this->db->insert_batch($table,$set);
	}
	
	public function update($table=NULL,$set=array(),$option=array()){
		if( count($set) > 0 ){
			foreach($set as $key=>$val){
				$this->db->set($key,$val);
			}
		}
		set_options($option);
		$this->db->update($table);
	}
	public function update_batch($table=NULL,$set=array()){
		$this->db->update_batch($table,$set);
	}
	
	public function delete($table=NULL,$option=array()){
		if( count($option)>0 ){
			set_options($option);
			$this->db->delete($table);
		}else{
			$this->db->empty_table($table);
		}
	}

}
/* End of file common_model.php */
/* Location: ./models/common_model.php */