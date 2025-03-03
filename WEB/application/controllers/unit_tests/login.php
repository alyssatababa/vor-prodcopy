<?php
Class Login extends CI_Controller{

	public function __construct() {
		parent::__construct();
		$this->load->library('unit_test');
	}

	function test_login_apis()
	{
		$data['username'] = 'admin';
		$data['password'] = 'admin123';
		$data['remember_me'] = false;
		
		$result_data = $this->rest_app->get('index.php/users/user/', $data, 'application/json');
		
		$this->unit->run(isset($result_data->users) && count($result_data->users) > 0, true, 'testing if login api returns a valid user',count($result_data), 'test notes');
		
		$this->unit->run(1+1, 2, 'testing if 1+ 1 = 2', 'test notes');
		
		$this->unit->run($data['username'], 'ADMIN', 'testing if username is case sensitive', 'test notes');
		
		$test_data['result'] = $this->unit->result();
		$this->load->view('unit_tests/result_view', $test_data);
	}

}
?>