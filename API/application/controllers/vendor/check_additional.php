<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Check_additional extends REST_Controller {
	
	public function __construct() {
		parent::__construct();
			// $this->load->model('vendor/check_status_model');
		$this->load->model('vendor/check_status_model');
		$this->load->model('mail_model');
		$this->load->model('common_model');
	}

	function cron_additional_put()
	{
		$res = $this->check_status_model->select_foradditional_vendor();
		$days = $this->check_status_model->select_additional_days();
		$datelist= array();
		// $data = array();
		$total = 0;
		$x = date('y-m-d');
		//$x = date('18-02-04'); //commented by jay



		//$date_timestamp = date('m/d/Y h:i:s A');
		//$date_timestamp = DateTime::createFromFormat('m/d/Y h:i:s A', $date_timestamp);
		//$date_timestamp = $date_timestamp->format("d-M-y h.i.s.u A");
		$date_timestamp = date('Y/m/d H:i:s');
		$yy =array();
		// $user_id = "";
		


		foreach ($days as $key => $value) {
			$datelist[$value['CONFIG_NAME']] = $value['CONFIG_VALUE'];
		}
		
		foreach ($res as $key => $value) {
		$total = (strtotime($x) - strtotime($value['ADDITIONAL_START_DATE']))/86400;
		

			if($total >= ($datelist['additional_requirement_deactivate']) ){
				$vendor_invite_id = $value['VENDOR_INVITE_ID'];
				//marc // Jay nilipat ko sa taas nag uundefined offset
				$vendor_details = $this->check_status_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $vendor_invite_id),'EMAIL,VENDOR_NAME,USER_ID,CREATED_BY');
					
					$position_id = $value['POSITION_ID'];
					$vendor_status_invite_id = $value['VENDOR_INVITE_STATUS_ID'];

					$where_arr = ['VENDOR_INVITE_ID' => $vendor_invite_id];
					$vendor_name = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

					$where_arr = ['VENDOR_INVITE_ID' => $vendor_invite_id];
					$user_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', $where_arr);

					$this->common_model->expired_column('ADDITIONAL_EXPIRATION', date('m-d-Y'), $vendor_invite_id);

					$this->check_status_model->update_query('SMNTP_VENDOR_STATUS',array('STATUS_ID' => '193'),array('VENDOR_INVITE_ID' => $vendor_invite_id));
					$this->check_status_model->update_query('SMNTP_VENDOR_STATUS',array('ADDITIONAL_DEACTIVATED_FLAG' => '1'),array('VENDOR_INVITE_ID' => $vendor_invite_id));
					$this->check_status_model->update_query('SMNTP_CREDENTIALS',array('DEACTIVATED_FLAG' => '1'),array('USER_ID' => $user_id));
					$this->check_status_model->update_query('SMNTP_USERS',array('USER_STATUS' => '0'),array('USER_ID' => $user_id));
					$this->common_model->insert_table('SMNTP_ACTION_LOGS', array('USER_ID' => $user_id, 'ACTION_ID' => '27', 'DATE_CREATED' => $date_timestamp, 'ACTIVE' => '1'));

					$this->common_model->insert_table('SMNTP_VENDOR_STATUS_LOGS', array('VENDOR_INVITE_STATUS_ID' => $vendor_status_invite_id, 'VENDOR_INVITE_ID' => $vendor_invite_id, 'STATUS_ID' => '193', 'DATE_UPDATED' => $date_timestamp, 'POSITION_ID' => $position_id, 'ACTIVE' => '1'));

					$user_status_logs = array(
						'USER_ID' => $user_id,
						'USER_STATUS_ID' => 0,
						'DATE_MODIFIED' => date("Y-m-d h:i:s")
					);

					$susl = $this->db->insert('SMNTP_USERS_STATUS_LOGS',$user_status_logs);


					// send email to vendor
					//marc 
					//$vendor_details = $this->check_status_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $vendor_invite_id),'EMAIL,VENDOR_NAME,USER_ID,CREATED_BY');

				
				//jay niligay ko sa loob
				if(!empty($vendor_details)){	
					$vrds = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('USER_ID' => $vendor_details[0]['CREATED_BY']),'VRDSTAFF_ID,VRDHEAD_ID');

					//$this->response($vendor_details);
					$head_details = $this->check_status_model->select_query('SMNTP_USERS',array('USER_ID' => $vrds[0]['VRDHEAD_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
					$vemail = $this->check_status_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => '26'),'TEMPLATE_TYPE,CONTENT');
					
					//For multiple VRDHEAD
					foreach($head_details as $hd){	
						if(! empty($head_details)){
							$content2 = $this->check_status_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => '26'),'TEMPLATE_TYPE,CONTENT');
							
							$hd_name = $hd['USER_FIRST_NAME'] . ' ' . $hd['USER_MIDDLE_NAME'];
							$hd_name = trim($hd_name) . ' ' . $hd['USER_LAST_NAME'];
							$hd_name = trim($hd_name);
							$content2 = str_replace('[head]', $hd_name , $content2[0]['CONTENT']);
							$content2 = str_replace('[vendor_name]',  $vendor_details[0]['VENDOR_NAME'],$content2 );


							$send_data2 = array(
							'subject' => $vendor_details[0]['VENDOR_NAME'].' - User Account Deactivated',
							'content' =>  nl2br($content2),
							'to' =>  $hd['USER_EMAIL']
							);

							$this->common_model->send_email_notification($send_data2);
						}
					}
				
					$where_arr_def = array(
						'TYPE_ID' 		=> 1, // for registration
						'STATUS_ID' 	=> 197 //statusid for incomplete required documents
					);

					$vMess = $this->common_model->get_message_default($where_arr_def)->result_array();
						
					//$this->response($vrds);
					

					

					$c = 0;
	
					for($c = 0;$c < count($vrds); $c++){
						
						//jay
						if(empty($vrds[$c]['VRDSTAFF_ID'])){
							continue;
						}
						$vrinfo = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vrds[$c]['VRDSTAFF_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
						
						//Jay check if empty
						if(!empty($vrinfo)){
							$vrname = $this->trim_name($vrinfo);
							//email
							
							$temail = $vemail;
							$temail = str_replace('[head]', $vrname , $temail[0]['CONTENT']);
							$temail = str_replace('[vendor_name]',  $vendor_details[0]['VENDOR_NAME'],$temail );

							$send_data2 = array(
							'subject' => $vendor_details[0]['VENDOR_NAME'].' - User Account Deactivated',
							'content' =>  nl2br($temail) ,
							'cc' => '',
							'to' =>  $vrinfo[0]['USER_EMAIL']
							);

							$this->common_model->send_email_notification($send_data2);

							//end email

							//portal

							$tMess = $vMess;	
							$tMess[0]['SUBJECT'] = str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $tMess[0]['SUBJECT']);
							$tMess[0]['TOPIC'] = str_replace('[vendorname]',$vendor_details[0]['VENDOR_NAME'], $tMess[0]['TOPIC']);
							$tMess[0]['MESSAGE'] = str_replace('[recepient]', $vrname, $tMess[0]['MESSAGE']);
							$tMess[0]['MESSAGE'] = str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $tMess[0]['MESSAGE']);

							if(empty($vrds[$c]['VRDSTAFF_ID'])){
								continue;
							}
							$insert_array = array(
							'SUBJECT' => $tMess[0]['SUBJECT'],
							'TOPIC' => $tMess[0]['TOPIC'],
							'DATE_SENT' => date('Y-m-d H:i:s'),
							'BODY' => $tMess[0]['MESSAGE'],
							'TYPE' => 'notification',
							'SENDER_ID' => 0,
							'RECIPIENT_ID' => $vrds[$c]['VRDSTAFF_ID'], //can be changed in query
							'VENDOR_ID' => $vendor_details[0]['USER_ID']
							);			

							$model_data = $this->mail_model->send_message($insert_array);
						
						
						}
						
						

						$vrdhinfo = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vrds[$c]['VRDHEAD_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
						if(!empty($vrdhinfo)){	
							$vrdhname = $this->trim_name($vrdhinfo);	
							$temail = $vemail;
							$temail = str_replace('[head]', $vrdhname , $temail[0]['CONTENT']);
							$temail = str_replace('[vendor_name]',  $vendor_details[0]['VENDOR_NAME'],$temail );

							$send_data2 = array(
							'subject' => $vendor_details[0]['VENDOR_NAME'].' - User Account Deactivated',
							'content' => nl2br($temail) ,
							// 'cc' => '',
							'to' =>  $vrdhinfo[0]['USER_EMAIL']
							);

							$this->common_model->send_email_notification($send_data2);

							$tMess = $vMess;
							
							$tMess[0]['SUBJECT'] = str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $tMess[0]['SUBJECT']);
							$tMess[0]['TOPIC'] = str_replace('[vendorname]',$vendor_details[0]['VENDOR_NAME'], $tMess[0]['TOPIC']);
							$tMess[0]['MESSAGE'] = str_replace('[recepient]', $vrdhname, $tMess[0]['MESSAGE']);
							$tMess[0]['MESSAGE'] = str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $tMess[0]['MESSAGE']);
							
							if(empty( $vrds[$c]['VRDHEAD_ID'])){
								continue;
							}
							$insert_array = array(
							'SUBJECT' => $tMess[0]['SUBJECT'],
							'TOPIC' => $tMess[0]['TOPIC'],
							'DATE_SENT' => date('Y-m-d H:i:s'),
							'BODY' => $tMess[0]['MESSAGE'],
							'TYPE' => 'notification',
							'SENDER_ID' => $vendor_details[0]['CREATED_BY'],
							'RECIPIENT_ID' => $vrds[$c]['VRDHEAD_ID'], //can be changed in query
							'VENDOR_ID' => $vendor_details[0]['USER_ID']
							);			

							 $model_data = $this->mail_model->send_message($insert_array);

						}
					}


					// --> end marc		

			
					//to creator email
					$creator_name = $this->check_status_model->select_query('SMNTP_USERS',array('USER_ID' => $vendor_details[0]['CREATED_BY']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
					
					$cname = $creator_name[0]['USER_FIRST_NAME'] .  ' ' . $creator_name[0]['USER_MIDDLE_NAME'];
					$cname = trim($cname) . ' ' . $creator_name[0]['USER_LAST_NAME'] ;
					$cname = trim($cname);
					
					$temail = $vemail;
					$temail = str_replace('[head]', $cname , $temail[0]['CONTENT']);
					$temail = str_replace('[vendor_name]',  $vendor_details[0]['VENDOR_NAME'],$temail );

					$send_data2 = array(
						'subject' => $vendor_details[0]['VENDOR_NAME'] . ' - User Account Deactivated',
						'content' =>  nl2br($temail) ,
						'cc' => '',
						'to' =>  $creator_name[0]['USER_EMAIL']
					);
					$this->common_model->send_email_notification($send_data2);
					
					//to creator portal
					$tMess = $vMess;	
					$tMess[0]['SUBJECT'] = str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $tMess[0]['SUBJECT']);
					$tMess[0]['TOPIC'] = str_replace('[vendorname]',$vendor_details[0]['VENDOR_NAME'], $tMess[0]['TOPIC']);
					$tMess[0]['MESSAGE'] = str_replace('[recepient]', $cname, $tMess[0]['MESSAGE']);
					$tMess[0]['MESSAGE'] = str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $tMess[0]['MESSAGE']);

					/*if(empty($vrds[$c]['VRDSTAFF_ID'])){
						continue;
					}*/
					$insert_array = array(
					'SUBJECT' => $tMess[0]['SUBJECT'],
					'TOPIC' => $tMess[0]['TOPIC'],
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $tMess[0]['MESSAGE'],
					'TYPE' => 'notification',
					'SENDER_ID' => 0,
					'RECIPIENT_ID' => $vendor_details[0]['CREATED_BY'], //can be changed in query
					'VENDOR_ID' => $vendor_details[0]['USER_ID']
					);			

					$model_data = $this->mail_model->send_message($insert_array);
					
					
					$where_arr = ['VENDOR_INVITE_ID' => $vendor_invite_id];
					$email_vndor	= $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'EMAIL', $where_arr);
					$email_arr 		= ['TEMPLATE_TYPE' 	=> 19, //12 for expired email for Vendor
										'ACTIVE'	=> 1];
					$rs_email		= $this->common_model->get_email_template($email_arr);
					$message 		= $rs_email->row()->CONTENT;

					$message = str_replace('[vendor_name]', $vendor_name, $message);

					$email_data['to'] 		= $email_vndor;
					//  $email_data['bcc']  	= '';
					$email_data['subject'] 	= 'Deactivated Due to Non-submission of Additional';
					$email_data['content'] 	= nl2br($message);
					$this->common_model->send_email_notification($email_data);


					// $this->response($message);


					// return;
				}


				
			}

			
		

		}

		$this->response($days);
		
	}	

	function trim_name($name)
	{
		

	if(!isset($name[0]['USER_FIRST_NAME'])){
		$name[0]['USER_FIRST_NAME'] = "";

	}
	if(!isset($name[0]['USER_MIDDLE_NAME'])){
		$name[0]['USER_MIDDLE_NAME'] = "";
	}
	if(!isset($name[0]['USER_LAST_NAME'])){
		$name[0]['USER_LAST_NAME'] = "";
	}
		$rname = trim($name[0]['USER_FIRST_NAME'] ." ". $name[0]['USER_MIDDLE_NAME'] ." ". $name[0]['USER_LAST_NAME']);
	
		return $rname;

		

	}
	
}



