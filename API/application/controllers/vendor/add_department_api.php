<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
require APPPATH . '/libraries/REST_Controller.php';
class Add_department_api extends REST_Controller
{
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/add_department_model');
		$this->load->model('vendor/registration_model');
		$this->load->model('common_model');
		$this->load->model('mail_model');
		$this->load->helper('message_helper');
	}
	
	public function add_vendor_department_get(){
		$data['inviter'] = $this->get('inviter');
		
		$var['status'] 		= $this->get('status'); // default muna sa 1
		$var['position_id']	= $this->get('position_id');
		$var['type']			= 1; // registration
		if($var['status'] == 2){
			$data['position_id'] = 2;
		}else{
			$var = $this->common_model->get_next_process($var);
			$data['position_id'] 	= $var['next_position'];
		}
		
		$data['date_submitted'] = $this->get('txt_date_upload');
		$data['approved_items'] = $this->get('txt_approve_items');
		$data['file_location'] = $this->get('txt_file_path');

		// second file

		$data['date_submitted2'] = $this->get('txt_date_upload2');
		$data['approved_items2'] = $this->get('txt_approve_items2');
		$data['file_location2'] = $this->get('txt_file_path2');

		
		$data['buhead_id'] = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('USER_ID' => $data['inviter']),'BUHEAD_ID');
		
		$data['vendor_invite_id'] = $this->get('vendor_invite_id');
		$data['vendor_id'] = $this->get('vendor_id');
		$data['vendor_name'] = $this->get('vendor_name');
		$data['vendor_code'] = $this->get('vendor_code');
		$data['vendor_type'] = $this->get('vendor_type');
		$data['category_count'] = $this->get('count_a_dept');
		$data['sub_category_count'] = $this->get('count_a_sub_dept');
		$data['brand_count'] = $this->get('count_a_brand');
		$data['approver_note'] = $this->get('txt_approver_note');
		$data['multiple_vc'] = $this->get('multiple_vc');
		$data['main_vt'] = $this->get('main_vt');
		
		for($a=1; $a<=$data['category_count']; $a++){
			$data['dept_'.$a] = $this->get('a_dept'.$a);
		}
		
		for($b=1; $b<=$data['brand_count']; $b++){
			$data['brand_'.$b] = $this->get('a_brand'.$b);
		}
		
		for($c=1; $c<=$data['sub_category_count']; $c++){
			$data['sub_dept_'.$c] = $this->get('a_sub_dept'.$c);
		}
		
		$ada_id = $this->add_department_model->save_add_vendor_department($data);
		$test = $this->send_email_inviter_to_buh($data['inviter'], $data['buhead_id'][0]['BUHEAD_ID'], $data['vendor_invite_id']);
		
		$this->response($ada_id);
	}
	
	public function vendor_profile_get(){
		$var['invite_id'] = $this->get('invite_id');
		$vendor_info = $this->add_department_model->get_vendor_info($var);
		$this->response($vendor_info);
	}
	
	public function div_head_get(){
		$rs = $this->get('rs');
		$created_by = $rs['CREATED_BY'];
		
		$inviter_matrix_data = $this->registration_model->get_users_in_matrix($created_by)[0];
		
		if( ! empty($inviter_matrix_data['BUHEAD_ID'])){
			$approver_id = $inviter_matrix_data['BUHEAD_ID'];
		}else if( ! empty($inviter_matrix_data['GHEAD_ID'])){
			$approver_id = $inviter_matrix_data['GHEAD_ID'];
		}
		
		$division_head_data = $this->registration_model->get_user(array(
			'USER_ID' => $approver_id
		))->row_array();
		
		$division_head_name = $division_head_data['USER_FIRST_NAME'] . ' ';
		$division_head_name .= (empty($division_head_data['USER_MIDDLE_NAME']) ? '' : $division_head_data['USER_MIDDLE_NAME'] . ' ');
		$division_head_name .= (empty($division_head_data['USER_LAST_NAME']) ? '' : $division_head_data['USER_LAST_NAME'] . ' ');
		
		$data['division_head'] = trim($division_head_name);
		$this->response($data['division_head']);
	}
	
	public function response_approval_get(){
		// 251 = Add Department Approved By BUH
		// 252 = Add Department Rejected By BUH
		// 253 = Add Department Approved By HaTS
		// 254 = Add Department Rejected By HaTS
		$date_timestamp = date('Y-m-d H:i:s');
		
		$date_timestamp_two = date('Y-m-d H:i:s');
		
		$position_id = $this->get('position_id');
		$rec_id = $this->get('rec_id');
		$user_id = $this->get('user_id');
		$action = $this->get('action');
		$vendor_invite_id = $this->get('vendor_invite_id');
		$vendor_id = $this->get('vendor_id');
		$record = [];
		
		$rs = $this->add_department_model->get_vendor_status($vendor_invite_id);
		
		/* 
			250 = BUH Approval
			251 = BUH Rejected
			252 = VRD Approval
			253 = VRD Rejected
			254 = HATS Approval
			255 = Hats Rejected
			256 = Completed
		*/
		
		if($action == 1){ // Approve
			if($position_id == 3){ // BU Head
				$var['status'] 		= 250; // default muna sa 1
				$var['action']	= 3;
				$vendor_status = 252;
				array_push($record, (object)['DATE_BUH_APPROVED' => $date_timestamp, 'BUH_APPROVED_BY' => $user_id]);
			}else if($position_id == 5 && $rs->STATUS_ID == 252){ // VRD Head
				$var['status'] 		= 252; // default muna sa 1
				$var['action']	= 3;
				$vendor_status = 254;
				array_push($record, (object)['VRD_HEAD_DATE_APPROVED' => $date_timestamp, 'VRD_HEAD_APPROVED_BY' => $user_id]);
			}else if($position_id == 5 && $rs->STATUS_ID == 254){ // VRD Head
				$position_id	= 6;
				$var['status'] 		= 254; // default muna sa 1
				$var['action']	= 3;
				$vendor_status = 19;
				array_push($record, (object)['DATE_HATS_APPROVED' => $date_timestamp, 'HATS_APPROVED_BY' => $user_id]);
			}else{ // HaTS
				$var['status'] 		= 254; // default muna sa 1
				$var['action']	= 3;
				$vendor_status = 19;
				array_push($record, (object)['DATE_HATS_APPROVED' => $date_timestamp, 'HATS_APPROVED_BY' => $user_id]);
			}			
			
		}else{ // Rejected
			if($position_id == 3){ // BU Head
				$var['status'] = 251;
				$var['action']	= 4;
				$vendor_status = 19;
			}else if($position_id == 5){ // VRD Head
				$var['status'] = 253;
				$var['action']	= 4;
				$vendor_status = 19;
			}else{ // HaTS
				$var['status'] = 255;
				$var['action']	= 4;
				$vendor_status = 19;
			}
			
			$logs['vendor_invite_status_id'] = $rs->VENDOR_INVITE_STATUS_ID;
			$logs['status_id'] = $rs->STATUS_ID;
			$logs['position_id'] = $rs->POSITION_ID;
			$logs['approver_id'] = $rs->APPROVER_ID;
			$logs['approver_remarks'] = $rs->APPROVER_REMARKS;
			$logs['date_updated'] = $rs->DATE_UPDATED;
			$logs['termspayment'] = $rs->TERMSPAYMENT;
			
			$vendor_status_logs_info = array(
					'VENDOR_INVITE_STATUS_ID'	=> $logs['vendor_invite_status_id'],
					'VENDOR_INVITE_ID'	=> $vendor_invite_id,
					'STATUS_ID'			=> $var['status'],
					'POSITION_ID'		=> $position_id,
					'APPROVER_ID'		=> $user_id,
					'DATE_UPDATED'		=> $date_timestamp,
					'TERMSPAYMENT'		=> $logs['termspayment'],
					'ACTIVE'			=> 1
				);
				
			$insert_logs = $this->common_model->insert_table('SMNTP_VENDOR_STATUS_LOGS', $vendor_status_logs_info);
		}
		
		$var['position_id']	= $position_id;
		$var['type'] = 1; // registration
		$current_status = $var['status'];
		$var = $this->common_model->get_next_process($var);
		$new_position_id 	= $var['next_position'];
		$status = $var['next_status'];
		
		if(count($record) > 0){
			$record[0]->STATUS_ID = $status;	
		}else{
			array_push($record, (object)['STATUS_ID' => $status]);
		}
		
		$where = array(
				'VENDOR_DEPT_ADH_ID' => $rec_id,
				'STATUS_ID' => $current_status
			);
		
		$rs = $this->common_model->update_table('SMNTP_VENDOR_ADH', $record[0], $where);
		
		$update_array = array(
				'STATUS_ID' 	=> $vendor_status,
				'POSITION_ID' 	=> $new_position_id,
				'APPROVER_ID'	=> $user_id
			);
		$where = array(
				'VENDOR_INVITE_ID' => $vendor_invite_id
			);
		$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $update_array, $where);
		
		$get_vendor_invite_status_id = $this->common_model->select_query_active('VENDOR_INVITE_STATUS_ID','SMNTP_VENDOR_STATUS',array('VENDOR_INVITE_ID'=>$vendor_invite_id));
		$get_vendor_invite_status_id = $get_vendor_invite_status_id->row();
		$vendor_invite_status_id = $get_vendor_invite_status_id->VENDOR_INVITE_STATUS_ID;
		
		$vendor_status_logs_info = array(
				'VENDOR_INVITE_STATUS_ID'	=> $vendor_invite_status_id,
				'VENDOR_INVITE_ID'	=> $vendor_invite_id,
				'STATUS_ID'			=> $vendor_status,
				'POSITION_ID'		=> $new_position_id,
				'APPROVER_ID'		=> $user_id,
				'DATE_UPDATED'		=> $date_timestamp_two,
				'ACTIVE'			=> 1
			);
			
		$insert_logs = $this->common_model->insert_table('SMNTP_VENDOR_STATUS_LOGS', $vendor_status_logs_info);
		
		if($status == 256){			
			$update_dsdb = $this->add_department_model->update_dsdb($rec_id);
		}
		
		if($var['next_status'] == 252){//Approved by BUH
			$this->send_email_buh_to_vrdh($user_id, $vendor_invite_id);
		}else if($var['next_status'] == 256){ // Approved by VRD/HATS
			$test = $this->send_email_hats_to_inviter($user_id, $vendor_invite_id);
		}
		
		$this->response($rs);
	}
	
	function send_email_inviter_to_buh($inviter_id, $buhead_id, $vendor_invite_id){
		$res = $this->db->query('SELECT SMNTP_VENDOR.VENDOR_ID, SMNTP_VENDOR.VENDOR_INVITE_ID, SMNTP_USERS.USER_ID,SMNTP_USERS.USER_EMAIL,CONCAT(SMNTP_USERS.USER_FIRST_NAME , \' \' , SMNTP_USERS.USER_MIDDLE_NAME , \' \' , SMNTP_USERS.USER_LAST_NAME) AS FULLNAME FROM SMNTP_USERS LEFT JOIN SMNTP_VENDOR_INVITE ON SMNTP_USERS.USER_ID = SMNTP_VENDOR_INVITE.CREATED_BY LEFT JOIN SMNTP_VENDOR ON SMNTP_VENDOR_INVITE.VENDOR_INVITE_ID = SMNTP_VENDOR.VENDOR_INVITE_ID WHERE SMNTP_VENDOR_INVITE.VENDOR_INVITE_ID = '.$vendor_invite_id.'');	
		$res = $res->result();
		
		$approver = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID, USER_EMAIL','SMNTP_USERS',array('USER_ID'=>$buhead_id));
		$approver = $approver->row();
		$approver_name = $approver->USER_FIRST_NAME ." ".$approver->USER_MIDDLE_NAME ." ".$approver->USER_LAST_NAME;
		$approver_email = $approver->USER_EMAIL;
		
		$vendor_info = $this->common_model->select_query_active('CREATED_BY,VENDOR_NAME','SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID'=> $vendor_invite_id));
		$vendorname = $vendor_info->row()->VENDOR_NAME;
		
		$data['full_name'] = $res[0]->FULLNAME;
		$data['buhead'] = $approver_name;
		$data['vendor_name'] = $vendorname;
		$data['send_to'] = $approver_email;
		
		$where_arr = array('TEMPLATE_TYPE' => 75,
							'ACTIVE'	 => 1);
		$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
				
		$email_template = str_replace('[buhead]', $approver_name, $email_template);
		$email_template = str_replace('[vendorname]', $vendorname, $email_template);
		$email_template = str_replace('[inviter]', $res[0]->FULLNAME, $email_template);
		
		$email_data['subject'] = $vendorname. ' - Additional Department Approval';
		$email_data['content'] = nl2br($email_template);
		$email_data['to'] = $approver_email;
		$this->common_model->send_email_notification($email_data);
		
		
		//select portal
		$pm[0]['SUBJECT'] = $vendorname;
		$pm[0]['TOPIC'] = "Additional Department Approval";

			$insert_array = array(
			'SUBJECT' => $pm[0]['SUBJECT'],
			'TOPIC' => $pm[0]['TOPIC'],
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $email_template,
			'TYPE' => 'notification',
			'SENDER_ID' => 0,
			'RECIPIENT_ID' => $buhead_id, 
			'VENDOR_ID' => $res[0]->VENDOR_ID, 
			'INVITE_ID' => $res[0]->VENDOR_INVITE_ID
			);
			
		$model_data = $this->mail_model->send_message($insert_array);
		
		$this->response($email_data);
	}
	
	public function send_email_buh_to_vrdh($approver, $invite_id){
		$approver = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID, USER_EMAIL','SMNTP_USERS',array('USER_ID'=>$approver));
		$approver = $approver->row();
		$approver_name = $approver->USER_FIRST_NAME ." ".$approver->USER_MIDDLE_NAME ." ".$approver->USER_LAST_NAME;
		
		$vendor_info = $this->common_model->select_query_active('CREATED_BY,VENDOR_NAME','SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID'=> $invite_id));
		$vendorname = $vendor_info->row()->VENDOR_NAME;
		$inviter = $vendor_info->row()->CREATED_BY;
		
		$add_vendor_header = $this->common_model->select_query_active('VENDOR_DEPT_ADH_ID,VENDOR_ID','SMNTP_VENDOR_ADH',array('VENDOR_INVITE_ID'=> $invite_id, 'STATUS_ID !=' => '19'));
		$vendor_dept_adh_id = $add_vendor_header->row()->VENDOR_DEPT_ADH_ID;
		
		$dept_list = $this->add_department_model->get_dept_list($vendor_dept_adh_id);
		
		$xcounter = 0;
		foreach($dept_list as $dept_name){
			if($xcounter == 0){
				$x_deptname = $dept_name['DESCRIPTION'];
			}else{
				$x_deptname .= ", ".$dept_name['DESCRIPTION'];
			}
			$xcounter += 1;
		}
		
		$vrd_head = $this->common_model->select_query_wherein('SMNTP_USERS_MATRIX', 'USER_ID', array($inviter), 'VRDHEAD_ID');
		$vrd_staff = $this->common_model->select_query_wherein('SMNTP_USERS_MATRIX', 'USER_ID', array($inviter), 'VRDSTAFF_ID');
		
		$counter = 0;
		foreach($vrd_head as $vrdhead){
			if($vrdhead['VRDHEAD_ID'] != ''){
				$user = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID, USER_EMAIL','SMNTP_USERS',array('USER_ID'=>$vrdhead['VRDHEAD_ID']));
				$user = $user->row();
				$user_name = $user->USER_FIRST_NAME ." ".$user->USER_MIDDLE_NAME ." ".$user->USER_LAST_NAME;
				$user_email = $user->USER_EMAIL;
				$user_id = $user->USER_ID;
				
				$where_arr = array('TEMPLATE_TYPE' => 76,
									'ACTIVE'	 => 1);
				$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
						
				$email_template = str_replace('[bu_head]', $approver_name, $email_template);
				$email_template = str_replace('[vrd_head]', $user_name, $email_template);
				$email_template = str_replace('[vendorname]', $vendorname, $email_template);
				$email_template = str_replace('[dept]', $x_deptname, $email_template);
				
				$email_data['subject'] = $vendorname. ' - Additional Department Approval';
				$email_data['content'] = nl2br($email_template);
				$email_data['to'] = $user_email;
				$this->common_model->send_email_notification($email_data);
				
				//select portal
				$pm[0]['SUBJECT'] = $vendorname;
				$pm[0]['TOPIC'] = "Additional Department Approval";

					$insert_array = array(
					'SUBJECT' => $pm[0]['SUBJECT'],
					'TOPIC' => $pm[0]['TOPIC'],
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $email_template,
					'TYPE' => 'notification',
					'SENDER_ID' => 0,
					'RECIPIENT_ID' => $user_id, 
					'VENDOR_ID' => $add_vendor_header->row()->VENDOR_ID, 
					'INVITE_ID' => $invite_id
					);
					
				$model_data = $this->mail_model->send_message($insert_array);
			}
			$counter += 1;
		}
		
		$counter = 0;
		foreach($vrd_staff as $vrdstaff){
			if($vrdstaff['VRDSTAFF_ID'] != ''){
				$user = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID, USER_EMAIL','SMNTP_USERS',array('USER_ID'=>$vrdstaff['VRDSTAFF_ID']));
				$user = $user->row();
				$user_name = $user->USER_FIRST_NAME ." ".$user->USER_MIDDLE_NAME ." ".$user->USER_LAST_NAME;
				$user_email = $user->USER_EMAIL;
				$user_id = $user->USER_ID;
				
				$where_arr = array('TEMPLATE_TYPE' => 78,
									'ACTIVE'	 => 1);
				$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
						
				$email_template = str_replace('[bu_head]', $approver_name, $email_template);
				$email_template = str_replace('[vrd_staff]', $user_name, $email_template);
				$email_template = str_replace('[vendor_name]', $vendorname, $email_template);
				$email_template = str_replace('[dept]', $x_deptname, $email_template);
				
				$email_data['subject'] = $vendorname. ' - Additional Department Approval';
				$email_data['content'] = nl2br($email_template);
				$email_data['to'] = $user_email;
				$this->common_model->send_email_notification($email_data);
				
				//select portal
				$pm[0]['SUBJECT'] = $vendorname;
				$pm[0]['TOPIC'] = "Additional Department Approval";

					$insert_array = array(
					'SUBJECT' => $pm[0]['SUBJECT'],
					'TOPIC' => $pm[0]['TOPIC'],
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $email_template,
					'TYPE' => 'notification',
					'SENDER_ID' => 0,
					'RECIPIENT_ID' => $user_id, 
					'VENDOR_ID' => $add_vendor_header->row()->VENDOR_ID, 
					'INVITE_ID' => $invite_id
					);
					
				$model_data = $this->mail_model->send_message($insert_array);
			}
			$counter += 1;
		}
		
		$this->response('done');
	}
	
	public function send_email_hats_to_inviter($approver, $invite_id){
		$approver = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID, USER_EMAIL','SMNTP_USERS',array('USER_ID'=>$approver));
		$approver = $approver->row();
		$approver_name = $approver->USER_FIRST_NAME ." ".$approver->USER_MIDDLE_NAME ." ".$approver->USER_LAST_NAME;
		
		$vendor_info = $this->common_model->select_query_active('CREATED_BY,VENDOR_NAME','SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID'=> $invite_id));
		$vendorname = $vendor_info->row()->VENDOR_NAME;
		$inviter_id = $vendor_info->row()->CREATED_BY;
		
		$get_vendor_id = $this->common_model->select_query_active('VENDOR_ID','SMNTP_VENDOR',array('VENDOR_INVITE_ID'=> $invite_id));
		$vendor_id = $get_vendor_id->row()->VENDOR_ID;
		
		$inviter = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID, USER_EMAIL','SMNTP_USERS',array('USER_ID'=>$inviter_id));
		$inviter = $inviter->row();
		$inviter_name = $inviter->USER_FIRST_NAME ." ".$inviter->USER_MIDDLE_NAME ." ".$inviter->USER_LAST_NAME;
		$inviter_email = $inviter->USER_EMAIL;
		
		$where_arr = array('TEMPLATE_TYPE' => 77,
							'ACTIVE'	 => 1);
		$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
				
		$email_template = str_replace('[approver]', $approver_name, $email_template);
		$email_template = str_replace('[receiver]', $inviter_name, $email_template);
		$email_template = str_replace('[vendor_name]', $vendorname, $email_template);
		
		$email_data['subject'] = $vendorname. ' - Additional Department Approval';
		$email_data['content'] = nl2br($email_template);
		$email_data['to'] = $inviter_email;
		$this->common_model->send_email_notification($email_data);
				
		//select portal
		$pm[0]['SUBJECT'] = $vendorname;
		$pm[0]['TOPIC'] = "Additional Department Approval";

			$insert_array = array(
			'SUBJECT' => $pm[0]['SUBJECT'],
			'TOPIC' => $pm[0]['TOPIC'],
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $email_template,
			'TYPE' => 'notification',
			'SENDER_ID' => 0,
			'RECIPIENT_ID' => $inviter_id, 
			'VENDOR_ID' => $vendor_id, 
			'INVITE_ID' => $invite_id
			);
			
		$model_data = $this->mail_model->send_message($insert_array);
		
		$vrd_staff = $this->common_model->select_query_wherein('SMNTP_USERS_MATRIX', 'USER_ID', array($inviter_id), 'VRDSTAFF_ID');
		
		$counter = 0;
		foreach($vrd_staff as $vrdstaff){
			if($vrdstaff['VRDSTAFF_ID'] != ''){
				$user = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID, USER_EMAIL','SMNTP_USERS',array('USER_ID'=>$vrdstaff['VRDSTAFF_ID']));
				$user = $user->row();
				$user_name = $user->USER_FIRST_NAME ." ".$user->USER_MIDDLE_NAME ." ".$user->USER_LAST_NAME;
				$user_email = $user->USER_EMAIL;
				$user_id = $user->USER_ID;
				
				$where_arr = array('TEMPLATE_TYPE' => 77,
									'ACTIVE'	 => 1);
				$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
						
				$email_template = str_replace('[approver]', $approver_name, $email_template);
				$email_template = str_replace('[receiver]', $user_name, $email_template);
				$email_template = str_replace('[vendor_name]', $vendorname, $email_template);
				
				$email_data['subject'] = $vendorname. ' - Additional Department Approval';
				$email_data['content'] = nl2br($email_template);
				$email_data['to'] = $user_email;
				$this->common_model->send_email_notification($email_data);
				
				
				//select portal
				$pm[0]['SUBJECT'] = $vendorname;
				$pm[0]['TOPIC'] = "Additional Department Approval";

					$insert_array = array(
					'SUBJECT' => $pm[0]['SUBJECT'],
					'TOPIC' => $pm[0]['TOPIC'],
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $email_template,
					'TYPE' => 'notification',
					'SENDER_ID' => 0,
					'RECIPIENT_ID' => $user_id, 
					'VENDOR_ID' => $vendor_id, 
					'INVITE_ID' => $invite_id
					);
					
				$model_data = $this->mail_model->send_message($insert_array);
			}
			$counter += 1;
		}
		
		$this->response('done');
	}
	
}
?>