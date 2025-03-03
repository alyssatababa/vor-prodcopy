<?Php defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Rfq_rfb_awarding_app extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfq_awarding_model');
			$this->load->model('common_model');
			$this->load->model('mail_model');
		}

		public function get_rfq_details_get()
		{
			$arr = array(
				'CREATED_BY' => $this->get('CREATED_BY'),
				'RFQ_RFB_ID' => $this->get('RFQ_RFB_ID')
				);

			$result = $this->rfq_awarding_model->get_rfq_details($arr);
			
			$this->response($result);
			
		}

		public function get_line_details_get()
		{
			$arr = array(
				'CREATED_BY' => $this->get('CREATED_BY'),
				'RFQ_RFB_ID' => $this->get('RFQ_RFB_ID')
				);
			//if(!empty($this->get('dropdown'))){
			//	$arr['DROPDOWN'] = $this->get('dropdown');
			//}
			$result = $this->rfq_awarding_model->get_line($arr);		
			
			$this->response($result);
			
		}

		public function to_failed_put()
		{
			$data = array(
				'CREATED_BY' => $this->put('CREATED_BY'),
				'RFQ_RFB_ID' =>$this->put('RFQ_RFB_ID')
				);

			$this->response($data);
		}

		public function getparticipants_get()
		{

			$arr = array(
					'CREATED_BY' => $this->get('CREATED_BY'),
					'RFQ_RFB_ID' => $this->get('RFQ_RFB_ID')
					);

			$result = $this->rfq_awarding_model->get_participants($arr);	
			// $this->rest_app->debug();
				//$result = $this->rfqrfb_shortlist_model->count_distinct($arr);		
			//$result['len'] = strlen(json_encode($result));
			$this->response($result);
		}

		public function vendor_list_get()
		{

		$rs =$this->rfq_awarding_model->select_vendors($this->get('rfq'));

		$this->response($rs);
		}

		public function getpodetails_get()
		{

			$arr = array(
					'CREATED_BY' => $this->get('CREATED_BY'),
					'RFQ_RFB_ID' => $this->get('RFQ_RFB_ID')
			);

			$result = $this->rfq_awarding_model->get_podetails($arr);	
			// $this->rest_app->debug();
				//$result = $this->rfqrfb_shortlist_model->count_distinct($arr);		
				
			$this->response($result);
		}

		public function version_list_get()
		{
			$var['qoute_id'] 	= $this->get('qoute_id');
			$var['order_list'] 	= $this->get('order_list');

			$rs = $this->rfq_awarding_model->get_version_list($var);

			$this->response($rs);
		}

		public function save_award_put()
		{
			$real_q_id = explode("|", $this->put('ins'));
			$user_id		= $this->put('user_id');
			$position_id	= $this->put('position_id');
			$rfq_id			= $this->put('rfq_id');
			$action 		= $this->put('action'); //1 = submt , 2 = failed bid
			$remarks		= $this->put('remarks');
			$send_notif 	= false;
			$notif_data 	= array();

			$arr = array(
				'CREATED_BY' => $user_id,
				'RFQ_RFB_ID' => $rfq_id
				);

			$result = $this->rfq_awarding_model->get_line($arr);	
	

			if ($action == 1) // if submitted update checkbox selected to awarded = 1
			{
				for ($i=0; $i < count($result); $i++)
				{ 
					$m=0;
					for($m=0;$m<count($real_q_id)-1;$m++){

					$qoute_id = $real_q_id[$m];
					$record_arr = array('AWARDED' => 1);
					$where_arr 		= array('RESPONSE_QUOTE_ID' => $qoute_id);
					$this->common_model->update_table('SMNTP_RFQRFB_RESPONSE_QUOTE', $record_arr, $where_arr);	


					}				
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
							'TYPE_ID' 		=> 3, 
							'STATUS_ID' 	=> 999 // generic for approval ayon kay topher
						);

			$rs_msg = $this->common_model->get_message_default($where_arr_def);

			if ($rs_msg->num_rows() > 0)
			{
				$row = $rs_msg->row();

				if ($action == 1) // send for award approval
				{
					$send_notif = true; // send message
					$action_name = 'Award';
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

		function approve_reject_awarded_put()
		{
			$user_id		= $this->put('user_id');
			$position_id	= $this->put('position_id');
			$rfq_id			= $this->put('rfq_id');
			$action 		= $this->put('action'); //3 = Approve , 4 = Reject
			$remarks		= $this->put('remarks');
			$send_notif		= false;
			$notif_data		= array();

			$send_notif_response = false;
			$notif_data_response = array();

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

			

			$arr = array(
				'CREATED_BY' => $user_id,
				'RFQ_RFB_ID' => $rfq_id
				);

			$result = $this->rfq_awarding_model->get_line($arr);

			if ($action == 4)
			{
				for ($i=0; $i < count($result); $i++)
				{ 
					$qoute_id = $this->put('rad_option'.$i);
					$record_arr = array('AWARDED' => 0);
					$where_arr 	= array('RESPONSE_QUOTE_ID' => $qoute_id);
					$this->common_model->update_table('SMNTP_RFQRFB_RESPONSE_QUOTE', $record_arr, $where_arr);

					$where_arr_qouteid 	= array('RESPONSE_QUOTE_ID' => $qoute_id);
					$invite_id  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB_RESPONSE_QUOTE', 'INVITE_ID', $where_arr_qouteid);

					// $rs_email = $this->send_email_award($invite_id, $rfq_id, $action);
					// $send_notif = true;
					// $notif_data = $rs_email;
					// $notif_data['recipient_id'][] = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', ['VENDOR_INVITE_ID' => $invite_id]);

				}
			}
			elseif($action == 3) // approve
			{
				if ($data['last_cycle_approver'] == 1) // if its last approver update SMNTP_RFQRFB_INVITE_STATUS
				{
					// update unawarded first . pra pag may dalawang man na record at na unaward ung isa tapos awarded ung isa mauupdate na award sa next loop
					$rs = $this->rfq_awarding_model->get_unawarded($rfq_id);
					foreach ($rs as $row) 
					{
						$var = array(
										'status' 		=> 181, // hardcoded as per sir joey :D
										'position_id' 	=> 10, // automatic vendor lagi as per sir joey
										'type'			=> 3,
										'action'		=> 4 // treat as reject
									);

						$var = $this->common_model->get_next_process($var);					

						$where_arr_invstat 	= array(
														'RFQRFB_ID' => $row['RFQRFB_ID'],
														'INVITE_ID' => $row['INVITE_ID']
												);

						// update rfqrfb_invite_status
						$record_arr = array(
										'STATUS_ID'			=> $var['next_status'],
										'POSITION_ID'		=> $var['next_position']
									);
						$this->common_model->update_table('SMNTP_RFQRFB_INVITE_STATUS', $record_arr, $where_arr_invstat);
					}

					for ($i=0; $i < count($result); $i++)
					{ 
						$qoute_id = $this->put('rad_option'.$i);
						$where_arr_qouteid 	= array('RESPONSE_QUOTE_ID' => $qoute_id);
						$invite_id  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB_RESPONSE_QUOTE', 'INVITE_ID', $where_arr_qouteid);

						$where_arr_invstat 	= array(
														'RFQRFB_ID' => $rfq_id,
														'INVITE_ID' => $invite_id
												);
						$var = array(
										'status' 		=> 181, // hardcoded as per sir joey :D
										'position_id' 	=> 10, // automatic vendor lagi as per sir joey
										'type'			=> 3,
										'action'		=> $action
									);

						$var = $this->common_model->get_next_process($var);

						// update rfqrfb_invite_status
						$record_arr = array(
										'STATUS_ID'			=> $var['next_status'],
										'POSITION_ID'		=> $var['next_position']
									);
						$this->common_model->update_table('SMNTP_RFQRFB_INVITE_STATUS', $record_arr, $where_arr_invstat);

						// $rs_email = $this->send_email_award($invite_id, $rfq_id, $action);
						// $send_notif = true;
						// $notif_data = $rs_email;
						// $notif_data['recipient_id'][] = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', ['VENDOR_INVITE_ID' => $invite_id]);
					}
				}
				else // send notif to the next approver
				{
					$rfq_creator_id = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'CREATED_BY', ['RFQRFB_ID' => $rfq_id]);
					// get recipient id here for message
					$where_arr = array(
										'USER_ID' => $rfq_creator_id 
									);
					$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', 'FASHEAD_ID', $where_arr);
					$email_addr = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', ['USER_ID' => $recipient_id]);
					
					$where_arr 	= array('RFQRFB_ID' => $rfq_id);
					$rfq_title  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'TITLE', $where_arr);

					$var = array(
							'TEMPLATE_TYPE' => 14,
							'ACTIVE' 		=> 1
						);

					$rs_email = $this->common_model->get_email_template($var);
					$message = $rs_email->row()->CONTENT;

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

					$message = str_replace('[recipient_name]', $approvername, $message);
					$message = str_replace('[sender_name]', $sendername, $message);
					$message = str_replace('[RFQ_ID]', $rfq_id, $message);
					$message = str_replace('[RFQ_TITLE]', $rfq_title, $message);

					$email_data['to'] 		= $email_addr;
					//$email_data['bcc']  	= 'ernesto.gempisao@sandmansystems.com';
					$email_data['subject'] 	= $rfq_id.' - '.$rfq_title;
					$email_data['content'] 	= nl2br($message);
					$this->common_model->send_email_notification($email_data);

					$send_notif = true;
					$notif_data['subject'] 		= $email_data['subject'];
					$notif_data['topic'] 		= $rfq_id.' - '.$rfq_title.' Approval';
					$notif_data['message'] 		= $message;
					$notif_data['rfqrfb_id'] 	= $rfq_id;
					$notif_data['recipient_id'] = $recipient_id;
				}
			}

			$data['send_notif'] = $send_notif;
			$data['notif_data'] = $notif_data;

			################################# NOTIF FOR GHead and Buyer Start ###########################################
			$where_arr_def = array(
							'TYPE_ID' 		=> 3,
							'STATUS_ID' 	=> 22 // generic for request action ayon kay topher
						);

			$rs_msg = $this->common_model->get_message_default($where_arr_def);

			if ($rs_msg->num_rows() > 0)
			{
				$row = $rs_msg->row();

				if ($action == 3)
				{
					$send_notif_response = true;
					$action_name = 'Approved';					
				}
				else if ($action == 4)
				{
					$send_notif_response = true;
					$action_name = 'Rejected';
				}

				$where_arr 		= array('RFQRFB_ID' => $rfq_id);
				$rfq_title  	= $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'TITLE', $where_arr);
				$creator_id  	= $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'CREATED_BY', $where_arr); // buyer
				$recipients 	= $creator_id;

				// get another recipient 
				if ($data['last_cycle_approver'] == 1)
				{
					$where_arr = array(
									'USER_ID' => $creator_id
								);
					$ghead_id = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', 'GHEAD_ID', $where_arr);
					$recipients = $creator_id.'|'.$ghead_id;

					$pos_name 		= $this->common_model->get_position_name($position_id);
					$action_name   .= ' by '.$pos_name;
				}
				else
				{
					$pos_name 		= $this->common_model->get_position_name($position_id);
					$action_name   .= ' by '.$pos_name;
				}

				$message	= $row->MESSAGE;
				$message	= str_replace('[buyername]', '', $message);
				$message	= str_replace('[request]', 'Award', $message);
				$message	= str_replace('[action]', $action_name, $message);

				$notif_data_response['subject'] 		= $rfq_id.' - '.$rfq_title;
				$notif_data_response['topic'] 			= $rfq_id.' - '.$rfq_title.' Approval';
				$notif_data_response['message'] 		= $message;
				$notif_data_response['rfqrfb_id'] 		= $rfq_id;
				$notif_data_response['recipient_id'] 	= $recipients;
			}	

			$data['send_notif_response'] = $send_notif_response;
			$data['notif_data_response'] = $notif_data_response;		
			################################# NOTIF FOR GHead and Buyer END   ###########################################

			$this->response($data);
		}

		function send_email_award($invite_id, $rfq_id, $action)
		{
			$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
			$email_addr  = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'EMAIL', $where_arr);

			$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
			$vendor_name  = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

			$where_arr 	= array('RFQRFB_ID' => $rfq_id);
			$rfq_title  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'TITLE', $where_arr);

			if ($action == 3) // approve
				$template_type = 8;
			elseif ($action == 4) // reject
				$template_type = 15;

			$var = array(
						'TEMPLATE_TYPE' => $template_type,
						'ACTIVE' 		=> 1
					);

			$rs_email = $this->common_model->get_email_template($var);
			$message = $rs_email->row()->CONTENT;

			$message = str_replace('[vendor_name]', $vendor_name, $message);
			$message = str_replace('[RFQ_ID]', $rfq_id, $message);
			$message = str_replace('[RFQ_TITLE]', $rfq_title, $message);

			$email_data['to'] 		= $email_addr;
			//$email_data['bcc']  	= 'ernesto.gempisao@sandmansystems.com';
			$email_data['subject'] 	= $rfq_id.' - '.$rfq_title;;
			$email_data['content'] 	= nl2br($message);
			// $this->common_model->send_email_notification($email_data);

			$data['subject'] 	= $email_data['subject'];
			$data['topic'] 		= $rfq_id.' - '.$rfq_title.' Approval';
			$data['message'] 	= $message;
			$data['rfqrfb_id'] 	= $rfq_id;

			return $data;
		}

		function save_po_details_post()
		{
			$user_id		= $this->post('user_id');
			$position_id	= $this->post('position_id');
			$rfq_id			= $this->post('rfq_id');
			$line_arr 		= $this->post('line_id'); // array of line_id 

			//Disable PO Details
			$this->rfq_awarding_model->disable_podetails($rfq_id);
			$check_if_email = count($this->rfq_awarding_model->select_query('SMNTP_RFQRFB_PO_DETAILS',array('RFQRFB_ID' => $rfq_id),'PO_NUMBER'));
			foreach ($line_arr as $key => $line_id)
			{
				$pod_count = $this->post('pod_count_'.$line_id); //get count of row table per line

				for ($i=1; $i <= $pod_count; $i++)
				{ 
					$company_operating_unit	= $this->post('cou_'.$line_id.'_'.$i);
					$po_number 				= $this->post('pon_'.$line_id.'_'.$i);
					$negotiated_amount 		= $this->post('negam_'.$line_id.'_'.$i);
					$quantity 				= $this->post('quantity_'.$line_id.'_'.$i);
					$vendor_id  			= $this->post('slt_'.$line_id.'_'.$i);
					// $date_updated 			= $this->post('date_upd_'.$line_id.'_'.$i);
					// $updated_by 			= $this->post('update_by_'.$line_id.'_'.$i);

					if(!empty($po_number)){
						$save_pod_arr =	[
											'RFQRFB_ID' 	=> $rfq_id,
											'LINE_ID' 		=> $line_id,
											'COMPANY' 		=> $company_operating_unit,
											'PO_NUMBER' 	=> $po_number,
											'NEGO_AMOUNT' 	=> $negotiated_amount,
											'QUANTITY' 		=> $quantity,
											'DATE_CREATED' 	=> date('d-M-Y'),
											'CREATED_BY' 	=> $user_id,
											'VENDOR_ID' 		=>$vendor_id
										];

						//If does not exists. then insert.
						$result_po_detail = $this->rfq_awarding_model->check_po_details_if_exists($save_pod_arr);
						$result_count = count($result_po_detail);
						if($result_count == 0 || $result_count > 1){
							$this->common_model->insert_table('SMNTP_RFQRFB_PO_DETAILS', $save_pod_arr);
						}else if($result_count == 1){
							$this->rfq_awarding_model->enable_podetails($result_po_detail[0]->PO_DETAIL_ID);
						}
					}
				}
			}

			// after saving notify all losing vendors
			$where_arr 	= array('RFQRFB_ID' => $rfq_id);
			$rfq_title  = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'TITLE', $where_arr);

			$var = array(
					'TEMPLATE_TYPE' => 28,
					'ACTIVE' 		=> 1
				);

			$rs_email = $this->common_model->get_email_template($var);
			$message = $rs_email->row()->CONTENT;

			$message = str_replace('[RFQ_ID]', $rfq_id, $message);
			$message = str_replace('[RFQ_TITLE]', $rfq_title, $message);

			
			$all_participants_arr = $this->rfq_awarding_model->get_participants(['RFQ_RFB_ID' => $rfq_id]);
			//$this->response($all_participants_arr);

			$check_if_email2 = count($this->rfq_awarding_model->select_query('SMNTP_RFQRFB_PO_DETAILS',array('RFQRFB_ID' => $rfq_id),'PO_NUMBER'));
			$z = array();
			foreach ($all_participants_arr as $row)
			{
				if ($row['SHORTLISTED'] == 1 && $row['AWARDED'] == 0) // send notif to shortlisted but not awarded
				{

					$email_addr = '';
					$where_arr 	= array('USER_ID' => $row['USER_ID']);
					$email_addr  = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

					$mess2 = str_replace('[vendor_name]',$row['VENDOR_NAME'], $message);
					//array_push($z,$vr);
					// send email
					$email_data['to'] 		= $email_addr;
					//$email_data['bcc']  	= 'marcanthonypacres@yahoo.com';
					$email_data['subject'] 	= $rfq_id.' - '.$rfq_title;
					$email_data['content'] 	= nl2br($mess2);
				
					//$this->response($check_if_email);
					if($check_if_email == 0 && $check_if_email2 >0){
						$x = $this->common_model->send_email_notification($email_data);
					}

					// send message
					$message_data['TYPE'] = 'notification';
					$message_data['SENDER_ID'] = $user_id;
					$message_data['SUBJECT'] = $row['VENDOR_NAME'];
					$message_data['TOPIC'] = urlencode($rfq_id.' - '.$rfq_title.' Award');
					$message_data['BODY'] = urlencode($message);
					$message_data['DATE_SENT'] = date('d-M-Y h:i:s A');
					$message_data['RFQRFB_ID'] = $rfq_id;
					$message_data['RECIPIENT_ID'] = $row['USER_ID'];
		
				

					$model_data = $this->mail_model->send_message($message_data);
				}
				else if ($row['SHORTLISTED'] == 1 && $row['AWARDED'] == 1) // send notif to awarded 
				{

						//email awarded

					$v3 = array(
						'TEMPLATE_TYPE' => 8,
						'ACTIVE' 		=> 1
					);


					$email_addr = '';
					$where_arr 	= array('USER_ID' => $row['USER_ID']);
					$email_addr  = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);


					$rs_emails = $this->common_model->get_email_template($v3);
					$smessage = $rs_emails->row()->CONTENT;

					$smessage = str_replace('[RFQ_ID]', $rfq_id, $smessage);
					$smessage = str_replace('[RFQ_TITLE]', $rfq_title, $smessage);
					$smessage = str_replace('[vendor_name]',$row['VENDOR_NAME'], $smessage);

					$email_data['to'] 		= $email_addr;
					//$email_data['bcc']  	= 'marcanthonypacres@yahoo.com';
					$email_data['subject'] 	= $rfq_id.' - '.$rfq_title;
					$email_data['content'] 	= nl2br($smessage);


			//		$this->response($email_data);

					if($check_if_email == 0 && $check_if_email2 >0){

					$message_data2['TYPE'] = 'notification';
					$message_data2['SENDER_ID'] = $user_id;
					$message_data2['SUBJECT'] = $row['VENDOR_NAME'];
					$message_data2['TOPIC'] = urlencode($rfq_id.' - '.$rfq_title.' Award');
					$message_data2['BODY'] = urlencode($smessage);
					$message_data2['DATE_SENT'] = date('d-M-Y h:i:s A');
					$message_data2['RFQRFB_ID'] = $rfq_id;
					$message_data2['RECIPIENT_ID'] = $row['USER_ID'];				
					$model_data = $this->mail_model->send_message($message_data2);
					$this->common_model->send_email_notification($email_data);

					}					









					//$rs_awarded = $this->send_email_award($row['INVITE_ID'], $rfq_id, '3'); // action = 3 dito na ung send ng notif sa mga na award

					// send message

				}	
			}	
		}
}
?>