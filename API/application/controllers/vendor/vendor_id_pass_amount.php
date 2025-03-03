<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('user_agent', 'Mozilla/5.0');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class vendor_id_pass_amount extends REST_Controller
{
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/vendor_id_pass_amount_model');
		$this->load->model('common_model');
		$this->load->model('mail_model');
		$this->load->helper('message_helper');
		$this->load->model('users_model');
	}
	
	
	function view_all_data_get(){
		$rs = $this->vendor_id_pass_amount_model->get_all_data();
		
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


		
	public function view_vendor_id_pass_amount_post(){
		$vendor_id_pass_amount_data = $this->post('input_vendor_id_pass_amount_desc');
		$data = $this->vendor_id_pass_amount_model->search_vendor_id_pass_amount($vendor_id_pass_amount_data);
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

	public function save_vendor_id_pass_amount_post(){
		$amount = $this->post('amount');
		$description = $this->post('description');
		$effectivity_date = $this->post('effectivity_date');
		$user_id = $this->post('user_id');

		$data = $this->vendor_id_pass_amount_model->insert_vendor_id_pass_amount($amount, $description, $effectivity_date, $user_id);
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

	public function update_vendor_id_pass_amount_post(){
		$amount_id = $this->post('amount_id');
		$amount = $this->post('amount');
		$description = $this->post('description');
		$date_created = $this->post('date_created');
		$effectivity_date = $this->post('effectivity_date');
		$user_id = $this->post('user_id');

		$data = $this->vendor_id_pass_amount_model->update_vendor_id_pass_amount($amount_id, $amount, $description, $effectivity_date, $user_id);
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

	public function remove_vendor_id_pass_amount_post(){
		$amount_id = $this->post('amount_id');
		$user_id = $this->post('user_id');

		$data = $this->vendor_id_pass_amount_model->remove_vendor_id_pass_amount($amount_id, $user_id);
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