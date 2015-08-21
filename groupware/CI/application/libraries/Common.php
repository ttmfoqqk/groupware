<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common {
	public function __construct()
    {
		$this->CI =& get_instance();
    }

    function getRet($result, $data=''){
    	$ret = '{"result":' . json_encode($result) . ',"data":' . json_encode($data) .'}';
    	return $ret;
    }
    
}

/* End of file session.php */
/* Location: ./application/libraries/common.php */