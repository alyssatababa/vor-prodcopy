<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Login_attempts extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('login_attempts_model');
		}

		public function test_get(){
			$this->login_attempt_put();
		}

		public function login_attempt_put(){
			$user_id = $this->put('user_id');
			$unlock_time = $this->put('unlock_time');
			$attempts = $this->put('attempts');
			$last_attempt = $this->put('last_attempt');

			// $user_id = 5;
			// check if name already exists.
			$data['login_attempt']  = $this->login_attempts_model->read($user_id);

			// if both queried data and menu id is not null and ids are not equal throw an error
			if ($user_id && ($user_id!=null && $user_id>0)) {
				if ($data['login_attempt'] && isset($data['login_attempt'][0]['USER_ID'])) {
					// do insert
					$update = $this->login_attempts_model->update($user_id,$unlock_time,$attempts,$last_attempt);
					$data['login_attempt'] = $this->login_attempts_model->read($user_id);

					if ($update) {
						$this->response(array_merge($data,[
							'status' => TRUE,
							'error' => 'PUT existing successful'
						]));
					} else {
						$this->response([
							'status' => FALSE,
							'error' => 'PUT existing failed.'
							], 400);
					}
				} else {
					$insert = $this->login_attempts_model->insert($user_id,$unlock_time,$attempts,$last_attempt);
					$data['login_attempt'] = $this->login_attempts_model->read($user_id);
					if ($insert) {
						$this->response(array_merge($data,[
							'status' => TRUE,
							'error' => 'PUT new successful'
						]));
					} else {
						$this->response([
							'status' => TRUE,
							'error' => 'PUT new failed'
						], 404);
					}
				}
			} else {
				$this->response([
					'status' => FALSE,
					'error' => 'Error! Please check your parameters'
					], 400);
			}
		}
	}

?>
