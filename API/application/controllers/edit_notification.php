<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Edit_notification extends REST_Controller {
	
			public function __construct() {
			parent::__construct();
			$this->load->model('edit_email_model');
		}

		public function get_notification_get()
		{
			$res = $this->edit_email_model->get_all_notification();

			$this->response($res);
		}

		public function save_edit_post()
		{
			$where = array('MESSAGE_DEFAULT_ID' => $this->post('MESSAGE_DEFAULT_ID'));
			$table = 'SMNTP_MESSAGE_DEFAULT';
			$updata = array(
				'TOPIC' => $this->post('TOPIC'),
				'MESSAGE' => $this->post('MESSAGE'),
				'DESCRIPTION' => $this->post('DESCRIPTION')
				);

			$res = $this->edit_email_model->update_query($where,$updata,$table);
			$this->response($res);


		}

		public function save_new_put()
		{
			$table = 'SMNTP_MESSAGE_DEFAULT';
			$updata = array(
				'TOPIC' => $this->put('TOPIC'),
				'MESSAGE' => $this->put('MESSAGE')
				);

			$res = $this->edit_email_model->insert_query($updata,$table);
			$this->response($res);


		}

		public function deact_temp_post()
		{
			$where = array('MESSAGE_DEFAULT_ID' => $this->post('MESSAGE_DEFAULT_ID'));
			$table = 'SMNTP_MESSAGE_DEFAULT';
			$updata = array('ACTIVE' => 0);

			$res = $this->edit_email_model->update_query($where,$updata,$table);
			$this->response($res);


		}

	
}