<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class OrganizationType extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendorparam/organizationtype_model');
		}
		

		
		// Server's Get Method
		public function add_orgtype_post(){
			$orgtype_name = $this->post('orgtype_name');
			$description = $this->post('description');
			$bus_division = $this->post('bus_division');
			$created_by = $this->post('user_id');

			$data = $this->organizationtype_model->insert_orgtype($orgtype_name, $description, $bus_division, $created_by);
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
		
		public function save_orgtype_post(){
			$orgtype_id = $this->post('orgtype_id');
			$orgtype_name = $this->post('orgtype_name');
			$description = $this->post('description');
			$bus_division = $this->post('bus_division');

			$data = $this->organizationtype_model->update_orgtype($orgtype_id, $orgtype_name, $description, $bus_division);
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
		
		public function deactivate_orgtype_post(){
			$orgtype_id = $this->post('orgtype_id');

			$data = $this->organizationtype_model->deactivate_orgtype($orgtype_id);
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
		
		
		public function view_all_orgtype_post(){
			$data = $this->organizationtype_model->select_all_orgtype();
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
		
		public function view_orgtype_post(){
			$orgtype = $this->post('input_orgtype');
			$data = $this->organizationtype_model->select_orgtype($orgtype);
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