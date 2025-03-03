<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Resetpassword extends CI_Controller
{
	
	function index($token = null)
	{
		
		$no_js_message = $this->rest_app->get('index.php/common_api/get_config', '', 'application/json');
		
		$data = array();
		if( ! empty($no_js_message)){
			$data['no_js_message'] = $no_js_message->CONFIG_DESCRIPTION;
		}
		$username = '';
		$message  = '';
		$alert_class  = '';
		$data['reset'] = 0;
		if ($token != null)
		{
			$data['token'] = $token;
			$rs = $this->rest_app->get('index.php/common_api/token_info', $data, 'application/json');
			//$this->rest_app->debug();
			$rs = (array)$rs;
			
			if(!empty($rs)){
				$resultcount 	= $rs['resultcount'];
				$query 			= $rs['query'];
				$creator_data 	= $rs['creatordata'];
				if($query[0]->REGISTRATION_TYPE == 2){
					$expire_day		= 3650;
				}else{
					$expire_day		= $rs['expire_day'];	
				}

				if ($resultcount > 0)
				{
					$username = $query[0]->USERNAME;
					$invite_id = $query[0]->VENDOR_INVITE_ID;
					$date_created = $query[0]->DATE_CREATED;

					$expiry = strtotime($date_created. ' + '.$expire_day.' days');
					$today = strtotime("now");
					
					if($today >= $expiry)
					{
						$message = '<strong>Invite Expired!</strong>';
						$alert_class = 'alert-danger';
						
						$var = array('invite_id' => $invite_id, 'token' => $token);
						// update status to invite expired
						$rs = $this->rest_app->put('index.php/common_api/expired_invite', $var, 'text');
					
						if($rs->status && empty($rs->error)){
							//Send Notif
							//Status ID 5 = Invite Expired
							$gmd = $this->rest_app->get('index.php/mail/get_message_default', array('type_id' => 1, 'status_id' => 5), 'application/json');
							$vendorname = $query[0]->VENDOR_NAME;
							
							
							$creatorname = $creator_data[0]->USER_FIRST_NAME
										. (!empty($creator_data[0]->USER_MIDDLE_NAME) ? ' ' . $creator_data[0]->USER_MIDDLE_NAME : '') 
										. (!empty($creator_data[0]->USER_LAST_NAME) ? ' ' . $creator_data[0]->USER_LAST_NAME : '') ;
							
							
							$post_data['user_id'] = $query[0]->CREATED_BY;
					
							$post_data['type'] = 'notification';
							
							$post_data['recipient_id'] = $query[0]->CREATED_BY;
							$post_data['mail_subj'] = str_replace('[vendorname]', $vendorname, $gmd->SUBJECT);
							$post_data['mail_topic'] = str_replace('[vendorname]', $vendorname, $gmd->TOPIC);
							
							$message = str_replace('[creatorname]', $creatorname, $gmd->MESSAGE);
							$message = str_replace('[vendorname]', $vendorname, $message);
							
							$post_data['mail_body'] = $message;
							$post_data['invite_id'] = $query[0]->VENDOR_INVITE_ID;
							$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
							//Send Email
							$email_data['to'] = $creator_data[0]->USER_EMAIL;
							$email_data['subject'] = $post_data['mail_topic'];
							$email_data['content'] = $message;
							$send_email = $this->rest_app->post('index.php/common_api/send_email_message', $email_data, '');
							$message = "<strong>Invite Expired!</strong>";
						}	
						
					}
				}
				else
				{
					$message = '<strong>Invite Expired!</strong>';
					$alert_class = 'alert-danger';
					$data['btn_disable'] = 'disabled';
				}
			}else
			{
				$message = '<strong>Invite Expired!</strong>';
				$alert_class = 'alert-danger';
				$data['btn_disable'] = 'disabled';
			}
		}

		$data['username'] 	 = $username;
		$data['message'] 	 = $message;
		$data['alert_class'] = $alert_class;

		$this->load->view('common/reset_password', $data);
	}

	function validate_password($token = null)
	{
		$data['password'] = $this->input->post('new_password');
		$data['token'] 	= $token;
		$data['reset'] = 0;

		if ($token != null)
		{

			$data['token'] = $token;
			$rs = $this->rest_app->get('index.php/common_api/token_info', $data, 'application/json');
			$rs = (array)$rs;

			$resultcount 	= $rs['resultcount'];
			$query 			= $rs['query'];

			$data['que'] = $query;

			if ($resultcount > 0)
			{
				$username = $query[0]->USERNAME;
				$data['invite_id'] = $query[0]->VENDOR_INVITE_ID;
				$data['username'] = $username;
				$test = $rs;
				$rs = $this->rest_app->put('index.php/common_api/reset_pw', $data, 'text');

				if (isset($rs->status) && $rs->status)
				{
					//3 = Invited
					$gmd = $this->rest_app->get('index.php/mail/get_message_default', array('type_id' => 1, 'status_id' => 3), 'application/json');
					$vendorname = $query[0]->VENDOR_NAME;
					
					
					//$creatorname = $creator_data[0]->USER_FIRST_NAME
					//			. (!empty($creator_data[0]->USER_MIDDLE_NAME) ? ' ' . $creator_data[0]->USER_MIDDLE_NAME : '') 
					//			. (!empty($creator_data[0]->USER_LAST_NAME) ? ' ' . $creator_data[0]->USER_LAST_NAME : '') ;
					//
					
					$post_data['user_id'] = $query[0]->USER_ID;
			
					$post_data['type'] = 'notification';
					
					$post_data['recipient_id'] = $query[0]->USER_ID; //Change to vendor id here
					$post_data['mail_subj'] = str_replace('[vendorname]', $vendorname, $gmd->SUBJECT);
					$post_data['mail_topic'] = str_replace('[vendorname]', $vendorname, $gmd->TOPIC);
					
					$message = str_replace('[vendorname]', $vendorname, $gmd->MESSAGE);
					$message = str_replace('[submission_deadline]', $rs->submission_deadline, $message);
					
					$post_data['mail_body'] = $message;
					$post_data['invite_id'] = $query[0]->VENDOR_INVITE_ID;
					$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
					//$this->rest_app->debug();
					
					//print_r($post_data);
					$data['message'] = '<strong>Password Updated!</strong> Click <a href="'.base_url().'">Login</a> to continue';
					$data['alert_class'] = 'alert-success';
					$data['reset'] = 1;
				}
				
				$data['username'] 	= $username;
				
				//print_r($rs);
				//print_r($query);
				//die();
			}
		}

		$this->load->view('common/reset_password', $data);
	}
}
?>