<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class City extends CI_Controller {

	public function index()
	{		
		$this->load->view('maintenance/vfm/city');
	}

	public function add_city()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'CITY_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/city_form/create_city/', $data, '');	
		echo json_encode(	$result);
	}

	public function select_city()
	{
		$data = array(
			'CITY_NAME' => $this->input->post('data')
			);

		$result = $this->rest_app->get('index.php/city_form/select_city/', $data, '');
		echo json_encode(	$result);
	}

	public function edit_city()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'CITY_ID' => $n[0],
			'CITY_NAME' => $n[2],
			'DESCRIPTION' => $n[1],
			'STATUS' => $n[3],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/city_form/edit_city/', $data, '');	
		echo json_encode(	$result);
	}

	public function del_city()
	{
		$n = $this->input->post('data');
		$x = $this->session->userdata['user_id'];
		$data = array(
			'CITY_ID' =>$n,
			);

		$result = $this->rest_app->post('index.php/city_form/del_city/', $data, '');	
		echo json_encode(	$result);

		


	}



}