<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Orgtype_reqdocs_assignments extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendorparam/organizationtype_model');
			$this->load->model('vendorparam/required_documents_model');
			$this->load->model('vendorparam/orgtype_reqdocs_assignments_model');
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

		public function view_all_reqdocs_post(){
			$data = $this->required_documents_model->select_all_reqdocs();
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

		public function view_orgtype_reqdocs_post(){
			$orgtype_id = $this->post('input_orgtype_id');
			$vendortype_id = $this->post('input_vendortype_id');
			$data = $this->orgtype_reqdocs_assignments_model->select_orgtype_reqdocs($orgtype_id,$vendortype_id);
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

		public function view_orgtype_reqdocs_not_in_post(){
			$orgtype_id = $this->post('input_orgtype_id');
			$vendortype_id = $this->post('input_vendortype_id');
			$data = $this->orgtype_reqdocs_assignments_model->select_orgtype_reqdocs_not_in($orgtype_id,$vendortype_id);
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

		public function save_docs_post(){
			$docs_data = $this->post('docs_data');
			$orgtype_id = $this->post('orgtype_id');
			$vendortype_id = $this->post('vendortype_id');
			$created_by = $this->post('created_by');
			$data = $this->orgtype_reqdocs_assignments_model->save_docs($orgtype_id, $docs_data, $created_by,$vendortype_id);
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


		// public function view_all_screens_post(){
		// 	$data = $this->role_management_model->select_all_screens();
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

		// public function view_screens_post(){
		// 	$orgtype_id = $this->post('input_orgtype_id');
		// // 	$data = $this->required_documents_model->select_reqdocs($reqdocs);
		// 	$data = $this->role_management_model->select_screens($orgtype_id);
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

		// public function view_screens_not_in_post(){
		// 	$orgtype_id = $this->post('input_orgtype_id');
		// // 	$data = $this->required_documents_model->select_reqdocs($reqdocs);
		// 	$data = $this->role_management_model->select_screens_not_in($orgtype_id);
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

		// public function save_screens_post(){
		// 	$screens_data = $this->post('screens_data');
		// 	$orgtype_id = $this->post('orgtype_id');
		// 	$data = $this->role_management_model->save_screens($orgtype_id, $screens_data);
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