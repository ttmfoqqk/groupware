<?
class Member_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function encryp($passwd){
		$salt   = $this->config->item('encryption_key');
		$string = $passwd . $salt;
		for($i=0;$i<10;$i++) {
			$string = hash('sha512',$string . $passwd . $salt);
		}
		return $string;
	}

	public function get_login($option){
		set_options($option);
		$this->db->where('is_active',0);
		$result = $this->db->get('sw_user',1);
		return $result;
	}
	
	
	
	

public function get_user_list($option=NULL,$limit=NULL,$offset=NULL,$type=NULL){
		if($type == 'count'){
			$this->db->select('count(*) as total');
		}else{
			$this->db->select('*');
			$this->db->select('IF(is_active=0,"재직","퇴사") as active',FALSE);
			$this->db->select('date_format(sDate,"%Y-%m-%d") as sDate',FALSE);
			$this->db->select('date_format(eDate,"%Y-%m-%d") as eDate',FALSE);
			$this->db->select('date_format(birth,"%Y-%m-%d") as birth',FALSE);
			$this->db->select('date_format(created,"%Y-%m-%d") as created',FALSE);
			
			$this->db->order_by('order','ASC');
			$this->db->order_by('no','DESC');
			$this->db->limit($limit,$offset);
		}

		$this->db->from('sw_user');
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
	
	public function get_user_detail($option=NULL,$setVla=array()){
		$this->db->select('*');
		$this->db->from('sw_user');
		set_options($option);
	
		$query = $this->db->get();
		$result = set_detail_field($query,$setVla);
	
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