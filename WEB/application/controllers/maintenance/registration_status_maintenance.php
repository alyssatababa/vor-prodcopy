<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class registration_status_maintenance extends CI_Controller {

	public function index()
	{
		
		$this->load->view('maintenance/registration_status_maintenance');
	}
	}