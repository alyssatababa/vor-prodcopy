<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class vendor_id_pass_amount extends CI_Controller {
	
	public function index()
	{
		$this->load_smvendortypes();
	}
	
	public function load_smvendortypes(){
		
		$data['result_data'] = $this->rest_app->get('index.php/vendor/vendor_id_pass_amount/department', '', '');
		$this->load->view('vendor/vendor_id_pass_amount',$data);
	}
	
	
	
	public function get_all_data(){
		$data['result_data'] = $this->rest_app->get('index.php/vendor/vendor_id_pass_amount/view_all_data', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_vendor_id_pass_amount(){
		$data['input_vendor_id_pass_amount_desc'] = $this->input->post('vendor_id_pass_amount_desc');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_id_pass_amount/view_vendor_id_pass_amount', $data, '');
		echo json_encode($data['result_data']);
	}

	public function save_vendor_id_pass_amount(){
		$data['amount'] = $this->input->post('amount');
		$data['description'] = $this->input->post('description');
		$data['effectivity_date'] = $this->input->post('effectivity_date');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_id_pass_amount/save_vendor_id_pass_amount', $data, '');
		echo json_encode($data['result_data']);
	}

	public function update_vendor_id_pass_amount(){
		$data['amount_id'] = $this->input->post('amount_id');
		$data['amount'] = $this->input->post('amount');
		$data['description'] = $this->input->post('description');
		$data['effectivity_date'] = $this->input->post('effectivity_date');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_id_pass_amount/update_vendor_id_pass_amount', $data, '');
		echo json_encode($data['result_data']);
	}

	public function remove_vendor_id_pass_amount(){
		$data['amount_id'] = $this->input->post('amount_id');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_id_pass_amount/remove_vendor_id_pass_amount', $data, '');
		echo json_encode($data['result_data']);	
	}
}