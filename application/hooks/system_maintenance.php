<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class System_maintenance {
	var $CI;
	public function maintenance() {
		$this->CI = & get_instance();
		$this->CI->config->load('maintenance'); // Load custom config file
		if ($this->CI->config->item("system_maintenance")) {

		$data['HTTPUSERAGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown User Agent';
		
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		
		$data['REMOTEADDR'] = $ipaddress;
			
		if($data['REMOTEADDR'] != '122.54.211.70'){
			include(APPPATH . '/views/common/maintenance_view.php');
			die();
		}
			
		
		}
	}
}
?>