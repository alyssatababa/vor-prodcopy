<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Orgtype_secagmt_assignments extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendorparam/organizationtype_model');
			$this->load->model('vendorparam/secondary_documents_model');
			$this->load->model('vendorparam/orgtype_secagmt_assignments_model');
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

		public function view_all_secagmt_post(){
			$data = $this->secondary_documents_model->select_all_secagmt();
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

		public function view_orgtype_secagmt_post(){
			$orgtype_id = $this->post('input_orgtype_id');
			$vendortype_id = $this->post('input_vendortype_id');
			$data = $this->orgtype_secagmt_assignments_model->select_orgtype_secagmt($orgtype_id,$vendortype_id);
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

		public function view_orgtype_secagmt_not_in_post(){
			$orgtype_id = $this->post('input_orgtype_id');
			$vendortype_id = $this->post('input_vendortype_id');
			$data = $this->orgtype_secagmt_assignments_model->select_orgtype_secagmt_not_in($orgtype_id,$vendortype_id);
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

		public function save_agmt_post(){
			/*$agmt_data = $this->post('agmt_data');
			$orgtype_id = $this->post('orgtype_id');
			$vendortype_id = $this->post('vendortype_id');
			$created_by = $this->post('created_by');
			$data = $this->orgtype_secagmt_assignments_model->save_agmt($orgtype_id, $agmt_data, $created_by,$vendortype_id);
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
			}	*/
			$matrix = $this->post('matrix');
			$itms = $this->post('itms');
			$mx['type'] = $matrix[1];
			$mx['org'] = $matrix[0];
			$mx['tradevendortype'] = $matrix[2]; //vendor type || category
			$mx['vtoc'] = $matrix[3];
			$mx['user_id'] = $this->post('user_id');



			$rs = $this->orgtype_secagmt_assignments_model->save_agmt($itms,$mx);


			$this->response($rs);




		}


		public function categories_nontrade_get()
		{

			$category = $this->orgtype_secagmt_assignments_model->select_category();


			$this->response($category);

		}

		public function categories_trade_get()
		{

			$category = $this->orgtype_secagmt_assignments_model->select_category_trade();

			$this->response($category);

		}

		public function view_all_sec_get()
		{

			$data = $this->get('snd');
			$rs = $this->orgtype_secagmt_assignments_model->select_files($data);
			$this->response($rs);
		}

		public function restore_default_get()
		{
			$data = $this->get('mtx');
			if(!isset($data)){
				$this->response("error");
			}


			$owner['OWNERSHIP_ID'] = $data[0];
			$cat['CATEGORY_ID'] = $data[2];
			$vtype['VENDOR_TYPE_ID'] = 3;

			$rs = $this->orgtype_secagmt_assignments_model->restore_def($cat,$owner);
			
			$this->response($rs);

		}

		public function delete_agmt_get()
		{

			$data = $this->get('mtx');
			if(!isset($data)){
				$this->response("error");
			}
			$owner['OWNERSHIP_ID'] = $data[0];
			$cat['CATEGORY_ID'] = $data[3];
			$rs = $this->orgtype_secagmt_assignments_model->delete_def($cat,$owner);
			$this->response($rs);


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
		// // 	$data = $this->secondary_documents_model->select_secagmt($secagmt);
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
		// // 	$data = $this->secondary_documents_model->select_secagmt($secagmt);
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