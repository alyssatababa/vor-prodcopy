<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reason extends CI_Controller {

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
		$this->load->view('rfx/reason');
	}

	public function get_all_reason(){
		$data['result_data'] = $this->rest_app->post('index.php/rfx/reason/view_all_reason', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/reason' , $data);
	}

	public function get_reason(){
		$data['input_reason'] = $this->input->post('reason');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/reason/view_reason', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/currency' , $data);
	}

	public function add_reason(){
		$data['reason'] = $this->input->post('reason');

		$data['result_data'] = $this->rest_app->post('index.php/rfx/reason/add_reason', $data, '');
		//var_dump($data['result_data']);
		echo json_encode($data['result_data']);
		//echo $data['reason'];
	}

	public function save_reason(){
		$data['reason_id'] = $this->input->post('reason_id');
		$data['reason'] = $this->input->post('reason');

		$data['result_data'] = $this->rest_app->post('index.php/rfx/reason/save_reason', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_reason(){
		$data['reason_id'] = $this->input->post('reason_id');

		$data['result_data'] = $this->rest_app->post('index.php/rfx/reason/deactivate_reason', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
