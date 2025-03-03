<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Role_management extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('maintenance/role_management_model');
		}
		
		public function view_position_post(){
			$data = $this->role_management_model->select_position();
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
		
		public function view_vendor_type_post(){
			$data = $this->role_management_model->select_vendor_type();
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
		
		public function view_all_screens_post(){
			$data = $this->role_management_model->select_all_screens();
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

		public function view_screens_post(){
			$position_id = $this->post('input_position_id');
			$vendor_type_id = $this->post('vendor_type_id');
		// 	$data = $this->required_documents_model->select_reqdocs($reqdocs);
			$data = $this->role_management_model->select_screens($position_id, $vendor_type_id);
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

		public function view_screens_not_in_post(){
			$position_id = $this->post('input_position_id');
			$vendor_type_id = $this->post('vendor_type_id');
		// 	$data = $this->required_documents_model->select_reqdocs($reqdocs);
			$data = $this->role_management_model->select_screens_not_in($position_id, $vendor_type_id);
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

		public function save_screens_post(){
			$screens_data = $this->post('screens_data');
			$position_id = $this->post('position_id');
			$vendor_type_id = $this->post('vendor_type_id');
			$data = $this->role_management_model->save_screens($position_id, $screens_data, $vendor_type_id);
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





		// // Server's Get Method
		// public function add_reqdocs_post(){
		// 	$reqdocs_name = $this->post('reqdocs_name');
		// 	$description = $this->post('description');
		// 	$bus_division = $this->post('bus_division');
		// 	$created_by = $this->post('created_by');

		// 	$data = $this->required_documents_model->insert_reqdocs($reqdocs_name, $description, $bus_division, $created_by);
		// 	if ($data)
		// 	{
		// 		$this->response($data);
		// 	}
		// 	else
		// 	{
		// 		$this->response([
		// 			'status' => FALSE,
		// 			'error' => 'Invalid insert.'
		// 			], 404);
		// 	}			
		// }
		
		// public function save_reqdocs_post(){
		// 	$reqdocs_id = $this->post('reqdocs_id');
		// 	$reqdocs_name = $this->post('reqdocs_name');
		// 	$description = $this->post('description');
		// 	$bus_division = $this->post('bus_division');

		// 	$data = $this->required_documents_model->update_reqdocs($reqdocs_id, $reqdocs_name, $description, $bus_division);
		// 	if ($data)
		// 	{
		// 		$this->response($data);
		// 	}
		// 	else
		// 	{
		// 		$this->response([
		// 			'status' => FALSE,
		// 			'error' => 'Invalid insert.'
		// 			], 404);
		// 	}			
		// }
		
		// public function deactivate_reqdocs_post(){
		// 	$reqdocs_id = $this->post('reqdocs_id');

		// 	$data = $this->required_documents_model->deactivate_reqdocs($reqdocs_id);
		// 	if ($data)
		// 	{
		// 		$this->response($data);
		// 	}
		// 	else
		// 	{
		// 		$this->response([
		// 			'status' => FALSE,
		// 			'error' => 'Invalid insert.'
		// 			], 404);
		// 	}			
		// }
		
		
		// public function view_all_reqdocs_post(){
		// 	$data = $this->required_documents_model->select_all_reqdocs();
		// 	if ($data)
		// 	{	
		// 		$this->response($data);
		// 	}
		// 	else
		// 	{
		// 		$this->response([
		// 			'status' => FALSE,
		// 			'error' => 'Record could not be found'
		// 			], 404);
		// 	}		
		// }
		
		// public function view_reqdocs_post(){
		// 	$reqdocs = $this->post('input_reqdocs');
		// 	$data = $this->required_documents_model->select_reqdocs($reqdocs);
		// 	if ($data)
		// 	{
		// 		$this->response($data);
		// 	}
		// 	else
		// 	{
		// 		$this->response([
		// 			'status' => FALSE,
		// 			'error' => 'Record could not be found'
		// 			], 404);
		// 	}					
		// }
		
	}

?>