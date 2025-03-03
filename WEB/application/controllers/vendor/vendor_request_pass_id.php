<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class vendor_request_pass_id extends CI_Controller {
	
	public function index()
	{
		$smvs = $this->rest_app->get('index.php/vendor/registration_api/smvs_email', $data, 'application/json');
		$data['user_email'] = $smvs->smvs_email;

		$this->load->view('vendor/registration', $data);
	}
	
	// public function load_requesttypes(){
		
	// 	$data['result_data'] = $this->rest_app->get('index.php/vendor/vendor_request_pass_id/request_type', '', '');
	// 	echo json_encode($data['result_data']);
	// }
	

	public function vendor_request_insert(){

		$data['vendor_invite_id'] = $this->input->post('vendor_invite_id');
		$data['approval_date'] = $this->input->post('approval_date');
		$data['vendorname'] = $this->input->post('vendorname');
		$data['vendor_code'] = $this->input->post('vendor_code');
		$data['vendor_code_02'] = $this->input->post('vendor_code_02');
		$data['req_emailadd_outright'] = $this->input->post('req_emailadd_outright');
		$data['req_emailadd_sc'] = $this->input->post('req_emailadd_sc');
		$data['vendor_pass'] = $this->input->post('vendor_pass');
		$data['request_type'] = $this->input->post('request_type');
		$data['vendorid'] = $this->input->post('vendorid');
		$data['user_id'] = $this->session->userdata('user_id');


		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_request_pass_id/vendor_request_insert', $data, '');

		//var_dump($data); die();
		
		echo($data['result_data']);

	}


	public function vendor_email_insert(){
		$data['vendor_invite_id'] = $this->input->post('vendor_invite_id');
		$data['email_outright'] = $this->input->post('email_outright');
		$data['email_sc'] = $this->input->post('email_sc');

		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_request_pass_id/vendor_email_insert', $data, '');

		//var_dump($data);die;

		echo($data['result_data']);
	}

	
}