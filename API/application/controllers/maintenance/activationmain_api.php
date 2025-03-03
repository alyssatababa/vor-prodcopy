<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Activationmain_api extends REST_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('maintenance/activation_model');
			$this->load->model('common_model');
			$this->load->model('vendor/registration_model');
		}

	function activation_table_get()
	{
		$data['user_id'] = $this->get('user_id');
		$data['position_id'] = $this->get('position_id');
		$data['vendorname'] = $this->get('vendorname');
		$data['vendorcode'] = $this->get('vendorcode');

		$rs = $this->activation_model->getmaintable_activation($data);

		$this->response($rs);

	}

	function approval_table_get()
	{
		$data['user_id'] = $this->get('user_id');
		$data['position_id'] = $this->get('position_id');
		$data['vendorname'] = $this->get('vendorname');
		$data['vendorcode'] = $this->get('vendorcode');

		$rs = $this->activation_model->getapprovaltable_activation($data);

		$this->response($rs);

	}

	function activate_selected_post()
	{	


		$this->load->model('mail_model');
		$data['user_id'] = $this->post('user_id');
		
		$data['position_id'] = $this->post('position_id');
		$data['numselected'] = $this->post('numselected');
		$data['next_position_id'] = $this->post('next_position_id');
		$data['next_status_id'] = $this->post('next_status_id');


		for($i = 1; $i <= $data['numselected']; $i++)
	    {	
	         $data['vendorinviteid'.$i] = $this->post('vendorinviteid'.$i);
	    }

	    $rs = $this->activation_model->activate_selected($data);
	 
	    if($data['next_position_id'] == 5){
	   		$rres = $this->vrdhead_request_react($data);
	    }

	   // $this->response($data);


	    if($data['position_id'] == 5)
	    {
	    	$date = strtotime("+30 day", time());
			$date = date('F d, Y', $date);

			$where_arr = array('TEMPLATE_TYPE' => 23,
								'ACTIVE'	 => 1);
		    $email_template_id = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

		    $where_arr = array('TEMPLATE_TYPE' => 42,
								'ACTIVE'	 => 1);
		    $emaill_vrd = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
		    $where_arr_def = array(
				'TYPE_ID' 		=> 1, // for registration
				'STATUS_ID' 	=> 161 
			);
		    $portal = $this->common_model->get_message_default($where_arr_def);

		  //  $this->response($portal);


		    $approver = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $data['user_id']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
		    $approver = $this->trim_name($approver);

		    //$this->response($approver);

			for($i = 1; $i <= $data['numselected']; $i++)
		    {	
		    	// email to vendor
		         $data['vendorinviteid'] = $this->post('vendorinviteid'.$i);

		         $where_arr = array('VENDOR_INVITE_ID' => $data['vendorinviteid']);
				 $vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

		         $where_arr = array('VENDOR_INVITE_ID' => $data['vendorinviteid']);
				 $user_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', $where_arr);

		         $where_arr = array('USER_ID' => $user_id);
				 $emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

				 $email_template_id = str_replace('[vendorname]', $vendorname, $email_template_id); // (what tofind, value change, whole sentence) 
				 $email_template_id = str_replace('[type]', 'additional requirements', $email_template_id); // (what tofind, value change, whole sentence) 
				 $email_template_id = str_replace('[date]', $date, $email_template_id); // (what tofind, value change, whole sentence) 


				 $email_data['subject'] = 'Re-activation of Account for Additional Requirements';
				 //$email_data['bcc'] = 'justine.jovero@novawareystems.com';
				 $email_data['content'] = nl2br($email_template_id);

				 $email_data['to'] = $emailaddress;
				 $this->common_model->send_email_notification($email_data);


				 $cb = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' =>  $data['vendorinviteid']),'CREATED_BY');



				 $vrdstaff = $this->common_model->select_vrdstaff_join2($cb[0]['CREATED_BY']);


				 if(count($vrdstaff) > 0){
				 	foreach ($vrdstaff as $val) {
				 		if($val['VRDSTAFF_ID'] != null){

				 			$vrdstaff_id = $val['VRDSTAFF_ID'];
				 			$tname[0] = array(
				 				'USER_FIRST_NAME' => $val['USER_FIRST_NAME'],
				 				'USER_MIDDLE_NAME' => $val['USER_MIDDLE_NAME'],
				 				'USER_LAST_NAME' => $val['USER_LAST_NAME']
				 			);

				 			$vrdstaffName = $this->trim_name($tname);
				 			$vrdEmail = $val['USER_EMAIL'];

				 			$emaill_vrd_content = str_replace('[vendorname]', $vendorname, $emaill_vrd); // (what tofind, value change, whole sentence) 
							$emaill_vrd_content = str_replace('[vrdstaff]', $vrdstaffName, $emaill_vrd_content);
							$emaill_vrd_content = str_replace('[approver]', $approver, $emaill_vrd_content); // (what tofind, value change, whole sentence) 
   
							$email_data['subject'] = $vendorname . ' - Account Reactivated (Additional Requirements)';
							//$email_data['bcc'] = 'justine.jovero@novawareystems.com,jobert.beltran@sandmansystems.com,marcanthonypacres@yahoo.com';
							$email_data['content'] = nl2br($emaill_vrd_content);

							$email_data['to'] = $vrdEmail;
							$this->common_model->send_email_notification($email_data);

							// PORTAL NOTIF
							if ($portal->num_rows()>0) {
								$msg_row = $portal->first_row();
								
								$subject = str_replace('[vendorname]', $vendorname, $msg_row->SUBJECT);
								$topic = str_replace('[vendorname]', $vendorname, $msg_row->TOPIC);
								$message = $emaill_vrd_content;
								// $message = str_replace('[vendor]', $vendorname, $message);
								// $message = str_replace('[approver]', $approver, $message);
		
								$insert_array = array(
									'SUBJECT' => $subject,
									'TOPIC' => $topic,
									'DATE_SENT' => date('Y-m-d H:i:s'),
									'BODY' => $message,
									'TYPE' => 'notification',
									'SENDER_ID' =>0, //portal
									'RECIPIENT_ID' => $vrdstaff_id, //can be changed in query
									'VENDOR_ID' => $user_id
								);

								$model_data = $this->mail_model->send_message($insert_array);

							}

				 			
				 		}
				 	}
				 }

			
			
			 

/*				 if ($vrdstaff->num_rows()>0) {
					foreach ($vrdstaff->result() as $vrd_row) {
						if($vrd_row->VRDSTAFF_ID != NULL){
							$vrdstaff_id = $vrd_row->VRDSTAFF_ID;
							$vrdstaffName = trim($vrd_row).' '.trim($vrd_row->USER_MIDDLE_NAME).' '.trim($vrd_row->USER_LAST_NAME);
							$vrdEmail = $vrd_row->USER_EMAIL;

							$emaill_vrd_content = str_replace('[vendorname]', $vendorname, $emaill_vrd); // (what tofind, value change, whole sentence) 
							$emaill_vrd_content = str_replace('[vrdstaff]', $vrdstaffName, $emaill_vrd_content);
							$emaill_vrd_content = str_replace('[approver]', $approver, $emaill_vrd_content); // (what tofind, value change, whole sentence) 
   
							$email_data['subject'] = 'Account Reactivated (Additional)';
							//$email_data['bcc'] = 'justine.jovero@novawareystems.com,jobert.beltran@sandmansystems.com';
							$email_data['content'] = nl2br($emaill_vrd_content);

							$email_data['to'] = $vrdEmail;
							$this->common_model->send_email_notification($email_data);

							// PORTAL NOTIF
							if ($portal->num_rows()>0) {
								$msg_row = $portal->first_row();
								
								$subject = str_replace('[vendorname]', $vendorname, $msg_row->SUBJECT);
								$topic = str_replace('[vendorname]', $vendorname, $msg_row->TOPIC);
								$message = str_replace('[buyer]', $vrdstaffName, $msg_row->MESSAGE);
								$message = str_replace('[vendor]', $vendorname, $message);
								$message = str_replace('[approver]', $approver, $message);
		
								$insert_array = array(
									'SUBJECT' => $subject,
									'TOPIC' => $topic,
									'DATE_SENT' => date('d-M-Y h:i:s A'),
									'BODY' => $message,
									'TYPE' => 'notification',
									'SENDER_ID' => $this->post('user_id'),
									'RECIPIENT_ID' => $vrdstaff_id, //can be changed in query
									'VENDOR_ID' => $user_id
								);

								$model_data = $this->mail_model->send_message($insert_array);
							}
						}
					}
				 }*/
				
				 // email to creator
/*
		         $where_arr = array('VENDOR_INVITE_ID' => $data['vendorinviteid']);
				 $created_by_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);

		         $where_arr = array('USER_ID' => $created_by_id);
				 $creator_emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

		         $where_arr = array('USER_ID' => $created_by_id);
				 $creator_name = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_FIRST_NAME', $where_arr);

				 $where_arr = array('TEMPLATE_TYPE' => 24,
				 					'ACTIVE'	 => 1);
			     $email_template_id = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

			     $email_template_id = str_replace('[vendor_name]', $vendorname, $email_template_id); // (what tofind, value change, whole sentence) 
				 $email_template_id = str_replace('[created_by]', $creator_name, $email_template_id); // (what tofind, value change, whole sentence) 

				 $email_data['subject'] = 'Account Reactivated (Additional)';
				 //$email_data['bcc'] = '';
				 $email_data['content'] = nl2br($email_template_id);

				 $email_data['to'] = $creator_emailaddress;
				 $this->common_model->send_email_notification($email_data);
*/
			



		    }
	    	
	    }


		$this->response($rs);

	}

	function vendordata_get()
	{
		$user_id = $this->get('user_id');
		$position_id = $this->get('position_id');

		$rs = $this->activation_model->get_vendor_data($user_id, $position_id);

		$this->response($rs);
	}
	
	function recipientdata_get()
	{
		//Get multiple vendor data
		$vendorinviteid = $this->get('vendorids');
		$vendordata = array();
		
		foreach($vendorinviteid as $v){
		
			$vendorinfo = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $v),'*');
			$vendoremail = $vendorinfo[0]['EMAIL'];
			$vendorid = $vendorinfo[0]['USER_ID'];
			//$vendorinfo = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vendorid),'*');
			//$vendorname =  $vendorinfo[0]['USER_FIRST_NAME']
			//		. (!empty($vendorinfo[0]['USER_MIDDLE_NAME']) ? ' ' . $vendorinfo[0]['USER_MIDDLE_NAME'] : '') 
			//		. (!empty($vendorinfo[0]['USER_LAST_NAME']) ? ' ' . $vendorinfo[0]['USER_LAST_NAME'] : '') ;
		
			$vendorname = $vendorinfo[0]['VENDOR_NAME'];

			$vendordata[] = array(
				'vendoremail' => $vendoremail,
				'vendorid' => $vendorid,
				'vendorname' => $vendorinfo[0]['VENDOR_NAME'],
				'vendorinviteid' => $v
			);
		}
		
		//Get Approver Info
		$senderid = $this->get('user_id');
		$approverid = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('USER_ID' => $senderid),'DISTINCT BUHEAD_ID, GHEAD_ID', false);
		
		$approverinfo = array();
		foreach($approverid as $id){
		
			if(!empty($id['BUHEAD_ID'])){
				$approverinfo[] = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $id['BUHEAD_ID']),'*')[0];
			}else if(!empty($id['GHEAD_ID'])){
				$approverinfo[] = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $id['GHEAD_ID']),'*')[0];
			}
		}
		//Get Email Default template 
		//Template type 40 = Account Reactivation
		$email_message =  $this->common_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => 40),'*')[0];
		$data['response'] = array(
			'vendorinfo'	=> $vendordata,
			'approverinfo' 	=> $approverinfo,
			'emailmessage' => $email_message
 		);
		$this->response($data);
	}

	function approve_selected_post()
	{

		$this->load->model('mail_model');
		$data['user_id'] = $this->post('user_id');
		$data['position_id'] = $this->post('position_id');
		$data['numselected'] = $this->post('numselected');

		
		$vendors_primary_deactivated_flag =  array();
		for($i = 1; $i <= $data['numselected']; $i++)
	    {	
			$data['vendorinviteid'.$i] = $this->post('vendorinviteid'.$i);
			//Jay, get the position id 
			// If 10(Vendor position), 
			$where_arr = array('VENDOR_INVITE_ID' => $data['vendorinviteid'.$i] );
			$primary_deactivated_flag = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'PRIMARY_DEACTIVATED_FLAG', $where_arr);
			$vendors_primary_deactivated_flag[$i] = $primary_deactivated_flag;
	    }
		
		// $rs = $this->activation_model->approve_selected($data);

		$appname = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $data['user_id']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
		$appname = $this->trim_name($appname);

		//$this->response($data);


		$react_em = $this->common_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => 42),'CONTENT');
		$where_arr_def = array(
				'TYPE_ID' 		=> 1, // for registration
				'STATUS_ID' 	=> 161 
			);
	    	
	    $portal = $this->common_model->get_message_default($where_arr_def)->result_array();


	

	 	if($data['position_id'] == 3){
	 		 $tvrdstff = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('BUHEAD_ID' => $data['user_id']),'VRDSTAFF_ID');
	 		 // $this->response($this->db->last_query());
	 	}else{
	 		$tvrdstff = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('VRDHEAD_ID' => $data['user_id']),'VRDSTAFF_ID');
	 	}	

	    


	   


	  	if(count($tvrdstff) == 0){

	  		$tvrdstff = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('GHEAD_ID' => $data['user_id']),'VRDSTAFF_ID');

	  	}  

	    //$this->response($tvrdstff);
	    $vrdstff = array();
	    foreach ($tvrdstff as $key => $value) {
	    	if($value['VRDSTAFF_ID'] != NULL){
	    		array_push($vrdstff,$value['VRDSTAFF_ID']);
	    	}
	    }

	    $vrdstff = array_unique($vrdstff);
		
		//Jay - reassigned key
		$temp = $vrdstff;
		$counter = 0;
		foreach($vrdstff as $key => $value){
			if(in_array($value, $temp)){
				unset($temp[$key]);
			}
			$temp[$counter] = $value;
			$counter++;
		}
		$vrdstff = $temp;
		
	//	$this->response($vrdstff);
		//$this->response($vrdstff);
		if(count($vrdstff) > 1){
			 $vrdinfo = $this->common_model->select_query_wherein('SMNTP_USERS','USER_ID',$vrdstff,'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL, USER_ID');
		}else{
			// $vrdinfo = $this->common_model->select_query('SMNTP_USERS', array('USER_ID' => $vrdstff[0]),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL, USER_ID');
		}
	   

	    //$this->response($vrdinfo);
	    //$this->response($data);
	    $rs = $this->activation_model->approve_selected($data);



	    $jklp = 1;
	    for($jklp =1; $jklp <= $data['numselected'];$jklp++){

			$date_timestamp = date('Y-m-d H:i:s');

			$res = $this->activation_model->get_vendor_invite_status($data['vendorinviteid'.$jklp]);

			
			$next_status_id  = 190;

			if($data['position_id'] == 5){
				$next_status_id  = 190;
			}else{
				$next_status_id  = 9;
			}

			$insert_logs_array = array(
				'POSITION_ID' => 10, //hard code muna. di pa sure sa config check later
				'VENDOR_INVITE_ID' => $data['vendorinviteid'.$jklp],
				'STATUS_ID' => $next_status_id,
				'APPROVER_ID' => $data['user_id'],
				'DATE_UPDATED' => $date_timestamp
			);

			if(!empty($res[0]['VENDOR_INVITE_STATUS_ID'])){
				$insert_logs_array['VENDOR_INVITE_STATUS_ID'] = $res[0]['VENDOR_INVITE_STATUS_ID'];
			}

			$activation_logs = $this->activation_model->activation_logs($insert_logs_array);

	    }




	    $where_arr = array('TEMPLATE_TYPE' => 23,
								'ACTIVE'	 => 1);

	    $email_vendor =  $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);


		$where_arr = array('TEMPLATE_TYPE' => 24,
			'ACTIVE'	 => 1);
		$email_creator = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
	  /*  $email_template_id = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);*/



	    if($data['position_id'] == 5){

	  /*  	$date = strtotime("+30 day", time());*/
	  		$whr = array('CONFIG_NAME' => 'additional_requirement_deactivate');
	  		$date2  = $this->common_model->select_query('SMNTP_SYSTEM_CONFIG',$whr,'CONFIG_VALUE');

	    	$stype = "Additional Requirements";
	    }else{
	    	$stype = "Primary Requirements";	
			// Jay , for extension na 7 days
	  		$whr = array('CONFIG_NAME' => 'primary_requirement_extension'); //primary_requirement_deactivate
	  		$date2  = $this->common_model->select_query('SMNTP_SYSTEM_CONFIG',$whr,'CONFIG_VALUE');

	    }

	    //$this->response($date2);
	 /*   $date = date('M d, Y', $date2[0]['CONFIG_NAME']);*/
	 	$quey = "SELECT (DATE_ADD(NOW(), INTERVAL ".$date2[0]['CONFIG_VALUE']." DAY)) AS SS FROM DUAL";

	 	$res = $this->db->query($quey)->result_array();
	
		
	 
		$date = $res[0]['SS'];

		$date = date_create($date);
		$date = date_format($date,'F d, Y');


		//Jay
		// Associative Array
		// Key = Vendor Invite ID
		// Value = Creator ID
		$list_of_vendors_creator = array();
		//Jay end
		
		for($i = 1; $i <= $data['numselected']; $i++)
	    {	
	    	//$this->response($data);
	    	// email to vendor

	    	$email_template_id =  "";
	    	$email_template_id =  $email_vendor;


			

	         $data['vendorinviteid'] = $this->post('vendorinviteid'.$i);

	         $where_arr = array('VENDOR_INVITE_ID' => $data['vendorinviteid']);
			 $vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

	         $where_arr = array('VENDOR_INVITE_ID' => $data['vendorinviteid']);
			 $user_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', $where_arr);
			 
	       // $where_arr = array('VENDOR_INVITE_ID' => $data['vendorinviteid']);
			// //Jay, get the position id 
			// // If 10(Vendor position), 
			//$primary_deactivated_flag = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'PRIMARY_DEACTIVATED_FLAG', $where_arr);
			//$additional_deactivated_flag = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'ADDITIONAL_DEACTIVATED_FLAG', $where_arr);
			 

	         $where_arr = array('USER_ID' => $user_id);
			 $emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);
			 
			 //jay
			 $where_arr = array('USER_ID' => $user_id);
			 $vendor_Id = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'VENDOR_ID', $where_arr);
			 //jay end
			 
			 $email_template_id = str_replace('[vendorname]', $vendorname, $email_template_id); // (what tofind, value change, whole sentence) 
			 $email_template_id = str_replace('[type]', strtolower($stype), $email_template_id); // (what tofind, value change, whole sentence) 
			 $email_template_id = str_replace('[date]', $date, $email_template_id); // (what tofind, value change, whole sentence) 


			 $email_data['subject'] = 'Re-activation of Account for '.$stype;
			 //$email_data['bcc'] = 'justine.jovero@novawareystems.com';
			 $email_data['content'] = nl2br($email_template_id);

			 $email_data['to'] = $emailaddress;
			 $this->common_model->send_email_notification($email_data);

			 
			 // email to creator
	         $where_arr = array('VENDOR_INVITE_ID' => $data['vendorinviteid']);
			 $created_by_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);
			 
			 //jay 
			 $list_of_vendors_creator[$data['vendorinviteid']] = array(
				'CREATOR_USER_ID' => $created_by_id,
				'VENDOR_ID' => $vendor_Id
			 );
	         //jay end
			 
			 if( ! empty($vendors_primary_deactivated_flag[$i]) && $vendors_primary_deactivated_flag[$i] == 1){
				 $where_arr = array('USER_ID' => $created_by_id);
				 $creator_emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

				 $where_arr = array('USER_ID' => $created_by_id);
				 
				 $fname = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_FIRST_NAME', $where_arr);
				 $mname = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_MIDDLE_NAME', $where_arr);
				 $lname = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_LAST_NAME', $where_arr);
				 $creator_name = $this->trim_name(array(array(
				 	'USER_FIRST_NAME' => $fname,
				 	'USER_MIDDLE_NAME' => $mname,
				 	'USER_LAST_NAME' => $lname
				 )));

		/*		 $where_arr = array('TEMPLATE_TYPE' => 24,
									'ACTIVE'	 => 1);
				 $email_template_id = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);*/


				 //<--dito yun

				 $email_template_id = "";
				 $email_template_id =  $email_creator;

				 $email_template_id = str_replace('[vendor_name]', $vendorname, $email_template_id); // (what tofind, value change, whole sentence) 
				 $email_template_id = str_replace('[created_by]', $creator_name, $email_template_id); // (what tofind, value change, whole sentence) 

				 $email_data['subject'] = $vendorname . ' - Account Reactivated ('.$stype.') ';
				 //$email_data['bcc'] = 'justine.jovero@novawareystems.com';
				 $email_data['content'] = nl2br($email_template_id);

				 $email_data['to'] = $creator_emailaddress;
				 $this->common_model->send_email_notification($email_data);

				 //$this->response($portal);

				 	$tportal = $portal;

			 // $email_template_id = str_replace('[vendorname]', $vendorname, $email_template_id); // (what tofind, value change, whole sentence) 
			 // $email_template_id = str_replace('[type]', $stype, $email_template_id); // (what tofind, value change, whole sentence) 
			 // $email_template_id = str_replace('[date]', $date, $email_template_id); // (what tofind, value change, whole sentence) 

				 		$tportal[0]['SUBJECT'] = str_replace('[vendorname]', $vendorname, $tportal[0]['SUBJECT']);
						$tportal[0]['TOPIC'] = str_replace('[vendorname]', $vendorname, $tportal[0]['TOPIC']);
						$tportal[0]['MESSAGE'] = $email_template_id;
						// $tportal[0]['MESSAGE'] = str_replace('[vendor]', $vendorname, $tportal[0]['MESSAGE']);
						// $tportal[0]['MESSAGE'] = str_replace('[approver]', $appname, $tportal[0]['MESSAGE']);


						$insert_array = array(
						'SUBJECT' => $tportal[0]['SUBJECT'],
						'TOPIC' => $tportal[0]['TOPIC'],
						'DATE_SENT' => date('Y-m-d H:i:s'),
						'BODY' => $tportal[0]['MESSAGE'],
						'TYPE' => 'notification',
						'SENDER_ID' =>0, //portal
						'RECIPIENT_ID' => $created_by_id , //can be changed in query
						'VENDOR_ID' => $vendor_Id
						);

					
						$model_data = $this->mail_model->send_message($insert_array);

				 
			 }else{ 
				
				$whr = array('VENDOR_INVITE_ID' => $data['vendorinviteid'.$i]);
				$cb = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY,USER_ID');
				$cbname = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $cb[0]['CREATED_BY']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
				$cbname = $this->trim_name($cbname);
				$vname = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $cb[0]['USER_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
				$vname = $this->trim_name($vname);  
				$aname = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $data['user_id']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
				$aname = $this->trim_name($aname);  


				$vrdstaff = $this->common_model->select_query_notin('SMNTP_USERS_MATRIX', array('USER_ID' => $cb[0]['CREATED_BY']), "VRDSTAFF_ID IS NOT NULL", "VRDSTAFF_ID");
				

/*
				$rs_msg =  $portal;

				$rs_msg[0]['SUBJECT'] = str_replace('[vendorname]', $vname, $rs_msg[0]['SUBJECT']);
				$rs_msg[0]['TOPIC'] = str_replace('[vendorname]', $vname, $rs_msg[0]['TOPIC']);
				$rs_msg[0]['MESSAGE'] = str_replace('[buyer]', $cbname, $rs_msg[0]['MESSAGE']);
				$rs_msg[0]['MESSAGE'] = str_replace('[vendor]', $vname, $rs_msg[0]['MESSAGE']);
				$rs_msg[0]['MESSAGE'] = str_replace('[approver]', $aname, $rs_msg[0]['MESSAGE']);
				//$rs_msg[0]['MESSAGE'] = str_replace('[approvername]', $appname, $rs_msg[0]['MESSAGE']);

				$insert_array = array(
				'SUBJECT' => $rs_msg[0]['SUBJECT'],
				'TOPIC' => $rs_msg[0]['TOPIC'],
				'DATE_SENT' => date('d-M-Y h:i:s A'),
				'BODY' => $rs_msg[0]['MESSAGE'],
				'TYPE' => 'notification',
				'SENDER_ID' => $this->post('user_id'),
				'RECIPIENT_ID' => $cb[0]['CREATED_BY'], //can be changed in query
				'VENDOR_ID' => $cb[0]['USER_ID']
				);



				$model_data = $this->mail_model->send_message($insert_array);*/

				//$this->response($insert_array);

				// foreach ($vrdstff as $key => $value) {
				// $tmess = $react_em;

				// $tmess[0]['CONTENT'] = str_replace('[vrdstaff]', replace, subject)
				// }

				$ic = 0;
				for($ic = 0;$ic < count($vrdstaff);$ic++){

					if(isset($vrdstaff[$ic])){ //Jay - Add condition to check if key does exists.
						$tmess = $react_em;
						$tportal =  $portal;

						$vrd_info = $this->common_model->select_query('SMNTP_USERS', array('USER_ID' => $vrdstaff[$ic]['VRDSTAFF_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL, USER_ID');

						$tname = array(array('USER_FIRST_NAME' => $vrd_info[0]['USER_FIRST_NAME'],'USER_MIDDLE_NAME' => $vrd_info[0]['USER_MIDDLE_NAME'],'USER_LAST_NAME' => $vrd_info[0]['USER_LAST_NAME']));

						$tname = $this->trim_name($tname);
						$tmess[0]['CONTENT'] = str_replace('[vrdstaff]', $tname, $tmess[0]['CONTENT']);
						$tmess[0]['CONTENT'] = str_replace('[vendorname]', $vname, $tmess[0]['CONTENT']);
						$tmess[0]['CONTENT'] = str_replace('[approver]', $aname, $tmess[0]['CONTENT']);
						$ed['subject'] = $vname . ' - Account Reactivated ('.$stype.') ';
						$ed['bcc'] = '';
						$ed['content'] = nl2br($tmess[0]['CONTENT']);
						$ed['to'] = $vrd_info[0]['USER_EMAIL'];
						$this->common_model->send_email_notification($ed);

						//portal			

						$tportal[0]['SUBJECT'] = str_replace('[vendorname]', $vname, $tportal[0]['SUBJECT']);
						$tportal[0]['TOPIC'] = str_replace('[vendorname]', $vname, $tportal[0]['TOPIC']);
						$tportal[0]['MESSAGE'] = $tmess[0]['CONTENT'];
						// $tportal[0]['MESSAGE'] = str_replace('[vendor]', $vname, $tportal[0]['MESSAGE']);
						// $tportal[0]['MESSAGE'] = str_replace('[approver]', $aname, $tportal[0]['MESSAGE']);


						$insert_array = array(
						'SUBJECT' => $tportal[0]['SUBJECT'],
						'TOPIC' => $tportal[0]['TOPIC'],
						'DATE_SENT' => date('Y-m-d H:i:s'),
						'BODY' => $tportal[0]['MESSAGE'],
						'TYPE' => 'notification',
						'SENDER_ID' =>0, //portal
						'RECIPIENT_ID' => $vrd_info[0]['USER_ID'], //can be changed in query
						'VENDOR_ID' => $cb[0]['USER_ID']
						);
				/*		$this->response($insert_array);*/
						$model_data = $this->mail_model->send_message($insert_array);
					//	$this->response($insert_array);
					}
				}

				// $ic = 0;
				// for($ic = 0;$ic < count($vrdinfo);$ic++){

				// 	if(isset($vrdinfo[$ic])){ //Jay - Add condition to check if key does exists.
				// 		$tmess = $react_em;
				// 		$tportal =  $portal;
				// 		$tname = array(array('USER_FIRST_NAME' => $vrdinfo[$ic]['USER_FIRST_NAME'],'USER_MIDDLE_NAME' => $vrdinfo[$ic]['USER_MIDDLE_NAME'],'USER_LAST_NAME' => $vrdinfo[$ic]['USER_LAST_NAME']));

				// 		$tname = $this->trim_name($tname);
				// 		$tmess[0]['CONTENT'] = str_replace('[vrdstaff]', $tname, $tmess[0]['CONTENT']);
				// 		$tmess[0]['CONTENT'] = str_replace('[vendorname]', $vname, $tmess[0]['CONTENT']);
				// 		$tmess[0]['CONTENT'] = str_replace('[approver]', $aname, $tmess[0]['CONTENT']);
				// 		$ed['subject'] = 'Account Reactivated';
				// 		$ed['bcc'] = '';
				// 		$ed['content'] = nl2br($tmess[0]['CONTENT']);
				// 		$ed['to'] = $vrdinfo[$ic]['USER_EMAIL'];
				// 		$this->common_model->send_email_notification($ed);

				// 		//portal			

				// 		$tportal[0]['SUBJECT'] = str_replace('[vendorname]', $vname, $tportal[0]['SUBJECT']);
				// 		$tportal[0]['TOPIC'] = str_replace('[vendorname]', $vname, $tportal[0]['TOPIC']);
				// 		$tportal[0]['MESSAGE'] = $tmess[0]['CONTENT'];
				// 		// $tportal[0]['MESSAGE'] = str_replace('[vendor]', $vname, $tportal[0]['MESSAGE']);
				// 		// $tportal[0]['MESSAGE'] = str_replace('[approver]', $aname, $tportal[0]['MESSAGE']);


				// 		$insert_array = array(
				// 		'SUBJECT' => $tportal[0]['SUBJECT'],
				// 		'TOPIC' => $tportal[0]['TOPIC'],
				// 		'DATE_SENT' => date('d-M-Y h:i:s A'),
				// 		'BODY' => $tportal[0]['MESSAGE'],
				// 		'TYPE' => 'notification',
				// 		'SENDER_ID' => $data['user_id'],
				// 		'RECIPIENT_ID' => $vrdinfo[$ic]['USER_ID'], //can be changed in query
				// 		'VENDOR_ID' => $cb[0]['USER_ID']
				// 		);
				// /*		$this->response($insert_array);*/
				// 		$model_data = $this->mail_model->send_message($insert_array);
				// 	//	$this->response($insert_array);
				// 	}
				// }
				//$this->response($vrdinfo);
				
				//$list_of_vendors_creator[$data['vendorinviteid']] = $vrdinfo_data;
			 }
	    }
	    
	    /*$c = 1;
	    for($c = 1;$c <= $data['numselected'];$c++){

	    	  // $this->response($data);
			$whr = array('VENDOR_INVITE_ID' => $data['vendorinviteid'.$c]);
	    	$cb = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY,USER_ID');
	    	$cbname = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $cb[0]['CREATED_BY']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
	    	$cbname = $this->trim_name($cbname);
	    	$vname = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $cb[0]['USER_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
	    	$vname = $this->trim_name($vname);  
	    	$aname = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $data['user_id']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
	    	$aname = $this->trim_name($aname);  
	    	

	    	$rs_msg =  $portal;

	    	$rs_msg[0]['SUBJECT'] = str_replace('[vendorname]', $vname, $rs_msg[0]['SUBJECT']);
			$rs_msg[0]['TOPIC'] = str_replace('[vendorname]', $vname, $rs_msg[0]['TOPIC']);
			$rs_msg[0]['MESSAGE'] = str_replace('[buyer]', $cbname, $rs_msg[0]['MESSAGE']);
			$rs_msg[0]['MESSAGE'] = str_replace('[vendor]', $vname, $rs_msg[0]['MESSAGE']);
			$rs_msg[0]['MESSAGE'] = str_replace('[approver]', $aname, $rs_msg[0]['MESSAGE']);
			//$rs_msg[0]['MESSAGE'] = str_replace('[approvername]', $appname, $rs_msg[0]['MESSAGE']);

			$insert_array = array(
			'SUBJECT' => $rs_msg[0]['SUBJECT'],
			'TOPIC' => $rs_msg[0]['TOPIC'],
			'DATE_SENT' => date('d-M-Y h:i:s A'),
			'BODY' => $rs_msg[0]['MESSAGE'],
			'TYPE' => 'notification',
			'SENDER_ID' => $this->post('user_id'),
			'RECIPIENT_ID' => $cb[0]['CREATED_BY'], //can be changed in query
			'VENDOR_ID' => $cb[0]['USER_ID']
			);

			$model_data = $this->mail_model->send_message($insert_array);

			//$this->response($insert_array);

			// foreach ($vrdstff as $key => $value) {
			// $tmess = $react_em;

			// $tmess[0]['CONTENT'] = str_replace('[vrdstaff]', replace, subject)
			// }

			$ic = 0;
			
			for($ic = 0;$ic < count($vrdstff);$ic++){

				if(isset($vrdinfo[$ic])){ //Jay - Add condition to check if key does exists.
					$tmess = $react_em;
					$tportal =  $portal;
					$tname = array(array('USER_FIRST_NAME' => $vrdinfo[$ic]['USER_FIRST_NAME'],'USER_MIDDLE_NAME' => $vrdinfo[$ic]['USER_MIDDLE_NAME'],'USER_LAST_NAME' => $vrdinfo[$ic]['USER_LAST_NAME']));

					$tname = $this->trim_name($tname);
					$tmess[0]['CONTENT'] = str_replace('[vrdstaff]', $tname, $tmess[0]['CONTENT']);
					$tmess[0]['CONTENT'] = str_replace('[vendorname]', $vname, $tmess[0]['CONTENT']);
					$tmess[0]['CONTENT'] = str_replace('[approver]', $aname, $tmess[0]['CONTENT']);
					$ed['subject'] = 'Account Reactivated';
					$ed['bcc'] = 'justine.jovero@novawareystems.com';
					$ed['content'] = nl2br($tmess[0]['CONTENT']);
					$ed['to'] = $vrdinfo[$ic]['USER_EMAIL'];
					$this->common_model->send_email_notification($ed);

					//portal			

					$tportal[0]['SUBJECT'] = str_replace('[vendorname]', $vname, $tportal[0]['SUBJECT']);
					$tportal[0]['TOPIC'] = str_replace('[vendorname]', $vname, $tportal[0]['TOPIC']);
					$tportal[0]['MESSAGE'] = str_replace('[buyer]', $tname, $tportal[0]['MESSAGE']);
					$tportal[0]['MESSAGE'] = str_replace('[vendor]', $vname, $tportal[0]['MESSAGE']);
					$tportal[0]['MESSAGE'] = str_replace('[approver]', $aname, $tportal[0]['MESSAGE']);


					$insert_array = array(
					'SUBJECT' => $tportal[0]['SUBJECT'],
					'TOPIC' => $tportal[0]['TOPIC'],
					'DATE_SENT' => date('d-M-Y h:i:s A'),
					'BODY' => $tportal[0]['MESSAGE'],
					'TYPE' => 'notification',
					'SENDER_ID' => $data['user_id'],
					'RECIPIENT_ID' => $vrdstff[$ic], //can be changed in query
					'VENDOR_ID' => $cb[0]['USER_ID']
					);
					$model_data = $this->mail_model->send_message($insert_array);
				//	$this->response($insert_array);
				}
			}
	    }*/
		$this->response($rs);
	}
	function change_termsofpayment_post()
	{
		$data['VENDOR_INVITE_ID'] = $this->post('invite_id');
		$data1['TERMSPAYMENT'] = $this->post('termsofpayment');
		$data2['AVC_TERMSPAYMENT'] = $this->post('termsofpayment');
		$data3['invite_id'] = $this->post('invite_id');
		$user_id = $this->post('user_id');
		
		$check_vendor_type = $this->registration_model->check_vendor_id($data3);
		$var['registration_type'] = $check_vendor_type[0]['REGISTRATION_TYPE'];	


		if($var['registration_type'] != 4){
			$current_terms_payment = $this->common_model->select_query('SMNTP_VENDOR_STATUS',array('VENDOR_INVITE_ID' => $data['VENDOR_INVITE_ID']),'TERMSPAYMENT')[0]['TERMSPAYMENT'];
			$current_tp_name = $this->common_model->select_query('SMNTP_TERMS_PAYMENT',array('TERMS_PAYMENT_ID' => $current_terms_payment),'TERMS_PAYMENT_NAME')[0]['TERMS_PAYMENT_NAME'];
			$updated_tp_name = $this->common_model->select_query('SMNTP_TERMS_PAYMENT',array('TERMS_PAYMENT_ID' => $data1['TERMSPAYMENT']),'TERMS_PAYMENT_NAME')[0]['TERMS_PAYMENT_NAME'];

			$get_vendor_code = $this->common_model->select_query('SMNTP_VENDOR',array('VENDOR_INVITE_ID' => $data['VENDOR_INVITE_ID']),'VENDOR_ID')[0]['VENDOR_ID'];
			if($get_vendor_code != NULL){
				$insert_pt_al = $this->registration_model->insert_logs($get_vendor_code, $user_id, $current_tp_name, $updated_tp_name, "Terms Payment");	
			}

			$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS',$data1,$data);	
		}else{
			$current_terms_payment = $this->common_model->select_query('SMNTP_VENDOR_STATUS',array('VENDOR_INVITE_ID' => $data['VENDOR_INVITE_ID']),'AVC_TERMSPAYMENT')[0]['AVC_TERMSPAYMENT'];
			$current_tp_name = $this->common_model->select_query('SMNTP_TERMS_PAYMENT',array('TERMS_PAYMENT_ID' => $current_terms_payment),'TERMS_PAYMENT_NAME')[0]['TERMS_PAYMENT_NAME'];
			$updated_tp_name = $this->common_model->select_query('SMNTP_TERMS_PAYMENT',array('TERMS_PAYMENT_ID' => $data2['TERMSPAYMENT']),'TERMS_PAYMENT_NAME')[0]['TERMS_PAYMENT_NAME'];
			
			$get_vendor_code = $this->common_model->select_query('SMNTP_VENDOR',array('VENDOR_INVITE_ID' => $data['VENDOR_INVITE_ID']),'VENDOR_ID')[0]['VENDOR_ID'];
			if($get_vendor_code != NULL){
				$insert_pt_al = $this->registration_model->insert_logs($get_vendor_code, $user_id, $current_tp_name, $updated_tp_name, "Terms Payment");	
			}
			
			$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS',$data2,$data);	
		}
		
		$this->response($user_id);
		// $this->response($rs);
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

	function vrdhead_request_react($data)
	{

		//$this->response($data);
		$this->load->model('mail_model');

		$usinfo = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $data['user_id']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
		$usname = $this->trim_name($usinfo);


		//$created_by = $this->common_model->select_query()


		$vrdhead = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('VRDSTAFF_ID' => $data['user_id']),'VRDHEAD_ID');

	//	$this->response($vrdhead);

		$narray = array();

		$c = 1;

		$vl = array();

		for($c = 1;$c <= $data['numselected'];$c++){
			array_push($vl,$data['vendorinviteid'.$c]);
		}

		$rm = $this->common_model->select_query_wherein('SMNTP_VENDOR_INVITE','VENDOR_INVITE_ID',$vl,'USER_ID,CREATED_BY');



		$vrdhead = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('USER_ID' => $rm[0]['CREATED_BY']),'VRDHEAD_ID');



		
		$rmid = array();

		foreach ($rm as $key => $value) {
			array_push($rmid,$value['USER_ID']);
		}


		$rinfo = $this->common_model->select_query_wherein('SMNTP_USERS','USER_ID',$rmid,'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');

	


		foreach ($vrdhead as $key => $value) {
			if($value['VRDHEAD_ID'] != NULL){
				array_push($narray, $value['VRDHEAD_ID']);
			}
		}



		//email

		$email = $this->common_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => 41),'CONTENT');

			$where_arr_def = array(
				'TYPE_ID' 		=> 1, // for registration
				'STATUS_ID' 	=> 196 //statusid for incomplete
				);
		$portal = $this->common_model->get_message_default($where_arr_def)->result_array();




	// foreach ($rinfo as $key => $value) {
		$cjz = 0;
		for($cjz = 0;$cjz < count($rinfo); $cjz++){

			$value = array(
				'USER_FIRST_NAME' => $rinfo[$cjz]['USER_FIRST_NAME'],
				'USER_MIDDLE_NAME' => $rinfo[$cjz]['USER_MIDDLE_NAME'],
				'USER_LAST_NAME' => $rinfo[$cjz]['USER_LAST_NAME'],
				);


		//$this->response(count($rinfo));
		$j = 0;
		
		$tvname = array(array('USER_FIRST_NAME' => $value['USER_FIRST_NAME'],'USER_MIDDLE_NAME' => $value['USER_MIDDLE_NAME'],'USER_LAST_NAME' => $value['USER_LAST_NAME']));

		$tvname = $this->trim_name($tvname);


		$narray = array_unique($narray);
		

		foreach ($narray as $key => $value) { //loop to catch multiple vrdhead -> can be removed in the future 
			$vrdinfo =  $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $value),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
			$vrdname = $this->trim_name($vrdinfo);
			$temail = $email;
			$temail[0]['CONTENT'] = str_replace('[approver]',$vrdname,$temail[0]['CONTENT']);
			$temail[0]['CONTENT'] = str_replace('[vendor]',$tvname,$temail[0]['CONTENT']);
			$temail[0]['CONTENT'] = str_replace('[requestor]',$usname,$temail[0]['CONTENT']);


			$emailaddress = $vrdinfo[0]['USER_EMAIL'];

			 $email_data['subject'] = $tvname . ' - Account Reactivation Request (Additional Requirements)';
			 //$email_data['bcc'] = 'justine.jovero@novawareystems.com';
			 $email_data['content'] = nl2br($temail[0]['CONTENT']);

			 $email_data['to'] = $emailaddress;
			 $this->common_model->send_email_notification($email_data);

			 // end email 

			 //portal

			 $tmportal = $portal;


				$tmportal[0]['SUBJECT'] = str_replace('[vendorname]', $tvname, $tmportal[0]['SUBJECT']);
				$tmportal[0]['TOPIC'] = str_replace('[vendorname]', $tvname, $tmportal[0]['TOPIC']);
				$tmportal[0]['MESSAGE'] = $temail[0]['CONTENT'];
				// $tmportal[0]['MESSAGE'] = str_replace('[sendername]', $usname, $tmportal[0]['MESSAGE']);
				// $tmportal[0]['MESSAGE'] = str_replace('[vendorname]', $tvname, $tmportal[0]['MESSAGE']);


				$insert_array = array(
				'SUBJECT' => $tmportal[0]['SUBJECT'],
				'TOPIC' => $tmportal[0]['TOPIC'],
				'DATE_SENT' => date('Y-m-d H:i:s'),
				'BODY' => $tmportal[0]['MESSAGE'],
				'TYPE' => 'notification',
				'SENDER_ID' =>0, //portal
				'RECIPIENT_ID' =>$value, //can be changed in query
				'VENDOR_ID' => $rmid[$j]
				);


				$model_data = $this->mail_model->send_message($insert_array);
				$j++;

				

		}

	}



		
	


		//-->end email		






	}




}