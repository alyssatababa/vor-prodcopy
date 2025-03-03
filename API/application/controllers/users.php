<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Users extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('users_model');
		}

		// Server's Get Method
		public function user_get(){
			// echo CI_VERSION;
			$input_username = $this->get('username');
			$input_password = $this->get('password');
			// $input_username = $this->encrypt->decode($this->get('username'));
			// $input_password = $this->encrypt->decode($this->get('password'));

			$data['users'] = $this->users_model->read($input_username, $input_password);
			if (is_array($data)) {
				if ($data['users'])
				{
					$this->response(array_merge($data,[
						'status' => TRUE,
						'error' => ''
					]));
				}
				else
				{
					$this->response([
						'status' => FALSE,
						'error' => 'Invalid username or password.' // 'Invalid username or password.'
						], 404);
				}				
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Invalid username or password.'
					], 404);
			}				

		}

		public function action_logs_post() {
			$post_data['USER_ID'] = $this->post('USER_ID');
			$post_data['ACTION_ID'] = $this->post('ACTION_ID');
			$post_data['SCREEN_ID'] = $this->post('SCREEN_ID');

			$model_data = $this->users_model->log_action($post_data);

			$this->response($model_data);
		}

		public function action_logs_get() {
			$user_id = $this->get('user_id');

			$user_logs = $this->users_model->get_action_logs($user_id);

			$this->response($user_logs);
		}

		public function check_migrated_token_get() {
			$vendor_invite_id = $this->get('vendor_invite_id');
			$password = $this->get('password');

			$result = $this->users_model->get_check_migrated_token($vendor_invite_id, $password);

			$this->response($result);
		}
	}

?>
