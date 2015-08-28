<?
class Md_company extends CI_Model{
	private $TABLE_NAME = 'sw_information';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function setTable($tableName){
		$this->TABLE_NAME = $tableName;
	}
	
	public function getAllCount(){
		return $this->db->count_all($this->TABLE_NAME);
	}
	
	/**
	 * @param array $where
	 * @param array $likes
	 * @return int
	 */
	public function getCount($where=NULL, $likes=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		if($where != NULL)
			$this->db->where($where);
		
		$this->db->select('count(*) as total');
		$ret = $this->db->get($this->TABLE_NAME)->row();
		return $ret->total;
	}
	
	/**
	 * @param array $where
	 * @param string | array $select
	 * @param int $offset
	 * @param int $limit
	 * @param array $likes
	 */
	public function get($where=NULL, $select ='*', $offset=NULL, $limit=NULL, $likes=NULL, $asc=FALSE, $desc=FALSE){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		
		if (in_array("order", $this->getFileds())) 
			$this->db->order_by("order",'ASC');
		else{
			if($asc != false)
				$this->db->order_by($asc,'ASC');
			if($desc != false)
				$this->db->order_by($desc,'DESC');
		}
		
		$this->db->select($select);
		if($where != NULL)
			$this->db->where($where);
		$ret = $this->db->get($this->TABLE_NAME, $offset, $limit);
		return $ret->result_array();
	}
	
	public function create($data){
		return $this->db->insert($this->TABLE_NAME,$data);
	}
	
	public function modify($where, $data){
		return $this->db->update($this->TABLE_NAME, $data, $where);
	}
	
	public function delete($where){
		$this->db->where($where);
		return $this->db->delete($this->TABLE_NAME);
	}
	
	public function deleteAll(){
		return $this->db->empty_table($this->TABLE_NAME);
	}
	
	public function deleteIn($key, $val){
		$this->db->where_in($key,$val);
		return $this->db->delete($this->TABLE_NAME);
	}
	
	public function getFileds(){
		return $this->db->list_fields($this->TABLE_NAME);
	}
	
	public function getEmptyData(){
		$fileds = $this->md_company->getFileds();
		$ret = array();
		foreach ($fileds as $filed){
			$ret[$filed] = '';
		}
		return $ret;
	}
	
	//only sw_information
	public function getSettingData($category, $bizName, $ceoName, $classify, $bizType, 
			$bizConfition, $addr, $phone, $fax, $note, $order, $created, $bizNumber){
		if($this->TABLE_NAME != 'sw_information')
			return null;
		$data = array(
				'category'=>$category,
				'bizName'=>$bizName,
				'ceoName'=>$ceoName,
				'gubun'=>$classify,
				'bizType'=>$bizType,
				'bizCondition'=>$bizConfition,
				'addr'=>$addr,
				'phone'=>$phone,
				'fax'=>$fax,
				'bigo'=>$note,
				'order'=>$order,
				'created'=>$created,
				'bizNumber'=>$bizNumber
		);
		return $data;
	}
	
	public function getUsersByDepartment($dptNo){
		$this->db->select('u.no, u.name');
		$this->db->join('sw_user u', 'ud.user_no = u.no', 'left');
		$this->db->where('ud.menu_no', $dptNo);
		$this->db->join('sw_menu m', 'ud.menu_no = m.no', 'left');
		
		$ret = $this->db->get('sw_user_department ud');
		if (count($ret) > 0){
			return json_encode($ret->result_array());
		}else
			return json_encode(array());
	}


	/* 담당자 */
	public function get_staff_list($option){
		$this->db->from('sw_information_staff');
		$this->db->where($option);
		$this->db->order_by('order','ASC');
		$this->db->order_by('name','ASC');
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function set_staff_delete($option){
		$this->db->delete('sw_information_staff',$option);
	}

	public function set_staff_insert($option,$staff){
		$this->set_staff_delete($staff);
		$this->db->insert_batch('sw_information_staff',$option);
	}

	/* 사이트 */
	public function get_site_list($option){
		$this->db->from('sw_information_site');
		$this->db->where($option);
		$this->db->order_by('order','ASC');
		$this->db->order_by('url','ASC');
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	public function set_site_delete($option){
		$this->db->delete('sw_information_site',$option);
	}

	public function set_site_insert($option,$staff){
		$this->set_site_delete($staff);
		$this->db->insert_batch('sw_information_site',$option);
	}


	/* 사원->부서 */
	public function get_department_list($option){
		$this->db->from('sw_user_department');
		$this->db->where($option);
		$this->db->order_by('order','ASC');
		$this->db->order_by('position','ASC');
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function set_department_delete($option){
		$this->db->delete('sw_user_department',$option);
	}
	public function set_department_insert($option,$staff){
		$this->set_department_delete($staff);
		$this->db->insert_batch('sw_user_department',$option);
	}
	/* 사원->연차 */
	public function get_annual_list($option){
		$this->db->from('sw_user_annual');
		$this->db->where($option);
		$this->db->order_by('order','ASC');
		$this->db->order_by('data','DESC');
		
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function set_annual_delete($option){
		$this->db->delete('sw_user_annual',$option);
	}
	public function set_annual_insert($option,$staff){
		$this->set_annual_delete($staff);
		$this->db->insert_batch('sw_user_annual',$option);
	}
}
/* End of file company_model.php */
/* Location: ./models/company_model.php */
?>
