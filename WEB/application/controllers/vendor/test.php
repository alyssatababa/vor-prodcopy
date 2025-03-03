<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test extends CI_Controller
{
	public function test_function(){
		$result = $this->rest_app->get('index.php/vendor/test/test_function', '', 'application/json');
		
		if(empty($result)){
			echo "No result";
		}else{
			echo "<pre>";
			print_r($result);
		}
	}
}
?>