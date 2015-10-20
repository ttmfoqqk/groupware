<?
class BaseCode_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_code_list($option=NULL){
		
		$this->db->select('*');
		$this->db->from('sw_base_code');
		$this->db->order_by('order','ASC');
		$this->db->order_by('no','DESC');
		set_options($option);
		
		$query  = $this->db->get();
		$result = $query->result_array();
		
		return $result;
	}
	
	public function set_code_insert($option){
		$this->db->insert('sw_base_code',$option);
		return $this->db->insert_id();
	}
	public function set_code_update($values,$option){
		set_options($option);
		$this->db->update('sw_base_code',$values);
	}
	public function set_code_delete($option){
		set_options($option);
		$this->db->delete('sw_base_code');
	}
}
/* End of file baseCode_model.php */
/* Location: ./models/baseCode_model.php */