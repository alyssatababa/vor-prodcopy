<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Country extends CI_Controller {

	public function index()
	{		
		$this->load->view('maintenance/vfm/country');
	}

	public function add_country()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'COUNTRY_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/country_form/create_country/', $data, '');	
		echo json_encode(	$result);
	}

	public function select_country()
	{
		$data = array(
			'COUNTRY_NAME' => $this->input->post('data')
			);

		$result = $this->rest_app->get('index.php/country_form/select_country/', $data, '');
		echo json_encode(	$result);
	}

	public function edit_country()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'COUNTRY_ID' => $n[0],
			'COUNTRY_NAME' => $n[2],
			'DESCRIPTION' => $n[1],
			'STATUS' => $n[3],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/country_form/edit_country/', $data, '');	
		echo json_encode(	$result);
	}

	public function del_country()
	{
		$n = $this->input->post('data');
		$x = $this->session->userdata['user_id'];
		$data = array(
			'COUNTRY_ID' => $n
			);

		$result = $this->rest_app->post('index.php/country_form/del_country/', $data, '');	
		echo json_encode(	$result);
	}


	public function c_sel_country()
	{

		$n = $this->input->post('data');
		$x = $this->session->userdata['user_id'];

		$data = array(
			'COUNTRY_ID' => $n,
			'USER_ID' => $x
			);

		$result = $this->rest_app->put('index.php/country_form/c_sel_country/', $data, '');	
		echo json_encode($result);
	}
	


}