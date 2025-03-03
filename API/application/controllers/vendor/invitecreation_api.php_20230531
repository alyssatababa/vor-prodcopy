<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
require APPPATH . '/libraries/REST_Controller.php';
class Invitecreation_api extends REST_Controller
{

	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/invitecreation_model');
		$this->load->model('vendor/registration_model');
		$this->load->model('common_model');
		$this->load->helper('message_helper');
	}

	public function template_get()
	{
		$user_id = $this->get('user_id');
		$data = $this->invitecreation_model->get_msg_template($user_id);

		$this->response($data);
	}

	// Added MSF - 20191118 (IJR-10618)
	public function sub_cat_get()
	{
		$cat_id = $this->get('cat_id');
		$data = $this->invitecreation_model->get_sub_category($cat_id);

		$this->response($data);
	}

	public function addinvite_post()
	{
		$var['invite_type']		= htmlspecialchars_decode ($this->post('rad_invite_type'));
		$var['source_invite_id']		= htmlspecialchars_decode ($this->post('source_invite_id'));
		$var['user_id'] 		= htmlspecialchars_decode ($this->post('user_id'));
		$var['position_id'] 	= htmlspecialchars_decode ($this->post('position_id'));
		$var['vendorname'] 		= htmlspecialchars_decode ($this->post('txt_vendorname'));
		$var['new_vendorname'] 		= htmlspecialchars_decode ($this->post('txt_nvendorname'));
		$var['contact_person'] 	= htmlspecialchars_decode ($this->post('txt_contact_person'));
		$var['email'] 			= htmlspecialchars_decode ($this->post('txt_email'));
		$var['msg_template'] 	= htmlspecialchars_decode ($this->post('cbo_msg_template'));
		$var['vendor_msg'] 		= htmlspecialchars_decode ($this->post('txt_vendor_msg'));
		$var['template_msg'] 	= htmlspecialchars_decode ($this->post('txt_template_msg'));
		$var['approver_note'] 	= htmlspecialchars_decode ($this->post('txt_approver_note'));
		$var['status'] 			= htmlspecialchars_decode ($this->post('status'));
		$var['cat_count']		= htmlspecialchars_decode ($this->post('cat_sup_count'));
		// Added MSF - 20191118 (IJR-10618)
		$var['sub_cat_count']	= htmlspecialchars_decode ($this->post('sub_cat_sup_count'));
		$var['vendorname'] 		= strtoupper(trim($var['vendorname'])); // trim spaces
		//$var['vendorname'] 		= trim($var['vendorname']); // trim spaces
		$var['tv_type']			= htmlspecialchars_decode ($this->post('rad_trade_vendor_type'));
		$var['cbo_tp'] = htmlspecialchars_decode ($this->post('cbo_tp'));

		// Added MSF - 20191108 (IJR-10617)
		$var['approved_items']		= $this->post('orig_name');
		$var['file_location']		= $this->post('file_path');
		$var['date_submitted']		= $this->post('upload_date');

		$recipient_id 	= '';
		$subject	 	= '';
		$topic	 		= '';
		$message	 	= '';

		for ($i=1; $i <= $var['cat_count']; $i++)
		{
			$var['category_id'.$i] = htmlspecialchars_decode ($this->post('category_id'.$i));
		}
		
		// Added MSF - 20191118 (IJR-10618)
		for ($x=1; $x <= $var['sub_cat_count']; $x++)
		{
			$sub_category = explode('|',htmlspecialchars_decode($this->post('sub_category_id'.$x)));
			$var['sub_category_id'.$x] = $sub_category[0];
			$var['sub_category_source'.$x] = $sub_category[1];
		}

		if ($var['status'] == 2) // ni submit na
		{
			$data['status'] 		= 1; // default muna sa 1
			$data['position_id']	= $var['position_id'];
			$data['type']			= 1; // registration
			$data = $this->common_model->get_next_process($data);

			$var['position_id'] 	= $data['next_position'];
			$var['business_type'] 	= $data['business_type'];

			$aprv_id = '';
			// get approver ID
			if ($var['business_type'] == 1 || $var['business_type'] == 3) // trade and Non Trade Service
			{
				$aprv_id = 'BUHEAD_ID';
			}
			elseif ($var['business_type'] == 2) // non trade
			{
				$aprv_id = 'GHEAD_ID';
			}
			
			// get recipient id here for message
			$where_arr = array(
								'USER_ID' => $var['user_id']
							);
			$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', $aprv_id, $where_arr);
			$where_arr_def = array(
								'STATUS_ID' 	=> $data['next_status']
							);

			$rs_message = $this->common_model->select_query_active('CONTENT,TEMPLATE_HEADER','SMNTP_EMAIL_DEFAULT_TEMPLATE',$where_arr_def);
			$rs_notif = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$where_arr_def);

			//marcss


			if ($rs_message->num_rows() > 0)
			{


				$approver = $this->common_model->select_query_active('*','SMNTP_USERS',array('USER_ID' => $recipient_id));
				$approvername = $approver->row()->USER_FIRST_NAME. " " .$approver->row()->USER_MIDDLE_NAME. " " .$approver->row()->USER_LAST_NAME;
				$approver_position_id = $approver->row()->POSITION_ID;
				$approver_position_name = $this->common_model->get_position_name($approver_position_id);
				$approver_email = $approver->row()->USER_EMAIL;
				//end approver


				//sender
				$sender = $this->common_model->select_query_active('*','SMNTP_USERS',array('USER_ID' => $var['user_id']));
				$sendername = $sender->row()->USER_FIRST_NAME. " " .$sender->row()->USER_MIDDLE_NAME. " " .$sender->row()->USER_LAST_NAME;
				$sender_position_id = $sender->row()->POSITION_ID;
				$sender_position_name = $this->common_model->get_position_name($sender_position_id);
				//end sender

			if($var['invite_type'] != 5){
				$app_sen_ven = array(
					array('name' => $approvername ,'type' => 'approver'),
					array('name' => $sendername ,'type' => 'sender'),
					array('name' => htmlspecialchars_decode($var['vendorname']), 'type' => 'vendor')
				);
			}else{
				$app_sen_ven = array(
					array('name' => $approvername ,'type' => 'approver'),
					array('name' => $sendername ,'type' => 'sender'),
					array('name' => htmlspecialchars_decode($var['new_vendorname']), 'type' => 'vendor')
				);
			}

				//end declaration
				$msg = $rs_message->row(); 	
				$msg = change_tag_email($msg,$app_sen_ven);
				$notf =$rs_notif->row();
				$notf = change_tag_email($notf,$app_sen_ven);

				$email_data = array(
					'to' => $approver_email,
					'subject' => $msg->HEADER,
					'content' =>$msg->CONTENT
				);

				
				$res = $this->common_model->send_email_notification($email_data);

			}
		}

		$invite_id = $this->invitecreation_model->save_invitecreate($var);
		

		if ($invite_id)
		{

			$data['status'] 		= TRUE;
			$data['error'] 			= '';
			$data['invite_id'] 		= $invite_id;
			$data['recipient_id'] 	= $recipient_id;

			if($var['status'] == 2){
			$data['subject'] 		= $notf->SUBJECT;
			$data['topic'] 			= $notf->TOPIC;
			$data['message'] 		= $notf->MESSAGE;
			}

			if($var['status'] == 1){
			$data['subject'] 		= '';
			$data['topic'] 			= '';
			$data['message'] 		= '';
			}


			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	
	public function get_vendor_type_get(){
		$invite_id = $this->get('invite_id');
		$rs 	= $this->invitecreation_model->get_vendor_type($invite_id);
		$this->response($rs);
	}
	
	// Added MSF - 20191108 (IJR-10617)
	public function get_vendor_approved_items_get(){
		$invite_id = $this->get('invite_id');
		$rs 	= $this->invitecreation_model->get_vendor_approved_items($invite_id);
		$this->response($rs);
	}
	
	public function get_avc_vendor_approved_items_get(){
		$invite_id = $this->get('invite_id');
		$rs 	= $this->invitecreation_model->get_avc_vendor_approved_items($invite_id);
		$this->response($rs);
	}
	
	public function get_ad_vendor_approved_items_get(){
		$invite_id = $this->get('invite_id');
		$rs 	= $this->invitecreation_model->get_ad_vendor_approved_items($invite_id);
		$this->response($rs);
	}
	
	public function get_all_ad_vendor_approved_items_get(){
		$invite_id = $this->get('invite_id');
		$rs 	= $this->invitecreation_model->get_all_ad_vendor_approved_items($invite_id);
		$this->response($rs);
	}

	public function load_records_get()
	{
		$invite_id = $this->get('invite_id');

		$var['SVI.VENDOR_INVITE_ID'] = $invite_id;
		$rs 	= $this->invitecreation_model->get_invite_record($var);

		$var2['SVC.VENDOR_INVITE_ID'] = $invite_id;
		if($rs['query'][0]['REGISTRATION_TYPE'] != 4){
			$rs_cat = $this->invitecreation_model->get_invite_categories($var2);	
		}else{
			$rs_cat = $this->invitecreation_model->get_invite_avc_categories($var2);	
		}
		
		// Added MSF - 20191118 (IJR-10618)
		$var4['SVSC.VENDOR_INVITE_ID'] = $invite_id;
		if($rs['query'][0]['REGISTRATION_TYPE'] != 4){
			$rs_sub_cat = $this->invitecreation_model->get_invite_sub_categories($var4);
		}else{
			$rs_sub_cat = $this->invitecreation_model->get_invite_avc_sub_categories($var4);	
		}

		$data = $rs;
		$data['rs_cat'] = $rs_cat->result_array();
		$data['rs_cat_count'] = $rs_cat->num_rows();
		
		// Added MSF - 20191118 (IJR-10618)
		$data['rs_sub_cat'] = $rs_sub_cat->result_array();
		$data['rs_sub_cat_count'] = $rs_sub_cat->num_rows();
		$data['approved_items']	= $this->invitecreation_model->get_vendor_approved_items($invite_id);

		$this->response($data);
	}

	public function updateinvite_put()
	{

		$var['user_id'] 		= $this->put('user_id');
		$var['position_id'] 	= $this->put('position_id');
		$var['invite_id'] 		= $this->put('invite_id');
		$var['vendorname'] 		= $this->put('txt_vendorname');
		$var['new_vendorname'] 		= $this->put('txt_nvendorname');
		$var['contact_person'] 	= $this->put('txt_contact_person');
		$var['email'] 			= $this->put('txt_email');
		$var['msg_template'] 	= $this->put('cbo_msg_template');
		$var['vendor_msg'] 		= $this->put('txt_vendor_msg');
		$var['template_msg'] 	= $this->put('txt_template_msg');
		$var['approver_note'] 	= $this->put('txt_approver_note');
		$var['status'] 			= $this->put('status');
		$var['cat_count']		= $this->put('cat_sup_count');
		// Added MSF - 20191118 (IJR-10618)
		$var['sub_cat_count']	= htmlspecialchars_decode ($this->put('sub_cat_sup_count'));
		$var['vendorname'] 		= trim($var['vendorname']); // trim spaces
		$var['tv_type']			= $this->put('rad_trade_vendor_type');
		$var['cbo_tp']          = $this->put('cbo_tp');
		$var['invite_type']		= $this->put('rad_invite_type');
		// Added MSF - 20191108 (IJR-10617)
		$var['approved_items']		= $this->put('txt_approve_items');
		$var['file_location']		= $this->put('txt_file_path');
		$var['date_submitted']		= $this->put('txt_date_upload');
		
		$var['original_status']  = $var['status'];	
		$var['reason_for_extension'] 	= $this->put('reason_for_extension');
		$recipient_id 	= '';
		$subject	 	= '';
		$topic	 		= '';
		$message	 	= '';

		//marc --> remove if mag error

		$data['business_type'] = $this->put('business_type');
		if ($data['business_type'] == 1 || $data['business_type'] == 3) // trade
		{
		$aprv_id = 'BUHEAD_ID';
		}
		elseif ($data['business_type'] == 2) // non trade
		{
		$aprv_id = 'GHEAD_ID';
		}

		$where_arr = array(
								'USER_ID' => $var['user_id']
							);

		$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', $aprv_id, $where_arr);

		//end marc

		for ($i=1; $i <= $var['cat_count']; $i++)
		{
			$var['category_id'.$i] = $this->put('category_id'.$i);
		}
		
		// Added MSF - 20191118 (IJR-10618)
		for ($x=1; $x <= $var['sub_cat_count']; $x++)
		{
			$sub_category = explode('|',htmlspecialchars_decode($this->put('sub_category_id'.$x)));
			$var['sub_category_idz'.$x] = $sub_category[0];
			$var['sub_category_source'.$x] = $sub_category[1];
		}

		if ($var['status'] == 2) // ni submit na
		{
			$where_arr = array(
								'VENDOR_INVITE_ID' => $var['invite_id']
							);
			$invite_status_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'STATUS_ID', $where_arr);
			$data['status'] 		= $invite_status_id; // default muna sa 1
			$data['position_id']	= $var['position_id'];
			$data['type']			= 1; // registration
			$data['reg_type_id']	= $var['invite_type'];
			if($var['invite_type'] == 4){
				$var['vendor_id'] = $this->common_model->get_vendor_id($var['invite_id']);
			}
			$data = $this->common_model->get_next_process($data);
			$data['business_type'] = $this->put('business_type');
			$var['position_id'] 	= $data['next_position'];
			$var['business_type'] 	= $data['business_type'];
			$var['status'] 			= $data['next_status'];

			// get approver ID
			if ($var['business_type'] == 1 || $var['business_type'] == 3) // trade
			{
				$aprv_id = 'BUHEAD_ID';
			}
			elseif ($var['business_type'] == 2) // non trade
			{
				$aprv_id = 'GHEAD_ID';
			}

			// get recipient id here for message
			$where_arr = array(
								'USER_ID' => $var['user_id']
							);

			$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', $aprv_id, $where_arr);

			$where_arr_def = array(
								'STATUS_ID' 	=> $data['next_status']
							);

			$rs_message = $this->common_model->select_query_active('CONTENT,TEMPLATE_HEADER','SMNTP_EMAIL_DEFAULT_TEMPLATE',$where_arr_def);
			if( $data['next_status'] == 197){
				$where_arr_def['STATUS_ID'] = 791; //taken na yung 197 may tatamaan pag sinunod ko sa status id
			}
			$rs_notif = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$where_arr_def);

			

			if($rs_message->num_rows() > 0){
				//variable declaration

				//approver
				$approver = $this->common_model->select_query_active('*','SMNTP_USERS',array('USER_ID' => $recipient_id));
				$approvername = $approver->row()->USER_FIRST_NAME. " " .$approver->row()->USER_MIDDLE_NAME. " " .$approver->row()->USER_LAST_NAME;
				$approver_position_id = $approver->row()->POSITION_ID;
				$approver_position_name = $this->common_model->get_position_name($approver_position_id);
				$approver_email = $approver->row()->USER_EMAIL;
				//end approver

				//sender
				$sender = $this->common_model->select_query_active('*','SMNTP_USERS',array('USER_ID' => $var['user_id']));
				$sendername = $sender->row()->USER_FIRST_NAME. " " .$sender->row()->USER_MIDDLE_NAME. " " .$sender->row()->USER_LAST_NAME;
				$sender_position_id = $sender->row()->POSITION_ID;
				$sender_position_name = $this->common_model->get_position_name($sender_position_id);
				//end sender

			if($var['invite_type'] != 5){
				$app_sen_ven = array(
					array('name' => $approvername ,'type' => 'approver'),
					array('name' => $sendername ,'type' => 'sender'),
					array('name' => $var['vendorname'], 'type' => 'vendor')
				);
			}else{
				$app_sen_ven = array(
					array('name' => $approvername ,'type' => 'approver'),
					array('name' => $sendername ,'type' => 'sender'),
					array('name' => $var['new_vendorname'], 'type' => 'vendor')
				);
			}

				//end declaration
				$msg = $rs_message->row(); 	
				$msg = change_tag_email($msg,$app_sen_ven);

				$notif = $rs_notif->row(); 	
				$notif = change_tag_email($notif,$app_sen_ven);

				$subject = $notif->SUBJECT;
				$topic = $notif->TOPIC;
				$message = $notif->MESSAGE;





				$email_data = array(
					'to' => $approver_email,
					'subject' => $msg->HEADER,
					'content' =>$msg->CONTENT
				);


				$res = $this->common_model->send_email_notification($email_data);
			}
		}
		if ($var['status'] == 5 || $var['status'] == 6) // invite extended or invite close
		{
			$data['status'] 		= 5; // expired invite
			$data['position_id']	= $var['position_id'];
			$data['type']			= 1; // registration

			if ($var['status'] == 5) // its like approve for extension
				$data['action'] = 3;
			elseif ($var['status'] == 6) // its like reject close invite
				$data['action'] = 4;

			$data = $this->common_model->get_next_process($data);

			if ($var['status'] == 5) //invite extend email
			{
				// get approver ID
				if ($data['business_type'] == 1 || $data['business_type'] == 3) // trade
				{
					$aprv_id = 'BUHEAD_ID';
				}
				elseif ($data['business_type'] == 2) // non trade
				{
					$aprv_id = 'GHEAD_ID';
				}

				// get recipient id here for message
				$where_arr = array(
									'USER_ID' => $var['user_id']
								);
				$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', $aprv_id, $where_arr);

				$where_arr_def = array(
									'STATUS_ID' 	=> $data['next_status']
								);


				$rs_msg = $this->common_model->select_query_active('CONTENT,TEMPLATE_HEADER','SMNTP_EMAIL_DEFAULT_TEMPLATE',$where_arr_def);




				if ($rs_msg->num_rows() > 0)
				{

					$approver = $this->common_model->select_query_active('*','SMNTP_USERS',array('USER_ID' => $recipient_id));
					$approvername = $approver->row()->USER_FIRST_NAME. " " .$approver->row()->USER_MIDDLE_NAME. " " .$approver->row()->USER_LAST_NAME;
					$approver_position_id = $approver->row()->POSITION_ID;
					$approver_position_name = $this->common_model->get_position_name($approver_position_id);
					$approver_email = $approver->row()->USER_EMAIL;


					$sender = $this->common_model->select_query_active('*','SMNTP_USERS',array('USER_ID' => $var['user_id']));
					$sendername = $sender->row()->USER_FIRST_NAME. " " .$sender->row()->USER_MIDDLE_NAME. " " .$sender->row()->USER_LAST_NAME;
					$sender_position_id = $sender->row()->POSITION_ID;
					$sender_position_name = $this->common_model->get_position_name($sender_position_id);
				
			
				if($var['invite_type'] != 5){
					$app_sen_ven = array(
						array('name' => $approvername ,'type' => 'approver'),
						array('name' => $sendername ,'type' => 'sender'),
						array('name' => $var['vendorname'], 'type' => 'vendor')
					);
				}else{
					$app_sen_ven = array(
						array('name' => $approvername ,'type' => 'approver'),
						array('name' => $sendername ,'type' => 'sender'),
						array('name' => $var['new_vendorname'], 'type' => 'vendor')
					);
				}

					//end declaration
					$msg = $rs_msg->row(); 	
					$msg = change_tag_email($msg,$app_sen_ven);



					$email_data = array(
						'to' => $approver_email,
						'subject' => $msg->HEADER,
						'content' =>$msg->CONTENT
					);
						
					$res = $this->common_model->send_email_notification($email_data);

					if($data['next_status'] == 41){

					$where_arr_def = array(
						'STATUS_ID' 	=> 41
						);

					$rs_notf = $this->common_model->select_query_active('TOPIC,SUBJECT,MESSAGE','SMNTP_MESSAGE_DEFAULT',$where_arr_def);
					//$this->response($where_arr_def);


					$app_sen_ven = array(
						array('name' => $approvername ,'type' => 'approver'),
						array('name' => $sendername ,'type' => 'sender'),
						array('name' => $var['vendorname'], 'type' => 'vendor')
					);

					$ntf = $rs_notf->row();
					$ntf = change_tag_email($ntf,$app_sen_ven);

					$data['recipient_id'] 	= $recipient_id;
					$subject		= $ntf->SUBJECT;
					$topic 			= $ntf->TOPIC;
					$message 		= $ntf->MESSAGE;

					}

			
	
				}
			}
			elseif ($var['status'] == 6) //invite closed email
			{

				// send email notif
				$email_arr = array(
							'TEMPLATE_TYPE' 	=> 9, // invite closed email
							'ACTIVE' 			=> 1
						);


				$query = $this->common_model->get_email_template($email_arr);


				$name = array(
					array('name' => $var['vendorname'], 'type' => 'vendor')
				);
				$msg = $query->row();
				$msg = change_tag_email($msg,$name);		


				$where_arr_invite = array('VENDOR_INVITE_ID' => $var['invite_id']);
				$email = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'EMAIL', $where_arr_invite);



				$email_data = array(
						'to' => $email,
						'subject' => $msg->HEADER,
						'content' =>$msg->CONTENT
					);


				$this->common_model->send_email_notification($email_data);
			}

			$var['position_id'] 	= $data['next_position'];
			$var['status'] 			= $data['next_status'];
		}
		
		
		if(isset($var['vendor_id'])){
			$rs_one = $this->registration_model->backup_table($var['vendor_id']);
		}else{
			$rs_one = $this->registration_model->backup_invite_only($var['invite_id']);
		}
		
		$rs = $this->invitecreation_model->update_invitecreate($var);

		if ($rs)
		{


			$data['status'] = TRUE;
			$data['error'] = '';
			$data['invite_id'] 		= $var['invite_id'];
			$data['recipient_id'] 	= $recipient_id;
			$data['subject'] 		= $subject;
			$data['topic'] 			= $topic;
			$data['message'] 		= $message;

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	public function category_list_get()
	{
		$business_type 		= $this->get('business_type');
		$category_name 		= $this->get('search_cat');
		$cat_count			= $this->get('cat_sup_count');
		$user_id 			= $this->get('user_id');
		$position_id 		= $this->get('position_id');
		$selected_catid = array();
		$use_not_in = false;
		if ($cat_count != '')
		{
			for ($i=1; $i <= $cat_count; $i++)
			{
				$selected_catid[] = $this->get('category_id'.$i);
				$use_not_in = true;
			}
		}

		$where_arr = array(
						'category_name' => $category_name,
						'selected_catid' => $selected_catid,
						'use_not_in' 	=> $use_not_in,
						//'business_type' => $business_type,
						'user_id'		=> $user_id,
						'position_id'	=> $position_id
					);
		
		//if not NTS
		if($position_id != 11){
			$where_arr['business_type'] = $business_type;
		}else{
			//NTS
			$where_arr['business_type'] = 3;
		}

		$rs = $this->invitecreation_model->get_category_list($where_arr);
		$data = $rs;

		$this->response($data);
	}

	public function email_template_get()
	{
		$rs = $this->invitecreation_model->get_email_template();

		if ($rs)
		{
			$data = $rs->row()->CONTENT;

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	public function registration_type_get()
	{
		$invite_id = $this->get('invite_id');

		$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
		$status_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'REGISTRATION_TYPE', $where_arr);

		$this->response($status_id);
	}
	
	public function invite_status_get()
	{
		$invite_id = $this->get('invite_id');

		$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
		$status_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'STATUS_ID', $where_arr);

		$this->response($status_id);
	}
	public function termspayment_get()
	{
		$invite_id = $this->get('invite_id');

		$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
		$status_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'TERMSPAYMENT', $where_arr);

		$this->response($status_id);
	}
	
	public function avc_termspayment_get()
	{
		$invite_id = $this->get('invite_id');

		$where_arr 	= array('VENDOR_INVITE_ID' => $invite_id);
		$status_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'AVC_TERMSPAYMENT', $where_arr);

		$this->response($status_id);
	}

	public function validate_record_get()
	{
		$vendorname	 	 = $this->get('vendorname');
		$invite_id	 	 = $this->get('invite_id');
		$contact_person	 = $this->get('contact_person');
		$email	 		 = $this->get('email');

		$message = '';

		$where_id = ' and VENDOR_INVITE_ID != '.$invite_id;

		$where_name 	= "upper(VENDOR_NAME) = upper(".$this->db->escape($vendorname).")";
		if (!empty($invite_id))
			$where_name .= $where_id;

		$name_exists = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_name, false, false);
		// echo $this->db->last_query();
		$where_email 	= "upper(EMAIL) = upper(".$this->db->escape($email).")";
		if (!empty($invite_id))
			$where_email .= $where_id;

		$email_exists = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'EMAIL', $where_email);
		// echo $this->db->last_query();
		$where_contact 	= "upper(CONTACT_PERSON) = upper(".$this->db->escape($contact_person).")";
		if (!empty($invite_id))
			$where_contact .= $where_id;

		$contact_exists = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CONTACT_PERSON', $where_contact);
		// echo $this->db->last_query();


		if (!empty($name_exists))
		{
			if ($message == '')
				$message .= 'Vendor Name <input type="hidden" id="venname" value="1">';
			else
				$message .= 'and Vendor Name <input type="hidden" id="venname" value="1">';
		}
		// if (!empty($email_exists))
		// {
			// if ($message == '')
				// $message .= 'Email <input type="hidden" id="venemail" value="1">';
			// else
				// $message .= 'and Email <input type="hidden" id="venemail" value="1">';s
		// }
		// if (!empty($contact_exists))
		// {
		// 	if ($message == '')
		// 		$message .= 'Contact Person <input type="hidden" id="vencontact" value="1">';
		// 	else
		// 		$message .= 'and Contact Person <input type="hidden" id="vencontact" value="1">';
		// }

		$this->response($message);
	}




