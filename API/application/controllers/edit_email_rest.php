<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Edit_email_rest extends REST_Controller {
	
			public function __construct() {
			parent::__construct();
			$this->load->model('edit_email_model');
		}

		public function get_email_get()
		{
			$res = $this->edit_email_model->get_all_email();

			$this->response($res);
		}

		public function save_edit_post()
		{
			$where = array('TEMPLATE_ID' => $this->post('TEMPLATE_ID'));
			$table = 'SMNTP_EMAIL_DEFAULT_TEMPLATE';
			$updata = array(
				'DESCRIPTION' => $this->post('DESCRIPTION'),
				'CONTENT' => $this->post('CONTENT'),
				'TEMPLATE_HEADER' => $this->post('TEMPLATE_HEADER')
			);

			$res = $this->edit_email_model->update_query($where,$updata,$table);
			$this->response($res);


		}

		public function save_new_put()
		{
			$table = 'SMNTP_EMAIL_DEFAULT_TEMPLATE';
			$updata = array(
				'DESCRIPTION' => $this->put('DESCRIPTION'),
				'CONTENT' => $this->put('CONTENT'),
				'TEMPLATE_HEADER' => $this->put('TEMPLATE_HEADER'),
				'ACTIVE' => 1
			);

			$res = $this->edit_email_model->insert_query($updata,$table);
			$this->response($res);


		}

		public function deact_temp_post()
		{
			$where = array('TEMPLATE_ID' => $this->post('TEMPLATE_ID'));
			$table = 'SMNTP_EMAIL_DEFAULT_TEMPLATE';
			$updata = array('ACTIVE' => 0);

			$res = $this->edit_email_model->update_query($where,$updata,$table);
			$this->response($res);


		}

	
}