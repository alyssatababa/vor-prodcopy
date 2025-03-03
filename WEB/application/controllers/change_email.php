<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Change_email extends CI_Controller
{
	function index($token = null)
	{
		$data['user_id'] 	= $this->session->userdata('user_id');
		$data['email']		= $this->session->userdata('user_email');
		$this->load->view('common/change_email', $data);
	}
	
	function validate_userdata(){
		
		$data['user_id'] = $this->input->post('user_id');
		$data['c_password'] = $this->input->post('c_password');
		$data['n_password'] = $this->input->post('n_password');
		$data['base_url'] = base_url();
        
		$err_code = $this->rest_app->get('index.php/common_api/validate_update_email', $data);
		
		if($err_code == '1'){
			$session_data['user_email'] = $data['n_password'];
			$this->session->set_userdata($session_data);
		}

		echo $err_code;
		//print_r($data);
	}
}
?>