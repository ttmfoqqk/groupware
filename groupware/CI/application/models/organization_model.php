<?
class Organization_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_organization($option){
		$this->db->order_by('parent_no','ASC');
		$this->db->order_by('order','ASC');
		$this->db->where('is_active',0);
		$result = $this->db->get_where('sw_menu',$option);
		return $result->result_array();
	}
	public function get_organization_children($parent_no){
		$this->db->where('parent_no', $parent_no);
		$this->db->from('sw_menu');
		return $this->db->count_all_results();
	}

	public function set_organization_update($option,$where){
		$this->db->update('sw_menu',$option,$where);
	}

	public function set_organization_insert($option){
		$sql = "update `sw_menu` set `order` = `order`+1 where `parent_no` = '".$option['parent_no']."' ";
		$this->db->query($sql);

		$this->db->set('is_active',0);
		$this->db->insert('sw_menu',$option);
	}

}
/* End of file organization_model.php */
/* Location: ./models/organization_model.php */