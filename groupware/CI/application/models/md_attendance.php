<?
class Md_attendance extends CI_Model{
	private $TABLE_NAME = 'sw_attendance_history';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function attendance_history_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('h.no, u.name, h.sData, h.eData, h.oData, h.point, h.created, ud.menu_no, m.name as menu_name');
			$this->db->order_by('h.no','DESC');
			$this->db->limit($limit,$offset);
		}

		$this->db->from('sw_attendance_history h');
		$this->db->join('sw_user u', 'h.user_no = u.no', 'left outer');
		$this->db->join('(select * from (SELECT * FROM sw_user_department order by `order`) AS A group by user_no) as ud', 'u.no = ud.user_no', 'left outer');
		$this->db->join('sw_menu m', 'ud.menu_no = m.no', 'left outer');
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
	 * 누적 지각,업무시간 
	 */
	public function attendance_history_sum($option=NULL,$setVla=array()){
		$this->db->select('TIME_FORMAT( SEC_TO_TIME( SUM( TIME_TO_SEC( `oData` ) ) ) ,"%H시간 %i분 %s초") AS late_time',FALSE);
		$this->db->select('TIME_FORMAT( SEC_TO_TIME( sum( TIMESTAMPDIFF(SECOND, sData, eData ) ) ) ,"%H시간 %i분 %s초") working_time',FALSE);
		$this->db->from('sw_attendance_history');
		$this->db->order_by('no','ASC');
		set_options($option);
		
		$query = $this->db->get();
		$result = set_detail_field($query,$setVla);
	
		return $result;
	}
	
	public function set_attendance_update($values,$option){
		set_options($option);
		$this->db->update('sw_attendance',$values);
	}
	
	
	public function get_temp_baseCode($option=NULL){
		$this->db->select('name');
		$this->db->from('sw_base_code');
		set_options($option);
	
		$query = $this->db->get();
		$result = set_detail_field($query);
	
		return $result;
	}
	
	
	
	
	public function attendance_list(){
		$this->db->select('*');
		$this->db->from('sw_attendance');
		$this->db->order_by('no','ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		
		return $result;
	}
	
	
	
	public function getAttendanceCount($where=NULL, $likes=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		if($where != NULL)
			$this->db->where($where);
	
		//$this->db->select('h.no, u.name, h.sData, h.eData, h.oData, h.point, h.created, ud.menu_no, m.name as menu_name');
		$this->db->join('sw_user u', 'h.user_no = u.no', 'left outer');
		$this->db->join('(select * from (SELECT * FROM sw_user_department order by `order`) AS A group by user_no) as ud', 'u.no = ud.user_no', 'left outer');
		$this->db->join('sw_menu m', 'ud.menu_no = m.no', 'left outer');
		
		$this->db->select('count(*) as total');
		$ret = $this->db->get('sw_attendance_history h')->row();
		return $ret->total;
	}
	
	public function getAttendance($where=NULL, $likes=NULL, $offset=NULL, $limit=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		if($where != NULL)
			$this->db->where($where);
		
		$this->db->select('h.no, u.name, h.sData, h.eData, h.oData, h.point, h.created, ud.menu_no, m.name as menu_name');
		$this->db->join('sw_user u', 'h.user_no = u.no', 'left outer');
		$this->db->join('(select * from (SELECT * FROM sw_user_department order by `order`) AS A group by user_no) as ud', 'u.no = ud.user_no', 'left outer');
		$this->db->join('sw_menu m', 'ud.menu_no = m.no', 'left outer');
		
		$ret = $this->db->get('sw_attendance_history h', $offset, $limit);
		return $ret->result_array();
	}
	
}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
