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

}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
