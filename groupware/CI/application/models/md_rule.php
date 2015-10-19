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
	
	public function get_rule_detail($option=NULL,$setVla=array()){
		$this->db->select('*');
		$this->db->from('sw_rule');
		set_options($option);
	
		$query = $this->db->get();
		$result = set_detail_field($query,$setVla);
	
		return $result;
	}
	
	
	public function set_rule_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_rule',$option);
		return $this->db->insert_id();
	}
	public function set_rule_update($values,$option){
		set_options($option);
		$this->db->update('sw_rule',$values);
	}
	public function set_rule_delete($option){
		set_options($option);
		$this->db->delete('sw_rule');
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
