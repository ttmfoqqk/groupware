<?
class Board_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	/* 게시판 설정 */
	public function get_setting_list($option=null,$limit=null,$offset=null){
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
	public function set_setting_insert($option){
		$this->db->insert('sw_board_list',$option);
		//$no = $this->db->insert_id();
	}
	public function set_setting_update($option,$where){
		$this->db->update('sw_board_list',$option,$where);
	}


	/* 게시판 */
	public function get_board_list($option=null,$limit=null,$offset=null){
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
		$this->db->where('is_delete',0);
		$this->db->where($where);
		$this->db->like($like);
		$query = $this->db->get('sw_board_contents');
		$query = $query->row();
		$result['total'] = $query->total;
		


		$this->db->where('is_delete',0);
		$this->db->where($where);
		$this->db->like($like);
		$this->db->order_by('original_no','DESC');
		$this->db->order_by('order','ASC');
		$query = $this->db->get('sw_board_contents',$limit,$offset);
		$result['list'] = $query->result_array();



		$this->db->where('is_delete',0);
		$this->db->where('is_notice',0);
		$this->db->where($option['code']);
		$this->db->order_by('original_no','DESC');
		$this->db->order_by('order','ASC');
		$query = $this->db->get('sw_board_contents');
		$result['notice'] = $query->result_array();

		return $result;
	}
	public function get_board_detail($option,$method=null){
		if( $method == 'view' ){
			$sql = "update `sw_board_contents` set count_hit=count_hit+1 where no = '".$option['no']."' ";
			$this->db->query($sql);
		}
		$this->db->where('is_delete',0);
		$result['data']  = $this->db->get_where('sw_board_contents',$option);
		$result['files'] = $this->get_board_file_list(array('parent_no'=>$option['no']));
		return $result;
	}
	public function set_board_insert($option){
		$this->db->set('created', 'NOW()', false);
		$this->db->insert('sw_board_contents',$option);
		$result = $this->db->insert_id();
		return $result;
	}
	public function set_board_update($option,$where){
		$this->db->update('sw_board_contents',$option,$where);
	}
	/* 임시 파일 insert */
	public function set_board_file_insert($option){
		$this->db->insert('sw_board_file',$option);
		$result = $this->db->insert_id();
		return $result;
	}
	public function set_board_file_update($option,$where){
		$this->db->update('sw_board_file',$option,$where);
	}
	public function set_board_file_delete($option){
		$this->db->delete('sw_board_file',$option);
	}
	public function get_board_file_list($option){
		$query = $this->db->get_where('sw_board_file',$option);
		$result = $query->result_array();
		return $result;
	}
	/*
		파일 보기
		sw_board_file where code , parent_no
	*/
}
/* End of file board_model.php */
/* Location: ./models/board_model.php */