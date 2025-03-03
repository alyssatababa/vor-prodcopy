<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Incomplete_documents_reasons extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendorparam/incomplete_documents_reasons_model');
		}
		

		
		// Server's Get Method
		public function add_incdocreasons_post(){
			$incdocreasons_name = $this->post('incdocreasons_name');
			$document_type_id = $this->post('document_type_id');
			$document_name = $this->post('document_name');
			$created_by = $this->post('created_by');

			$data = $this->incomplete_documents_reasons_model->insert_incdocreasons($incdocreasons_name, $document_type_id, $document_name, $created_by);
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
		
		public function save_incdocreasons_post(){
			$incdocreasons_id = $this->post('incdocreasons_id');
			$incdocreasons_name = $this->post('incdocreasons_name');
			$document_type_id = $this->post('document_type_id');
			$document_name = $this->post('document_name');

			$data = $this->incomplete_documents_reasons_model->update_incdocreasons($incdocreasons_id, $incdocreasons_name, $document_type_id, $document_name);
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
		
		public function deactivate_incdocreasons_post(){
			$incdocreasons_id = $this->post('incdocreasons_id');

			$data = $this->incomplete_documents_reasons_model->deactivate_incdocreasons($incdocreasons_id);
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
		
		
		public function view_all_incdocreasons_post(){
			$data = $this->incomplete_documents_reasons_model->select_all_incdocreasons();
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
		
		public function view_incdocreasons_post(){
			$search_incdocreasons = $this->post('search_incdocreasons');
			$data = $this->incomplete_documents_reasons_model->select_incdocreasons($search_incdocreasons);
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