function resubmit_portal_put()
{		

		$find = 'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME';
		$this->load->model('mail_model');
		$senderid = $this->put('user_id');
		$pos_id = $this->put('position_id');

		$var['status'] 			= $this->put('status');

	


		if($pos_id == 7){
		//ghead
		$gheadinfo = $this->common_model->select_query_notin('SMNTP_USERS_MATRIX	',array('USER_ID' => $this->put('user_id')),'GHEAD_ID is NOT NULL','GHEAD_ID');
		$ghead = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $gheadinfo[0]['GHEAD_ID']),$find);
		$gheadname = $this->trim_name($ghead);
		//end ghead	
		}elseif ($pos_id == 2 || $pos_id == 11) {
					
		$gheadinfo = $this->common_model->select_query_notin('SMNTP_USERS_MATRIX	',array('USER_ID' => $this->put('user_id')),'BUHEAD_ID is NOT NULL','BUHEAD_ID');
		$ghead = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $gheadinfo[0]['BUHEAD_ID']),$find);
		$gheadname = $this->trim_name($ghead);
		//end ghead
		}



		//$this->response($gheadinfo);
		//user
		$user = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $this->put('user_id')),$find);
		$username = $this->trim_name($user);
		//end user

		//vendorinfo
		$vendorname = $this->put('txt_vendorname');
		$vendor_id = $this->common_model->select_query('SMNTP_VENDOR',array('VENDOR_INVITE_ID' => $this->put('invite_id')),'VENDOR_ID');
		
		//get portal message



		$where = array('STATUS_ID' => 201);

		if ($var['status'] == '5'){
			$where = array('STATUS_ID' => 41);
		}

		$portal = $this->common_model->get_message_default($where)->result_array();

			$portal[0]['SUBJECT'] = str_replace("[vendorname]", $vendorname, $portal[0]['SUBJECT']);
			$portal[0]['TOPIC'] = str_replace("[vendorname]", $vendorname, $portal[0]['TOPIC']);
			if ($var['status'] == '2'){
					$portal[0]['TOPIC'] = 'Re-invite Approval Request';;
			}
			$portal[0]['MESSAGE'] = str_replace("[approvername]", $gheadname, $portal[0]['MESSAGE']);
			$portal[0]['MESSAGE'] = str_replace("[sendername]", $username, $portal[0]['MESSAGE']);
			$portal[0]['MESSAGE'] = str_replace("[vendorname]", $vendorname, $portal[0]['MESSAGE']);
			$portal[0]['MESSAGE'] = str_replace("[vendor_name]", $vendorname, $portal[0]['MESSAGE']);
			$portal[0]['DATE_SENT'] = date('d M,Y h:i:s A');



			$rec = "";

			if($pos_id == 7){
				$rec = $gheadinfo[0]['GHEAD_ID'];
			}elseif ($pos_id == 2 || $pos_id == 11) {
				$rec = $gheadinfo[0]['BUHEAD_ID'];
			}


			$insert_array = array(
					'SUBJECT' => $portal[0]['SUBJECT'],
					'TOPIC' => $portal[0]['TOPIC'],
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $portal[0]['MESSAGE'],
					'TYPE' => 'notification',
					'SENDER_ID' => $senderid,
					'RECIPIENT_ID' => $rec,
					'INVITE_ID' =>$this->put('invite_id'),
					'VENDOR_ID' => "" //set to 000 temporary
				);

				
			$model_data = $this->mail_model->send_message($insert_array);
			$this->response($insert_array);

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


	function send_mail_notif_invitecreation_put()
	{



	}
	
	function completed_vendors_get(){
		$vendors = $this->common_model->get_completed_vendors();
		$this->response($vendors);
	}

	function vendor_info_get(){
		$vendor_name = $this->get('vendor_name');
		$rs = $this->invitecreation_model->get_vendor_info($vendor_name);
		if ($rs){
			//$dt = DateTime::createFromFormat("d#M#y H#i#s*A", $rs[0]['DATE_CREATED']);
			//isset($rs[0]['DATE_CREATED']) ? $dt->format('m/d/Y h:i:s a') : '';		
			isset($rs[0]['DATE_CREATED']) ? $rs[0]['DATE_CREATED'] : '';
			$rs[0]['status'] = TRUE;
			$data = $rs;
			$this->response($data);
		}else{
			$rs[0]['status'] = FALSE;
			$rs[0]['error'] = 'Something went wrong!';
			$data = $rs;
			$this->response($data);
		}
		
		$this->response($data);
	}

	function vendor_info_dept_get(){
		$invite_id = $this->get('invite_id');
		$rs = $this->invitecreation_model->get_vendor_info_dept($invite_id);
		$rs_two = $this->invitecreation_model->get_vendor_draft_dept($invite_id);
		$rs = array_merge($rs, $rs_two);
		if ($rs){
			//$dt = DateTime::createFromFormat("d#M#y H#i#s*A", $rs[0]['DATE_CREATED']);
			//isset($rs[0]['DATE_CREATED']) ? $dt->format('m/d/Y h:i:s a') : '';		
			isset($rs[0]['DATE_CREATED']) ? $rs[0]['DATE_CREATED'] : '';
			$rs[0]['status'] = TRUE;
			$data = $rs;
			$this->response($data);
		}else{
			$rs[0]['status'] = FALSE;
			$rs[0]['error'] = 'Something went wrong!';
			$data = $rs;
			$this->response($data);
		}
		
		$this->response($data);
	}	
	
}
?>
