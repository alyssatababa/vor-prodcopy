<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Vendor_status_additional extends CI_Controller
	{
		function test()
		{

			$data =array();

			$rs = $this->rest_app->put('index.php/vendor/check_additional/cron_additional/', $data,'');
			$this->rest_app->debug();
			var_dump($rs);
		}	

	}
?>