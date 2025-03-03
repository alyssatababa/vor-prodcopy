<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class vendor_id_pass extends CI_Controller {
	
	public function index()
	{
		$this->load_smvendortypes();
	}
	
	public function load_smvendortypes(){
		
		$data['result_data'] = $this->rest_app->get('index.php/vendor/vendor_id_pass/department', '', '');
		$this->load->view('vendor/vendor_id_pass',$data);
	}
	
	
	
	public function get_all_data(){
		$data['result_data'] = $this->rest_app->get('index.php/vendor/vendor_id_pass/view_all_data', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_vendor_id_pass(){
		$data['input_vendor_id_pass_desc'] = $this->input->post('vendor_id_pass_desc');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_id_pass/view_vendor_id_pass', $data, '');
		echo json_encode($data['result_data']);
	}

	public function save_vendor_id_pass(){
		$data['request_type_name'] = $this->input->post('request_type_name');
		$data['request_type_code'] = $this->input->post('request_type_code');
		$data['description'] = $this->input->post('description');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_id_pass/save_vendor_id_pass', $data, '');
		echo json_encode($data['result_data']);
	}

	public function update_vendor_id_pass(){
		$data['request_type_id'] = $this->input->post('request_type_id');
		$data['request_type_name'] = $this->input->post('request_type_name');
		$data['request_type_code'] = $this->input->post('request_type_code');
		$data['description'] = $this->input->post('description');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_id_pass/update_vendor_id_pass', $data, '');
		echo json_encode($data['result_data']);
	}

	public function remove_vendor_id_pass(){
		$data['request_type_id'] = $this->input->post('request_type_id');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_id_pass/remove_vendor_id_pass', $data, '');
		echo json_encode($data['result_data']);	
	}
}