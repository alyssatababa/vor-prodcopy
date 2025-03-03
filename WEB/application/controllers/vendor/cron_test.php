<?php
Class Cron_test extends CI_Controller{
	//public function test($endofmonth){
	//	$data =array();
	//	//var_dump($endofmonth);
	//	$data['endofmonth'] = $endofmonth;
	//	$rs = $this->rest_app->put('index.php/vendor/cron_email_reports_test/send_report/', $data,'');
	//	//var_dump($rs);
	//	echo "1";
	//}
	//public function test_expiry(){
	//	$data =array();
    //
	//	$rs = $this->rest_app->put('index.php/vendor/cron_vendors/cron_expired_token/', $data,'');
	//	var_dump($rs);
	//}
	
	public function get_password(){
		$rs = $this->rest_app->get('index.php/vendor/test/test_get_pass', '','');
		print_r($rs);
	}
	
	public function set_password(){
		$rs = $this->rest_app->get('index.php/vendor/test/set_pass/', '','');
		print_r($rs);
	}
	
	public function set_password_two(){
		$rs = $this->rest_app->get('index.php/vendor/test/set_pass_two/', '','');
		print_r($rs);
	}
	
	public function set_password_three(){
		$rs = $this->rest_app->get('index.php/vendor/test/set_pass_three/', '','');
		print_r($rs);
	}
}
?>