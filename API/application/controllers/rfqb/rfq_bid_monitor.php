<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Rfq_bid_monitor extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfqb/rfq_bid_monitor_model');
		}
		
		public function view_rfqb_post(){
			$rfb_id = $this->post('rfb_id');
			$data = $this->rfq_bid_monitor_model->select_rfqrfb_ipr($rfb_id);
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

		public function view_rfqb_table_post(){
			$rfb_id = $this->post('rfb_id');
			$data = $this->rfq_bid_monitor_model->select_ipr_table($rfb_id);
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

		public function view_rfqb_position_post(){
			$rfb_id = $this->post('rfb_id');
			$user_id = $this->post('user_id');
			$data = $this->rfq_bid_monitor_model->select_rfqrfb_position($rfb_id);
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

		public function set_rfqb_status_post(){
			$user_id = $this->post('user_id');
			$rfb_id = $this->post('rfb_id');
			$rfb_status = $this->post('rfb_status');
			$reason = $this->post('reason');
			$position_id = $this->post('position_id');
			$extension_date = $this->post('extension_date');
			$invite_status = 0;
			$curr_status = 0;

			$data = $this->rfq_bid_monitor_model->set_rfqrfb_status($rfb_id, $rfb_status, $reason, $position_id, $extension_date, $invite_status, $user_id, $curr_status);
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

		public function set_sub_deadline_post(){
			$rfb_id = $this->post('rfb_id');
			$sub_date = $this->post('sub_date');
			$data = $this->rfq_bid_monitor_model->set_sub_deadline($rfb_id, $sub_date);
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
		
		public function get_rfqb_config_post(){
			$rfb_id = $this->post('rfb_id');
			$data = $this->rfq_bid_monitor_model->get_rfqrfb_config($rfb_id);
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
		

		
	}

?>