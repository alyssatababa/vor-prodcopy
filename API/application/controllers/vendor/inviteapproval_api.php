<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
require APPPATH . '/libraries/REST_Controller.php';
class Inviteapproval_api extends REST_Controller
{

	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/inviteapproval_model');
		$this->load->model('common_model');
		$this->load->model('mail_model');
		$this->load->helper('message_helper');
	}

	public function process_put()
	{
		$var['user_id'] 		= $this->put('user_id');
		$var['position_id'] 	= $this->put('position_id');
		$var['invite_id'] 		= $this->put('invite_id');
		$var['status'] 			= $this->put('status');
		$var['remarks'] 		= $this->put('remarks');
		$var['action'] 			= $this->put('action');
		$var['vendor_msg'] 		= $this->put('vendor_msg');
		$var['surl'] 			= $this->put('surl');
		$var['cbo_tp'] 			= $this->put('cbo_tp');


		$data['status'] 		= $var['status'];
		$data['position_id']	= $var['position_id'];
		$data['type']			= 1; // registration
		$data['action']			= $var['action']; // registration
		$data['reg_type_id']			= $this->put('reg_type_id');
		$var['reg_type_id'] = $this->put('reg_type_id');

		$recipient_id 	= '';
		$subject	 	= '';
		$topic	 		= '';
		$message	 	= '';
		$no_user = empty($var['user_id']) ? true : false;
		
		//Check
		$where_arr = array('VENDOR_INVITE_ID' => $var['invite_id']);
		$business_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'BUSINESS_TYPE', $where_arr);
		$vendor_name = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

		if($business_type == 3){
			$data['business_type'] = $business_type;
		}else if($business_type != 2){
			$data['business_type'] = '';
			$data['approval_type'] = true;
		}
		if($business_type == 1 && $data['status'] == 197){
			$data['business_type'] = 1;
			$data['approval_type'] = false;
		}
		//$this->response($data);
		//exit();
		$data = $this->common_model->get_next_process($data);
		 // echo $this->db->last_query();
		//$this->response($this->db->last_query());
		$var['next_status'] 	= $data['next_status'];
		$var['next_position'] 	= $data['next_position'];
		//$this->response($var);
		//exit();

		$rs = $this->inviteapproval_model->invite_process($var);

		if ($rs)
		{
			$data['status'] = TRUE;
			$data['error'] = '';

			// send email
			if ($var['action'] == 3) // approved
			{
				$query = $this->inviteapproval_model->get_invite_details($var);
				$row = $query->row();

				$user_id 	= $row->USER_ID;
				$user_name 	= $row->USERNAME;

				if ($user_id == null || $user_id == '') // pag empty mag create ng bago
				{
					$create_user = array(
								'USER_FIRST_NAME' 	=> $row->VENDOR_NAME,
								'USER_EMAIL' 		=> $row->EMAIL,
								'USER_TYPE_ID'		=> 2, // vendor
								'POSITION_ID'		=> 10 // vendor
							);

					$user_arr = $this->inviteapproval_model->create_user($create_user);
					$user_id = $user_arr['user_id'];
					$user_name = $user_arr['user_name'];

					$var2 = array(
							'USER_ID' => $user_id
						);
					//set user id to vendor invite
					$this->inviteapproval_model->update_vendor_invite($var2, $var['invite_id']);
					$inv = array('VENDOR_INVITE_ID' => $var['invite_id']);
					$this->common_model->update_table('SMNTP_VENDOR_STATUS',array('TERMSPAYMENT' => $var['cbo_tp']),$inv);

				}

				$var3 = array(
							'user_id' 	=> $user_id,
							'invite_id' => $var['invite_id']
						);

				// create token for link
				$token = $this->inviteapproval_model->create_token($var3);
				
				//41 invite for extension
				$config_name = 'invite_expiration_days';
				if($var['status'] == 41){
				$config_name = 'invite_extension_days';
				}
				$where_arr = array('CONFIG_NAME' => $config_name);
				$expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

				$where_arr2 = array('TOKEN' => $token);
				$start_day = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_TOKEN', 'DATE_CREATED', $where_arr2);

				$expiry_date = date('F d, Y', strtotime($start_day. ' + '.$expire_day.' days'));

				$token 	= '<a href="'.$var['surl'].'index.php/resetpassword/index/'.$token.'" title="">CLICK HERE TO SET PASSWORD</a>';
				$surl 	= '<a href="'.$var['surl'].'" title="">'.$var['surl'].'</a>';

				$var['vendor_msg'] = str_replace('[vendor_name]', $vendor_name, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[vendorname]', $vendor_name, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[username]', $user_name, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[expiryday]', $expire_day, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[expirydate]', $expiry_date, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[token]', $token, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[base_url]', $surl, $var['vendor_msg']);

				$message = nl2br($var['vendor_msg']);
			
				$email_data['to'] 		= $row->EMAIL;
				//$email_data['bcc']  	= '';
				//$email_data['subject'] 	= $row->VENDOR_NAME . ' - Vendor Registration Invite Approved'; <-- pinatangal nila. pag pinabalik. pabalik natin sa kanila
				$email_data['subject'] 	= 'Vendor Registration Invite Approved';
				$email_data['content'] 	= $message;
				$where_arr_created 	= array('VENDOR_INVITE_ID' => $var['invite_id']);
				$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr_created);
				
				if($no_user || $var['action']  == 3){

					$this->common_model->send_email_notification($email_data);
				}
			}
			elseif ($var['action'] == 4) // reject
			{
				$where_arr_created 	= array('VENDOR_INVITE_ID' => $var['invite_id']);
				$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr_created);


				$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $data['next_status']
							);

				$notif = $this->common_model->get_message_default($where_arr_def);
				
				$template_type = '';
				$subject = '';
				//invite approval		
				if($var['status'] == 2 || $var['status'] == 197){
					$template_type = 46;
				}else if($var['status'] == 41){ // 41 = invite extension
					$template_type = 48; // invite extension rejected
				}else{
					$template_type = 34;
				}
				//$this->response($template_type);
				//
				$where_arr = array('TEMPLATE_TYPE' => $template_type);
									
			   // $message = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
			    $email = $this->common_model->select_query_active('CONTENT,TEMPLATE_HEADER','SMNTP_EMAIL_DEFAULT_TEMPLATE',$where_arr);

				if ($notif->num_rows() > 0 || $var['status'] == 2)
				{
					//$row = $rs_msg->row();

					$where_arr = array('VENDOR_INVITE_ID' => $var['invite_id']);
					$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

					$where_arr = array(
									'USER_ID' => $recipient_id
								);
					$recipient_name = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
					$approver_posid 	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
					$approver_posname 	= $this->common_model->get_position_name($approver_posid);
					// $recipient_name 	= $recipient_name.' ('.$approver_posname.')';

					$where_arr = array(
									'USER_ID' => $var['user_id']
								);
					$sendername 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
					$sender_posid 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
					$sender_posname 	= $this->common_model->get_position_name($sender_posid);
					// $sendername 		= $sendername.' ('.$sender_posname.')';
					
					/*$subject	= str_replace('[vendorname]', $vendorname, $row->SUBJECT);
					$topic		= str_replace('[vendorname]', $vendorname, $row->TOPIC);
					$message	= $row->MESSAGE;
					*/



					$app_sen_ven = array(
					array('name' => $sendername ,'type' => 'approver'),
					array('name' => $recipient_name ,'type' => 'receiver'),
					array('name' => $vendorname, 'type' => 'vendor'),
					array('name' =>$var['remarks'],'type' => 'remark')
					);


					if($template_type == 48){

					$app_sen_ven = array(
						array('name' => $sendername ,'type' => 'approver'),
						array('name' => $recipient_name ,'type' => 'sender'),
						array('name' => $vendorname, 'type' => 'vendor'),
						array('name' =>$var['remarks'],'type' => 'remark')
					);

					}


					$email_temp = $email->row();
					$email_temp = change_tag_email($email_temp,$app_sen_ven);
					$notif_temp = $notif->row();
					$notif_temp = change_tag_email($notif_temp,$app_sen_ven);


				$email_addr	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', ['USER_ID' => $recipient_id]);
				$email_data = array(
					'to' => $email_addr,
					'subject' => $email_temp->HEADER,
					'content' =>$email_temp->CONTENT
				);


				
				$res = $this->common_model->send_email_notification($email_data);


				}

			}

		
			$data['recipient_id'] 	= $recipient_id;
			$data['subject'] 		= 0;
			$data['topic'] 			= 0;
			$data['message'] 		= 0;
			$data['sender'] 		= $this->inviteapproval_model->get_sender($var['invite_id']);

			if(isset($notif_temp->SUBJECT)){
			$data['recipient_id'] 	= $recipient_id;
			$data['subject'] 		= $notif_temp->SUBJECT;
			$data['topic'] 			= $notif_temp->TOPIC;
			$data['message'] 		= $notif_temp->MESSAGE;
			$data['sender'] 		= $this->inviteapproval_model->get_sender($var['invite_id']);
			}

			
