<?php
Class Cron_test extends CI_Controller{
	public function test($endofmonth){
		$data =array();
		//var_dump($endofmonth);
		$data['endofmonth'] = $endofmonth;
		$rs = $this->rest_app->put('index.php/vendor/cron_email_reports_test/send_report/', $data,'');
		//var_dump($rs);
		echo "1";
	}
	public function test_expiry(){
		$data =array();

		$rs = $this->rest_app->put('index.php/vendor/cron_vendors/cron_expired_token/', $data,'');
		var_dump($rs);
	}
}
?>