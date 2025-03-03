<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purpose extends CI_Controller {

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
		$this->load->view('rfx/purpose');
	}

	public function get_all_purpose(){
		$data['result_data'] = $this->rest_app->post('index.php/rfx/purpose/view_all_purpose', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/purpose' , $data);
	}

	public function get_purpose(){
		$data['input_purpose'] = $this->input->post('purpose');
		$data['result_data'] = $this->rest_app->post('index.php/rfx/purpose/view_purpose', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('rfx/currency' , $data);
	}

	public function add_purpose(){
		$data['purpose'] = $this->input->post('purpose');

		$data['result_data'] = $this->rest_app->post('index.php/rfx/purpose/add_purpose', $data, '');
		//var_dump($data['result_data']);
		echo json_encode($data['result_data']);
		//echo $data['purpose'];
	}

	public function save_purpose(){
		$data['purpose_id'] = $this->input->post('purpose_id');
		$data['purpose'] = $this->input->post('purpose');

		$data['result_data'] = $this->rest_app->post('index.php/rfx/purpose/save_purpose', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_purpose(){
		$data['purpose_id'] = $this->input->post('purpose_id');

		$data['result_data'] = $this->rest_app->post('index.php/rfx/purpose/deactivate_purpose', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
