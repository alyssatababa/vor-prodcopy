<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Visit_invite_template extends REST_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('visit_invite_temp_model');
		}


			
	public function create_vit_post()
	{

		$data = array(
			'VST_INV_TITLE' => $this->post('VST_INV_TITLE'),
			'VST_INV_MSG' => $this->post('VST_INV_MSG'),
			'USER_ID' => $this->post('USER_ID')
			);

			$result = $this->visit_invite_temp_model->m_create_vist($data);


			$this->response([
			'data' => $result
			]);

	}

	public function get_all_get()
	{

		$result = $this->visit_invite_temp_model->m_get_all();
		$this->response([
			'data' => $result
			]);


	}

	

	public function edit_vit_post()
	{

		$data = array(
			'VST_INV_TITLE' => $this->post('VST_INV_TITLE'),
			'VST_INV_MSG' => $this->post('VST_INV_MSG'),
			);

		$data2 = array(
			'VST_ID' => $this->post('VST_ID'),
			);

			$result = $this->visit_invite_temp_model->m_edit_ven($data,$data2);


			$this->response([
			'data' => $result
			]);
	}

	
	public function del_vit_post()
	{

		$data2 = array(
			'VST_ID' => $this->post('VST_ID'),
			);
			$result = $this->visit_invite_temp_model->m_del_ven($data2);

			$this->response([
			'data' => $result
			]);

	}
		public function del_vit_mul_post()
	{

		$data2 = array(
			'VST_ID' => $this->post('VST_ID'),
			);
			$result = $this->visit_invite_temp_model->m_del_ven_mul($data2);

			$this->response([
			'data' => $result
			]);

	}

	


}