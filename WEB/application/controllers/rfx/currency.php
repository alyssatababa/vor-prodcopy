<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency extends CI_Controller {

	/*
	public function __construct() {
		parent::__construct();
		$this->load->library('rest', $this->config->item('app_server_api'));
	}
	*/

	public function index()
	{
		//$this->load->view('rfx/currency');
		$this->load_currency();
	}
	
	public function add_currency(){
		$data['currency'] = $this->input->post('currency');
		$data['abbreviation'] = $this->input->post('abbreviation');
		$data['country'] = $this->input->post('country');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/add_currency', $data, '');
		//var_dump($data['result_data']);
		echo $data['result_data'];
	}
	
	public function load_currency(){
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/view_all_currency', '', '');
		//var_dump($data['result_data']);
		//echo json_encode($data['result_data']);
		$this->load->view('rfx/currency' , $data);
	}
	
	public function sort_currency(){
		$data['order_by'] = $this->input->post('order_by');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/sort_currency', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/currency' , $data);
	}
	
	public function get_currency(){
		$data['input_currency'] = $this->input->post('currency');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/view_currency', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/currency' , $data);
	}
	
	public function get_all_currency(){
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/view_all_currency', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/currency' , $data);
	}

	public function get_default(){
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/get_default', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/currency' , $data);
	}
	
	
	public function save_currency(){
		$data['currency_id'] = $this->input->post('currency_id');
		$data['currency'] = $this->input->post('currency');
		$data['abbreviation'] = $this->input->post('abbreviation');
		$data['country'] = $this->input->post('country');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/save_currency', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_currency(){
		$data['currency_id'] = $this->input->post('currency_id');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/deactivate_currency', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function default_currency(){
		$data['currency_id'] = $this->input->post('currency_id');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/currency/default_currency', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}	
}
