<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registrationreview extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

		$this->load->view('vendor/invitecreation');
	}

	public function registration_details($vendor_id = null)
	{
		$data['position_id'] 	= $this->session->userdata('position_id');
		$positionname 			= $this->rest_app->get('index.php/common_api/position_name', $data, 'application/json');

		$data['filter_city'] 	= $this->rest_app->get('index.php/vendor/registration_api/filter_city', '', 'application/json');
		$data['filter_state'] 	= $this->rest_app->get('index.php/vendor/registration_api/filter_state', '', 'application/json');
		$data['filter_country'] = $this->rest_app->get('index.php/vendor/registration_api/filter_country', '', 'application/json');
		$data['filter_region'] = $this->rest_app->get('index.php/vendor/registration_api/filter_region', '', 'application/json');

		$data['display'] = 'none';
		//echo $positionname;
		if($positionname == 'HTS')
			$data['display'] = 'inline';

		$data['vendor_id'] = $vendor_id;
		$data['user_id'] 	= $this->session->userdata('user_id');

		$param2['vendor_id'] = $data['vendor_id'];
		//print_r($param2); return;
		$param['invite_id'] = $this->rest_app->get('index.php/vendor/registration_api/get_vendor_invite_id', $param2, 'application/json');
		//print_r($param); return;
		$param['position_id'] = $data['position_id'];
		
		
		$d['user_id'] 	= $data['user_id']; 
		$d['invite_id'] 	= $param['invite_id'];

		$data['invite_id'] = $param['invite_id'];
        $vendor_data = $this->rest_app->get('index.php/vendor/inviteapproval_api/vendor_pass_request_vendor', $d, 'application/json');
        //print_r(json_decode($data['invite_id'])); return;
        //print_r($vendor_data); return;
        $data['approval_date'] = $vendor_data->query[0]->APPROVAL_DATE;
        $data['vendorname'] = $vendor_data->query[0]->VENDOR_NAME;
        //$data['user_email_outright'] = $vendor_data->query[0]->EMAIL_ADD_OUTRIGHT;
        //$data['user_email_sc'] = $vendor_data->query[0]->EMAIL_ADD_SC;

		$rs_vt = $this->rest_app->get('index.php/vendor/registration_api/vendor_type', $d, 'application/json');
		$data['vendor_type'] = $rs_vt->vendor_type;
		$data['trade_vendor_type'] = $rs_vt->trade_vendor_type;

		$rs_vendor_info = $this->rest_app->get('index.php/vendor/registration_api/vendor_info', $d, 'application/json');
		$data['registration_type'] = $rs_vendor_info->registration_type;
		$data['prev_registration_type'] = $rs_vendor_info->prev_registration_type;
		$data['vendor_code'] = $rs_vendor_info->vendor_code;
		$data['vendor_code_02'] = $rs_vendor_info->vendor_code_02;
		$data['current_status'] = $rs_vendor_info->current_status;
		$param['registration_type'] = $data['registration_type'];

		//var_dump($rs_vendor_info);die;
		
		$rs_vendorid = (array)$this->rest_app->get('index.php/vendor/registration_api/fetch_vendorid_approval', $param, 'application/json'); // also getting vendor name based on invite id

		$data['s']= $rs_vendorid;
		
		//echo "<pre>";
		//print_r($rs_vt);
		//exit();
		
		//if($data['registration_type'] != 4){
		//	$data['termspayment'] = $rs_vendorid['termspayment'];
		//}else{
		//	$data['termspayment'] = $rs_vendorid['avc_termspayment'];
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
		
		$data['payment_locked'] = 'disabled';
		if ($rs_vendorid['status_id']==10) // terms payment is only avaiable when it is for review.
			$data['payment_locked'] = '';

		$data['status_id'] = $rs_vendorid['status_id'];

		if(!empty($data['termspayment']))
			$data['payment_locked'] = 'disabled';

/*		$array_index[''] = '-- Select --';
		$data['payment_terms'] = array_merge($array_index, (array)$this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json'));*/
		//old code. uncomment if new code is not working


		$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');
		

		$inv =array('invite_id' => $param['invite_id']);
		$data['status'] = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', $inv, 'application/json');
		
		$data['waive_data'] = $this->rest_app->get('index.php/vendor/registration_api/get_waive_data', array('invite_id' => $param['invite_id']), 'application/json');
		//$this->rest_app->debug();
		//echo "<pre>";var_dump($data['waive_data']->rd_waive_result);die();
		$data['invite_id'] = $param['invite_id'];
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
		//$data['termspayment'] = '';
		
		$data['ad_approved_items'] = $this->rest_app->get('index.php/vendor/invitecreation_api/get_all_ad_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');

		$this->load->view('vendor/registration_review', $data);
	}

	function get_vendor_data()
	{
		$data['user_id'] 	= $this->session->userdata('user_id');
		$data['vendor_id'] 	= $this->input->post('vendor_id');

		// if (!empty($data['vendor_id']))
		// {
			$rs = $this->rest_app->get('index.php/vendor/registration_api/vendor_data', $data, 'application/json');
			//$this->rest_app->debug();
			//echo "<pre>";
			//print_r($rs);
			//var_dump($rs);die;
			echo json_encode($rs);
		// }
		// else
		// 	echo 0; // it means no data
	}

	function review_registration()
	{
		$data = $_POST;
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$status 				= $this->input->post('status'); // 1 draft, 2 submit, 3 approve, 4 reject/incomplete
		
		$rs = $this->rest_app->put('index.php/vendor/registrationreview_api/registration_review', $data, 'text');
		//print_r($rs);
		//die();
		
		// Draft
		if($data['status'] == 1){
			echo 1;
			return 1;
		}
		if (isset($rs->status) && $rs->status)
		{
			if($rs->next_status == 19){
				$res = $this->rest_app->put('index.php/vendor/registrationreview_api/completed_notif', $data, 'text');
			}
			if($status == 4 && $rs->next_status == 195){
				$res = $this->rest_app->put('index.php/vendor/registrationreview_api/incomplete_additional_notif', $data, 'text');
			}
			if ($status == 2)
			{
				if ($rs->message != '')
				{
					$post_data['user_id'] = $data['user_id'];
					$post_data['type'] 			= 'notification';
					$post_data['recipient_id'] 	= $rs->recipient_id;
					$post_data['mail_subj'] 	= $rs->subject;
					$post_data['mail_topic'] 	= $rs->topic;
					$post_data['mail_body'] 	= $rs->message;
					$post_data['vendor_id'] 	= $rs->vendor_id;
					$post_data['invite_id'] 	= $rs->invite_id;

					// print_r($post_data);
					$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
				}
			}else if($rs->next_status == 11){
				$gmd = $this->rest_app->get('index.php/mail/get_message_default', array('type_id' => 1, 'status_id' => 11), 'application/json');
				
				$u_umn = $this->session->userdata('user_middle_name');
				$u_uln = $this->session->userdata('user_last_name');
				$u_ufn = $this->session->userdata('user_first_name');
				$vrdname = $u_ufn 
				. (!empty($u_umn) ? ' ' . $u_umn : '') 
				. (!empty($u_uln) ? ' ' . $u_uln : '') ;
			
				//Incomplete Primary requirements
				$post_data['user_id'] = $data['user_id'];

				$post_data['type'] 			= 'notification';
				$post_data['recipient_id'] 	= $rs->vendor_user_id;
				$post_data['mail_subj'] = str_replace('[vendorname]', $rs->email_vendorname, $gmd->SUBJECT);
				$post_data['mail_topic'] = str_replace('[vendorname]', $rs->email_vendorname, $gmd->TOPIC);
					
				$message = str_replace('[vendorname]', $rs->email_vendorname, $gmd->MESSAGE);
				$message = str_replace('[vrdstaff]', $vrdname, $message);
				$message = str_replace('[remarks]', $rs->remarks, $message);
				
				$post_data['mail_body'] = $message;
				$post_data['vendor_id'] 	= $rs->vendor_user_id;
				$post_data['invite_id'] 	= $rs->invite_id;
				// print_r($post_data);
				$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
			
			}
			echo 1;
		}
		else
		{
			echo $rs->error;
		}
	}

	function submit_review()
	{
		$data = $_POST;
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$rs = $this->rest_app->put('index.php/vendor/registrationreview_api/submit_review', $data, 'text');
		//echo('<pre>');
		//print_r($rs);
		//die();
		//$this->rest_app->debug();
		if (isset($rs->status) && $rs->status)
		{
			if ($rs->message != '' && $rs->recipient_id != '')
			{
				$post_data['user_id'] = $data['user_id'];

				$post_data['type'] 			= 'notification';
				$post_data['recipient_id'] 	= $rs->recipient_id;
				$post_data['mail_subj'] 	= $rs->subject;
				$post_data['mail_topic'] 	= $rs->topic;
				$post_data['mail_body'] 	= $rs->message;
				$post_data['vendor_id'] 	= $rs->vendor_id;
				$post_data['invite_id'] 	= $rs->invite_id;
				//print_r($post_data);
				$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
				//$this->rest_app->debug();
				///Email
				$email_data['to'] = $rs->sender_email[0]->USER_EMAIL;
				$email_data['subject'] = $rs->vendorname . ' - ' . $rs->topic;
				$email_data['content'] = $rs->message;
				$send_email = $this->rest_app->post('index.php/common_api/send_email_message', $email_data, '');
			}
			//die();
			echo 1;
		}
		else
		{
			//echo $rs->error;
			//var_dump( $rs);
		}
	}

	function validate_registration($vendor_id = null)
	{
		$data['filter_city'] 	= $this->rest_app->get('index.php/vendor/registration_api/filter_city', '', 'application/json');
		$data['filter_state'] 	= $this->rest_app->get('index.php/vendor/registration_api/filter_state', '', 'application/json');
		$data['filter_country'] = $this->rest_app->get('index.php/vendor/registration_api/filter_country', '', 'application/json');

		$data['vendor_id'] = $vendor_id;
		$data['validate'] = true;
		$data['user_id'] 	= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');

		$param2['vendor_id'] = $data['vendor_id'];
		$param['invite_id'] = $this->rest_app->get('index.php/vendor/registration_api/get_vendor_invite_id', $param2, 'application/json');
		$param['position_id'] = $data['position_id'];
		$rs_vendorid = (array)$this->rest_app->get('index.php/vendor/registration_api/fetch_vendorid_approval', $param, 'application/json'); // also getting vendor name based on invite id
		
		$d['user_id'] 	= $data['user_id']; 
		$d['invite_id'] 	= $param['invite_id'];

		$rs_vt = $this->rest_app->get('index.php/vendor/registration_api/vendor_type', $d, 'application/json');
		$data['vendor_type'] = $rs_vt->vendor_type;
		$data['trade_vendor_type'] = $rs_vt->trade_vendor_type;
		
		//echo "<pre>"; var_dump($data);"</pre>";
		$rs_vendor_info = $this->rest_app->get('index.php/vendor/registration_api/vendor_info', $d, 'application/json');
		$data['registration_type'] = $rs_vendor_info->registration_type;
		$data['vendor_code'] = $rs_vendor_info->vendor_code;
		$data['vendor_code_02'] = $rs_vendor_info->vendor_code_02;
		$data['current_status'] = $rs_vendor_info->current_status;
		$param['registration_type'] = $data['registration_type'];
		
		//$data['termspayment'] = $rs_vendorid['termspayment'];
		//if($data['registration_type'] != 4){
		//	$data['termspayment'] = $rs_vendorid['termspayment'];
		//}else{
		//	$data['termspayment'] = $rs_vendorid['avc_termspayment'];
		//}
		
		// if($data['registration_type'] == 4){
		// 	$data['termspayment'] = $rs_vendorid['avc_termspayment'];
		// 	$data['avc_termspayment'] = $rs_vendorid['termspayment'];
		// }else{
		// 	$data['termspayment'] = $rs_vendorid['termspayment'];
		// 	$data['avc_termspayment'] = $rs_vendorid['avc_termspayment'];
		// }

		if($data['registration_type'] == 4 && $data['vendor_code_02'] != ''){
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

		$data['payment_locked'] = 'disabled';
		if ($rs_vendorid['status_id']==10) // terms payment is only avaiable when it is for review.
			$data['payment_locked'] = '';

		$data['status'] = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', $data, 'application/json');


		//$array_index[''] = '-- Select --';
		$data['payment_terms'] = (array)$this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');


		$inv =array('invite_id' => $param['invite_id']);
		$data['status'] = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', $inv, 'application/json');
		
		$data['waive_data'] = $this->rest_app->get('index.php/vendor/registration_api/get_waive_data', array('invite_id' => $param['invite_id']), 'application/json');
		//$this->rest_app->debug();
		//echo "<pre>";var_dump($data['waive_data']);die();
		$data['invite_id'] = $param['invite_id'];
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
			
			if($data['registration_type'] == 4 && $data['vendor_code_02'] != ''){
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
		
			$data['ad_approved_items'] = $this->rest_app->get('index.php/vendor/invitecreation_api/get_all_ad_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');		
		$this->load->view('vendor/registration_review', $data);
	}

	function save_incomplete_reason()
	{
		$data = $_POST;
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');

		$rs = $this->rest_app->put('index.php/vendor/registrationreview_api/save_incomplete_reason', $data, 'text');
		// $this->rest_app->debug();
		if (isset($rs->status) && $rs->status)
		{
			echo 1;
		}
		else
		{
				echo $rs->error;
		}
	}

	function get_document_agreement()
	{
		$data['ownership'] 			= $this->input->post('ownership');
		$data['trade_vendor_type'] 	= $this->input->post('trade_vendor_type');
		$data['vendor_type'] 		= $this->input->post('vendor_type');
		$data['current_status_id'] 	= $this->input->post('current_status_id');
		$data['category_id']		= $this->input->post('category_id');
		$data['invite_id'] 			=  $this->input->post('invite_id');

		//echo "<pre>";
		//print_r($data);die();
		//$rs = $this->rest_app->get('index.php/vendor/registrationreview_api/filter_document_agreement', $data, 'application/json');
		$rs = $this->rest_app->get('index.php/vendor/registrationreview_api/filter_documents', $data, 'application/json');
		//$this->rest_app->debug();
		//echo "<pre>";
		//print_r($rs);
		//echo '</pre>';
		//die();
		
		echo json_encode($rs);
	}

	function get_inc_reason()
	{
		$data['cbo_da'] = $this->input->post('cbo_da');
		//echo "<pre>";
		//print_r($this->input->post());
		//die();
		$rs = $this->rest_app->get('index.php/vendor/registrationreview_api/inc_reason', $data, 'application/json');

		echo json_encode($rs);
	}

	function generate_pdf()
	{
		$vendor_id = $this->input->post('vendor_id');
		$data = '';

		$this->load->library('mpdf_gen');
		$pdf_filename = 'pdf-vendor-details-'.date('YmdHis').'-'.$vendor_id.'.pdf';
		// $stylecss = file_get_contents(base_url().'assets/css/pdf_award.css'); //Style for pdf

	 	$mpdf = new mPDF();
	    $mpdf->useSubstitutions = FALSE;
	    $mpdf->simpleTables = TRUE; //Disable for complex table
	    $mpdf->packTableData = TRUE;
	    // $mpdf->WriteHTML($stylecss,1); //Styling css
	    $mpdf->WriteHTML($this->load->view('vendor/vendor_details_1_pdf_view.php',$data,true),0); //Html template
	    $mpdf->Output($pdf_filename,'D');  // F = file creation, D = direct download
	}

	public function print_template($id = null)
	{
		$data['user_id'] 	= $this->session->userdata('user_id');
		$data['vendor_id'] 	= $id;

		$data['rs'] = $this->rest_app->get('index.php/vendor/registration_api/vendor_data', $data, 'application/json');

		// $this->load->view('vendor/vendor_details_1_pdf_view.php', $data);
		$this->load->view('vendor/vendor_details_2_pdf_view.php', $data);
		// $this->load->view('vendor/vendor_details_3_pdf_view.php', $data);

	}
	
	public function department_pdf(){
		$data = [];
		
		$this->load->view('vendor/add_department_pdf_view.php', $data);
	}	
	
	function save_remarks()
	{
		$d = json_decode($this->input->post('data'));
		$data['vid'] 			= $d->vid;
		$data['remark_type'] 	= $d->remark_type;
		$data['note'] 			= $d->note;
		
		$rs = $this->rest_app->put('index.php/vendor/registrationreview_api/save_remarks', $data, 'text');
	    //$this->rest_app->debug();
		if (isset($rs) && $rs)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
	}
	
	function delete_vendor(){
		$d = json_decode($this->input->post('data'));
		$data['vid'] = $d->vid;
		$data['delReason'] = $d->delReason;
		//$data['sid'] = $d->sid;
		$rs = $this->rest_app->put('index.php/vendor/registrationreview_api/delete_vendor', $data, 'text');
		echo $rs;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
