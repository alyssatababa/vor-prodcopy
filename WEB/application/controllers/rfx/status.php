<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('rfx/status');
	}
	
	public function get_all_status(){
		$data['result_data'] = $this->rest_app->post('index.php/rfx/status/view_all_status', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/status' , $data);
	}
	
	public function get_status(){
		$data['input_status'] = $this->input->post('rfx_status');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/status/view_status', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/currency' , $data);
	}
	
	public function add_status(){
		$data['status'] = $this->input->post('rfx_status');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/status/add_status', $data, '');
		//var_dump($data['result_data']);
		echo $data['result_data'];
		//echo $data['status'];
	}

	public function save_status(){
		$data['status_id'] = $this->input->post('status_id');
		$data['status'] = $this->input->post('rfx_status');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/status/save_status', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_status(){
		$data['status_id'] = $this->input->post('status_id');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfx/status/deactivate_status', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}		
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */