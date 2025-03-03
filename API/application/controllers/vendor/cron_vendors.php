<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class Cron_vendors extends REST_Controller
{
	public $debug;
	// Load model in constructor
	public function __construct() {
		parent::__construct();		
		$this->debug = FALSE;
		$this->load->model('common_model');
		$this->load->model('mail_model');
	}
	//http://piccolo/smntp/app/index.php/vendor/cron_vendors/cron_expired_token
	function cron_expired_token_get()
	{
		$where_arr 	= array('CONFIG_NAME' => 'invite_expiration_days');
		$expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);
		
		$where_arr 	= array('CONFIG_NAME' => 'invite_extension_days');
		$extension_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);
		
		$today 		= strtotime("now");

		$rs = $this->common_model->active_tokens();
		$data = array();
		foreach ($rs->result() as $row)
		{
			$date_created 	= $row->DATE_CREATED;
			$invite_id 		= $row->VENDOR_INVITE_ID;
			$token 			= $row->TOKEN;
			$user_id 		= $row->USER_ID;
			
			$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
			$vendor_invite_status_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'STATUS_ID', $where_arr);
			
			// 6 - Invite Extended
			$expiry = 0;
			if($vendor_invite_status_id == 6){
				$expiry = strtotime($date_created. ' + '.$extension_day.' days');
			}else{
				$expiry = strtotime($date_created. ' + '.$expire_day.' days');
			}
			
			if($today >= $expiry) // update status to invite expired
			{
				//get id of invite creator then get its positionid start
				$where_arr = ['VENDOR_INVITE_ID' => $invite_id];
				$creator_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);
				
				if(!empty($creator_id)){
					$vendor_name = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);
					
					$where_id = ['USER_ID' => $creator_id];
					$position_id = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_id);
					//get id of invite creator then get its positionid end
					//$this->response($where_arr);
					
					/* Modified by MSF - 20191108 (IJR-10617)
					$record = array(
							'STATUS_ID' 	=> 5, // expired invite
							'POSITION_ID' 	=> $position_id // send to creator positionid
						);
					*/
					if($vendor_invite_status_id == 6){
						$record = array(
							'STATUS_ID' 	=> 7, // expired invite
							'POSITION_ID' 	=> $position_id // send to creator positionid
						);
					}else{
						$record = array(
							'STATUS_ID' 	=> 5, // expired invite
							'POSITION_ID' 	=> $position_id // send to creator positionid
						);	
					}
					
					$where = array(
							'VENDOR_INVITE_ID' => $invite_id
						);
					$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record, $where);

					$this->common_model->deactive_token($token);

					$sysdate = $this->common_model->get_sysdate();

					// $record = array(
					// 		'INVITE_EXPIRATION' => $sysdate
					// 	);
					// $where = array(
					// 		'VENDOR_INVITE_ID' => $invite_id
					// 	);

					$this->common_model->expired_column('INVITE_EXPIRATION', date('m-d-Y'), $invite_id);
					// $this->common_model->expired_column('PRIMARY_EXPIRATION', date('m-d-Y'), $invite_id);
					// $this->common_model->expired_column('ADDITIONAL_EXPIRATION', date('m-d-Y'), $invite_id);

					
					$email_creator	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_id);
					$creator_name 	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME), (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_id, 'FULLNAME');
					$crator_posname = $this->common_model->get_position_name($position_id);
					$creator_name 	= $creator_name;//.' ('.$crator_posname.')';

					/* Modified by MSF - 20191108 (IJR-10617)
					$email_arr 	= ['TEMPLATE_TYPE' 	=> 11, //11 for expired email for senmer/buyer
										'ACTIVE'	=> 1];
					*/
					if($vendor_invite_status_id == 6){
						$email_arr 	= ['TEMPLATE_TYPE' 	=> 65, //11 for expired email for senmer/buyer
										'ACTIVE'	=> 1];
					}else{
						$email_arr 	= ['TEMPLATE_TYPE' 	=> 11, //11 for expired email for senmer/buyer
										'ACTIVE'	=> 1];
					}
					$rs_email 	= $this->common_model->get_email_template($email_arr);
					$message 	= $rs_email->row()->CONTENT;

					$message = str_replace('[creatorname]', $creator_name, $message);
					$message = str_replace('[vendorname]', $vendor_name, $message);

					
					/* Modified by MSF - 20191108 (IJR-10617)
					$portal_arr = array(
						'TYPE_ID' 		=> 1, // for registration
						'STATUS_ID' 	=> 5 // Vendor registration expired
					);
					*/
					if($vendor_invite_status_id == 6){
						$portal_arr = array(
							'TYPE_ID' 		=> 1, // for registration
							'STATUS_ID' 	=> 800 // Vendor registration expired
						);
					}else{
						$portal_arr = array(
							'TYPE_ID' 		=> 1, // for registration
							'STATUS_ID' 	=> 5 // Vendor registration expired
						);
					}
					$rs_portal 	= $this->common_model->get_message_default($portal_arr)->result_array();
					$rs_portal[0]['MESSAGE'] = str_replace('[creatorname]', $creator_name, $rs_portal[0]['MESSAGE']);
					$rs_portal[0]['MESSAGE'] = str_replace('[vendorname]', $vendor_name, $rs_portal[0]['MESSAGE']);
					
					// send message to creator
					$message_data['TYPE'] = 'notification';
					$message_data['SENDER_ID'] = 0; // no sender sent from cron
					$message_data['SUBJECT'] = $vendor_name;
					$message_data['TOPIC'] = urlencode($rs_portal[0]['TOPIC']);
					$message_data['BODY'] = urlencode($rs_portal[0]['MESSAGE']);
					$message_data['DATE_SENT'] = date('Y-m-d H:i:s');
					$message_data['INVITE_ID'] = $invite_id;
					$message_data['RECIPIENT_ID'] = $creator_id;

					$model_data = $this->mail_model->send_message($message_data);	

					// send email to creator
					$message = nl2br($message);

					$email_data['to'] 		= $email_creator;
					//$email_data['bcc']  	= 'justine.jovero@novwaresystems.com';
					$email_data['subject'] 	= $vendor_name . ' - ' . $rs_portal[0]['TOPIC'];
					$email_data['content'] 	= $message;
					$this->common_model->send_email_notification($email_data);
					
					

					// send email to vendor
					$email_vndor	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', ['USER_ID' => $user_id]);
					
					/* Modified by MSF - 20191108 (IJR-10617)
					$email_arr 		= ['TEMPLATE_TYPE' 	=> 12, //12 for expired email for Vendor
										'ACTIVE'	=> 1];
					*/
					if($vendor_invite_status_id == 6){
						$email_arr 		= ['TEMPLATE_TYPE' 	=> 9, //12 for expired email for Vendor
										'ACTIVE'	=> 1];
					}else{
						$email_arr 		= ['TEMPLATE_TYPE' 	=> 12, //12 for expired email for Vendor
										'ACTIVE'	=> 1];
					}
					$rs_email		= $this->common_model->get_email_template($email_arr);
					$message 		= $rs_email->row()->CONTENT;

					/*Modified by MSF - 20191108 (IJR-10617)
					$message = str_replace('[vendorname]', $vendor_name, $message);
					$email_data['subject'] 	= 'Vendor Registration Invite - Expired';
					*/

					if($vendor_invite_status_id == 6){
						$message = str_replace('[vendor_name]', $vendor_name, $message);
						$email_data['subject'] 	= 'Vendor Registration Invite - Closed';
					}else{
						$message = str_replace('[vendorname]', $vendor_name, $message);
						$email_data['subject'] 	= 'Vendor Registration Invite - Expired';
					}

					$email_data['to'] 		= $email_vndor;
					//$email_data['bcc']  	= 'justine.jovero@novwaresystems.com';
					$email_data['content'] 	= nl2br($message);
					$this->common_model->send_email_notification($email_data);


					$data[] = array(
						'creator' 		=> $creator_name,
						'vendor_name'	=> $vendor_name,
						'current_status_id' => $vendor_invite_status_id,
					);
				}
			}

		}
		
		//jay. pang reset kapag nirun yung expire_all_invites_get()
		if($this->debug){
			$this->reset_live_invite_extension_days_config_get();
			$this->reset_live_invite_expiration_days_config_get();
		}
		
		$this->response($data);
	}
	
	#
	# Vendor Invite
	#
	
	function expire_all_invites_get(){
		$this->debug = TRUE;
		$this->zero_invite_extension_days_config_get();
		$this->zero_invite_expiration_days_config_get();
		$this->cron_expired_token_get();
	}
	
	function expire_first_invites_only_get(){
		$this->debug = TRUE;
		$this->zero_invite_expiration_days_config_get();
		$this->cron_expired_token_get();
	}
	
	function expire_extend_invites_only_get(){
		$this->debug = TRUE;
		$this->zero_invite_extension_days_config_get();
		$this->cron_expired_token_get();
	}
	
	// Extended Invite Expiration
	function reset_live_invite_extension_days_config_get(){
		$this->reset_invite_config(7, 'invite_extension_days');
	}
	
	// First Vendor Invite Expiration
	function reset_live_invite_expiration_days_config_get(){
		$this->reset_invite_config(14, 'invite_expiration_days');
	}
	
	//Set to zero Extended Invite Expiration
	function zero_invite_expiration_days_config_get(){
		$this->reset_invite_config(0, 'invite_expiration_days');
	} 
	
	//Set to zero First Vendor Invite Expiration
	function zero_invite_extension_days_config_get(){
		$this->reset_invite_config(0, 'invite_extension_days');
	}
	
	//Reset Invite Expiration Config Funciton
	function reset_invite_config($value, $table){
		$record = array('CONFIG_VALUE' 	=> $value );
		$where = array('CONFIG_NAME' 	=> $table);
		$rs = $this->common_model->update_table('SMNTP_SYSTEM_CONFIG', $record, $where);
		
		if($rs){
			$data['result']  = "Success";
		}else{
			$data['result']  = "Failed";
		}
		if(! $this->debug){
			$this->response($data);
		}
	}
	
	//ARD AND ini_perdir
	
	function zero_prd_ard_expiration_config_get(){
		
		$rs = $this->db->query("UPDATE SMNTP_SYSTEM_CONFIG 
						SET CONFIG_VALUE = 0 
						WHERE CONFIG_NAME IN ('primary_requirement_extension',
												'primary_requirement_deactivate',
												'additional_requirement_deactivate')");
												

		if($rs){
			$data['result']  = "Success";
		}else{
			$data['result']  = "Failed";
		}									
		$this->response($data);
	}
	
	#
	# Vendor Invite End
	#
	
	function reset_prd_ard_expiration_config_get(){
		
		//Query 1
		$query =  $this->db->query("UPDATE SMNTP_SYSTEM_CONFIG
			SET CONFIG_VALUE = 14
			WHERE CONFIG_NAME IN ('primary_requirement_deactivate')"); //primary_requirement_extension
			
		$result['QUERY RESULT 1'] = array(
			'query' => "UPDATE SMNTP_SYSTEM_CONFIG
						SET CONFIG_VALUE = 14
						WHERE CONFIG_NAME IN ('primary_requirement_deactivate')",
			'result' => ($query ? 'SUCCESS' : 'FAILED')
		);
		
		
		$query =  $this->db->query("UPDATE SMNTP_SYSTEM_CONFIG
			SET CONFIG_VALUE = 7
			WHERE CONFIG_NAME IN ('primary_requirement_extension')"); //primary_requirement_extension
			
		$result['QUERY RESULT 2'] = array(
			'query' => "UPDATE SMNTP_SYSTEM_CONFIG
						SET CONFIG_VALUE = 7
						WHERE CONFIG_NAME IN ('primary_requirement_extension')",
			'result' => ($query ? 'SUCCESS' : 'FAILED')
		);
		
		//Query 2
		$query =  $this->db->query("UPDATE SMNTP_SYSTEM_CONFIG
										SET CONFIG_VALUE = 30
										WHERE CONFIG_NAME IN ('additional_requirement_deactivate')");
		$result['QUERY RESULT 3'] = array(
			'query' => "UPDATE SMNTP_SYSTEM_CONFIG
					SET CONFIG_VALUE = 30
					WHERE CONFIG_NAME IN ('additional_requirement_deactivate')",
			'result' => ($query ? 'SUCCESS' : 'FAILED')
		);
		
		$this->response($result);
	}
	
	
	//For prod email
	function get_all_users_get(){
		
		//Query 1
		$query =  $this->db->query("SELECT USER_ID, USER_EMAIL FROM SMNTP_USERS")->result_array(); //primary_requirement_extension
		
		$this->response($query);
	}
	
	function update_prod_email_get(){
		$users = array (
			0 => 
			array(
			 'USER_ID' => '6292',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			1 => 
			array(
			 'USER_ID' => '6268',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			2 => 
			array(
			 'USER_ID' => '6277',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			3 => 
			array(
			 'USER_ID' => '6294',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			4 => 
			array(
			 'USER_ID' => '6295',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			5 => 
			array(
			 'USER_ID' => '4590707',
			 'USER_EMAIL' => 'apple.potente@gmail.com'
			),
			6 => 
			array(
			 'USER_ID' => '6663',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			7 => 
			array(
			 'USER_ID' => '6664',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			8 => 
			array(
			 'USER_ID' => '4590744',
			 'USER_EMAIL' => 'rpaloma_016@yahoo.com'
			),
			9 => 
			array(
			 'USER_ID' => '4590745',
			 'USER_EMAIL' => 'vendorrelations.sm.ho@smretail.com'
			),
			10 => 
			array(
			 'USER_ID' => '4590749',
			 'USER_EMAIL' => 'vendorrelations.sm.ho@smretail.com'
			),
			11 => 
			array(
			 'USER_ID' => '6269',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			12 => 
			array(
			 'USER_ID' => '6279',
			 'USER_EMAIL' => 'dsratest@gmail.com'
			),
			13 => 
			array(
			 'USER_ID' => '6283',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			14 => 
			array(
			 'USER_ID' => '6290',
			 'USER_EMAIL' => 'mpdbu4@gmail.com'
			),
			15 => 
			array(
			 'USER_ID' => '4590682',
			 'USER_EMAIL' => 'rpaloma_016@yahoo.com'
			),
			16 => 
			array(
			 'USER_ID' => '4590742',
			 'USER_EMAIL' => 'apple.potente@gmail.com'
			),
			17 => 
			array(
			 'USER_ID' => '4590781',
			 'USER_EMAIL' => 'marc.anthony.pacres@novawaresystems.com'
			),
			18 => 
			array(
			 'USER_ID' => '4590702',
			 'USER_EMAIL' => 'vendorrelations.sm.ho@smretail.com'
			),
			19 => 
			array(
			 'USER_ID' => '4590748',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			20 => 
			array(
			 'USER_ID' => '6274',
			 'USER_EMAIL' => 'VendorRelations.SM.HO@smretail.com'
			),
			21 => 
			array(
			 'USER_ID' => '6285',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			22 => 
			array(
			 'USER_ID' => '6289',
			 'USER_EMAIL' => 'dsratest@gmail.com'
			),
			23 => 
			array(
			 'USER_ID' => '6293',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			24 => 
			array(
			 'USER_ID' => '6300',
			 'USER_EMAIL' => 'dsratest@gmail.com'
			),
			25 => 
			array(
			 'USER_ID' => '4590754',
			 'USER_EMAIL' => 'rpaloma_016@yahoo.com'
			),
			26 => 
			array(
			 'USER_ID' => '4590755',
			 'USER_EMAIL' => 'apple.potente@gmail.com'
			),
			27 => 
			array(
			 'USER_ID' => '4590757',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			28 => 
			array(
			 'USER_ID' => '6272',
			 'USER_EMAIL' => 'VendorRelations.SM.HO@smretail.com'
			),
			29 => 
			array(
			 'USER_ID' => '6273',
			 'USER_EMAIL' => 'VendorRelations.SM.HO@smretail.com'
			),
			30 => 
			array(
			 'USER_ID' => '6282',
			 'USER_EMAIL' => 'dsratest@gmail.com'
			),
			31 => 
			array(
			 'USER_ID' => '6299',
			 'USER_EMAIL' => 'dsratest@gmail.com'
			),
			32 => 
			array(
			 'USER_ID' => '6286',
			 'USER_EMAIL' => 'amgo@myherohub.com'
			),
			33 => 
			array(
			 'USER_ID' => '6288',
			 'USER_EMAIL' => 'marc.anthony.pacres@novawaresystems.com'
			),
			34 => 
			array(
			 'USER_ID' => '4590780',
			 'USER_EMAIL' => 'justine.jovero@novawaresystems.com'
			),
			35 => 
			array(
			 'USER_ID' => '6278',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			36 => 
			array(
			 'USER_ID' => '6275',
			 'USER_EMAIL' => 'dsratest@gmail.com'
			),
			37 => 
			array(
			 'USER_ID' => '6276',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			38 => 
			array(
			 'USER_ID' => '6280',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			39 => 
			array(
			 'USER_ID' => '6287',
			 'USER_EMAIL' => 'dsratest@gmail.com'
			),
			40 => 
			array(
			 'USER_ID' => '6291',
			 'USER_EMAIL' => 'marc.anthony.pacres@novawaresystems.com'
			),
			41 => 
			array(
			 'USER_ID' => '6296',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			42 => 
			array(
			 'USER_ID' => '4590662',
			 'USER_EMAIL' => 'apple.potente@gmail.com'
			),
			43 => 
			array(
			 'USER_ID' => '4590722',
			 'USER_EMAIL' => 'apple.potente@gmail.com'
			),
			44 => 
			array(
			 'USER_ID' => '4590753',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			45 => 
			array(
			 'USER_ID' => '6270',
			 'USER_EMAIL' => 'Apple.H.Potente@smretail.com'
			),
			46 => 
			array(
			 'USER_ID' => '6271',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			47 => 
			array(
			 'USER_ID' => '4590622',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			48 => 
			array(
			 'USER_ID' => '4590703',
			 'USER_EMAIL' => 'vendorrelations.sm.ho@smretail.com'
			),
			49 => 
			array(
			 'USER_ID' => '4590706',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			50 => 
			array(
			 'USER_ID' => '4590743',
			 'USER_EMAIL' => 'vendorrelations.sm.ho@smretail.com'
			),
			51 => 
			array(
			 'USER_ID' => '4590746',
			 'USER_EMAIL' => 'vendorrelations.sm.ho@smretail.com'
			),
			52 => 
			array(
			 'USER_ID' => '4590750',
			 'USER_EMAIL' => 'rpaloma_016@yahoo.com'
			),
			53 => 
			array(
			 'USER_ID' => '4590756',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			54 => 
			array(
			 'USER_ID' => '4590704',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			55 => 
			array(
			 'USER_ID' => '6267',
			 'USER_EMAIL' => NULL
			),
			56 => 
			array(
			 'USER_ID' => '6281',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			57 => 
			array(
			 'USER_ID' => '6284',
			 'USER_EMAIL' => 'AMGO@MYHEROHUB.com'
			),
			58 => 
			array(
			 'USER_ID' => '6297',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			59 => 
			array(
			 'USER_ID' => '6298',
			 'USER_EMAIL' => 'dsra_test@yahoo.com'
			),
			60 => 
			array(
			 'USER_ID' => '4590623',
			 'USER_EMAIL' => 'apple.potente@gmail.com'
			),
			61 => 
			array(
			 'USER_ID' => '4590705',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			62 => 
			array(
			 'USER_ID' => '4590747',
			 'USER_EMAIL' => 'rpaloma_016@yahoo.com'
			),
			63 => 
			array(
			 'USER_ID' => '4590751',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			),
			64 => 
			array(
			 'USER_ID' => '4590752',
			 'USER_EMAIL' => 'allie_012003@yahoo.com'
			)
		);
		
		$results = array();
		foreach($users as $user){
			$results[$user['USER_ID']] =  $this->db->query('UPDATE SMNTP_USERS SET USER_EMAIL = ? WHERE USER_ID = ?', array($user['USER_EMAIL'], $user['USER_ID'])); 
		}
		
		$this->response($results);
	}
	
	function reset_prod_email_get(){
		$result = $this->db->query('UPDATE SMNTP_USERS SET USER_EMAIL = ?', array('angelicaarzadonapprover@gmail.com'));
		
		$this->response($result);
	}

	//Modified by MSF - 20200210 (IJR-10617)
	function vendor_retension_get(){
		$this->load->helper('cron_helper');
		$counter = 0;
		$group_of_user_id = '0';
		$group_of_vendor_invite_id = '0';
		$where_arr 	= array('CONFIG_NAME' => 'vendor_retension_month(s)');
        $expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);
		
		$start_date = "2018-01-01";

		//$expiry = subtract_date(date('Y-m-d'),$expire_day);
		//$expiry = date("Y-m-d", strtotime("-".$expire_day." months"));
		$where = "STATUS_ID IN (1,4,5,7,241) AND DATE_UPDATED BETWEEN '". $start_date ."' AND DATE(DATE_SUB(CURDATE(), INTERVAL ".$expire_day." MONTH))";
		$rs = $this->db->query("SELECT SVS.VENDOR_INVITE_ID, SVI.USER_ID, SVS.STATUS_ID FROM SMNTP_VENDOR_STATUS SVS
						  INNER JOIN SMNTP_VENDOR_INVITE SVI ON SVS.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
						  WHERE ".$where)->result_array();
		// Getting all User ID's and Vendor Invite ID's
		foreach($rs as $row){
			if($row['USER_ID'] != NULL){
				$group_of_user_id .= ','.$row['USER_ID'];
			}
			$group_of_vendor_invite_id .= ','.$row['VENDOR_INVITE_ID'];
			$counter += 1;
		}

		//Insert To User_Logs
		if($group_of_user_id != "0"){
			$insert_user_logs = "INSERT INTO SMNTP_USERS_SYS_LOGS(USER_ID,USER_FIRST_NAME, USER_MIDDLE_NAME,USER_LAST_NAME,USER_TYPE_ID,POSITION_ID,USER_STATUS, USER_DATE_CREATED, USER_MOBILE, USER_EMAIL,VENDOR_ID, HEAD_ID)
									SELECT * FROM SMNTP_USERS WHERE USER_ID IN(".$group_of_user_id.")";
			$this->db->query($insert_user_logs);
		}

		//Insert To Vendor Logs
		if($group_of_vendor_invite_id != "0"){
			$insert_vendor_invite_logs = "INSERT INTO SMNTP_VENDOR_INVITE_SYS_LOGS(VENDOR_INVITE_ID, VENDOR_NAME, CONTACT_PERSON, EMAIL, TEMPLATE_ID, MESSAGE, APPROVER_NOTE, DATE_CREATED, CREATED_BY, ACTIVE, USER_ID, EMAIL_TEMPLATE_ID, BUSINESS_TYPE, TRADE_VENDOR_TYPE, REASON_FOR_EXTENSION, REMOVE_DATE)
											SELECT SVI.*, CURRENT_DATE FROM SMNTP_VENDOR_INVITE SVI WHERE SVI.VENDOR_INVITE_ID IN (".$group_of_vendor_invite_id.") ";
			$this->db->query($insert_vendor_invite_logs);
		}

		//set credentials to deactivated
        $record = array(
            'DEACTIVATED_FLAG' => 1
            );
		$where = "USER_ID IN (".$group_of_user_id.")";
		$rs = $this->common_model->update_table('SMNTP_CREDENTIALS', $record, $where);
		
		//Delete Users
		$delete_users = "DELETE FROM SMNTP_USERS WHERE USER_ID IN (".$group_of_user_id.")";
		$this->db->query($delete_users);

		//Delete Vendors
		$delete_vendors = "DELETE FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID IN (".$group_of_vendor_invite_id.")";
		$this->db->query($delete_vendors);

		//$this->response("Deleting Vendor Invite ID's, ".$group_of_vendor_invite_id);
		$this->response($rs);
	}
	
	//additional MSF Phase2 - 20210316
	function migration_orafin_get(){
		$this->load->library('CryptManager');
		//exit();
		$list_of_vendors = '';
		foreach(glob('/var/www/html/API/storage/Inbound/Orafin/*.csv') as $filename){
			$fh = fopen( $filename,"r" );
			$ctr = 0;
			while ( ( $data = fgetcsv( $fh ) ) !== false ){
				if($ctr != 0){
					$vendor_name           = trim($data[0],'"');
					$template_id           = 1120;
					$email_template_id     = 803;
					$trade_vendor_type     = trim($data[1],'"'); //outright or sc
					$registration_type     = 2;
					$user_type_id          = 2;
					$position_id           = 10;
					$termspayment          = trim($data[2],'"');
					$vendor_code           = trim($data[3],'"');
					$tin_no                = trim($data[4],'"');
					//$business_type         = trim($data[5],'"'); //vendor type
					$business_type          = 1; // Trade Vendor
					$category         		= explode("|",$data[5]);
					$ownership_type         = trim($data[6],'"'); // 1 = CORPORATION , 2 = PARTNERSHIP , 3 = SOLE PROPRIETORSHIP , 4 = FREE LANCE
					
					if(strtoupper($trade_vendor_type) == "OUTRIGHT"){
						$trade_vendor_type = 1;
					}else{
						$trade_vendor_type = 2;
					}
					
					switch(strtoupper($ownership_type)){
						case 'CORPORATION':
							$ownership_type_id = 1;
							break;
						
						case 'PARTNERSHIP':
							$ownership_type_id = 2;
							break;
						
						case 'SOLE PROPRIETORSHIP':
							$ownership_type_id = 3;
							break;
						
						case 'FREE LANCE':
							$ownership_type_id = 4;
							break;
					}
					
					$check_vendor = $this->db->query("SELECT VENDOR_NAME from SMNTP_VENDOR_INVITE where VENDOR_NAME = '".$this->db->escape_str($vendor_name)."'")->result_array();
					if(count($check_vendor) > 0){
						$list_of_vendors .= $check_vendor[0]['VENDOR_NAME']. "<br/>";
						continue;
					}
					
					$insert_vendor_invite = "INSERT INTO SMNTP_VENDOR_INVITE(VENDOR_NAME,TEMPLATE_ID,DATE_CREATED,CREATED_BY,ACTIVE,EMAIL_TEMPLATE_ID,TRADE_VENDOR_TYPE,REGISTRATION_TYPE,BUSINESS_TYPE)
											 VALUES('".$this->db->escape_str($vendor_name)."','".$template_id."',NOW(),4596414,1,'".$email_template_id."','".$trade_vendor_type."','".$registration_type."','".$business_type."')";
					$this->db->query($insert_vendor_invite);
											 
					$insert_users = "INSERT INTO SMNTP_USERS(USER_FIRST_NAME,USER_TYPE_ID,POSITION_ID)
									 VALUES('".$this->db->escape_str($vendor_name)."','".$user_type_id."','".$position_id."')";
					$this->db->query($insert_users);
									 
					$get_invite_id =  $this->db->query("SELECT VENDOR_INVITE_ID FROM SMNTP_VENDOR_INVITE WHERE VENDOR_NAME = '".$this->db->escape_str($vendor_name)."'")->result_array();
					$vendor_invite_id = $get_invite_id[0]['VENDOR_INVITE_ID'];
									 
					$get_user_id =  $this->db->query("SELECT USER_ID FROM SMNTP_USERS WHERE USER_FIRST_NAME = '".$this->db->escape_str($vendor_name)."'")->result_array();
					$user_id = $get_user_id[0]['USER_ID'];
					
					$get_terms_payment = $this->db->query("SELECT TERMS_PAYMENT_ID FROM SMNTP_TERMS_PAYMENT WHERE terms_payment_name = '".strtoupper($termspayment)."'")->result_array();
					$terms_payment = $get_terms_payment[0]['TERMS_PAYMENT_ID'];
					
					$insert_vendor_status = "INSERT INTO SMNTP_VENDOR_STATUS(VENDOR_INVITE_ID,STATUS_ID,POSITION_ID,DATE_UPDATED,TERMSPAYMENT,ACTIVE)
											 VALUES('".$vendor_invite_id."',3,10,NOW(),".$terms_payment.",1)";
					$this->db->query($insert_vendor_status);
                    
					$insert_credentials = "INSERT INTO SMNTP_CREDENTIALS(USER_ID,USERNAME,TIME_STAMP)
										   VALUES('".$user_id."','".$vendor_code."',NOW())";
					$this->db->query($insert_credentials);
										   
					$get_credentials = $this->db->query("SELECT CONCAT(DATE_FORMAT(TIME_STAMP,'%d-'),UCASE(DATE_FORMAT(TIME_STAMP,'%b')),DATE_FORMAT(TIME_STAMP,'-%y %h.%i.%s.%f %p')) AS TIME_STAMP,`PASSWORD` FROM SMNTP_CREDENTIALS WHERE USER_ID = '".$user_id."'")->result_array();
					$time_stamp = $get_credentials[0]['TIME_STAMP'];
					$password = $get_credentials[0]['PASSWORD'];
					
					$insert_vendor = "INSERT INTO SMNTP_VENDOR(VENDOR_INVITE_ID, VENDOR_NAME, TAX_ID_NO, VENDOR_CODE, OWNERSHIP_TYPE)
									  VALUES('".$vendor_invite_id."','".$this->db->escape_str($vendor_name)."','".$tin_no."','".$vendor_code."',".$ownership_type_id.")";
					$this->db->query($insert_vendor);
									  
					$update_vendor_invite = "UPDATE SMNTP_VENDOR_INVITE SET USER_ID = '".$user_id."' WHERE VENDOR_INVITE_ID = '".$vendor_invite_id."'";
					$this->db->query($update_vendor_invite);
					
					$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $time_stamp . $user_id)),0,16);
					$enc_password = $this->cryptmanager->encrypt(substr($tin_no,0,9), FALSE, $file_crypt_key);
					
					$update_credentials = "UPDATE SMNTP_CREDENTIALS SET PASSWORD = '".$enc_password."' WHERE USER_ID = '".$user_id."'";
					$this->db->query($update_credentials);
					
					$insert_token = "INSERT INTO SMNTP_VENDOR_TOKEN(TOKEN,DATE_CREATED,USER_ID,ACTIVE,VENDOR_INVITE_ID) VALUES('".substr($tin_no,0,9)."',NOW(),".$user_id.",1,".$vendor_invite_id.")";
					$this->db->query($insert_token);
					
					$insert_user_status_logs = "INSERT INTO SMNTP_USERS_STATUS_LOGS(USER_ID, USER_STATUS_ID, DATE_MODIFIED) VALUES(".$user_id.",1,NOW())";
					$this->db->query($insert_user_status_logs);					
					
					foreach($category as $cat){
						$rs_category =  $this->db->query("SELECT DISTINCT b.CATEGORY_ID FROM TEST_20220525 a JOIN SMNTP_CATEGORY b ON a.DEPT_DESC = b.CATEGORY_NAME WHERE a.dept_code = ".$cat)->result_array();
						$category_id = $rs_category[0]['CATEGORY_ID'];
						
						$insert_category = "INSERT INTO SMNTP_VENDOR_CATEGORIES(VENDOR_INVITE_ID, CATEGORY_ID, DATE_CREATED, ACTIVE)
										  VALUES('".$vendor_invite_id."','".$category_id."',NOW(),1)";
						$this->db->query($insert_category);
					}
				}
				$ctr += 1;
			}
		}
		$this->response('Record Inserted ['.$ctr.']<br/> List of Vendors not Inserted<br/>'.$list_of_vendors);
	}


	
	//additional MSF Phase2 - 20210316
	function migration_orafin_two_get(){
		$this->load->library('CryptManager');
		$list_of_vendors = '';
		foreach(glob('/var/www/html/API/storage/Inbound/Orafin_02/*.csv') as $filename){
			$fh = fopen( $filename,"r" );
			$ctr = 0;
			while ( ( $data = fgetcsv( $fh ) ) !== false ){
				if($ctr != 0){
					$vendor_name           = trim($data[0],'"');
					$template_id           = 1120;
					$email_template_id     = 803;
					$trade_vendor_type     = trim($data[1],'"'); //outright or sc
					$registration_type     = 2;
					$user_type_id          = 2;
					$position_id           = 10;
					$termspayment          = trim($data[2],'"');
					$vendor_code           = trim($data[3],'"');
					$tin_no                = trim($data[4],'"');
					//$business_type         = trim($data[5],'"'); //vendor type
					$business_type          = 1; // Trade Vendor
					$category         		= explode("|",$data[5]);
					$ownership_type         = trim($data[6],'"'); // 1 = CORPORATION , 2 = PARTNERSHIP , 3 = SOLE PROPRIETORSHIP , 4 = FREE LANCE
					$vendor_code_02			= $data[7];
					$category_02         		= explode("|",$data[8]);
					$termspayment_02          = trim($data[9],'"');
					
					if(strtoupper($trade_vendor_type) == "OUTRIGHT"){
						$trade_vendor_type = 1;
					}else{
						$trade_vendor_type = 2;
					}
					
					switch(strtoupper($ownership_type)){
						case 'CORPORATION':
							$ownership_type_id = 1;
							break;
						
						case 'PARTNERSHIP':
							$ownership_type_id = 2;
							break;
						
						case 'SOLE PROPRIETORSHIP':
							$ownership_type_id = 3;
							break;
						
						case 'FREE LANCE':
							$ownership_type_id = 4;
							break;
					}
					
					$check_vendor = $this->db->query("SELECT VENDOR_NAME from SMNTP_VENDOR_INVITE where VENDOR_NAME = '".$this->db->escape_str($vendor_name)."'")->result_array();
					if(count($check_vendor) > 0){
						$list_of_vendors .= $check_vendor[0]['VENDOR_NAME']. "<br/>";
						continue;
					}
					
					$insert_vendor_invite = "INSERT INTO SMNTP_VENDOR_INVITE(VENDOR_NAME,TEMPLATE_ID,DATE_CREATED,CREATED_BY,ACTIVE,EMAIL_TEMPLATE_ID,TRADE_VENDOR_TYPE,REGISTRATION_TYPE,BUSINESS_TYPE)
											 VALUES('".$this->db->escape_str($vendor_name)."','".$template_id."',NOW(),4596414,1,'".$email_template_id."','".$trade_vendor_type."','".$registration_type."','".$business_type."')";
					$this->db->query($insert_vendor_invite);
											 
					$insert_users = "INSERT INTO SMNTP_USERS(USER_FIRST_NAME,USER_TYPE_ID,POSITION_ID)
									 VALUES('".$this->db->escape_str($vendor_name)."','".$user_type_id."','".$position_id."')";
					$this->db->query($insert_users);
									 
					$get_invite_id =  $this->db->query("SELECT VENDOR_INVITE_ID FROM SMNTP_VENDOR_INVITE WHERE VENDOR_NAME = '".$this->db->escape_str($vendor_name)."'")->result_array();
					$vendor_invite_id = $get_invite_id[0]['VENDOR_INVITE_ID'];
									 
					$get_user_id =  $this->db->query("SELECT USER_ID FROM SMNTP_USERS WHERE USER_FIRST_NAME = '".$this->db->escape_str($vendor_name)."'")->result_array();
					$user_id = $get_user_id[0]['USER_ID'];
					
					$get_terms_payment = $this->db->query("SELECT TERMS_PAYMENT_ID FROM SMNTP_TERMS_PAYMENT WHERE terms_payment_name = '".strtoupper($termspayment)."'")->result_array();
					$terms_payment = $get_terms_payment[0]['TERMS_PAYMENT_ID'];
					
					$get_terms_payment_02 = $this->db->query("SELECT TERMS_PAYMENT_ID FROM SMNTP_TERMS_PAYMENT WHERE terms_payment_name = '".strtoupper($termspayment_02)."'")->result_array();
					$terms_payment_02 = $get_terms_payment_02[0]['TERMS_PAYMENT_ID'];
					
					$insert_vendor_status = "INSERT INTO SMNTP_VENDOR_STATUS(VENDOR_INVITE_ID,STATUS_ID,POSITION_ID,DATE_UPDATED,TERMSPAYMENT,ACTIVE,AVC_TERMSPAYMENT)
											 VALUES('".$vendor_invite_id."',3,10,NOW(),".$terms_payment.",1,".$terms_payment_02.")";
					$this->db->query($insert_vendor_status);
                    
					$insert_credentials = "INSERT INTO SMNTP_CREDENTIALS(USER_ID,USERNAME,TIME_STAMP)
										   VALUES('".$user_id."','".$vendor_code."',NOW())";
					$this->db->query($insert_credentials);
										   
					$get_credentials = $this->db->query("SELECT CONCAT(DATE_FORMAT(TIME_STAMP,'%d-'),UCASE(DATE_FORMAT(TIME_STAMP,'%b')),DATE_FORMAT(TIME_STAMP,'-%y %h.%i.%s.%f %p')) AS TIME_STAMP,`PASSWORD` FROM SMNTP_CREDENTIALS WHERE USER_ID = '".$user_id."'")->result_array();
					$time_stamp = $get_credentials[0]['TIME_STAMP'];
					$password = $get_credentials[0]['PASSWORD'];
					
					$insert_vendor = "INSERT INTO SMNTP_VENDOR(VENDOR_INVITE_ID, VENDOR_NAME, TAX_ID_NO, VENDOR_CODE, OWNERSHIP_TYPE,VENDOR_CODE_02)
									  VALUES('".$vendor_invite_id."','".$this->db->escape_str($vendor_name)."','".$tin_no."','".$vendor_code."',".$ownership_type_id.",".$vendor_code_02.")";
					$this->db->query($insert_vendor);
									  
					$update_vendor_invite = "UPDATE SMNTP_VENDOR_INVITE SET USER_ID = '".$user_id."' WHERE VENDOR_INVITE_ID = '".$vendor_invite_id."'";
					$this->db->query($update_vendor_invite);
					
					$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $time_stamp . $user_id)),0,16);
					$enc_password = $this->cryptmanager->encrypt(substr($tin_no,0,9), FALSE, $file_crypt_key);
					
					$update_credentials = "UPDATE SMNTP_CREDENTIALS SET PASSWORD = '".$enc_password."' WHERE USER_ID = '".$user_id."'";
					$this->db->query($update_credentials);
					
					$insert_token = "INSERT INTO SMNTP_VENDOR_TOKEN(TOKEN,DATE_CREATED,USER_ID,ACTIVE,VENDOR_INVITE_ID) VALUES('".substr($tin_no,0,9)."',NOW(),".$user_id.",1,".$vendor_invite_id.")";
					$this->db->query($insert_token);
					
					$insert_user_status_logs = "INSERT INTO SMNTP_USERS_STATUS_LOGS(USER_ID, USER_STATUS_ID, DATE_MODIFIED) VALUES(".$user_id.",1,NOW())";
					$this->db->query($insert_user_status_logs);
					
					foreach($category as $cat){
						$category_id = 0;
						$rs_category =  $this->db->query("SELECT DISTINCT b.CATEGORY_ID FROM TEST_20220525 a JOIN SMNTP_CATEGORY b ON a.DEPT_DESC = b.CATEGORY_NAME WHERE a.dept_code = ".$cat)->result_array();
						$category_id = $rs_category[0]['CATEGORY_ID'];
						
						$insert_category = "INSERT INTO SMNTP_VENDOR_CATEGORIES(VENDOR_INVITE_ID, CATEGORY_ID, DATE_CREATED, ACTIVE)
										  VALUES('".$vendor_invite_id."','".$category_id."',NOW(),1)";
						$this->db->query($insert_category);
					}
					
					foreach($category_02 as $cat_02){
						$category_id_02 = 0;
						$rs_category_02 =  $this->db->query("SELECT DISTINCT b.CATEGORY_ID FROM TEST_20220525 a JOIN SMNTP_CATEGORY b ON a.DEPT_DESC = b.CATEGORY_NAME WHERE a.dept_code = ".$cat_02)->result_array();
						$category_id_02 = $rs_category_02[0]['CATEGORY_ID'];
						
						$insert_category_02 = "INSERT INTO SMNTP_VENDOR_AVC_CAT(VENDOR_INVITE_ID, CATEGORY_ID, DATE_CREATED, ACTIVE)
										  VALUES('".$vendor_invite_id."','".$category_id_02."',NOW(),1)";
						$this->db->query($insert_category_02);
					}
				}
				$ctr += 1;
			}
		}
		$this->response('Record Inserted ['.$ctr.']<br/> List of Vendors not Inserted<br/>'.$list_of_vendors);
	}
}?>