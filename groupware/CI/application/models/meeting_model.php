<?
class Meeting_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_meeting_list($option=null,$limit=null,$offset=null){
		$where = array();
		foreach($option['where'] as $key=>$val){
			if($val!=''){
				$this->db->where($key, $val);
				$where[$key] = $val;
			}
		}
		$like = array();
		foreach($option['like'] as $key=>$val){
			if($val!=''){
				$like[$key] = $val;
			}
		}

		$this->db->select('count(*) as total');
		$this->db->from('sw_meeting meeting');
		$this->db->join('sw_menu menu','meeting.menu_no = menu.no');
		$this->db->join('sw_user user','meeting.user_no = user.no');
		$this->db->where($where);
		$this->db->like($like);
		$query = $this->db->get();
		$query = $query->row();
		$result['total'] = $query->total;

		$this->db->select('meeting.*');
		$this->db->select('menu.name as menu_name');
		$this->db->select('user.name as user_name');
		$this->db->from('sw_meeting meeting');
		$this->db->join('sw_menu menu','meeting.menu_no = menu.no');
		$this->db->join('sw_user user','meeting.user_no = user.no');
		$this->db->order_by('meeting.order','ASC');
		$this->db->order_by('meeting.no','DESC');
		$this->db->where($where);
		$this->db->like($like);
		$this->db->limit($limit,$offset);

		$query = $this->db->get();
		$result['list'] = $query->result_array();
		return $result;
	}
	public function get_meeting_detail($option){
		$this->db->select('meeting.*');
		$this->db->select('user.name as user_name');
		$this->db->from('sw_meeting meeting');
		$this->db->join('sw_user user','meeting.user_no = user.no');
		$this->db->order_by('meeting.order','ASC');
		$this->db->order_by('meeting.no','DESC');
		$this->db->where($option);
		$result = $this->db->get();
		return $result;
	}
	public function get_meeting_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_meeting',$option);
		return $this->db->insert_id();
	}
	public function get_meeting_update($option,$where){
		$this->db->update('sw_meeting',$option,$where);
	}
	public function get_meeting_delete($set_no){
		$this->db->delete('sw_meeting','no in('.$set_no.')');
	}
}
/* End of file meeting_model.php */
/* Location: ./models/meeting_model.php */