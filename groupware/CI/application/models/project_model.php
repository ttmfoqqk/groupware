<?
class Project_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_project_list($option=null,$limit=null,$offset=null){
		$where = array();
		foreach($option['where'] as $key=>$val){
			if($val!=''){
				$this->db->where($key, $val);
				$where[$key] = $val;
			}
		}
		$like = array();
		foreach($option['like'] as $key=>$val){
			if($val!=''){
				$like[$key] = $val;
			}
		}

		$this->db->select('count(*) as total');
		$this->db->from('sw_project');
		$this->db->join('sw_menu a','sw_project.menu_part_no = a.no');
		$this->db->join('sw_menu b','sw_project.menu_no = b.no');
		$this->db->join('sw_user c','sw_project.user_no = c.no');
		$this->db->where($where);
		$this->db->like($like);
		$query = $this->db->get();
		$query = $query->row();
		$result['total'] = $query->total;


		$this->db->select('sw_project.*');
		$this->db->select('a.name as part_name');
		$this->db->select('b.name as menu_name');
		$this->db->select('c.name as user_name');
		$this->db->from('sw_project');
		$this->db->join('sw_menu a','sw_project.menu_part_no = a.no');
		$this->db->join('sw_menu b','sw_project.menu_no = b.no');
		$this->db->join('sw_user c','sw_project.user_no = c.no');
		$this->db->order_by('sw_project.order','ASC');
		$this->db->order_by('sw_project.no','DESC');
		$this->db->where($where);
		$this->db->like($like);
		$this->db->limit($limit,$offset);

		$query = $this->db->get();
		$result['list'] = $query->result_array();
		return $result;
	}
	public function get_project_detail($option){
		$result = $this->db->get_where('sw_project',$option);
		return $result;
	}
	public function get_project_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_project',$option);
		return $this->db->insert_id();
	}
	public function get_project_update($option,$where){
		$this->db->update('sw_project',$option,$where);
	}
	public function get_project_delete($set_no){
		$this->db->delete('sw_project','no in('.$set_no.')');
		$this->get_project_staff('project_no in('.$set_no.')');
	}

	/* 담당자 */
	public function get_project_staff($where){
		$this->db->delete('sw_project_staff',$where);
	}
}
/* End of file project_model.php */
/* Location: ./models/project_model.php */