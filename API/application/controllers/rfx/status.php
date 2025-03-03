<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Status extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfx/status_model');
		}
		
		// Server's Get Method
		public function add_status_post(){
			$status = $this->post('status');
			//$this->response($status);
			
			$data = $this->status_model->insert_status($status);
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
		
		public function view_all_status_post(){
			$data = $this->status_model->select_all_status();
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
		
		public function view_status_post(){
			$status = $this->post('input_status');
			$data = $this->status_model->select_status($status);
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
		
		public function save_status_post(){
			$status_id = $this->post('status_id');
			$status = $this->post('status');

			//$this->response($status_id . " " . $status);
			
			$data = $this->status_model->update_status($status_id, $status);
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
		
		public function deactivate_status_post(){
			$status_id = $this->post('status_id');

			$data = $this->status_model->deactivate_status($status_id);
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