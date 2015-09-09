<?
class Md_attendance extends CI_Model{
	private $TABLE_NAME = 'sw_attendance_history';
	
	public function __construct(){
		parent::__construct();
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
