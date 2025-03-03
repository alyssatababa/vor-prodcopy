<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulletin_creation extends CI_Controller {


	public function index()
	{
		
		$this->load->view('bulletin/bulletin_creation');
	}

	public function reports()
	{
		
		$this->load->view('bulletin/bulletin_reports');
	}
	
}
