<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('user_agent', 'Mozilla/5.0');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class vendor_id_pass extends REST_Controller
{
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/vendor_id_pass_model');
		$this->load->model('common_model');
		$this->load->model('mail_model');
		$this->load->helper('message_helper');
		$this->load->model('users_model');
	}
	
	
	function view_all_data_get(){
		$rs = $this->vendor_id_pass_model->get_all_data();
		
		if ($rs)
		{
			$data = $rs;
			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}


		
	public function view_vendor_id_pass_post(){
		$vendor_id_pass_data = $this->post('input_vendor_id_pass_desc');
		$data = $this->vendor_id_pass_model->search_vendor_id_pass($vendor_id_pass_data);
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

	public function save_vendor_id_pass_post(){
		$request_type_name = $this->post('request_type_name');
		$request_type_code = $this->post('request_type_code');
		$description = $this->post('description');
		$user_id = $this->post('user_id');

		$data = $this->vendor_id_pass_model->insert_vendor_id_pass($request_type_name, $request_type_code, $description, $user_id);
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

	public function update_vendor_id_pass_post(){
		$request_type_id = $this->post('request_type_id');
		$request_type_name = $this->post('request_type_name');
		$request_type_code = $this->post('request_type_code');
		$description = $this->post('description');
		$date_created = $this->post('date_created');

		$data = $this->vendor_id_pass_model->update_vendor_id_pass($request_type_id, $request_type_name, $request_type_code, $description);
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

	public function remove_vendor_id_pass_post(){
		$request_type_id = $this->post('request_type_id');
		$user_id = $this->post('user_id');

		$data = $this->vendor_id_pass_model->remove_vendor_id_pass($request_type_id, $user_id);
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