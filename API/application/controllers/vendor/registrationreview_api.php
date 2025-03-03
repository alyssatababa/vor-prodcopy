<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('user_agent', 'Mozilla/5.0');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class Registrationreview_api extends REST_Controller
{
	
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/registration_model');
		$this->load->model('common_model');
		$this->load->model('mail_model');
	}

	public function registration_review_put() // with validation
	{
		$var['vendor_id'] 		= $this->put('vendor_id');
		$var['user_id'] 		= $this->put('user_id');
		$var['position_id'] 	= $this->put('position_id');
		$var['status']	 		= $this->put('status'); // 3 request visit/approve , 4 incomplete/reject
		$real_status_id 		= $this->put('status_id');
		$registration_type 		= $this->put('registration_type');


		$recipient_id 	= '';
		$subject	 	= '';
		$topic	 		= '';
		$message	 	= '';
		$invite_id	 	= '';
		
		if(($var['status'] == 3) && ($this->put('rv_checkbox') == 'on') && ($real_status_id == 194 || 198)){ // If N/A is Checked
			// get vendor invite id 
			$where_arr = array('VENDOR_ID' => $var['vendor_id']);
			$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr);	
			
			
			$data['next_position'] = 10;			
			$data['next_status'] = 19;

			$rs = $this->update_review($var); //update reviewed
			
			$rsd = $this->registration_model->checked_na_rsd($var['vendor_id']);
			$ra = $this->registration_model->checked_na_ra($var['vendor_id']);
			if($registration_type == 5){
				$ccn = $this->registration_model->checked_na_ccn($var['vendor_id']);
			}
			
			// Insert to LOGS
			$data2['status'] 					= 19; 
			$data2['nxt_position_id'] 			= 10;
			$data2['invite_id'] 				= $invite_id;
			$data2['user_id'] 					= $var['user_id'];
			$data2['reject_remarks']			= '';
			
			$rs2 = $this->registration_model->update_review_status($data2);
			
			// Update Status
			$record_arr = array(
							'STATUS_ID'			=> 19,
							'DATE_UPDATED'		=> date('Y-m-d H:i:s'),
							'POSITION_ID'		=> 10,
							'APPROVER_ID'		=> $var['user_id']
						);
			
			$where_arr = array(
							'VENDOR_INVITE_ID' 	=> $invite_id
						);
			// update status and position id
			$rs3 = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);
		}else{

			$rs = $this->update_review($var); //update reviewed
			
			// if save as draft just save update review^
			if ($var['status'] != 1) 
			{
				// update status 
				if ($var['status'] == 2) // submit
					$status_id = 12; // validation
				else
				{
					if ($real_status_id == 194 || 198){
						$status_id = $real_status_id; // additional review
					}
					else{
						$status_id = 10; // review			
					}
				}

				$next_arr = array(
								'status' 		=> $status_id,
								'position_id' 	=> $var['position_id'],
								'type' 			=> 1, // registration
								'action'		=> $var['status'] // 3 request visit/approve , 4 incomplete/reject
								
							);

				if ($status_id == 10) // kuhain ung vendor type para malaman kanino ipapasa ao 171005-- //------------------------------------ if ($var['status'] == 2) // if "validation submit" query for busniess type para malaman kanino ipapasa (old spec)
					{

					// get vendor_type
					$where_arr = array('VENDOR_ID' => $var['vendor_id']);
					$vendor_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_TYPE', $where_arr); // 1/trade or 2/non trade
					
					if($vendor_type == 4){
						$vendor_type = 3;
					}
					
					$next_arr['business_type'] = $vendor_type;
				}
				
				// Jay Waive
				// 4 = VRD STAFF
				if($var['position_id'] == 4){
					//If VRD STAFF add waive remarks
					$submitted_data = $this->put();
					$rsd_search = 'waive_rsd_document_chk';
					$ad_search = 'waive_ad_document_chk';
					$rsd_waive_checks = array();
					$ad_waive_checks = array();
					
					
					$waive_params['vendor_invite_id'] = $this->registration_model->get_vendor_invite_id($var['vendor_id']);
					
					//Primary Requirements
					//Filter waive rsd_document_chk
					foreach($submitted_data as $key => $value){
						if(strpos( $key, $rsd_search ) !== false){
							$rsd_waive_checks[$key] = $value;
						}
						if(strpos( $key, $ad_search ) !== false){
							$ad_waive_checks[$key] = $value;
						}
					}
					
					if( ! empty($rsd_waive_checks) || ! empty($ad_waive_checks)){
						if( ! empty($waive_params['vendor_invite_id'] )){
							$waive_params['vendor_invite_id'] = $waive_params['vendor_invite_id'][0]['VENDOR_INVITE_ID'];
								
							$waive_params['rsd_waive'] = $rsd_waive_checks;
							$waive_params['ad_waive'] = $ad_waive_checks;
							$waive_params['user_id'] = $var['user_id'];
							$waive_params['rsd_waive_remarks'] = $this->put('rsd_waive_remarks');
							$waive_params['ad_waive_remarks'] = $this->put('ad_waive_remarks');
						
							$waive_result = $this->registration_model->set_waive($waive_params);
							
						}
					}
					
				}
				// End

				$data = $this->common_model->get_next_process($next_arr);
			
				
				// get vendor invite id 
				$where_arr = array('VENDOR_ID' => $var['vendor_id']);
				$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr);			

				$record_arr = array(
								'STATUS_ID'			=> $data['next_status'],
								'DATE_UPDATED'		=> date('Y-m-d H:i:s'),
								'POSITION_ID'		=> $data['next_position']							
							);
				//inc doc
				$incomplete_doc = '';
				$tbl_inc_reason_logs = '';
				if ($var['status'] == 3 || $var['status'] == 4)
				{

					$date_from 	= $this->put('rv_txt_from');
					$date_to 	= $this->put('rv_txt_to');
					$reason 	= $this->put('rv_incomplete');

					if (!empty($date_from))
					$date_from 	= date('Y-m-d', strtotime($date_from)); 
					if (!empty($date_to))
					$date_to 	= date('d-M-Y', strtotime($date_to)); 

					$record_arr['RV_FROM'] 			= $date_from;
					$record_arr['RV_TO'] 			= $date_to;
					$record_arr['APPROVER_ID']		= $var['user_id'];
					$record_arr['APPROVER_REMARKS']	= $reason;

					if ($var['status'] == 4) // if incomplete reason
					{

						$ir_count 	= $this->put('increason_count');

						$ir_arr = ['VENDOR_ID' =>  $var['vendor_id'], 'INVITE_ID' => $invite_id ];
						// count of existing incomplete reason
						$curr_ir = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INCOMPLETE_REASON', 'COUNT(*) AS COUNT', $ir_arr, 'COUNT');

						if ($curr_ir > 0) // delete if exist before insert new values
							$this->registration_model->delete_table('SMNTP_VENDOR_INCOMPLETE_REASON', $ir_arr);
						
						$ir_save = array();
						$incomplete_html = '';
						for ($i=1; $i <= $ir_count; $i++)
						{ 
							$reason_id 	= $this->put('cbo_inc_reason'.$i);
							$cbo_da 	= $this->put('cbo_da'.$i);
							$da 		= explode('|', $cbo_da);

							$da_id 			= $da[0]; // DOCUMENT_ID
							$document_type 	= $da[1]; // DOCUMENT_TYPE

							$ir_save = [
											'VENDOR_ID' 	=> $var['vendor_id'],
											'INVITE_ID' 	=> $invite_id,
											'REASON_ID' 	=> $reason_id,
											'DOCUMENT_ID' 	=> $da_id,
											'DOCUMENT_TYPE' => $document_type
										];

							$this->common_model->insert_table('SMNTP_VENDOR_INCOMPLETE_REASON', $ir_save);
							
							//build html for email 
							if ($document_type == 1)
								$doc_name 	= $this->common_model->get_from_table_where_array('SMNTP_VP_REQUIRED_DOCUMENTS', 'REQUIRED_DOCUMENT_NAME', ['REQUIRED_DOCUMENT_ID' => $da_id]);
							elseif ($document_type == 2)
								$doc_name 	= $this->common_model->get_from_table_where_array('SMNTP_VP_REQUIRED_AGREEMENTS', 'REQUIRED_AGREEMENT_NAME', ['REQUIRED_AGREEMENT_ID' => $da_id]);	

							$reason_desc 	= $this->common_model->get_from_table_where_array('SMNTP_INCOMPLETE_DOC_REASONS', 'INCOMPLETE_REASON', ['REASON_ID' => $reason_id]);

							if (isset($reason) || $reason == ''){
								if ($doc_name != 'Others'){
									$incomplete_html .= '<tr><td>'.$doc_name.'</td><td>'.$reason_desc.'</td></tr>';
								}
							}
							if ($doc_name != 'Others'){
								$incomplete_doc .= 'Form/Document: ' . $doc_name . '<br/>' . 'Reason: ' . $reason_desc . '<br/><br/>';
							}
						}
						
						//Jay VENDOR STATUS LOGS 
						$tbl_inc_reason_logs  = '<table class="table table-bordered" >';//jay width="100%" align="right"
						if( ! empty($incomplete_html)){
							$tbl_inc_reason_logs .= '<tr>';
							$tbl_inc_reason_logs .= '<th class="inc_th" >Form/Document</th>';
							$tbl_inc_reason_logs .= '<th class="inc_th" >Reason</th>';
							$tbl_inc_reason_logs .= '</tr>';
							$tbl_inc_reason_logs .= $incomplete_html;
							$tbl_inc_reason_logs .= '<tr>';
						}
						$tbl_inc_reason_logs .= '<th colspan="2" class="inc_th" >Others</th>';
						$tbl_inc_reason_logs .= '</tr>';
						$tbl_inc_reason_logs .= '<tr>';
						$tbl_inc_reason_logs .= '<td colspan="2">'.$reason.'</td>';
						$tbl_inc_reason_logs .= '</tr>';
						$tbl_inc_reason_logs .= '</table>';
						//end
					}
				}

				$where_arr = array(
								'VENDOR_INVITE_ID' 	=> $invite_id
							);

				// update status and position id
				$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);
					
				//Jay VENDOR STATUS LOGS 
				//If for validation of additional requirements
				if($data['next_status'] == 12){
					$date_from 	= $this->put('rv_txt_from');
					$date_to 	= $this->put('rv_txt_to');
					$newDateFrom = date("F d,Y", strtotime($date_from));	
					$newDateTo = date("F d,Y", strtotime($date_to));	
					
					if($this->put('rv_checkbox') != 'on'){
						$tbl_inc_reason_logs = 'Request Visit Schedule: ' . $newDateFrom  . ' - ' . $newDateTo;	
					}else{
						$tbl_inc_reason_logs = 'Request Visit Schedule: Not Applicable';	
					}
				}
				$data2['status'] 					= $data['next_status']; 
				$data2['nxt_position_id'] 			= $data['next_position'];
				$data2['invite_id'] 				= $invite_id;
				$data2['user_id'] 					= $var['user_id'];
				$data2['reject_remarks']			= $tbl_inc_reason_logs;
				
				$rs2 = $this->registration_model->update_review_status($data2);
				//jay end
				
				if($var['status'] == 4 || $var['status'] == 3)
				{
					$tbl_inc_reason = '';
					if($var['status'] == 4)
					{
						$where_arr1 = array('TEMPLATE_TYPE' => 4);
						$rs = $this->common_model->get_email_template($where_arr1);
						$email_data['subject'] = 'Vendor Registration - Incomplete';

						// table for incomplete reason
						$tbl_inc_reason .= '<table border="1">'; //  width="100%" align="right"
						//jay
						if( ! empty($incomplete_html)){
							$tbl_inc_reason .= '<tr>';
							$tbl_inc_reason .= '<th class="inc_th" >Form/Document</th>';
							$tbl_inc_reason .= '<th class="inc_th" >Reason</th>';
							$tbl_inc_reason .= '</tr>';
							$tbl_inc_reason .= $incomplete_html;
							$tbl_inc_reason .= '<tr>';
						}
						$tbl_inc_reason .= '<th colspan="2" class="inc_th">Others</th>';
						$tbl_inc_reason .= '</tr>';
						$tbl_inc_reason .= '<tr>';
						$tbl_inc_reason .= '<td colspan="2">'.$reason.'</td>';
						$tbl_inc_reason .= '</tr>';
						$tbl_inc_reason .= '</table>';
					}elseif($var['status'] == 3){
						if($this->put('rv_checkbox') != 'on'){
							$where_arr1 = array('TEMPLATE_TYPE' => 5);
						}else{
							$where_arr1 = array('TEMPLATE_TYPE' => 71);
						}
						
						$rs = $this->common_model->get_email_template($where_arr1);
						$email_data['subject'] = 'Vendor Registration - Request For Visit';
					}

					$where_arr2 = array('VENDOR_INVITE_ID' => $invite_id);
					$email_to = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'EMAIL', $where_arr2);		

					$where_arr3 = array('VENDOR_INVITE_ID' => $invite_id);
					$email_vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr3);		
					$vendor_user_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', $where_arr2);		
					
					$data['vendor_user_id'] = $vendor_user_id;
					$data['email_to'] = $email_to;
					$data['email_vendorname'] = $email_vendorname;
					$data['remarks'] = $tbl_inc_reason_logs;// . 'Others:<br/>' . $reason;
					$email_data['content'] = $rs->row()->CONTENT;

					$tdate = date_Create($date_from);
					$date_from = date_format($tdate,'F d,Y');
					$tdate = date_Create($date_to);
					$date_to = date_format($tdate,'F d,Y');

					if($var['status'] == 3)
					{
						$email_data['content'] = str_replace('[data_from]', $date_from, $email_data['content']); // (what tofind, value change, whole sentence) 
						$email_data['content'] = str_replace('[date_to]', $date_to, $email_data['content']); // (what tofind, value change, whole sentence) 
					}

					$email_data['content'] = str_replace('[remarks]', $tbl_inc_reason, $email_data['content']); // (what tofind, value change, whole sentence) 
					$email_data['content'] = str_replace('[repname]', $email_vendorname, $email_data['content']); // (what tofind, value change, whole sentence) 
					$email_data['content'] = nl2br($email_data['content']);
					//$email_data['bcc'] = '';
					$email_data['to'] = $email_to;

					$this->common_model->send_email_notification($email_data);

					if($var['status'] == 3){
						//select portal
						$pm = $this->common_model->select_query('SMNTP_MESSAGE_DEFAULT',array('STATUS_ID' => 200),'*');
						$pm[0]['SUBJECT'] = str_replace('[vendorname]',$email_vendorname,$pm[0]['SUBJECT']);
						$pm[0]['TOPIC'] = str_replace('[vendorname]',$email_vendorname,$pm[0]['TOPIC']);
						$pm[0]['MESSAGE'] = str_replace('[repname]',$email_vendorname,$pm[0]['MESSAGE']);
						$pm[0]['MESSAGE'] = str_replace('[from]',$date_from,$pm[0]['MESSAGE']);
						$pm[0]['MESSAGE'] = str_replace('[to]',$date_to,$pm[0]['MESSAGE']);

							$insert_array = array(
							'SUBJECT' => $pm[0]['SUBJECT'],
							'TOPIC' => $pm[0]['TOPIC'],
							'DATE_SENT' => date('Y-m-d H:i:s'),
							'BODY' => $pm[0]['MESSAGE'],
							'TYPE' => 'notification',
							'SENDER_ID' => 0,//notif
							'RECIPIENT_ID' => $vendor_user_id, //can be changed in query
							'VENDOR_ID' => $var['vendor_id']
							);
							
						$model_data = $this->mail_model->send_message($insert_array);
					}
				}
			}
		}

		if ($rs){
			$data['status'] = TRUE;
			$data['error'] = '';
			$data['vendor_id'] 		= $var['vendor_id'];
			$data['invite_id'] 		= $invite_id;
			$data['recipient_id'] 	= $recipient_id;
			$data['subject'] 		= $subject;
			$data['topic'] 			= $topic;
			$data['message'] 		= $message;
		}else{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
		}

		$this->response($data);
	}

	public function submit_review_put()
	{
		$var['registration_type'] = $this->put('registration_type');		
		$var['vendor_id'] 		= $this->put('vendor_id');
		$var['user_id'] 		= $this->put('user_id');
		$var['position_id'] 	= $this->put('position_id');
		$var['reg_type_id']		= $this->put('registration_type');
		$var['status']	 		= '';
		//$cbo_tp 				= $this->put('cbo_tp');


		$recipient_id 	= '';
		$subject	 	= '';
		$topic	 		= '';
		$message	 	= '';
		$invite_id	 	= '';

		$rs = $this->update_review($var); //update reviewed
		
		$next_arr = array(
							'status' 		=> 10,
							'position_id' 	=> $var['position_id'],
							'type' 			=> 1, // registration
							'action'		=> 3 // submitted / parang approve kasi ang reject incomplete
							
						);
		// get vendor_type
		$where_arr = array('VENDOR_ID' => $var['vendor_id']);
		$vendor_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_TYPE', $where_arr); // 1/trade or 2/non trade
		
		if($vendor_type == 4){
			$vendor_type = 3; // NTS
		}
		$next_arr['business_type'] = $vendor_type;
		
		if($var['reg_type_id'] != 1){
			$next_arr['reg_type_id'] = $var['reg_type_id'];
		}else{
			$next_arr['reg_type_id'] = 1;
		}

		$data = $this->common_model->get_next_process($next_arr);

		// get vendor invite id 
		$where_arr = array('VENDOR_ID' => $var['vendor_id']);
		$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr);

		/*
		$record_arr = array(
							'STATUS_ID'			=> $data['next_status'],
							'DATE_UPDATED'		=> date('d-M-Y'),
							'POSITION_ID'		=> $data['next_position'],
							'APPROVER_ID'		=> $var['user_id'],
							'TERMSPAYMENT'		=> $cbo_tp
						);
		*/
		
		$record_arr = array(
							'STATUS_ID'			=> $data['next_status'],
							'DATE_UPDATED'		=> date('Y-m-d H:i:s'),
							'POSITION_ID'		=> $data['next_position'],
							'APPROVER_ID'		=> $var['user_id']
						);

		$where_arr = array(
							'VENDOR_INVITE_ID' 	=> $invite_id
						);
		// update status and position id
		$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);

		//Jay VENDOR STATUS LOGS 
		$data2['status'] 					= $data['next_status']; 
		$data2['nxt_position_id'] 			= $data['next_position'];
		$data2['invite_id'] 				= $invite_id;
		$data2['user_id'] 					= $var['user_id'];
		$data2['reject_remarks']			= '';
		$rs2 = $this->registration_model->update_review_status($data2);
		//end
		
		$creator_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', ['VENDOR_INVITE_ID' => $invite_id]);

		// send message to approver
		// get recipient id here for message
		if(($var['registration_type'] == 2) || ($var['registration_type'] == 3)){
			$aprv_id = 'VRDHEAD_ID';
		}else{
			if ($next_arr['business_type'] == 1 || $next_arr['business_type'] == 3)
				$aprv_id = 'BUHEAD_ID';
			else
				$aprv_id = 'FASHEAD_ID';
		}
		// get recipient id here for message
		$where_arr = array(
							'USER_ID' => $creator_id,
							$aprv_id. ' IS NOT NULL' => NULL
						);
		$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', $aprv_id, $where_arr);

		$where_arr_def = array(
						'TYPE_ID' 		=> 1, // for registration
						'STATUS_ID' 	=> $data['next_status']
					);

		$rs_msg = $this->common_model->get_message_default($where_arr_def);

		if ($rs_msg->num_rows() > 0)
		{
			$row = $rs_msg->row();

			$where_arr = array(
						'USER_ID' => $recipient_id
					);
			//$approvername = $this->common_model->get_from_table_where_array('SMNTP_USERS', "CONCAT ((USER_FIRST_NAME), (' '), (USER_MIDDLE_NAME), (' ') , (USER_LAST_NAME)) AS FULL_NAME", $where_arr, 'FULL_NAME');
			$approvername = $this->common_model->get_from_table_where_array('SMNTP_USERS', "CASE WHEN USER_LAST_NAME IS NULL THEN USER_FIRST_NAME ELSE CONCAT((USER_FIRST_NAME), (' ') , (USER_MIDDLE_NAME), (' '), (USER_LAST_NAME)) END AS FULL_NAME", $where_arr, 'FULL_NAME');

			$where_arr = array(
						'USER_ID' => $var['user_id']
					);
			//$sendername = $this->common_model->get_from_table_where_array('SMNTP_USERS', "CONCAT ((USER_FIRST_NAME), (' '), (USER_MIDDLE_NAME), (' ') , (USER_LAST_NAME)) AS FULL_NAME", $where_arr, 'FULL_NAME');
			$sendername = $this->common_model->get_from_table_where_array('SMNTP_USERS', "CASE WHEN USER_LAST_NAME IS NULL THEN USER_FIRST_NAME ELSE CONCAT((USER_FIRST_NAME), (' ') , (USER_MIDDLE_NAME), (' '), (USER_LAST_NAME)) END AS FULL_NAME", $where_arr, 'FULL_NAME');

			$where_arr3 = array('VENDOR_INVITE_ID' => $invite_id);
			$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr3);

			$subject	= str_replace('[vendorname]', $vendorname, $row->SUBJECT);
			$topic		= str_replace('[vendorname]', $vendorname, $row->TOPIC);
			$message	= $row->MESSAGE;
			$data['vendorname'] = $vendorname;
			$message = str_replace('[approvername]', $approvername,$message);
			$message = str_replace('[sendername]', $sendername,$message);
			$message = str_replace('[vendorname]', $vendorname,$message);
			$data['sender_email'] = $this->registration_model->get_sender_email($recipient_id);
		}

		// Jay Waive
		// 4 = VRD STAFF
		if($var['position_id'] == 4){
			//If VRD STAFF add waive remarks
			$submitted_data = $this->put();
			$rsd_search = 'waive_rsd_document_chk';
			$ad_search = 'waive_ad_document_chk';
			$rsd_waive_checks = array();
			$ad_waive_checks = array();
			
			
			$waive_params['vendor_invite_id'] = $this->registration_model->get_vendor_invite_id($var['vendor_id']);
			
			//Primary Requirements
			//Filter waive rsd_document_chk
			foreach($submitted_data as $key => $value){
				if(strpos( $key, $rsd_search ) !== false){
					$rsd_waive_checks[$key] = $value;
				}
				if(strpos( $key, $ad_search ) !== false){
					$ad_waive_checks[$key] = $value;
				}
			}
			
			if( ! empty($rsd_waive_checks) || ! empty($ad_waive_checks)){
				if( ! empty($waive_params['vendor_invite_id'] )){
					$waive_params['vendor_invite_id'] = $waive_params['vendor_invite_id'][0]['VENDOR_INVITE_ID'];
						
					$waive_params['rsd_waive'] = $rsd_waive_checks;
					$waive_params['ad_waive'] = $ad_waive_checks;
					$waive_params['user_id'] = $var['user_id'];
					$waive_params['rsd_waive_remarks'] = $this->put('rsd_waive_remarks');
					$waive_params['ad_waive_remarks'] = $this->put('ad_waive_remarks');
				
					$waive_result = $this->registration_model->set_waive($waive_params);
					
				}
			}
			
		}
		// End
		
		if ($rs)
		{
			$data['status'] 		= TRUE;
			$data['error'] 			= '';
			$data['vendor_id'] 		= $var['vendor_id'];
			$data['invite_id'] 		= $invite_id;
			$data['recipient_id'] 	= $recipient_id;
			$data['subject'] 		= $subject;
			$data['topic'] 			= $topic;
			$data['message'] 		= $message;
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
		}

		$this->response($data);
	}

	public function update_review($var)
	{
		$var['audit_logs'] 	= $this->put('audit_logs');
		$audit_logs = explode(",", $var['audit_logs']);
		$audit_logs = array_unique($audit_logs);
		
		$docs_var['ownership'] 			= $this->put('ownership');
		$docs_var['trade_vendor_type'] 	= $this->put('trade_vendor_type');
		$docs_var['vendor_type'] 		= $this->put('vendor_type');
		$docs_var['registration_type']	= $this->put('registration_type');
		$var = 	array_merge($var, $this->put());
		//$var .= $this->put();
		$category_sup_count = $this->put('cat_sup_count');				
		
		if($var['status_id'] == 10){
			$test = "";
			foreach($audit_logs as $logs){
				switch($logs){
					case "brand":
						$count_to_insert = $this->put('brand_count');
					
						$get_existing_brand = $this->registration_model->al_brand($var['vendor_id']);
						$row_count = $get_existing_brand['num_row'];
							
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									if($get_existing_brand['result'][$b]['BRAND_NAME'] == $this->put('brand_name'.($a+1))){
										$handler = ($b+1);
										break;
									}
								}
								
								if(($a+1) <= $row_count){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], $this->put('brand_name'.($a+1)), 'Brand');
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $this->put('brand_name'.($a+1)), 'Brand');
								}
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									if($get_existing_brand['result'][$a]['BRAND_NAME'] == $this->put('brand_name'.($b+1))){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], $this->put('brand_name'.($a+1)), 'Brand');
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], '', 'Brand');
								}
							}
						}
						
						break;
					case "employee":
						$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
						if($get_existing['result'][0]['EMPLOYEE'] != $this->put('no_of_employee')){
							$db_record = '';
							$post_record = '';
							switch($get_existing['result'][0]['EMPLOYEE']){
								case 0:
									$db_record = 'MICRO (1 - 9)';
									break;
								case 1:
									$db_record = 'SMALL (10 - 99)';
									break;
								case 2:
									$db_record = 'MEDIUM (100 - 199)';
									break;
								case 3:
									$db_record = 'LARGE (200 and above)';
									break;
							}
							
							switch($this->put('no_of_employee')){
								case 0:
									$post_record = 'MICRO (1 - 9)';
									break;
								case 1:
									$post_record = 'SMALL (10 - 99)';
									break;
								case 2:
									$post_record = 'MEDIUM (100 - 199)';
									break;
								case 3:
									$post_record = 'LARGE (200 and above)';
									break;
							}
							
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $db_record, $post_record, 'No. of Employee');
						}
						break;
					case "business_asset":
						$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
						if($get_existing['result'][0]['BUSINESS_ASSET'] != $this->put('business_asset')){
							$db_record = '';
							$post_record = '';
							switch($get_existing['result'][0]['BUSINESS_ASSET']){
								case 0:
									$db_record = 'MICRO (Up to P3,000,000)';
									break;
								case 1:
									$db_record = 'SMALL (P3,000,001 - P15,000,000)';
									break;
								case 2:
									$db_record = 'MEDIUM (P15,000,001 - P100,000,000)';
									break;
								case 3:
									$db_record = 'LARGE (P100,000,001 and above)';
									break;
							}
							
							switch($this->put('business_asset')){
								case 0:
									$post_record = 'MICRO (Up to P3,000,000)';
									break;
								case 1:
									$post_record = 'SMALL (P3,000,001 - P15,000,000)';
									break;
								case 2:
									$post_record = 'MEDIUM (P15,000,001 - P100,000,000)';
									break;
								case 3:
									$post_record = 'LARGE (P100,000,001 and above)';
									break;
							}
							
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $db_record, $post_record, 'MSME Business Asset Classification');
						}
						break;
					case "business_years":
						$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
						if($get_existing['result'][0]['YEAR_IN_BUSINESS'] != $this->put('cbo_yr_business')){
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['YEAR_IN_BUSINESS'], $this->put('cbo_yr_business'), 'Years in Business');
						}
						break;
					case "tax":
						$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
						
						if($get_existing['result'][0]['TAX_ID_NO'] != $this->put('tax_idno')){
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['TAX_ID_NO'], $this->put('tax_idno'), 'Tax Identification No');
							
						}
						
						if($get_existing['result'][0]['TAX_CLASSIFICATION'] != $this->put('tax_class')){
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['TAX_CLASSIFICATION'], $this->put('tax_class'), 'Tax Classification');
							
						}
						break;
					case "offadd":
						$count_to_insert = $this->put('office_addr_count');
						$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'1');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$insert_details = $this->put('office_add'.($a+1)) . $this->put('office_brgy_cm'.($a+1)) . $this->put('office_state_prov'.($a+1)) . $this->put('office_zip_code'.($a+1)) . $this->put('office_region'.($a+1)) . $this->put('office_country'.($a+1));
								$to_logs = $this->put('office_add'.($a+1)) . " " . $this->put('office_brgy_cm'.($a+1)) . " " . $this->put('office_state_prov'.($a+1)) . " " . $this->put('office_region'.($a+1)) . " " . $this->put('office_zip_code'.($a+1)) . " " . $this->put('office_country'.($a+1));
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
									if($db_details == $insert_details){
										$handler = ($b+1);
										break;
									}
								}
								if(($a+1) <= $row_count){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Office Address');
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Office Address');
								}
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
								$to_logs = $get_existing['result'][$b]['ADDRESS_LINE'] . " " . $get_existing['result'][$b]['CITY_NAME'] . " " . $get_existing['result'][$b]['STATE_PROV_NAME'] . " " . $get_existing['result'][$b]['REGION_DESC_TWO'] . " " . $get_existing['result'][$b]['ZIP_CODE'] . " " . $get_existing['result'][$b]['COUNTRY_NAME'];
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									$insert_details = $this->put('office_add'.($b+1)) . $this->put('office_brgy_cm'.($b+1)) . $this->put('office_state_prov'.($b+1)) . $this->put('office_zip_code'.($b+1)) . $this->put('office_region'.($b+1)) . $this->put('office_country'.($b+1));
									if($db_details == $insert_details){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->put('office_add'.($a+1)) . " " . $this->put('office_brgy_cm'.($a+1)) . " " . $this->put('office_state_prov'.($a+1)) . " " . $this->put('office_region'.($a+1)) . " " . $this->put('office_zip_code'.($a+1)) . " " . $this->put('office_country'.($a+1)), 'Office Address');
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Office Address');
								}
								
							}
						}
						
						break;
					case "whadd":
						$count_to_insert = $this->put('wh_addr_count');
						$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'2');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$insert_details = $this->put('ware_addr'.($a+1)) . $this->put('ware_brgy_cm'.($a+1)) . $this->put('ware_state_prov'.($a+1)) . $this->put('ware_zip_code'.($a+1)) . $this->put('ware_region'.($a+1)) . $this->put('ware_country'.($a+1));
								$to_logs = $this->put('ware_addr'.($a+1)) . " " . $this->put('ware_brgy_cm'.($a+1)) . " " . $this->put('ware_state_prov'.($a+1)) . " " . $this->put('ware_region'.($a+1)) . " " . $this->put('ware_zip_code'.($a+1)) . " " . $this->put('ware_country'.($a+1));
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
									if($db_details == $insert_details){
										$handler = ($b+1);
										break;
									}
								}
								if(($a+1) <= $row_count){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Warehouse Address');
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Warehouse Address');
								}
								
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
								$to_logs = $get_existing['result'][$b]['ADDRESS_LINE'] . " " . $get_existing['result'][$b]['CITY_NAME'] . " " . $get_existing['result'][$b]['STATE_PROV_NAME'] . " " . $get_existing['result'][$b]['REGION_DESC_TWO'] . " " . $get_existing['result'][$b]['ZIP_CODE'] . " " . $get_existing['result'][$b]['COUNTRY_NAME'];
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									$insert_details = $this->put('ware_addr'.($b+1)) . $this->put('ware_brgy_cm'.($b+1)) . $this->put('ware_state_prov'.($b+1)) . $this->put('ware_zip_code'.($b+1)) . $this->put('ware_region'.($b+1)) . $this->put('ware_country'.($b+1));
									if($db_details == $insert_details){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->put('ware_addr'.($a+1)) . " " . $this->put('ware_brgy_cm'.($a+1)) . " " . $this->put('ware_state_prov'.($a+1)) . " " . $this->put('ware_region'.($a+1)) . " " . $this->put('ware_zip_code'.($a+1)) . " " . $this->put('ware_country'.($a+1)), 'Warehouse Address');
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Warehouse Address');
								}
								
								
							}
						}
						break;
					case "facadd":
						$count_to_insert = $this->put('factory_addr_count');
						$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'3');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$insert_details = $this->put('factory_addr'.($a+1)) . $this->put('factory_brgy_cm'.($a+1)) . $this->put('factory_state_prov'.($a+1)) . $this->put('factory_zip_code'.($a+1)) . $this->put('factory_region'.($a+1)) . $this->put('factory_country'.($a+1));
								$to_logs = $this->put('factory_addr'.($a+1)) . " " . $this->put('factory_brgy_cm'.($a+1)) . " " . $this->put('factory_state_prov'.($a+1)) . " " . $this->put('factory_region'.($a+1)) . " " . $this->put('factory_zip_code'.($a+1)) . " " . $this->put('factory_country'.($a+1));
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
									if($db_details == $insert_details){
										$handler = ($b+1);
										break;
									}
								}
								if(($a+1) <= $row_count){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Factory Address');
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Factory Address');
								}
								
								
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
								$to_logs = $get_existing['result'][$b]['ADDRESS_LINE'] . " " . $get_existing['result'][$b]['CITY_NAME'] . " " . $get_existing['result'][$b]['STATE_PROV_NAME'] . " " . $get_existing['result'][$b]['REGION_DESC_TWO'] . " " . $get_existing['result'][$b]['ZIP_CODE'] . " " . $get_existing['result'][$b]['COUNTRY_NAME'];
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									$insert_details = $this->put('factory_addr'.($b+1)) . $this->put('factory_brgy_cm'.($b+1)) . $this->put('factory_state_prov'.($b+1)) . $this->put('factory_zip_code'.($b+1)) . $this->put('factory_region'.($b+1)) . $this->put('factory_country'.($b+1));
									if($db_details == $insert_details){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->put('factory_addr'.($a+1)) . " " . $this->put('factory_brgy_cm'.($a+1)) . " " . $this->put('factory_state_prov'.($a+1)) . " " . $this->put('factory_region'.($a+1)) . " " . $this->put('factory_zip_code'.($a+1)) . " " . $this->put('factory_country'.($a+1)), 'Factory Address');
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Factory Address');
								}
								
								
							}
						}
						
						break;
					case "telno":
						$count_to_insert = $this->put('telno_count');
						
						$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'1');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$insert_details = $this->put('tel_ccode'.($a+1)) . $this->put('tel_acode'.($a+1)) . $this->put('tel_no'.($a+1)) . $this->put('tel_elno'.($a+1));
								$to_logs = $this->put('tel_ccode'.($a+1)) . " " . $this->put('tel_acode'.($a+1)) . " " . $this->put('tel_no'.($a+1)) . " " . $this->put('tel_elno'.($a+1));
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['AREA_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'] . $get_existing['result'][$b]['EXTENSION_LOCAL_NUMBER'];
									if($db_details == $insert_details){
										$handler = ($b+1);
										break;
									}
								}
								if(($a+1) <= $row_count){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'], $to_logs, 'Tel No.');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Tel No.');
								}
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['AREA_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'] . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
								$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									$insert_details = $this->put('tel_ccode'.($b+1)) . $this->put('tel_acode'.($b+1)) . $this->put('tel_no'.($b+1)) . $this->put('tel_elno'.($b+1));
									if($db_details == $insert_details){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->put('tel_ccode'.($a+1)) . " " . $this->put('tel_acode'.($a+1)) . " " . $this->put('tel_no'.($a+1)) . " " . $this->put('tel_elno'.($a+1)), 'Tel No.');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Tel No.');
								}
							}
						}
						break;
					case "email":
						$count_to_insert = $this->put('email_count');
						
						$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'4');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									if($get_existing['result'][$b]['CONTACT_DETAIL'] == $this->put('email'.($a+1))){
										$handler = ($b+1);
										break;
									}
								}
								
								if(($a+1) <= $row_count){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], $this->put('email'.($a+1)), 'Email');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $this->put('email'.($a+1)), 'Email');
								}
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									if($get_existing['result'][$a]['CONTACT_DETAIL'] == $this->put('email'.($b+1))){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], $this->put('email'.($a+1)), 'Email');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], '', 'Email');
								}
							}
						}
						break;
					case "fax":
						$count_to_insert = $this->put('faxno_count');
						
						$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'2');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$insert_details = $this->put('fax_ccode'.($a+1)) . $this->put('fax_acode'.($a+1)) . $this->put('fax_no'.($a+1)) . $this->put('fax_elno'.($a+1));
								$to_logs = $this->put('fax_ccode'.($a+1)) . " " . $this->put('fax_acode'.($a+1)) . " " . $this->put('fax_no'.($a+1)) . " " . $this->put('fax_elno'.($a+1));
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['AREA_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'] . $get_existing['result'][$b]['EXTENSION_LOCAL_NUMBER'];
									if($db_details == $insert_details){
										$handler = ($b+1);
										break;
									}
								}
								if(($a+1) <= $row_count){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'], $to_logs, 'Fax No.');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Fax No.');
								}
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['AREA_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'] . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
								$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									$insert_details = $this->put('fax_ccode'.($b+1)) . $this->put('fax_acode'.($b+1)) . $this->put('fax_no'.($b+1)) . $this->put('fax_elno'.($b+1));
									if($db_details == $insert_details){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->put('fax_ccode'.($a+1)) . " " . $this->put('fax_acode'.($a+1)) . " " . $this->put('fax_no'.($a+1)) . " " . $this->put('fax_elno'.($a+1)), 'Fax No.');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Fax No.');
								}
							}
						}
						break;
					case "cpno":
						$count_to_insert = $this->put('mobno_count');
						
						$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'3');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$insert_details = $this->put('mobile_ccode'.($a+1)) . $this->put('mobile_no'.($a+1));
								$to_logs = $this->put('mobile_ccode'.($a+1)) . " " . $this->put('mobile_no'.($a+1));
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'];
									if($db_details == $insert_details){
										$handler = ($b+1);
										break;
									}
								}
								if(($a+1) <= $row_count){
									if($handler == 0){
										//$handler .= 'test';	// Insert
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'], $to_logs, 'Mobile No.');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Mobile No.');
								}
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'];
								$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'];
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									$insert_details = $this->put('mobile_ccode'.($b+1)) . $this->put('mobile_no'.($b+1));
									if($db_details == $insert_details){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->put('mobile_ccode'.($a+1)) . " " . $this->put('mobile_no'.($a+1)), 'Mobile No.');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Mobile No.');
								}
							}
						}
						break;
					case "opd":
						$count_to_insert = $this->put('opd_count');
						
						$get_existing = $this->registration_model->al_o_ar($var['vendor_id'],'SMNTP_VENDOR_OWNERS');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$insert_details = $this->put('opd_fname'.($a+1)) . $this->put('opd_mname'.($a+1)) . $this->put('opd_lname'.($a+1)) . $this->put('opd_pos'.($a+1));
								$to_logs = $this->put('opd_fname'.($a+1)) . " " . $this->put('opd_mname'.($a+1)) . " " . $this->put('opd_lname'.($a+1)) . " " . $this->put('opd_pos'.($a+1));
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									$db_details = $get_existing['result'][$b]['FIRST_NAME'] . $get_existing['result'][$b]['MIDDLE_NAME'] . $get_existing['result'][$b]['LAST_NAME'] . $get_existing['result'][$b]['POSITION'];
									if($db_details == $insert_details){
										$handler = ($b+1);
										break;
									}
								}
								if(($a+1) <= $row_count){
									if($handler == 0){
										//$handler .= 'test';	// Insert
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'], $to_logs, 'Owners/Partners/Directors');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Owners/Partners/Directors');
								}
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$db_details = $get_existing['result'][$a]['FIRST_NAME'] . $get_existing['result'][$a]['MIDDLE_NAME'] . $get_existing['result'][$a]['LAST_NAME'] . $get_existing['result'][$a]['POSITION'];
								$to_logs = $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'];
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									$insert_details = $this->put('opd_fname'.($b+1)) . $this->put('opd_mname'.($b+1)) . $this->put('opd_lname'.($b+1)) . $this->put('opd_pos'.($b+1));
									if($db_details == $insert_details){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->put('opd_fname'.($a+1)) . " " . $this->put('opd_mname'.($a+1)) . " " . $this->put('opd_lname'.($a+1)) . " " . $this->put('opd_pos'.($a+1)), 'Owners/Partners/Directors');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Owners/Partners/Directors');
								}
							}
						}
						
						break;
					case "ar":
						$count_to_insert = $this->put('authrep_count');
						
						$get_existing = $this->registration_model->al_o_ar($var['vendor_id'],'SMNTP_VENDOR_REP');
						$row_count = $get_existing['num_row'];
						
						if($row_count <= $count_to_insert){ // mas madami iinsert sa db
							for($a=0; $a<$count_to_insert; $a++){
								$insert_details = $this->put('authrep_fname'.($a+1)) . $this->put('authrep_mname'.($a+1)) . $this->put('authrep_lname'.($a+1)) . $this->put('authrep_pos'.($a+1));
								$to_logs = $this->put('authrep_fname'.($a+1)) . " " . $this->put('authrep_mname'.($a+1)) . " " . $this->put('authrep_lname'.($a+1)) . " " . $this->put('authrep_pos'.($a+1));
								$handler = 0;
								for($b=0; $b<$row_count; $b++){
									$db_details = $get_existing['result'][$b]['FIRST_NAME'] . $get_existing['result'][$b]['MIDDLE_NAME'] . $get_existing['result'][$b]['LAST_NAME'] . $get_existing['result'][$b]['POSITION'];
									if($db_details == $insert_details){
										$handler = ($b+1);
										break;
									}
								}
								if(($a+1) <= $row_count){
									if($handler == 0){
										//$handler .= 'test';	// Insert
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'], $to_logs, 'Authorized Representatives');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Authorized Representatives');
								}
								
							}
						}else{ // Mas mdmi ang nasa db
							 for($a=0; $a<$row_count; $a++){
								$db_details = $get_existing['result'][$a]['FIRST_NAME'] . $get_existing['result'][$a]['MIDDLE_NAME'] . $get_existing['result'][$a]['LAST_NAME'] . $get_existing['result'][$a]['POSITION'];
								$to_logs = $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'];
								$handler = 0;
								for($b=0; $b<$count_to_insert; $b++){
									$insert_details = $this->put('authrep_fname'.($b+1)) . $this->put('authrep_mname'.($b+1)) . $this->put('authrep_lname'.($b+1)) . $this->put('authrep_pos'.($b+1));
									if($db_details == $insert_details){
										$handler = ($b+1);
									}
								}
								
								if(($a+1) <= $count_to_insert){
									if($handler == 0){
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->put('authrep_fname'.($a+1)) . " " . $this->put('authrep_mname'.($a+1)) . " " . $this->put('authrep_lname'.($a+1)) . " " . $this->put('authrep_pos'.($a+1)), 'Authorized Representatives');
										
									}
								}else{
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Authorized Representatives');
								}
							}
						}
						
						break;
				}
			}
			
			$this->update_vendor($var);
			//$this->delete_incrementing_tables($var);
			
			$where_vendor_arr = array('VENDOR_ID' => $var['vendor_id']);
			
			$this->registration_model->delete_table('SMNTP_VENDOR_BRAND', $where_vendor_arr);
			$this->save_brands($var);
			
			$this->registration_model->delete_table('SMNTP_VENDOR_ADDRESSES', $where_vendor_arr);
			$this->save_addresses($var);
			
			$this->registration_model->delete_table('SMNTP_VENDOR_CONTACT_DETAILS', $where_vendor_arr);
			$this->save_contact_details($var);
			
			$this->registration_model->delete_table('SMNTP_VENDOR_OWNERS', $where_vendor_arr);
			$this->save_opd($var); //owner partner directors

			$this->registration_model->delete_table('SMNTP_VENDOR_REP', $where_vendor_arr);
			$this->save_authrep($var); //Authorized Representatives

			$this->registration_model->delete_table('SMNTP_VENDOR_BANK', $where_vendor_arr);
			$this->save_bankrep($var); //Bank References

			$this->registration_model->delete_table('SMNTP_VENDOR_OTHER_RETCUST', $where_vendor_arr);
			$this->save_retcust($var); //Other Retail Customers/Clients

			$this->registration_model->delete_table('SMNTP_VENDOR_OTHER_BUSINESS', $where_vendor_arr);
			$this->save_otherbusiness($var); //Other Business

			$this->registration_model->delete_table('SMNTP_VENDOR_RELATIVES', $where_vendor_arr);
			$this->save_relativeaffiliates($var); //Disclosure of Relatives Working in SM or its Affiliates	
		}			
		
		$cat_array = '';
		for($i = 1; $i <= $category_sup_count; $i++){
			if(!($categoryid = $this->put('category_id' . $i))) {
				continue;
			}
			$cat_array .= $categoryid . ',';
		}
		
		$docs_var['category_id']		= explode(',', rtrim($cat_array, ','));
		$docs_var['invite_id'] 			= $this->put('invite_id');
		
		//Required Scanned Documents
		$rsd = $this->registration_model->get_rsd_docs($docs_var);	
		
		for ($i=1; $i <= count($rsd); $i++)
		{ 
			$rsd_document_chk 		= $this->put('rsd_document_chk'.$i); // get id
			$rsd_document_review 	= $this->put('rsd_document_review'.$i); // checkbox review
			$rsd_date_reviewed 		= $this->put('rsd_date_reviewed'.$i); // review date
			$rsd_document_validated = $this->put('rsd_document_validated'.$i); // checkbox validate
			$rsd_date_validated 	= $this->put('rsd_date_validated'.$i); // date validated

			if ($var['status'] == 1|| $var['status'] == 2) // 1 draft or 2 submit
			{
				if (!empty($rsd_document_validated))
				{
					//if ($rsd_date_validated != '')
					//{
					//	$rsd_date_validated = DateTime::createFromFormat('m/d/Y h:i:s A', $rsd_date_validated);
					//	$rsd_date_validated = $rsd_date_validated->format("Y-m-d H:i:s");
					//}

					$update_req_doc = array(
									'DATE_VERIFIED' => $rsd_date_validated
								);

					$where_req_doc = array(
									'VENDOR_ID' 	=> $var['vendor_id'],
									'DOC_TYPE_ID' 	=> $rsd_document_chk
								);
					$this->common_model->update_table('SMNTP_VENDOR_REQUIRED_DOC', $update_req_doc, $where_req_doc);
					
				}
			}
			else // 3 approve or 4 reject
			{
				if (!empty($rsd_document_review))
				{
					//if ($rsd_date_reviewed != '')
					//{
					//	$rsd_date_reviewed = DateTime::createFromFormat('m/d/Y h:i:s A', $rsd_date_reviewed);
					//	$rsd_date_reviewed = $rsd_date_reviewed->format("Y-m-d H:i:s");
					//}

					$update_req_doc = array(
									'DATE_REVIEWED' => $rsd_date_reviewed
								);

					$where_req_doc = array(
									'VENDOR_ID' 	=> $var['vendor_id'],
									'DOC_TYPE_ID' 	=> $rsd_document_chk
								);
					
					$this->common_model->update_table('SMNTP_VENDOR_REQUIRED_DOC', $update_req_doc, $where_req_doc);
					
				}
			}
		}

		//Required Agreements
		$ra = $this->registration_model->get_ra_docs($docs_var);

		for ($i=1; $i <= count($ra); $i++)
		{ 
			$ra_document_chk 		= $this->put('ra_document_chk'.$i); // get id
			$ra_document_review 	= $this->put('ra_document_review'.$i); // checkbox review
			$ra_date_reviewed 		= $this->put('ra_date_reviewed'.$i); // review date
			$ra_document_validated 	= $this->put('ra_document_validated'.$i); // checkbox validated
			$ra_date_validated 		= $this->put('ra_date_validated'.$i); // submitted date

			if ($var['status'] == 1|| $var['status'] == 2) // 1 draft or 2 submit
			{
				if (!empty($ra_document_validated))
				{
					//if ($ra_date_validated != '')
					//{
					//	$ra_date_validated = DateTime::createFromFormat('m/d/Y h:i:s A', $ra_date_validated);
					//	$ra_date_validated = $ra_date_validated->format("Y-m-d H:i:s");
					//}

					$update_agreement = array(
										'DATE_SUBMITTED' 	=> $ra_date_validated
									);

					$where_agreement = array(
										'VENDOR_ID' 	=> $var['vendor_id'],
										'DOC_TYPE_ID' 	=> $ra_document_chk
									);

					$this->common_model->update_table('SMNTP_VENDOR_AGREEMENTS', $update_agreement, $where_agreement);
					
				}
			}
			else // 3 approve or 4 reject
			{
				if (!empty($ra_document_review))
				{
					//if ($ra_date_reviewed != '')
					//{
					//	$ra_date_reviewed = DateTime::createFromFormat('m/d/Y h:i:s A', $ra_date_reviewed);
					//	$ra_date_reviewed = $ra_date_reviewed->format("Y-m-d H:i:s");
					//}
					
					$update_agreement = array(
										'DATE_REVIEWED' 	=> $ra_date_reviewed
									);

					$where_agreement = array(
										'VENDOR_ID' 	=> $var['vendor_id'],
										'DOC_TYPE_ID' 	=> $ra_document_chk
									);

					$this->common_model->update_table('SMNTP_VENDOR_AGREEMENTS', $update_agreement, $where_agreement);
					
				}
			}
		}

		//CCN Agreements
		if($docs_var['registration_type'] == 5){
			$ccn = $this->registration_model->get_ccn_docs();

			for ($i=1; $i <= count($ccn); $i++)
			{ 
				$ccn_document_chk 		= $this->put('ccn_document_chk'.$i); // get id
				$ccn_document_review 	= $this->put('ccn_document_review'.$i); // checkbox review
				$ccn_date_reviewed 		= $this->put('ccn_date_reviewed'.$i); // review date
				$ccn_document_validated 	= $this->put('ccn_document_validated'.$i); // checkbox validated
				$ccn_date_validated 		= $this->put('ccn_date_validated'.$i); // submitted date

				if ($var['status'] == 1|| $var['status'] == 2) // 1 draft or 2 submit
				{
					if (!empty($ccn_document_validated))
					{
						//if ($ccn_date_validated != '')
						//{
						//	$ccn_date_validated = DateTime::createFromFormat('m/d/Y h:i:s A', $ccn_date_validated);
						//	$ccn_date_validated = $ccn_date_validated->format("Y-m-d H:i:s");
						//}

						$update_agreement = array(
											'DATE_SUBMITTED' 	=> $ccn_date_validated
										);

						$where_agreement = array(
											'VENDOR_ID' 	=> $var['vendor_id'],
											'DOC_TYPE_ID' 	=> $ccn_document_chk
										);

						$this->common_model->update_table('SMNTP_VENDOR_CCN', $update_agreement, $where_agreement);
					}
				}
				else // 3 approve or 4 reject
				{
					if (!empty($ccn_document_review))
					{
						//if ($ccn_date_reviewed != '')
						//{
						//	$ccn_date_reviewed = DateTime::createFromFormat('m/d/Y h:i:s A', $ccn_date_reviewed);
						//	$ccn_date_reviewed = $ccn_date_reviewed->format("Y-m-d H:i:s");
						//}
						
						$update_agreement = array(
											'DATE_REVIEWED' 	=> $ccn_date_reviewed
										);

						$where_agreement = array(
											'VENDOR_ID' 	=> $var['vendor_id'],
											'DOC_TYPE_ID' 	=> $ccn_document_chk
										);

						$this->common_model->update_table('SMNTP_VENDOR_CCN', $update_agreement, $where_agreement);
					}
				}
			}
		}

		return true;
	}

	public function save_incomplete_reason_put()
	{
		$reason 	= $this->put('rv_incomplete');
		$vendor_id 	= $this->put('vendor_id');
		$user_id 	= $this->put('user_id');
		$ir_count 	= $this->put('increason_count');

		// get vendor invite id 
		$where_arr = array('VENDOR_ID' => $vendor_id);
		$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr);

		$ir_arr = ['VENDOR_ID' =>  $vendor_id, 'INVITE_ID' => $invite_id ];
		// count of existing incomplete reason
		$curr_ir = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INCOMPLETE_REASON', 'COUNT(*) AS COUNT', $ir_arr, 'COUNT');

		if ($curr_ir > 0) // delete if exist before insert new values
			$this->registration_model->delete_table('SMNTP_VENDOR_INCOMPLETE_REASON', $ir_arr);
		
		$ir_save = array();
		$counter = 0;
		for ($i=1; $i <= $ir_count; $i++)
		{ 
			$reason_id 	= $this->put('cbo_inc_reason'.$i);
			$cbo_da 	= $this->put('cbo_da'.$i);
			$da 		= explode('|', $cbo_da);

			$da_id 			= '';
			$document_type 	= '';
			if (count($da) > 1)
			{
				$da_id 			= $da[0]; // DOCUMENT_ID
				$document_type 	= $da[1]; // DOCUMENT_TYPE
			}			

			$ir_save = [
							'VENDOR_ID' 	=> $vendor_id,
							'INVITE_ID' 	=> $invite_id,
							'REASON_ID' 	=> $reason_id,
							'DOCUMENT_ID' 	=> $da_id,
							'DOCUMENT_TYPE' => $document_type
						];

			if (!empty($cbo_da))
			$save_db = $this->common_model->insert_table('SMNTP_VENDOR_INCOMPLETE_REASON', $ir_save);
		
			if($save_db){
				$counter = $counter + 1;	
			}
		}
			
		$record_arr = [ 
						'APPROVER_ID' 		=> $user_id,
						'APPROVER_REMARKS' 	=> $reason
						];

		$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, ['VENDOR_INVITE_ID' => $invite_id]);

		if ($counter > 0)
		{
			$data['status'] = TRUE;
			$data['error'] = '';
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
		}
		
		$this->response($data);
	}

	public function filter_document_agreement_get()
	{
		$ownership 			= $this->get('ownership');
		$trade_vendor_type 	= $this->get('trade_vendor_type');

		$da_arr = [
					'ownership' 		=> $ownership,
					'trade_vendor_type' => $trade_vendor_type
				];

		$rs_da = $this->registration_model->get_document_agreement($da_arr);

		$data['rs_da'] = $rs_da;
		$this->response($data);
	}

	public function filter_documents_get()
	{
		$ownership 			= $this->get('ownership');
		$trade_vendor_type 	= $this->get('trade_vendor_type');
		$vendor_type 		= $this->get('vendor_type');
		$current_status_id 	= $this->get('current_status_id');
		$invite_id 			= $this->get('invite_id');
		$categories			= $this->get('category_id');
		
		$param = array(
			'ownership' 		=> $ownership,
			'vendor_type' 		=> $vendor_type,
			'trade_vendor_type' => $trade_vendor_type,
			'invite_id' 		=> $invite_id,
			'current_status_id' => $current_status_id
		);

		if( ! empty($categories)){
			$param['category_id'] = explode(",", $categories);
		}else{
			$param['category_id'] = array();
		}

		// Modified MSF - 20200108 (IJR-10619)
		/*
		$other_docs = NULL;

		$current_documents = NULL;
		// 10 = Vendor
		if($current_status_id == 10){
			// Primary Documents
			$other_docs = $this->registration_model->get_primary_others_docs();
			$current_documents = $this->registration_model->get_rsd_docs($param);
		}else{
			// Additional Documents
			$other_docs =  $this->registration_model->get_additional_others_docs();
			$current_documents = $this->registration_model->get_ra_docs($param);
		}
		
		$data['rs_da'] = array_merge($current_documents, $other_docs);
		*/
		
		$other_primary_docs = NULL;
		$other_additional_docs = NULL;
		$current_documents = NULL;
		$additional_docs = NULL;

		$current_documents = $this->registration_model->get_rsd_docs($param);
		$additional_docs = $this->registration_model->get_ra_docs($param);
		//var_dump($additional_docs);die;
		$other_primary_docs = $this->registration_model->get_primary_others_docs();
		
		$data['rs_da'] = array_merge($current_documents, $additional_docs, $other_primary_docs);
		
		$this->response($data);
	}

	public function inc_reason_get()
	{
		$cbo_da = $this->get('cbo_da');
		$da = explode('|', $cbo_da);

		$da_id 			= '';
		$document_type 	= '';
		if (count($da) > 1)
		{
			$da_id 			= $da[0]; // DA_ID
			$document_type 	= $da[1]; // DOCUMENT_TYPE
		}
		

		$var = [
				'DOCUMENT_ID' 	=> $da_id,
				'DOCUMENT_TYPE' => $document_type
				];

		$rs = $this->registration_model->get_incomplete_reason($var);

		$tmprs = $rs->result_array();

/*		if(count($tmprs) == 0){
			$tmprs[0]['INCOMPLETE_REASON']  = "-- NO REASON FOUND --";
			$tmprs[0]['REASON_ID'] = "0";
		}*/

		$data['rs_inc_reason'] = $tmprs;
		//$data['rs_inc_reason'] = $rs->result_array();
		$this->response($data);
	}
	
	public function save_remarks_put()
	{
		$data['vid'] 			= $this->put('vid');
		$data['remark_type'] 	= $this->put('remark_type');
		$data['note'] 			= $this->put('note');
		
		if(empty(trim($data['note']))){
			$data['note'] = NULL;
		}
		$result = $this->registration_model->save_remarks($data);

		$this->response($result);
	}
	
	public function delete_vendor_put()
	{
		$data['vid'] 			= $this->put('vid');
		$data['delReason'] 		= $this->put('delReason');
		//$data['sid'] 		= $this->put('sid');
		$result = $this->registration_model->delete_vendor($data);
		$this->response($result);
	}


	public function incomplete_additional_notif_put()
	{
	
		$rcount = $this->put('increason_count');
		$this->load->model('mail_model');


		$vndid = $this->put('vendor_id');

		if(!isset($vndid)){
			$this->response("error");
		}

		$ra = array();
		$ra2 = array();
		$ra3 = array();

	$x = 1;
	for($x = 1;$x <= $rcount ; $x++){

		$da = explode("|",$this->put('cbo_da'.$x.''));

		array_push($ra,$da[0]);
		array_push($ra2, $this->put('cbo_inc_reason'.$x.''));
		//array_push($ra3,$da[0]."|". $this->put('cbo_inc_reason'.$x.''));
	}

	if(count($ra) == 1){


		$rName2 = $this->common_model->select_query2($ra,$ra2);

	}

	if(count($ra) > 1){
		$rName2 = $this->common_model->select_query_wherein2('A.REQUIRED_AGREEMENT_ID',$ra,'B.REASON_ID',$ra2);
	}



	$bdy = "";

	foreach ($rName2 as $key => $value) {
	
		//$bdy .= "Document : " . $value['REQUIRED_AGREEMENT_NAME'] .'<br>';
		//$bdy .= "Reason : " . $value['INCOMPLETE_REASON'] .'<br>';
		if (isset($value['INCOMPLETE_REASON']) || $value['INCOMPLETE_REASON'] == ''){
			if ($value['REQUIRED_AGREEMENT_NAME'] != 'Others'){
				$incomplete_html .= '<tr><td>'.$value['REQUIRED_AGREEMENT_NAME'].'</td><td>'.$value['INCOMPLETE_REASON'] .'</td></tr>';
			}
		}
	}

	$tbl_inc_reason_logs  = '<table class="table table-bordered" >';//jay width="100%" align="right"
	if( ! empty($incomplete_html)){
		$tbl_inc_reason_logs .= '<tr>';
		$tbl_inc_reason_logs .= '<th class="inc_th" >Form/Document</th>';
		$tbl_inc_reason_logs .= '<th class="inc_th" >Reason</th>';
		$tbl_inc_reason_logs .= '</tr>';
		$tbl_inc_reason_logs .= $incomplete_html;
		$tbl_inc_reason_logs .= '<tr>';
	}
	$tbl_inc_reason_logs .= '<th colspan="2" class="inc_th" >Others</th>';
	$tbl_inc_reason_logs .= '</tr>';
	$tbl_inc_reason_logs .= '<tr>';
	$tbl_inc_reason_logs .= '<td colspan="2">'.$this->put('rv_incomplete').'</td>';
	$tbl_inc_reason_logs .= '</tr>';
	$tbl_inc_reason_logs .= '</table>';
	//$bdy .= "Remarks : " . $this->put('rv_incomplete')."<br>";


	



	$vndn = $this->common_model->select_query('SMNTP_USERS',array('VENDOR_ID' => $vndid),'USER_ID,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
	$approver = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $this->put('user_id')),'USER_ID,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

	$approvername = $this->trim_name($approver);

	$vndname = $this->trim_name($vndn);

				$where_arr_def = array(
				'TYPE_ID' 		=> 1, // for registration
				'STATUS_ID' 	=> 155 //statusid for incomplete
			);


	$tMess = $this->common_model->get_message_default($where_arr_def)->result_array();


	$tMess[0]['SUBJECT'] = str_replace('[vendorname]', $vndname, $tMess[0]['SUBJECT']);
	$tMess[0]['TOPIC'] = str_replace('[vendorname]', $vndname, $tMess[0]['TOPIC']);
	$tMess[0]['MESSAGE'] = str_replace('[vendorname]', $vndname, $tMess[0]['MESSAGE']);
	$tMess[0]['MESSAGE'] = str_replace('[approver]', $approvername, $tMess[0]['MESSAGE']);
	$tMess[0]['MESSAGE'] = str_replace('[remarks]', $tbl_inc_reason_logs, $tMess[0]['MESSAGE']);



			$insert_array = array(
			'SUBJECT' => $tMess[0]['SUBJECT'],
			'TOPIC' => $tMess[0]['TOPIC'],
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $tMess[0]['MESSAGE'],
			'TYPE' => 'notification',
			'SENDER_ID' => 0,//notif
			'RECIPIENT_ID' => $vndn[0]['USER_ID'], //can be changed in query
			'VENDOR_ID' => $this->put('vendor_id')
		);

		$model_data = $this->mail_model->send_message($insert_array);
		$this->response($insert_array);
	}

	public function trim_name($name)
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


	function completed_notif_put()
	{
		$this->load->model('mail_model');

		$registration_type = $this->put('registration_type');

		$vndid['VENDOR_ID'] = $this->put('vendor_id');
		$vndinfo = $this->common_model->select_vendor_info($vndid);

		$vndns = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vndinfo[0]['USER_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
		$cndns = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vndinfo[0]['CREATED_BY']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
		$vndn = $this->trim_name($vndns);
		$cndn = $this->trim_name($cndns);

		if(!isset($vndid)){
			$this->response("error");
		}
		
		//For vendor
		
		//Portal notification
		$message_data['where'] = array(
			'TYPE_ID' 		=> 1, // for registration
			'STATUS_ID' 	=> 157 //Account Registration Complete For Vendor
		);
		$message_data['vendorname'] 	= $vndn;
		$message_data['creatorname'] 	= $cndn;
		$message_data['vendor_id']	 	= $vndid['VENDOR_ID'];
		$message_data['subject']  		= 'Vendor Registration Complete';
		$message_data['recipient_id'] 	= $vndinfo[0]['USER_ID'];
		$this->send_completed_portal_message($message_data);
		
		//Email
		$message_data['template_type'] 	= 36;
		$message_data['email'] 			= $vndns[0]['USER_EMAIL']; 
		$this->send_completed_email_message($message_data);
		//vendor end
	
	
		if($registration_type != 2 && $registration_type != 3){
			//For creator - Portal notification
			$message_data['where'] = array(
				'TYPE_ID' 		=> 1, // for registration
				'STATUS_ID' 	=> 158 //Account Registration Complete For SM Users
			);
			$message_data['subject']  		= $vndn .  ' - Vendor Registration Complete';
			$message_data['creatorname'] = $cndn;
			$message_data['recipient_id'] = $vndinfo[0]['CREATED_BY'];
			$this->send_completed_portal_message($message_data);
			
			//Email
			$message_data['template_type'] 	= 37;
			$message_data['email'] 			= $cndns[0]['USER_EMAIL']; 
			$this->send_completed_email_message($message_data);
			//end
			
			//Inviter User matrix
			$inviter_matrix = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('USER_ID' => $vndinfo[0]['CREATED_BY']),'BUHEAD_ID, GHEAD_ID, FASHEAD_ID, VRDHEAD_ID');
			$sm_users_id = array();
			
			//Get all assigned ID
			foreach($inviter_matrix as $uid){
				$temp_uid = NULL;
				
				//BUHEAD
				if( ! empty($uid['BUHEAD_ID'])){
					$temp_uid = $uid['BUHEAD_ID'];
				}
				if( ! in_array($temp_uid, $sm_users_id) && ! empty($temp_uid)){
					$sm_users_id[] = $temp_uid;
				}
				
				//GHEAD
				if( ! empty($uid['GHEAD_ID'])){
					$temp_uid = $uid['GHEAD_ID'];
				}
				if( ! in_array($temp_uid, $sm_users_id) && ! empty($temp_uid)){
					$sm_users_id[] = $temp_uid;
				}
				
				//FASHEAD
				if( ! empty($uid['FASHEAD_ID'])){
					$temp_uid = $uid['FASHEAD_ID'];
				}
				if( ! in_array($temp_uid, $sm_users_id) && ! empty($temp_uid)){
					$sm_users_id[] = $temp_uid;
				}
				
				//VRDHEAD
				if( ! empty($uid['VRDHEAD_ID'])){
					$temp_uid = $uid['VRDHEAD_ID'];
				}
				if( ! in_array($temp_uid, $sm_users_id) && ! empty($temp_uid)){
					$sm_users_id[] = $temp_uid;
				}
			}
			
			//Send portal and email to sm users id array
			foreach($sm_users_id as $sm_uid){
				//For creator - Portal notification
				$sm_user_info = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $sm_uid),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
				$sm_user_name = $this->trim_name($sm_user_info);

				$message_data['creatorname'] = $sm_user_name;
				$message_data['recipient_id'] = $sm_uid;
				$this->send_completed_portal_message($message_data);
				
				//Email
				$message_data['email'] = $sm_user_info[0]['USER_EMAIL']; 
				$this->send_completed_email_message($message_data);
				//end
			}
		}
		$this->response('DONE');
	}
	
	
	
	function update_vendor($var)
	{
		$business_yr 		= $var['cbo_yr_business'];
		$ownership 			= $var['ownership'];
		$vendor_type 		= $var['vendor_type'];
		$tax_idno 			= $var['tax_idno'];
		$tax_class 			= $var['tax_class'];
		//nature of businiess
		$license_dist 		= isset($var['nob_license_dist']) 	? 1 : 0;
		$manufacturer 		= isset($var['nob_manufacturer']) 	? 1 : 0;
		$importer 			= isset($var['nob_importer']) 		? 1 : 0;
		$wholesaler 		= isset($var['nob_wholesaler']) 	? 1 : 0;
		$nob_others 		= isset($var['nob_others']) 		? 1 : 0;
		$txt_nob_others		= $var['txt_nob_others'];
		$trade_vendor_type	= isset($var['trade_vendor_type']) ? $var['trade_vendor_type'] : '';
		
		//business_asset and no_of_employee
		$business_asset = $var['business_asset'];
		$no_of_employee = $var['no_of_employee'];
		
		// update to SMNTP_VENDOR first
		$where_arr = array('VENDOR_INVITE_ID' => $var['invite_id']);
		$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);
		
		$created_by = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);
		
		$where_arr2 = array('USER_ID' => $created_by);
		$creator_position = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr2);
		
		//If NTS then gawing 3 ang Vendor Type
		if($creator_position == 11){
			$vendor_type = 3; // 3 = NTS
		}else if($creator_position == 7){
			$vendor_type = 2; // 2 = NTFAS
		}else if($creator_position == 2){
			$vendor_type = 1; // 1 = TRADE
			$tvt = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'TRADE_VENDOR_TYPE', $where_arr);
			if(!empty($tvt)){
				$trade_vendor_type = $tvt;
			}
		}
		
		$arr_record_vendor = array(
							'VENDOR_INVITE_ID'		=> $var['invite_id'],
							'VENDOR_NAME'			=> $vendorname,
							'YEAR_IN_BUSINESS'		=> $business_yr,
							'OWNERSHIP_TYPE'		=> $ownership, // Corporation = 1, Partnership = 2, Sole Proprietorship = 3
							'VENDOR_TYPE'			=> $vendor_type, //Trade = 1, Non Trade = 2
							'TAX_ID_NO'				=> $tax_idno,
							'TAX_CLASSIFICATION'	=> $tax_class,
							'NOB_DISTRIBUTOR'		=> $license_dist,
							'NOB_MANUFACTURER'		=> $manufacturer,
							'NOB_IMPORTER'			=> $importer,
							'NOB_WHOLESALER'		=> $wholesaler,
							'NOB_OTHERS'			=> $nob_others,
							'NOB_OTHERS_TEXT'		=> $txt_nob_others,
							'TRADE_VENDOR_TYPE'		=> $trade_vendor_type, // Outright = 1, Consignor = 2
							'EMPLOYEE'		=> $no_of_employee, 
							'BUSINESS_ASSET'		=> $business_asset 
					);

		$where_vendor_arr = array('VENDOR_ID' => $var['vendor_id']);
		
		if($var['status_id'] == 1){
			$already_submitted = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'DATE_CREATED', $where_vendor_arr);
			if(empty($already_submitted)){
				$arr_record_vendor['DATE_CREATED'] = NULL;
			}
		}else{
			$this->registration_model->update_vendor_submitted_date($var['vendor_id']);
		}
		
		$this->common_model->update_table('SMNTP_VENDOR', $arr_record_vendor, $where_vendor_arr);
	}
	
	function save_brands($var)
	{
		$brand_count 	= $var['brand_count'];

		for ($i=1; $i <= $brand_count; $i++)
		{ 
			$brand_id 	= $var['brand_id'.$i];
			$brand_name = strtoupper(trim($var['brand_name'.$i])); //trim spaces
			if (empty($brand_id)) // if brand id is empty insert brand to SMNTP_BRAND then get id and insert to SMNTP_VENDOR_BRAND
			{
				// double check if brand exists then get ID
				if (!empty($brand_name))
				{
					$where = array('UPPER(BRAND_NAME)' => strtoupper($brand_name)); //array('BRAND_NAME' => $brand_name);
					$brand_id = $this->common_model->get_from_table_where_array('SMNTP_BRAND', 'BRAND_ID', $where);
					$bi = array('BRAND_ID'=> $brand_id);
					$this->common_model->update_brand($bi);

				}

				if (empty($brand_id))
				{
					$save_brand = array(
									'BRAND_NAME' => $brand_name,
									'CREATED_BY' => $var['user_id'],
									'DATE_UPLOADED' => date('Y-m-d H:i:s'),
									'STATUS' => '1'
								);

					$brand_id = $this->registration_model->insert_brand($save_brand);
				}
			}

			// insert to SMNTP_VENDOR_BRAND
			$save_vendor_brand = array(
									'VENDOR_ID'		=> $var['vendor_id'],
									'BRAND_ID'		=> $brand_id,
									// 'DATE_CREATED'	=> $var['timestamp']
								);
			
			$this->common_model->insert_table('SMNTP_VENDOR_BRAND', $save_vendor_brand);
		}
	}
	
	function save_addresses($var)
	{
		$office_addr_count	= $var['office_addr_count'];
		$office_primary		= $var['office_primary'];

		$batch_office = array();
		for ($i=1; $i <= $office_addr_count; $i++)
		{ 
			$office_add 			= strtoupper(html_entity_decode($var['office_add'.$i]));
			$office_brgy_cm_id 		= null;
			$office_brgy_cm 		= strtoupper(html_entity_decode($var['office_brgy_cm'.$i]));
			$office_state_prov_id 	= null;
			$office_state_prov 		= strtoupper(html_entity_decode($var['office_state_prov'.$i]));
			$office_zip_code 		= strtoupper($var['office_zip_code'.$i]);
			$office_country_id 		= null;
			$office_country 		= strtoupper(html_entity_decode($var['office_country'.$i]));
			
			$office_region_id 		= null;
			$office_region 		= strtoupper(html_entity_decode($var['office_region'.$i]));

			if (empty($office_region_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($office_region))
				{
					$where = 'upper(REGION_DESC_TWO) = upper(\''.$office_region.'\')';
					$office_region_id = $this->common_model->get_from_table_where_array('SMNTP_REGIONS', 'REGION_ID', $where);
				}

				if (empty($office_region_id) && !empty($office_region))
				{
					$region_arr = array(
									'REGION_DESC_TWO' 	=> $office_region,
									'CREATED_BY' 	=> $var['user_id'],
									'STATUS' 	=> 1
								);

					$office_region_id = $this->registration_model->insert_region($region_arr);
				}
			}

			if (empty($office_brgy_cm_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($office_brgy_cm))
				{
					$where = "CITY_NAME = '".$office_brgy_cm."'";
					$office_brgy_cm_id = $this->common_model->get_from_table_where_array('SMNTP_CITY', 'CITY_ID', $where);
				}

				if (empty($office_brgy_cm_id) && !empty($office_brgy_cm))
				{
					$city_arr = array(
									'CITY_NAME' 	=> $office_brgy_cm,
									'CREATED_BY' 	=> $var['user_id']
								);

					$office_brgy_cm_id = $this->registration_model->insert_city($city_arr);
				}
			}

			if (empty($office_state_prov_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($office_state_prov))
				{
					$where = "STATE_PROV_NAME = '".$office_state_prov."'";
					$office_state_prov_id = $this->common_model->get_from_table_where_array('SMNTP_STATE_PROVINCE', 'STATE_PROV_ID', $where);
				}

				if (empty($office_state_prov_id) && !empty($office_state_prov))
				{
					$state_arr = array(
									'STATE_PROV_NAME' 	=> $office_state_prov,
									'CREATED_BY' 		=> $var['user_id']
								);

					$office_state_prov_id = $this->registration_model->insert_state($state_arr);
				}
			}

			if (empty($office_country_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($office_country))
				{
					$where = "COUNTRY_NAME = '".$office_country."'";
					$office_country_id = $this->common_model->get_from_table_where_array('SMNTP_COUNTRY', 'COUNTRY_ID', $where);
				}

				if (empty($office_country_id) && !empty($office_country))
				{
					$country_arr = array(
									'COUNTRY_NAME' 	=> $office_country,
									'CREATED_BY' 	=> $var['user_id']
								);

					$office_country_id = $this->registration_model->insert_country($country_arr);
				}
			}

			if ($i == $office_primary)
				$primary = 1;
			else
				$primary = 0;

			$batch_office[] = array(
									'VENDOR_ID'				=> $var['vendor_id'],
									'ADDRESS_TYPE'			=> 1, //1 = offce , 2 = factor, 3 = warehouse
									'ADDRESS_LINE'			=> $office_add,
									'BRGY_MUNICIPALITY_ID'	=> $office_brgy_cm_id,
									'STATE_PROVINCE_ID'		=> $office_state_prov_id,
									'ZIP_CODE'				=> $office_zip_code,
									'COUNTRY_ID'			=> $office_country_id,
									'REGION_ID'				=> $office_region_id,
									'`PRIMARY`'				=> $primary,
									// 'DATE_CREATED'			=> $var['timestamp'],
									'ACTIVE'				=> 1
								);
		}

		// SMNTP_VENDOR_ADDRESSES = 1 = offce , 2 = factor, 3 = warehouse
		if (!empty($batch_office))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_ADDRESSES', $batch_office);

		$factory_addr_count	= $var['factory_addr_count'];
		$factory_primary	= strtoupper($var['factory_primary']);

		$batch_factory = array();
		for ($i=1; $i <= $factory_addr_count; $i++)
		{ 
			$factory_addr 			= strtoupper(html_entity_decode($var['factory_addr'.$i]));
			if($factory_addr == null ){
				break;
			}
			$factory_brgy_cm_id 	= null;
			$factory_brgy_cm 		= strtoupper(html_entity_decode($var['factory_brgy_cm'.$i]));
			$factory_state_prov_id	= null;
			$factory_state_prov		= strtoupper(html_entity_decode($var['factory_state_prov'.$i]));
			$factory_zip_code 		= strtoupper($var['factory_zip_code'.$i]);
			$factory_country_id 	= null;
			$factory_country 		= strtoupper(html_entity_decode($var['factory_country'.$i]));
			
			$factory_region_id 		= null;
			$factory_region 		= strtoupper(html_entity_decode($var['factory_region'.$i]));

			if(empty($factory_addr) ||
				(empty($factory_brgy_cm_id) && empty($factory_brgy_cm)) || 
				(empty($factory_state_prov_id) && empty($factory_state_prov)) || 
				(empty($factory_state_prov_id) && empty($factory_state_prov)) || 
				(empty($factory_region_id) && empty($factory_region)) || 
				empty($factory_zip_code)){
					continue;
				}

			if (empty($factory_region_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($factory_region))
				{
					$where = 'upper(REGION_DESC_TWO) = upper(\''.$factory_region.'\')';
					$factory_region_id = $this->common_model->get_from_table_where_array('SMNTP_REGIONS', 'REGION_ID', $where);
				}

				if (empty($factory_region_id) && !empty($factory_region))
				{
					$region_arr = array(
									'REGION_DESC_TWO' 	=> $factory_region,
									'CREATED_BY' 	=> $var['user_id']
								);

					$factory_region_id = $this->registration_model->insert_region($region_arr);
				}
			}
			
			if (empty($factory_brgy_cm_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($factory_brgy_cm))
				{
					$where = "CITY_NAME = '".$factory_brgy_cm."'";
					$factory_brgy_cm_id = $this->common_model->get_from_table_where_array('SMNTP_CITY', 'CITY_ID', $where);
				}

				if (empty($factory_brgy_cm_id) && !empty($factory_brgy_cm))
				{
					$city_arr = array(
									'CITY_NAME' 	=> $factory_brgy_cm,
									'CREATED_BY' 	=> $var['user_id']
								);

					$factory_brgy_cm_id = $this->registration_model->insert_city($city_arr);
				}
			}

			if (empty($factory_state_prov_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($factory_state_prov))
				{
					$where = "STATE_PROV_NAME = '".$factory_state_prov."'";
					$factory_state_prov_id = $this->common_model->get_from_table_where_array('SMNTP_STATE_PROVINCE', 'STATE_PROV_ID', $where);
				}

				if (empty($factory_state_prov_id) && !empty($factory_state_prov))
				{
					$state_arr = array(
									'STATE_PROV_NAME' 	=> $factory_state_prov,
									'CREATED_BY' 		=> $var['user_id']
								);

					$factory_state_prov_id = $this->registration_model->insert_state($state_arr);
				}
			}

			if (empty($factory_country_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($factory_country))
				{
					$where = "COUNTRY_NAME = '".$factory_country."'";
					$factory_country_id = $this->common_model->get_from_table_where_array('SMNTP_COUNTRY', 'COUNTRY_ID', $where);
				}

				if (empty($factory_country_id) && !empty($factory_country))
				{
					$country_arr = array(
									'COUNTRY_NAME' 	=> $factory_country,
									'CREATED_BY' 	=> $var['user_id']
								);

					$factory_country_id = $this->registration_model->insert_country($country_arr);
				}
			}

			if ($i == $factory_primary)
				$primary = 1;
			else
				$primary = 0;

			$batch_factory[] = array(
									'VENDOR_ID'				=> $var['vendor_id'],
									'ADDRESS_TYPE'			=> 2, //1 = offce , 2 = factor, 3 = warehouse
									'ADDRESS_LINE'			=> $factory_addr,
									'BRGY_MUNICIPALITY_ID'	=> $factory_brgy_cm_id,
									'STATE_PROVINCE_ID'		=> $factory_state_prov_id,
									'ZIP_CODE'				=> $factory_zip_code,
									'COUNTRY_ID'			=> $factory_country_id,
									'REGION_ID'			=> $factory_region_id,
									'`PRIMARY`'				=> $primary,
									// 'DATE_CREATED'			=> $var['timestamp'],
									'ACTIVE'				=> 1
								);
		}

		if (!empty($batch_factory))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_ADDRESSES', $batch_factory);
		
		$wh_addr_count	= $var['wh_addr_count'];
		$ware_primary	= $var['ware_primary'];

		$batch_warehouse = array();
		for ($i=1; $i <= $wh_addr_count; $i++)
		{ 
			$ware_addr 		 	= strtoupper(html_entity_decode($var['ware_addr'.$i]));
			if($ware_addr == null ){
				break;
			}
			$ware_brgy_cm_id 	= null;
			$ware_brgy_cm 	 	= strtoupper(html_entity_decode($var['ware_brgy_cm'.$i]));
			$ware_state_prov_id = null;
			$ware_state_prov 	= strtoupper(html_entity_decode($var['ware_state_prov'.$i]));
			$ware_zip_code 	 	= strtoupper($var['ware_zip_code'.$i]);
			$ware_country_id 	= null;
			$ware_country 	 	= strtoupper(html_entity_decode($var['ware_country'.$i]));
			
			$ware_region_id 	= null;
			$ware_region 		= strtoupper(html_entity_decode($var['ware_region'.$i]));

			if(empty($ware_addr) ||
				(empty($ware_brgy_cm_id) && empty($ware_brgy_cm)) || 
				(empty($ware_state_prov_id) && empty($ware_state_prov)) || 
				(empty($ware_region_id) && empty($ware_region)) || 
				empty($ware_zip_code)){
					continue;
				}

			if (empty($ware_region_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($ware_region))
				{
					$where = 'upper(REGION_DESC_TWO) = upper(\''.$ware_region.'\')';
					$ware_region_id = $this->common_model->get_from_table_where_array('SMNTP_REGIONS', 'REGION_ID', $where);
				}

				if (empty($ware_region_id) && !empty($ware_region))
				{
					$region_arr = array(
									'REGION_DESC_TWO' 	=> $ware_region,
									'CREATED_BY' 	=> $var['user_id']
								);

					$ware_region_id = $this->registration_model->insert_region($region_arr);
				}
			}
			
			if (empty($ware_brgy_cm_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($ware_brgy_cm))
				{
					$where = "CITY_NAME = '".$ware_brgy_cm."'";
					$ware_brgy_cm_id = $this->common_model->get_from_table_where_array('SMNTP_CITY', 'CITY_ID', $where);
				}

				if (empty($ware_brgy_cm_id) && !empty($ware_brgy_cm))
				{
					$city_arr = array(
									'CITY_NAME' 	=> $ware_brgy_cm,
									'CREATED_BY' 	=> $var['user_id']
								);

					$ware_brgy_cm_id = $this->registration_model->insert_city($city_arr);
				}
			}

			if (empty($ware_state_prov_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($ware_state_prov))
				{
					$where = "STATE_PROV_NAME = '".$ware_state_prov."'";
					$ware_state_prov_id = $this->common_model->get_from_table_where_array('SMNTP_STATE_PROVINCE', 'STATE_PROV_ID', $where);
				}

				if (empty($ware_state_prov_id) && !empty($ware_state_prov))
				{
					$state_arr = array(
									'STATE_PROV_NAME' 	=> $ware_state_prov,
									'CREATED_BY' 		=> $var['user_id']
								);

					$ware_state_prov_id = $this->registration_model->insert_state($state_arr);
				}
			}

			if (empty($ware_country_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($ware_country))
				{
					$where = "COUNTRY_NAME = '".$ware_country."'";
					$ware_country_id = $this->common_model->get_from_table_where_array('SMNTP_COUNTRY', 'COUNTRY_ID', $where);
				}

				if (empty($ware_country_id) && !empty($ware_country))
				{
					$country_arr = array(
									'COUNTRY_NAME' 	=> $ware_country,
									'CREATED_BY' 	=> $var['user_id']
								);

					$ware_country_id = $this->registration_model->insert_country($country_arr);
				}
			}

			if ($i == $ware_primary)
				$primary = 1;
			else
				$primary = 0;

			$batch_warehouse[] = array(
									'VENDOR_ID'				=> $var['vendor_id'],
									'ADDRESS_TYPE'			=> 3, //1 = offce , 2 = factor, 3 = warehouse
									'ADDRESS_LINE'			=> $ware_addr,
									'BRGY_MUNICIPALITY_ID'	=> $ware_brgy_cm_id,
									'STATE_PROVINCE_ID'		=> $ware_state_prov_id,
									'ZIP_CODE'				=> $ware_zip_code,
									'COUNTRY_ID'			=> $ware_country_id,
									'REGION_ID'			=> $ware_region_id,
									'`PRIMARY`'				=> $primary,
									// 'DATE_CREATED'			=> $var['timestamp'],
									'ACTIVE'				=> 1
								);
		}

		if (!empty($batch_warehouse))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_ADDRESSES', $batch_warehouse);
	}
	
	function save_contact_details($var)
	{
		$telno_count	= $var['telno_count'];

		$telno_batch = array();
		for ($i=1; $i <= $telno_count; $i++)
		{ 
			$tel_ccode 	= $var['tel_ccode'.$i];
			$tel_acode 	= $var['tel_acode'.$i];
			$tel_no 	= $var['tel_no'.$i];
			$tel_elno 	= $var['tel_elno'.$i];

			$telno_batch[] = array(
								'VENDOR_ID'					=> $var['vendor_id'],
								'CONTACT_DETAIL_TYPE'		=> 1, // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
								'CONTACT_DETAIL'			=> $tel_no,
								// 'DATE_CREATED'			=> $var['timestamp'],
								'ACTIVE'					=> 1,
								'COUNTRY_CODE' 				=> $tel_ccode,
								'AREA_CODE' 				=> $tel_acode,
								'EXTENSION_LOCAL_NUMBER' 	=> $tel_elno
							);
		}

		if (!empty($telno_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_CONTACT_DETAILS', $telno_batch);

		$email_count	= $var['email_count'];

		$email_batch = array();
		for ($i=1; $i <= $email_count; $i++)
		{ 
			$email = $var['email'.$i];

			$email_batch[] = array(
								'VENDOR_ID'				=> $var['vendor_id'],
								'CONTACT_DETAIL_TYPE'	=> 4, // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
								'CONTACT_DETAIL'		=> $email,
								// 'DATE_CREATED'			=> $var['timestamp'],
								'ACTIVE'				=> 1
							);
		}

		if (!empty($email_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_CONTACT_DETAILS', $email_batch);

		$faxno_count	= $var['faxno_count'];

		$faxno_batch = array();
		for ($i=1; $i <= $faxno_count; $i++)
		{ 
			$fax_ccode 	= $var['fax_ccode'.$i];
			$fax_acode 	= $var['fax_acode'.$i];
			$fax_no 	= $var['fax_no'.$i];
			$fax_elno 	= $var['fax_elno'.$i];

			$faxno_batch[] = array(
								'VENDOR_ID'					=> $var['vendor_id'],
								'CONTACT_DETAIL_TYPE'		=> 2, // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
								'CONTACT_DETAIL'			=> $fax_no,
								// 'DATE_CREATED'			=> $var['timestamp'],
								'ACTIVE'					=> 1,
								'COUNTRY_CODE' 				=> $fax_ccode,
								'AREA_CODE' 				=> $fax_acode,
								'EXTENSION_LOCAL_NUMBER' 	=> $fax_elno
							);
		}

		if (!empty($faxno_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_CONTACT_DETAILS', $faxno_batch);

		$mobno_count	= $var['mobno_count'];

		$mobno_batch = array();
		for ($i=1; $i <= $mobno_count; $i++)
		{ 
			$mobile_ccode 	= isset ( $var['mobile_ccode'.$i] ) ? $var['mobile_ccode'.$i] : '';
			$mobile_acode 	= isset ( $var['mobile_acode'.$i] ) ? $var['mobile_acode'.$i] : '';
			$mobile_no 		= isset ( $var['mobile_no'.$i] ) ? $var['mobile_no'.$i] : '';
			$mobile_elno 	= isset ( $var['mobile_elno'.$i] ) ? $var['mobile_elno'.$i] : '';

			$mobno_batch[] = array(
								'VENDOR_ID'					=> $var['vendor_id'],
								'CONTACT_DETAIL_TYPE'		=> 3, // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
								'CONTACT_DETAIL'			=> $mobile_no,
								// 'DATE_CREATED'			=> $var['timestamp'],
								'ACTIVE'					=> 1,
								'COUNTRY_CODE' 				=> $mobile_ccode,
								'AREA_CODE' 				=> $mobile_acode,
								'EXTENSION_LOCAL_NUMBER' 	=> $mobile_elno
							);
		}

		if (!empty($mobno_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_CONTACT_DETAILS', $mobno_batch);
	}

	function save_opd($var)
	{
		//Owners/Partners/Directors
		$opd_count	= $var['opd_count'];
		$opd_batch = array();
		for ($i=1; $i <= $opd_count; $i++)
		{ 
			$opd_fname 	= strtoupper($var['opd_fname'.$i]);
			$opd_mname 	= strtoupper($var['opd_mname'.$i]);
			$opd_lname 	= strtoupper($var['opd_lname'.$i]);
			$opd_pos 	= strtoupper($var['opd_pos'.$i]);
			if(isset($var['opd_auth'.$i])){
				$opd_auth = 'Y';
			}else{
				$opd_auth = 'N';
			}

			$opd_name = $opd_lname.', '.$opd_fname.' '.$opd_mname;

			$opd_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'NAME'			=> $opd_name,
								'POSITION'		=> $opd_pos,
								'FIRST_NAME' 	=> $opd_fname,
								'MIDDLE_NAME' 	=> $opd_mname,
								'LAST_NAME' 	=> $opd_lname,
								// 'DATE_CREATED'	=> $var['timestamp'],
								'ACTIVE'		=> 1,
								'AUTH_SIG'		=> $opd_auth
							);
		}

		if (!empty($opd_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_OWNERS', $opd_batch);
	}

	function save_authrep($var)
	{
		//Authorized Representatives
		$authrep_count	= $var['authrep_count'];

		$authrep_batch = array();
		for ($i=1; $i <= $authrep_count; $i++)
		{ 
			$authrep_fname 	= strtoupper($var['authrep_fname'.$i]);
			$authrep_mname 	= strtoupper($var['authrep_mname'.$i]);
			$authrep_lname 	= strtoupper($var['authrep_lname'.$i]);
			$authrep_pos 	= strtoupper($var['authrep_pos'.$i]);
			
			if(isset($var['authrep_auth'.$i])){
				$authrep_auth = 'Y';
			}else{
				$authrep_auth = 'N';
			}

			$authrep_name = $authrep_lname.', '.$authrep_fname.' '.$authrep_mname;

			$authrep_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'NAME'			=> $authrep_name,
								'POSITION'		=> $authrep_pos,
								'FIRST_NAME' 	=> $authrep_fname,
								'MIDDLE_NAME' 	=> $authrep_mname,
								'LAST_NAME' 	=> $authrep_lname,
								// 'DATE_CREATED'	=> $var['timestamp'],
								'ACTIVE'		=> 1,
								'AUTH_SIG'		=> $authrep_auth
							);
		}

		if (!empty($authrep_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_REP', $authrep_batch);
	}

	function save_bankrep($var)
	{
		//Bank References
		$bankrep_count	= $var['bankrep_count'];

		$bankrep_batch = array();
		for ($i=1; $i <= $bankrep_count; $i++)
		{ 
			$bankrep_name 	= strtoupper($var['bankrep_name'.$i]);
			$bankrep_branch = strtoupper($var['bankrep_branch'.$i]);

			$bankrep_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'BANK_NAME'		=> $bankrep_name,
								'BANK_BRANCH'	=> $bankrep_branch
							);
		}

		if (!empty($bankrep_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_BANK', $bankrep_batch);
	}

	function save_retcust($var)
	{
		//Other Retail Customers/Clients
		$orcc_count	= $var['orcc_count'];

		$orcc_batch = array();
		for ($i=1; $i <= $orcc_count; $i++)
		{ 
			$orcc_compname 	= strtoupper($var['orcc_compname'.$i]);

			$orcc_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'COMPANY_NAME'	=> $orcc_compname,
								// 'DATE_CREATED'	=> $var['timestamp']
							);
		}

		if (!empty($orcc_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_OTHER_RETCUST', $orcc_batch);
	}

	function save_otherbusiness($var)
	{
		//Other Business
		$otherbusiness_count	= $var['otherbusiness_count'];

		$otherbusiness_batch = array();
		for ($i=1; $i <= $otherbusiness_count; $i++)
		{ 
			$ob_compname 	= strtoupper($var['ob_compname'.$i]);
			$ob_pso 		= strtoupper($var['ob_pso'.$i]);

			$otherbusiness_batch[] = array(
								'VENDOR_ID'			=> $var['vendor_id'],
								'COMPANY_NAME'		=> $ob_compname,
								'SERVICE_OFFERED'	=> $ob_pso,
								// 'DATE_CREATED'		=> $var['timestamp']
							);
		}

		if (!empty($otherbusiness_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_OTHER_BUSINESS', $otherbusiness_batch);
	}

	function save_relativeaffiliates($var)
	{
		//Disclosure of Relatives Working in SM or its Affiliates
		$affiliates_count	= $var['affiliates_count'];

		$affiliate_batch = array();
		for ($i=1; $i <= $affiliates_count; $i++)
		{ 
			$affiliates_fname 		= strtoupper($var['affiliates_fname'.$i]);
			$affiliates_lname 		= strtoupper($var['affiliates_lname'.$i]);
			$affiliates_pos 		= strtoupper($var['affiliates_pos'.$i]);
			$affiliates_comp_afw 	= strtoupper($var['affiliates_comp_afw'.$i]);
			$affiliates_rel 		= strtoupper($var['affiliates_rel'.$i]);

			$affiliate_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'FIRST_NAME'	=> $affiliates_fname,
								'LAST_NAME'		=> $affiliates_lname,
								'POSITION'		=> $affiliates_pos,
								'COMPANY'		=> $affiliates_comp_afw,
								'RELATIONSHIP'	=> $affiliates_rel,
								// 'DATE_CREATED'	=> $var['timestamp']
							);
		}

		if (!empty($affiliate_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_RELATIVES', $affiliate_batch);
	}
	
	function delete_incrementing_tables($var) // delete to save new post
	{
		$where_vendor_arr = array('VENDOR_ID' => $var['vendor_id']);

		$this->registration_model->delete_table('SMNTP_VENDOR_BRAND', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_ADDRESSES', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_CONTACT_DETAILS', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_OWNERS', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_REP', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_BANK', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_OTHER_RETCUST', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_OTHER_BUSINESS', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_RELATIVES', $where_vendor_arr);
		//$this->registration_model->delete_table('SMNTP_VENDOR_REQUIRED_DOC', $where_vendor_arr);
		//$this->registration_model->delete_table('SMNTP_VENDOR_AGREEMENTS', $where_vendor_arr);
	}
	
	
	protected function send_completed_portal_message($data = array()){
		$portalvendor = $this->common_model->get_message_default($data['where'])->result_array();
		$portalvendor[0]['SUBJECT'] = str_replace('[vendorname]', $data['vendorname'], $portalvendor[0]['SUBJECT']);
		$portalvendor[0]['TOPIC'] = str_replace('[vendorname]', $data['vendorname'], $portalvendor[0]['TOPIC']);
		$portalvendor[0]['MESSAGE'] = str_replace('[vendorname]', $data['vendorname'], $portalvendor[0]['MESSAGE']);
		$portalvendor[0]['MESSAGE'] = str_replace('[creator]', $data['creatorname'], $portalvendor[0]['MESSAGE']);

		$insert_array = array(
			'SUBJECT' 		=> $portalvendor[0]['SUBJECT'],
			'TOPIC' 		=> $portalvendor[0]['TOPIC'],
			'DATE_SENT' 	=> date('Y-m-d H:i:s'),
			'BODY' 			=> $portalvendor[0]['MESSAGE'],
			'TYPE' 			=> 'notification',
			'SENDER_ID' 	=> 0,//notif
			'RECIPIENT_ID' 	=> $data['recipient_id'], //can be changed in query
			'VENDOR_ID' 	=> $data['vendor_id']
		);

		$model_data = $this->mail_model->send_message($insert_array);
	}
	
	protected function send_completed_email_message($data = array()){
		$em = $this->common_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => $data['template_type']),'CONTENT');
		$em[0]['CONTENT'] = str_replace('[vendorname]', $data['vendorname'], $em[0]['CONTENT']);
		$em[0]['CONTENT'] = str_replace('[creator]', $data['creatorname'], $em[0]['CONTENT']);

		$email_data['subject'] = $data['subject'];
		$email_data['content'] = nl2br($em[0]['CONTENT']);
		$email_data['to'] = $data['email']; 
		$this->common_model->send_email_notification($email_data);
	}
}
?>