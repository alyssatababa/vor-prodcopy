<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Subdoc_template extends REST_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('subdoc_temp_model');
		}


			
	public function create_vit_post()
	{

		$data = array(
			'SDT_TITLE' => $this->post('SDT_TITLE'),
			'SDT_MSG' => $this->post('SDT_MSG'),
			'USER_ID' => $this->post('USER_ID')
			);

			$result = $this->subdoc_temp_model->m_create_vist($data);


			$this->response([
			'data' => $result
			]);

	}

	public function get_all_get()
	{

		$result = $this->subdoc_temp_model->m_get_all();

		$this->response([
			'data' => $result
			]);
	}
	public function edit_vit_post()
	{

		$data = array(
			'SDT_TITLE' => $this->post('SDT_TITLE'),
			'SDT_MSG' => $this->post('SDT_MSG'),
			);

		$data2 = array(
			'SDT_ID' => $this->post('SDT_ID'),
			);

			$result = $this->subdoc_temp_model->m_edit_ven($data,$data2);


			$this->response([
			'data' => $result
			]);
	}

	
	public function del_vit_post()
	{

		$data2 = array(
			'SDT_ID' => $this->post('SDT_ID'),
			);
			$result = $this->subdoc_temp_model->m_del_ven($data2);

			$this->response([
			'data' => $result
			]);
	}

	public function c_seltmplt_put()
	{

		$data = array(
			'SDT_ID' => $this->put('SDT_ID')
		);
			$result = $this->subdoc_temp_model->m_sel_sel($data);

			$this->response(
			 $result
			);
	}

	


}