<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Registration extends CI_Controller
	{

		function index($invite_id = null)
		{
			//check vendor if accept dpa
			$data['user_id'] 	= $this->session->userdata('user_id');
			$data['invite_id'] 	= $invite_id;

			$rs_vt = $this->rest_app->get('index.php/vendor/registration_api/vendor_type', $data, 'application/json');
			$rs = $this->rest_app->get('index.php/vendor/registration_api/check_dpa', $data, 'application/json'); // also getting vendor name based on invite id
			$rs_vendor_info = $this->rest_app->get('index.php/vendor/registration_api/vendor_info', $data, 'application/json');


			$data['valid'] = $rs->valid;
			$data['vendorname'] = $rs->vendorname;
			$data['vendor_type'] = $rs_vt->vendor_type;
			$data['registration_type'] = $rs_vendor_info->registration_type;
			$data['cc_vendor_name'] = $rs_vendor_info->cc_vendor_name;
			$data['cc_vendor_code'] = $rs_vendor_info->cc_vendor_code;
			$data['vendor_code'] = $rs_vendor_info->vendor_code;
			$data['vendor_code_02'] = $rs_vendor_info->vendor_code_02;
			$data['trade_vendor_type'] = $rs_vt->trade_vendor_type;
			
			if ($rs_vt->vendor_type == 1)
			{
				$data['check_trade'] 				= 'checked';
				$data['check_nontrade']	 			= '';
				$data['disable_trade_vendor_type'] 	= 'disabled';
				$data['check_nontradeservice']		= '';

				if ($rs_vt->trade_vendor_type == 1)
				{
					$data['check_out'] = 'checked';
					$data['check_con'] = '';
				}
				elseif ($rs_vt->trade_vendor_type == 2)
				{
					$data['check_out'] = '';
					$data['check_con'] = 'checked';
				}		
			}
			else
			{
				if($data['vendor_type'] == 3){
					
					$data['check_trade'] 				= '';
					$data['check_nontrade']	 			= '';
					$data['check_nontradeservice']	 	= 'checked';
				}else{
						
					$data['check_trade'] 				= '';
					$data['check_nontrade']	 			= 'checked';
					$data['check_nontradeservice']		= '';
				}
				$data['disable_trade_vendor_type'] 	= 'disabled';

				$data['check_out'] = '';
				$data['check_con'] = '';
			}

			$filter_brand 	= $this->rest_app->get('index.php/vendor/registration_api/filter_brand', array(), 'application/json');
			$filter_city	= $this->rest_app->get('index.php/vendor/registration_api/filter_city', '', 'application/json');
			$filter_state 	= $this->rest_app->get('index.php/vendor/registration_api/filter_state', '', 'application/json');
			$filter_country = $this->rest_app->get('index.php/vendor/registration_api/filter_country', '', 'application/json');
			$data['default_country_id'] = '';
			$data['default_country'] = '';
			// $this->rest_app->debug();

			//getting login splash screen //SMNTP LOGIN SPLASH TMPLT
			$splash_rs = $this->rest_app->get('index.php/vendor/registration_api/splash_screen', '', 'application/json');
			// $this->rest_app->debug();
			$data['dpa_title'] = $splash_rs->dpa_title;
			$data['dpa_message'] = $splash_rs->dpa_message;
			// $data['dpa_sections'] = $splash_rs->dpa_sections;
			$data['dpa_link_label'] = isset($splash_rs->dpa_link_label) ? $splash_rs->dpa_link_label : 'Privacy Policy.';
			
			//
			$brand_array = array();
			if(empty($filter_brand->error)){
				foreach($filter_brand as $row)
				{
					$brand_array[$row->BRAND_ID] = $row->BRAND_NAME;
				}
			}
			$data['brand_array']	 = $brand_array;

			$city_array = array();
			if(empty($filter_city->error)){
				foreach($filter_city as $row)
				{
					$city_array[$row->CITY_ID] = $row->CITY_NAME;
				}
			}
			$data['city_array']	 = $city_array;

			$state_array = array();
			if(empty($filter_state->error)){
				foreach($filter_state as $row)
				{
					$state_array[$row->STATE_PROV_ID] = $row->STATE_PROV_NAME;
				}
			}
			$data['state_array']	 = $state_array;

			$country_array = array();
			if(empty($filter_country->error)){
				foreach($filter_country as $row)
				{
					$country_array[$row->COUNTRY_ID] = $row->COUNTRY_NAME;
					if ($row->DEFAULT_FLAG == 1) {
						$data['default_country_id'] = $row->COUNTRY_ID;
						$data['default_country'] = $row->COUNTRY_NAME;
					}
				}
			}
			$data['country_array']	 = $country_array;

			//getting submit docs notification
			$doc_notification_rs = $this->rest_app->get('index.php/vendor/registration_api/doc_notification', '', 'application/json');
			// $this->rest_app->debug();
			$data['dn_title'] = $doc_notification_rs->dn_title;
			$data['dn_message'] = $doc_notification_rs->dn_message;

			$data['defaults'] = $this->rest_app->get('index.php/vendor/registration_api/default_values', '', 'application/json');
			
			$data['status'] = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', array('invite_id' => $data['invite_id']), 'application/json');
			//echo "<pre>";
			//var_dump ($data['status']);
			//die();
			
			$data['waive_data'] = $this->rest_app->get('index.php/vendor/registration_api/get_waive_data', array('invite_id' => $data['invite_id']), 'application/json');
			//$this->rest_app->debug();
			
			//Primary Documents
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
			//echo "<pre>";
			//print_r($data['waive_ad_document_chk']);
			//echo "</pre>";
			/*echo "<pre>";
			print_r($data);*/
			//echo "</pre>";
			$this->load->view('vendor/registration', $data);
		}

		function registrationmain()
		{
			$param['user_position_id'] = $this->session->userdata('position_id');
			$param['status_type'] = 1;

			$data['user_type_id'] = $this->session->userdata('user_type_id');
			$data['filter_brand'] = $this->rest_app->get('index.php/vendor/registration_api/filter_brand', $param, 'application/json');
			$data['filter_status'] = $this->rest_app->get('index.php/vendor/registration_api/filter_status', $param, 'application/json');

			$brand_array = array();
			if(empty($data['filter_brand']->error)){
				foreach($data['filter_brand'] as $row)
				{
					$brand_array[$row->BRAND_ID] = $row->BRAND_NAME;
				}
			}
			$data['brand_array']	 = $brand_array;

			if( ! empty($data['filter_brand']->error)){
				$data['filter_brand'] = array();
			}
			
			if( ! empty($data['filter_status']->error)){
				$data['filter_status'] = array();
			}
			$business_type = $this->session->userdata('business_type');

			$trade 		= '';
			$nontrade 	= '';
			if (!empty($business_type))
			{
				if ($business_type == 1)
				{
					$trade 		= 'checked';
					$nontrade 	= '';
				}
				else if ($business_type == 2)
				{
					$trade 		= '';
					$nontrade 	= 'checked';
				}
			}
			$data['business_type'] = $business_type;
			$data['position_id'] 		= $param['user_position_id'];
			$data['trade'] 		= $trade;
			$data['nontrade'] 	= $nontrade;

			if ($data['position_id'] == 10) // if vendor
			{
				$param2['user_id'] 	= $this->session->userdata('user_id');
				$vendor_status 		= $this->rest_app->get('index.php/vendor/registration_api/get_vendor_status', $param2, 'application/json');
				// $this->rest_app->debug();
				$data['invite_id'] 			= $vendor_status->invite_id;
				$data['status_id'] 			= $vendor_status->status_id;
				$data['upload_complete'] 	= $vendor_status->upload_complete;
			}
			
			//$this->rest_app->debug();
			$this->load->view('vendor/registration_main', $data);
		}

		function registrationmain_table()
		{
			$data['user_id'] 		= $this->session->userdata('user_id');
			$data['position_id'] 	= $this->session->userdata('position_id');
			$data['vendorname'] 	= $this->input->post('txt_vendorname');
			$data['vendor_type'] 	= explode('.', $this->input->post('vendor_type_filter'))[0];
			$data['trade_vendor_type'] 	= ($data['vendor_type'] != 1 || empty($data['vendor_type'])) ? 0 : explode('.', $this->input->post('vendor_type_filter'))[1];
			$data['tin_no'] 		= $this->input->post('txt_tinno');
			$data['auth_rep'] 		= $this->input->post('txt_auth_rep');
			$data['brand_id'] 		= $this->input->post('cbo_brand');
			$data['brand_name'] 		= $this->input->post('cbo_brand_text');
			$data['status_id'] 		= $this->input->post('cbo_status');
			$data['override_position_id'] = $this->input->post('override_position_id');	
			$data['dashboard_status_id'] = $this->input->post('dashboard_status_id');	
			$data['page_no']		= $this->input->post('page_no');
			$data['sort']			= $this->input->post('sort');
			$data['sort_type']		= $this->input->post('sort_type');

			$rs = $this->rest_app->get('index.php/vendor/registration_api/regtable', $data, 'application/json');
			// $rs = (array)$this->rest_app->get('index.php/vendor/registration_api/regtable', $data, 'application/json');
			
			// //1st dimension
			// foreach ($rs as $key => $value) {
			// 	$type = gettype($rs[$key]);

			// 	if($type == "array" || $type == "object"){

			// 	}else{
			// 		$rs[$key] = $this->security->xss_clean($value);
			// 	}
			// }
			// //2nd dimension
			// for ($i=0; $i < sizeof($rs["query"]); $i++) { 
			// 	foreach ($rs["query"][$i] as $key => $value) {
			// 		$rs["query"][$i]->$key = $this->security->xss_clean($value);
			// 	}
			// }
			
			// //return the data type
			// $rs = (object)$rs;
			
			/*$this->rest_app->debug();
			echo "<pre>";*/
			//print_r($rs);
			//die();
			echo json_encode($rs);
		}
		
		function for_hats_approval()
		{
			$rs = $this->rest_app->get('index.php/vendor/registration_api/hats_approval', null, 'application/json');
			//$this->rest_app->debug();
			echo json_encode($rs);
		}

		function accept_dpa()
		{
			$data['user_id'] 		= $this->session->userdata('user_id');
			$data['invite_id']		= $this->input->post('invite_id');

			$rs = $this->rest_app->post('index.php/vendor/registration_api/accept_dpa', $data);

			echo json_encode($rs);
		}

		function upload_file($type) //$type = 1 Documents , 2 Agreements
		{
			$file_path  = '';
			$error 		= '';
			$orig_name	= '';
			$output = '';

			if (isset($_FILES['fileupload']['name']))
			{
				$orig_name = $_FILES['fileupload']['name'];

				if ($type == 1) // documents
					$upload_type = 'documents';
				elseif ($type == 2) // 2 = agreements
					$upload_type = 'agreements';
				// Added MSF - 20191108 (IJR-10617)
				elseif ($type == 3) // 3 = Approve Items
					$upload_type = 'approved_items';
				elseif ($type == 4) // 3 = Approve Items
					$upload_type = 'change_company_name';

				// if (base_url() == 'http://piccolo/smntp/web/')
				// 	$web_upload_path = 'F:\\\\inetpub\\smscoreonline\\SMNTP\\web\\'.'vendor_upload_'.$upload_type.'\\';
				// elseif(base_url() == 'http://yogi:8080/SMNTP/web/')
				// 	$web_upload_path = 'D:\\\\inetpub\\smscoreonline\\SMNTP\\web\\'.'vendor_upload_'.$upload_type.'\\';
				// else
				// 	$web_upload_path = '/data/lampstack-5.4.14-0/apache2/htdocs/'.'vendor_upload_'.$upload_type.'/';

				$web_upload_path = FCPATH.'vendor_upload_'.$upload_type;

				if(!is_dir($web_upload_path))
			    {
			    	mkdir($web_upload_path, 0777);
			    }

			    $config['upload_path'] = $web_upload_path;
	            $config['allowed_types'] = 'png|jpg|jpeg|pdf|PNG|JPG|JPEG|PDF|octet-stream';
	            $config['max_size'] = '10000';
	            $config['file_name'] = 'upload_'.$upload_type.'_'.time();

	            $this->load->library('upload', $config, 'fileupload');
	    		$this->fileupload->initialize($config);

	            if ( !$this->fileupload->do_upload('fileupload', FALSE))
	            {
	                $error = $this->fileupload->display_errors();
	            }
	            else
	            {
	                $error = '';
	                $data = $this->fileupload->data();

		            // $file_name = $config['file_name'].$data['file_ext'];
		            $file_path = 'vendor_upload_'.$upload_type.'/'.$data['file_name'];
				}
				
				$date_upload = date('m/d/y h:i:s A');
				$output  = '<input type="hidden" id="file_path" name="file_path" value="'.$file_path.'">';
				$output .= '<input type="hidden" id="error" name="error" value="'.$error.'">';
				$output .= '<input type="hidden" id="upload_date" name="upload_date" value="'.$date_upload.'">';
				$output .= '<input type="hidden" id="orig_name" name="orig_name" value="'.$orig_name.'">';
			}else{
				$error  = 'Something went wrong while uploading the file. Please try again.';
				$output = '<input type="hidden" id="error" name="error" value="'.$error.'">';
			}
			echo $output;
		}

		function get_list_docs()
		{
			$data['ownership'] 			= $this->input->post('ownership');
			$data['trade_vendor_type'] 	= $this->input->post('trade_vendor_type');
			$data['vendor_type'] 	= $this->input->post('vendor_type');
			$data['category_id']		= $this->input->post('cat_id');
			$data['invite_id'] 			= $this->input->post('invite_id');
			$data['registration_type']	= $this->input->post('registration_type');
			$data['vendor_code_02']	= $this->input->post('vendor_code_02');
			//echo "<pre>";
			//print_r($data);
			//echo "</pre>";die();
			
			$rs = $this->rest_app->get('index.php/vendor/registration_api/list_docs', $data, 'application/json');
			//$this->rest_app->debug();
			//echo "<pre>";
			//print_r($rs);
			//echo "</pre>";	
			//die();

		/*	var_dump($rs);
			return;*/
			echo json_encode($rs);
		}

		function add_registration()
		{
			$data = $_POST;
			$data['user_id'] 		= $this->session->userdata('user_id');
			$data['position_id'] 	= $this->session->userdata('position_id');
			$data['vendor_id'] 		= $this->session->userdata('vendor_id');
			$data['web_site_url'] 		= rtrim(site_url(), 'index.php');
			
			//echo "<pre>";
			//var_dump($data);die();
			$rs = $this->rest_app->post('index.php/vendor/registration_api/registration_add', $data); // edit if vendor_id exists
			/*echo "<pre>";
			var_dump($rs);die();*/
			//this->rest_app->debug();
			//var_dump($rs->uploaded_file_error); die();
			//return;

			if(isset($rs->transaction_result) && !$rs->transaction_result){
				echo -1;
				return -1;
			}

			if(!empty($rs->uploaded_file_error)){
				echo json_encode($rs->uploaded_file_error);
				exit();
			}
			
			/// Draft
			if($data['status'] == 1){
				echo 1;
				return 1;
			}
			if (isset($rs->status) && $rs->status)
			{
				if (empty($data['vendor_id'])) // no vendor id yet
				{
					$session_data['vendor_id'] = $rs->vendor_id;
					$this->session->set_userdata($session_data);
				}
				
				//195 = Incomplete - Additional Requirements
				//190 = For Additional Requirement Submission
				//11  = Incomplete - Primary
				//10  = Submitted
				if($this->input->post('status_id') == 195 || $this->input->post('status_id') == 11 ||$this->input->post('status_id') == 10 || $rs->next_status == 10|| $this->input->post('status_id') == 190){ 
					//Send Message Notification
					
					if($this->input->post('status_id') == 190){

						$gmd = $this->rest_app->get('index.php/mail/get_message_default', array('type_id' => 1, 'status_id' => 160), 'application/json');
					}else{
						if($this->input->post('status_id') == 11){
							//1001 = resubmitted primary
							$gmd = $this->rest_app->get('index.php/mail/get_message_default', array('type_id' => 1, 'status_id' => 1001), 'application/json');
						}else{
							$gmd = $this->rest_app->get('index.php/mail/get_message_default', array('type_id' => 1, 'status_id' => $rs->next_status), 'application/json');
						}
					}
					
					
					$vendorname = $rs->vendor_info[0]->USER_FIRST_NAME
							. (!empty($rs->vendor_info[0]->USER_MIDDLE_NAME) ? ' ' . $rs->vendor_info[0]->USER_MIDDLE_NAME : '') 
							. (!empty($rs->vendor_info[0]->USER_LAST_NAME) ? ' ' . $rs->vendor_info[0]->USER_LAST_NAME : '') ;
					
					$post_data['type'] 		= 'notification';
					$post_data['mail_subj'] = str_replace('[vendorname]', $vendorname, $gmd->SUBJECT);
					$post_data['mail_topic'] = str_replace('[vendorname]', $vendorname, $gmd->TOPIC);
					$post_data['invite_id'] 	= $rs->invite_id;
					$staffs = $rs->vrdstaffs;
					
					$template_type_id = NULL;
					if($this->input->post('status_id') == 11){
						$template_type_id = 47; // Primary Resubmitted
					}else if($this->input->post('status_id') == 190){
						$template_type_id = 43; // Additional Submitted
					}else{
						$template_type_id = 35; // Additional Resubmitted
					}
					$get = $this->rest_app->get('index.php/mail/get_email_template2', array( 'template_type' => $template_type_id), 'application/json');
					
					// justine_pagarao
					$review_additional = $this->rest_app->get('index.php/mail/get_email_template2', array( 'template_type' => 43), 'application/json');

					foreach($staffs as $staff){
						$post_data['recipient_id'] 	= $staff->USER_ID;
						
						$sender_name = $staff->USER_FIRST_NAME
							. (!empty($staff->USER_MIDDLE_NAME) ? ' ' . $staff->USER_MIDDLE_NAME : '') 
							. (!empty($staff->USER_LAST_NAME) ? ' ' . $staff->USER_LAST_NAME : '') ;
						
						$message = $gmd->MESSAGE;
						
						if($this->input->post('status_id') == 195 || $this->input->post('status_id') == 190){
							$post_data['mail_body']  = str_replace('[vrdstaff]', $sender_name, $message);
							$post_data['mail_body']  = str_replace('[sender]', $sender_name, $post_data['mail_body']);
						}else{
							$post_data['mail_body']  = str_replace('[sendername]', $sender_name, $message);
							$post_data['mail_body']  = str_replace('[vrdstaff]', $sender_name, $post_data['mail_body']);
							$post_data['mail_body']  = str_replace('[sender]', $sender_name, $post_data['mail_body']);
						}
						
						$post_data['mail_body']  = str_replace('[vendorname]', $vendorname, $post_data['mail_body'] );
						$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');

						if($this->input->post('status_id') == 195 || $this->input->post('status_id') == 190 || $this->input->post('status_id') == 11){
							$message = $get->CONTENT;

							$post_data['mail_body']  = str_replace('[vrdstaff]', $sender_name, $message);
							$post_data['mail_body']  = str_replace('[sender]', $sender_name, $post_data['mail_body']);

							$post_data['mail_body']  = str_replace('[vendorname]', $vendorname, $post_data['mail_body']);

							$email_data['to'] = $staff->USER_EMAIL;
							$email_data['subject'] = $vendorname . ' - ' . $post_data['mail_topic'];
							$email_data['content'] = $post_data['mail_body'];	

						}else{
							$email_data['to'] = $staff->USER_EMAIL;
							$email_data['subject'] = $vendorname . ' - ' . $post_data['mail_topic'];
							$email_data['content'] = $post_data['mail_body'];							
						}


					
						$send_email = $this->rest_app->post('index.php/common_api/send_email_message', $email_data, '');
					
						
					}
					// print_r($post_data);
					//--- Naduduplicate ang send ng email dahil nag sesend na sa taas.
					//
					//$res = $this->rest_app->put('index.php/vendor/registration_api/resubmit_additional_info', $data); 
					//$this->rest_app->debug();
					//var_dump($res);
				}
				//var_dump($data);
				//die();
				echo 1;
			}
			else
			{
				// print_r($rs);return;
				echo $rs->error;
			}
		}

		function get_vendor_data()
		{
			$data['user_id'] = $this->session->userdata('user_id');
			$data['vendor_id'] = $this->session->userdata('vendor_id');
			$data['invite_id'] = $this->input->post('invite_id');

			// if (!empty($data['vendor_id']))
			// {
				$rs = $this->rest_app->get('index.php/vendor/registration_api/vendor_data', $data, 'application/json');
				//$this->rest_app->debug();
				//echo "<pre>";
				//print_r($rs);die();
				echo json_encode($rs);
			// }
			// else
			// 	echo 0; // it means no data
		}

		function display_vendor_details($vendor_id)
		{

			$data['user_id'] 		= $this->session->userdata('user_id');
			$data['position_id'] 	= $this->session->userdata('position_id');
			$data['is_open'] = '';
			
			$positionname 			= $this->rest_app->get('index.php/common_api/position_name', $data, 'application/json');

			$data['filter_city'] 	= $this->rest_app->get('index.php/vendor/registration_api/filter_city', '', 'application/json');
			$data['filter_state'] 	= $this->rest_app->get('index.php/vendor/registration_api/filter_state', '', 'application/json');
			$data['filter_country'] = $this->rest_app->get('index.php/vendor/registration_api/filter_country', '', 'application/json');
			
			
			if(!empty($data['filter_city']->error)){
				$data['filter_city'] = array();
			}
			
			if(!empty($data['filter_state']->error)){
				$data['filter_state'] = array();
			}
			
			if(!empty($data['filter_country']->error)){
				$data['filter_country'] = array();
			}
			$data['invite_id']		=  $this->rest_app->get('index.php/vendor/registration_api/invite_id', array('vendor_id'=>$vendor_id), 'application/json');

			//print_r($vendor_id);return;
			$inv =array('invite_id' => $data['invite_id']);
				
				
			$d['user_id'] 	= $data['user_id']; 
			$d['invite_id'] 	= $data['invite_id'];

			
			$rs_vt = $this->rest_app->get('index.php/vendor/registration_api/vendor_type', $d, 'application/json');
			$data['vendor_type'] = $rs_vt->vendor_type;
			$data['trade_vendor_type'] = $rs_vt->trade_vendor_type;

			$rs_vendor_info = $this->rest_app->get('index.php/vendor/registration_api/vendor_info', $d, 'application/json');
			$data['registration_type'] = $rs_vendor_info->registration_type;
			$data['vendor_code'] = $rs_vendor_info->vendor_code;
			$data['vendor_code_02'] = $rs_vendor_info->vendor_code_02;
			$data['prev_registration_type'] = $rs_vendor_info->prev_registration_type;
			$data['current_status'] = $rs_vendor_info->current_status;
			$param['registration_type'] = $data['registration_type'];
			$param['invite_id'] = $data['invite_id'];
			
			//$rs_vendorid = (array) $this->rest_app->get('index.php/vendor/registration_api/fetch_vendorid_approval', array('invite_id'=>$data['invite_id']), 'application/json'); // also getting vendor name based on invite id
			$rs_vendorid = (array)$this->rest_app->get('index.php/vendor/registration_api/fetch_vendorid_approval', $param, 'application/json'); // also getting vendor name based on invite id
			
			//if($data['registration_type'] == 4 && $data['current_status'] != 19){
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

			//$data['termspayment'] = $rs_vendorid['termspayment'];
			//$data['avc_termspayment'] = $rs_vendorid['avc_termspayment'];
			
			//if($data['registration_type'] != 4){
			//	$data['termspayment'] = $rs_vendorid['termspayment'];
			//}else{
			//	$data['termspayment'] = $rs_vendorid['avc_termspayment'];
			//}

			
			$data['status'] = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', $inv, 'application/json');

			//$this->rest_app->debug();
			$data['display'] = 'none';
			//echo $positionname;
			if($data['position_id'] == 6)// 6 = HATS
			{
				$data['display'] = 'inline';
				$data['display_vrd'] = 'inline';
			}

			if($data['position_id'] == 5) //5 = VRD HEAD
				$data['display_vrd'] = 'inline';

			$data['vendor_id'] = $vendor_id;
			$data['view_only'] = 1; //if 1 view only
			$data['validate'] = true;
			$data['user_id'] 	= $this->session->userdata('user_id');
			
			$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');
			$data['payment_locked'] = 'disabled';
			//var_dump($data['invite_id']);die();
			$data['waive_data'] = $this->rest_app->get('index.php/vendor/registration_api/get_waive_data', array('invite_id' => $data['invite_id']), 'application/json');
			//$this->rest_app->debug();
			//echo "<pre>";var_dump($data['waive_data']);die();
			
			//Primary Documents
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
		
			$data['ad_approved_items'] = $this->rest_app->get('index.php/vendor/invitecreation_api/get_all_ad_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');		
			
			$this->load->view('vendor/registration_review', $data); // view of review and validation used for viewing of details
		}

		function get_tooltip()
		{
			$param['label'] = $this->input->post('label');

			$rs = $this->rest_app->get('index.php/vendor/registration_api/show_tooltip', $param, 'application/json');

			echo $rs->tooltip;
		}

		function print_image($file_name)
		{
			$data['file_name'] = base_url().'vendor_upload_documents/'.$file_name;

			$tmp_name = explode('_',$file_name);
			if($tmp_name[1] == "agreements"){
				$data['file_name'] = base_url().'vendor_upload_agreements/'.$file_name;
			}	
			// Added MSF 20191125 (NA)
			else if($tmp_name[1] == "approved"){
				$data['file_name'] = base_url().'vendor_upload_approved_items/'.$file_name;
			}
				
		// if (strpos($file_name, 'upload_agreements') !== false)
		//strpos not working???
			$this->load->view('vendor/print_image', $data);
		}

		// Added MSF - 20191105 (IJR-10612)
		function download_img($file_name){
			$this->load->helper('download');
			
			$new_file_name = base_url().'vendor_upload_documents/'.$file_name;
			$tmp_name = explode('_',$file_name);
			if($tmp_name[1] == "agreements"){
				$new_file_name = base_url().'vendor_upload_agreements/'.$file_name;
			}else if($tmp_name[1] == "approved"){
				$new_file_name = base_url().'vendor_upload_approved_items/'.$file_name;
			}

			$file_content = file_get_contents($new_file_name);
			force_download($file_name,$file_content);

			// Manual Downloading -- Mark Francisco
			/*$path = 'vendor_upload_documents/'.$file_name;
			$filename = $file_name;
			$extracted_filename = explode('.',$filename);
			$total_count_efn = count($extracted_filename);
			$file_extension = $extracted_filename[$total_count_efn - 1];
			header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
			header('Content-Encoding: none');
			header('Content-Type: '. image_type_to_mime_type(IMAGETYPE_JPEG));  // Change the mime type if the file is not PDF
			header('Content-Disposition: attachment; filename=' . $filename);  // Make the browser display the Save As dialog
			readfile($path);*/
		}



		function check_smvs(){

			$data['vendor_invite_id'] = $this->input->post('vendor_invite_id');

			$data['result_data'] = $this->rest_app->post('index.php/vendor/sm_vendor_systems/check_smvs', $data, '');

			//var_dump($data);die;
			echo $data['result_data']->query[0]->RECORD_COUNT;
		}

		function check_vendor_request(){

			$data['vendor_invite_id'] = $this->input->post('vendor_invite_id');

			$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_request_pass_id/check_vendor_request', $data, '');

			//var_dump($data);die;

			//print_r($data['result_data']->query[0]->COUNT); exit();

			echo $data['result_data']->query[0]->COUNT;

			
		}

		function check_pass_qty(){
			$data['vendor_invite_id'] = $this->input->post('vendor_invite_id');

			$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_request_pass_id/check_pass_qty', $data, '');

			echo $data['result_data']->query[0]->VENDOR_PASS_QTY;
		}
	}
?>