<?
class Project_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_project_list($option,$limit,$offset){
		$this->db->select('count(*) as total');
		$query = $this->db->get_where('sw_project',$option );
		$query = $query->row();
		$result['total'] = $query->total;
		
		$this->db->order_by('order','ASC');
		$this->db->order_by('no','DESC');
		$query = $this->db->get_where('sw_project',$option,$limit,$offset);
		$result['list'] = $query->result_array();
		return $result;
	}
	public function get_project_detail($option){
		$result = $this->db->get_where('sw_project',$option);
		return $result;
	}
	public function get_project_insert($option){
		$this->db->insert('sw_project',$option);
		return $this->db->insert_id();
	}
	public function get_project_update($option,$where){
		$this->db->update('sw_project',$option,$where);
	}
}
/* End of file project_model.php */
/* Location: ./models/project_model.php */