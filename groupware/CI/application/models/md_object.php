<?
class Md_object extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function get_object_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('object.*');
			$this->db->select('date_format(object.created,"%Y-%m-%d") as created',FALSE);
			$this->db->select('menu.no as menu_no , menu.name as menu_name');
			$this->db->select('user.name as user_name');
			$this->db->order_by('object.order','ASC');
			$this->db->order_by('object.no','DESC');
			$this->db->limit($limit,$offset);
		}
	
		$this->db->from('sw_object as object');
		$this->db->join('sw_menu menu', 'object.menu_no = menu.no', 'left');
		$this->db->join('sw_user user', 'object.user_no = user.no', 'left');
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
	
	public function get_object_detail($option=NULL,$setVla=array()){
		$this->db->select('object.*');
		$this->db->select('date_format(object.created,"%Y-%m-%d") as created',FALSE);
		$this->db->select('menu.no as menu_no , menu.name as menu_name');
		$this->db->select('user.name as user_name');
		$this->db->from('sw_object as object');
		$this->db->join('sw_menu menu', 'object.menu_no = menu.no', 'left');
		$this->db->join('sw_user user', 'object.user_no = user.no', 'left');
		set_options($option);
	
		$query = $this->db->get();
		$result = set_detail_field($query,$setVla);
		return $result;
	}
	
	public function set_object_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_object',$option);
		return $this->db->insert_id();
	}
	public function set_object_update($values,$option){
		set_options($option);
		$this->db->update('sw_object',$values);
	}
	public function set_object_delete($option){
		set_options($option);
		$this->db->delete('sw_object');
	}

}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
