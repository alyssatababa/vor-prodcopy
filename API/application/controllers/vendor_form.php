<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Vendor_form extends REST_Controller {

	public function __construct() {
			parent::__construct();
			$this->load->model('vendor_form_model');
		}
//-------------------Brand
		public function create_brand_post()
		{

			$data = array(
				'BRAND_NAME' => $this->post('BRAND_NAME'),
				'DESCRIPTION' => $this->post('DESCRIPTION'),
				'CREATED_BY' => $this->post('USER_ID'),
				'DATE_UPLOADED' => date('Y-m-d H:i:s.u'),
				'STATUS' => '1'
				);
				
			$result = $this->vendor_form_model->create_new_brand($data);

			$this->response([
			'data' => $result
			]);
		}

		public function select_brand_get()
		{

			$data = array(
				"LOWER(BRAND_NAME)" => strtolower($this->get('BRAND_NAME'))
				);

		$result = $this->vendor_form_model->select_all_brand($data);

			$this->response([
			'data' => $result
			]);

		}

		public function edit_brand_post()
		{

			$data = array(
				'BRAND_NAME' => $this->post('BRAND_NAME'),
				'STATUS' => $this->post('STATUS'),
				'DESCRIPTION' => $this->post('DESCRIPTION')
				);

			$data2 = array(
				'BRAND_ID' => $this->post('BRAND_ID')
				);
				
			$result = $this->vendor_form_model->edit_brand($data,$data2);

			$this->response([
			'data' => $result
			]);
		}


		public function del_brand_post()
		{


			$data = array(
				'BRAND_ID' => $this->post('BRAND_ID')
				);
				
			$result = $this->vendor_form_model->del_brand($data);

			$this->response(
			 $result
			);
		}




//-------------------State
		public function create_state_post()
		{

			$data = array(
				'STATE_PROV_NAME' => $this->post('STATE_PROV_NAME'),
				'DESCRIPTION' => $this->post('DESCRIPTION'),
				'CREATED_BY' => $this->post('USER_ID')
				);
				
			$result = $this->vendor_form_model->create_new_state($data);

			$this->response([
			'data' => $result
			]);
		}

		public function select_state_get()
		{

			$data = array(
				'STATE_PROV_NAME' => $this->get('STATE_PROV_NAME')
				);

		$result = $this->vendor_form_model->select_all_state($data);

			$this->response([
			'data' => $result
			]);

		}

		public function edit_state_post()
		{

			$data = array(
				'STATE_PROV_NAME' => $this->post('STATE_PROV_NAME'),
				'STATUS' => $this->post('STATUS'),
				'DESCRIPTION' => $this->post('DESCRIPTION')
				);

			$data2 = array(
				'STATE_PROV_ID' => $this->post('STATE_PROV_ID')
				);
				
			$result = $this->vendor_form_model->edit_state($data,$data2);

			$this->response([
			'data' => $result
			]);
		}

//-------------------City

		public function create_city_post()
		{

			$data = array(
				'CITY_NAME' => $this->post('CITY_NAME'),
				'DESCRIPTION' => $this->post('DESCRIPTION'),
				'CREATED_BY' => $this->post('USER_ID')
				);
				
			$result = $this->vendor_form_model->create_new_city($data);

			$this->response([
			'data' => $result
			]);
		}

		public function select_city_get()
		{

			$data = array(
				'CITY_NAME' => $this->get('CITY_NAME')
				);

		$result = $this->vendor_form_model->select_all_city($data);

			$this->response([
			'data' => $result
			]);

		}

		public function edit_city_post()
		{

			$data = array(
				'CITY_NAME' => $this->post('CITY_NAME'),
				'STATUS' => $this->post('STATUS'),
				'DESCRIPTION' => $this->post('DESCRIPTION')
				);

			$data2 = array(
				'CITY_ID' => $this->post('CITY_ID')
				);
				
			$result = $this->vendor_form_model->edit_city($data,$data2);

			$this->response([
			'data' => $result
			]);
		}



}