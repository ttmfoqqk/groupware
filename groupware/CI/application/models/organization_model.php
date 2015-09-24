<?
class Organization_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_organization($option){
		$this->db->order_by('parent_no','ASC');
		$this->db->order_by('order','ASC');
		$this->db->where('is_active',0);
		$result = $this->db->get_where('sw_menu',$option);
		return $result->result_array();
	}
	public function get_organization_children($parent_no){
		$this->db->where('parent_no', $parent_no);
		$this->db->from('sw_menu');
		return $this->db->count_all_results();
	}
	
	public function get_organization_search($no=0,$mode='parent'){
		if( $mode == 'parent'){
			$array = $this->tree_search_parent($no);
			$result['no'] = explode('『',$array['no']);
			$result['name'] = explode('『',$array['name']);
			return $result;
		}elseif( $mode == 'children' ){
			$result = $this->tree_search_children($no);
		}
		return $result;
	}
	private function tree_search_parent($no){
		$result['no']   = '';
		$result['name'] = '';
		
		$this->db->select('no,parent_no,name,count(*) as cnt');
		$this->db->from('sw_menu');
		$this->db->where('no',$no);
		$this->db->where('is_active',0);
		$query = $this->db->get();
		$query = $query->row();
		
		if( $query->cnt > 0 ){
			$result['no']   = $query->no;
			$result['name'] = $query->name;
			if( $query->parent_no > 0 ){
				$sub_result = $this->tree_search_parent($query->parent_no);
				$result['no']   = $sub_result['no'] .'『'. $result['no'];
				$result['name'] = $sub_result['name'] .'『'. $result['name'];
			}
		}
		return $result;
	}
	
	private function tree_search_children($no){
		$result = array();
	
		$this->db->select('no');
		$this->db->from('sw_menu');
		$this->db->where('parent_no',$no);
		$this->db->where('is_active',0);
		$query = $this->db->get();
		$query = $query->result_array();

		foreach($query as $lt){
			array_push($result, $lt['no']);
			$sub_result = $this->tree_search_children($lt['no']);
			$result = array_merge($result, $sub_result);
		}
		return $result;
	}
	

	public function set_organization_update($option,$where){
		$this->db->update('sw_menu',$option,$where);
	}

	public function set_organization_insert($option){
		$sql = "update `sw_menu` set `order` = `order`+1 where `parent_no` = '".$option['parent_no']."' ";
		$this->db->query($sql);

		$this->db->set('is_active',0);
		$this->db->insert('sw_menu',$option);
	}
	
	
}
/* End of file organization_model.php */
/* Location: ./models/organization_model.php */