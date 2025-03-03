<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requestor extends CI_Controller {

	/*
	public function __construct() {
		parent::__construct();
		$this->load->library('rest', $this->config->item('app_server_api'));
	}
	*/

	public function index()
	{
		//$this->load->view('rfx/requestor');
		$this->load_requestor();
	}
	
	public function add_requestor(){
		$data['requestor'] = $this->input->post('requestor');
		$data['company'] = $this->input->post('company');
		$data['department'] = $this->input->post('department');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/requestor/add_requestor', $data, '');
		//var_dump($data['result_data']);
		echo $data['result_data'];
	}
	
	public function load_requestor(){
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/requestor/view_all_requestor', '', '');
		//var_dump($data['result_data']);
		//echo json_encode($data['result_data']);
		$this->load->view('rfx/requestor' , $data);
	}
	
	public function sort_requestor(){
		$data['order_by'] = $this->input->post('order_by');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/requestor/sort_requestor', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/requestor' , $data);
	}
	
	public function get_requestor(){
		$data['input_requestor'] = $this->input->post('requestor');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/requestor/view_requestor', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/requestor' , $data);
	}
	
	public function get_all_requestor(){
		$data['result_data'] = $this->rest_app->post('index.php/rfx/requestor/view_all_requestor', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/requestor' , $data);
	}
	
	public function save_requestor(){
		$data['requestor_id'] = $this->input->post('requestor_id');
		$data['requestor'] = $this->input->post('requestor');
		$data['company'] = $this->input->post('company');
		$data['department'] = $this->input->post('department');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/requestor/save_requestor', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_requestor(){
		$data['requestor_id'] = $this->input->post('requestor_id');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/requestor/deactivate_requestor', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}	
}
