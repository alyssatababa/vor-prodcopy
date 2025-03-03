<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Check_session {
	var $CI;

	public function check_session_data(){
		$this->CI = & get_instance();
		//$user_id = $this->CI->session->userdata('');
		$CI =& get_instance();
if($CI->router->class == 'resetpassword' || $CI->router->class == 'forgot_password' ) {
    return;
    die();
}

	if(!$this->CI->session->userdata('user_id')){


		$controller_names = array('login',
			'',
			'login/validate_credentials',
			'resetpassword/index',
			'forgot_password',
			'forgot_password/validate_userdata',
			'forgot_password/reset_password',
			'forgot_password/validate_token',
			'unit_tests/version_hash',
			'unit_tests/version_hash/hashes_get',
			'unit_tests/version_hash/get_hashes'
		);

		

	if(!in_array($this->CI->uri->uri_string(), $controller_names))
    {


    	echo 'no_session|';
    	return;
    	die();
        
    }

	}

}
}

?>