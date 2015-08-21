<?
class Md_object extends CI_Model{
	private $TABLE_NAME = 'sw_object';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getObjectCount($where=NULL, $likes=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		if($where != NULL)
			$this->db->where($where);
	
		$this->db->select('o.no, o.order, m.name as menu_name, o.name, o.area, o.created, u.name as user_name, o.is_active');
		$this->db->join('sw_menu m', 'o.menu_no = m.no', 'left outer');
		$this->db->join('sw_user u', 'o.user_no = u.no', 'left outer');
		
		$this->db->select('count(*) as total');
		$ret = $this->db->get('sw_object o')->row();
		return $ret->total;
	}
	
	public function getObject($where=NULL, $likes=NULL, $offset=NULL, $limit=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		if($where != NULL)
			$this->db->where($where);
		/*
		$this->db->select('h.no, u.name, h.sData, h.eData, h.oData, h.point, h.created, ud.menu_no, m.name as menu_name');
		$this->db->from('sw_object h');
		$this->db->join('sw_user u', 'h.user_no = u.no', 'left outer');
		$this->db->join('sw_user_department ud', 'u.no = ud.user_no', 'left outer');
		$this->db->join('sw_menu m', 'ud.menu_no = m.no', 'left outer');
		*/
		$this->db->select('o.no, o.order, m.name as menu_name, o.name, o.area, o.created, u.name as user_name, o.is_active');
		$this->db->join('sw_menu m', 'o.menu_no = m.no', 'left outer');
		$this->db->join('sw_user u', 'o.user_no = u.no', 'left outer');
		
		$ret = $this->db->get('sw_object o', $offset, $limit);
		return $ret->result_array();
	}
}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
