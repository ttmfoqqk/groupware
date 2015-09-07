<?
class Main extends CI_Controller{
	public function __construct() {
       parent::__construct();
	   delete_cookie('left_menu_open_cookie');
	   $this->load->model("md_company");
    }

	public function _remap($method){
		login_check();
		if ($this->input->is_ajax_request()) {
			if(method_exists($this, '_' . $method)){
				$this->{'_' . $method}();
			}
		}else{
			if(method_exists($this, $method)){
				$this->load->view('inc/header_v');
				$this->load->view('inc/side_v');
				$this->$method();
				$this->load->view('inc/footer_v');
			}else{
				show_error('에러');
			}
		}
	}

	public function index(){
		$atn = $this->getInToday($this->session->userdata('no'));
		$data['atn'] = $atn;
		
		$approved_json = json_decode(APPROVED_COUNT_JSON);
		foreach($approved_json->sender as $key=>$value){
			$data['sender'][$key]=$value;
			$data['anchor_s'][$key] = site_url('approved_send/lists/'.$key);
			$data['class_s'][$key] = $value > 0 ? 'text-danger' : '';
		}
		foreach($approved_json->receiver as $key=>$value){
			$data['receiver'][$key]=$value;
			$data['anchor_r'][$key] = site_url('approved_receive/lists/'.$key);
			$data['class_r'][$key] = $value > 0 ? 'text-danger' : '';
		}

		$this->load->view('main/main_v',$data);
	}
	
	/**
	 * 근태현환 설정 반환. 주중, 토요일, 일요일. 세가지 설정이 있어야 한다.
	 * @return unknown|array
	 */
	public function getAtdConf(){
		$this->md_company->setTable('sw_attendance');
		$ret = $this->md_company->get();
		if(count($ret)>0)
			return $ret;
		else
			return null;
	}
	
	/**
	 * $userNo가 출근하면 금일 출근 정보를 반환
	 * @param int $userNo
	 * @return array|NULL
	 */
	public function getInToday($userNo){
		$this->md_company->setTable('sw_attendance_history');
		$ret = $this->md_company->get(array('user_no'=>$userNo, 'sData >='=>date("Y-m-d") . " 00:00:00"));
		if(count($ret)>0)
			return $ret[0];
		else
			return null;
	}
	
	public function getHoliday($year){
		$sDate = $year . '-01-01';
		$eDate = date("Y-m-t", strtotime($year . '-12-01'));
	
		$this->md_company->setTable('sw_holiday');
		$ret = $this->md_company->get(array('date >='=>$sDate, 'date <='=>$eDate), 'date');
		if(count($ret) > 0){
			$holiday = array();
			foreach ($ret as $hday){
				array_push($holiday, $hday['date']);
			}
			return $holiday;
		}else{
			return null;
		}
	}
	
	/**
	 * @param string $year
	 * @param string $today
	 * @return boolean
	 */
	public function checkHoliday($year, $today){
		$holiday = $this->getHoliday($year);
		list($d,$t) = explode(' ',$today);
		return in_array($d,$holiday);
	}
	
	public function getDayOfWeek($date){
		list($d,$w) = explode(' ',date('Y-m-d w', strtotime($date)));
		return $w;
	}
	
	/**
	 * 요일과 사용여부에 근거한 근태설정 반환
	 * @param string a formatted date string  $date
	 * @param array $configs
	 * @return NULL|array
	 */
	public function getAtdConfigByDay($date, $configs){
		$w = $this->getDayOfWeek($date);
		$conf = array();
		if($w == 0 && $configs[2]['is_active']){ //일요일
			$conf = $configs[2];
		}else if($w == 6 && $configs[1]['is_active']){	//토요일
			$conf = $configs[1];
		}else if($w >=1 && $w <6 && $configs[0]['is_active']){	//평일
			$conf = $configs[0];
		}else
			return null;
		return $conf;
	}
	
	
	public function checkInOffice(){
		$cDate = date("Y-m-d H:i:s");
		$cTime = explode(' ',$cDate);
		if($this->checkHoliday(date("Y"), $cDate)){
			alert("휴일입니다");
		}else{
			$configs = $this->getAtdConf();
			if(count($configs) != 3)
				alert('근태설정이 되어있지 않습니다. 관리자에게 연락해주세요.');
			
			$conf = $this->getAtdConfigByDay($cDate, $configs);
			$this->md_company->setTable('sw_attendance_history');
			if($conf){
				$confT = strtotime ($conf['sDate']);
				$mT = strtotime($cTime[1]);
				
				$diff_in_mins = floor(($mT - $confT) / 60);
				if($diff_in_mins > 0){
					$latePoint = $diff_in_mins * $conf['point'];
					
					$data = array(
							'user_no'=>$this->session->userdata('no'),
							'sData'=>$cDate,
							'oData'=>date('H:i:s', strtotime(gmdate("H:i:s", ($mT - $confT)))), 
							'point'=>$latePoint,
							'created'=>$cDate
					); 
					
					$this->md_company->create($data);
					alert($diff_in_mins .'분 지각 했네', 'main');
				}else{
					alert($diff_in_mins .'분 빨리 왔네', 'main');
				}
			}else
				alert('근태설정 확인. 관리자에게 연락해주세요.');
		}
	}
	
	public function start(){
		$atn = $this->getInToday($this->session->userdata('no'));
		
		if(isset($atn['sData']))
			alert("출첵 했응꼐 또 누르지 말아줘봐");
		else{
			$this->checkInOffice();
		}
	}
	
	public function end(){
		$cDate = date("Y-m-d H:i:s");
		
		$atn = $this->getInToday($this->session->userdata('no'));
		if(!isset($atn['sData']))
			alert('출근도 안했는데 퇴근함?');
		
		if(isset($atn['eData']))
			alert('또 퇴근하냐? 집 가!');
		else{
			$this->md_company->setTable('sw_attendance_history');
			$this->md_company->modify(array('no'=>$atn['no']), array('eData'=>$cDate));
			alert('집에 가십니다', 'main');
		}
		
	}
}
/* End of file main.php */
/* Location: ./controllers/main/main.php */