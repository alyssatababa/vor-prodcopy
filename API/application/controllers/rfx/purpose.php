<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Purpose extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfx/purpose_model');
		}

		// Server's Get Method
		public function add_purpose_post(){
			$purpose = $this->post('purpose');
			//$this->response($purpose);
			$data_exist = $this->purpose_model->select_purpose($purpose);
			if ($data_exist)
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Purpose already exists.'
					], 404);
			} else {
				$data = $this->purpose_model->insert_purpose($purpose);
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

		public function view_all_purpose_post(){
			$data = $this->purpose_model->select_all_purpose();
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

		public function view_purpose_post(){
			$purpose = $this->post('input_purpose');
			$data = $this->purpose_model->select_purpose($purpose);
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

		public function save_purpose_post(){
			$purpose_id = $this->post('purpose_id');
			$purpose = $this->post('purpose');

			//$this->response($purpose_id . " " . $purpose);

			$data = $this->purpose_model->update_purpose($purpose_id, $purpose);
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

		public function deactivate_purpose_post(){
			$purpose_id = $this->post('purpose_id');

			$data = $this->purpose_model->deactivate_purpose($purpose_id);
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
