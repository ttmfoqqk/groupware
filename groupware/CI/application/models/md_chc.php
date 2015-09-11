<?
class Md_chc extends CI_Model{
	private $TABLE_NAME = 'sw_chc';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getCount($where=NULL, $likes=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		
		if($where!=NULL){
			foreach ($where as $key=>$val){
				if($val!='' && $val!=NULL){
					if($key == 'c.rank >=' && $val == 6){
						$this->db->where("c.rank >= 6 OR c.rank = 0");
					}else
						$this->db->where($key, $val);
				}
			}
		}
		
		$this->db->join('sw_project p', 'c.project_no = p.no', 'left outer');
	//	$this->db->join('sw_user u', 'p.user_no = u.no', 'left outer');
		$this->db->join('sw_menu m', 'menu_no = m.no', 'left outer');
		$this->db->join('sw_information i', 'c.customer_no = i.no', 'left outer');
		
		$this->db->join('sw_project_staff ps', 'ps.project_no = p.no AND ps.order = 1', 'left outer');
		$this->db->join('sw_menu m2', 'ps.menu_no = m2.no', 'left outer');
		$this->db->join('sw_user u', 'u.no = ps.user_no', 'left outer');
		
		$this->db->select('count(*) as total');
		$ret = $this->db->get('sw_chc c')->row();
		return $ret->total;
	}
	
	public function get($where=NULL, $likes=NULL, $offset=NULL, $limit=NULL){
		if($likes!=NULL){
			foreach ($likes as $key=>$val){
				if($val!='')
					$this->db->like($key, $val);
			}
		}
		
		if($where!=NULL){
			foreach ($where as $key=>$val){
				if($val!='' && $val!=NULL){
					if($key == 'c.rank >=' && $val == 6){
						$this->db->where("c.rank >= 6 OR c.rank = 0");
					}else
						$this->db->where($key, $val);
				}
			}
		}
		
		$this->db->order_by("c.order",'ASC');
		
		$this->db->select('c.no, c.order, c.kind, c.created, m2.no as department_no, m2.name as department_name ,m.name as menu_kind, c.title, i.bizName, c.keyword, c.url, p.sData, p.eData, c.rank, u.name as user_name, c.title, p.pPoint, p.mPoint, p.menu_part_no, c.ip, c.status, c.customer_no');
		$this->db->join('sw_project p', 'c.project_no = p.no', 'left outer');
	//	$this->db->join('sw_user u', 'p.user_no = u.no', 'left outer');
		$this->db->join('sw_menu m', 'p.menu_no = m.no', 'left outer');
		$this->db->join('sw_information i', 'c.customer_no = i.no', 'left outer');
		
		$this->db->join('sw_project_staff ps', 'ps.project_no = p.no AND ps.order = 1', 'left outer');
		$this->db->join('sw_menu m2', 'ps.menu_no = m2.no', 'left outer');
		$this->db->join('sw_user u', 'u.no = ps.user_no', 'left outer');
		
		$ret = $this->db->get('sw_chc c', $offset, $limit);
		$arDatas = $ret->result_array();
		if(count($arDatas) > 0){
			$i = 0;
			foreach ($arDatas as $arData){
				$no = $arData['no'];
				$sDate = $arData['sData'];
				$eDate = $arData['eData'];
				
				//노출률
				$where = array('chc_no'=>$no, 'date >='=>$sDate, 'date <'=>date("Y-m-d", strtotime($eDate."+1 day")));
				$this->db->select('count(*) as total');
				$allCount = $this->db->get_where('sw_chc_log', $where)->row();
				$allCount = $allCount->total;
				
				$where = array('chc_no'=>$no, 'date >='=>$sDate, 'date <'=>date("Y-m-d", strtotime($eDate."+1 day")), 'rank >='=>1, 'rank <'=>6);
				$this->db->select('count(*) as total');
				$goodRankCount = $this->db->get_where('sw_chc_log', $where)->row();
				$goodRankCount = $goodRankCount->total;
				
				$exposeRate = 0;
				if($allCount != 0)
					$exposeRate = round($goodRankCount/$allCount * 100, 2);
				
				$arDatas[$i]['exposeRate'] = $exposeRate;
				
				//작업수
				$arDatas[$i]['history'] = $this->db->get_where('sw_chc_history', array('chc_no'=>$no))->result_array();
				
				//답변수
				$arDatas[$i]['response'] = $this->db->get_where('sw_account_history', array('chc_no'=>$no, 'used'=>'답변'))->result_array();
				
				//사용아이디 히스토리
				$arDatas[$i]['idHistory'] = $this->db->get_where('sw_account_history', array('chc_no'=>$no))->result_array();
				
				$i = $i + 1;
			}
		}
		return $arDatas;
	}
	
}
/* End of file md_attendance.php */
/* Location: ./models/md_attendance.php */
?>
