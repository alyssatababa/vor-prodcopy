<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class State_form extends REST_Controller {

	public function __construct() {
			parent::__construct();
			$this->load->model('vendor_form_model');
		}

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
				'LOWER(STATE_PROV_NAME)' => strtolower($this->get('STATE_PROV_NAME'))
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
			public function del_state_post()
		{



			$data = array(
				'STATE_PROV_ID' => $this->post('STATE_PROV_ID')
				);
				
			$result = $this->vendor_form_model->del_state($data);

			$this->response($result);
		}

}