<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class State extends CI_Controller {

public function index()
	{		
		$this->load->view('maintenance/vfm/state');
	}

	public function add_state()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'STATE_PROV_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'USER_ID' => $x
			);
		$result = $this->rest_app->post('index.php/state_form/create_state/', $data, '');	
		echo json_encode(	$result);
	}



	public function select_state()
	{
		$data = array(
			'STATE_PROV_NAME' => $this->input->post('data')
			);

		$result = $this->rest_app->get('index.php/state_form/select_state/', $data, '');
		echo json_encode(	$result);
	}

	public function edit_state()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'STATE_PROV_ID' => $n[0],
			'STATE_PROV_NAME' => $n[2],
			'DESCRIPTION' => $n[1],
			'STATUS' => $n[3],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/state_form/edit_state/', $data, '');	
		echo json_encode(	$result);
	}

	public function del_state()
	{
		$data['STATE_PROV_ID'] =$this->input->post('data');
		$data['USER_ID'] = $this->session->userdata['user_id'];



		$result = $this->rest_app->post('index.php/state_form/del_state/', $data, '');	
		echo json_encode(	$result);
	}

}