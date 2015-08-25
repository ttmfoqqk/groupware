<?
class Approved_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_approved_list($option=null,$limit=null,$offset=null){
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
		$this->db->from('sw_approved AS approved');
		$this->db->join('(select a.*,b.no as project_menu_no,b.name as project_menu from sw_project a join sw_menu b on a.menu_no=b.no) AS project','approved.project_no = project.no','left');
		$this->db->join('(select a.*,b.no as document_menu_no,b.name as document_menu from sw_document a join sw_menu b on a.menu_no=b.no) AS document','approved.project_no = document.no','left');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no','left');
		$this->db->join('sw_user AS user_sender','status.sender = user_sender.no','left');
		$this->db->join('sw_user AS user_receiver','status.receiver = user_receiver.no','left');
		$this->db->join('sw_user AS user_default','approved.user_no = user_default.no','left');
		$this->db->where($where);
		$this->db->where($option['cus_where']);
		$this->db->like($like);
		$query = $this->db->get();
		$query = $query->row();
		$result['total'] = $query->total;


		$this->db->select('*');
		$this->db->select('approved.no as approved_no, approved.order as orders, approved.created as createds ,user_default.name as user_default');
		$this->db->from('sw_approved AS approved');
		$this->db->join('(select a.*,b.no as project_menu_no,b.name as project_menu from sw_project a join sw_menu b on a.menu_no=b.no) AS project','approved.project_no = project.no','left');
		$this->db->join('(select a.*,b.no as document_menu_no,b.name as document_menu from sw_document a join sw_menu b on a.menu_no=b.no) AS document','approved.project_no = document.no','left');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no','left');
		$this->db->join('sw_user AS user_sender','status.sender = user_sender.no','left');
		$this->db->join('sw_user AS user_receiver','status.receiver = user_receiver.no','left');
		$this->db->join('sw_user AS user_default','approved.user_no = user_default.no','left');
		$this->db->order_by('approved.order','ASC');
		$this->db->order_by('approved.no','DESC');
		$this->db->where($where);
		$this->db->where($option['cus_where']);
		$this->db->like($like);		
		$this->db->limit($limit,$offset);

		$query = $this->db->get();
		$result['list'] = $query->result_array();
		return $result;
	}
	public function get_approved_detail($option){
		$this->db->select('*, approved.no as no , approved.order as approved_order ,approved.menu_no as menu_no , approved.project_no as project_no');

		$this->db->select('project.title as project_title , project.sData as project_sData , project.eData as project_eData , project.contents as project_contents ');
		$this->db->select('project.file as project_file');

		$this->db->select('document.name as document_title');
		$this->db->select('department.name as department');
		$this->db->select('project_menu.name as project_menu_name');
		$this->db->select('approved_contents.contents as approved_contents');
		$this->db->from('sw_approved as approved');
		$this->db->join('sw_approved_contents AS approved_contents','approved.no = approved_contents.approved_no','left');
		$this->db->join('sw_menu AS department','approved.menu_no = department.no');
		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('sw_document AS document','approved.project_no = document.no','left');

		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no');

		$result = $this->db->get_where('sw_approved',$option);
		return $result;
	}

	public function set_approved_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_approved',$option);
		return $this->db->insert_id();
	}
	public function set_approved_update($option,$where){
		$this->db->update('sw_approved',$option,$where);
	}
	public function set_approved_delete($set_no){
		$this->db->delete('sw_approved','no in('.$set_no.')');
		$this->set_approved_staff_delete('approved_no in('.$set_no.')');
	}

	public function set_approved_contents_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_approved_contents',$option);
		return $this->db->insert_id();
	}

	/* 담당자 */
	public function get_approved_staff_list($option){
		$this->db->order_by('order','ASC');
		$query  = $this->db->get_where('sw_approved_status',$option);
		$result = $query->result_array();
		return $result;
	}

	public function set_approved_staff_delete($option){
		$this->db->delete('sw_approved_status',$option);
	}

	public function set_approved_staff_insert($option,$staff){
		$this->set_approved_staff_delete($staff);
		$this->db->insert_batch('sw_approved_status',$option);
	}
}
/* End of file approved_model.php */
/* Location: ./models/approved_model.php */