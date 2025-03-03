<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submitteddoc extends CI_Controller {

	public function index()
	{
		$this->load->view('maintenance/submitteddoc');
	}



	public function save_visit_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'SDT_TITLE' => $n[1],
			'SDT_MSG' => $n[0],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/subdoc_template/create_vit/', $data, '');
		echo json_encode($result);
	}

	public function get_all()
	{

	$result = $this->rest_app->get('index.php/subdoc_template/get_all/', '', '');
	echo json_encode($result);

	}



	public function edit_visit_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'SDT_ID' => $n[0],
			'SDT_TITLE' => $n[2],
			'SDT_MSG' => $n[1],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/subdoc_template/edit_vit/', $data, '');
		echo json_encode($result);
	}

	public function del_visit_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'SDT_ID' => $n,
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/subdoc_template/del_vit/', $data, '');
		echo json_encode($result);
	}

	public function c_selected_tmplt()
	{

		$n = $this->input->post('data');
		$x = $this->session->userdata['user_id'];

		$data = array(
			'SDT_ID' => $n,
			'USER_ID' => $x
			);

		$result = $this->rest_app->put('index.php/subdoc_template/c_seltmplt/', $data, '');
		echo json_encode($result);
	}




}
