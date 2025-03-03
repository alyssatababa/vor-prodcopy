<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visitinvite extends CI_Controller {

	public function index()
	{
		$this->load->view('maintenance/visitinvite');
	}



	public function save_visit_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'VST_INV_TITLE' => $n[1],
			'VST_INV_MSG' => $n[0],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/visit_invite_template/create_vit/', $data, '');
		echo json_encode($result);
	}

	public function get_all()
	{

	$result = $this->rest_app->get('index.php/visit_invite_template/get_all/', '', '');
	echo json_encode($result);

	}



	public function edit_visit_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'VST_ID' => $n[0],
			'VST_INV_TITLE' => $n[2],
			'VST_INV_MSG' => $n[1],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/visit_invite_template/edit_vit/', $data, '');
		echo json_encode($result);
	}

	public function del_visit_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'VST_ID' => $n,
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/visit_invite_template/del_vit/', $data, '');
		echo json_encode($result);
	}

	public function del_visit_template_mul()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'VST_ID' => $n,
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/visit_invite_template/del_vit_mul/', $data, '');
		echo json_encode($result);
	}

}
