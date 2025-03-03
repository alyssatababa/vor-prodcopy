<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Country_form extends REST_Controller {

	public function __construct() {
			parent::__construct();
			$this->load->model('vendor_form_model');
		}

				public function create_country_post()
		{

			$data = array(
				'COUNTRY_NAME' => $this->post('COUNTRY_NAME'),
				'DESCRIPTION' => $this->post('DESCRIPTION'),
				'CREATED_BY' => $this->post('USER_ID')
				);
				
			$result = $this->vendor_form_model->create_new_country($data);

			$this->response([
			'data' => $result
			]);
		}

		public function select_country_get()
		{

			$data = array(
				'LOWER(COUNTRY_NAME)' => strtolower($this->get('COUNTRY_NAME'))
				);

		$result = $this->vendor_form_model->select_all_country($data);

			$this->response([
			'data' => $result
			]);

		}

		public function edit_country_post()
		{

			$data = array(
				'COUNTRY_NAME' => $this->post('COUNTRY_NAME'),
				'STATUS' => $this->post('STATUS'),
				'DESCRIPTION' => $this->post('DESCRIPTION')
				);

			$data2 = array(
				'COUNTRY_ID' => $this->post('COUNTRY_ID')
				);
				
			$result = $this->vendor_form_model->edit_country($data,$data2);

			$this->response([
			'data' => $result
			]);
		}

		public function del_country_post()
		{

			$data = array(
				'COUNTRY_ID' => $this->post('COUNTRY_ID'),
	
				);

		
			$result = $this->vendor_form_model->del_country($data);

			$this->response([
			'data' => $result
			]);
		}

	
	public function c_selected_country()
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

	public function c_sel_country_put()
	{

		$data = array(
			'COUNTRY_ID' => $this->put('COUNTRY_ID')
		);
		$result = $this->vendor_form_model->m_sel_country($data);

			$this->response(
			 $result
			);
	}


}
	
