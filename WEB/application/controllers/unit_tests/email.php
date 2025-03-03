<?php
Class Email extends CI_Controller{

	public function __construct() {
		parent::__construct();
		$this->load->library('unit_test');
	}

	function index()
	{
		echo CI_VERSION;
		$data['mail_body'] = $this->rest_app->get('index.php/vendor/invitecreation_api/email_template', null, 'application/json');
		
		// echo "<pre>".$data['mail_body'] ."</pre><br> <br>";
		echo "<pre>".json_encode($this->rest_app->post('index.php/unit_tests/common/send_mail', $data, ''),JSON_PRETTY_PRINT)."</pre><br> <br>";
		$this->rest_app->debug();
	}

}
?>