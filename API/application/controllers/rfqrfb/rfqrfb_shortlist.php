<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Rfqrfb_shortlist extends REST_Controller {
	
			public function __construct() {
			parent::__construct();
			$this->load->model('rfqrfb_shortlist_model');
			$this->load->model('common_model');
		}

		public function details_get()
		{


			$data = array(
			'RFQRFB_ID' => $this->get('RFQRFB_ID'),
			'CREATED_BY' =>$this->get('CREATED_BY')	
			) ;


			$res = $this->rfqrfb_shortlist_model->rfq_getdetails($data);	


			$this->response(
			$res
			);

		}

		public function getline_get()
		{


			$arr = array(
				'CREATED_BY' => $this->get('CREATED_BY'),
				'RFQ_RFB_ID' => $this->get('RFQ_RFB_ID')
				);

			$result = $this->rfqrfb_shortlist_model->get_line($arr);
			
			$this->response($result);
			
		
		}

		public function getparticipants_get()
		{

			$arr = array(
					'CREATED_BY' => $this->get('CREATED_BY'),
					'RFQ_RFB_ID' => $this->get('RFQ_RFB_ID')
					);

			$result = $this->rfqrfb_shortlist_model->get_participants($arr);
			
				//$result = $this->rfqrfb_shortlist_model->count_distinct($arr);		
				
			$this->response($result);
				




		}

		public function save_shortlisted_put()
		{
			$user_id		= $this->put('user_id');
			$position_id	= $this->put('position_id');
			$rfq_id			= $this->put('rfq_id');
			$shortlist_arr 	= $this->put('chk_shortlist');
			$shortlist_arr_h 	= $this->put('chk_shortlist_h');
			$action 		= $this->put('action'); //1 = submt , 2 = failed bid
			$remarks 		= $this->put('remarks');
			$send_notif 	= false;
			$notif_data 	= array();

			if ($action == 1) // if submitted update checkbox selected to shortlisted = 1
			{
				if (!empty($shortlist_arr))
				{
					$shortlist 	= implode(', ',$shortlist_arr);
					$record_arr = array('SHORTLISTED' => 1);
					$where 		= 'RESPONSE_QUOTE_ID IN ('.$shortlist.')';
					$this->common_model->update_table('SMNTP_RFQRFB_RESPONSE_QUOTE', $record_arr, $where);					
				}
				if (!empty($shortlist_arr_h))
				{
					$shortlist_h 	= implode(', ',$shortlist_arr_h);
					$record_arr = array('SHORTLISTED' => 1);
					$where 		= 'QUOTE_NO IN ('.$shortlist_h.')';
					$this->common_model->update_table('SMNTP_RFQRFB_LINE_BOM_COST', $record_arr, $where);					
				}
			}

			$where_arr 	= array('RFQRFB_ID' => $rfq_id);
			$status_id  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB_STATUS', 'STATUS_ID', $where_arr);

			if ($action == 1)
				$common_action = 3; // its like approve
			else
				$common_action = 4; // its like reject

			$data = array(
						'status' 		=> $status_id,
						'position_id' 	=> $position_id,
						'type'			=> 2,// rfq = 2
						'action'		=> $common_action
					);

			$data = $this->common_model->get_next_process($data);

			// update rfqrfb status
			$record_arr = array(
							'STATUS_ID'			=> $data['next_status'],
							'POSITION_ID'		=> $data['next_position']
						);
			if ($action == 2) // if failed bid add remakrs
				$record_arr['APPROVER_REMARKS'] = $remarks;

			$this->common_model->update_table('SMNTP_RFQRFB_STATUS', $record_arr, $where_arr); //reused $where_arr ^

			//logs/approval history ======================================

			$this->db->select('RFQRFB_STATUS_ID, EXTENSION_DATE, APPROVER_REMARKS');
			$this->db->from('SMNTP_RFQRFB_STATUS');
			$this->db->where('RFQRFB_ID', $rfq_id);

			$query = $this->db->get();

			$rfq_status_id = $query->row()->RFQRFB_STATUS_ID;
			$extension_date = $query->row()->EXTENSION_DATE;
			$approver_remarks = $query->row()->APPROVER_REMARKS;

			$logs_insert = array(
									'RFQRFB_STATUS_ID'		=> $rfq_status_id,
									'RFQRFB_ID'				=> $rfq_id,
									'STATUS_ID' 			=> $data['next_status'],
									'POSITION_ID' 			=> $data['next_position'],
									'APPROVER_REMARKS'		=> $approver_remarks,
									'APPROVER_ID'			=> $user_id,
									'DATE_UPDATED'			=> date("d-M-Y"),
									'EXTENSION_DATE'		=> $extension_date

									);

			$this->db->insert('SMNTP_RFQRFB_STATUS_LOGS', $logs_insert);

			//=============================================================

			########################### Notif data START ########################
			$where_arr_def = array(
							'TYPE_ID' 		=> 3, // for registration
							'STATUS_ID' 	=> 999 // generic for approval ayon kay topher
						);

			$rs_msg = $this->common_model->get_message_default($where_arr_def);

			if ($rs_msg->num_rows() > 0)
			{
				$row = $rs_msg->row();

				if ($action == 1) // send for shortlist approval
				{
					$send_notif = true; // send message
					$action_name = 'Shortlist';
				}
				else if ($action == 2) // send for failed bid approval
				{
					$send_notif = true; // send message
					$action_name = 'Failed Bid';
				}

				$where_arr 	= array('RFQRFB_ID' => $rfq_id);
				$rfq_title  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'TITLE', $where_arr);

				// get recipient id here for message
				$where_arr = array(
									'USER_ID' => $user_id
								);
				$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', 'GHEAD_ID', $where_arr);

				$where_arr = array(
								'USER_ID' => $recipient_id
							);
				$approvername 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_FIRST_NAME || \' \' || USER_MIDDLE_NAME || \' \' || USER_LAST_NAME AS FULLNAME', $where_arr, 'FULLNAME');
				$approver_posid 	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
				$approver_posname 	= $this->common_model->get_position_name($approver_posid);
				$approvername 		= $approvername.' ('.$approver_posname.')';

				$where_arr = array(
								'USER_ID' => $user_id
							);
				$sendername 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_FIRST_NAME || \' \' || USER_MIDDLE_NAME || \' \' || USER_LAST_NAME AS FULLNAME', $where_arr, 'FULLNAME');
				$sender_posid 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
				$sender_posname 	= $this->common_model->get_position_name($sender_posid);
				$sendername 		= $sendername.' ('.$sender_posname.')';

				$message	= $row->MESSAGE;
				$message	= str_replace('[approvername]', $approvername, $message);
				$message	= str_replace('[sendername]', $sendername, $message);
				$message	= str_replace('[action]', $action_name, $message);

				$notif_data['subject'] 		= $rfq_id.' - '.$rfq_title;
				$notif_data['topic'] 		= $rfq_id.' - '.$rfq_title.' Approval';
				$notif_data['message'] 		= $message;
				$notif_data['rfqrfb_id'] 	= $rfq_id;
				$notif_data['recipient_id'] = $recipient_id;
			}

			$data['send_notif'] = $send_notif;
			$data['notif_data'] = $notif_data;
			########################### Notif data END ##########################

			$this->response($data);			
		}

		function approve_reject_shortlisted_put() // just update smntp_rfqrfb_status
		{	
			$user_id		= $this->put('user_id');
			$position_id	= $this->put('position_id');
			$rfq_id			= $this->put('rfq_id');
			$action 		= $this->put('action'); //3 = Approve , 4 = Reject
			$shortlist_arr 	= $this->put('chk_shortlist');
			$shortlist_h_arr 	= $this->put('chk_shortlist_h');
			$remarks 		= $this->put('remarks');
			$send_notif 	= false;
			$notif_data 	= array();
			$recipients		= array();

			$sent_notif_buyer = false;
			$notif_data_buyer = array();

			if ($action == 4) // if rejected shorlisted set to 0
			{
				if (!empty($shortlist_arr))
				{
					$shortlist 	= implode(', ',$shortlist_arr);
					$record_arr = array('SHORTLISTED' => 0);
					$where 		= 'RESPONSE_QUOTE_ID IN ('.$shortlist.')';
					$this->common_model->update_table('SMNTP_RFQRFB_RESPONSE_QUOTE', $record_arr, $where);
				}
			}
			elseif($action == 3) // approve
			{
				$rs = $this->rfqrfb_shortlist_model->get_notshortlisted($rfq_id);
				foreach ($rs as $row)
				{
					$where_arr_invstat 	= array(
													'RFQRFB_ID' => $row['RFQRFB_ID'],
													'INVITE_ID' => $row['INVITE_ID']
											);

					// update rfqrfb_invite_status
					$record_arr = array(
									'STATUS_ID'			=> 109,// notshortlisted
									'POSITION_ID'		=> 10
								);
					$this->common_model->update_table('SMNTP_RFQRFB_INVITE_STATUS', $record_arr, $where_arr_invstat);
				}

				if (!empty($shortlist_arr))
				{
					foreach ($shortlist_arr as $qoute_id)
					{
						$where_arr_qouteid 	= array('RESPONSE_QUOTE_ID' => $qoute_id);
						$invite_id  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB_RESPONSE_QUOTE', 'INVITE_ID', $where_arr_qouteid);
						
						$where_arr_invstat 	= array(
													'RFQRFB_ID' => $rfq_id,
													'INVITE_ID' => $invite_id
											);

						$data = array(
									'status' 		=> 105, // status for shortlisting
									'position_id' 	=> 10, // automatic vendor lagi as per sir joey
									'type'			=> 3
								);

						$data = $this->common_model->get_next_process($data);

						// update rfqrfb_invite_status
						$record_arr = array(
										'STATUS_ID'			=> $data['next_status'],
										'POSITION_ID'		=> $data['next_position']
									);
						$this->common_model->update_table('SMNTP_RFQRFB_INVITE_STATUS', $record_arr, $where_arr_invstat);

						// $send_notif = true;
						// $notif_data = $this->send_email_shortlisted($invite_id, $rfq_id);
						
						// $where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
						// $recipients[] = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', $where_arr);
					}
				}
			}


			$where_arr 	= array('RFQRFB_ID' => $rfq_id);
			$status_id  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB_STATUS', 'STATUS_ID', $where_arr);

			$data = array(
						'status' 		=> $status_id,
						'position_id' 	=> $position_id,
						'type'			=> 2,// rfq = 2
						'action'		=> $action
					);

			$data = $this->common_model->get_next_process($data);

			// update rfqrfb status
			$record_arr = array(
							'STATUS_ID'			=> $data['next_status'],
							'POSITION_ID'		=> $data['next_position']
						);

			if ($action == 4) // rejected
			$record_arr['APPROVER_REMARKS'] = $remarks;
			

			$this->common_model->update_table('SMNTP_RFQRFB_STATUS', $record_arr, $where_arr); //reused $where_arr ^

			//logs/approval history ======================================

			$this->db->select('RFQRFB_STATUS_ID, EXTENSION_DATE, APPROVER_REMARKS');
			$this->db->from('SMNTP_RFQRFB_STATUS');
			$this->db->where('RFQRFB_ID', $rfq_id);

			$query = $this->db->get();

			$rfq_status_id = $query->row()->RFQRFB_STATUS_ID;
			$extension_date = $query->row()->EXTENSION_DATE;
			$approver_remarks = $query->row()->APPROVER_REMARKS;

			$logs_insert = array(
									'RFQRFB_STATUS_ID'		=> $rfq_status_id,
									'RFQRFB_ID'				=> $rfq_id,
									'STATUS_ID' 			=> $data['next_status'],
									'POSITION_ID' 			=> $data['next_position'],
									'APPROVER_REMARKS'		=> $approver_remarks,
									'APPROVER_ID'			=> $user_id,
									'DATE_UPDATED'			=> date("d-M-Y"),
									'EXTENSION_DATE'		=> $extension_date

									);

			$this->db->insert('SMNTP_RFQRFB_STATUS_LOGS', $logs_insert);

			//=============================================================

			########################### Notif data Buyer START ########################
			$where_arr_def = array(
							'TYPE_ID' 		=> 3, // for registration
							'STATUS_ID' 	=> 22 // generic for request action ayon kay topher
						);

			$rs_msg = $this->common_model->get_message_default($where_arr_def);
			if ($rs_msg->num_rows() > 0)
			{
				$row = $rs_msg->row();

				if ($action == 3) //approved
				{
					$sent_notif_buyer = true;
					$action_name = 'Approved';
				}
				else if ($action == 4) // rejected
				{
					$sent_notif_buyer = true;
					$action_name = 'Rejected';
				}

				$where_arr 		= array('RFQRFB_ID' => $rfq_id);
				$rfq_title  	= $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'TITLE', $where_arr);
				$recipient_id  	= $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'CREATED_BY', $where_arr);

				$where_arr = array(
								'USER_ID' => $recipient_id
							);
				$approvername 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_FIRST_NAME || \' \' || USER_MIDDLE_NAME || \' \' || USER_LAST_NAME AS FULLNAME', $where_arr, 'FULLNAME');
				$approver_posid 	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
				$approver_posname 	= $this->common_model->get_position_name($approver_posid);
				$approvername 		= $approvername.' ('.$approver_posname.')'; // the buyer


				$message	= $row->MESSAGE;
				$message	= str_replace('[buyername]', $approvername, $message);
				$message	= str_replace('[request]', 'Shortlist', $message);
				$message	= str_replace('[action]', $action_name, $message);

				$notif_data_buyer['subject'] 		= $rfq_id.' - '.$rfq_title;
				$notif_data_buyer['topic'] 			= $rfq_id.' - '.$rfq_title.' Approval';
				$notif_data_buyer['message'] 		= $message;
				$notif_data_buyer['rfqrfb_id'] 		= $rfq_id;
				$notif_data_buyer['recipient_id'] 	= $recipient_id;
			}
			########################### Notif data Buyer END ##########################
			
			$data['send_notif'] = $send_notif;
			$data['notif_data'] = $notif_data;
			$data['recipients'] = implode("|",$recipients);;

			$data['sent_notif_buyer'] = $sent_notif_buyer;
			$data['notif_data_buyer'] = $notif_data_buyer;
			
			$this->response($data);			
		}

		function send_email_shortlisted($invite_id, $rfq_id) // send email to vendor
		{
			$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
			$email_addr  = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'EMAIL', $where_arr);

			$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
			$vendor_name  = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

			$where_arr 	= array('RFQRFB_ID' => $rfq_id);
			$rfq_title  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'TITLE', $where_arr);

			$var = array(
						'TEMPLATE_TYPE' => 13,
						'ACTIVE' 		=> 1
					);

			$rs_email = $this->common_model->get_email_template($var);
			$message = $rs_email->row()->CONTENT;

			$message = str_replace('[vendor_name]', $vendor_name, $message);
			$message = str_replace('[RFQ_ID]', $rfq_id, $message);
			$message = str_replace('[RFQ_TITLE]', $rfq_title, $message);

			$email_data['to'] 		= $email_addr;
			//$email_data['bcc']  	= 'ernesto.gempisao@sandmansystems.com';
			$email_data['subject'] 	= $rfq_id.' - '.$rfq_title;
			$email_data['content'] 	= nl2br($message);
			$this->common_model->send_email_notification($email_data);

			$data['subject'] 	= $email_data['subject'];
			$data['topic'] 		= $rfq_id.' - '.$rfq_title.' Approval';
			$data['message'] 	= $message;
			$data['rfqrfb_id'] 	= $rfq_id;

			return $data;
		}


}
?>