<?
class Main_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_list(){
		$query = $this->db->get('sw_user',10);
		return $query->result_array();
	}
}
/* End of file main_model.php */
/* Location: ./models/main_model.php */