<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Reason extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfx/reason_model');
		}

		// Server's Get Method
		public function add_reason_post(){
			$reason = $this->post('reason');
			//$this->response($reason);
			$data_exist = $this->reason_model->select_reason($reason);
			if ($data_exist)
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Reason already exists.'
					], 404);
			} else {
				$data = $this->reason_model->insert_reason($reason);
				if ($data)
				{
					$this->response([
						'status' => true,
						'data' => $data
						]);
				}
				else
				{
					$this->response([
						'status' => FALSE,
						'error' => 'Invalid insert.'
						], 404);
				}
			}

		}

		public function view_all_reason_post(){
			$data = $this->reason_model->select_all_reason();
			if ($data)
			{
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Record could not be found'
					], 404);
			}
		}

		public function view_reason_post(){
			$reason = $this->post('input_reason');
			$data = $this->reason_model->select_reason($reason);
			if ($data)
			{
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Record could not be found'
					], 404);
			}
		}

		public function save_reason_post(){
			$reason_id = $this->post('reason_id');
			$reason = $this->post('reason');

			//$this->response($reason_id . " " . $reason);

			$data = $this->reason_model->update_reason($reason_id, $reason);
			if ($data)
			{
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Invalid insert.'
					], 404);
			}

		}

		public function deactivate_reason_post(){
			$reason_id = $this->post('reason_id');

			$data = $this->reason_model->deactivate_reason($reason_id);
			if ($data)
			{
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Invalid insert.'
					], 404);
			}
		}
	}

?>
