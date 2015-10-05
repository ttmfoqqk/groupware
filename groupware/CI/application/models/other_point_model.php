<?
class Other_point_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function get_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('other.*');
			$this->db->select('sum(sum.point) as sPoint');
			$this->db->select('menu.name as menu_name');
			$this->db->select('user.name as user_name');
			
			$this->db->group_by('other.no');
			$this->db->order_by('other.no','DESC');
			$this->db->limit($limit,$offset);
		}

		$this->db->from('sw_other_point AS other');
		$this->db->join('sw_other_point AS sum','other.user_no = sum.user_no and other.no >= sum.no');
		$this->db->join('sw_user AS user','other.user_no = user.no');
		$this->db->join('sw_menu AS menu','other.menu_no = menu.no');
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

	public function set_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_other_point',$option);
		return $this->db->insert_id();
	}
	public function set_update($option,$where){
		$this->db->update('sw_other_point',$option,$where);
	}
	public function set_delete($set_no){
		$this->db->delete('sw_other_point','no in('.$set_no.')');
	}

}
/* End of file other_point_model.php */
/* Location: ./models/other_point_model.php */