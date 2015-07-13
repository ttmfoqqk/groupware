<?
class Member_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}

	public function get_login($option){
		$this->db->where('id',$option['userid']);
		$this->db->where('pwd',$option['password']);
		$this->db->where('is_active',0);
		$result = $this->db->get('sw_user',1);
		return $result;
	}

	public function get_user($option){
		$this->db->where($option);
		$this->db->where('is_active',0);
		$this->db->order_by('no','DESC');
		$result = $this->db->get('sw_user');
		return $result->result_array();
	}
	public function get_user_detail($option){
		$result = $this->db->get_where('sw_user',$option);
		return $result;
	}
	public function set_user_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_user',$option);
		//$no = $this->db->insert_id();
	}
	public function set_user_update($option,$where){
		$this->db->set('last_update', 'NOW()', false);
		$this->db->update('sw_user',$option,$where);
	}

}
/* End of file member_model.php */
/* Location: ./models/member_model.php */