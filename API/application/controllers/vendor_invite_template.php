<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Vendor_invite_template extends REST_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('vendor_invite_temp_model');
		}

	public function create_vit_post()
	{

		$data = array(
			'VEN_INV_TITLE' => $this->post('VEN_INV_TITLE'),
			'VEN_INV_MESSAGE' => $this->post('VEN_INV_MESSAGE'),
			'USER_ID' => $this->post('USER_ID')
			);


			$result = $this->vendor_invite_temp_model->m_create_ven($data);


			$this->response([
			'data' => $result
			]);

	}

	public function get_all_get()
	{

		$n = $this->get('USER_ID');
		$data = array(
			'V.USER_ID' => $this->get('USER_ID')
			);
		$result = $this->vendor_invite_temp_model->m_get_all($data);

		$this->response([
			'data' => $result
			]);


	}

	public function edit_vit_post()
	{
		$user_id = $this->post('USER_ID');
		$data = array(
			'VEN_INV_TITLE' => $this->post('VEN_INV_TITLE'),
		'VEN_INV_MESSAGE' => $this->post('VEN_INV_MESSAGE'),
			);

		$data2 = array(
			'VEN_INV_ID' => $this->post('VEN_INV_ID'),
		);

		$result = $this->vendor_invite_temp_model->m_edit_ven($data,$data2, $user_id);


		$this->response([
			'data' => $result
		]);

	}
	public function del_vit_post()
	{

		$data2 = array(
			'VEN_INV_ID' => $this->post('VEN_INV_ID'),
			);
			$result = $this->vendor_invite_temp_model->m_del_ven($data2);

			$this->response([
			'data' => $result
			]);

	}

		public function del_vit_mul_post()
	{

			$data['ids'] = json_decode($this->post('id'));

			$result = $this->vendor_invite_temp_model->m_del_ven_mul($data);

			$this->response(
			 $result
			);

	}



}