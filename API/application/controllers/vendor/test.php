<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class Test extends REST_Controller
{
	public $debug;
	// Load model in constructor
	public function __construct() {
		parent::__construct();		
		$this->debug = FALSE;
		$this->load->model('common_model');
		$this->load->model('mail_model');
	}
	
	function get_password_get(){
		$this->load->library('CryptManager');
		$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', '25-JUN-19 03.06.38.786630 PM' . '4598645')),0,16);
		$enc_password = $this->cryptmanager->encrypt('uat2019', FALSE, $file_crypt_key);
		$dec_password = $this->cryptmanager->decrypt('d8f31a7e0feb985025b8b9c8c199450f088f7a2bc5cc3e2832464f9b3a0b1798', FALSE, $file_crypt_key);
		
		
		//$this->response(base64_decode($dec_password));
		//$this->response($enc_password);
		//$this->response(base64_decode($dec_password). " - " . $enc_password);
	}
	
	function get_password_put(){
		$this->load->library('CryptManager');
		$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', '09-AUG-21 10.25.44.412427 PM' . '4600042')),0,16);
		$enc_password = $this->cryptmanager->encrypt('uat2019', FALSE, $file_crypt_key);
		$dec_password = $this->cryptmanager->decrypt('fd699c8f4fd1b393332a7c816ae4f934', FALSE, $file_crypt_key);
		
		
		//$this->response(base64_decode($dec_password));
		//$this->response($enc_password);
	}
	
	//function reset_password_get(){
	//	$this->load->library('CryptManager');
	//	
	//	$data = $this->common_model->get_all_users();
	//	
	//	foreach($data as $list){
	//		$user_id = $list['USER_ID'];
	//		$user_name = $list['USERNAME'];
	//		$password = $list['PASSWORD'];
	//		$time_stamp = $list['TIME_STAMP'];
	//		//$db_timestamp = strtotime($list['TIME_STAMP']);
	//		//$time_stamp = date('d-M-y h.i.s.u A', $db_timestamp);
	//		
	//		$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', strtoupper($time_stamp) . $user_id)),0,16);
	//		$enc_password = $this->cryptmanager->encrypt('uat2019', FALSE, $file_crypt_key);
	//		$dec_password = $this->cryptmanager->decrypt($password, FALSE, $file_crypt_key);
	//		
	//		echo $user_id . ' - ' . $user_name . ' - ' . strtoupper($time_stamp) . ' - ' . $enc_password . ' - '. base64_decode($dec_password) . '<br/>';
	//		
	//		$record = array(
	//				'PASSWORD' 	=> $enc_password
	//			);
	//		$where = array(
	//				'USER_ID' => $user_id
	//			);
	//		
	//		$rs = $this->common_model->update_table('SMNTP_CREDENTIALS', $record, $where);
	//		
	//		$record = array(
	//				'EMAIL' 	=> 'markjoseph.s.francisco@smretail.com'
	//			);
	//		$rs = $this->common_model->update_table('SMNTP_VENDOR_INVITE', $record, 'VENDOR_NAME != 31313131');
	//		
	//		$record = array(
	//				'USER_EMAIL' 	=> 'markjoseph.s.francisco@smretail.com'
	//			);
	//		$rs = $this->common_model->update_table('SMNTP_USERS', $record, 'USER_FIRST_NAME != 31313131');
	//	}
	//	
	//}
	
	function set_pass_get(){
		
		$sel = $this->db->query("SELECT USER_ID, USERNAME, `PASSWORD`, CONCAT(DATE_FORMAT(TIME_STAMP,'%d-'),UCASE(DATE_FORMAT(TIME_STAMP,'%b')),DATE_FORMAT(TIME_STAMP,'-%y %h.%i.%s.%f %p')) AS TIME_STAMP FROM SMNTP_CREDENTIALS WHERE USER_ID <= 4609250 ")->result();
		$counter = count($sel);
		
		for($a=0;$a<$counter;$a++){
			if($sel[$a]->PASSWORD != '' || $sel[$a]->PASSWORD != null){
				$this->load->library('CryptManager');
				$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $sel[$a]->TIME_STAMP . $sel[$a]->USER_ID)),0,16);
				$dec_password = $this->cryptmanager->encrypt('uat2019', FALSE, $file_crypt_key);
				$password = $dec_password;
				
				$where = array(
					'USER_ID' => $sel[$a]->USER_ID
				);
				$record = array(
					'PASSWORD' 	=> $password
				);
				
				if($this->common_model->update_table('SMNTP_CREDENTIALS', $record, $where)){
					echo($sel[$a]->USER_ID . " - " . $sel[$a]->USERNAME . " - " . $password." - UPDATED! </br>");
				}else{
					echo "not updated";
				}
				
				//$dec_password = $this->cryptmanager->decrypt($sel[$a]->PASSWORD, FALSE, $file_crypt_key);
				//$password = base64_decode($dec_password);
				//echo($sel[$a]->USERNAME . " - " . $password."</br>");	
				
			}
		}
	}
	
	function set_pass_three_get(){
		
		$sel = $this->db->query("SELECT USER_ID, USERNAME, `PASSWORD`, CONCAT(DATE_FORMAT(TIME_STAMP,'%d-'),UCASE(DATE_FORMAT(TIME_STAMP,'%b')),DATE_FORMAT(TIME_STAMP,'-%y %h.%i.%s.%f %p')) AS TIME_STAMP FROM SMNTP_CREDENTIALS WHERE USER_ID >= 4609250 and USER_ID <= 4609860 ")->result();
		$counter = count($sel);
		
		for($a=0;$a<$counter;$a++){
			if($sel[$a]->PASSWORD != '' || $sel[$a]->PASSWORD != null){
				$this->load->library('CryptManager');
				$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $sel[$a]->TIME_STAMP . $sel[$a]->USER_ID)),0,16);
				$dec_password = $this->cryptmanager->encrypt('uat2019', FALSE, $file_crypt_key);
				$password = $dec_password;
				
				$where = array(
					'USER_ID' => $sel[$a]->USER_ID
				);
				$record = array(
					'PASSWORD' 	=> $password
				);
				
				if($this->common_model->update_table('SMNTP_CREDENTIALS', $record, $where)){
					echo($sel[$a]->USER_ID . " - " . $sel[$a]->USERNAME . " - " . $password." - UPDATED! </br>");
				}else{
					echo "not updated";
				}
				
				//$dec_password = $this->cryptmanager->decrypt($sel[$a]->PASSWORD, FALSE, $file_crypt_key);
				//$password = base64_decode($dec_password);
				//echo($sel[$a]->USERNAME . " - " . $password."</br>");	
				
			}
		}
	}
	
	function set_pass_two_get(){
		
		$sel = $this->db->query("SELECT USER_ID, USERNAME, `PASSWORD`, CONCAT(DATE_FORMAT(TIME_STAMP,'%d-'),UCASE(DATE_FORMAT(TIME_STAMP,'%b')),DATE_FORMAT(TIME_STAMP,'-%y %h.%i.%s.%f %p')) AS TIME_STAMP FROM SMNTP_CREDENTIALS WHERE USER_ID >= 4609860 ")->result();
		$counter = count($sel);
		
		for($a=0;$a<$counter;$a++){
			if($sel[$a]->PASSWORD != '' || $sel[$a]->PASSWORD != null){
				$this->load->library('CryptManager');
				$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $sel[$a]->TIME_STAMP . $sel[$a]->USER_ID)),0,16);
				$dec_password = $this->cryptmanager->encrypt('uat2019', FALSE, $file_crypt_key);
				$password = $dec_password;
				
				$where = array(
					'USER_ID' => $sel[$a]->USER_ID
				);
				$record = array(
					'PASSWORD' 	=> $password
				);
				
				if($this->common_model->update_table('SMNTP_CREDENTIALS', $record, $where)){
					echo($sel[$a]->USER_ID . " - " . $sel[$a]->USERNAME . " - " . $password." - UPDATED! </br>");
				}else{
					echo "not updated";
				}
				
				//$dec_password = $this->cryptmanager->decrypt($sel[$a]->PASSWORD, FALSE, $file_crypt_key);
				//$password = base64_decode($dec_password);
				//echo($sel[$a]->USERNAME . " - " . $password."</br>");	
				
			}
		}
	}
	
	function test_get_pass_get(){
		
		//$sel = $this->common_model->select_query_no_where('SMNTP_CREDENTIALS');
		$sel = $this->db->query("SELECT USER_ID, USERNAME, `PASSWORD`, CONCAT(DATE_FORMAT(TIME_STAMP,'%d-'),UCASE(DATE_FORMAT(TIME_STAMP,'%b')),DATE_FORMAT(TIME_STAMP,'-%y %h.%i.%s.%f %p')) AS TIME_STAMP FROM SMNTP_CREDENTIALS")->result();
		
		//foreach($sel['query'] as $data){
		//	$this->load->library('CryptManager');
		//	$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $data['TIME_STAMP'] . $data['USER_ID'])),0,16);
		//	//$dec_password = $this->cryptmanager->decrypt($data['PASSWORD'], FALSE, $file_crypt_key);
		//	$dec_password = $this->cryptmanager->encrypt('uat2019', FALSE, $file_crypt_key);
		//	//$password = base64_decode($dec_password);
		//	$password = $dec_password;
		//	echo($data['USERNAME'] . " - " . $password."</br>");
		//}
		
		$counter = count($sel);
		
		for($a=0;$a<$counter;$a++){
			if($sel[$a]->PASSWORD != '' || $sel[$a]->PASSWORD != null){
				$this->load->library('CryptManager');
				$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $sel[$a]->TIME_STAMP . $sel[$a]->USER_ID)),0,16);
				//$dec_password = $this->cryptmanager->encrypt('uat2019', FALSE, $file_crypt_key);
				//$password = $dec_password;
				//echo($sel[$a]->USERNAME . " - " . $password."</br>");
				
				$dec_password = $this->cryptmanager->decrypt($sel[$a]->PASSWORD, FALSE, $file_crypt_key);
				$password = base64_decode($dec_password);
				echo($sel[$a]->USER_ID . " - " . $sel[$a]->USERNAME . " - " . $password."</br>");	
			}
		}
		
			
			//for($a=0; $a<count($sel_data); $a++){
			//	echo $a. "-----";
			//	//echo $sel_data[$a]['PASSWORD']."<br/>";
			//	//echo $sel_data[$a]['TIME_STAMP']."<br/>";
			//	//$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $sel_data[$a]['TIME_STAMP'] . $sel_data[$a]['USER_ID'])),0,16);
			//	//$dec_password = $this->cryptmanager->decrypt($sel_data[$a]['PASSWORD'], FALSE, $file_crypt_key);
			//	//$password = $this->response(base64_decode($dec_password));
			//	//echo $sel_data[$a]['USERNAME'] . " - " . $password."</br>";
			//}
			//echo $sel_data['PASSWORD'];
			//exit();
			//exit();
		
	}
	
	function test_expiration_get(){

		$res = $this->check_status_model->select_inprogress_vendor();
		$days = $this->check_status_model->select_number_of_days();
		$content = $this->check_status_model->select_query_or('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => '17'),array('TEMPLATE_TYPE' => '18'),'TEMPLATE_TYPE,CONTENT');

		$date_timestamp = date('m/d/Y h:i:s A');
		$date_timestamp = DateTime::createFromFormat('m/d/Y h:i:s A', $date_timestamp);
		$date_timestamp = $date_timestamp->format("d-M-y h.i.s.u A");

		$date_only = date('m/d/Y h:i:s A');
		$date_only = DateTime::createFromFormat('m/d/Y h:i:s A', $date_only);
		$date_only = $date_only->format("d-M-y");

		$datelist= array();
		$email_cnt = array();
		$adata = array();
		$total = 0;
		$x = date('y-m-d');//"18-02-04" format for testing to expired
		$yy =array();

		foreach ($content as $key => $value) {
			$email_cnt[$value['TEMPLATE_TYPE']] = $value['CONTENT'];
		}

		foreach ($days as $key => $value) {
			$datelist[$value['CONFIG_NAME']] = $value['CONFIG_VALUE'];
		}
		foreach ($res as $key => $value) {
			$total = (strtotime($x) - strtotime($value['PRIMARY_START_DATE']))/86400;

			//$extd = date("Y-m-d", strtotime($date_only. ' + '.$datelist['primary_requirement_extension'].' days'));
			if(empty($value['VENDOR_INVITE_ID'])){
				continue;
			}
			
			$vendor_status_logs = $this->check_status_model->select_query('SMNTP_VENDOR_STATUS_LOGS',array(
				'VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID'],
				'STATUS_ID'	=> 191
			),'STATUS_ID');
			
			
			if(count($vendor_status_logs) > 0){
				//Use primary_requirement_extension 7 Days
				$expiration_day = $datelist['primary_requirement_extension'];
			}else{
				//Use primary_requirement_deactivate 14 Days
				$expiration_day = $datelist['primary_requirement_deactivate'];
			}
			
			// echo $value['VENDOR_INVITE_ID'] . " - " . $value['PRIMARY_START_DATE'] . " - " . $x . " - " . $expiration_day . " - " . $total . "</br>";
			echo $value['VENDOR_INVITE_ID'] . " - " . $value['PRIMARY_START_DATE'] . " - " . $x . " - " . $expiration_day . " - " . $total;
			
			if($total >= $expiration_day){
				$st = $expiration_day;
				
				echo " HerE". "</br>";
				
				if($total >= $st) {
				}
			}else{
				echo "</br>";
			}
		}
	}
}