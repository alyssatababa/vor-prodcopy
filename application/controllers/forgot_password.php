<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Forgot_password extends CI_Controller
{

	function index($user_id = null, $token = null) {

		$no_js_message = $this->rest_app->get('index.php/common_api/get_config', '', 'application/json');
		
		$data = array();
		if( ! empty($no_js_message)){
			$data['no_js_message'] = $no_js_message->CONFIG_DESCRIPTION;
		}
		
		if($token)
		{
			$data['user_id'] = $user_id;
			$data['username'] = $this->rest_app->get('index.php/common_api/username', ['user_id' => $user_id]);
			$data['token'] = $token;
			$data['is_token_valid'] = ($this->validate_token($token)) ? 'valid' : 'invalid';
			$data['err_msg'] = (!$this->validate_token($token)) ? 'Token is already Expired!' : '';

			$this->load->view('common/reset_user_password', $data);
		}
		else {
			
			$this->load->view('common/forgot_password', $data);
		}
	}

	function validate_userdata() {
		$data['user_data'] = $this->input->post('user_data');
		$data['base_url'] = base_url();

		$err_code = $this->rest_app->get('index.php/common_api/userdata_validation', $data);

		echo $err_code;
	}

	function reset_password()
	{
		$token = $this->input->post('token');
		$data['user_id'] = $this->input->post('user_id');
		$data['new_password'] = $this->input->post('new_password');

		if($this->validate_token($token)) {
			$this->rest_app->put('index.php/common_api/reset_password', $data, 'text');
			echo json_encode(['err_code' => 0, 'message' => 'Password Successfully Updated. Try to <a href="'.base_url().'index.php/login">Log-in</a>']);
		}
		else {
			echo json_encode(['err_code' => 1, 'message' => 'Password Not Updated. Token is already Expired!']);
		}
	}

	function validate_token($token)
	{
		$err_code = $this->rest_app->get('index.php/common_api/valid_token', ['token' => $token]);
		if ($err_code === '0') {
			return true;
		}
		else if ($err_code === '1') {
			return false;
		}
	}
}
?>