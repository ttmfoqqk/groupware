<?
class Project_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_project_list($option=null,$limit=null,$offset=null){
		$where = array();
		foreach($option['where'] as $key=>$val){
			if($val!=''){
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
		$this->db->join('sw_project_staff d','sw_project.no = d.project_no and d.order=1','left');
		$this->db->join('sw_menu d1','d.menu_no = d1.no','left');
		$this->db->join('sw_user d2','d.user_no = d2.no','left');
		$this->db->where($where);
		$this->db->like($like);
		$query = $this->db->get();
		$query = $query->row();
		$result['total'] = $query->total;


		$this->db->select('sw_project.*');
		$this->db->select('a.name as part_name');
		$this->db->select('b.name as menu_name');
		$this->db->select('c.name as user_name');
		$this->db->select('d.user_no as staff_no');
		$this->db->select('d.menu_no as staff_menu_no');
		$this->db->select('d1.name as staff_menu_name');
		$this->db->select('d2.name as staff_name');
		$this->db->select('checks.cnt');
		$this->db->from('sw_project');
		$this->db->join('sw_menu a','sw_project.menu_part_no = a.no');
		$this->db->join('sw_menu b','sw_project.menu_no = b.no');
		$this->db->join('sw_user c','sw_project.user_no = c.no');
		$this->db->join('sw_project_staff d','sw_project.no = d.project_no and d.order=1','left');
		$this->db->join('sw_menu d1','d.menu_no = d1.no','left');
		$this->db->join('sw_user d2','d.user_no = d2.no','left');
		$this->db->join('(select B1.project_no as project_no,count(*) AS cnt from sw_approved as B1 join sw_approved_status as B2 on( B1.no = B2.approved_no) where B1.kind = 0 and B2.order > 1 and B2.status is not null group by B1.project_no) AS checks','sw_project.no = checks.project_no','left');
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
		$this->db->select('sw_project.*');
		$this->db->select('checks.cnt');
		$this->db->from('sw_project');
		$this->db->join('(select B1.project_no as project_no,count(*) AS cnt from sw_approved as B1 join sw_approved_status as B2 on( B1.no = B2.approved_no) where B1.kind = 0 and B2.order > 1 and B2.status is not null group by B1.project_no) AS checks','sw_project.no = checks.project_no','left');
		$this->db->where($option);
		$result = $this->db->get();
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
		$this->set_project_staff_delete('project_no in('.$set_no.')');
	}

	/* 담당자 */
	public function get_project_staff_list($option){
		$this->db->select('staff.*');
		$this->db->select('menu.name as menu_name');
		$this->db->select('user.name as user_name');
		$this->db->from('sw_project_staff as staff');
		$this->db->join('sw_menu menu','staff.menu_no = menu.no');
		$this->db->join('sw_user user','staff.user_no = user.no');
		$this->db->where($option);
		$this->db->order_by('staff.order','ASC');
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function set_project_staff_delete($option){
		$this->db->delete('sw_project_staff',$option);
	}

	public function set_project_staff_insert($option,$staff){
		$this->set_project_staff_delete($staff);
		$this->db->insert_batch('sw_project_staff',$option);
	}
	
	
	
	public function get_schedule($option=NULL){
		$where = array();
		foreach($option['where'] as $key=>$val){
			if($val!=''){
				$where[$key] = $val;
			}
		}
		$like = array();
		foreach($option['like'] as $key=>$val){
			if($val!=''){
				$like[$key] = $val;
			}
		}
		
		
		
		$this->db->select('*');
		$this->db->from('sw_user');
		$this->db->order_by('order','ASC');
		$query = $this->db->get();
		$result['user'] = $query->result_array();
		
		
		$this->db->select('project.title');
		$this->db->select('project.sData');
		$this->db->select('project.eData');
		$this->db->select('user.id');
		$this->db->select('user.name');
		$this->db->select('user.color');
		$this->db->from('sw_project AS project');
		$this->db->join('sw_project_staff AS staff','project.no = staff.project_no');
		$this->db->join('sw_user AS user','staff.user_no = user.no');
		$this->db->order_by('user.no','ASC');
		$this->db->where($where);
		$this->db->like($like);
		$query = $this->db->get();
		$result['list'] = $query->result_array();
		return $result;
	}
}
/* End of file project_model.php */
/* Location: ./models/project_model.php */