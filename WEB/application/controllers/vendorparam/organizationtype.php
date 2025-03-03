<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrganizationType extends CI_Controller {

	/*
	public function __construct() {
		parent::__construct();
		$this->load->library('rest', $this->config->item('app_server_api'));
	}
	*/

	public function index()
	{
		//$this->load->view('vendorparam/organizationtype');
		$this->load_orgtype();
	}
	
	public function add_orgtype(){
		$data['user_id'] = $this->session->userdata('user_id');
		$data['orgtype_name'] = $this->input->post('orgtype_name');
		$data['description'] = $this->input->post('description');
		$data['bus_division'] = $this->input->post('bus_division');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/organizationtype/add_orgtype', $data, '');
		echo $data['result_data'];
	}
	
	public function load_orgtype(){
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/organizationtype/view_all_orgtype', '', '');
		//var_dump($data['result_data']);
		//echo json_encode($data['result_data']);
		$this->load->view('vendorparam/organizationtype' , $data);
	}
	
	public function get_orgtype(){
		$data['input_orgtype'] = $this->input->post('orgtype');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/organizationtype/view_orgtype', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('vendorparam/organizationtype' , $data);
	}
	
	public function get_all_orgtype(){
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/organizationtype/view_all_orgtype', '', '');
		//var_dump(json_encode($data['result_data']));
		//$this->rest_app->debug();
		echo json_encode($data['result_data']);
		//$this->load->view('vendorparam/organizationtype' , $data);
	}
	
	public function save_orgtype(){
		$data['orgtype_id'] = $this->input->post('orgtype_id');
		$data['orgtype_name'] = $this->input->post('orgtype_name');
		$data['description'] = $this->input->post('description');
		$data['bus_division'] = $this->input->post('bus_division');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/organizationtype/save_orgtype', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_orgtype(){
		$data['orgtype_id'] = $this->input->post('orgtype_id');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/organizationtype/deactivate_orgtype', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}	
}
