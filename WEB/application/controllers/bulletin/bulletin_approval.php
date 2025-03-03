<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bulletin_approval extends CI_Controller {


	public function index()
	{
		
		$this->load->view('bulletin/bulletin_approval');
	}

	
}
