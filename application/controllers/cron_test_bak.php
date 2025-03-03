<?php
Class Cron_test extends CI_Controller{
	public function test(){
		$data =array();

		$rs = $this->rest_app->put('index.php/vendor/check_additional/cron_additional/', $data,'');
		var_dump($rs);
	}
}
?>