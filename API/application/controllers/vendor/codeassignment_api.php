<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class Codeassignment_api extends REST_Controller
{
	
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/registration_model');
		$this->load->model('common_model');
		$this->load->model('users_model');
	}

	public function vendor_data_get()
	{
		$vendor_id 	= $this->get('vendor_id');

		$where_arr = array(
						'VENDOR_ID' => $vendor_id
					);

		$rs_vendor = $this->registration_model->get_vendor_data($where_arr);

		$temp = $this->registration_model->get_category_id($where_arr);

		$data['rs_vendor'] = $rs_vendor->result_array();

		$data['temp'] = $temp->result_array();
		
		$this->response($data);
	}

	public function getinfo_get()
	{

		$vi['VENDOR_ID'] = $this->get('vendor_id');
		$invid = $this->common_model->select_query('SMNTP_VENDOR',$vi,'VENDOR_INVITE_ID');
		$tmpvi =  $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $invid[0]['VENDOR_INVITE_ID']),'USER_ID');
		$status =  $this->common_model->select_query('SMNTP_VENDOR_STATUS',array('VENDOR_INVITE_ID' => $invid[0]['VENDOR_INVITE_ID']),'STATUS_ID');
		$vinfo = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $tmpvi[0]['USER_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID,USER_EMAIL');

		if($vinfo[0]['USER_FIRST_NAME'] == null){
			$vinfo[0]['USER_FIRST_NAME'] = '';
		}
		if($vinfo[0]['USER_MIDDLE_NAME'] == null){
			$vinfo[0]['USER_MIDDLE_NAME'] = '';
		}
		if($vinfo[0]['USER_LAST_NAME'] == null){
			$vinfo[0]['USER_LAST_NAME'] = '';
		}


		$vinfo[0]['STATUS_ID'] = $status[0]['STATUS_ID'];


		//tmp code. will join later

		$this->response($vinfo);
	}

	public function get_vendor_code_post()
	{
		$vendor_name = $this->post('vendor_name');
		$vendor_type = str_replace('-', ' ', $this->post('vendor_type'));
		$tin_no = $this->post('tin_no');

		if($vendor_type == 'NON TRADE SERVICE '){
			$vendor_type = 'NON TRADE GENERAL';
		}else if($this->post('vendor_type') == 'TRADE - CONSIGNOR'){
			$vendor_type = 'TRADE STORE CONSIGNOR';
		}
		
		
		//DEV
		$credentials_dev = 'zycus:smriAp1d3V';
		$url_dev = '10.143.10.116:50000/XISOAPAdapter/MessageServlet?senderService=Zycus&interface=OB059_SI_ZYCUS_REQ&interfaceNamespace=http%3A%2F%2Fsmretail.com%2Fxi%2FSMRI%2FS4HANA%2FFI';

		//UAT
		$credentials_uat = 'zycus:smri2019';
		$url_uat = 'http://10.143.10.91:50000/XISOAPAdapter/MessageServlet?senderParty=&senderService=Zycus&receiverParty=&receiverService=&interface=OB059_SI_ZYCUS_REQ&interfaceNamespace=http://smretail.com/xi/SMRI/S4HANA/FI';

		//PRODUCTION
		$credentials_prod = 'zycus:smriAp1pRd';
		$url_prod = 'http://10.143.9.69:50000/XISOAPAdapter/MessageServlet?senderParty=&senderService=Zycus&receiverParty=&receiverService=&interface=OB059_SI_ZYCUS_REQ&interfaceNamespace=http://smretail.com/xi/SMRI/S4HANA/FI';

		

		$curl = curl_init();

		
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url_prod,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		  CURLOPT_USERPWD => $credentials_prod,
		  CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="utf-8"?> 
		<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> 
		    <SOAP-ENV:Body> 
		        <yq1:OB059_MT_ZYCUS_REQ xmlns:yq1="http://smretail.com/xi/SMRI/S4HANA/FI"> 
		            <IV_SUP_NAME><![CDATA['.$vendor_name.']]></IV_SUP_NAME> 
		            <IV_SUP_TYPE>'.$vendor_type.'</IV_SUP_TYPE>
		            <IV_TIN_NO>'.$tin_no.'</IV_TIN_NO> 
		        </yq1:OB059_MT_ZYCUS_REQ> 
		    </SOAP-ENV:Body> 
		</SOAP-ENV:Envelope>',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/xml'
		  ),
		));


		$response = curl_exec($curl);

		if ($response === false) {
        	throw new Exception(curl_error($curl));
    	}

    	//$response = json_encode($response);

    	//var_dump($response);die;

		curl_close($curl);

		$xml = simplexml_load_string($response);

		$xml->registerXPathNamespace('ns1', 'http://smretail.com/xi/SMRI/S4HANA/FI');

		$path = $xml->xpath('//ns1:OB059_MT_ZYCUS_RES');

		if($path){
			$xmlResult = $xml->xpath('//ns1:OB059_MT_ZYCUS_RES')[0]->EV_LEGACY_CODE;
		}else{
			$xmlResult = 0;
		}

		
	
		
		//$this->response($xmlResult);
		
		

		echo $xmlResult;



		/*$ch = curl_init($url);
		//curl_setopt($ch, CURLOPT_URL, $url);
		
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERPWD, 'zycus' . ':' . 'smriAp1d3V');
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);*/

		//curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));

		/*$result = curl_exec($ch);
		curl_close($ch);

		var_dump($result);die;
		return $result;*/
	}

	public function save_pending_integration_post(){


		$invite_id 	= $this->post('invite_id');

		$user_id = $this->post('user_id');

		$data['next_status'] = 121;//no category
		$data['next_position'] = 4;

		$where_arr = array(
						'VENDOR_INVITE_ID' 	=> $invite_id
					);

		$this->db->select('VENDOR_INVITE_STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where(array_filter($where_arr));
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <=

		$query = $this->db->get();

		$vendor_invite_status_id = $query->row()->VENDOR_INVITE_STATUS_ID;

		$date_timestamp = date('Y-m-d H:i:s');

		$status_logs = array(
				'VENDOR_INVITE_STATUS_ID'	=> $vendor_invite_status_id,
				'VENDOR_INVITE_ID'	=> $invite_id,
				'STATUS_ID'			=> $data['next_status'],
				'POSITION_ID'		=> $data['next_position'],
				'DATE_UPDATED'		=> $date_timestamp,
				'ACTIVE'			=> 1,
				'APPROVER_ID'		=> $user_id,
			);
		
		
		// Update Vendor STATUS
		$record_arr = array(
			'STATUS_ID'				=> $data['next_status'],
			'DATE_UPDATED'			=> date('Y-m-d'),
			'POSITION_ID'			=> $data['next_position'],
			'ADDITIONAL_START_DATE' => date('Y-m-d')
		);
		$where_arr = array('VENDOR_INVITE_ID' => $invite_id);

		$this->common_model->insert_table('SMNTP_VENDOR_STATUS_LOGS', $status_logs);
		$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);

		//var_dump($data);die;

		$this->response($data);
	}

	public function save_codeassgin_post()
	{
		$position_id 	= $this->post('position_id');
		$code_assign 	= $this->post('code_assign');
		$watson_vendor 	= json_decode($this->post('watson_vendor'));
		$vendor_id 		= $this->post('vendor_id');
		$created_by		= $this->post('user_id');
		$vendorcode_auto = $this->post('vendorcode_auto');
		$registration_type 		= $this->post('registration_type');
		$vendor_type 	= $this->post('vendor_type');

		//var_dump($watson_vendor);die;
		
		//$this->response(123);

		//check if code assign already exists 		
		$where_arr_vendor = array('VENDOR_CODE' => $code_assign);
		$vendor_id_check = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_ID', $where_arr_vendor);

		if (!empty($vendor_id_check))
		{
			$data['status'] = FALSE;
			$data['error'] = 'Duplicate Found!';
		}
		else
		{
			//get invite_id
			$where_arr_vendor = array('VENDOR_ID' => $vendor_id);
			$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr_vendor);
			
			
			/*
			//Test Code
			$next_arr = array(
							'status' 		=> 121,
							'position_id' 	=> $position_id,
							'type' 			=> 1, // registration
						);
			$data = $this->common_model->get_next_process($next_arr);
            
			if($rf[0]['AGREEMENT_ID'] == "-1"){
					$data['next_status'] = 198;//no category
					$data['next_position'] = 4;
			}
			
			$grd3 = array('invite_id' => $invite_id, 'vendor_id' => $vendor_id);
			$ard_available = $this->registration_model->get_ra_docs3($grd3); 
			$check_waived_all_ad = $this->registration_model->check_additional_requirements_waive(array('invite_id' => $invite_id, 'vendor_id' => $vendor_id));
			$data['check_waived_all_ad'] = $check_waived_all_ad;
			$this->response($ard_available);die();*/

			if($vendor_type == 3){
				$watson_vendor = true;
			}

			// update vendor table set code assign
			if($registration_type == 4){				
				$record_arr = array(
					'VENDOR_CODE_02' => $code_assign
				);
			}else{				
				$record_arr = array(
					'VENDOR_CODE' => $code_assign
				);
			}
			$where_arr = array(
				'VENDOR_ID' => $vendor_id
			);
			
			$this->common_model->update_table('SMNTP_VENDOR', $record_arr, $where_arr);

			$where_arr_invite = array(
                                        'VENDOR_INVITE_ID' => $invite_id
                                );

			$vendor_request_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_REQUESTS', 'VENDOR_REQUEST_ID', $where_arr_invite);

			$where_arr_request = array(
                                        'VENDOR_REQUEST_ID' => $vendor_request_id
                                );

			if($registration_type == 4){				
				$record_arr = array(
					'VENDOR_CODE_02' => $code_assign
				);

				$this->common_model->update_table('SMNTP_VENDOR_PASS', $record_arr, $where_arr_request);
			}else{				
				$record_arr = array(
					'VENDOR_CODE' => $code_assign
				);

				$this->common_model->update_table('SMNTP_VENDOR_PASS', $record_arr, $where_arr_request);
			}

           		
			
			if($watson_vendor == true){
				$watson_record_array = array(
						'VENDOR_CODE'	=> $code_assign,
						'DATE_CREATED'	=> date('Y-m-d h:i:s'),
						'CREATED_BY'	=> $created_by
					);
				$this->common_model->insert_table('SMNTP_WATSON_EXTRACT', $watson_record_array);
			}

				$vor_record_array = array(
						'VENDOR_CODE'	=> $code_assign,
						'DATE_CREATED'	=> date('Y-m-d h:i:s'),
						'CREATED_BY'	=> $created_by
					);
				$this->common_model->insert_table('SMNTP_VOR_EXTRACT', $vor_record_array);
			

			// User ID
			$where_arr = array('VENDOR_ID' => $vendor_id);
			$vendor_user_data = $this->users_model->get_vendor_user_data($vendor_id)->row_array();
			
			if(empty($vendor_user_data)){
				$user_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', array('VENDOR_INVITE_ID' => $invite_id));
				$this->common_model->update_table('SMNTP_USERS', array('VENDOR_ID' => $vendor_id), array('USER_ID' => $user_id));
				$vendor_user_data = $this->users_model->get_vendor_user_data($vendor_id)->row_array();
			}
			
			// Vendor Name
			$vendor_data = $this->users_model->get_vendor_data($vendor_id)->row_array();

			$old_username = $this->common_model->get_from_table_where_array('SMNTP_CREDENTIALS', 'USERNAME', array('USER_ID' => $vendor_user_data['USER_ID']));
			
			// update username set code assign 
			// always add -1 to codeassign for username
			//$new_user_name = $code_assign.'-1';
			
			// update MSF - 20191105 (IJR-10612)
			if($registration_type != 4){				
				$new_user_name = $code_assign;		
				$rs = $this->common_model->update_table('SMNTP_CREDENTIALS', array('USERNAME' => $new_user_name), array('USER_ID' => $vendor_user_data['USER_ID']));
			}else{
				$new_user_name = $old_username;		
				$rs = $this->common_model->update_table('SMNTP_CREDENTIALS', array('USERNAME' => $new_user_name), array('USER_ID' => $vendor_user_data['USER_ID']));				
			}

			$user_status_logs = array(
				'USER_ID' => $vendor_user_data['USER_ID'],
				'USER_STATUS_ID' => 1,
				'DATE_MODIFIED' => date("Y-m-d h:i:s")
			);

			$susl = $this->db->insert('SMNTP_USERS_STATUS_LOGS',$user_status_logs);

			$rf = 0;
			
			$docs_var['ownership'] 			= $vendor_data['OWNERSHIP_TYPE'];
			$docs_var['trade_vendor_type'] 	= $vendor_data['TRADE_VENDOR_TYPE'];
			$docs_var['vendor_type'] 		= $vendor_data['VENDOR_TYPE'];
			$docs_var['category_id'] 		= '';
			$docs_var['invite_id'] 			= $invite_id;
			
			//if($docs_var['vendor_type'] == 3){
				$vendor_categories = $this->users_model->get_vendor_assigned_categories($invite_id)->result_array();
				
				$str_categories = '';
				$cat_array = '';
				foreach($vendor_categories as $category){
					$cat_array .=  $category['CATEGORY_ID'] . ',';
				}
				
				$docs_var['category_id']		= explode(',', rtrim($cat_array, ','));
			//}
			
			$ard_available = $this->registration_model->get_ra_docs($docs_var);  //FALSE = NO AVAILABLE ARD

			// update status 
			$status_id = 121; // code assignment 
			$next_arr = array(
				'status' 		=> $status_id,
				'position_id' 	=> $position_id,
				'type' 			=> 1, // registration
				'reg_type_id' 			=> $registration_type, // registration
			);
			$data = $this->common_model->get_next_process($next_arr);

			//var_dump($next_arr);die;

			if(empty($ard_available)){
				$data['next_status'] = 198;//no category
				$data['next_position'] = 4;
			}
			
			// Update Vendor STATUS
			$record_arr = array(
				'STATUS_ID'				=> $data['next_status'],
				'DATE_UPDATED'			=> date('Y-m-d'),
				'POSITION_ID'			=> $data['next_position'],
				'ADDITIONAL_START_DATE' => date('Y-m-d')
			);
			$where_arr = array('VENDOR_INVITE_ID' => $invite_id);

			
			$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);
		

			// if additional req is alread uploaded update again and pass status == $has_additional_req = true
			$has_additional_req = $this->registration_model->check_additional_requirements_upload($vendor_id);
			
			$no_of_ra = count($ard_available);
			$is_finished_reviewed_ra = $this->registration_model->check_reviewed_ra_upload($vendor_id);
			$is_finished_reviewed_waived = $this->registration_model->check_ra_waive($docs_var);
			$total_files = $is_finished_reviewed_waived[1] + $is_finished_reviewed_ra[1];

			
			//jay
			//Check if all Additional Documents waived
			if(empty($ard_available)){
				$data['check_waived_all_ad'] = TRUE;
			}else{
				$check_waived_all_ad = $this->registration_model->check_additional_requirements_waive(count($ard_available));
				$data['check_waived_all_ad'] = $check_waived_all_ad;
			}
		
			//jay
			$status_log_next_status  =  $data['next_status'];
			$status_log_next_position = $data['next_position'];
			
			if($registration_type != 4){	
				if ($no_of_ra == $total_files)
				{
					$status_log_next_status 	= 198;
					$status_log_next_position 	= 4;
					
					$record_arr = array(
						'STATUS_ID'				=> 198,
						'POSITION_ID'			=> 4
					);

					$where_arr = array(
						'VENDOR_INVITE_ID' 	=> $invite_id
					);

					$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);
				}else if($has_additional_req || $check_waived_all_ad){
					$next_arr = array(
						'status' 		=> $data['next_status'],
						'position_id' 	=> $data['next_position'],
						'type' 			=> 1, // registration
					);
					
					$data2 = $this->common_model->get_next_process($next_arr);

					if( ! $ard_available){
						$data2['next_status'] 	= 198; // No Category
						$data2['next_position'] = 4; // VRD Staff
					}
					
					//jay
					$status_log_next_status 	= $data2['next_status'];
					$status_log_next_position 	= $data2['next_position'];
					//end

					$record_arr = array(
						'STATUS_ID'				=> $data2['next_status'],
						'POSITION_ID'			=> $data2['next_position']
					);

					$where_arr = array(
						'VENDOR_INVITE_ID' 	=> $invite_id
					);

					$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);
				}
			
				// variable for send email notif
				$var = array(
					'TEMPLATE_TYPE' 	=> 2, //2 for codeassignment email
					'ACTIVE' 			=> 1
				);

				$query = $this->common_model->get_email_template($var);
				$message = $query->row()->CONTENT;
				$message = str_replace('[vendorname]', $vendor_data['VENDOR_NAME'], $message);
				$message = str_replace('[vendorcode]', $code_assign, $message);
				$message = str_replace('[username]', $new_user_name, $message);
				$message = str_replace('[old_username]', $old_username, $message);

				$message = nl2br($message);
			}else{
			
				// variable for send email notif
				$var = array(
					'TEMPLATE_TYPE' 	=> 73, //2 for codeassignment email
					'ACTIVE' 			=> 1
				);
				
				if($docs_var['trade_vendor_type'] == 1){
					$temp_vendor_type = 'Consignor';
				}else{
					$temp_vendor_type = 'Outright';
				}
				
				$query = $this->common_model->get_email_template($var);
				$message = $query->row()->CONTENT;
				$message = str_replace('[vendorname]', $vendor_data['VENDOR_NAME'], $message);
				$message = str_replace('[vendor_type]', $temp_vendor_type, $message);
				$message = str_replace('[vendor_code]', $code_assign, $message);
				$message = str_replace('[user_name]', $old_username, $message);

				$message = nl2br($message);
			}
			
			
			//Jay VENDOR STATUS LOGS 
			$data2['status'] 					= $status_log_next_status; 
			$data2['nxt_position_id'] 			= $status_log_next_position;
			$data2['invite_id'] 				= $invite_id;
			$data2['user_id'] 					=  $this->post('user_id');
			$data2['reject_remarks']			= '';
			$rs2 = $this->registration_model->update_review_status($data2);

			//get email
			$email = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'EMAIL', array('VENDOR_INVITE_ID' => $invite_id));

			$email_data['to'] 		= $email;
			if($registration_type != 4){	
				$email_data['subject'] 	= 'Approved Vendor Registration';
			}else{
				$email_data['subject'] 	= 'Approved Additional Vendor Code';
			}
			$email_data['content'] 	= $message;
			$this->common_model->send_email_notification($email_data);

			if ($rs)
			{
				$data['status'] = TRUE;
				$data['error'] = '';
			}
			else
			{
				$data['status'] = FALSE;
				$data['error'] = 'Something went wrong!';
			}
			
			//Jay comment
			//If there is no Additional requirements submitted then message the vendor
			$ARD_already_submitted = $this->registration_model->check_additional_requirements_upload($vendor_id);
			$data['vendor_info'] = $this->common_model->select_query('SMNTP_USERS',array('VENDOR_ID' => $vendor_id),'*');
			$data['ARD_already_submitted'] = $ARD_already_submitted;

			/*if( count($ard_available) <= 0){
				$data['ARD_already_submitted'] = true;
			}*/
			//Jay -end
			
 			if ( count($ard_available) > 0 && ! $ARD_already_submitted && ! $data['check_waived_all_ad']){
				// $date_exp = $this->check_status_model->select_query('SMNTP_SYSTEM_CONFIG',array('SYSTEM_CONFIG_ID' => '63'),'CONFIG_VALUE');
				$date_exp = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', array('SYSTEM_CONFIG_ID' => '63'));
				$content = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', array('TEMPLATE_TYPE' => '21'));
				$subdate = date('F d,Y',strtotime("+".$date_exp. " days"));

				$message = str_replace('[vendor_name]', $vendor_data['VENDOR_NAME'], $content);
				$message = str_replace('[submission_deadline]', $subdate, $message);

				$message = nl2br($message);

				$where_arr_invite = array('VENDOR_INVITE_ID' => $invite_id);
				$email = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'EMAIL', $where_arr_invite);

				$email_data['to'] 		= $email;
				//$email_data['bcc']  	= 'justine.pagarao@novawaresystems.com';
				$email_data['subject'] 	= 'Additional Requirements Submission';
				$email_data['content']  = $message;
				$this->common_model->send_email_notification($email_data);
				
				
				$data['submission_date'] 	= $subdate;
				
				if ($rs)
				{
					$data['status'] = TRUE;
					$data['error'] = '';
				}
				else
				{
					$data['status'] = FALSE;
					$data['error'] = 'Something went wrong!';
				}
			}else{
				//Jay
				//If already submitted
				$date_exp = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', array('SYSTEM_CONFIG_ID' => '63'));
				$subdate = date('F d,Y',strtotime("+".$date_exp. " days"));

				$data['submission_date'] 	= $subdate;
			}
		}

		$this->response($data);
	}
}
?>