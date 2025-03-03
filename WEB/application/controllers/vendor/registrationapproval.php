<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Registrationapproval extends CI_Controller
{

	function display_approval($invite_id = 0, $override_position_id = 0)
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['is_open'] = '';

		$data['payment_locked'] = 'disabled';
		if($override_position_id > 0)
			$data['payment_locked'] = '';

		/*if($data['position_id'] == 3 ) // || $data['position_id'] == 9 //di na daw kasama si fashead
			$data['payment_locked'] = '';*/

	//	$array_index[''] = '-- Select --';
		
		$data['invite_id'] = $invite_id;
        $vendor_data = $this->rest_app->get('index.php/vendor/inviteapproval_api/vendor_pass_request_vendor', $data, 'application/json');
        $data['approval_date'] = $vendor_data->query[0]->APPROVAL_DATE;
        $data['vendorname'] = $vendor_data->query[0]->VENDOR_NAME;
        //$data['user_email'] = $vendor_data->query[0]->REQUESTORS_EMAIL_ADD;
            //var_dump($vendor_data); die();
            //print_r($vendor_data->query[0]->VENDOR_NAME); return;

		$positionname = $this->rest_app->get('index.php/common_api/position_name', $data, 'application/json');

		$data['invite_id'] 	= $invite_id;
		$d['user_id'] 	= $data['user_id']; 
		$d['invite_id'] 	= $data['invite_id'];

		$rs_vt = $this->rest_app->get('index.php/vendor/registration_api/vendor_type', $d, 'application/json');
		$data['vendor_type'] = $rs_vt->vendor_type;
		$data['trade_vendor_type'] = $rs_vt->trade_vendor_type;
		
		$rs_vendorid = (array)$this->rest_app->get('index.php/vendor/registration_api/fetch_vendorid_approval', $data, 'application/json'); // also getting vendor name based on invite id
		if ($data['position_id'] != $rs_vendorid['position_id'] && $override_position_id<=0)
			$data['is_open'] = ' disabled';
		$data['suspended'] = '<input type="button" class="btn btn-primary " id="btn_approval_suspend" '.$data["is_open"].' value="Suspend" onclick="suspend_approval();">';
		$data['reg_type_id'] = $rs_vendorid['reg_type_id'];

		if($rs_vendorid['status_id'] == 14 || $rs_vendorid['status_id'] == 16 || $rs_vendorid['status_id'] == 123 || $rs_vendorid['status_id'] == 124)
			$data['suspended'] = '';


		$rs_vendor_info = $this->rest_app->get('index.php/vendor/registration_api/vendor_info', $data, 'application/json');
		$data['registration_type'] = $rs_vendor_info->registration_type;
		$data['prev_registration_type'] = $rs_vendor_info->prev_registration_type;
		$data['cc_vendor_name'] = $rs_vendor_info->cc_vendor_name;
		$data['cc_vendor_code'] = $rs_vendor_info->cc_vendor_code;
		$data['vendor_code_02'] = $rs_vendor_info->vendor_code_02;
		$data['current_status'] = $rs_vendor_info->current_status;

		//$this->rest_app->debug();
		//if($data['registration_type'] == 4 && $data['current_status'] == 19){
		//	if($data['trade_vendor_type'] == 1){
		//		$data['termspayment'] = $rs_vendorid['termspayment'];
		//		$data['avc_termspayment'] = $rs_vendorid['avc_termspayment'];
		//	}else{
		//		$data['termspayment'] = $rs_vendorid['avc_termspayment'];
		//		$data['avc_termspayment'] = $rs_vendorid['termspayment'];
		//	}
		//}else if($data['registration_type'] == 2 && $data['vendor_code_02'] != ''){
		//	if($data['trade_vendor_type'] == 1){
		//		$data['termspayment'] = $rs_vendorid['termspayment'];
		//		$data['avc_termspayment'] = $rs_vendorid['avc_termspayment'];
		//	}else{
		//		$data['termspayment'] = $rs_vendorid['avc_termspayment'];
		//		$data['avc_termspayment'] = $rs_vendorid['termspayment'];
		//	}
		//}else if($data['registration_type'] == 4){
		//	$data['termspayment'] = $rs_vendorid['avc_termspayment'];
		//}else{
		//	$data['termspayment'] = $rs_vendorid['termspayment'];
		//}
		
		if(($data['registration_type'] == 4 && $data['vendor_code_02'] != '') || ($data['prev_registration_type'] == 4) ){
			if($data['trade_vendor_type'] == 1){
				$data['termspayment'] = $rs_vendorid['avc_termspayment'];
				$data['avc_termspayment'] = $rs_vendorid['termspayment'];
			}else{
				$data['termspayment'] = $rs_vendorid['termspayment'];
				$data['avc_termspayment'] = $rs_vendorid['avc_termspayment'];
			}
		}else if($data['vendor_code_02'] != '' && $data['registration_type'] != 5){
			if($data['trade_vendor_type'] == 1){
				$data['termspayment'] = $rs_vendorid['termspayment'];
				$data['avc_termspayment'] = $rs_vendorid['avc_termspayment'];
			}else{
				$data['termspayment'] = $rs_vendorid['avc_termspayment'];
				$data['avc_termspayment'] = $rs_vendorid['termspayment'];
			}
		}else if($data['registration_type'] == 4){
			$data['termspayment'] = $rs_vendorid['avc_termspayment'];
		}else{
			$data['termspayment'] = $rs_vendorid['termspayment'];
		}

		$data['vendor_id'] = $rs_vendorid['vendor_id'];
		$data['vendor_name'] = $rs_vendorid['vendor_name'];
		$data['status_id'] = $rs_vendorid['status_id'];
		$data['position_id'] = $rs_vendorid['position_id'];
		$data['vrdnote_rs'] = $rs_vendorid['vrd_remarks'];
		$data['hatsnote_rs'] = $rs_vendorid['hats_remarks'];
		$data['status_type'] = 1;
		$inv =array('invite_id' => $data['invite_id']);
		$data['status'] = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', $inv, 'application/json');

		$data['note'] = '';
		$data['label'] = '';

		$data['vrdnote'] = '';
		$data['vrdlabel'] = '';
		// 6 = HaTs
		// 5 = VRD HEAD
		if($data['position_id'] == 6 || ($data['position_id'] == 5 && $override_position_id==6))
		{
			$data['label'] = "HaTS Remarks:";
			$data['note'] = '<textarea class="form-control limit-chars" oninput="change_border(this.id)" id="note_hts" name="note_hts" placeholder="Note"  maxlength="300">'.$data['hatsnote_rs'].'</textarea>';

			$data['vrdlabel'] = "VRD Head's Note:";
			$data['vrdnote'] = '<textarea class="form-control limit-chars" id="note_vrd" name="note_vrd" placeholder="Note" readonly   maxlength="300">'.$data['vrdnote_rs'].'</textarea>';
		} else if($data['position_id'] == 5) {
			$data['vrdlabel'] = "VRD Head's Note:";
			$data['vrdnote'] = '<textarea class="form-control limit-chars" oninput="change_border(this.id)" id="note_vrd" name="note_vrd" placeholder="Note"   maxlength="300" >'.$data['vrdnote_rs'].'</textarea>';
		}

		$data['payment_terms'] = (array)$this->rest_app->get('index.php/vendor/registration_api/payment_terms', $data, 'application/json');
		
		//Get Waiver Data Here
		//...
		$data['waive_data'] = $this->rest_app->get('index.php/vendor/registration_api/get_waive_data', array('invite_id' => $data['invite_id']), 'application/json');
		//$this->rest_app->debug();
		if( ! empty($data['waive_data']->rd_waive_result)){
			$data['rsd_remarks'] = $data['waive_data']->rd_waive_result[0]->REMARK;
			$data['waive_rsd_document_chk'] = array();
			
			foreach($data['waive_data']->rd_waive_result as $waive){
				if($waive->WAIVE == 1){
					$data['waive_rsd_document_chk'][] =  $waive->REQDOCS_ID;
				}
			}
		}else{
			$data['rsd_remarks'] = '';
		}
		
		//Additional Documents
		if( ! empty($data['waive_data']->ad_waive_result)){
			$data['ad_remarks'] = $data['waive_data']->ad_waive_result[0]->REMARK;
			$data['waive_ad_document_chk'] = array();
			
			foreach($data['waive_data']->ad_waive_result as $waive){
				if($waive->WAIVE == 1){
					$data['waive_ad_document_chk'][] =  $waive->ADDDOCS_ID;
				}
			}
		}else{
			$data['ad_remarks'] = '';
		}
			
		// Added MSF 20191125 (NA)
		$data['original_file_name'] = '';
		$data['file_path'] = '';
		$approved_items = $this->rest_app->get('index.php/vendor/invitecreation_api/get_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');
		$data['avc_original_file_name'] = '';
		$data['avc_file_path'] = '';
		$avc_approved_items = $this->rest_app->get('index.php/vendor/invitecreation_api/get_avc_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');

		
		if(($data['registration_type'] == 4 && $data['vendor_code_02'] != '') || ($data['prev_registration_type'] == 4)){
			if($data['trade_vendor_type'] == 1){
				if(!empty($avc_approved_items)){
					$data['original_file_name'] = $avc_approved_items[0]->ORIGINAL_FILE_NAME;
					$data['file_path'] = $avc_approved_items[0]->FILE_PATH;
				}
				if(!empty($approved_items)){
					$data['avc_original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
					$data['avc_file_path'] = $approved_items[0]->FILE_PATH;
				}
			}else{
				if(!empty($approved_items)){
					$data['original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
					$data['file_path'] = $approved_items[0]->FILE_PATH;
				}
				if(!empty($avc_approved_items)){
					$data['avc_original_file_name'] = $avc_approved_items[0]->ORIGINAL_FILE_NAME;
					$data['avc_file_path'] = $avc_approved_items[0]->FILE_PATH;
				}
			}
			
		}else if($data['vendor_code_02'] != '' && $data['registration_type'] != 5){
			if($data['trade_vendor_type'] == 1){
				if(!empty($approved_items)){
					$data['original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
					$data['file_path'] = $approved_items[0]->FILE_PATH;
				}
				if(!empty($avc_approved_items)){
					$data['avc_original_file_name'] = $avc_approved_items[0]->ORIGINAL_FILE_NAME;
					$data['avc_file_path'] = $avc_approved_items[0]->FILE_PATH;
				}
			}else{
				if(!empty($avc_approved_items)){
					$data['original_file_name'] = $avc_approved_items[0]->ORIGINAL_FILE_NAME;
					$data['file_path'] = $avc_approved_items[0]->FILE_PATH;
				}
				if(!empty($approved_items)){
					$data['avc_original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
					$data['avc_file_path'] = $approved_items[0]->FILE_PATH;
				}
			}
		}else if($data['registration_type'] == 4){
				if(!empty($avc_approved_items)){
					$data['original_file_name'] = $avc_approved_items[0]->ORIGINAL_FILE_NAME;
					$data['file_path'] = $avc_approved_items[0]->FILE_PATH;
				}
		}else{
			if(!empty($approved_items)){
				$data['original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
				$data['file_path'] = $approved_items[0]->FILE_PATH;
			}
		}
		
		//echo "<pre>";
		//print_r($data);
		//echo "</pre>";
		$data['ad_approved_items'] = $this->rest_app->get('index.php/vendor/invitecreation_api/get_all_ad_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');

		$this->load->view('vendor/registration_approval_buh', $data);

	}

	function get_data()
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 		= $this->session->userdata('position_id');
		$data['vendor_id'] 		= $this->input->post('vendor_id');

		if (!empty($data['vendor_id']))
		{
			$rs = $this->rest_app->get('index.php/vendor/registration_api/vendor_data', $data, 'application/json');
			//$this->rest_app->debug();
			//echo "<pre>";
			//print_r($rs);die();
			echo json_encode($rs);
		}
		else
			echo 0; // it means no data

	}

	function save_approval_process()
	{


		$data['user_id'] 			= $this->session->userdata('user_id');
		$data['user_position_id']	= $this->session->userdata('position_id');
		$data['position_id'] 	= $this->session->userdata('position_id');


		$positionname = $this->rest_app->get('index.php/common_api/position_name', $data, 'application/json');

		$data['reg_type_id'] 				= $this->input->post('reg_type_id');
		$data['status_type'] 			= 1;
		$data['action'] 				= $this->input->post('action');
		$data['current_status_id'] 		= $this->input->post('status_id');
		$data['position_id'] 			= $this->input->post('position_id');
		$data['vendor_id'] 				= $this->input->post('vendor_id');
		$data['invite_id'] 				= $this->input->post('invite_id');
		$data['cbo_tp'] 				= $this->input->post('cbo_tp');
		$data['note_hts'] 				= $this->input->post('note_hts');
		$data['note_hts']				= (empty($data['note_hts']) ? "" : $data['note_hts']);
		$data['note_vrd'] 				= $this->input->post('note_vrd');
		$data['note_vrd']				= (empty($data['note_vrd']) ? "" : $data['note_vrd']);
		$data['reject_remarks']			= $this->input->post('reject_remarks');
		$data['vendor_type_id'] 		= $this->rest_app->get('index.php/vendor/registration_api/vendor_type', $data, 'application/json'); // also getting vendor name based on invite id
		$vendor_type = $data['vendor_type_id']->vendor_type;
		
		$rs_vendorid = $this->rest_app->get('index.php/vendor/registration_api/fetch_vendorid_approval', $data, 'application/json'); // also getting vendor name based on invite id

		if($positionname == 'HTS')
		{
			if( ! empty($rs_vendorid->vrd_remarks)){
				$data['note_vrd'] = $rs_vendorid->vrd_remarks;
			}
		}

		$next_status_data		 		= (array)$this->rest_app->get('index.php/rfq_api/next_status/', $data, 'application/json');

		//print_r($next_status_data);
		//echo "<pre>";
		//print_r($data);
		//die();	
		// $this->rest_app->debug();

		if($data['action'] == 1)
		{
			$data['nxt_position_id']	= $next_status_data['result'][0]->NEXT_POSITION_ID;
			$data['status']				= $next_status_data['result'][0]->APPROVE_STATUS_ID;
		}
		elseif($data['action'] == 2)
		{
			$data['nxt_position_id']	= $data['position_id'];// $next_status_data['result'][0]->NEXT_POSITION_ID;
			$data['status']				= $next_status_data['result'][0]->SUSPEND_STATUS_ID;
		}
		else
		{
			$email_data				 	= $this->rest_app->get('index.php/vendor/registration_api/approval_email_data/', $data, 'application/json');

			$data['nxt_position_id']	= $next_status_data['result'][0]->CURRENT_POSITION_ID;
			if($data['reg_type_id'] == 2 || $data['reg_type_id'] == 3){
				$data['status']				= 19;
			}else{
				$data['status']				= $next_status_data['result'][0]->REJECT_STATUS_ID;
			}
		}

		//print_r($data);
		//die();	
		
		//var_dump($data);die();
		$rs = (array)$this->rest_app->put('index.php/vendor/registration_api/save_approval', $data, 'text');
		//var_dump($rs);die();
		

		//send message to old approvers
		if($data['action'] == 0)
		{
			//$rs_msg = $this->rest_app->get('index.php/vendor/registration_api/message_approvers_vendor', $data, 'application/json');
			//$rs_email = $this->rest_app->get('index.php/vendor/registration_api/email_approvers_vendor', $data, 'application/json');
			//$this->rest_app->debug();
			//var_dump($rs_email);
		}
		
		if(($data['action'] == 1 && $data['status'] == 15) || ($data['action'] == 1 && $data['status'] == 13))
		{
			//fashead/buhead
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_vrdhead', $data, 'text');
		}

		if($data['action'] == 1 && $data['status'] == 17)
		{
			$rs_email = $this->rest_app->get('index.php/vendor/registration_api/message_hats', $data, 'application/json');
			
			//jAY
			/*if($vendor_type == 1 || $vendor_type == 3|| $vendor_type == 4){	
				//FOR TRADE AND NTS ONLY
				//BUHEAD
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_buhead', $data, 'text');
			}else{
				//FOR NTFAS ONLY
				//FASHEAD
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_ghead', $data, 'text');
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_fashead', $data, 'text');
			}
	
			
			//Message Creator
			if($vendor_type == 1 || $vendor_type == 2 || $vendor_type == 3|| $vendor_type == 4){	
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_creator', $data, 'text');
			}*/
			
		}

		if($data['action'] == 2 && $data['status'] == 16)// || $data['status'] == 14)
		{
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_bs_suspended', $data, 'text');

		}

		if($data['action'] == 1 && $data['status'] == 121){
			$rs_email = $this->rest_app->get('index.php/vendor/registration_api/message_buyer', $data, 'application/json');
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_vrdstaff', $data, 'text');
		}
		
		if($data['action'] == 1 && $data['status'] == 190){
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_vrdstaff_two', $data, 'text');
		}

		if(($data['action'] == 0 && $data['status'] == 18) || ($data['action'] == 0 && $data['status'] == 241) || ($data['action'] == 0 && $data['status'] == 242) || ($data['action'] == 0 && $data['status'] == 244) || ($data['action'] == 0 && $data['status'] == 245) || ($data['action'] == 0 && $data['status'] == 246)){

			$data['status'] = 18;
			$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');
	
			if ($rs_msg_vendor->message != '')
			{
				$post_data['user_id'] = $this->session->userdata('user_id');

				$post_data['type'] 			= 'notification';
				$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
				$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
				$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
				$post_data['mail_body'] 	= $rs_msg_vendor->message;
				$post_data['invite_id'] 	= $data['invite_id'];
				//$post_data['vendor_id'] 	= $data['vendor_id'];
				// print_r($post_data);
				$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');

				
				//JAY
				//PORTAL
				$array_vrdheads = array();
				$result = $this->rest_app->get('index.php/vendor/registration_api/get_users_matrix', array('user_id' => $rs_msg_vendor->recipient_id), 'application/json');
				//$this->rest_app->debug();
				//var_dump($result);
				
				//TSENMER
				
				//Send Reject notif if not BUHEAD or FASHEAD
				if($data['position_id'] != 3 && $data['position_id'] != 9){
					if($vendor_type == 1 || $vendor_type == 3 || $vendor_type == 4){	
						//FOR TRADE AND NTS ONLY
						//rs_msg_vendor->recipient_id
						
						//BUHEAD
						
						$array_buheads = array();
						foreach($result as $res){
							$post_data['recipient_id'] = $res->BUHEAD_ID;
							if(!empty($post_data['recipient_id'])){
								if( ! in_array($post_data['recipient_id'], $array_buheads)){	
									$data['other_id'] = $post_data['recipient_id'];
									$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');
									if ($rs_msg_vendor->message != '')
									{
										$post_data['user_id'] = $this->session->userdata('user_id');

										$post_data['type'] 			= 'notification';
										$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
										$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
										$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
										$post_data['mail_body'] 	= $rs_msg_vendor->message . ' teest ';
										$post_data['invite_id'] 	= $data['invite_id'];
									}
									$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
									$array_buheads[] = $post_data['recipient_id'];
								}
							}
							//VRDHEAD
							if($data['position_id'] != 5){
								$post_data['recipient_id'] = $res->VRDHEAD_ID;
								if(!empty($post_data['recipient_id'])){
									if( ! in_array($post_data['recipient_id'], $array_vrdheads)){
										$data['other_id'] = $post_data['recipient_id'];
										$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');

										if ($rs_msg_vendor->message != '')
										{
											$post_data['user_id'] = $this->session->userdata('user_id');

											$post_data['type'] 			= 'notification';
											$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
											$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
											$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
											$post_data['mail_body'] 	= $rs_msg_vendor->message;
											$post_data['invite_id'] 	= $data['invite_id'];
										}


										if($this->session->userdata('user_id') != $rs_msg_vendor->recipient_id){
										$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
										$array_vrdheads[] = $post_data['recipient_id'];
									}

									}
								}
							}

							if($data['position_id'] == 5){
								$post_data['recipient_id'] = $res->VRDHEAD_ID;
								if(!empty($post_data['recipient_id'])){
									if( ! in_array($post_data['recipient_id'], $array_vrdheads)){
										$data['other_id'] = $post_data['recipient_id'];
										$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');

										if ($rs_msg_vendor->message != '')
										{
											$post_data['user_id'] = $this->session->userdata('user_id');

											$post_data['type'] 			= 'notification';
											$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
											$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
											$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
											$post_data['mail_body'] 	= $rs_msg_vendor->message;
											$post_data['invite_id'] 	= $data['invite_id'];
										}


										if($this->session->userdata('user_id') != $rs_msg_vendor->recipient_id){
										$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
										$array_vrdheads[] = $post_data['recipient_id'];
									}

									}
								}
							}
						}
						
					/*	var_dump($array_vrdheads);
						return;*/
						//var_dump($array_buheads);
					}else{

						//FOR NTFAS ONLY
						//FASHEAD
						$array_gheads = array();
						$array_fasheads = array();
						foreach($result as $res){
							$post_data['recipient_id'] = $res->GHEAD_ID;
							if(!empty($post_data['recipient_id'])){
								if( ! in_array($post_data['recipient_id'], $array_gheads)){	
									$data['other_id'] = $post_data['recipient_id'];
									$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');
									if ($rs_msg_vendor->message != '')
									{
										$post_data['user_id'] = $this->session->userdata('user_id');

										$post_data['type'] 			= 'notification';
										$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
										$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
										$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
										$post_data['mail_body'] 	= $rs_msg_vendor->message;
										$post_data['invite_id'] 	= $data['invite_id'];
									}
									$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
									$array_gheads[] = $post_data['recipient_id'];
								}
							}
							//FASHEAD
							$post_data['recipient_id'] = $res->FASHEAD_ID;
							if(!empty($post_data['recipient_id'])){
								if( ! in_array($post_data['recipient_id'], $array_fasheads)){
									$data['other_id'] = $post_data['recipient_id'];
									$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');
									if ($rs_msg_vendor->message != '')
									{
										$post_data['user_id'] = $this->session->userdata('user_id');

										$post_data['type'] 			= 'notification';
										$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
										$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
										$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
										$post_data['mail_body'] 	= $rs_msg_vendor->message;
										$post_data['invite_id'] 	= $data['invite_id'];
									}
									$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
									$array_fasheads[] = $post_data['recipient_id'];
								}
							}

							//VRDHEAD
							if($data['position_id'] != 5){
								$post_data['recipient_id'] = $res->VRDHEAD_ID;
								if(!empty($post_data['recipient_id'])){
									if( ! in_array($post_data['recipient_id'], $array_vrdheads)){
									$data['other_id'] = $post_data['recipient_id'];
										$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');
										if ($rs_msg_vendor->message != '')
										{
											$post_data['user_id'] = $this->session->userdata('user_id');

											$post_data['type'] 			= 'notification';
											$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
											$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
											$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
											$post_data['mail_body'] 	= $rs_msg_vendor->message;
											$post_data['invite_id'] 	= $data['invite_id'];
										}	
										if($this->session->userdata('user_id') != $rs_msg_vendor->recipient_id){
										$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
										$array_vrdheads[] = $post_data['user_id'];
										}							
										
									}
								}
							}
						}
					}
				}else if($data['position_id'] == 9){
					//send to ghead for fashead only - notif
					$array_gheads = array();
					foreach($result as $res){
						$post_data['recipient_id'] = $res->GHEAD_ID;
						if(!empty($post_data['recipient_id'])){
							if( ! in_array($post_data['recipient_id'], $array_gheads)){	
								$data['other_id'] = $post_data['recipient_id'];
								$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');
								if ($rs_msg_vendor->message != '')
								{
									$post_data['user_id'] = $this->session->userdata('user_id');

									$post_data['type'] 			= 'notification';
									$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
									$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
									$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
									$post_data['mail_body'] 	= $rs_msg_vendor->message;
									$post_data['invite_id'] 	= $data['invite_id'];
								}
								$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
								$array_gheads[] = $post_data['recipient_id'];
							}
						}
					}
				}
				if($vendor_type == 1 || $vendor_type == 2 || $vendor_type == 3|| $vendor_type == 4){	
					/*$data['other_id'] = null;
					$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');
					if ($rs_msg_vendor->message != '')
					{
						$post_data['user_id'] = $this->session->userdata('user_id');

						$post_data['type'] 			= 'notification';
						$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
						$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
						$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
						$post_data['mail_body'] 	= $rs_msg_vendor->message;
						$post_data['invite_id'] 	= $data['invite_id'];
					}								
					$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');*/
				}
				//JAY END
			}
			$data['topic'] = $post_data['mail_topic'];
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_info', $data, 'text');
			
			//jAY
			if($vendor_type == 1 || $vendor_type == 3|| $vendor_type == 4){	
				//FOR TRADE AND NTS ONLY
				//BUHEAD
				if($data['position_id'] != 3 && $data['position_id'] != 9){
					$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_buhead_failed', $data, 'text');
				}
			}else{
				//FOR NTFAS ONLY
				//FASHEAD
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_ghead_failed', $data, 'text');
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_fashead_failed', $data, 'text');
			}
			
			
			//VRDHEAD = 5
			if($data['position_id'] != 3 && $data['position_id'] != 9 && $data['position_id'] != 5){
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_vrdhead_failed', $data, 'text');
			}
			//JAY END

			if($data['status'] = 18 && $data['position_id'] == 5){
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_vrdhead_failed', $data, 'text');
			}
			
			//Message Creator
			if($vendor_type == 1 || $vendor_type == 2 || $vendor_type == 3|| $vendor_type == 4){	
				$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_creator_failed', $data, 'text');
			}
		}

		if($data['action'] == 2 && ($data['status'] == 123 || $data['status'] == 124 || $data['status'] == 14)){
			$rs_msg_vendor = $this->rest_app->get('index.php/vendor/registration_api/message_info', $data, 'application/json');
			/*	
			if ($rs_msg_vendor->message != '')
			{
				$post_data['user_id'] = $this->session->userdata('user_id');

				$post_data['type'] 			= 'notification';
				$post_data['recipient_id'] 	= $rs_msg_vendor->recipient_id;
				$post_data['mail_subj'] 	= $rs_msg_vendor->subject;
				$post_data['mail_topic'] 	= $rs_msg_vendor->topic;
				$post_data['mail_body'] 	= $rs_msg_vendor->message;
				$post_data['vendor_id'] 	= $data['vendor_id'];
				$post_data['invite_id'] 	= $data['invite_id'];


				// print_r($post_data);
				$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
					
			}*/
			//$data['topic'] = $post_data['mail_topic'];
		
			 	$rs_email = $this->rest_app->put('index.php/vendor/registration_api/send_email_to', $data, 'text');

			 
			
	/*		var_dump($rs_email);
			return;*/

		}
		
		//Jay
		//If Suspended by HATS
		if($data['action'] == 2 && $data['status'] == 124){
			//Send to GHEAD or BUHEAD
			//Send to VRDHEAD
			
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/suspend_buhead', $data, 'text');
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_ghead_suspended', $data, 'text');
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_fashead_suspended', $data, 'text');
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/suspended_vrdhead', $data, 'text');

		}
		
		//If Suspended by VRDHEAD
		if($data['action'] == 2 && $data['status'] == 16){
	/*			echo 123;
										return;*/
			//Send to GHEAD or BUHEAD or Fashead
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/suspend_buhead', $data, 'text');
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_ghead_suspended', $data, 'text');
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_fashead_suspended', $data, 'text');
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/suspended_vrdhead', $data, 'text');
		}
		
		//If Suspended by FASHEAD
		if($data['action'] == 2 && $data['status'] == 123){
			//Send to GHEAD
			$rs_email = $this->rest_app->put('index.php/vendor/registration_api/message_ghead_suspended', $data, 'text');

		}
		
		//var_dump($data);
		//die();
	}

	function display_vrdh_approval()
	{
		$this->load->view('vendor/registration_approval_vrdh');
	}

	function display_hats_approval()
	{
		$this->load->view('vendor/registration_approval_hats');
	}
}
?>
