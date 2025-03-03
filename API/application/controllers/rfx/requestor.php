<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Requestor extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfx/requestor_model');
		}
		
		// Server's Get Method
		public function add_requestor_post(){
			$requestor = $this->post('requestor');
			$company = $this->post('company');
			$department = $this->post('department');

			$data = $this->requestor_model->insert_requestor($requestor, $company, $department);
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
		
		public function save_requestor_post(){
			$requestor_id = $this->post('requestor_id');
			$requestor = $this->post('requestor');
			$company = $this->post('company');
			$department = $this->post('department');

			$data = $this->requestor_model->update_requestor($requestor_id, $requestor, $company, $department);
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
		
		public function deactivate_requestor_post(){
			$requestor_id = $this->post('requestor_id');

			$data = $this->requestor_model->deactivate_requestor($requestor_id);
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
		
		public function view_all_requestor_post(){
			$data = $this->requestor_model->select_all_requestor();
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
		
		public function view_requestor_post(){
			$requestor = $this->post('input_requestor');
			$data = $this->requestor_model->select_requestor($requestor);
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
		
		public function sort_requestor_post(){
			$order_by = $this->post('order_by');
			$data = $this->requestor_model->sort_requestor($order_by);
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
	}

?>