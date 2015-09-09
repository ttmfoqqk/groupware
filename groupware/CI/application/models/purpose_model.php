<?
class Purpose_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_point_approved($option=null){
		$where = array();
		foreach($option['where'] as $key=>$val){
			if($val!=''){
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
		// 휴일
		$holiday_result = $this->get_holiday();
		$holiday = array();
		foreach($holiday_result as $lt){
			array_push($holiday,$lt['date']);
		}
		
		// 연차
		$annual_option = array();
		foreach($option['annual'] as $key=>$val){
			if($val!=''){
				$annual_option[$key] = $val;
			}
		}
		$annual_result = $this->get_annual($annual_option);
		$annual = array();
		foreach($annual_result as $lt){
			array_push($annual,$lt['date']);
		}

		$data = array(
			'point_total'   => 0,
			'point_plus'    => 0,
			'point_minus'   => 0,
			'point_avg'     => 0,
			'percent_plus'  => 0,
			'percent_minus' => 0,
			'percent_avg'   => 0
		);

		

		// 리스트
		foreach($result as $lt){
			// 주말제거, 휴일제거, 연차제거
			for( $i=0; $i < $lt['dataDiff']; $i++ ){
				$date = date("Y-m-d", strtotime( $lt['sData']."+$i day"));
				$yoil = date('w',strtotime($date));
				if($yoil > 0 && $yoil < 6 ){
					if( in_array($date,$holiday) || in_array($date,$annual) ){
						continue 1;
					}
					$data['point_total'] += $lt['pPoint'];
				}
			}
			// [ 보낸결재 승인 +점수, 그외 -점수 ]
			if($lt['status'] == 'c'){
				$data['point_plus']  += $lt['pPoint'];
			}else{
				$data['point_minus'] += $lt['mPoint'];
			}
		}

		$data['point_avg']     = $data['point_plus'] - $data['point_minus'];
		$data['percent_plus']  = ceil($data['point_plus'] / $data['point_total'] * 100);
		$data['percent_minus'] = ceil($data['point_minus'] / $data['point_total'] * 100);
		$data['percent_avg']   = ceil($data['point_avg'] / $data['point_total'] * 100);

		$data['percent_plus']  = $data['percent_plus'] < 0 ? 0 : $data['percent_plus'];
		$data['percent_minus'] = $data['percent_minus'] < 0 ? 0 : $data['percent_minus'];
		$data['percent_avg']   = $data['percent_avg'] < 0 ? 0 : $data['percent_avg'];

		return $data;
	}
	public function get_point_chc($option=null){
		$where = array();
		foreach($option['where'] as $key=>$val){
			if($val!=''){
				$where[$key] = $val;
			}
		}
		$like = array();
		foreach($option['like'] as $key=>$val){
			if($val!=''){
				$like[$key] = $val;
			}
		}

		$this->db->select('IFNULL( ROUND( 10-(avg(IF(log.rank=0,11,log.rank))-1) , 1 ) , 0) AS point_avg',false);
		$this->db->select('IFNULL( ROUND( count(IF(log.rank > 0 and log.rank < 6,log.rank,NULL)) / count(*) * 100 ) , 0 ) AS percent_display',false);
		$this->db->from('sw_chc AS chc');
		$this->db->join('sw_chc_log AS log','chc.no = log.chc_no');
		$this->db->join('sw_project_staff AS staff','chc.project_no = staff.project_no');
		$this->db->where('log.rank > -1');
		$this->db->where($where);
		$this->db->like($like);
		$query = $this->db->get();
		$result = $query->row();

		$data['point_avg']       = $result->point_avg < 0 ? 0 : $result->point_avg;
		$data['percent_avg']     = $data['point_avg'] * 10;
		$data['point_text']      = 0;
		$data['percent_text']    = 0;

		$data['percent_display'] = $result->percent_display;

		$data['point_total']     = ($data['point_avg'] * $data['percent_display'] / 100) + $data['point_text'];
		$data['percent_total']   = $data['point_total'];

		return $data;
	}

	public function get_point_other($option=null){
		$where = array();
		foreach($option['where'] as $key=>$val){
			if($val!=''){
				$where[$key] = $val;
			}
		}
		$like = array();
		foreach($option['like'] as $key=>$val){
			if($val!=''){
				$like[$key] = $val;
			}
		}

		$this->db->select('ifnull(sum(point),0) AS sum',false);
		$this->db->from('sw_other_point');
		$this->db->where($where);
		$this->db->like($like);
		$query  = $this->db->get();
		$result = $query->row();
		

		$data = array(
			'sum' => $result->sum,
			'percent_sum' => $result->sum < 0 ? 0 : $result->sum
		);
		return $data;
	}



	private function get_holiday(){
		$this->db->select('DATE_FORMAT(date,"%Y-%m-%d") AS date',false);
		$query = $this->db->get('sw_holiday');
		$result = $query->result_array();
		return $result;
	}
	private function get_annual($option=null){
		$this->db->select('DATE_FORMAT(annual.data,"%Y-%m-%d") AS date',false);
		$this->db->from('sw_user_annual AS annual');
		$this->db->join('sw_user_department AS department','annual.user_no = department.user_no','left');
		$this->db->where($option);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	
	
}
/* End of file purpose_model.php */
/* Location: ./models/purpose_model.php */