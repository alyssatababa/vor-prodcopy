<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendorinvite extends CI_Controller {

	public function index()
	{		
		$this->load->view('maintenance/vendorinvite');
	}

	public function save_vendor_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'VEN_INV_TITLE' => $n[1],
			'VEN_INV_MESSAGE' => $n[0],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/vendor_invite_template/create_vit/', $data, '');	
		echo json_encode($result);
	}

	public function get_all()
	{
	$data = array(
		'USER_ID' => $this->session->userdata['user_id']
		);
	$result = $this->rest_app->get('index.php/vendor_invite_template/get_all/', $data, '');	
	echo json_encode($result);

	}

	public function edit_vendor_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'VEN_INV_ID' => $n[0],
			'VEN_INV_TITLE' => $n[2],
			'VEN_INV_MESSAGE' => $n[1],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/vendor_invite_template/edit_vit/', $data, '');	
		echo json_encode($result);
	}

	public function del_vendor_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'VEN_INV_ID' => $n,
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/vendor_invite_template/del_vit/', $data, '');	
		echo json_encode($result);
	}

		public function del_vendor_template_multi()
	{

		$data['id'] = $this->input->post('data');

		$result = $this->rest_app->post('index.php/vendor_invite_template/del_vit_mul/', $data, '');	
		echo json_encode($result);
	}

}
