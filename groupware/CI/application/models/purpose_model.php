<?
class Purpose_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_point_approved($option=null){
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

		$this->db->select('project.sData');
		$this->db->select('project.eData');
		$this->db->select('TO_DAYS(project.eData)-TO_DAYS(project.sData)+1 AS dataDiff');
		$this->db->select('project.pPoint');
		$this->db->select('project.mPoint');

		$this->db->select('status.status');
		$this->db->select('status.sender');
		$this->db->select('status.receiver');
		$this->db->select('status.part_sender');
		$this->db->select('status.part_receiver');
		$this->db->select('status.created');

		$this->db->select('sender.name AS sender_name');
		$this->db->select('receiver.name AS receiver_name');
		$this->db->from('sw_approved AS approved');
		$this->db->join('sw_project AS project','approved.project_no = project.no');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no');

		$this->db->join('sw_user AS sender','status.sender = sender.no');
		$this->db->join('sw_user AS receiver','status.receiver = receiver.no');

		$this->db->where('approved.kind = 0');
		$this->db->where($where);
		$this->db->like($like);

		$query = $this->db->get();
		$result = $query->result_array();
		
		
		/* 
			sData~eData 주말제거 A
			sw_holiday  휴일제거 B
			dataDiif -= A.count - B.count
			dataDiif * pPoint,mPoint
		*/

		// 전체점수
		$point_total = 0;
		// 리스트
		foreach($result as $lt){
			// 가용일 카운트
			$cnt_day = 0;
			// 주말제거, 휴일제거 추가하기
			for( $i=0; $i < $lt['dataDiff']; $i++ ){
				$date = date("Y-m-d", strtotime( $lt['sData']."+$i day"));
				$yoil = date('w',strtotime($date));
				if($yoil > 0 && $yoil < 6 ){
					//echo $yoil;
					$cnt_day++;
					$point_total += $lt['pPoint'];
				}
			}
		}


		//return $result;
	}
	
}
/* End of file purpose_model.php */
/* Location: ./models/purpose_model.php */