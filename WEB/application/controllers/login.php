<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	/*

	*/

	public function index()
	{
		// var_dump($this->session->all_userdata());
		if ($this->session->userdata('logged_in') === 1 || $this->session->userdata('logged_in') == "1" || $this->session->userdata('remember_me') === 1) {
			redirect('dashboard', 'refresh');
		} else {

			$no_js_message = $this->rest_app->get('index.php/common_api/get_config', '', 'application/json');

			$data = array();
			if( ! empty($no_js_message)){
				$data['no_js_message'] = $no_js_message->CONFIG_DESCRIPTION;
			}
			
			$this->rest_app->post('index.php/common_api/log_user', $this->get_user_data(), '');
			
			$login_return_data = $this->session->userdata('login_return_data');
			
			if( ! empty($login_return_data)){
				$data = array_merge($data, $login_return_data);
			}
			
			$this->session->sess_destroy();
			$this->load->view('common/login', $data);
		}
	}

	public function __construct() {
		parent::__construct();
	}

	public function validate_credentials()
	{
		$data['username'] = $this->input->post('input_username');
		$data['password'] = $this->input->post('input_password');
		$data['remember_me'] = $this->input->post('input_remember_me');

		if(empty($this->input->post())){
			redirect('login');
		}

		$session_data['username'] = $data['username'];
		$session_data['password'] = $data['password'];
		$session_data['remember_me'] = $data['remember_me'];
		$session_data['last_login_attempt'] = microtime(true);
		$session_data['logged_in'] = 0;


		$result_data = $this->rest_app->get('index.php/users/user/', $data, 'application/json');
		//$this->rest_app->debug();
		
		
		$data['message'] = '';
		if (isset($result_data->status) && $result_data->status) {
			$user_data = (array_key_exists(1, $result_data->users) ? $result_data->users[1] : $result_data->users[0]);
			//echo "<pre>";
			//var_dump($user_data);die();
			if($user_data->DEACTIVATED_FLAG == 1){
				//$data['message'] = 'The user account is deactivated.';
				$data['message'] = 'User Account is INACTIVE';
				$this->load->view('common/login', $data);
				return;
			}
			
			if($user_data->REGISTRATION_TYPE == 2){				
				$test['password'] = $data['password'];
				$test['vendor_invite_id'] = $user_data->VENDOR_INVITE_ID;
                
				$get_token = $this->rest_app->get('index.php/users/check_migrated_token/', $test, 'application/json');
				if(isset($get_token[0]->SVTID) != ''){
					redirect('resetpassword/index/'.$test['password'], 'refresh');
					return;	
				}
			}
			
			$max_login_attempts = $this->config->item('max_login_attempts');
			$account_unlock_time = $this->config->item('account_unlock_time');
			$put_data['user_id'] = $user_data->USER_ID;
			$put_data['unlock_time'] = $user_data->UNLOCK_TIME;
			$put_data['last_attempt'] = $user_data->LAST_ATTEMPT;
			$put_data['last_login'] = $user_data->LAST_LOGIN;
			$put_data['attempts'] =($user_data->ATTEMPTS + 1);
			
			if ($user_data->PASSWORD_VALID && microtime(true)>=$put_data['unlock_time']) {
				$this->log_user_action($user_data->USER_ID, 1);
				$session_data['logged_in'] = 1;
				$session_data['user_id'] = $user_data->USER_ID;
				$session_data['user_first_name'] = $user_data->USER_FIRST_NAME;
				$session_data['user_middle_name'] = $user_data->USER_MIDDLE_NAME;
				$session_data['user_last_name'] = $user_data->USER_LAST_NAME;
				$session_data['user_type_id'] = $user_data->USER_TYPE_ID;
				$session_data['user_type'] = $user_data->USER_TYPE;
				$session_data['position_id'] = $user_data->POSITION_ID;
				$session_data['user_status'] = $user_data->USER_STATUS;
				$session_data['user_mobile'] = $user_data->USER_MOBILE;
				$session_data['user_email'] = $user_data->USER_EMAIL;
				$session_data['user_type'] = $user_data->USER_TYPE;
				$session_data['vendor_id'] = $user_data->VENDOR_ID;
				$session_data['position_name'] = $user_data->POSITION_NAME;
				$session_data['position_code'] = $user_data->POSITION_CODE;
				$session_data['vendor_invite_id'] = $user_data->VENDOR_INVITE_ID;
				$session_data['business_type'] = $user_data->BUSINESS_TYPE;
				$session_data['v_business_type'] = $user_data->V_BUSINESS_TYPE;
				$session_data['vendor_code'] = $user_data->VENDOR_CODE;
				$session_data['last_attempt'] = $put_data['last_login']; 

				if($user_data->USER_TYPE_ID == 2)
				{
					if($user_data->SVDID > 0)
					{
						$session_data['new_vendor'] = 0;
					}
					else
					{
						$session_data['new_vendor'] = 1;
					}
				}
				else
				{
					$session_data['new_vendor'] = 0;
				}
				$put_data['unlock_time'] = 0;
				$put_data['attempts'] = 0;
				//$put_data['last_attempt'] = microtime(true); <-- eto dati return if error == true;
				$put_data['last_attempt'] = strtotime($put_data['last_login']);// jay convert to unix timestamp //$put_data['last_login']; 
				$result_data = $this->rest_app->put('index.php/login_attempts/login_attempt/', $put_data, 'text');
				$this->session->set_userdata($session_data);

				$arg = $this->get_user_data();
				$arg['USER_ID'] = $session_data['user_id'];

				$this->rest_app->post('index.php/common_api/log_user', $arg, '');
				
				redirect('dashboard', 'refresh');
				// var_dump($this->session->all_userdata());
				//$this->load->view('common/login', $data);
			} else {
				$data['message'] = 'username or password not valid';
				if ($put_data['attempts']>=$max_login_attempts) {
					
					if ($put_data['unlock_time']==0) {
						$put_data['unlock_time'] = microtime(true) + $account_unlock_time;
					}else if(microtime(true) >= $put_data['unlock_time']){
						$put_data['unlock_time'] = microtime(true) + $account_unlock_time;
					}
					
					//$data['message'] = 'You have reached the maximum number of failed login attempts. Your account will be unlocked on '.date("F j, Y, g:i:s a", $put_data['unlock_time']);
					$timer = (int)($put_data['unlock_time'] - microtime(true));
					$word = 'seconds';
					if($timer == 1){
						$word = 'second';
					}
					$data['message'] = '<p id="locked_message">Invalid username or password. ' . $session_data['username'] . ' has reached the maximum number of failed login attempts.<br/> Please wait <span id="unlock_countdown">' . $timer . '</span> <span id="unlock_countdown_word">' . $word . '</span> to login.<input id="current_input_username" type="hidden" value="'. $session_data['username']. '"></p>';
				}
				$session_data['logged_in'] = FALSE;
				$this->session->set_userdata($session_data);
				if ($data['remember_me'] != 1) {
						//$this->session->sess_destroy();
						$data['username'] = '';
						$data['password'] = '';
						$data['remember_me'] = 0;
						$data['destroy_local_storage'] = 1;
				}
				$result_data = $this->rest_app->put('index.php/login_attempts/login_attempt/', $put_data, 'text');
				$this->session->set_userdata('login_return_data', $data);
			
				redirect('login');
				//$this->load->view('common/login', $data);
			}

		} else if (isset($result_data->status) && !($result_data->status)) {
			$session_data['logged_in'] = FALSE;
			$this->session->set_userdata($session_data);
			if ($data['remember_me'] != 1) {
					//$this->session->sess_destroy();
					$data['username'] = '';
					$data['password'] = '';
					$data['remember_me'] = 0;
					$data['destroy_local_storage'] = 1;
			}
			$data['message'] = $result_data->error;

			$arg = $this->get_user_data();
			$arg['message'] = $data['message'];

			$this->rest_app->post('index.php/common_api/log_user', $arg, '');
			
			
			$this->session->set_userdata('login_return_data', $data);
			redirect('login');
			//$this->load->view('common/login', $data);
		} else {
			// var_dump($result_data);
			$session_data['logged_in'] = FALSE;
			$data['message'] = 'Log in failed. destination server was unreachable.';
			$this->session->set_userdata('login_return_data', $data);
			redirect('login');
			//$this->load->view('common/login', $data);
		}

	}

	function logout() {
		$this->session->sess_destroy();
		redirect('login', 'refresh');
	}

	function get_remember_me() {
		$data['username'] = $this->session->userdata('username');
		$data['password'] = $this->session->userdata('password');
		// $data['username'] = $this->encrypt->decode($this->session->userdata('username'));
		// $data['password'] = $this->encrypt->decode($this->session->userdata('password'));
		$data['remember_me'] = $this->session->userdata('remember_me');

		echo json_encode($data);
	}

	function get_session() {
		echo json_encode($this->session->all_userdata());
	}

	function refresh_session()
	{
		$session_data['username'] = $this->input->post('username');
		$session_data['remember_me'] = $this->input->post('remember_me');
		$session_data['last_login_attempt'] = $this->input->post('last_login_attempt');
		$session_data['logged_in'] = $this->input->post('logged_in');
		$session_data['user_id'] = $this->input->post('user_id');
		$session_data['user_first_name'] = $this->input->post('user_first_name');
		$session_data['user_middle_name'] = $this->input->post('user_middle_name');
		$session_data['user_last_name'] = $this->input->post('user_last_name');
		$session_data['user_type_id'] = $this->input->post('user_type_id');
		$session_data['position_id'] = $this->input->post('position_id');
		$session_data['user_status'] = $this->input->post('user_status');
		$session_data['user_mobile'] = $this->input->post('user_mobile');
		$session_data['user_email'] = $this->input->post('user_email');
		$session_data['user_type'] = $this->input->post('user_type');
		$session_data['vendor_id'] = $this->input->post('vendor_id');
		$session_data['position_name'] = $this->input->post('position_name');
		$session_data['position_code'] = $this->input->post('position_code');
		$session_data['vendor_invite_id'] = $this->input->post('vendor_invite_id');
		$session_data['last_attempt'] = $this->input->post('last_attempt');
		$session_data['business_type'] = $this->input->post('business_type');
		$session_data['v_business_type'] = $this->input->post('v_business_type');
		$session_data['vendor_code'] = $this->input->post('vendor_code');

		$this->session->set_userdata($session_data);

		echo json_encode($this->session->all_userdata());
	}

	function log_user_action($user_id = '', $action_id = '', $screen_id = '')
	{
		$data['USER_ID'] = ($user_id != '') ? $user_id : $this->input->post('user_id');
		
		//Get logged user id - jay
		if(empty($data['USER_ID'])){
			$data['USER_ID'] = $this->session->userdata('user_id');
		}
		
		$data['ACTION_ID'] = ($action_id != '') ? $action_id : $this->input->post('action_id');
		$data['SCREEN_ID'] = ($screen_id != '') ? $screen_id : $this->input->post('screen_id');

		$post_data = $this->rest_app->post('index.php/users/action_logs/', $data, '');
		if(empty($user_id)){
			echo $post_data;
		}
	}

	function get_user_logs()
	{
		$get_data['user_id'] = $this->input->get('user_id');
		//Get logged user id - jay
		if(empty($get_data['user_id'])){
			$get_data['user_id'] = $this->session->userdata('user_id');
		}
		$user_logs = $this->rest_app->get('index.php/users/action_logs', $get_data);

		echo json_encode($user_logs);
	}
	
	public function get_user_data(){
		$data['HTTPUSERAGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown User Agent';
		
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		
		$data['REMOTEADDR'] = $ipaddress;
		
		return $data;
	}

	public function redirect_expire_session(){

		$data['expired_session'] = true;
		$this->load->view('common/login',$data);
	}
}
