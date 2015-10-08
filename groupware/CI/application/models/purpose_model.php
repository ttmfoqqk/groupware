<?
class Purpose_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	public function get_point_approved($option=null,$sDate,$eDate){
		// 휴일
		$holiday_result = $this->get_holiday();
		$holiday = array();
		foreach($holiday_result as $lt){
			array_push($holiday,$lt['date']);
		}
		
		// 연차
		$annual_result = $this->get_annual($option['annual']);
		$annual = array();
		foreach($annual_result as $lt){
			array_push($annual,$lt['date']);
		}


		// 일할계산한 업무 목록 - 전체점수,
		$this->db->select('project.no');
		$this->db->select('IF(date_format(sData,"%Y-%m") < "'.$sDate.'" , "'.$sDate.'" ,sData) AS sData',false);
		$this->db->select('eData');
		$this->db->select('TO_DAYS( IF(date_format(eData,"%Y-%m") > "'.$eDate.'" , date_add(date_add("'.$eDate.'-01", interval +1 month), interval -1 day) ,eData) ) - TO_DAYS(  IF(date_format(sData,"%Y-%m") < "'.$sDate.'" , "'.$sDate.'-01" ,sData)  ) + 1 AS dataDiff',false);
		$this->db->select('pPoint,mPoint');
		$this->db->from('sw_project AS project');
		$this->db->join('sw_project_staff AS staff','project.no = staff.project_no');
		$this->db->join('sw_user AS user','staff.user_no = user.no');
		set_options($option);

		$query = $this->db->get();
		$result = $query->result_array();

		$project = array();
		$project['no']=array(0);
		foreach($result as $lt){
			if( !in_array($lt['no'],$project['no']) ){
				$project[ 'no_'.$lt['no'] ] = array(
					'no' => $lt['no'],
					'sData' => $lt['sData'],
					'eData' => $lt['eData'],
					'dataDiff' => $lt['dataDiff'],
					'pPoint' => $lt['pPoint'],
					'mPoint' => $lt['mPoint']
				);
				array_push($project['no'] ,$lt['no'] );
			}
		}
		unset($result);
		// 결재점수
		
		$option_approved = $option['approved'];
		$option_approved['where_in'] = array(
			'project_no' => $project['no']
		);
		
		$this->db->select('approved.project_no,status.*');
		$this->db->from('sw_approved AS approved');
		$this->db->join('sw_approved_status AS status','approved.no = status.approved_no');
		$this->db->join('sw_user AS user','status.sender = user.no');
		set_options($option_approved);
		
		$query = $this->db->get();
		$result = $query->result_array();

		/* 
			sData~eData 주말제거 A
			sw_holiday  휴일제거 B
			dataDiif -= A.count - B.count
			dataDiif * pPoint,mPoint
		*/
		
		$data = array(
			'point_total'   => 0,
			'point_plus'    => 0,
			'point_minus'   => 0,
			'point_avg'     => 0,
			'percent_plus'  => 0,
			'percent_minus' => 0,
			'percent_avg'   => 0
		);

		foreach($project as $key=>$val){
			if( $key != 'no' ){
				for( $i=0; $i < $project[$key]['dataDiff']; $i++ ){
					$date = date('Y-m-d', strtotime( $project[$key]['sData']."+$i day"));
					$yoil = date('w',strtotime($date));
					if($yoil > 0 && $yoil < 6 ){
						if( in_array($date,$holiday) || in_array($date,$annual) ){
							continue 1;
						}

						$data['point_total'] += $project[$key]['pPoint'];
						if( $date < date('Y-m-d') ){
							$data['point_minus'] += $project[$key]['mPoint'];
						}
					}
				}
			}
		}
		

