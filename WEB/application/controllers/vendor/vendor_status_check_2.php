<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Vendor_status_check_2 extends CI_Controller
	{
		function check_primary()
		{

			$data =array();

			$rs = $this->rest_app->put('index.php/vendor/check_status_2/cron_check/', $data,'');
			$this->rest_app->debug();
			var_dump($rs);
		}	

	}
?>