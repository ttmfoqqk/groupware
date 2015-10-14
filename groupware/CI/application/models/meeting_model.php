<?
class Meeting_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_meeting_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('meeting.*');
			$this->db->select('IF(meeting.is_active=0,"사용","비사용") as active',FALSE);
			$this->db->select('date_format(meeting.created,"%Y-%m-%d") as created',FALSE);
			$this->db->select('menu.no as menu_no');
			$this->db->select('menu.name as menu_name');
			$this->db->select('user.name as user_name');
			
			$this->db->order_by('meeting.order','ASC');
			$this->db->order_by('meeting.no','DESC');
			$this->db->limit($limit,$offset);
		}
		
		$this->db->from('sw_meeting meeting');
		$this->db->join('sw_menu menu','meeting.menu_no = menu.no');
		$this->db->join('sw_user user','meeting.user_no = user.no');
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

	public function get_meeting_detail($option=NULL,$setVla=array()){
		$this->db->select('meeting.*');
		$this->db->select('date_format(meeting.created,"%Y-%m-%d") as created',false);
		$this->db->select('user.name as user_name');
		$this->db->from('sw_meeting meeting');
		$this->db->join('sw_user user','meeting.user_no = user.no');
		$this->db->order_by('meeting.order','ASC');
		$this->db->order_by('meeting.no','DESC');
		set_options($option);
		
		$query  = $this->db->get();
		$result = set_detail_field($query,$setVla);
		
		return $result;
	}
	public function set_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_meeting',$option);
		return $this->db->insert_id();
	}
	public function set_update($values,$option){
		set_options($option);
		$this->db->update('sw_meeting',$values);
	}
	public function set_delete($option){
		set_options($option);
		$this->db->delete('sw_meeting');
	}
}
/* End of file meeting_model.php */
/* Location: ./models/meeting_model.php */