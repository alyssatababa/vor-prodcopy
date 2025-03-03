<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
class Registrationinvite extends CI_Controller
{
	
	function index()
	{
		$this->load->view('vendor/registration_invite');
	}

}
?>