<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Change_password extends CI_Controller
{
	function index($token = null)
	{
		$data['user_id'] 	= $this->session->userdata('user_id');
		$this->load->view('common/change_password', $data);
	}
	
	function validate_userdata(){
		
		$data['user_id'] = $this->input->post('user_id');
		$data['c_password'] = $this->input->post('c_password');
		$data['n_password'] = $this->input->post('n_password');
		$data['base_url'] = base_url();
        
		$err_code = $this->rest_app->get('index.php/common_api/validate_update_password', $data);
		
		if($err_code == '1'){
			$session_data['password'] = $data['n_password'];
			$this->session->set_userdata($session_data);
		}

		echo $err_code;
		//print_r($data);
	}
}
?>