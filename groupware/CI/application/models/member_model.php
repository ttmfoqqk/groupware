<?
class Member_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function encryp($passwd){
		$salt   = $this->config->item('encryption_key');
		$string = $passwd . $salt;
		for($i=0;$i<10;$i++) {
			$string = hash('sha512',$string . $passwd . $salt);
		}
		return $string;
	}

	public function get_login($option){
		set_options($option);
		$this->db->where('is_active',0);
		$result = $this->db->get('sw_user',1);
		return $result;
	}
	
	
	
	

	public function get_user_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('*');
			$this->db->select('IF(is_active=0,"재직","퇴사") as active',FALSE);
			$this->db->select('date_format(sDate,"%Y-%m-%d") as sDate',FALSE);
			$this->db->select('date_format(eDate,"%Y-%m-%d") as eDate',FALSE);
			$this->db->select('date_format(birth,"%Y-%m-%d") as birth',FALSE);
			$this->db->select('date_format(created,"%Y-%m-%d") as created',FALSE);
			
			$this->db->order_by('order','ASC');
			$this->db->order_by('no','DESC');
			$this->db->limit($limit,$offset);
		}

		$this->db->from('sw_user');
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
	
	public function get_user_detail($option=NULL,$setVla=array()){
		$this->db->select('*');
		$this->db->from('sw_user');
		set_options($option);
	
		$query = $this->db->get();
		$result = set_detail_field($query,$setVla);
	
		return $result;
	}
	
	
	public function set_user_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_user',$option);
		return $this->db->insert_id();
	}
	public function set_user_update($values,$option){
		set_options($option);
		$this->db->update('sw_user',$values);
	}
	public function set_user_delete($option){
		set_options($option);
		$this->db->delete('sw_user');
	}
	
	
	
	
	
	/* 사원->부서 */
	public function get_department_list($option){
		$this->db->from('sw_user_department');
		$this->db->where($option);
		$this->db->order_by('order','ASC');
		$this->db->order_by('position','ASC');
	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function set_department_delete($option){
		$this->db->delete('sw_user_department',$option);
	}
	public function set_department_insert($option,$staff){
		$this->set_department_delete($staff);
		$this->db->insert_batch('sw_user_department',$option);
	}
	/* 사원->연차 */
	public function get_annual_count($option){
		// 사용가능한,사용한 연차 카운트
		$this->db->select('A.annual,count(B.user_no) as use_cnt');
		$this->db->from('sw_user as A');
		$this->db->join('sw_user_annual as B','A.no = B.user_no and date_format(data,"%Y") = date_format(now(),"%Y")','left');
		$this->db->where($option);
		$this->db->group_by('A.no');
		$query = $this->db->get();
		return $query;
	}
	
	public function get_no_list($option){
		// 등록된 업무 일자 리스트
		$this->db->select('date_format(A.sData,"%Y-%m-%d") as sData,date_format(A.eData,"%Y-%m-%d") as eData',false);
		$this->db->from('sw_project as A');
		$this->db->join('sw_project_staff as B','A.no = B.project_no');
		$this->db->where($option);
		$this->db->group_by('A.sData,A.eData');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	public function get_annual_list($option){
		$this->db->from('sw_user_annual');
		$this->db->where($option);
		$this->db->order_by('order','ASC');
		$this->db->order_by('data','DESC');
	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function set_annual_delete($option){
		$this->db->delete('sw_user_annual',$option);
	}
	public function set_annual_insert($option,$staff){
		$this->set_annual_delete($staff);
		$this->db->insert_batch('sw_user_annual',$option);
	}
	/* 사원->권한 */
	public function get_permission_list($option=null,$user_no){
		$this->db->select('p.*, u.permission as u_permission');
		$this->db->from('sw_permission p');
		$this->db->join('sw_user_permission u', 'p.category = u.category and u.user_no="'.$user_no.'"', 'left');
		$this->db->where($option);
		$this->db->order_by('order','ASC');
		$this->db->order_by('category','ASC');
	
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function set_permission_delete($option){
		$this->db->delete('sw_user_permission',$option);
	}
	public function set_permission_insert($option=null,$staff){
		$this->set_permission_delete($staff);
		$this->db->insert_batch('sw_user_permission',$option);
	}
	
	
	
	public function getUsersByDepartment($dptNo){
		$this->db->select('u.no, u.name');
		$this->db->join('sw_user u', 'ud.user_no = u.no', 'left');
		$this->db->where('ud.menu_no', $dptNo);
		$this->db->join('sw_menu m', 'ud.menu_no = m.no', 'left');
	
		$ret = $this->db->get('sw_user_department ud');
		if (count($ret) > 0){
			return json_encode($ret->result_array());
		}else
			return json_encode(array());
	}
	

}
/* End of file member_model.php */
/* Location: ./models/member_model.php */