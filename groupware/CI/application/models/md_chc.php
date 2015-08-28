<?
class Md_chc extends CI_Model{
	private $TABLE_NAME = 'sw_chc';
	
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
	
		$this->db->join('sw_project p', 'c.project_no = p.no', 'left outer');
		$this->db->join('sw_user u', 'c.user_no = u.no', 'left outer');
		$this->db->join('sw_menu m', 'menu_no = m.no', 'left outer');
		$this->db->join('sw_information i', 'c.customer_no = i.no', 'left outer');
		
		$this->db->select('count(*) as total');
		$ret = $this->db->get('sw_chc c')->row();
		return $ret->total;
	}
	
	public function get($where=NULL, $likes=NULL, $offset=NULL, $limit=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		
		if($where!=NULL)
			$this->db->where($where);
		
		$this->db->select('c.no, c.order, c.kind, c.created, m.name as menu_kind, c.title, i.bizName, c.keyword, c.url, p.sData, p.eData, c.rank, u.name as user_name, c.title, p.pPoint, p.mPoint, c.ip');
		$this->db->join('sw_project p', 'c.project_no = p.no', 'left outer');
		$this->db->join('sw_user u', 'c.user_no = u.no', 'left outer');
		$this->db->join('sw_menu m', 'menu_no = m.no', 'left outer');
		$this->db->join('sw_information i', 'c.customer_no = i.no', 'left outer');
		
		$ret = $this->db->get('sw_chc c', $offset, $limit);
		return $ret->result_array();
	}
	
}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
