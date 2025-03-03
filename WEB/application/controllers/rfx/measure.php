<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Measure extends CI_Controller {

	/*
	public function __construct() {
		parent::__construct();
		$this->load->library('rest', $this->config->item('app_server_api'));
	}
	*/

	public function index()
	{
		//$this->load->view('rfx/measure');
		$this->load_measure();
	}
	
	public function add_measure(){
		$data['measure'] = $this->input->post('measure');
		$data['abbreviation'] = $this->input->post('abbreviation');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/measure/add_measure', $data, '');
		//var_dump($data['result_data']);
		echo $data['result_data'];
	}
	
	public function load_measure(){
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/measure/view_all_measure', '', '');
		//var_dump($data['result_data']);
		//echo json_encode($data['result_data']);
		$this->load->view('rfx/measure' , $data);
	}
	
	public function sort_measure(){
		$data['order_by'] = $this->input->post('order_by');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/measure/sort_measure', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/measure' , $data);
	}
	
	public function get_measure(){
		$data['input_measure'] = $this->input->post('measure');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/measure/view_measure', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/measure' , $data);
	}
	
	public function get_all_measure(){
		$data['result_data'] = $this->rest_app->post('index.php/rfx/measure/view_all_measure', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/measure' , $data);
	}
	
	public function save_measure(){
		$data['measure_id'] = $this->input->post('measure_id');
		$data['measure'] = $this->input->post('measure');
		$data['abbreviation'] = $this->input->post('abbreviation');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/measure/save_measure', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_measure(){
		$data['measure_id'] = $this->input->post('measure_id');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/measure/deactivate_measure', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}	
}
