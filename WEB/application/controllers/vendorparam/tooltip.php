<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tooltip extends CI_Controller {

	/*
	public function __construct() {
		parent::__construct();
		$this->load->library('rest', $this->config->item('app_server_api'));
	}
	*/

	public function index()
	{
		//$this->load->view('vendorparam/required_documents');
		$this->load_tooltip();
	}
	
	public function load_tooltip(){
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/tooltip/view_all_tooltip', '', '');
		//var_dump($data['result_data']);
		//echo json_encode($data['result_data']);
		$this->load->view('vendorparam/tooltip' , $data);
	}
	
	public function get_tooltip(){
		$data['input_tooltip'] = $this->input->post('tooltip');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/tooltip/view_tooltip', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('vendorparam/required_documents' , $data);
	}
	
	public function get_all_tooltip(){
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/tooltip/view_all_tooltip', '', '');

		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('vendorparam/required_documents' , $data);
	}
	
	public function save_tooltip(){
		$data['tooltip_id'] = $this->input->post('tooltip_id');
		$data['screen_name'] = $this->input->post('tooltip_name');
		$data['tooltip'] = $this->input->post('description');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/tooltip/save_tooltip', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}
}
