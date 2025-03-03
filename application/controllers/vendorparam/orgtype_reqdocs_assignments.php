<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orgtype_reqdocs_assignments extends CI_Controller {

	public function index()
	{
		
		$this->load->view('vendorparam/orgtype_reqdocs_assignments');
	}

	public function get_orgtype(){
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/view_all_orgtype', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_all_reqdocs(){
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/view_all_reqdocs', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_reqdocs(){
		$data['input_orgtype_id'] = $this->input->post('orgtype_id');
		$data['input_vendortype_id'] = $this->input->post('vendortype_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/view_orgtype_reqdocs', $data, '');
		echo json_encode($data['result_data']);
	}

	public function get_reqdocs_not_in(){
		$data['input_orgtype_id'] = $this->input->post('orgtype_id');
		$data['input_vendortype_id'] = $this->input->post('vendortype_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/view_orgtype_reqdocs_not_in', $data, '');
		echo json_encode($data['result_data']);
	}

	public function save_docs(){
		$data['docs_data'] = $this->input->post('req_docs');
		$data['orgtype_id'] = $this->input->post('orgtype_id');
		$data['vendortype_id'] = $this->input->post('vendortype_id');
		$data['created_by'] = $this->session->userdata('user_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/save_docs', $data, '');
		echo json_encode($data['result_data']);		
		// echo "1";
	}

	// public function get_all_screens(){
	// 	$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/view_all_screens', '', '');
	// 	echo json_encode($data['result_data']);
	// }

	// public function get_screens(){
	// 	$data['input_orgtype_id'] = $this->input->post('orgtype_id');
	// 	// $data['result_data'] = $this->rest_app->post('index.php/vendorparam/required_documents/view_reqdocs', $data, '');
	// 	$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/view_screens', $data, '');
	// 	echo json_encode($data['result_data']);
	// }

	// public function get_screens_not_in(){
	// 	$data['input_orgtype_id'] = $this->input->post('orgtype_id');
	// 	// $data['result_data'] = $this->rest_app->post('index.php/vendorparam/required_documents/view_reqdocs', $data, '');
	// 	$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/view_screens_not_in', $data, '');
	// 	echo json_encode($data['result_data']);
	// }

	// public function save_screens(){
	// 	$data['screens_data'] = $this->input->post('screens');
	// 	$data['orgtype_id'] = $this->input->post('orgtype_id');
		
	// 	$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_reqdocs_assignments/save_screens', $data, '');
	// 	echo json_encode($data['result_data']);		
	// 	// echo "1";
	// }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */