<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('user_agent', 'Mozilla/5.0');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class vendor_request_pass_id extends REST_Controller
{
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/vendor_request_pass_id_model');
	}

	
	public function vendor_request_insert_post(){
		$vendor_invite_id = $this->input->post('vendor_invite_id');
		$approval_date = $this->input->post('approval_date');
		$approval_date = date('Y-m-d', strtotime($approval_date));
		$vendorname = $this->input->post('vendorname');
		$vendor_code = $this->input->post('vendor_code');
		$vendor_code_02 = $this->input->post('vendor_code_02');
		$req_emailadd_outright = $this->input->post('req_emailadd_outright');
		$req_emailadd_sc = $this->input->post('req_emailadd_sc');

		//OUTRIGHT
		$vendor_pass = $this->input->post('vendor_pass');
		$vendor_pass = json_decode(stripslashes($vendor_pass));
		$vendor_qty_outright = $vendor_pass[0] ? $vendor_pass[0] : 0;
		$reqtype_pass_outright = $vendor_pass[1];

		//SC
		$vendor_qty_sc = $vendor_pass[2] ? $vendor_pass[2] : 0;
		$reqtype_pass_sc = $vendor_pass[3];

		//TOTAL QTY
		$total_qty = $vendor_pass[4] ? $vendor_pass[4] : 0;
		$total_amount = $vendor_pass[5];
		$user_id = $this->input->post('user_id');


		$vendorid = $this->input->post('vendorid');

		
		

		$data = $this->vendor_request_pass_id_model->vendor_request_insert($vendor_invite_id, $approval_date, $vendorname, $vendor_code, $vendor_code_02, $req_emailadd_outright, $req_emailadd_sc, $vendor_qty_outright, $reqtype_pass_outright, $vendor_qty_sc, $reqtype_pass_sc, $total_qty, $total_amount, $user_id, $vendorid);

		//var_dump($data); die();

		if ($data)
		{
			$this->response($data);
		}else
		{
			$this->response([
				'status' => FALSE,
				'error' => 'Invalid insert.'
				], 404);
		}	
	}

	public function check_vendor_request_post(){

		$vendor_invite_id = $this->input->post('vendor_invite_id');

		$data = $this->vendor_request_pass_id_model->check_vendor_request($vendor_invite_id);

		//print_r($data); exit();

		$this->response($data);
	}

	public function check_pass_qty_post(){
		$vendor_invite_id = $this->input->post('vendor_invite_id');

		$data = $this->vendor_request_pass_id_model->check_pass_qty($vendor_invite_id);

		$this->response($data);
	}

	public function vendor_email_insert_post(){
		$vendor_invite_id = $this->input->post('vendor_invite_id');
		$email_outright = $this->input->post('email_outright');
		$email_sc = $this->input->post('email_sc');

		$data = $this->vendor_request_pass_id_model->vendor_email_insert($vendor_invite_id, $email_outright, $email_sc);

		

		$this->response($data);
	}
}