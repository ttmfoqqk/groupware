<?
class Approved_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	/*
		보관함 TEST 리스트
	*/
	public function approved_archive_list($option=null,$limit=null,$offset=null){
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
		$this->db->join('sw_user AS user','approved.user_no = user.no');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no','left');

		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('(select * from sw_project_staff group by project_no) AS project_staff','project.no = project_staff.project_no','left');
		$this->db->join('sw_user AS project_user','project_staff.user_no = project_user.no','left');
		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no','left');

		$this->db->join('sw_document AS document','approved.project_no = document.no','left');
		$this->db->join('(select * from sw_document_staff group by approved_no) AS document_staff','document.no = document_staff.approved_no','left');
		$this->db->join('sw_user AS document_user','document_staff.user_no = document_user.no','left');
		$this->db->join('sw_menu AS document_menu','document.menu_no = document_menu.no','left');
		
		$this->db->where('status.approved_no is null');
		$this->db->where($where);
		$this->db->like($like);
		//$this->db->group_by('project_staff.project_no,document_staff.approved_no');

		$query = $this->db->get();
		$query = $query->row();
		$result['total'] = $query->total;


		$this->db->select('approved.*');
		$this->db->select('IF( approved.kind = 0, project_menu.name , document_menu.name ) as menu_name ');
		$this->db->select('IF( approved.kind = 0, project.title , approved.title ) as title ');
		$this->db->select('IF( approved.kind = 0, project.sData , approved.sData ) as sData ');
		$this->db->select('IF( approved.kind = 0, project.eData , approved.eData ) as eData ');
		$this->db->select('project.pPoint,project.mPoint');
		
		$this->db->select('user.name as user_name ');

		$this->db->from('sw_approved AS approved');
		$this->db->join('sw_user AS user','approved.user_no = user.no');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no','left');

		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('(select * from sw_project_staff group by project_no) AS project_staff','project.no = project_staff.project_no','left');
		$this->db->join('sw_user AS project_user','project_staff.user_no = project_user.no','left');
		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no','left');

		$this->db->join('sw_document AS document','approved.project_no = document.no','left');
		$this->db->join('(select * from sw_document_staff group by approved_no) AS document_staff','document.no = document_staff.approved_no','left');
		$this->db->join('sw_user AS document_user','document_staff.user_no = document_user.no','left');
		$this->db->join('sw_menu AS document_menu','document.menu_no = document_menu.no','left');
		
		$this->db->where('status.approved_no is null');
		$this->db->where($where);
		$this->db->like($like);
		
		
		$this->db->order_by('approved.order','ASC');
		$this->db->order_by('approved.no','DESC');
		
		$this->db->limit($limit,$offset);

		$query = $this->db->get();
		$result['list'] = $query->result_array();
		return $result;
	}

	/*
		보관함 TEST 상세
	*/

	public function approved_archive_detail($option){
		
		foreach($option as $key=>$val){
			if($val!=''){
				$option[$key] = $val;
			}
		}

		$this->db->select('approved.*');
		$this->db->select('contents.contents as contents');
		$this->db->select('user.name as user_name ');
		$this->db->select('user_menu.name as part_name ');
		
		$this->db->select('IF( approved.kind = 0, project_menu.name , document_menu.name ) as category_name ');
		$this->db->select('IF( approved.kind = 0, project.title , approved.title ) as title ');
		$this->db->select('IF( approved.kind = 0, project.sData , approved.sData ) as sData ');
		$this->db->select('IF( approved.kind = 0, project.eData , approved.eData ) as eData ');
		$this->db->select('IF( approved.kind = 0, project.file  , approved.file )  as file ');
		$this->db->select('project.pPoint,project.mPoint,project.contents as p_contents');
		
		$this->db->select('contents.contents as contents');
		$this->db->select('document.name as document_name');

		$this->db->from('sw_approved as approved');
		$this->db->join('sw_user AS user','approved.user_no = user.no');
		$this->db->join('sw_menu AS user_menu','approved.menu_no = user_menu.no','left');
		$this->db->join('sw_approved_contents AS contents','approved.no = contents.approved_no and approved.user_no = contents.user_no','left');

		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no','left');

		$this->db->join('sw_document AS document','approved.project_no = document.no','left');
		$this->db->join('sw_menu AS document_menu','document.menu_no = document.no','left');

		$this->db->where($option);
		$result = $this->db->get();
		return $result;
	}

	/*
		보낸 결재 리스트
	*/
	public function approved_send_list($option=null,$limit=null,$offset=null){
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
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no');
		$this->db->join('(select a.*,b.name as receiver_name from sw_approved_status as a join sw_user as b on a.receiver = b.no group by approved_no) AS rrr','approved.no = rrr.approved_no');
		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no','left');
		$this->db->join('sw_document AS document','approved.project_no = document.no','left');
		$this->db->join('sw_menu AS document_menu','document.menu_no = document_menu.no','left');
		$this->db->where($where);
		$this->db->like($like);

		$query = $this->db->get();
		$query = $query->row();
		$result['total'] = $query->total;

		$this->db->select('approved.*');
		$this->db->select('IF( approved.kind = 0, project_menu.name , document_menu.name ) as menu_name ');
		$this->db->select('IF( approved.kind = 0, project.title , approved.title ) as title ');
		$this->db->select('IF( approved.kind = 0, project.sData , approved.sData ) as sData ');
		$this->db->select('IF( approved.kind = 0, project.eData , approved.eData ) as eData ');
		$this->db->select('project.pPoint,project.mPoint');
		$this->db->from('sw_approved AS approved');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no');
		$this->db->join('(select a.*,b.name as receiver_name from sw_approved_status as a join sw_user as b on a.receiver = b.no group by approved_no) AS rrr','approved.no = rrr.approved_no');
		
		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no','left');
		$this->db->join('sw_document AS document','approved.project_no = document.no','left');
		$this->db->join('sw_menu AS document_menu','document.menu_no = document_menu.no','left');
		$this->db->where($where);
		$this->db->like($like);
		$this->db->order_by('approved.order','ASC');
		$this->db->order_by('approved.no','DESC');
		$this->db->limit($limit,$offset);

		$query = $this->db->get();
		$result['list'] = $query->result_array();
		return $result;
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
		$this->db->select('status.status');
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
	public function get_approved_detail($option=null){
		
		foreach($option as $key=>$val){
			if($val!=''){
				$option[$key] = $val;
			}
		}

		$this->db->select('approved.*');
		$this->db->select('approved_contents.contents as approved_contents');

		$this->db->select('project.title as project_title');
		$this->db->select('project.sData as project_sData , project.eData as project_eData');
		$this->db->select('project.contents as project_contents ');
		$this->db->select('project.pPoint, project.mPoint');
		$this->db->select('project.file as project_file');

		$this->db->select('department.name as department');
		$this->db->select('project_menu.name as project_menu_name');

		$this->db->select('document.name as document_title');

		$this->db->select('sender_department.name as sender_department');
		$this->db->select('sender_name.name as sender_name');

		$this->db->select('status.status as status');

		$this->db->from('sw_approved as approved');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no','left');
		$this->db->join('sw_menu AS sender_department','status.sender = sender_department.no','left');
		$this->db->join('sw_user AS sender_name','status.sender = sender_name.no','left');

		$this->db->join('sw_menu AS department','approved.menu_no = department.no','left');
		$this->db->join('sw_approved_contents AS approved_contents','approved.no = approved_contents.approved_no and status.sender = approved_contents.user_no','left');
		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('sw_document AS document','approved.project_no = document.no','left');
		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no','left');
		$this->db->where($option);
		
		$result = $this->db->get();
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
		$this->set_approved_contents_delete('approved_no in('.$set_no.')');
	}
	
	/* 내용 */
	public function set_approved_contents_insert($option){

		$this->db->select('count(*) as total');
		$this->db->from('sw_approved_contents');
		$this->db->where(array('approved_no'=>$option['approved_no'],'user_no'=>$option['user_no']));
		$query = $this->db->get();
		$query = $query->row();
		$total = $query->total;
		
		if($total < 1){
			$this->db->set('created', 'NOW()', false);
			$this->db->insert('sw_approved_contents',$option);
			return $this->db->insert_id();
		}else{
			$this->db->update('sw_approved_contents', array('contents'=>$option['contents']) , array('approved_no'=>$option['approved_no'],'user_no'=>$option['user_no']) );
		}
	}
	public function set_approved_contents_delete($option){
		$this->db->delete('sw_approved_contents',$option);
	}

	/* 담당자 */
	public function get_approved_staff_list($option){
		$this->db->select('status.* , user.name as user_name');
		$this->db->from('sw_approved_status as status');
		$this->db->join('sw_user AS user','status.receiver = user.no');
		$this->db->where($option);
		$this->db->order_by('order','ASC');

		$query  = $this->db->get();
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
	public function set_approved_staff_update($option,$where){
		$this->db->update('sw_approved_status',$option,$where);
	}

	
	/* 내용 */
	public function get_approved_contents_list($option){
		$this->db->select('contents.* , user.name as user_name');
		$this->db->from('sw_approved_contents as contents');
		$this->db->join('sw_user AS user','contents.user_no = user.no');
		$this->db->where($option);
		$this->db->order_by('created','ASC');

		$query  = $this->db->get();
		$result = $query->result_array();
		return $result;
	}


	/* 일반업무 담당자 임시 테이블 */
	public function temp_document_staff_list($option){
		$this->db->select('staff.*');
		$this->db->select('menu.name as menu_name');
		$this->db->select('user.name as user_name');
		$this->db->from('sw_document_staff as staff');
		$this->db->join('sw_menu menu','staff.menu_no = menu.no');
		$this->db->join('sw_user user','staff.user_no = user.no');
		$this->db->where($option);
		$this->db->order_by('staff.order','ASC');
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function temp_document_staff_delete($option){
		$this->db->delete('sw_document_staff',$option);
	}

	public function temp_document_staff_insert($option,$staff){
		$this->temp_document_staff_delete($staff);
		$this->db->insert_batch('sw_document_staff',$option);
	}
}
/* End of file approved_model.php */
/* Location: ./models/approved_model.php */