/*			if(!empty($data['sender'] )){
				$select = array('USER_ID' => $recipient_id);
				$cinfo = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL','SMNTP_USERS',$select);
				$cinfo = $cinfo->row();
				foreach ($cinfo as $key => $value) {
					if($value == NULL || $value == null){
						$cinfo->$key = "";
					}	
				}

				$data['sender_name'] = $cinfo->USER_FIRST_NAME ." ".$cinfo->USER_MIDDLE_NAME ." ".$cinfo->USER_LAST_NAME;
				$data['creator_email'] = $cinfo->USER_EMAIL;*/
		$data['creator_id'] = $recipient_id;
		$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	
	// Added MSF - 20191105 (IJR-10612)
	public function update_email_put(){
		$var['invite_id'] = $this->put('invite_id');
		$email = $this->put('email');
		
		$data['status'] = TRUE;
		
		$var2 = array(
			'EMAIL' => $email
		);

		$this->inviteapproval_model->update_email($var2, $var['invite_id']);
		$this->response($data);
	}	

	public function resend_email_put(){
		$var['user_id'] 		= $this->put('user_id');
		$var['position_id'] 	= $this->put('position_id');
		$var['invite_id'] 		= $this->put('invite_id');
		$var['status'] 			= $this->put('status');
		$var['remarks'] 		= $this->put('remarks');
		$var['action'] 			= $this->put('action');
		$var['vendor_msg'] 		= $this->put('vendor_msg');
		$var['surl'] 			= $this->put('surl');

		$data['status'] 		= $var['status'];
		$data['position_id']	= $var['position_id'];
		$data['type']			= 1; // registration
		$data['action']			= $var['action']; // registration

		$recipient_id 	= '';
		$subject	 	= '';
		$topic	 		= '';
		$message	 	= '';

		$data = $this->common_model->get_next_process($data);
		 // echo $this->db->last_query();

		$var['next_status'] 	= $data['next_status'];
		$var['next_position'] 	= $data['next_position'];

		$data['status'] = TRUE;
		$data['error'] = '';

		$where_arr = array('VENDOR_INVITE_ID' => $var['invite_id']);
		$vendor_name = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);
			// send email
			if ($var['action'] == 3) // approved
			{
				$query = $this->inviteapproval_model->get_invite_details($var);
				$row = $query->row();

				$user_id 	= $row->USER_ID;
				$user_name 	= $row->USERNAME;

				$var3 = array(
							'user_id' 	=> $user_id,
							'invite_id' => $var['invite_id']
						);

				// create token for link
				$where_arr2 = array('VENDOR_INVITE_ID' => $var['invite_id']);
				$token = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_TOKEN', 'TOKEN', $where_arr2);

				$config_name = 'invite_expiration_days';
				if($var['status'] == 41 || $var['status'] == 6){
				$config_name = 'invite_extension_days';
				}
				$where_arr = array('CONFIG_NAME' => $config_name);
				$expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

				$where_arr2 = array('TOKEN' => $token);
				$start_day = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_TOKEN', 'DATE_CREATED', $where_arr2);

				$expiry_date = date('F d, Y', strtotime($start_day. ' + '.$expire_day.' days'));

				$token 	= '<a href="'.$var['surl'].'index.php/resetpassword/index/'.$token.'" title="">CLICK HERE TO SET PASSWORD</a>';
				$surl 	= '<a href="'.$var['surl'].'" title="">'.$var['surl'].'</a>';
				
				
				$var['vendor_msg'] = str_replace('[vendor_name]', $vendor_name, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[vendorname]', $vendor_name, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[username]', $user_name, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[expiryday]', $expire_day, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[expirydate]', $expiry_date, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[token]', $token, $var['vendor_msg']);
				$var['vendor_msg'] = str_replace('[base_url]', $surl, $var['vendor_msg']);

				$message = nl2br($var['vendor_msg']);

				$email_data['to'] 		= $row->EMAIL;
				//$email_data['bcc']  	= '';
				$email_data['subject'] 	= 'Vendor Registration Invite Approved';
				$email_data['content'] 	= $message;
				$this->common_model->send_email_notification($email_data);
			}
			
			$data['recipient_id'] 	= $recipient_id;
			$data['subject'] 		= $subject;
			$data['topic'] 			= $topic;
			$data['message'] 		= $message;
			$this->response($data);
		
		//else
		//{
		//	$data['status'] = FALSE;
		//	$data['error'] = 'Something went wrong!';
		//	$this->response($data);
		//}
	}
	
	public function history_tbl_get()
	{
		$var['user_id'] 	= $this->get('user_id');
		$var['position_id'] = $this->get('position_id');
		$var['invite_id'] = $this->get('invite_id');
		$var['rpp']			= 25;
		$var['page_num']	= $this->get('current_page');

		$rs = $this->inviteapproval_model->get_appr_history($var);
		// echo $this->db->last_query();

		if ($rs)
		{
			$data = $rs;
			$data['status'] = TRUE;
			$data['error'] = '';

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	
	public function rev_history_tbl_get()
	{
		$var['user_id'] 	= $this->get('user_id');
		$var['position_id'] = $this->get('position_id');
		$var['invite_id'] = $this->get('invite_id');
		$var['rpp']			= 25;
		$var['page_num']	= $this->get('current_page');

		$rs = $this->inviteapproval_model->get_rev_history($var);

		if ($rs)
		{
			$data = $rs;
			$data['status'] = TRUE;
			$data['error'] = '';

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	public function smvs_get(){
		$var['invite_id'] = $this->get('invite_id');
		$rs = $this->inviteapproval_model->get_smvs($var);
			$this->response($rs);

		if ($rs)
		{
			$data = $rs;
			$data['status'] = TRUE;
			$data['error'] = '';

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	public function smvs_vendor_get(){
		$var['invite_id'] = $this->get('invite_id');
		$rs = $this->inviteapproval_model->get_smvs_vendor($var);

		if ($rs)
		{
			$data = $rs;
			$data['status'] = TRUE;
			$data['error'] = '';

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	public function vendor_id_request_vendor_get(){
        $var['invite_id'] = $this->get('invite_id');
        $rs = $this->inviteapproval_model->get_vendor_id_request_vendor($var);
       // print_r($rs); return;
        if ($rs)
        {
                $data = $rs;
                $data['status'] = TRUE;
                $data['error'] = '';
                $this->response($data);
        }
        else
        {
                $data['status'] = FALSE;
                $data['error'] = 'Something went wrong!';
                $this->response($data);
        }
    }

    public function vendor_pass_request_vendor_get(){
        $var['invite_id'] = $this->get('invite_id');
        $rs = $this->inviteapproval_model->get_vendor_pass_request_vendor($var);
        if ($rs)
        {
                $data = $rs;
                $data['status'] = TRUE;
                $data['error'] = '';
                $this->response($data);
        }
        else
        {
                $data['status'] = FALSE;
                $data['error'] = 'Something went wrong!';
                $this->response($data);
        }
    }
    
    public function vendor_request_history_get(){
        $var['invite_id'] = $this->get('invite_id');
        $rs = $this->inviteapproval_model->get_request_history($var);
        //print_r($rs); return;
        if ($rs)
        {
                $data = $rs;
                $data['status'] = TRUE;
                $data['error'] = '';
                $this->response($data);
        }
        else
        {
                $data['status'] = FALSE;
                $data['error'] = 'Something went wrong!';
                $this->response($data);
        }
    }


	public function send_email_invite_approval_post(){

		$inviter_id = $this->post('inviter_id');
		$approver_id = $this->post('approver_id');
		$status_id =  $this->post('status_id');
		$next_status_id = $this->input->post('next_status_id');
		$vendor_invite_id = $this->post('vendor_invite_id');
		$vendor_name = $this->post('vendorname');
		$remarks = $this->post('remarks');



		if($status_id == 2 && $next_status_id == 3){
			 $data = array('TEMPLATE_TYPE' => 29);
			 $email = $this->common_model->select_query_active('CONTENT,TEMPLATE_HEADER','SMNTP_EMAIL_DEFAULT_TEMPLATE',$data);
			 $email = $email->row();
			 $data = array('STATUS_ID' =>101);
			 $notif = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$data);
			 $notif = $notif->row();
			 $data =array('USER_ID' => $inviter_id);
			 $inviter =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $inviter =  $inviter->row();
			 $data =array('USER_ID' => $approver_id);
			 $approver =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $approver =  $approver->row();

			 foreach ($inviter as $key => $value) {
			 		if($value == NULL || $value == null){
			 			$inviter->$key = "";
			 		}
			 }

			 $inviter_name = $inviter->USER_FIRST_NAME ." ". $inviter->USER_MIDDLE_NAME ." ". $inviter->USER_LAST_NAME;
			 $inviter_email = $inviter->USER_EMAIL;

			 $approver_name = $approver->USER_FIRST_NAME ." ". $approver->USER_MIDDLE_NAME ." ". $approver->USER_LAST_NAME;

			$app_sen_ven = array(
			array('name' => $inviter_name ,'type' => 'sender'),
			array('name' => $vendor_name ,'type' => 'vendor'),
			array('name' => $approver_name ,'type' => 'approver')
			);

			$email = change_tag_email($email,$app_sen_ven);
			$notif = change_tag_email($notif,$app_sen_ven);

			$email_data = array(
			'to' => $inviter_email,
			'subject' => $email->HEADER,
			'content' =>$email->CONTENT
			);

			$notif_data = array(
			'SUBJECT' => $notif->SUBJECT,
			'TOPIC' => $notif->TOPIC,
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $notif->MESSAGE,
			'TYPE' => 'Notification',
			'SENDER_ID' => 0,// 0 portal
			'RECIPIENT_ID' => $inviter_id,
			'INVITE_ID' => $vendor_invite_id,
			'VENDOR_ID' => "" //set to 000 temporary
			);




			$res = $this->common_model->send_email_notification($email_data);
			$model_data = $this->mail_model->send_message($notif_data);
			$this->response($model_data);
		}

		if(($status_id == 2 || $status_id == 197) && $next_status_id == 4){


			 $data = array('TEMPLATE_TYPE' => 34);
			 $data = array('STATUS_ID' =>4);
			 $notif = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$data);
			 $notif = $notif->row();
			 $data =array('USER_ID' => $inviter_id);
			 $inviter =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $inviter =  $inviter->row();
			 $data =array('USER_ID' => $approver_id);
			 $approver =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $approver =  $approver->row();

			  foreach ($inviter as $key => $value) {
			 		if($value == NULL || $value == null){
			 			$inviter->$key = "";
			 		}
			 }

			 $inviter_name = $inviter->USER_FIRST_NAME ." ". $inviter->USER_MIDDLE_NAME ." ". $inviter->USER_LAST_NAME;
			 $approver_name = $approver->USER_FIRST_NAME ." ". $approver->USER_MIDDLE_NAME ." ". $approver->USER_LAST_NAME;

			$app_sen_ven = array(
			array('name' => $inviter_name ,'type' => 'receiver'),
			array('name' => $inviter_name ,'type' => 'sender'),
			array('name' => $vendor_name ,'type' => 'vendor'),
			array('name' => $approver_name ,'type' => 'approver'),
			array('name' => $remarks ,'type' => 'remark')
			);
			$notif = change_tag_email($notif,$app_sen_ven);

			$notif_data = array(
			'SUBJECT' => $notif->SUBJECT,
			'TOPIC' => $notif->TOPIC,
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $notif->MESSAGE,
			'TYPE' => 'Notification',
			'SENDER_ID' => 0,// 0 portal
			'RECIPIENT_ID' => $inviter_id,
			'INVITE_ID' => $vendor_invite_id,
			'VENDOR_ID' => "" //set to 000 temporary
			);
			$model_data = $this->mail_model->send_message($notif_data);
		
		}

		if($status_id == 197 && $next_status_id == 3){


			 $data = array('TEMPLATE_TYPE' => 29);
			 $email = $this->common_model->select_query_active('CONTENT,TEMPLATE_HEADER','SMNTP_EMAIL_DEFAULT_TEMPLATE',$data);
			 $email = $email->row();
			 $data = array('STATUS_ID' =>101);
			 $notif = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$data);
			 $notif = $notif->row();
			 $data =array('USER_ID' => $inviter_id);
			 $inviter =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $inviter =  $inviter->row();
			 $data =array('USER_ID' => $approver_id);
			 $approver =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $approver =  $approver->row();

			 foreach ($inviter as $key => $value) {
			 		if($value == NULL || $value == null){
			 			$inviter->$key = "";
			 		}
			 }

			 $inviter_name = $inviter->USER_FIRST_NAME ." ". $inviter->USER_MIDDLE_NAME ." ". $inviter->USER_LAST_NAME;
			 $inviter_email = $inviter->USER_EMAIL;

			 $approver_name = $approver->USER_FIRST_NAME ." ". $approver->USER_MIDDLE_NAME ." ". $approver->USER_LAST_NAME;

			$app_sen_ven = array(
			array('name' => $inviter_name ,'type' => 'sender'),
			array('name' => $vendor_name ,'type' => 'vendor'),
			array('name' => $approver_name ,'type' => 'approver')
			);

			$email = change_tag_email($email,$app_sen_ven);
			$notif = change_tag_email($notif,$app_sen_ven);

			$email_data = array(
			'to' => $inviter_email,
			'subject' => $email->HEADER,
			'content' =>$email->CONTENT
			);

			$notif_data = array(
			'SUBJECT' => $notif->SUBJECT,
			'TOPIC' => $notif->TOPIC,
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $notif->MESSAGE,
			'TYPE' => 'Notification',
			'SENDER_ID' => 0,// 0 portal
			'RECIPIENT_ID' => $inviter_id,
			'INVITE_ID' => $vendor_invite_id,
			'VENDOR_ID' => "" //set to 000 temporary
			);

			$res = $this->common_model->send_email_notification($email_data);
			$model_data = $this->mail_model->send_message($notif_data);

		}


			if($status_id == 41 && $next_status_id == 6){


		
			 $data = array('TEMPLATE_TYPE' => 52);
			 $email = $this->common_model->select_query_active('CONTENT,TEMPLATE_HEADER','SMNTP_EMAIL_DEFAULT_TEMPLATE',$data);
			 $email = $email->row();
			 $data = array('STATUS_ID' =>6);
			 $notif = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$data);
			 $notif = $notif->row();
			 $data =array('USER_ID' => $inviter_id);
			 $inviter =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $inviter =  $inviter->row();
			 $data =array('USER_ID' => $approver_id);
			 $approver =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $approver =  $approver->row();

			 foreach ($inviter as $key => $value) {
			 		if($value == NULL || $value == null){
			 			$inviter->$key = "";
			 		}
			 }

			 $inviter_name = $inviter->USER_FIRST_NAME ." ". $inviter->USER_MIDDLE_NAME ." ". $inviter->USER_LAST_NAME;
			 $inviter_email = $inviter->USER_EMAIL;

			 $approver_name = $approver->USER_FIRST_NAME ." ". $approver->USER_MIDDLE_NAME ." ". $approver->USER_LAST_NAME;

			$app_sen_ven = array(
			array('name' => $inviter_name ,'type' => 'sender'),
			array('name' => $vendor_name ,'type' => 'vendor'),
			array('name' => $approver_name ,'type' => 'approver')
			);

			$email = change_tag_email($email,$app_sen_ven);
			$notif = change_tag_email($notif,$app_sen_ven);

			$email_data = array(
			'to' => $inviter_email,
			'subject' => $email->HEADER,
			'content' =>$email->CONTENT
			);

			$notif_data = array(
			'SUBJECT' => $notif->SUBJECT,
			'TOPIC' => $notif->TOPIC,
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $notif->MESSAGE,
			'TYPE' => 'Notification',
			'SENDER_ID' => 0,// 0 portal
			'RECIPIENT_ID' => $inviter_id,
			'INVITE_ID' => $vendor_invite_id,
			'VENDOR_ID' => "" //set to 000 temporary
			);


			$res = $this->common_model->send_email_notification($email_data);
			$model_data = $this->mail_model->send_message($notif_data);
		
		}

		if($status_id == 41 && $next_status_id == 7){


			$vendor_email = $this->common_model->select_query_active('EMAIL','SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $vendor_invite_id));


		
			 $data = array('TEMPLATE_TYPE' => 9);
			 $email = $this->common_model->select_query_active('CONTENT,TEMPLATE_HEADER','SMNTP_EMAIL_DEFAULT_TEMPLATE',$data);
			 $email = $email->row();
			 $data = array('STATUS_ID' =>7);
			 $notif = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$data);
			 $notif = $notif->row();
			 $data =array('USER_ID' => $inviter_id);
			 $inviter =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $inviter =  $inviter->row();
			 $data =array('USER_ID' => $approver_id);
			 $approver =  $this->common_model->select_query_active('*','SMNTP_USERS',$data);
			 $approver =  $approver->row();

			 foreach ($inviter as $key => $value) {
			 		if($value == NULL || $value == null){
			 			$inviter->$key = "";
			 		}
			 }

			 $inviter_name = $inviter->USER_FIRST_NAME ." ". $inviter->USER_MIDDLE_NAME ." ". $inviter->USER_LAST_NAME;
			 $inviter_email = $inviter->USER_EMAIL;

			 $approver_name = $approver->USER_FIRST_NAME ." ". $approver->USER_MIDDLE_NAME ." ". $approver->USER_LAST_NAME;

/*			$app_sen_ven = array(
			array('name' => $inviter_name ,'type' => 'receiver'),
			array('name' => $vendor_name ,'type' => 'vendor'),
			array('name' => $approver_name ,'type' => 'approver')
			);
*/


			$vendor_email = $vendor_email->row()->EMAIL;

			$app_sen_ven = array(
			array('name' => $inviter_name ,'type' => 'sender'),
			array('name' => $vendor_name ,'type' => 'vendor'),
			array('name' => $approver_name ,'type' => 'approver'),
			array('name' => $remarks ,'type' => 'remark')
			);
			$email = change_tag_email($email,$app_sen_ven);
			$notif = change_tag_email($notif,$app_sen_ven);

			$email_data = array(
			'to' => $vendor_email,
			'subject' => $email->HEADER,
			'content' =>$email->CONTENT
			);



			$notif_data = array(
			'SUBJECT' => $notif->SUBJECT,
			'TOPIC' => $notif->TOPIC,
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $notif->MESSAGE,
			'TYPE' => 'Notification',
			'SENDER_ID' => 0,// 0 portal
			'RECIPIENT_ID' => $inviter_id,
			'INVITE_ID' => $vendor_invite_id,
			'VENDOR_ID' => "" //set to 000 temporary
			);

			//$this->response($email_data);
			$model_data = $this->mail_model->send_message($notif_data);
			$res = $this->common_model->send_email_notification($email_data);


			$this->response($model_data);
			//$model_data = $this->mail_model->send_message($notif_data);


		
		}


	

	


	}
}
?>