		foreach($result as $lt){
			// 주말제거, 휴일제거, 연차제거
			for( $i=0; $i < $project['no_'.$lt['project_no']]['dataDiff']; $i++ ){
				$date = date('Y-m-d', strtotime( $project['no_'.$lt['project_no']]['sData']."+$i day"));
				$yoil = date('w',strtotime($date));
				if($yoil > 0 && $yoil < 6 ){
					if( in_array($date,$holiday) || in_array($date,$annual) ){
						continue 1;
					}
					if( $date < date('Y-m-d') ){
						
					}
				}
			}
			if( $project['no_'.$lt['project_no']]['dataDiff'] > 0 ){
			// [ 보낸결재 승인 +점수 ]
				if($lt['status'] == 'c'){
					$data['point_plus']  += $project['no_'.$lt['project_no']]['pPoint'];
					$data['point_minus'] -= $project['no_'.$lt['project_no']]['mPoint'];
				}
			}
		}


		$data['point_avg']     = $data['point_plus'] - $data['point_minus'];
		$data['percent_plus']  = $data['point_total'] > 0 ? ceil($data['point_plus']  / $data['point_total'] * 100) : 0;
		$data['percent_minus'] = $data['point_total'] > 0 ? ceil($data['point_minus'] / $data['point_total'] * 100) : 0;
		$data['percent_avg']   = $data['point_total'] > 0 ? ceil($data['point_avg']   / $data['point_total'] * 100) : 0;

		$data['percent_plus']  = $data['percent_plus']  < 0 ? 0 : $data['percent_plus'];
		$data['percent_minus'] = $data['percent_minus'] < 0 ? 0 : $data['percent_minus'];
		$data['percent_avg']   = $data['percent_avg']   < 0 ? 0 : $data['percent_avg'];

		return $data;
	}
	public function get_point_chc($option=null){
		$this->db->select('IFNULL( ROUND( 10-(avg(IF(log.rank=0,11,log.rank))-1) , 1 ) , 0) AS point_avg',false);
		$this->db->select('IFNULL( ROUND( count(IF(log.rank > 0 and log.rank < 6,log.rank,NULL)) / count(*) * 100 ) , 0 ) AS percent_display',false);
		$this->db->from('sw_chc AS chc');
		$this->db->join('sw_chc_log AS log','chc.no = log.chc_no');
		$this->db->join('sw_project_staff AS staff','chc.project_no = staff.project_no');
		$this->db->join('sw_user AS user','staff.user_no = user.no');
		$this->db->where('log.rank > -1');
		set_options($option);
		$query = $this->db->get();
		$result = $query->row();

		$data['point_avg']       = $result->point_avg < 0 ? 0 : $result->point_avg;
		$data['percent_avg']     = $data['point_avg'] * 10;
		$data['point_text']      = 0;
		$data['percent_text']    = 0;

		$data['percent_display'] = $result->percent_display;

		$data['point_total']     = ($data['point_avg'] * $data['percent_display'] / 100) + $data['point_text'];
		$data['point_total']     = ceil($data['point_total'] * 100) / 100;
		$data['percent_total']   = $data['point_total'] * 10;

		return $data;
	}

	public function get_point_other($option=null){
		$this->db->select('ifnull(sum(point),0) AS sum',false);
		$this->db->from('sw_other_point as point');
		$this->db->join('sw_user as user','point.user_no = user.no');
		set_options($option['other']);
		$query  = $this->db->get();
		$result['other'] = $query->row();
		
		$this->db->select('ifnull(sum(point),0) AS sum',false);
		$this->db->from('sw_attendance_history as attendance');
		$this->db->join('sw_user as user','attendance.user_no = user.no');
		$this->db->join('sw_user_department as department','attendance.user_no = department.user_no');
		set_options($option['attendance']);
		$query  = $this->db->get();
		$result['attendance'] = $query->row();
		
		$tmp_sum = $result['other']->sum + $result['attendance']->sum;
		$data = array(
			'sum' => $tmp_sum,
			'percent_sum' => $tmp_sum < 0 ? 0 : $tmp_sum
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
		$this->db->join('sw_user AS user','annual.user_no = user.no');
		$this->db->join('sw_user_department AS department','annual.user_no = department.user_no','left');
		set_options($option);
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
}
/* End of file purpose_model.php */
/* Location: ./models/purpose_model.php */