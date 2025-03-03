<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';
class Common extends REST_Controller {

	/*
    This class will contain a set of tests to be run for testing new updates/ deployment
    things to test:
    app + etl + web servers connectivity.
    database connectivity.
    config mismatch for app etl and web modules.

	*/

	public function __construct() {
		parent::__construct();
		$this->load->model('unit_tests/unit_test_model');
	}

  public function connect_get() {
		// default return true, to test if the web server can access this method.
    $this->response(array(
      'status' => TRUE,
      'message' => 'Connected.',
      'server' => base_url(),
			'server_ip' => $_SERVER['SERVER_ADDR'],
			'server_port' => $_SERVER['SERVER_PORT'],
			'server_name' => $_SERVER['SERVER_NAME'],
			'server_time' => date('m/d/Y h:i:s a', time()),
			'db_hostname'=> $this->db->hostname,
			'db_username'=> $this->db->username,
			'db_database'=> $this->db->database
    ));
	}

	public function table_info_get(){
		$table_name = $this->get('table_name');
		$res = $this->unit_test_model->get_table_info($table_name);
		// $this->db->last_query();
		$this->response(array(
      'status' => TRUE,
      'result' => $res
    ));
	}

	public function table_content_get(){
		$table_name = $this->get('table_name');
		$where = $this->get('where');
		$select = $this->get('select');
		$res = $this->unit_test_model->get_table_content($table_name, $select, $where);
		// $this->db->last_query();
		$this->response(array(
			'status' => TRUE,
			'result' => $res
		));
	}

	public function send_mail_post(){

		$email_data['to'] 		= 'jobert.beltran@sandmansystems.com';
		$email_data['cc'] 		= 'joebrt.beltran@gmail.com,jbrtbeltran1@yahoo.com,jbrtbeltran1@hotmail.ph,sssi.smntp@gmail.com';
		// $email_data['subject'] 	= 'TEST MAIL SUBJECT';
		$email_data['subject'] 	= 'Vendor Registration Invite Approved- TEST FOR LONG VENDOR NAME EMAIL ERROR';

		$message = $this->post('mail_body');

		$token 	= '<a href="https://smvendorportal.com/index.php/resetpassword/index/abcdefghi" title="">CLICK HERE TO SET PASSWORD</a>';

		$message = str_replace('[vendor_name]', 'Jane Florence B. de Leon', $message);
		$message = str_replace('[vendorname]', 'Jane Florence B. de Leon', $message);
		$message = str_replace('[username]', 'TEST-AAA001', $message);
		$message = str_replace('[expiryday]', '0 days', $message);
		$message = str_replace('[expirydate]', date('F d, Y'), $message);
		$message = str_replace('[token]', $token, $message);
		$message = str_replace('[base_url]', 'https://smvendorportal.com/', $message);
		$message .="
		
		THIS IS A TEST MAIL.";
		$email_data['content'] 	= nl2br($message);

		$res['email_content'] = $email_data['content'];
		$res['email_debug'] = $this->unit_test_model->send_email_notification($email_data);

		$this->response(array(
			'status' => TRUE,
			'result' => $res
		));
	}
}