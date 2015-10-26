<?
class Approved_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	/*
		보관함 TEST 리스트
	*/
	public function approved_archive_list($option=null,$limit=null,$offset=null,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('approved.*');
			$this->db->select('date_format(approved.created,"%Y-%m-%d") as created',FALSE);
			$this->db->select('IF( approved.kind = 0, project_menu.name , document_menu.name ) as menu_name ');
			$this->db->select('IF( approved.kind = 0, project_menu.no , document_menu.no ) as menu_no');
			$this->db->select('IF( approved.kind = 0, project.title , approved.title ) as title ');
			$this->db->select('IF( approved.kind = 0, date_format(project.sData,"%Y-%m-%m") , date_format(approved.sData,"%Y-%m-%d") ) as sData ',FALSE);
			$this->db->select('IF( approved.kind = 0, date_format(project.eData,"%Y-%m-%m") , date_format(approved.eData,"%Y-%m-%d") ) as eData ',FALSE);
			$this->db->select('project.pPoint,project.mPoint');
			$this->db->select('user.name as user_name ');
			
			$this->db->order_by('approved.order','ASC');
			$this->db->order_by('approved.no','DESC');
			$this->db->limit($limit,$offset);
		}

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

	/*
		보관함 TEST 상세
	*/

	public function approved_archive_detail($option=NULL,$setVla=array()){

		$this->db->select('approved.*');
		$this->db->select('date_format(approved.created,"%Y-%m-%d") as created',false);
		$this->db->select('contents.contents as contents');
		$this->db->select('user.name as user_name ');
		$this->db->select('user_menu.name as part_name ');
		
		$this->db->select('IF( approved.kind = 0, project_menu.name , document_menu.name ) as category_name ');
		$this->db->select('IF( approved.kind = 0, project.title , approved.title ) as title ');
		$this->db->select('IF( approved.kind = 0, date_format(project.sData,"%Y-%m-%d") , date_format(approved.sData,"%Y-%m-%d") ) as sData ',false);
		$this->db->select('IF( approved.kind = 0, date_format(project.eData,"%Y-%m-%d") , date_format(approved.eData,"%Y-%m-%d") ) as eData ',false);
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

		set_options($option);
		
		$query  = $this->db->get();
		$result = set_detail_field($query,$setVla);
		return $result;
	}

	/*
		보낸 결재 리스트
		-> 받은결재 공통작업?
	*/
	public function approved_send_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('approved.*');
			$this->db->select('date_format(approved.created,"%Y-%m-%d") as created',false);
			$this->db->select('user.name as user_name');
			$this->db->select('IF( approved.kind = 0, project_menu.no , document_menu.no ) as menu_no ');
			$this->db->select('IF( approved.kind = 0, project_menu.name , document_menu.name ) as menu_name ');
			$this->db->select('IF( approved.kind = 0, project.title , approved.title ) as title ');
			$this->db->select('IF( approved.kind = 0, date_format(project.sData,"%Y-%m-%d") , date_format(approved.sData,"%Y-%m-%d") ) as sData ',false);
			$this->db->select('IF( approved.kind = 0, date_format(project.eData,"%Y-%m-%d") , date_format(approved.eData,"%Y-%m-%d") ) as eData ',false);
			$this->db->select('project.pPoint,project.mPoint');
			
			$this->db->order_by('approved.order','ASC');
			$this->db->order_by('approved.no','DESC');
			$this->db->limit($limit,$offset);
		}
		
		$this->db->from('sw_approved AS approved');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no');
		$this->db->join('sw_user AS user','approved.user_no = user.no');
		$this->db->join('(select a.*,b.name as receiver_name from sw_approved_status as a join sw_user as b on a.receiver = b.no group by approved_no) AS rrr','approved.no = rrr.approved_no');
		$this->db->join('(select a.*,b.name as sender_name from sw_approved_status as a join sw_user as b on a.sender = b.no group by approved_no) AS sss','approved.no = sss.approved_no');
		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no','left');
		$this->db->join('sw_document AS document','approved.project_no = document.no','left');
		$this->db->join('sw_menu AS document_menu','document.menu_no = document_menu.no','left');
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
	
	/*
		보낸/받은결재 공통 디테일?
	*/
	public function approved_send_detail($option=NULL,$setVla=array()){
		$where = array();
		foreach($option as $key=>$val){
			if($val!=''){
				$where[$key] = $val;
			}
		}

		$this->db->select('approved.*');
		$this->db->select('date_format(approved.created,"%Y-%m-%d") as created',false);
		$this->db->select('IF( approved.kind = 0, project_menu.name , document_menu.name ) as menu_name ');
		$this->db->select('IF( approved.kind = 0, project.title , approved.title ) as title ');
		$this->db->select('IF( approved.kind = 0, date_format(project.sData,"%Y-%m-%d") , date_format(approved.sData,"%Y-%m-%d") ) as sData ',false);
		$this->db->select('IF( approved.kind = 0, date_format(project.eData,"%Y-%m-%d") , date_format(approved.eData,"%Y-%m-%d") ) as eData ',false);
		$this->db->select('IF( approved.kind = 0, project.file  , approved.file  ) as file  ');
		$this->db->select('project.pPoint,project.mPoint,project.contents as p_contents');
		$this->db->select('document.name as document_name');
		$this->db->select('sender_part.name as sender_part');
		$this->db->select('sender_name.name as sender_name');
		$this->db->select('sender_contents.contents as sender_contents');
		$this->db->select('receiver_contents.contents as receiver_contents');
		$this->db->select('status.status as status');
		$this->db->select('status.created as status_created');

		$this->db->from('sw_approved as approved');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no');
		$this->db->join('sw_menu AS sender_part','approved.menu_no = sender_part.no');
		$this->db->join('sw_user AS sender_name','approved.user_no = sender_name.no');
		$this->db->join('sw_approved_contents AS sender_contents','approved.no = sender_contents.approved_no and status.sender = sender_contents.user_no','left');
		$this->db->join('sw_approved_contents AS receiver_contents','approved.no = receiver_contents.approved_no and status.receiver = receiver_contents.user_no','left');
		$this->db->join('sw_project AS project','approved.project_no = project.no','left');
		$this->db->join('sw_menu AS project_menu','project.menu_no = project_menu.no','left');
		$this->db->join('sw_document AS document','approved.project_no = document.no','left');
		$this->db->join('sw_menu AS document_menu','document.menu_no = document_menu.no','left');
		set_options($option);
		
		$query  = $this->db->get();
		$result = set_detail_field($query,$setVla);
		
		return $result;
	}

	/*
	 * 결재 카운트 - 중복제거용
	 */
	public function get_check($no){
		$this->db->select('count(*) as count');
		$this->db->from('sw_approved A');
		$this->db->join('sw_approved B','A.kind = b.kind and A.project_no = B.project_no');
		$this->db->join('sw_approved_status C','A.no = C.approved_no');
		$this->db->where('B.no',$no);
		$this->db->where('C.created >','date_format(now(),"%Y-%m-%d")',false);
		$this->db->where('C.sender',$this->session->userdata('no'));
		$query = $this->db->get();
		$query = $query->row();
		return $query->count;
	}
	
	
	/**
	 * 컨텐츠 입력/수정 
	 * @param unknown $option
	 */
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

}
/* End of file approved_model.php */
/* Location: ./models/approved_model.php */