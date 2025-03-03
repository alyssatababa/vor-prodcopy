<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Incomplete_documents_reasons extends CI_Controller {

	/*
	public function __construct() {
		parent::__construct();
		$this->load->library('rest', $this->config->item('app_server_api'));
	}
	*/

	public function index()
	{
		//$this->load->view('vendorparam/incomplete_documents_reasons');
		$this->load_incdocreasons();
	}
	
	public function add_incdocreasons(){
		$data['incdocreasons_name'] = $this->input->post('incdocreasons_name');
		$data['document_type_id'] = $this->input->post('document_type_id');
		$data['document_name'] = $this->input->post('document_name');
		$data['created_by'] = $this->session->userdata['user_id'];
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/incomplete_documents_reasons/add_incdocreasons', $data, '');
		//var_dump($data['result_data']);
		echo $data['result_data'];
	}
	
	public function load_incdocreasons(){
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/incomplete_documents_reasons/view_all_incdocreasons', '', '');
		//var_dump($data['result_data']);
		//echo json_encode($data['result_data']);
		$this->load->view('vendorparam/incomplete_documents_reasons' , $data);
	}
	
	public function get_incdocreasons(){
		$data['search_incdocreasons'] = $this->input->post('search_incdocreasons');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/incomplete_documents_reasons/view_incdocreasons', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('vendorparam/incomplete_documents_reasons' , $data);
	}
	
	public function get_all_incdocreasons(){
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/incomplete_documents_reasons/view_all_incdocreasons', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('vendorparam/incomplete_documents_reasons' , $data);
	}
	
	public function save_incdocreasons(){
		$data['incdocreasons_id'] = $this->input->post('incdocreasons_id');
		$data['incdocreasons_name'] = $this->input->post('incdocreasons_name');
		$data['document_type_id'] = $this->input->post('document_type_id');
		$data['document_name'] = $this->input->post('document_name');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/incomplete_documents_reasons/save_incdocreasons', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_incdocreasons(){
		$data['incdocreasons_id'] = $this->input->post('incdocreasons_id');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/incomplete_documents_reasons/deactivate_incdocreasons', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}	

}
