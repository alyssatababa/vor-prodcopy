<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brand extends CI_Controller {

	public function index()
	{		
		$this->load->view('maintenance/vfm/vendorform');
	}
	public function add_brand()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'BRAND_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/vendor_form/create_brand/', $data, '');	
		echo json_encode(	$result);
	}

	public function select_brand()
	{
		$data = array(
			'BRAND_NAME' => $this->input->post('data')
			);

		$result = $this->rest_app->get('index.php/vendor_form/select_brand/', $data, '');
		echo json_encode(	$result);
	}

	public function edit_brand()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'BRAND_ID' => $n[0],
			'BRAND_NAME' => $n[2],
			'DESCRIPTION' => $n[1],
			'STATUS' => $n[3],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/vendor_form/edit_brand/', $data, '');	
		echo json_encode(	$result);
	}

	public function del_brand()
	{

		$data['BRAND_ID'] = $this->input->post('data');
		$data['USER_ID'] = $this->session->userdata['user_id'];


		$result = $this->rest_app->post('index.php/vendor_form/del_brand/', $data, '');


		echo json_encode($result);
	}


}
?>