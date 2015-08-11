<?
class Board_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	/* 게시판 설정 */
	public function get_setting_list($option,$limit,$offset){
		$this->db->select('count(*) as total');
		$this->db->where('activated',0);
		$query = $this->db->get_where('sw_board_list',$option );
		$query = $query->row();
		$result['total'] = $query->total;
		
		$this->db->where('activated',0);
		$this->db->order_by('order','ASC');
		$this->db->order_by('no','DESC');
		$query = $this->db->get_where('sw_board_list',$option,$limit,$offset);
		$result['list'] = $query->result_array();
		return $result;
	}
	public function get_setting_detail($option){
		$result = $this->db->get_where('sw_board_list',$option);
		return $result;
	}
	public function get_setting_insert($option){
		$this->db->insert('sw_board_list',$option);
		//$no = $this->db->insert_id();
	}
	public function get_setting_update($option,$where){
		$this->db->update('sw_board_list',$option,$where);
	}


	/* 게시판 */
	public function get_board_list($option,$limit,$offset){
		$this->db->select('count(*) as total');
		$this->db->where('is_delete',0);
		$query = $this->db->get_where('sw_board_contents',$option );
		$query = $query->row();
		$result['total'] = $query->total;
		
		$this->db->where('is_delete',0);
		$this->db->order_by('original_no','DESC');
		$this->db->order_by('order','ASC');
		$query = $this->db->get_where('sw_board_contents',$option,$limit,$offset);
		$result['list'] = $query->result_array();

		$this->db->where('is_delete',0);
		$this->db->where('is_notice',0);
		$this->db->where('code', $option['code'] );
		$this->db->order_by('original_no','DESC');
		$this->db->order_by('order','ASC');
		$query = $this->db->get_where('sw_board_contents');
		$result['notice'] = $query->result_array();

		return $result;
	}
	public function get_board_detail($option,$method){
		if( $method == 'view' ){
			$sql = "update `sw_board_contents` set count_hit=count_hit+1 where no = '".$option['no']."' ";
			$this->db->query($sql);
		}
		$this->db->where('is_delete',0);
		$result = $this->db->get_where('sw_board_contents',$option);
		return $result;
	}
	public function get_board_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_board_contents',$option);
		$result = $this->db->insert_id();
		return $result;
	}
	public function get_board_update($option,$where){
		$this->db->update('sw_board_contents',$option,$where);
	}
}
/* End of file board_model.php */
/* Location: ./models/board_model.php */