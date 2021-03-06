<?
class Login extends CI_Controller{
	public function __construct() {
       parent::__construct();
    }
	public function index(){
		if( $this->session->userdata('is_login') ){
			redirect('main');
		}
		$this->load->view('auth/login_v');
	}

	public function authentication(){
		if( $this->session->userdata('is_login') ){
			redirect('main');
		}
		$this->load->library('form_validation');


		$this->form_validation->set_rules('userid','아이디','required|max_length[20]|');
		$this->form_validation->set_rules('password','비밀번호','required|max_length[20]');
		
		if($this->form_validation->run()==false){
			alert('계정 정보를 확인해주세요.', $this->config->base_url() );
		}else{
			$this->load->model('member_model');

			$userid   = $this->input->post('userid');
			$password = $this->member_model->encryp($this->input->post('password'));
			
			$option['where'] =array(
				'id'  => $userid,
				'pwd' => $password
			);

			$result = $this->member_model->get_login($option);

			if( $result->num_rows() > 0 ){
				$data = $result->row_array();
				$sessiondata = array(
					'is_login'   => true,
					'no'    => $data['no'],
					'id'    => $data['id'],
					'name'  => $data['name'],
					'file'	=> $data['file']
				);

				$this->session->set_userdata($sessiondata);

				if( $this->input->post('remember')=='true' ){
					set_cookie('login_id_save',$data['id'],'31536000');
				}else{
					delete_cookie('login_id_save');
				}
				
				$goUrl = $this->input->post('goUrl');
				$goUrl = !$goUrl ? '/' : $goUrl;
				redirect($goUrl);
			}else{
				alert('계정 정보를 확인해주세요.',$this->config->base_url() );
			}
			
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect( '/' );
	}
}
/* End of file login.php */
/* Location: ./controllers/login.php */