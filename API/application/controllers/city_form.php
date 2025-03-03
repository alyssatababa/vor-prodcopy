<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class City_form extends REST_Controller {

	public function __construct() {
			parent::__construct();
			$this->load->model('vendor_form_model');
		}

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
				'LOWER(CITY_NAME)' => strtolower($this->get('CITY_NAME'))
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


		public function del_city_post()
		{
				$data = array(
						'CITY_ID' => $this->post('CITY_ID')
					);

				$result = $this->vendor_form_model->del_city($data);			
				$this->response($result);


		}



	}
