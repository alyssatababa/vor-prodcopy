<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cron_vendors extends CI_Controller
{
	public function migrate_vendors(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/migration_orafin', '', 'application/json');
		
		if(empty($result)){
			echo "No result";
		}else{
			echo "<pre>";
			print_r($result);
		}
	}
	
	public function cron_expired_token(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/cron_expired_token', '', 'application/json');
		
		if(empty($result)){
			echo "No result";
		}else{
			echo "<pre>";
			print_r($result);
		}
	}
	
	//Expire all vendor invite, 
	public function expire_all_invites(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/expire_all_invites', '', 'application/json');
		
		echo "<pre>";
		print_r($result);
	}
	
	//Expire First vendor invite, 
	public function expire_first_invites_only(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/expire_first_invites_only', '', 'application/json');
		
		echo "<pre>";
		print_r($result);
	}
	
	//Expire First vendor invite, 
	public function expire_extend_invites_only(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/expire_extend_invites_only', '', 'application/json');
		
		echo "<pre>";
		print_r($result);
	}
	
	
	//Invite Expiration
	public function reset_live_invite_expiration_days_config(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/reset_live_invite_expiration_days_config', '', 'application/json');
		
		echo "<pre>";
		print_r($result);
	}
	
	public function reset_live_invite_extension_days_config(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/reset_live_invite_extension_days_config', '', 'application/json');
		
		echo "<pre>";
		print_r($result);
	}
	
	public function zero_invite_expiration_days_config(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/zero_invite_expiration_days_config', '', 'application/json');
		//$this->rest_app->debug();
		echo "<pre>";
		print_r($result);
	}
	
	public function zero_invite_extension_days_config(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/zero_invite_extension_days_config', '', 'application/json');
		//$this->rest_app->debug();
		echo "<pre>";
		print_r($result);
	}
	
	//ARD AND PRD
	//Set to zero
	public function zero_prd_ard_expiration_config(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/zero_prd_ard_expiration_config', '', 'application/json');
		//$this->rest_app->debug();
		echo "<pre>";
		print_r($result);
	}
	//reset
	public function reset_prd_ard_expiration_config(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/reset_prd_ard_expiration_config', '', 'application/json');
		//$this->rest_app->debug();
		echo "<pre>";
		print_r($result);
	}
	
	public function get_all_users(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/get_all_users', '', 'application/json');
		//$this->rest_app->debug();
		echo "<pre>";
		
		var_export($result);
	}
	
	public function update_prod_email(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/update_prod_email', '', 'application/json');
		//$this->rest_app->debug();
		echo "<pre>";
		
		var_export($result);
	}
	
	public function reset_prod_email(){
		$result = $this->rest_app->get('index.php/vendor/cron_vendors/reset_prod_email', '', 'application/json');
		//$this->rest_app->debug();
		echo "<pre>";
		
		var_dump($result);
	}
}
?>