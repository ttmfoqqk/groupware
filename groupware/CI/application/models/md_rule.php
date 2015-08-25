<?
class Md_rule extends CI_Model{
	private $TABLE_NAME = 'sw_rule';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getCount($where=NULL, $likes=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		if($where != NULL)
			$this->db->where($where);
	
		//$this->db->select('h.no, u.name, h.sData, h.eData, h.oData, h.point, h.created, ud.menu_no, m.name as menu_name');
		$this->db->join('sw_user u', 'r.user_no = u.no', 'left outer');
		$this->db->join('sw_menu m', 'r.menu_no = m.no', 'left outer');
		
		$this->db->select('count(*) as total');
		$ret = $this->db->get('sw_rule r')->row();
		return $ret->total;
	}
	
	public function get($where=NULL, $likes=NULL, $offset=NULL, $limit=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		if($where != NULL)
			$this->db->where($where);
		
		$this->db->select('r.no, r.order,  m.name as menu_name, r.name, r.operator, r.point,   r.is_active, r.created, u.name as user_name, r.menu_no');
		$this->db->join('sw_user u', 'r.user_no = u.no', 'left outer');
		$this->db->join('sw_menu m', 'r.menu_no = m.no', 'left outer');
		
		$ret = $this->db->get('sw_rule r', $offset, $limit);
		return $ret->result_array();
	}
	
}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
