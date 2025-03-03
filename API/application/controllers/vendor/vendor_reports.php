<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Vendor_reports extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendor/vendor_reports_model');
		}
		
		public function get_expired_invites_post(){
			$date_from = $this->post('date_from');
			$date_to = $this->post('date_to');
			$cat_filter = $this->post('cat_filter');
			$user_id = $this->post('user_id');

			$data = $this->vendor_reports_model->get_expired_invites($date_from, $date_to, $user_id, $cat_filter);
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

		//added JRM June 23, 2021
		public function get_pending_invites_post(){
			$userIDs = $this->post('userIDs');
			$data = $this->vendor_reports_model->get_pending_invites($userIDs);
			if($data)
				$this->response($data);
			else
				$this->response(['status' => FALSE, 'error' => 'Record could not be found'],404);
		}
		
		//added JRM June 21, 2021
		public function get_usernames_get(){
			$data = $this->vendor_reports_model->get_usernames();
			if($data)
				$this->response($data);
			else
				$this->response(['status' => FALSE, 'error' => 'Record could not be found'],404);
			
		}
		//added JRM June 25, 2021
		public function get_vendor_codes_get(){
			$data = $this->vendor_reports_model->get_vendor_codes();
			if($data)
				$this->response($data);
			else
				$this->response(['status' => FALSE, 'error' => 'Record could not be found'],404);
			
		}
		//added JRM June 25, 2021
		public function get_contacts_per_vendor_post(){
			$VCodes = $this->post('VCodes');
			$data = $this->vendor_reports_model->get_contacts_per_vendor($VCodes);
			if($data)
				$this->response($data);
			else
				$this->response(['status' => FALSE, 'error' => 'Record could not be found'],404);
		}
		
		public function get_active_inacvtive_user_post(){
			$user_type = $this->post('user_type');
			$user_status = $this->post('user_status');

			$data = $this->vendor_reports_model->get_active_inacvtive_user($user_type, $user_status);
			if ($data){
				$this->response($data);
			}else{
				$this->response(['status' => FALSE,'error' => 'Record could not be found'], 404);
			}		
		}

		//MSF 2022-12-23
		public function get_active_inactive_report_post(){
			$userIDs = $this->post('userIDs');
			$data = $this->vendor_reports_model->get_active_inactive_report($userIDs);
			if($data)
				$this->response($data);
			else
				$this->response(['status' => FALSE, 'error' => 'Record could not be found'],404);
		}

		//MSF 2023-1-3
		public function get_contact_person_per_vendor_post(){
			$vendor_id = $this->post('vendorIDs');
			$date_from = $this->post('date_from');
			$date_to = $this->post('date_to');
			$data = $this->vendor_reports_model->get_contact_person_per_vendor($vendor_id, $date_from, $date_to);
			if($data)
				$this->response($data);
			else
				$this->response(['status' => FALSE, 'error' => 'Record could not be found'],404);
		}

		public function get_contact_persons_post(){
			$date = $this->post('date');
			$data = $this->vendor_reports_model->get_contact_persons($date);
			if($data)
				$this->response($data);
			else
				$this->response(['status' => FALSE, 'error' => 'Record could not be found'],404);
		}
		
		public function get_expired_config_post(){
			$data = $this->vendor_report_model->get_expired_config();
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


		public function get_deactivated_account_post(){
			$date_from = $this->post('date_from');
			$date_to = $this->post('date_to');
			$cat_filter = $this->post('cat_filter');
			$user_id = $this->post('user_id');
			
			$data = $this->vendor_reports_model->get_deactivated_account($date_from, $date_to, $user_id, $cat_filter);
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
		
		//Added JRM - June 16, 2021
		public function get_deleted_vendor_invites_post(){
			$delType = $this->post('delType');
			$user_id = $this->post('user_id');
			
			$data = $this->vendor_reports_model->get_deleted_vendor_invites($delType, $user_id);
			
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
		
		// Added MSF - 20191126 (IJR-10619)
		public function get_completed_accounts_post(){
			$date_from = $this->post('date_from');
			$date_to = $this->post('date_to');
			$cat_filter = $this->post('cat_filter');
			$user_id = $this->post('user_id');
			
			$data = $this->vendor_reports_model->get_completed_accounts($date_from, $date_to, $user_id, $cat_filter);
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

		// Added MSF - 20191126 (IJR-10619)
		public function get_validation_schedule_post(){
			$date_from = $this->post('date_from');
			$date_to = $this->post('date_to');
			$cat_filter = $this->post('cat_filter');
			$user_id = $this->post('user_id');
			
			$data = $this->vendor_reports_model->get_validation_schedule($date_from, $date_to, $user_id, $cat_filter);
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

		// public function view_orgtype_reqdocs_post(){
		// 	$orgtype_id = $this->post('input_orgtype_id');
		// 	$vendortype_id = $this->post('input_vendortype_id');
		// 	$data = $this->orgtype_reqdocs_assignments_model->select_orgtype_reqdocs($orgtype_id,$vendortype_id);
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

		// public function view_orgtype_reqdocs_not_in_post(){
		// 	$orgtype_id = $this->post('input_orgtype_id');
		// 	$vendortype_id = $this->post('input_vendortype_id');
		// 	$data = $this->orgtype_reqdocs_assignments_model->select_orgtype_reqdocs_not_in($orgtype_id,$vendortype_id);
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

		// public function save_docs_post(){
		// 	$docs_data = $this->post('docs_data');
		// 	$orgtype_id = $this->post('orgtype_id');
		// 	$vendortype_id = $this->post('vendortype_id');
		// 	$created_by = $this->post('created_by');
		// 	$data = $this->orgtype_reqdocs_assignments_model->save_docs($orgtype_id, $docs_data, $created_by,$vendortype_id);
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