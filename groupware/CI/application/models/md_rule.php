<?
class Md_rule extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function get_rule_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('rule.*');
			$this->db->select('IF(rule.operator=0,"+","-") as operator',FALSE);
			$this->db->select('IF(rule.is_active=0,"사용","미사용") as active',FALSE);
			$this->db->select('date_format(rule.created,"%Y-%m-%d") as created',FALSE);
			$this->db->select('menu.no as menu_no , menu.name as menu_name');
			$this->db->select('user.name as user_name');
			$this->db->order_by('rule.order','ASC');
			$this->db->order_by('rule.no','DESC');
			$this->db->limit($limit,$offset);
		}
	
		$this->db->from('sw_rule as rule');
		$this->db->join('sw_user as user', 'rule.user_no = user.no', 'left');
		$this->db->join('sw_menu as menu', 'rule.menu_no = menu.no', 'left');
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
}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
