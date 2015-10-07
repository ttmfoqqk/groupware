<?
class Information_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('*');
			$this->db->select('date_format(created,"%Y-%m-%d") as created',false);
			$this->db->order_by('order','ASC');
			$this->db->order_by('no','DESC');
		}
		$this->db->from('sw_information');
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
	
	public function get_detail($option=NULL,$setVla=array()){
		$this->db->select('*');
		$this->db->from('sw_information');
		set_options($option);
		
		$query  = $this->db->get();
		$result = set_detail_field($query,$setVla);
		return $result;
	}
	
	public function set_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_information',$option);
		$result = $this->db->insert_id();
		return $result;
	}
	public function set_update($values,$option){
		set_options($option);
		$this->db->update('sw_information',$values);
	}
	public function set_delete($option){
		set_options($option);
		$this->db->delete('sw_information');
	}
	
	
	/* 담당자 */
	public function get_staff_list($option){
		$this->db->from('sw_information_staff');
		$this->db->order_by('order','ASC');
		$this->db->order_by('name','ASC');
		set_options($option);
	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	public function set_staff_delete($option){
		set_options($option);
		$this->db->delete('sw_information_staff');
	}
	
	public function set_staff_insert($option,$staff){
		$this->set_staff_delete($staff);
		$this->db->insert_batch('sw_information_staff',$option);
	}
	
	/* 사이트 */
	public function get_site_list($option){
		$this->db->from('sw_information_site');
		$this->db->order_by('order','ASC');
		$this->db->order_by('url','ASC');
		set_options($option);
	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	public function set_site_delete($option){
		set_options($option);
		$this->db->delete('sw_information_site');
	}
	
	public function set_site_insert($option,$staff){
		$this->set_site_delete($staff);
		$this->db->insert_batch('sw_information_site',$option);
	}
	
}
/* End of file Information_model.php */
/* Location: ./models/Information_model.php */