<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Contracts_and_agreements extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendorparam/contracts_and_agreements_model');
		}
		

		
		// Server's Get Method
		public function add_ca_post(){
			$ca_name = $this->post('ca_name');
			$description = $this->post('description');
			$bus_division = $this->post('bus_division');
			$downloadable = $this->post('downloadable');
			$viewable = $this->post('viewable');
			$tool_tip = $this->post('tool_tip');

			$data = $this->contracts_and_agreements_model->insert_ca($ca_name, $description, $bus_division, $downloadable, $viewable, $tool_tip);
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
		
		public function save_ca_post(){
			$ca_id = $this->post('ca_id');
			$ca_name = $this->post('ca_name');
			$description = $this->post('description');
			$bus_division = $this->post('bus_division');
			$downloadable = $this->post('downloadable');
			$viewable = $this->post('viewable');
			$tool_tip = $this->post('tool_tip');


			$data = $this->contracts_and_agreements_model->update_ca($ca_id, $ca_name, $description, $bus_division, $downloadable, $viewable, $tool_tip);
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
		
		public function deactivate_ca_post(){
			$ca_id = $this->post('ca_id');

			$data = $this->contracts_and_agreements_model->deactivate_ca($ca_id);
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
		
		
		public function view_all_ca_post(){
			$data = $this->contracts_and_agreements_model->select_all_ca();
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
		
		public function view_ca_post(){
			$ca = $this->post('input_ca');
			$data = $this->contracts_and_agreements_model->select_ca($ca);
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

		public function save_sample_file_post()
		{
			$ca_id = $this->post('ca_id');
			$sample_file = $this->post('sample_file');

			$data = $this->contracts_and_agreements_model->save_sample_file($ca_id, $sample_file);
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

		public function get_sample_file_post()
		{
			$ca_id = $this->post('ca_id');

			$data = $this->contracts_and_agreements_model->get_sample_file($ca_id);
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