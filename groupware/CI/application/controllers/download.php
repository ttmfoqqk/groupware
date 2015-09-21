<?
class Download extends CI_Controller{
	public function __construct() {
		parent::__construct();
		login_check();
    }
	public function index(){
		$this->load->helper('download');

		$path  = $this->input->get('path');
		$oname = $this->input->get('oname');
		$uname = $this->input->get('uname');

		$data = file_get_contents($path.$uname);
		$name = $oname;
		force_download($name, $data);
	}
}
/* End of file download.php */
/* Location: ./controllers/download.php */