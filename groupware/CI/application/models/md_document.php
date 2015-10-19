<?
class Md_document extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function get_document_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('document.*');
			$this->db->select('IF(document.is_active=0,"사용","미사용") as active',FALSE);
			$this->db->select('date_format(document.created,"%Y-%m-%d") as created',FALSE);
			$this->db->select('menu.no as menu_no , menu.name as menu_name');
			$this->db->select('user.name as user_name');
			$this->db->order_by('document.order','ASC');
			$this->db->order_by('document.no','DESC');
			$this->db->limit($limit,$offset);
		}
	
		$this->db->from('sw_document as document');
		$this->db->join('sw_user as user', 'document.user_no = user.no', 'left');
		$this->db->join('sw_menu as menu', 'document.menu_no = menu.no', 'left');
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
	
	public function get_document_detail($option=NULL,$setVla=array()){
		$this->db->select('*');
		$this->db->from('sw_document');
		set_options($option);
	
		$query = $this->db->get();
		$result = set_detail_field($query,$setVla);
	
		return $result;
	}
	
	
	public function set_document_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_document',$option);
		return $this->db->insert_id();
	}
	public function set_document_update($values,$option){
		set_options($option);
		$this->db->update('sw_document',$values);
	}
	public function set_document_delete($option){
		set_options($option);
		$this->db->delete('sw_document');
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
		$this->db->join('sw_user u', 'd.user_no = u.no', 'left outer');
		$this->db->join('sw_menu m', 'd.menu_no = m.no', 'left outer');
		
		$this->db->select('count(*) as total');
		$ret = $this->db->get('sw_document d')->row();
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
		
		$this->db->select('d.no, d.order,  m.name as menu_name, d.name,  d.is_active, d.created, u.name as user_name, d.menu_no , d.contents , d.file');
		$this->db->join('sw_user u', 'd.user_no = u.no', 'left outer');
		$this->db->join('sw_menu m', 'd.menu_no = m.no', 'left outer');
		
		$ret = $this->db->get('sw_document d', $offset, $limit);
		return $ret->result_array();
	}
	
}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
