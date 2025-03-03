<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_management extends CI_Controller {

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
		
		$this->load->view('maintenance/role_management');
	}

	public function get_position(){
		$data['result_data'] = $this->rest_app->post('index.php/maintenance/role_management/view_position', '', '');
		echo json_encode($data['result_data']);
	}
	
	public function get_vendor_type(){
		$data['result_data'] = $this->rest_app->post('index.php/maintenance/role_management/view_vendor_type', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_all_screens(){
		$data['result_data'] = $this->rest_app->post('index.php/maintenance/role_management/view_all_screens', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_screens(){
		$data['input_position_id'] = $this->input->post('position_id');
		$data['vendor_type_id'] = $this->input->post('vendor_type_id');
		// $data['result_data'] = $this->rest_app->post('index.php/vendorparam/required_documents/view_reqdocs', $data, '');
		$data['result_data'] = $this->rest_app->post('index.php/maintenance/role_management/view_screens', $data, '');
		echo json_encode($data['result_data']);
	}

	public function get_screens_not_in(){
		$data['input_position_id'] = $this->input->post('position_id');
		$data['vendor_type_id'] = $this->input->post('vendor_type_id');
		// $data['result_data'] = $this->rest_app->post('index.php/vendorparam/required_documents/view_reqdocs', $data, '');
		$data['result_data'] = $this->rest_app->post('index.php/maintenance/role_management/view_screens_not_in', $data, '');
		echo json_encode($data['result_data']);
	}

	public function save_screens(){
		$data['screens_data'] = $this->input->post('screens');
		$data['position_id'] = $this->input->post('position_id');
		$data['vendor_type_id'] = $this->input->post('vendor_type_id');
		$data['result_data'] = $this->rest_app->post('index.php/maintenance/role_management/save_screens', $data, '');
		echo json_encode($data['result_data']);		
		// echo "1";
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */