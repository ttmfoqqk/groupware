<?
class Document_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_category($option){
		$this->db->order_by('parent_no','ASC');
		$this->db->order_by('order','ASC');
		$this->db->where('activated',0);
		$result = $this->db->get_where('sw_document_category',$option);
		return $result->result_array();
	}
	public function get_category_children($parent_no){
		$this->db->where('parent_no', $parent_no);
		$this->db->where('activated', 0);
		$this->db->from('sw_document_category');
		return $this->db->count_all_results();
	}

	public function set_category_update($option,$where){
		$this->db->update('sw_document_category',$option,$where);
	}

	public function set_category_insert($option){
		$sql = "update `sw_document_category` set `order` = `order`+1 where `parent_no` = '".$option['parent_no']."' ";
		$this->db->query($sql);

		$this->db->set('activated',0);
		$this->db->insert('sw_document_category',$option);
	}

}
/* End of file document_model.php */
/* Location: ./models/document_model.php */