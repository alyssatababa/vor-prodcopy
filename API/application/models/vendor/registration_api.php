<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('user_agent', 'Mozilla/5.0');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class Registration_api extends REST_Controller
{
	
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/registration_model');
		$this->load->model('vendor/invitecreation_model');
		$this->load->model('common_model');
		$this->load->model('mail_model');
		$this->load->helper('message_helper');
		$this->load->model('users_model');
	}
	
	function filter_brand_get()
	{
		$var['user_position_id'] = $this->get('user_position_id');
		
		$rs = $this->registration_model->get_brand_filter($var);
		
		if ($rs)
		{
			$data = $rs;

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	
	function filter_status_get()
	{
		$var['status_type'] = $this->get('status_type');
		
		$rs = $this->registration_model->get_status_filter($var);
		
		if ($rs)
		{
			$data = $rs;
			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	function regtable_get()
	{
		$var['user_id'] 	= $this->get('user_id');
		$var['position_id'] = $this->get('position_id');
		$var['vendorname'] 	= $this->get('vendorname');
		$var['vendor_type'] = $this->get('vendor_type');
		$var['trade_vendor_type'] = $this->get('trade_vendor_type');
		$var['tin_no'] 		= $this->get('tin_no');
		$var['auth_rep'] 	= $this->get('auth_rep');
		$var['brand_id'] 	= $this->get('brand_id');
		$var['brand_name'] 	= $this->get('brand_name');
		$var['status_id'] 	= $this->get('status_id');
		$var['rpp']			= 25;
		$var['page_num']	= $this->get('current_page');
		$var['override_position_id']	= $this->get('override_position_id');
		$var['dashboard_status_id'] = $this->get('dashboard_status_id');	
		$var['page_no']		 = $this->get('page_no');
		$var['sort']		 = $this->get('sort');
		$var['sort_type']	 = $this->get('sort_type');

		$rs = $this->registration_model->get_registration_main($var);
		//echo $this->db->last_query();
		if ($rs)
		{
			$data = $rs;
			$data['last_query'] = $this->db->last_query();
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
	
	function hats_approval_get()
	{
		$rs = $this->registration_model->get_for_hats_approval();
		// echo $this->db->last_query();
		if ($rs)
		{
			$data = $rs;
			$data['rows'] = count($rs);
			$data['status'] = TRUE;
			$data['error'] = '';

			$this->response($data);
		}
		else
		{
			$data['rows'] = 0;
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	function check_dpa_get()
	{
		$user_id 	= $this->get('user_id');
		$invite_id 	= $this->get('invite_id');
		$vendorname = '';

		$data = array(
					'USER_ID' 	=> $user_id,
					'ACTIVE'	=> 1
				);

		$valid = $this->registration_model->check_dpa($data);

		if (!empty($invite_id))
		{
			$where_arr = array('VENDOR_INVITE_ID' => $invite_id);
			$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);	
		}
		

		$var['valid'] 		= $valid;
		$var['vendorname'] 	= $vendorname;

		$this->response($var);
	}

	function accept_dpa_post()
	{
		$user_id 	= $this->post('user_id');
		$invite_id 	= $this->post('invite_id');

		$record = array(
					'USER_ID' => $user_id
				);

		$rs = $this->common_model->insert_table('SMNTP_VENDOR_DPA', $record);

		$record = array(
					'STATUS_ID' 	=> 9 // after accepting dpa in process na
			);
		$where = array(
				'VENDOR_INVITE_ID' => $invite_id
			);
		$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record, $where);

		if ($rs)
		{
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

	function list_docs_get()
	{
		$var['ownership'] 			= $this->get('ownership');
		$var['trade_vendor_type'] 	= $this->get('trade_vendor_type');
		$var['vendor_type'] 	= $this->get('vendor_type');
		$var['category_id']			= explode(',', $this->get('category_id'));
		$var['invite_id'] = $this->get('invite_id');
		$var['registration_type'] = $this->get('registration_type');
		$var['vendor_code_02'] = $this->get('vendor_code_02');
		$data['ccn'] = [];
		
		/*if($var['trade_vendor_type'] == 2){
			$var['trade_vendor_type'] = 3;
		}else if($var['trade_vendor_type'] == 3){
			$var['trade_vendor_type'] = 4;
		}*/

		
		if($var['registration_type'] == 4 && $var['vendor_code_02'] != ''){
			/*if($var['trade_vendor_type'] == 1){
				$var['trade_vendor_type'] = 2;
			}else if($var['trade_vendor_type'] == 2){
				$var['trade_vendor_type'] = 1;
			}*/
			$var['trade_vendor_type_array'] = array(1, 2);
		}else if($var['registration_type'] == 4 && $var['vendor_code_02'] == ''){
			$var['trade_vendor_type_array'] = $var['trade_vendor_type'];
		}else if($var['vendor_code_02'] != '' && $var['vendor_code_02'] != ''){
			if($var['trade_vendor_type'] == 1){
				$var['trade_vendor_type'] = 2;
			}else if($var['trade_vendor_type'] == 2){
				$var['trade_vendor_type'] = 1;
			}
			$var['trade_vendor_type_array'] = array(1, 2);
		}else{
			$var['trade_vendor_type_array'] = $var['trade_vendor_type'];
		}

		$rsd = $this->registration_model->get_rsd_docs($var);
		$ra = $this->registration_model->get_ra_docs($var); 
		if($var['registration_type'] == 5){
			$ccn = $this->registration_model->get_ccn_docs();
		
			if( ! empty($ccn) && is_array($ccn)){	
				foreach($ccn as $key => $e){
					if($e['DOWNLOADABLE'] == 1){
						$ccn[$key]['DOWNLOADABLE'] = NULL;
					}
					if($e['VIEWABLE'] == 1){
						$ccn[$key]['VIEWABLE'] = NULL;
					}
				}
			}
			
			$data['ccn'] = $ccn;
		}
		
		if( ! empty($ra) && is_array($ra)){	
			foreach($ra as $key => $d){
				if($d['DOWNLOADABLE'] == 1){
					$ra[$key]['DOWNLOADABLE'] = NULL;
				}
				if($d['VIEWABLE'] == 1){
					$ra[$key]['VIEWABLE'] = NULL;
				}
			}
		}
		
		$data['rsd'] = $rsd;
		$data['ra'] = $ra;

		$this->response($data);
	}

	function registration_add_post()
	{
		$var['audit_logs'] 	= $this->post('audit_logs');
		$audit_logs = explode(",", $var['audit_logs']);
		$audit_logs = array_unique($audit_logs);
		$var['timestamp'] 		= date('Y-m-d H:i:s');
		$var['user_id'] 		= $this->post('user_id');
		$var['position_id'] 	= $this->post('position_id');
		$var['invite_id'] 		= $this->post('invite_id');
		$var['status_id'] 		= $this->post('status');
		$var['vendor_id'] 		= $this->post('vendor_id');
		$real_status_id = $this->post('status_id');
		$var['registration_type'] = $this->post('registration_type');
		$var['vendor_code_02'] = $this->post('vendor_code_02');

		
		$var['real_status']		= $real_status_id;
		$var['web_site_url']    = $this->post('web_site_url');
		/*$next_arr = array(
						'status' 		=> 9,
						'position_id' 	=> $var['position_id'],
						'type' 			=> 1 // registration
					);

		$data = $this->common_model->get_next_process($next_arr);*/
		$db_debug = $this->db->db_debug;
		$this->db->db_debug = false;
		$this->db->trans_begin();
		$uploaded_file_error = NULL;
		if ($real_status_id > 9 && $real_status_id != 11)
		{
			// SM Vendor System Logs here for update vendors
			
			$get_smvs = $this->registration_model->al_sm_vendor_system($var['vendor_id']);
			$smvs_row_count = count($get_smvs);
			$result = '';
			if($smvs_row_count > 0){
				for($a=0; $a<$smvs_row_count; $a++){
					if($get_smvs[$a]['FN2'] != $get_smvs[$a]['FN1']){
						if($get_smvs[$a]['FN1'] == ''){
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $get_smvs[$a]['FN2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Name)");
							$insert_smvs = $this->registration_model->insert_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
						}else{
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_smvs[$a]['FN1'], $get_smvs[$a]['FN2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Name)");
							$update_smvs = $this->registration_model->update_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
						}
					}

					if($get_smvs[$a]['EA2'] != $get_smvs[$a]['EA1']){
						if($get_smvs[$a]['EA1'] == ''){
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $get_smvs[$a]['EA2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Email)");
							$insert_smvs = $this->registration_model->insert_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
						}else{
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_smvs[$a]['EA1'],$get_smvs[$a]['EA2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Email)");
							$update_smvs = $this->registration_model->update_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
						}
					}

					if($get_smvs[$a]['MN2'] != $get_smvs[$a]['MN1']){
						if($get_smvs[$a]['MN1'] == ''){
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $get_smvs[$a]['MN2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Mobile)");
							$insert_smvs = $this->registration_model->insert_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
						}else{
							$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_smvs[$a]['MN1'], $get_smvs[$a]['MN2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Mobile)");
							$update_smvs = $this->registration_model->update_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
						}
					}
				}
			}

			// if ($real_status_id == 190 || $real_status_id == 195) //190 = additional req, 195 = incomplete additional req
			// {
			if ($real_status_id == 195)
			{
				################ DELETE INCOMPLETE REASON ########################### START
				$ir_arr = ['VENDOR_ID' =>  $var['vendor_id'] , 'INVITE_ID' => $var['invite_id'] ];
				$this->registration_model->delete_table('SMNTP_VENDOR_INCOMPLETE_REASON', $ir_arr);

				// for others
				$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', ['APPROVER_REMARKS' => null], ['VENDOR_INVITE_ID' 	=> $var['invite_id']]);
				################ DELETE INCOMPLETE REASON ########################### END
				
			}
			
			if($real_status_id == 19){ //For Update Completed Registration
				$vendor_id_check = $this->registration_model->check_vendor_id($var);
				$var['vendor_id'] = $vendor_id_check[0]['VENDOR_ID'];
				$var['current_registration_type'] = $vendor_id_check[0]['REGISTRATION_TYPE'];
				$var['prev_registration_type'] = $vendor_id_check[0]['PREV_REGISTRATION_TYPE'];
				$this->registration_model->backup_table($var['vendor_id']);
				
				if($var['current_registration_type'] != 3){
					$rs = $this->common_model->update_table('SMNTP_VENDOR_INVITE', ['REGISTRATION_TYPE' => 3, 'PREV_REGISTRATION_TYPE' => $var['registration_type']], ['VENDOR_INVITE_ID' 	=> $var['invite_id']]); // Update registration type to 3
				}else{
					$rs = $this->common_model->update_table('SMNTP_VENDOR_INVITE', ['REGISTRATION_TYPE' => 3], ['VENDOR_INVITE_ID' 	=> $var['invite_id']]); // Do not update registration type to 3
				}
				$var['registration_type'] = 3;
				
				// Insert Logs Here
				$test = "";
				$tester = '';

				foreach($audit_logs as $logs){
					switch($logs){
						case "brand":
							$count_to_insert = $this->post('brand_count');
						
							$get_existing_brand = $this->registration_model->al_brand($var['vendor_id']);
							$row_count = $get_existing_brand['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										if($get_existing_brand['result'][$b]['BRAND_NAME'] == $this->post('brand_name'.($a+1))){
											$handler = ($b+1);
											break;
										}
									}
									
									if(($a+1) <= $row_count){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], $this->post('brand_name'.($a+1)), 'Brand');
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $this->post('brand_name'.($a+1)), 'Brand');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										if($get_existing_brand['result'][$a]['BRAND_NAME'] == $this->post('brand_name'.($b+1))){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], $this->post('brand_name'.($a+1)), 'Brand');
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], '', 'Brand');
									}
								}
							}
							
							break;
						case "employee":
							$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
							if($get_existing['result'][0]['EMPLOYEE'] != $this->post('no_of_employee')){
								$db_record = '';
								$post_record = '';
								switch($get_existing['result'][0]['EMPLOYEE']){
									case 0:
										$db_record = 'MICRO (1 - 9)';
										break;
									case 1:
										$db_record = 'SMALL (10 - 99)';
										break;
									case 2:
										$db_record = 'MEDIUM (100 - 199)';
										break;
									case 3:
										$db_record = 'LARGE (200 and above)';
										break;
								}
								
								switch($this->post('no_of_employee')){
									case 0:
										$post_record = 'MICRO (1 - 9)';
										break;
									case 1:
										$post_record = 'SMALL (10 - 99)';
										break;
									case 2:
										$post_record = 'MEDIUM (100 - 199)';
										break;
									case 3:
										$post_record = 'LARGE (200 and above)';
										break;
								}
								
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $db_record, $post_record, 'No. of Employee');
								$tester .= $insert_record;
							}
							break;
						case "business_asset":
							$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
							if($get_existing['result'][0]['BUSINESS_ASSET'] != $this->post('business_asset')){
								$db_record = '';
								$post_record = '';
								switch($get_existing['result'][0]['BUSINESS_ASSET']){
									case 0:
										$db_record = 'MICRO (Up to P3,000,000)';
										break;
									case 1:
										$db_record = 'SMALL (P3,000,001 - P15,000,000)';
										break;
									case 2:
										$db_record = 'MEDIUM (P15,000,001 - P100,000,000)';
										break;
									case 3:
										$db_record = 'LARGE (P100,000,001 and above)';
										break;
								}
								
								switch($this->post('business_asset')){
									case 0:
										$post_record = 'MICRO (Up to P3,000,000)';
										break;
									case 1:
										$post_record = 'SMALL (P3,000,001 - P15,000,000)';
										break;
									case 2:
										$post_record = 'MEDIUM (P15,000,001 - P100,000,000)';
										break;
									case 3:
										$post_record = 'LARGE (P100,000,001 and above)';
										break;
								}
								
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $db_record, $post_record, 'MSME Business Asset Classification');
								$tester .= $insert_record;
							}
							break;
						case "business_years":
							$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
							if($get_existing['result'][0]['YEAR_IN_BUSINESS'] != $this->post('cbo_yr_business')){
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['YEAR_IN_BUSINESS'], $this->post('cbo_yr_business'), 'Years in Business');
								$tester .= $insert_record;
							}
							break;
						case "tax":
							$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
							
							if($get_existing['result'][0]['TAX_ID_NO'] != $this->post('tax_idno')){
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['TAX_ID_NO'], $this->post('tax_idno'), 'Tax Identification No');
								$tester .= $insert_record;
							}
							
							if($get_existing['result'][0]['TAX_CLASSIFICATION'] != $this->post('tax_class')){
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['TAX_CLASSIFICATION'], $this->post('tax_class'), 'Tax Classification');
								$tester .= $insert_record;
							}
							break;
						case "offadd":
							$count_to_insert = $this->post('office_addr_count');
							$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'1');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$insert_details = $this->post('office_add'.($a+1)) . $this->post('office_brgy_cm'.($a+1)) . $this->post('office_state_prov'.($a+1)) . $this->post('office_zip_code'.($a+1)) . $this->post('office_region'.($a+1)) . $this->post('office_country'.($a+1));
									$to_logs = $this->post('office_add'.($a+1)) . " " . $this->post('office_brgy_cm'.($a+1)) . " " . $this->post('office_state_prov'.($a+1)) . " " . $this->post('office_region'.($a+1)) . " " .  $this->post('office_zip_code'.($a+1)) . " " . $this->post('office_country'.($a+1));
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
										if($db_details == $insert_details){
											$handler = ($b+1);
											break;
										}
									}
									if(($a+1) <= $row_count){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Office Address');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Office Address');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$db_details = $get_existing['result'][$a]['ADDRESS_LINE'] . $get_existing['result'][$a]['CITY_NAME'] . $get_existing['result'][$a]['STATE_PROV_NAME'] . $get_existing['result'][$a]['ZIP_CODE'] . $get_existing['result'][$a]['REGION_DESC_TWO'] . $get_existing['result'][$a]['COUNTRY_NAME'];
									$to_logs = $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'];
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										$insert_details = $this->post('office_add'.($b+1)) . $this->post('office_brgy_cm'.($b+1)) . $this->post('office_state_prov'.($b+1)) . $this->post('office_zip_code'.($b+1)) . $this->post('office_region'.($b+1)) . $this->post('office_country'.($b+1));
										if($db_details == $insert_details){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('office_add'.($a+1)) . " " . $this->post('office_brgy_cm'.($a+1)) . " " . $this->post('office_state_prov'.($a+1)) . " " . $this->post('office_region'.($a+1)) . " " . $this->post('office_zip_code'.($a+1)) . " " . $this->post('office_country'.($a+1)), 'Office Address');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Office Address');
									}
								}
							}
							
							break;
						case "whadd":
							$count_to_insert = $this->post('wh_addr_count');
							$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'2');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$insert_details = $this->post('ware_addr'.($a+1)) . $this->post('ware_brgy_cm'.($a+1)) . $this->post('ware_state_prov'.($a+1)) . $this->post('ware_zip_code'.($a+1)) . $this->post('ware_region'.($a+1)) . $this->post('ware_country'.($a+1));
									$to_logs = $this->post('ware_addr'.($a+1)) . " " .$this->post('ware_brgy_cm'.($a+1)) . " " .$this->post('ware_state_prov'.($a+1)) . " " .$this->post('ware_region'.($a+1)) . " " .$this->post('ware_zip_code'.($a+1)) . " " .$this->post('ware_country'.($a+1));
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
										if($db_details == $insert_details){
											$handler = ($b+1);
											break;
										}
									}
									if(($a+1) <= $row_count){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " .$get_existing['result'][$a]['CITY_NAME'] . " " .$get_existing['result'][$a]['STATE_PROV_NAME'] . " " .$get_existing['result'][$a]['REGION_DESC_TWO'] . " " .$get_existing['result'][$a]['ZIP_CODE'] . " " .$get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Warehouse Address');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Warehouse Address');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$db_details = $get_existing['result'][$a]['ADDRESS_LINE'] . $get_existing['result'][$a]['CITY_NAME'] . $get_existing['result'][$a]['STATE_PROV_NAME'] . $get_existing['result'][$a]['ZIP_CODE'] . $get_existing['result'][$a]['REGION_DESC_TWO'] . $get_existing['result'][$a]['COUNTRY_NAME'];
									$to_logs = $get_existing['result'][$a]['ADDRESS_LINE'] . " " .$get_existing['result'][$a]['CITY_NAME'] . " " .$get_existing['result'][$a]['STATE_PROV_NAME'] . " " .$get_existing['result'][$a]['REGION_DESC_TWO'] . " " .$get_existing['result'][$a]['ZIP_CODE'] . " " .$get_existing['result'][$a]['COUNTRY_NAME'];
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										$insert_details = $this->post('ware_addr'.($b+1)) . $this->post('ware_brgy_cm'.($b+1)) . $this->post('ware_state_prov'.($b+1)) . $this->post('ware_zip_code'.($b+1)) . $this->post('ware_region'.($b+1)) . $this->post('ware_country'.($b+1));
										if($db_details == $insert_details){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('ware_addr'.($a+1)) . " " .$this->post('ware_brgy_cm'.($a+1)) . " " .$this->post('ware_state_prov'.($a+1)) . " " .$this->post('ware_region'.($a+1)) . " " .$this->post('ware_zip_code'.($a+1)) . " " .$this->post('ware_country'.($a+1)), 'Warehouse Address');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Warehouse Address');
									}
								}
							}
							break;
						case "facadd":
							$count_to_insert = $this->post('factory_addr_count');
							$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'3');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$insert_details = $this->post('factory_addr'.($a+1)) . $this->post('factory_brgy_cm'.($a+1)) . $this->post('factory_state_prov'.($a+1)) . $this->post('factory_zip_code'.($a+1)) . $this->post('factory_region'.($a+1)) . $this->post('factory_country'.($a+1));
									$to_logs = $this->post('factory_addr'.($a+1)) . " " .$this->post('factory_brgy_cm'.($a+1)) . " " .$this->post('factory_state_prov'.($a+1)) . " " .$this->post('factory_region'.($a+1)) . " " .$this->post('factory_zip_code'.($a+1)) . " " .$this->post('factory_country'.($a+1));
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
										if($db_details == $insert_details){
											$handler = ($b+1);
											break;
										}
									}
									if(($a+1) <= $row_count){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Factory Address');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Factory Address');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$db_details = $get_existing['result'][$a]['ADDRESS_LINE'] . $get_existing['result'][$a]['CITY_NAME'] . $get_existing['result'][$a]['STATE_PROV_NAME'] . $get_existing['result'][$a]['ZIP_CODE'] . $get_existing['result'][$a]['REGION_DESC_TWO'] . $get_existing['result'][$a]['COUNTRY_NAME'];
									$to_logs = $get_existing['result'][$a]['ADDRESS_LINE'] . " " .$get_existing['result'][$a]['CITY_NAME'] . " " .$get_existing['result'][$a]['STATE_PROV_NAME'] . " " .$get_existing['result'][$a]['REGION_DESC_TWO'] . " " .$get_existing['result'][$a]['ZIP_CODE'] . " " .$get_existing['result'][$a]['COUNTRY_NAME'];
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										$insert_details = $this->post('factory_addr'.($b+1)) . $this->post('factory_brgy_cm'.($b+1)) . $this->post('factory_state_prov'.($b+1)) . $this->post('factory_zip_code'.($b+1)) . $this->post('factory_region'.($b+1)) . $this->post('factory_country'.($b+1));
										if($db_details == $insert_details){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('factory_addr'.($a+1)) . " " .$this->post('factory_brgy_cm'.($a+1)) . " " .$this->post('factory_state_prov'.($a+1)) . " " . $this->post('factory_region'.($a+1)) . " " . $this->post('factory_zip_code'.($a+1)) . " " .$this->post('factory_country'.($a+1)), 'Factory Address');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Factory Address');
									}
								}
							}
							
							break;
						case "telno":
							$count_to_insert = $this->post('telno_count');
							
							$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'1');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$insert_details = $this->post('tel_ccode'.($a+1)) . $this->post('tel_acode'.($a+1)) . $this->post('tel_no'.($a+1)) . $this->post('tel_elno'.($a+1));
									$to_logs = $this->post('tel_ccode'.($a+1)) . " " . $this->post('tel_acode'.($a+1)) . " " . $this->post('tel_no'.($a+1)) . " " . $this->post('tel_elno'.($a+1));
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['AREA_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'] . $get_existing['result'][$b]['EXTENSION_LOCAL_NUMBER'];
										if($db_details == $insert_details){
											$handler = ($b+1);
											break;
										}
									}
									if(($a+1) <= $row_count){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'], $to_logs, 'Tel No.');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Tel No.');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['AREA_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'] . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
									$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										$insert_details = $this->post('tel_ccode'.($b+1)) . $this->post('tel_acode'.($b+1)) . $this->post('tel_no'.($b+1)) . $this->post('tel_elno'.($b+1));
										if($db_details == $insert_details){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('tel_ccode'.($a+1)) . " " . $this->post('tel_acode'.($a+1)) . " " . $this->post('tel_no'.($a+1)) . " " . $this->post('tel_elno'.($a+1)), 'Tel No.');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Tel No.');
									}
								}
							}
							break;
						case "email":
							$count_to_insert = $this->post('email_count');
							
							$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'4');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										if($get_existing['result'][$b]['CONTACT_DETAIL'] == $this->post('email'.($a+1))){
											$handler = ($b+1);
											break;
										}
									}
									
									if(($a+1) <= $row_count){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], $this->post('email'.($a+1)), 'Email');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $this->post('email'.($a+1)), 'Email');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										if($get_existing['result'][$a]['CONTACT_DETAIL'] == $this->post('email'.($b+1))){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], $this->post('email'.($a+1)), 'Email');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], '', 'Email');
									}
								}
							}
							break;
						case "fax":
							$count_to_insert = $this->post('faxno_count');
							
							$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'2');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$insert_details = $this->post('fax_ccode'.($a+1)) . $this->post('fax_acode'.($a+1)) . $this->post('fax_no'.($a+1)) . $this->post('fax_elno'.($a+1));
									$to_logs = $this->post('fax_ccode'.($a+1)) . " " . $this->post('fax_acode'.($a+1)) . " " . $this->post('fax_no'.($a+1)) . " " . $this->post('fax_elno'.($a+1));
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['AREA_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'] . $get_existing['result'][$b]['EXTENSION_LOCAL_NUMBER'];
										if($db_details == $insert_details){
											$handler = ($b+1);
											break;
										}
									}
									if(($a+1) <= $row_count){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'], $to_logs, 'Fax No.');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Fax No.');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['AREA_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'] . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
									$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										$insert_details = $this->post('fax_ccode'.($b+1)) . $this->post('fax_acode'.($b+1)) . $this->post('fax_no'.($b+1)) . $this->post('fax_elno'.($b+1));
										if($db_details == $insert_details){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('fax_ccode'.($a+1)) . " " . $this->post('fax_acode'.($a+1)) . " " . $this->post('fax_no'.($a+1)) . " " . $this->post('fax_elno'.($a+1)), 'Fax No.');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Fax No.');
									}
								}
							}
							break;
						case "cpno":
							$count_to_insert = $this->post('mobno_count');
							
							$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'3');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$insert_details = $this->post('mobile_ccode'.($a+1)) . $this->post('mobile_no'.($a+1));
									$to_logs = $this->post('mobile_ccode'.($a+1)) . " " . $this->post('mobile_no'.($a+1));
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'];
										if($db_details == $insert_details){
											$handler = ($b+1);
											break;
										}
									}
									if(($a+1) <= $row_count){
										if($handler == 0){
											//$handler .= 'test';	// Insert
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'], $to_logs, 'Mobile No.');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Mobile No.');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'];
									$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'];
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										$insert_details = $this->post('mobile_ccode'.($b+1)) . $this->post('mobile_no'.($b+1));
										if($db_details == $insert_details){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('mobile_ccode'.($a+1)) . " " . $this->post('mobile_no'.($a+1)), 'Mobile No.');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Mobile No.');
									}
								}
							}
							break;
						case "opd":
							$count_to_insert = $this->post('opd_count');
							
							$get_existing = $this->registration_model->al_o_ar($var['vendor_id'],'SMNTP_VENDOR_OWNERS');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$insert_details = $this->post('opd_fname'.($a+1)) . $this->post('opd_mname'.($a+1)) . $this->post('opd_lname'.($a+1)) . $this->post('opd_pos'.($a+1));
									$to_logs = $this->post('opd_fname'.($a+1)) . " " . $this->post('opd_mname'.($a+1)) . " " . $this->post('opd_lname'.($a+1)) . " " . $this->post('opd_pos'.($a+1));
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										$db_details = $get_existing['result'][$b]['FIRST_NAME'] . $get_existing['result'][$b]['MIDDLE_NAME'] . $get_existing['result'][$b]['LAST_NAME'] . $get_existing['result'][$b]['POSITION'];
										if($db_details == $insert_details){
											$handler = ($b+1);
											break;
										}
									}
									if(($a+1) <= $row_count){
										if($handler == 0){
											//$handler .= 'test';	// Insert
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'], $to_logs, 'Owners/Partners/Directors');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Owners/Partners/Directors');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$db_details = $get_existing['result'][$a]['FIRST_NAME'] . $get_existing['result'][$a]['MIDDLE_NAME'] . $get_existing['result'][$a]['LAST_NAME'] . $get_existing['result'][$a]['POSITION'];
									$to_logs = $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'];
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										$insert_details = $this->post('opd_fname'.($b+1)) . $this->post('opd_mname'.($b+1)) . $this->post('opd_lname'.($b+1)) . $this->post('opd_pos'.($b+1));
										if($db_details == $insert_details){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('opd_fname'.($a+1)) . " " . $this->post('opd_mname'.($a+1)) . " " . $this->post('opd_lname'.($a+1)) . " " . $this->post('opd_pos'.($a+1)), 'Owners/Partners/Directors');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Owners/Partners/Directors');
									}
								}
							}
							
							break;
						case "ar":
							$count_to_insert = $this->post('authrep_count');
							
							$get_existing = $this->registration_model->al_o_ar($var['vendor_id'],'SMNTP_VENDOR_REP');
							$row_count = $get_existing['num_row'];
							
							if($row_count <= $count_to_insert){ // mas madami iinsert sa db
								for($a=0; $a<$count_to_insert; $a++){
									$insert_details = $this->post('authrep_fname'.($a+1)) . $this->post('authrep_mname'.($a+1)) . $this->post('authrep_lname'.($a+1)) . $this->post('authrep_pos'.($a+1));
									$to_logs = $this->post('authrep_fname'.($a+1)) . " " . $this->post('authrep_mname'.($a+1)) . " " . $this->post('authrep_lname'.($a+1)) . " " . $this->post('authrep_pos'.($a+1));
									$handler = 0;
									for($b=0; $b<$row_count; $b++){
										$db_details = $get_existing['result'][$b]['FIRST_NAME'] . $get_existing['result'][$b]['MIDDLE_NAME'] . $get_existing['result'][$b]['LAST_NAME'] . $get_existing['result'][$b]['POSITION'];
										if($db_details == $insert_details){
											$handler = ($b+1);
											break;
										}
									}
									if(($a+1) <= $row_count){
										if($handler == 0){
											//$handler .= 'test';	// Insert
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'], $to_logs, 'Authorized Representatives');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Authorized Representatives');
									}
									
								}
							}else{ // Mas mdmi ang nasa db
								 for($a=0; $a<$row_count; $a++){
									$db_details = $get_existing['result'][$a]['FIRST_NAME'] . $get_existing['result'][$a]['MIDDLE_NAME'] . $get_existing['result'][$a]['LAST_NAME'] . $get_existing['result'][$a]['POSITION'];
									$to_logs = $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'];
									$handler = 0;
									for($b=0; $b<$count_to_insert; $b++){
										$insert_details = $this->post('authrep_fname'.($b+1)) . $this->post('authrep_mname'.($b+1)) . $this->post('authrep_lname'.($b+1)) . $this->post('authrep_pos'.($b+1));
										if($db_details == $insert_details){
											$handler = ($b+1);
										}
									}
									
									if(($a+1) <= $count_to_insert){
										if($handler == 0){
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('authrep_fname'.($a+1)) . " " . $this->post('authrep_mname'.($a+1)) . " " . $this->post('authrep_lname'.($a+1)) . " " . $this->post('authrep_pos'.($a+1)), 'Authorized Representatives');
											$tester .= $insert_record;
										}
									}else{
										$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Authorized Representatives');
									}
								}
							}
							
							break;
					}
				}
				//$this->response($tester);
				//exit();
				// End
				
				$this->update_vendor($var);
				$this->delete_incrementing_tables($var);
				
				$this->save_brands($var);
				$this->save_addresses($var);
				$this->save_contact_details($var);
				$this->save_opd($var); //owner partner directors
				$this->save_authrep($var); //Authorized Representatives
				$this->save_bankrep($var); //Bank References
				$this->save_retcust($var); //Other Retail Customers/Clients
				$this->save_otherbusiness($var); //Other Business
				$this->save_relativeaffiliates($var); //Disclosure of Relatives Working in SM or its Affiliates
				$upload_result = $this->save_uploads($var); //Required Scanned Documents and Required Agreements
			}else{
				//For Additional Requirements and Incomplete requirements (No of Employee & Business Assets)
				$rs = $this->common_model->update_table('SMNTP_VENDOR', ['EMPLOYEE' => $this->post('no_of_employee'), 'BUSINESS_ASSET' => $this->post('business_asset')], ['VENDOR_INVITE_ID' 	=> $var['invite_id']]);	

				$upload_result = $this->pass_additional_requirements($var); // can uplaod additional req anytime
		
				//catch upload error
				if(!empty($upload_result)){
					$uploaded_file_error = @$upload_result;
				}
				// }
			}
		}
		else
		{
			$add_audit_logs = false;
			$vendor_id_check = $this->registration_model->check_vendor_id($var);
			
			if (!empty($var['vendor_id']) && !empty($vendor_id_check)) // add vendor
			{
				if($real_status_id != 9 && $real_status_id != ''){
					$add_audit_logs = true;
				}else if($vendor_id_check[0]['REGISTRATION_TYPE'] == 4 && $real_status_id == 9){
					$add_audit_logs = true;
					
					// Get Token
					$token = $this->common_model->select_query('SMNTP_VENDOR_TOKEN',array('USER_ID' => $var['user_id']),'TOKEN')[0]['TOKEN'];
					
					// Deactivate Token
					$this->common_model->deactive_token($token);
				}
			}
			
			if($add_audit_logs == true){
				
				$get_smvs = $this->registration_model->al_sm_vendor_system($var['vendor_id']);
				$smvs_row_count = count($get_smvs);
				$result = '';
				if($smvs_row_count > 0){
					for($a=0; $a<$smvs_row_count; $a++){
						if($get_smvs[$a]['FN2'] != $get_smvs[$a]['FN1']){
							if($get_smvs[$a]['FN1'] == ''){
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $get_smvs[$a]['FN2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Name)");
								$insert_smvs = $this->registration_model->insert_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
							}else{
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_smvs[$a]['FN1'], $get_smvs[$a]['FN2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Name)");
								$update_smvs = $this->registration_model->update_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
							}
						}

						if($get_smvs[$a]['EA2'] != $get_smvs[$a]['EA1']){
							if($get_smvs[$a]['EA1'] == ''){
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $get_smvs[$a]['EA2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Email)");
								$insert_smvs = $this->registration_model->insert_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
							}else{
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_smvs[$a]['EA1'], $get_smvs[$a]['EA2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Email)");
								$update_smvs = $this->registration_model->update_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
							}
						}

						if($get_smvs[$a]['MN2'] != $get_smvs[$a]['MN1']){
							if($get_smvs[$a]['MN1'] == ''){
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $get_smvs[$a]['MN2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Mobile)");
								$insert_smvs = $this->registration_model->insert_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
							}else{
								$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_smvs[$a]['MN1'], $get_smvs[$a]['MN2'], $get_smvs[$a]['SM_SYSTEM_DESC']." (Mobile)");
								$update_smvs = $this->registration_model->update_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
							}
						}
					}
				}
				
				$test = "";
					$tester = '';
					foreach($audit_logs as $logs){
						switch($logs){
							case "brand":
								$count_to_insert = $this->post('brand_count');
							
								$get_existing_brand = $this->registration_model->al_brand($var['vendor_id']);
								$row_count = $get_existing_brand['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											if($get_existing_brand['result'][$b]['BRAND_NAME'] == $this->post('brand_name'.($a+1))){
												$handler = ($b+1);
												break;
											}
										}
										
										if(($a+1) <= $row_count){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], $this->post('brand_name'.($a+1)), 'Brand');
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $this->post('brand_name'.($a+1)), 'Brand');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											if($get_existing_brand['result'][$a]['BRAND_NAME'] == $this->post('brand_name'.($b+1))){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], $this->post('brand_name'.($a+1)), 'Brand');
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing_brand['result'][$a]['BRAND_NAME'], '', 'Brand');
										}
									}
								}
								
								break;
							case "employee":
								$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
								if($get_existing['result'][0]['EMPLOYEE'] != $this->post('no_of_employee')){
									$db_record = '';
									$post_record = '';
									switch($get_existing['result'][0]['EMPLOYEE']){
										case 0:
											$db_record = 'MICRO (1 - 9)';
											break;
										case 1:
											$db_record = 'SMALL (10 - 99)';
											break;
										case 2:
											$db_record = 'MEDIUM (100 - 199)';
											break;
										case 3:
											$db_record = 'LARGE (200 and above)';
											break;
									}
									
									switch($this->post('no_of_employee')){
										case 0:
											$post_record = 'MICRO (1 - 9)';
											break;
										case 1:
											$post_record = 'SMALL (10 - 99)';
											break;
										case 2:
											$post_record = 'MEDIUM (100 - 199)';
											break;
										case 3:
											$post_record = 'LARGE (200 and above)';
											break;
									}
									
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $db_record, $post_record, 'No. of Employee');
									$tester .= $insert_record;
								}
								break;
							case "business_asset":
								$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
								if($get_existing['result'][0]['BUSINESS_ASSET'] != $this->post('business_asset')){
									$db_record = '';
									$post_record = '';
									switch($get_existing['result'][0]['BUSINESS_ASSET']){
										case 0:
											$db_record = 'MICRO (Up to P3,000,000)';
											break;
										case 1:
											$db_record = 'SMALL (P3,000,001 - P15,000,000)';
											break;
										case 2:
											$db_record = 'MEDIUM (P15,000,001 - P100,000,000)';
											break;
										case 3:
											$db_record = 'LARGE (P100,000,001 and above)';
											break;
									}
									
									switch($this->post('business_asset')){
										case 0:
											$post_record = 'MICRO (Up to P3,000,000)';
											break;
										case 1:
											$post_record = 'SMALL (P3,000,001 - P15,000,000)';
											break;
										case 2:
											$post_record = 'MEDIUM (P15,000,001 - P100,000,000)';
											break;
										case 3:
											$post_record = 'LARGE (P100,000,001 and above)';
											break;
									}
									
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $db_record, $post_record, 'MSME Business Asset Classification');
									$tester .= $insert_record;
								}
								break;
							case "business_years":
								$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
								if($get_existing['result'][0]['YEAR_IN_BUSINESS'] != $this->post('cbo_yr_business')){
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['YEAR_IN_BUSINESS'], $this->post('cbo_yr_business'), 'Years in Business');
									$tester .= $insert_record;
								}
								break;
							case "tax":
								$get_existing = $this->registration_model->al_tax_basset_nemployee_years($var['vendor_id']);
								
								if($get_existing['result'][0]['TAX_ID_NO'] != $this->post('tax_idno')){
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['TAX_ID_NO'], $this->post('tax_idno'), 'Tax Identification No');
									$tester .= $insert_record;
								}
								
								if($get_existing['result'][0]['TAX_CLASSIFICATION'] != $this->post('tax_class')){
									$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][0]['TAX_CLASSIFICATION'], $this->post('tax_class'), 'Tax Classification');
									$tester .= $insert_record;
								}
								break;
							case "offadd":
								$count_to_insert = $this->post('office_addr_count');
								$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'1');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$insert_details = $this->post('office_add'.($a+1)) . $this->post('office_brgy_cm'.($a+1)) . $this->post('office_state_prov'.($a+1)) . $this->post('office_zip_code'.($a+1)) . $this->post('office_region'.($a+1)) . $this->post('office_country'.($a+1));
										$to_logs = $this->post('office_add'.($a+1)) . " " . $this->post('office_brgy_cm'.($a+1)) . " " . $this->post('office_state_prov'.($a+1)) . " " . $this->post('office_zip_code'.($a+1)) . " " . $this->post('office_region'.($a+1)) . " " . $this->post('office_country'.($a+1));
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
											if($db_details == $insert_details){
												$handler = ($b+1);
												break;
											}
										}
										if(($a+1) <= $row_count){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Office Address');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Office Address');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
										$to_logs = $get_existing['result'][$b]['ADDRESS_LINE'] . " " . $get_existing['result'][$b]['CITY_NAME'] . " " . $get_existing['result'][$b]['STATE_PROV_NAME'] . " " . $get_existing['result'][$b]['ZIP_CODE'] . " " . $get_existing['result'][$b]['REGION_DESC_TWO'] . " " . $get_existing['result'][$b]['COUNTRY_NAME'];
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											$insert_details = $this->post('office_add'.($b+1)) . $this->post('office_brgy_cm'.($b+1)) . $this->post('office_state_prov'.($b+1)) . $this->post('office_zip_code'.($b+1)) . $this->post('office_region'.($b+1)) . $this->post('office_country'.($b+1));
											if($db_details == $insert_details){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('office_add'.($a+1)) . " " . $this->post('office_brgy_cm'.($a+1)) . " " . $this->post('office_state_prov'.($a+1)) . " " . $this->post('office_zip_code'.($a+1)) . " " . $this->post('office_region'.($a+1)) . " " . $this->post('office_country'.($a+1)), 'Office Address');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Office Address');
										}
									}
								}
								
								break;
							case "whadd":
								$count_to_insert = $this->post('wh_addr_count');
								$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'2');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$insert_details = $this->post('ware_addr'.($a+1)) . $this->post('ware_brgy_cm'.($a+1)) . $this->post('ware_state_prov'.($a+1)) . $this->post('ware_zip_code'.($a+1)) . $this->post('ware_region'.($a+1)) . $this->post('ware_country'.($a+1));
										$to_logs = $this->post('ware_addr'.($a+1)) . " " . $this->post('ware_brgy_cm'.($a+1)) . " " . $this->post('ware_state_prov'.($a+1)) . " " . $this->post('ware_zip_code'.($a+1)) . " " . $this->post('ware_region'.($a+1)) . " " . $this->post('ware_country'.($a+1));
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
											if($db_details == $insert_details){
												$handler = ($b+1);
												break;
											}
										}
										if(($a+1) <= $row_count){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Warehouse Address');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Warehouse Address');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
										$to_logs = $get_existing['result'][$b]['ADDRESS_LINE'] . " " . $get_existing['result'][$b]['CITY_NAME'] . " " . $get_existing['result'][$b]['STATE_PROV_NAME'] . " " . $get_existing['result'][$b]['ZIP_CODE'] . " " . $get_existing['result'][$b]['REGION_DESC_TWO'] . " " . $get_existing['result'][$b]['COUNTRY_NAME'];
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											$insert_details = $this->post('ware_addr'.($b+1)) . $this->post('ware_brgy_cm'.($b+1)) . $this->post('ware_state_prov'.($b+1)) . $this->post('ware_zip_code'.($b+1)) . $this->post('ware_region'.($b+1)) . $this->post('ware_country'.($b+1));
											if($db_details == $insert_details){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('ware_addr'.($a+1)) . " " . $this->post('ware_brgy_cm'.($a+1)) . " " . $this->post('ware_state_prov'.($a+1)) . " " . $this->post('ware_zip_code'.($a+1)) . " " . $this->post('ware_region'.($a+1)) . " " . $this->post('ware_country'.($a+1)), 'Warehouse Address');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Warehouse Address');
										}
									}
								}
								break;
							case "facadd":
								$count_to_insert = $this->post('factory_addr_count');
								$get_existing = $this->registration_model->al_address_details($var['vendor_id'],'3');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$insert_details = $this->post('factory_addr'.($a+1)) . $this->post('factory_brgy_cm'.($a+1)) . $this->post('factory_state_prov'.($a+1)) . $this->post('factory_zip_code'.($a+1)) . $this->post('factory_region'.($a+1)) . $this->post('factory_country'.($a+1));
										$to_logs = $this->post('factory_addr'.($a+1)) . " " . $this->post('factory_brgy_cm'.($a+1)) . " " . $this->post('factory_state_prov'.($a+1)) . " " . $this->post('factory_zip_code'.($a+1)) . " " . $this->post('factory_region'.($a+1)) . " " . $this->post('factory_country'.($a+1));
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
											if($db_details == $insert_details){
												$handler = ($b+1);
												break;
											}
										}
										if(($a+1) <= $row_count){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['ADDRESS_LINE'] . " " . $get_existing['result'][$a]['CITY_NAME'] . " " . $get_existing['result'][$a]['STATE_PROV_NAME'] . " " . $get_existing['result'][$a]['ZIP_CODE'] . " " . $get_existing['result'][$a]['REGION_DESC_TWO'] . " " . $get_existing['result'][$a]['COUNTRY_NAME'], $to_logs, 'Factory Address');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Factory Address');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$db_details = $get_existing['result'][$b]['ADDRESS_LINE'] . $get_existing['result'][$b]['CITY_NAME'] . $get_existing['result'][$b]['STATE_PROV_NAME'] . $get_existing['result'][$b]['ZIP_CODE'] . $get_existing['result'][$b]['REGION_DESC_TWO'] . $get_existing['result'][$b]['COUNTRY_NAME'];
										$to_logs = $get_existing['result'][$b]['ADDRESS_LINE'] . " " . $get_existing['result'][$b]['CITY_NAME'] . " " . $get_existing['result'][$b]['STATE_PROV_NAME'] . " " . $get_existing['result'][$b]['ZIP_CODE'] . " " . $get_existing['result'][$b]['REGION_DESC_TWO'] . " " . $get_existing['result'][$b]['COUNTRY_NAME'];
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											$insert_details = $this->post('factory_addr'.($b+1)) . $this->post('factory_brgy_cm'.($b+1)) . $this->post('factory_state_prov'.($b+1)) . $this->post('factory_zip_code'.($b+1)) . $this->post('factory_region'.($b+1)) . $this->post('factory_country'.($b+1));
											if($db_details == $insert_details){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('factory_addr'.($a+1)) . " " . $this->post('factory_brgy_cm'.($a+1)) . " " . $this->post('factory_state_prov'.($a+1)) . " " . $this->post('factory_zip_code'.($a+1)) . " " . $this->post('factory_region'.($a+1)) . " " . $this->post('factory_country'.($a+1)), 'Factory Address');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Factory Address');
										}
									}
								}
								
								break;
							case "telno":
								$count_to_insert = $this->post('telno_count');
							    
								$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'1');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$insert_details = $this->post('tel_ccode'.($a+1)) . $this->post('tel_acode'.($a+1)) . $this->post('tel_no'.($a+1)) . $this->post('tel_elno'.($a+1));
										$to_logs = $this->post('tel_ccode'.($a+1)) . " " . $this->post('tel_acode'.($a+1)) . " " . $this->post('tel_no'.($a+1)) . " " . $this->post('tel_elno'.($a+1));
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['AREA_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'] . $get_existing['result'][$b]['EXTENSION_LOCAL_NUMBER'];
											if($db_details == $insert_details){
												$handler = ($b+1);
												break;
											}
										}
										if(($a+1) <= $row_count){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'], $to_logs, 'Tel No.');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Tel No.');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['AREA_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'] . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
										$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											$insert_details = $this->post('tel_ccode'.($b+1)) . $this->post('tel_acode'.($b+1)) . $this->post('tel_no'.($b+1)) . $this->post('tel_elno'.($b+1));
											if($db_details == $insert_details){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('tel_ccode'.($a+1)) . " " . $this->post('tel_acode'.($a+1)) . " " . $this->post('tel_no'.($a+1)) . " " . $this->post('tel_elno'.($a+1)), 'Tel No.');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Tel No.');
										}
									}
								}
								break;
							case "email":
								$count_to_insert = $this->post('email_count');
							    
								$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'4');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											if($get_existing['result'][$b]['CONTACT_DETAIL'] == $this->post('email'.($a+1))){
												$handler = ($b+1);
												break;
											}
										}
										
										if(($a+1) <= $row_count){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], $this->post('email'.($a+1)), 'Email');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $this->post('email'.($a+1)), 'Email');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											if($get_existing['result'][$a]['CONTACT_DETAIL'] == $this->post('email'.($b+1))){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], $this->post('email'.($a+1)), 'Email');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['CONTACT_DETAIL'], '', 'Email');
										}
									}
								}
								break;
							case "fax":
								$count_to_insert = $this->post('faxno_count');
							    
								$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'2');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$insert_details = $this->post('fax_ccode'.($a+1)) . $this->post('fax_acode'.($a+1)) . $this->post('fax_no'.($a+1)) . $this->post('fax_elno'.($a+1));
										$to_logs = $this->post('fax_ccode'.($a+1)) . " " . $this->post('fax_acode'.($a+1)) . " " . $this->post('fax_no'.($a+1)) . " " . $this->post('fax_elno'.($a+1));
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['AREA_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'] . $get_existing['result'][$b]['EXTENSION_LOCAL_NUMBER'];
											if($db_details == $insert_details){
												$handler = ($b+1);
												break;
											}
										}
										if(($a+1) <= $row_count){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'], $to_logs, 'Fax No.');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Fax No.');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['AREA_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'] . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
										$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['AREA_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'] . " " . $get_existing['result'][$a]['EXTENSION_LOCAL_NUMBER'];
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											$insert_details = $this->post('fax_ccode'.($b+1)) . $this->post('fax_acode'.($b+1)) . $this->post('fax_no'.($b+1)) . $this->post('fax_elno'.($b+1));
											if($db_details == $insert_details){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('fax_ccode'.($a+1)) . " " . $this->post('fax_acode'.($a+1)) . " " . $this->post('fax_no'.($a+1)) . " " . $this->post('fax_elno'.($a+1)), 'Fax No.');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Fax No.');
										}
									}
								}
								break;
							case "cpno":
								$count_to_insert = $this->post('mobno_count');
							    
								$get_existing = $this->registration_model->al_contact_details($var['vendor_id'],'3');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$insert_details = $this->post('mobile_ccode'.($a+1)) . $this->post('mobile_no'.($a+1));
										$to_logs = $this->post('mobile_ccode'.($a+1)) . " " . $this->post('mobile_no'.($a+1));
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											$db_details = $get_existing['result'][$b]['COUNTRY_CODE'] . $get_existing['result'][$b]['CONTACT_DETAIL'];
											if($db_details == $insert_details){
												$handler = ($b+1);
												break;
											}
										}
										if(($a+1) <= $row_count){
											if($handler == 0){
												//$handler .= 'test';	// Insert
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'], $to_logs, 'Mobile No.');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Mobile No.');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$db_details = $get_existing['result'][$a]['COUNTRY_CODE'] . $get_existing['result'][$a]['CONTACT_DETAIL'];
										$to_logs = $get_existing['result'][$a]['COUNTRY_CODE'] . " " . $get_existing['result'][$a]['CONTACT_DETAIL'];
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											$insert_details = $this->post('mobile_ccode'.($b+1)) . $this->post('mobile_no'.($b+1));
											if($db_details == $insert_details){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('mobile_ccode'.($a+1)) . " " . $this->post('mobile_no'.($a+1)), 'Mobile No.');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Mobile No.');
										}
									}
								}
								break;
							case "opd":
								$count_to_insert = $this->post('opd_count');
							    
								$get_existing = $this->registration_model->al_o_ar($var['vendor_id'],'SMNTP_VENDOR_OWNERS');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$insert_details = $this->post('opd_fname'.($a+1)) . $this->post('opd_mname'.($a+1)) . $this->post('opd_lname'.($a+1)) . $this->post('opd_pos'.($a+1));
										$to_logs = $this->post('opd_fname'.($a+1)) . " " . $this->post('opd_mname'.($a+1)) . " " . $this->post('opd_lname'.($a+1)) . " " . $this->post('opd_pos'.($a+1));
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											$db_details = $get_existing['result'][$b]['FIRST_NAME'] . $get_existing['result'][$b]['MIDDLE_NAME'] . $get_existing['result'][$b]['LAST_NAME'] . $get_existing['result'][$b]['POSITION'];
											if($db_details == $insert_details){
												$handler = ($b+1);
												break;
											}
										}
										if(($a+1) <= $row_count){
											if($handler == 0){
												//$handler .= 'test';	// Insert
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'], $to_logs, 'Owners/Partners/Directors');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Owners/Partners/Directors');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$db_details = $get_existing['result'][$a]['FIRST_NAME'] . $get_existing['result'][$a]['MIDDLE_NAME'] . $get_existing['result'][$a]['LAST_NAME'] . $get_existing['result'][$a]['POSITION'];
										$to_logs = $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'];
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											$insert_details = $this->post('opd_fname'.($b+1)) . $this->post('opd_mname'.($b+1)) . $this->post('opd_lname'.($b+1)) . $this->post('opd_pos'.($b+1));
											if($db_details == $insert_details){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('opd_fname'.($a+1)) . " " . $this->post('opd_mname'.($a+1)) . " " . $this->post('opd_lname'.($a+1)) . " " . $this->post('opd_pos'.($a+1)), 'Owners/Partners/Directors');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Owners/Partners/Directors');
										}
									}
								}
								
								break;
							case "ar":
								$count_to_insert = $this->post('authrep_count');
							    
								$get_existing = $this->registration_model->al_o_ar($var['vendor_id'],'SMNTP_VENDOR_REP');
								$row_count = $get_existing['num_row'];
								
								if($row_count <= $count_to_insert){ // mas madami iinsert sa db
									for($a=0; $a<$count_to_insert; $a++){
										$insert_details = $this->post('authrep_fname'.($a+1)) . $this->post('authrep_mname'.($a+1)) . $this->post('authrep_lname'.($a+1)) . $this->post('authrep_pos'.($a+1));
										$to_logs = $this->post('authrep_fname'.($a+1)) . " " . $this->post('authrep_mname'.($a+1)) . " " . $this->post('authrep_lname'.($a+1)) . " " . $this->post('authrep_pos'.($a+1));
										$handler = 0;
										for($b=0; $b<$row_count; $b++){
											$db_details = $get_existing['result'][$b]['FIRST_NAME'] . $get_existing['result'][$b]['MIDDLE_NAME'] . $get_existing['result'][$b]['LAST_NAME'] . $get_existing['result'][$b]['POSITION'];
											if($db_details == $insert_details){
												$handler = ($b+1);
												break;
											}
										}
										if(($a+1) <= $row_count){
											if($handler == 0){
												//$handler .= 'test';	// Insert
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'], $to_logs, 'Authorized Representatives');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], '', $to_logs, 'Authorized Representatives');
										}
										
									}
								}else{ // Mas mdmi ang nasa db
									 for($a=0; $a<$row_count; $a++){
										$db_details = $get_existing['result'][$a]['FIRST_NAME'] . $get_existing['result'][$a]['MIDDLE_NAME'] . $get_existing['result'][$a]['LAST_NAME'] . $get_existing['result'][$a]['POSITION'];
										$to_logs = $get_existing['result'][$a]['FIRST_NAME'] . " " . $get_existing['result'][$a]['MIDDLE_NAME'] . " " . $get_existing['result'][$a]['LAST_NAME'] . " " . $get_existing['result'][$a]['POSITION'];
										$handler = 0;
										for($b=0; $b<$count_to_insert; $b++){
											$insert_details = $this->post('authrep_fname'.($b+1)) . $this->post('authrep_mname'.($b+1)) . $this->post('authrep_lname'.($b+1)) . $this->post('authrep_pos'.($b+1));
											if($db_details == $insert_details){
												$handler = ($b+1);
											}
										}
										
										if(($a+1) <= $count_to_insert){
											if($handler == 0){
												$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, $this->post('authrep_fname'.($a+1)) . " " . $this->post('authrep_mname'.($a+1)) . " " . $this->post('authrep_lname'.($a+1)) . " " . $this->post('authrep_pos'.($a+1)), 'Authorized Representatives');
												$tester .= $insert_record;
											}
										}else{
											$insert_record = $this->registration_model->insert_logs($var['vendor_id'], $var['user_id'], $to_logs, '', 'Authorized Representatives');
										}
									}
								}
								
								break;
						}
					}
			}else{
				$get_smvs = $this->registration_model->al_sm_vendor_system_two($var['invite_id']);
				$smvs_row_count = count($get_smvs);
				$result = '';
				if($smvs_row_count > 0){
					for($a=0; $a<$smvs_row_count; $a++){
								$insert_smvs = $this->registration_model->insert_smvs($get_smvs[$a]['VENDOR_INVITE_ID'], $get_smvs[$a]['SM_SYSTEM_ID'], $get_smvs[$a]['TRADE_VENDOR_TYPE'], $get_smvs[$a]['FIRST_NAME'], $get_smvs[$a]['MIDDLE_NAME'], $get_smvs[$a]['LAST_NAME'], $get_smvs[$a]['POSITION'], $get_smvs[$a]['EA2'], $get_smvs[$a]['MN2'], $var['user_id']);
					}
				}
			}
			
			if (empty($var['vendor_id']) && empty($vendor_id_check)) // add vendor
			{
				$vendor_id = $this->save_vendor($var);
				$var['vendor_id'] = $vendor_id;	
			}
			else// update vendor
			{	
				$var['vendor_id'] = $vendor_id_check[0]['VENDOR_ID'];
				$this->update_vendor($var);
				$this->delete_incrementing_tables($var);

				################ DELETE INCOMPLETE REASON ########################### START
				$ir_arr = ['VENDOR_ID' =>  $var['vendor_id'] , 'INVITE_ID' => $var['invite_id'] ];
				$this->registration_model->delete_table('SMNTP_VENDOR_INCOMPLETE_REASON', $ir_arr);

				// for others
				$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', ['APPROVER_REMARKS' => null], ['VENDOR_INVITE_ID' 	=> $var['invite_id']]);
				################ DELETE INCOMPLETE REASON ########################### END
			}
			$this->save_brands($var);
			$this->save_addresses($var);
			$this->save_contact_details($var);
			$this->save_opd($var); //owner partner directors
			$this->save_authrep($var); //Authorized Representatives
			$this->save_bankrep($var); //Bank References
			$this->save_retcust($var); //Other Retail Customers/Clients
			$this->save_otherbusiness($var); //Other Business
			$this->save_relativeaffiliates($var); //Disclosure of Relatives Working in SM or its Affiliates
			$upload_result = $this->save_uploads($var); //Required Scanned Documents and Required Agreements and Change Company Name Requirements
		

			//catch upload error
			if(!empty($upload_result)){
				$uploaded_file_error = @$upload_result;
			}
		}
		// update status 
		if ($var['status_id'] == 1) // save as draft
			$status_id = 8;
		else // submit
		{
			if ($real_status_id == 190 || $real_status_id == 195 || $real_status_id == 11) //190 = additional req, 195 = incomplete additional req
				$status_id = $real_status_id;
			else
				$status_id = 9;
		}

		$next_arr = array(
						'status' 		=> $status_id,
						'position_id' 	=> $var['position_id'],
						'type' 			=> 1 // registration
					);

		$data = $this->common_model->get_next_process($next_arr);
		$record_arr = array(
						'STATUS_ID'			=> $data['next_status'],
						'DATE_UPDATED'		=> date('Y-m-d H:i:s'),
						'POSITION_ID'		=> $data['next_position']
					);
		$where_arr = array(
						'VENDOR_INVITE_ID' 	=> $var['invite_id']
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
				'VENDOR_INVITE_ID'	=> $var['invite_id'],
				'STATUS_ID'			=> $data['next_status'],
				'POSITION_ID'		=> $data['next_position'],
				'DATE_UPDATED'		=> $date_timestamp,
				'ACTIVE'			=> 1,
				'APPROVER_ID'		=> $var['user_id'],
			);

		// update status and position id
		if ($real_status_id == 190 || $real_status_id == 195 || $real_status_id == 11) //190 = additional req, 195 = incomplete additional req
		{
			if ($var['status_id'] == 2){
				if(empty($data['next_status']) || empty($status_id)){
					$rs = false;
				}else{
					//If walang error sa upload
					if(empty($uploaded_file_error)){
						$this->common_model->insert_table('SMNTP_VENDOR_STATUS_LOGS', $status_logs);
						$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);
					}
				}
			}else{
				$rs = true; // dont update status //save as draft to ng additional req		
			}
		}
		else 
		{
			if ($real_status_id > 9 && $real_status_id != 11 && $real_status_id != 19){ // dont update status kasi nagupload lang sya ng additional req
				$rs = true;
			}else{
				if(empty($data['next_status']) || empty($status_id)){
					$rs = false;
				}else{
					//If walang error sa upload
					if(empty($uploaded_file_error)){
						$this->common_model->insert_table('SMNTP_VENDOR_STATUS_LOGS', $status_logs);
						$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record_arr, $where_arr);
					}
				}
			}
		}

		// echo $this->db->last_query();
		if (@$rs && empty($uploaded_file_error))
		{
			$data['vendor_id'] = $var['vendor_id'];
			$data['status'] = TRUE;
			$data['error'] = '';
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
		}
		
		//$this->response($data['next_status']);
		if( empty($uploaded_file_error) && ( ! empty($data['next_status']) && ! empty($status_id)) && (($data['next_status'] == 10 || $data['next_status'] == 194) && $data['next_position'] == 4)){
			//Send Notif and Email to VRDSTAFF
		
			//Get created_by 
			$created_by = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $var['invite_id']),'CREATED_BY')[0]['CREATED_BY'];
			$vrdstaffs = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('USER_ID' => $created_by),'VRDSTAFF_ID');
				
			$where = '';
			foreach($vrdstaffs as $id){
				if(!empty($id['VRDSTAFF_ID'])){
					$where .= 'USER_ID = ' . $id['VRDSTAFF_ID'] . ' OR ';
				}
			}
			$where = rtrim($where, ' OR');
			
			$vrdstaff_info = $this->common_model->select_query('SMNTP_USERS', $where,'USER_ID, USER_FIRST_NAME, USER_MIDDLE_NAME, USER_LAST_NAME, USER_EMAIL');
			$data['vrdstaffs'] = $vrdstaff_info;
			$data['invite_id'] = $var['invite_id'];
			$data['vendor_info'] = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $var['user_id']),'*');
			
			
		}
		
		//$this->response($this->db->queries);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$data['transaction_result'] = false;
		}else{
			$this->db->trans_commit();
			$data['transaction_result'] = true;
		}
		$this->db->db_debug = $db_debug;
		$data['uploaded_file_error'] = $uploaded_file_error;
		$this->response($data);
	}

	function save_vendor($var)
	{
		$business_yr 		= $this->post('cbo_yr_business');
		$ownership 			= $this->post('ownership');
		$vendor_type 		= $this->post('vendor_type');
		$tax_idno 			= $this->post('tax_idno');
		$tax_class 			= $this->post('tax_class');
		//nature of businiess
		$license_dist 		= ($this->post('nob_license_dist') 	? 1 : 0);
		$manufacturer 		= ($this->post('nob_manufacturer') 	? 1 : 0);
		$importer 			= ($this->post('nob_importer') 		? 1 : 0);
		$wholesaler 		= ($this->post('nob_wholesaler') 	? 1 : 0);
		$nob_others 		= ($this->post('nob_others') 		? 1 : 0);
		$txt_nob_others		= $this->post('txt_nob_others');
		$trade_vendor_type	= $this->post('trade_vendor_type');
		$no_of_employees	= $this->post('no_of_employee');
		$business_asset		= $this->post('business_asset');

		// save to SMNTP_VENDOR first
		$where_arr = array('VENDOR_INVITE_ID' => $var['invite_id']);
		$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

		$created_by = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);
		
		$where_arr2 = array('USER_ID' => $created_by);
		$creator_position = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr2);
		
		//If NTS then gawing 3 ang Vendor Type
		if($creator_position == 11){
			$vendor_type = 3; // 3 = NTS
		}else if($creator_position == 7){
			$vendor_type = 2; // 2 = NTFAS
		}else if($creator_position == 2){
			$vendor_type = 1; // 1 = TRADE
			$tvt = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'TRADE_VENDOR_TYPE', $where_arr);
			if(!empty($tvt)){
				$trade_vendor_type = $tvt;
			}
		}
		
		$save_vendor = array(
							'VENDOR_INVITE_ID'		=> $var['invite_id'],
							'VENDOR_NAME'			=> $vendorname,
							'YEAR_IN_BUSINESS'		=> $business_yr,
							'OWNERSHIP_TYPE'		=> $ownership, // Corporation = 1, Partnership = 2, Sole Proprietorship = 3
							'VENDOR_TYPE'			=> $vendor_type, //Trade = 1, Non Trade = 2
							'TAX_ID_NO'				=> $tax_idno,
							'TAX_CLASSIFICATION'	=> $tax_class,
							'NOB_DISTRIBUTOR'		=> $license_dist,
							'NOB_MANUFACTURER'		=> $manufacturer,
							'NOB_IMPORTER'			=> $importer,
							'NOB_WHOLESALER'		=> $wholesaler,
							'NOB_OTHERS'			=> $nob_others,
							'NOB_OTHERS_TEXT'		=> $txt_nob_others,
							'TRADE_VENDOR_TYPE'		=> $trade_vendor_type, // Outright = 1, Consignor = 2
							'EMPLOYEE'				=> $no_of_employees,
							'BUSINESS_ASSET'		=> $business_asset
					);

		if($var['status_id'] == 1){
			$save_vendor['DATE_CREATED'] = NULL;
		}

		$vendor_id = $this->registration_model->insert_vendor($save_vendor);

		//update users table set vendor id 
		$update_user = array('VENDOR_ID' => $vendor_id);
		$where_arr 	 = array('USER_ID' 	 => $var['user_id']);
		$this->common_model->update_table('SMNTP_USERS', $update_user, $where_arr);

		return $vendor_id;
	}

	function save_brands($var)
	{
		$brand_count 	= $this->post('brand_count');

		for ($i=1; $i <= $brand_count; $i++)
		{ 
			$brand_id 	= $this->post('brand_id'.$i);
			$brand_name = strtoupper(trim($this->post('brand_name'.$i))); //trim spaces
			if (empty($brand_id)) // if brand id is empty insert brand to SMNTP_BRAND then get id and insert to SMNTP_VENDOR_BRAND
			{
				// double check if brand exists then get ID
				if (!empty($brand_name))
				{
					$where = array('UPPER(BRAND_NAME)' => strtoupper($brand_name)); //array('BRAND_NAME' => $brand_name);
					$brand_id = $this->common_model->get_from_table_where_array('SMNTP_BRAND', 'BRAND_ID', $where);
					$bi = array('BRAND_ID'=> $brand_id);
					$this->common_model->update_brand($bi);

				}

				if (empty($brand_id))
				{
					$save_brand = array(
									'BRAND_NAME' => $brand_name,
									'CREATED_BY' => $var['user_id'],
									'DATE_UPLOADED' => date('Y-m-d H:i:s.u'),
									'STATUS' => '1'
								);

					$brand_id = $this->registration_model->insert_brand($save_brand);
				}
			}

			// insert to SMNTP_VENDOR_BRAND
			$save_vendor_brand = array(
									'VENDOR_ID'		=> $var['vendor_id'],
									'BRAND_ID'		=> $brand_id,
									// 'DATE_CREATED'	=> $var['timestamp']
								);
			
			$this->common_model->insert_table('SMNTP_VENDOR_BRAND', $save_vendor_brand);
		}
	}

	function save_addresses($var)
	{
		$office_addr_count	= $this->post('office_addr_count');
		$office_primary		= $this->post('office_primary');

		$batch_office = array();
		for ($i=1; $i <= $office_addr_count; $i++)
		{ 
			$office_add 			= strtoupper(html_entity_decode($this->post('office_add'.$i)));
			$office_brgy_cm_id 		= strtoupper($this->post('office_brgy_cm_id'.$i));
			$office_brgy_cm 		= strtoupper(html_entity_decode($this->post('office_brgy_cm'.$i)));
			$office_state_prov_id 	= strtoupper($this->post('office_state_prov_id'.$i));
			$office_state_prov 		= strtoupper(html_entity_decode($this->post('office_state_prov'.$i)));
			$office_zip_code 		= strtoupper($this->post('office_zip_code'.$i));
			$office_country_id 		= strtoupper($this->post('office_country_id'.$i));
			$office_country 		= strtoupper(html_entity_decode($this->post('office_country'.$i)));
			
			$office_region_id 		= strtoupper($this->post('office_region_id'.$i));
			$office_region 		= strtoupper(html_entity_decode($this->post('office_region'.$i)));

			if (empty($office_region_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($office_region))
				{
					$where = 'upper(REGION_DESC_TWO) = upper(\''.$office_region.'\')';
					$office_region_id = $this->common_model->get_from_table_where_array('SMNTP_REGIONS', 'REGION_ID', $where);
				}

				if (empty($office_region_id) && !empty($office_region))
				{
					$region_arr = array(
									'REGION_DESC_TWO' 	=> $office_region,
									'CREATED_BY' 	=> $var['user_id'],
									'STATUS' 	=> 1
								);

					$office_region_id = $this->registration_model->insert_region($region_arr);
				}
			}

			if (empty($office_brgy_cm_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($office_brgy_cm))
				{
					$where = 'upper(CITY_NAME) = upper(\''.$office_brgy_cm.'\')';
					$office_brgy_cm_id = $this->common_model->get_from_table_where_array('SMNTP_CITY', 'CITY_ID', $where);
				}

				if (empty($office_brgy_cm_id) && !empty($office_brgy_cm))
				{
					$city_arr = array(
									'CITY_NAME' 	=> $office_brgy_cm,
									'CREATED_BY' 	=> $var['user_id']
								);

					$office_brgy_cm_id = $this->registration_model->insert_city($city_arr);
				}
			}

			if (empty($office_state_prov_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($office_state_prov))
				{
					$where = 'upper(STATE_PROV_NAME) = upper(\''.$office_state_prov.'\')';
					$office_state_prov_id = $this->common_model->get_from_table_where_array('SMNTP_STATE_PROVINCE', 'STATE_PROV_ID', $where);
				}

				if (empty($office_state_prov_id) && !empty($office_state_prov))
				{
					$state_arr = array(
									'STATE_PROV_NAME' 	=> $office_state_prov,
									'CREATED_BY' 		=> $var['user_id']
								);

					$office_state_prov_id = $this->registration_model->insert_state($state_arr);
				}
			}

			if (empty($office_country_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($office_country))
				{
					$where = 'upper(COUNTRY_NAME) = upper(\''.$office_country.'\')';
					$office_country_id = $this->common_model->get_from_table_where_array('SMNTP_COUNTRY', 'COUNTRY_ID', $where);
				}

				if (empty($office_country_id) && !empty($office_country))
				{
					$country_arr = array(
									'COUNTRY_NAME' 	=> $office_country,
									'CREATED_BY' 	=> $var['user_id']
								);

					$office_country_id = $this->registration_model->insert_country($country_arr);
				}
			}

			if ($i == $office_primary)
				$primary = 1;
			else
				$primary = 0;

			$batch_office[] = array(
									'VENDOR_ID'				=> $var['vendor_id'],
									'ADDRESS_TYPE'			=> 1, //1 = offce , 2 = factor, 3 = warehouse
									'ADDRESS_LINE'			=> $office_add,
									'BRGY_MUNICIPALITY_ID'	=> $office_brgy_cm_id,
									'STATE_PROVINCE_ID'		=> $office_state_prov_id,
									'ZIP_CODE'				=> $office_zip_code,
									'COUNTRY_ID'			=> $office_country_id,
									'REGION_ID'				=> $office_region_id,
									'`PRIMARY`'				=> $primary,
									// 'DATE_CREATED'			=> $var['timestamp'],
									'ACTIVE'				=> 1
								);
		}

		// SMNTP_VENDOR_ADDRESSES = 1 = offce , 2 = factor, 3 = warehouse
		if (!empty($batch_office))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_ADDRESSES', $batch_office);

		$factory_addr_count	= $this->post('factory_addr_count');
		$factory_primary	= strtoupper($this->post('factory_primary'));

		$batch_factory = array();
		for ($i=1; $i <= $factory_addr_count; $i++)
		{ 
			$factory_addr 			= strtoupper(html_entity_decode($this->post('factory_addr'.$i)));
			$factory_brgy_cm_id 	= strtoupper($this->post('factory_brgy_cm_id'.$i));
			$factory_brgy_cm 		= strtoupper(html_entity_decode($this->post('factory_brgy_cm'.$i)));
			$factory_state_prov_id	= strtoupper($this->post('factory_state_prov_id'.$i));
			$factory_state_prov		= strtoupper(html_entity_decode($this->post('factory_state_prov'.$i)));
			$factory_zip_code 		= strtoupper($this->post('factory_zip_code'.$i));
			$factory_country_id 	= strtoupper($this->post('factory_country_id'.$i));
			$factory_country 		= strtoupper(html_entity_decode($this->post('factory_country'.$i)));
			
			$factory_region_id 		= strtoupper($this->post('factory_region_id'.$i));
			$factory_region 		= strtoupper(html_entity_decode($this->post('factory_region'.$i)));

			if(empty($factory_addr) ||
				(empty($factory_brgy_cm_id) && empty($factory_brgy_cm)) || 
				(empty($factory_state_prov_id) && empty($factory_state_prov)) || 
				(empty($factory_state_prov_id) && empty($factory_state_prov)) || 
				//(empty($factory_region_id) && empty($factory_region)) || 
				empty($factory_zip_code)){
					continue;
				}

			if (empty($factory_region_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($factory_region))
				{
					$where = 'upper(REGION_DESC_TWO) = upper(\''.$factory_region.'\')';
					$factory_region_id = $this->common_model->get_from_table_where_array('SMNTP_REGIONS', 'REGION_ID', $where);
				}

				if (empty($factory_region_id) && !empty($factory_region))
				{
					$region_arr = array(
									'REGION_DESC_TWO' 	=> $factory_region,
									'CREATED_BY' 	=> $var['user_id']
								);

					$factory_region_id = $this->registration_model->insert_region($region_arr);
				}
			}
			
			if (empty($factory_brgy_cm_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($factory_brgy_cm))
				{
					$where = 'upper(CITY_NAME) = upper(\''.$factory_brgy_cm.'\')';
					$factory_brgy_cm_id = $this->common_model->get_from_table_where_array('SMNTP_CITY', 'CITY_ID', $where);
				}

				if (empty($factory_brgy_cm_id) && !empty($factory_brgy_cm))
				{
					$city_arr = array(
									'CITY_NAME' 	=> $factory_brgy_cm,
									'CREATED_BY' 	=> $var['user_id']
								);

					$factory_brgy_cm_id = $this->registration_model->insert_city($city_arr);
				}
			}

			if (empty($factory_state_prov_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($factory_state_prov))
				{
					$where = 'upper(STATE_PROV_NAME) = upper(\''.$factory_state_prov.'\')';
					$factory_state_prov_id = $this->common_model->get_from_table_where_array('SMNTP_STATE_PROVINCE', 'STATE_PROV_ID', $where);
				}

				if (empty($factory_state_prov_id) && !empty($factory_state_prov))
				{
					$state_arr = array(
									'STATE_PROV_NAME' 	=> $factory_state_prov,
									'CREATED_BY' 		=> $var['user_id']
								);

					$factory_state_prov_id = $this->registration_model->insert_state($state_arr);
				}
			}

			if (empty($factory_country_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($factory_country))
				{
					$where = 'upper(COUNTRY_NAME) = upper(\''.$factory_country.'\')';
					$factory_country_id = $this->common_model->get_from_table_where_array('SMNTP_COUNTRY', 'COUNTRY_ID', $where);
				}

				if (empty($factory_country_id) && !empty($factory_country))
				{
					$country_arr = array(
									'COUNTRY_NAME' 	=> $factory_country,
									'CREATED_BY' 	=> $var['user_id']
								);

					$factory_country_id = $this->registration_model->insert_country($country_arr);
				}
			}

			if ($i == $factory_primary)
				$primary = 1;
			else
				$primary = 0;

			$batch_factory[] = array(
									'VENDOR_ID'				=> $var['vendor_id'],
									'ADDRESS_TYPE'			=> 2, //1 = offce , 2 = factor, 3 = warehouse
									'ADDRESS_LINE'			=> $factory_addr,
									'BRGY_MUNICIPALITY_ID'	=> $factory_brgy_cm_id,
									'STATE_PROVINCE_ID'		=> $factory_state_prov_id,
									'ZIP_CODE'				=> $factory_zip_code,
									'COUNTRY_ID'			=> $factory_country_id,
									'REGION_ID'			=> $factory_region_id,
									'`PRIMARY`'				=> $primary,
									// 'DATE_CREATED'			=> $var['timestamp'],
									'ACTIVE'				=> 1
								);
		}

		if (!empty($batch_factory))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_ADDRESSES', $batch_factory);

		$wh_addr_count	= $this->post('wh_addr_count');
		$ware_primary	= $this->post('ware_primary');

		$batch_warehouse = array();
		for ($i=1; $i <= $wh_addr_count; $i++)
		{ 
			$ware_addr 		 	= strtoupper(html_entity_decode($this->post('ware_addr'.$i)));
			$ware_brgy_cm_id 	= $this->post('ware_brgy_cm_id'.$i);
			$ware_brgy_cm 	 	= strtoupper(html_entity_decode($this->post('ware_brgy_cm'.$i)));
			$ware_state_prov_id = $this->post('ware_state_prov_id'.$i);
			$ware_state_prov 	= strtoupper(html_entity_decode($this->post('ware_state_prov'.$i)));
			$ware_zip_code 	 	= strtoupper($this->post('ware_zip_code'.$i));
			$ware_country_id 	= $this->post('ware_country_id'.$i);
			$ware_country 	 	= strtoupper(html_entity_decode($this->post('ware_country'.$i)));
			
			$ware_region_id 		= strtoupper($this->post('ware_region_id'.$i));
			$ware_region 		= strtoupper(html_entity_decode($this->post('ware_region'.$i)));

			if(empty($ware_addr) ||
				(empty($ware_brgy_cm_id) && empty($ware_brgy_cm)) || 
				(empty($ware_state_prov_id) && empty($ware_state_prov)) || 
				//(empty($ware_region_id) && empty($ware_region)) || 
				empty($ware_zip_code)){
					continue;
				}

			if (empty($ware_region_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($ware_region))
				{
					$where = 'upper(REGION_DESC_TWO) = upper(\''.$ware_region.'\')';
					$ware_region_id = $this->common_model->get_from_table_where_array('SMNTP_REGIONS', 'REGION_ID', $where);
				}

				if (empty($ware_region_id) && !empty($ware_region))
				{
					$region_arr = array(
									'REGION_DESC_TWO' 	=> $ware_region,
									'CREATED_BY' 	=> $var['user_id']
								);

					$ware_region_id = $this->registration_model->insert_region($region_arr);
				}
			}
			
			if (empty($ware_brgy_cm_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($ware_brgy_cm))
				{
					$where = 'upper(CITY_NAME) = upper(\''.$ware_brgy_cm.'\')';
					$ware_brgy_cm_id = $this->common_model->get_from_table_where_array('SMNTP_CITY', 'CITY_ID', $where);
				}

				if (empty($ware_brgy_cm_id) && !empty($ware_brgy_cm))
				{
					$city_arr = array(
									'CITY_NAME' 	=> $ware_brgy_cm,
									'CREATED_BY' 	=> $var['user_id']
								);

					$ware_brgy_cm_id = $this->registration_model->insert_city($city_arr);
				}
			}

			if (empty($ware_state_prov_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($ware_state_prov))
				{
					$where = 'upper(STATE_PROV_NAME) = upper(\''.$ware_state_prov.'\')';
					$ware_state_prov_id = $this->common_model->get_from_table_where_array('SMNTP_STATE_PROVINCE', 'STATE_PROV_ID', $where);
				}

				if (empty($ware_state_prov_id) && !empty($ware_state_prov))
				{
					$state_arr = array(
									'STATE_PROV_NAME' 	=> $ware_state_prov,
									'CREATED_BY' 		=> $var['user_id']
								);

					$ware_state_prov_id = $this->registration_model->insert_state($state_arr);
				}
			}

			if (empty($ware_country_id)) // if id empty check name if existing on table then get id else insert then get id
			{
				// double check if exists then get ID
				if (!empty($ware_country))
				{
					$where = 'upper(COUNTRY_NAME) = upper(\''.$ware_country.'\')';
					$ware_country_id = $this->common_model->get_from_table_where_array('SMNTP_COUNTRY', 'COUNTRY_ID', $where);
				}

				if (empty($ware_country_id) && !empty($ware_country))
				{
					$country_arr = array(
									'COUNTRY_NAME' 	=> $ware_country,
									'CREATED_BY' 	=> $var['user_id']
								);

					$ware_country_id = $this->registration_model->insert_country($country_arr);
				}
			}

			if ($i == $ware_primary)
				$primary = 1;
			else
				$primary = 0;

			$batch_warehouse[] = array(
									'VENDOR_ID'				=> $var['vendor_id'],
									'ADDRESS_TYPE'			=> 3, //1 = offce , 2 = factor, 3 = warehouse
									'ADDRESS_LINE'			=> $ware_addr,
									'BRGY_MUNICIPALITY_ID'	=> $ware_brgy_cm_id,
									'STATE_PROVINCE_ID'		=> $ware_state_prov_id,
									'ZIP_CODE'				=> $ware_zip_code,
									'COUNTRY_ID'			=> $ware_country_id,
									'REGION_ID'			=> $ware_region_id,
									'`PRIMARY`'				=> $primary,
									// 'DATE_CREATED'			=> $var['timestamp'],
									'ACTIVE'				=> 1
								);
		}

		if (!empty($batch_warehouse))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_ADDRESSES', $batch_warehouse);
	}

	function save_contact_details($var)
	{
		$telno_count	= $this->post('telno_count');

		$telno_batch = array();
		for ($i=1; $i <= $telno_count; $i++)
		{ 
			$tel_ccode 	= $this->post('tel_ccode'.$i);
			$tel_acode 	= $this->post('tel_acode'.$i);
			$tel_no 	= $this->post('tel_no'.$i);
			$tel_elno 	= $this->post('tel_elno'.$i);

			$telno_batch[] = array(
								'VENDOR_ID'					=> $var['vendor_id'],
								'CONTACT_DETAIL_TYPE'		=> 1, // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
								'CONTACT_DETAIL'			=> $tel_no,
								// 'DATE_CREATED'			=> $var['timestamp'],
								'ACTIVE'					=> 1,
								'COUNTRY_CODE' 				=> $tel_ccode,
								'AREA_CODE' 				=> $tel_acode,
								'EXTENSION_LOCAL_NUMBER' 	=> $tel_elno
							);
		}

		if (!empty($telno_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_CONTACT_DETAILS', $telno_batch);

		$email_count	= $this->post('email_count');

		$email_batch = array();
		for ($i=1; $i <= $email_count; $i++)
		{ 
			$email = $this->post('email'.$i);

			$email_batch[] = array(
								'VENDOR_ID'				=> $var['vendor_id'],
								'CONTACT_DETAIL_TYPE'	=> 4, // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
								'CONTACT_DETAIL'		=> $email,
								// 'DATE_CREATED'			=> $var['timestamp'],
								'ACTIVE'				=> 1
							);
		}

		if (!empty($email_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_CONTACT_DETAILS', $email_batch);

		$faxno_count	= $this->post('faxno_count');

		$faxno_batch = array();
		for ($i=1; $i <= $faxno_count; $i++)
		{ 
			$fax_ccode 	= $this->post('fax_ccode'.$i);
			$fax_acode 	= $this->post('fax_acode'.$i);
			$fax_no 	= $this->post('fax_no'.$i);
			$fax_elno 	= $this->post('fax_elno'.$i);

			$faxno_batch[] = array(
								'VENDOR_ID'					=> $var['vendor_id'],
								'CONTACT_DETAIL_TYPE'		=> 2, // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
								'CONTACT_DETAIL'			=> $fax_no,
								// 'DATE_CREATED'			=> $var['timestamp'],
								'ACTIVE'					=> 1,
								'COUNTRY_CODE' 				=> $fax_ccode,
								'AREA_CODE' 				=> $fax_acode,
								'EXTENSION_LOCAL_NUMBER' 	=> $fax_elno
							);
		}

		if (!empty($faxno_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_CONTACT_DETAILS', $faxno_batch);

		$mobno_count	= $this->post('mobno_count');

		$mobno_batch = array();
		for ($i=1; $i <= $mobno_count; $i++)
		{ 
			$mobile_ccode 	= $this->post('mobile_ccode'.$i);
			$mobile_acode 	= $this->post('mobile_acode'.$i);
			$mobile_no 		= $this->post('mobile_no'.$i);
			$mobile_elno 	= $this->post('mobile_elno'.$i);

			$mobno_batch[] = array(
								'VENDOR_ID'					=> $var['vendor_id'],
								'CONTACT_DETAIL_TYPE'		=> 3, // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
								'CONTACT_DETAIL'			=> $mobile_no,
								// 'DATE_CREATED'			=> $var['timestamp'],
								'ACTIVE'					=> 1,
								'COUNTRY_CODE' 				=> $mobile_ccode,
								'AREA_CODE' 				=> $mobile_acode,
								'EXTENSION_LOCAL_NUMBER' 	=> $mobile_elno
							);
		}

		if (!empty($mobno_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_CONTACT_DETAILS', $mobno_batch);
	}

	function save_opd($var)
	{
		//Owners/Partners/Directors
		$opd_count	= $this->post('opd_count');

		$opd_batch = array();
		for ($i=1; $i <= $opd_count; $i++)
		{ 
			$opd_fname 	= strtoupper($this->post('opd_fname'.$i));
			$opd_mname 	= strtoupper($this->post('opd_mname'.$i));
			$opd_lname 	= strtoupper($this->post('opd_lname'.$i));
			$opd_pos 	= strtoupper($this->post('opd_pos'.$i));

			$opd_name = $opd_lname.', '.$opd_fname.' '.$opd_mname;

			$opd_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'NAME'			=> $opd_name,
								'POSITION'		=> $opd_pos,
								'FIRST_NAME' 	=> $opd_fname,
								'MIDDLE_NAME' 	=> $opd_mname,
								'LAST_NAME' 	=> $opd_lname,
								// 'DATE_CREATED'	=> $var['timestamp'],
								'ACTIVE'		=> 1
							);
		}

		if (!empty($opd_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_OWNERS', $opd_batch);
	}

	function save_authrep($var)
	{
		//Authorized Representatives
		$authrep_count	= $this->post('authrep_count');

		$authrep_batch = array();
		for ($i=1; $i <= $authrep_count; $i++)
		{ 
			$authrep_fname 	= strtoupper($this->post('authrep_fname'.$i));
			$authrep_mname 	= strtoupper($this->post('authrep_mname'.$i));
			$authrep_lname 	= strtoupper($this->post('authrep_lname'.$i));
			$authrep_pos 	= strtoupper($this->post('authrep_pos'.$i));

			$authrep_name = $authrep_lname.', '.$authrep_fname.' '.$authrep_mname;

			$authrep_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'NAME'			=> $authrep_name,
								'POSITION'		=> $authrep_pos,
								'FIRST_NAME' 	=> $authrep_fname,
								'MIDDLE_NAME' 	=> $authrep_mname,
								'LAST_NAME' 	=> $authrep_lname,
								// 'DATE_CREATED'	=> $var['timestamp'],
								'ACTIVE'		=> 1
							);
		}

		if (!empty($authrep_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_REP', $authrep_batch);
	}

	function save_bankrep($var)
	{
		//Bank References
		$bankrep_count	= $this->post('bankrep_count');

		$bankrep_batch = array();
		for ($i=1; $i <= $bankrep_count; $i++)
		{ 
			$bankrep_name 	= strtoupper($this->post('bankrep_name'.$i));
			$bankrep_branch = strtoupper($this->post('bankrep_branch'.$i));

			$bankrep_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'BANK_NAME'		=> $bankrep_name,
								'BANK_BRANCH'	=> $bankrep_branch
							);
		}

		if (!empty($bankrep_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_BANK', $bankrep_batch);
	}

	function save_retcust($var)
	{
		//Other Retail Customers/Clients
		$orcc_count	= $this->post('orcc_count');

		$orcc_batch = array();
		for ($i=1; $i <= $orcc_count; $i++)
		{ 
			$orcc_compname 	= strtoupper($this->post('orcc_compname'.$i));

			$orcc_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'COMPANY_NAME'	=> $orcc_compname,
								// 'DATE_CREATED'	=> $var['timestamp']
							);
		}

		if (!empty($orcc_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_OTHER_RETCUST', $orcc_batch);
	}

	function save_otherbusiness($var)
	{
		//Other Business
		$otherbusiness_count	= $this->post('otherbusiness_count');

		$otherbusiness_batch = array();
		for ($i=1; $i <= $otherbusiness_count; $i++)
		{ 
			$ob_compname 	= strtoupper($this->post('ob_compname'.$i));
			$ob_pso 		= strtoupper($this->post('ob_pso'.$i));

			$otherbusiness_batch[] = array(
								'VENDOR_ID'			=> $var['vendor_id'],
								'COMPANY_NAME'		=> $ob_compname,
								'SERVICE_OFFERED'	=> $ob_pso,
								// 'DATE_CREATED'		=> $var['timestamp']
							);
		}

		if (!empty($otherbusiness_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_OTHER_BUSINESS', $otherbusiness_batch);
	}

	function save_relativeaffiliates($var)
	{
		//Disclosure of Relatives Working in SM or its Affiliates
		$affiliates_count	= $this->post('affiliates_count');

		$affiliate_batch = array();
		for ($i=1; $i <= $affiliates_count; $i++)
		{ 
			$affiliates_fname 		= strtoupper($this->post('affiliates_fname'.$i));
			$affiliates_lname 		= strtoupper($this->post('affiliates_lname'.$i));
			$affiliates_pos 		= strtoupper($this->post('affiliates_pos'.$i));
			$affiliates_comp_afw 	= strtoupper($this->post('affiliates_comp_afw'.$i));
			$affiliates_rel 		= strtoupper($this->post('affiliates_rel'.$i));

			$affiliate_batch[] = array(
								'VENDOR_ID'		=> $var['vendor_id'],
								'FIRST_NAME'	=> $affiliates_fname,
								'LAST_NAME'		=> $affiliates_lname,
								'POSITION'		=> $affiliates_pos,
								'COMPANY'		=> $affiliates_comp_afw,
								'RELATIONSHIP'	=> $affiliates_rel,
								// 'DATE_CREATED'	=> $var['timestamp']
							);
		}

		if (!empty($affiliate_batch))
			$this->common_model->insert_table_batch('SMNTP_VENDOR_RELATIVES', $affiliate_batch);
	}

	function save_uploads($var)
	{

		$vendor_id_check = $this->registration_model->check_vendor_id($var);
		
		//Required Scanned Documents
		$rsd_upload_count	= $this->post('rsd_upload_count');
		
		$docs_var['ownership'] 			= $this->post('ownership');
		$docs_var['trade_vendor_type'] 	= $this->post('trade_vendor_type');
		$docs_var['vendor_type'] 		= $this->post('vendor_type');
		$docs_var['registration_type'] 		= $this->post('registration_type');
		$docs_var['vendor_code_02'] 		= $this->post('vendor_code_02');
		
		// save to SMNTP_VENDOR first
		$where_arr = array('VENDOR_INVITE_ID' => $var['invite_id']);
		$created_by = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);		
		$where_arr2 = array('USER_ID' => $created_by);
		$creator_position = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr2);
		
		//If NTS then gawing 3 ang Vendor Type
		if($creator_position == 11){
			$docs_var['vendor_type'] = 3; // 3 = NTS
		}else if($creator_position == 7){
			$docs_var['vendor_type'] = 2; // 2 = NTFAS
		}else if($creator_position == 2){
			$docs_var['vendor_type'] = 1; // 1 = TRADE
			$tvt = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'TRADE_VENDOR_TYPE', $where_arr);
			if(!empty($tvt)){
				$docs_var['trade_vendor_type'] = $tvt;
			}
		}
		
		$category_sup_count = $this->post('cat_sup_count');
		
		$cat_array = '';
		for($i = 1; $i <= $category_sup_count; $i++){
			if(!($categoryid = $this->post('category_id' . $i))) {
				continue;
			}
			$cat_array .= $categoryid . ',';
			$cat_array .= $this->post('category_id' . $i) . ',';
		}
		
		$docs_var['category_id']		= explode(',', rtrim($cat_array, ','));
		$docs_var['invite_id'] 			= $this->post('invite_id');
		
		$rsd = $this->registration_model->get_rsd_docs($docs_var);	;	

		$rsd_batch = array();
		
		$error_output = array();
		
		for ($i=1; $i <= count($rsd); $i++)
		{ 
			$rsd_document_chk 		= $this->post('rsd_document_chk'.$i); // get id
			$rsd_date_upload 		= $this->post('rsd_date_upload'.$i);
			$rsd_orig_name 			= $this->post('rsd_orig_name'.$i);
			$btn_rsd_preview 		= $this->post('btn_rsd_preview'.$i); // file path
			
			//if ($rsd_date_upload != '')
			//{
			//	$rsd_date_upload = DateTime::createFromFormat('m/d/Y h:i:s A', $rsd_date_upload);
			//	//$rsd_date_upload = $rsd_date_upload->format("d-M-y h.i.s.u A");
			//	$rsd_date_upload = $rsd_date_upload->format("Y-m-d H:i:s");
			//}	
			
			//if ($var['registration_type'] == 3 || $var['registration_type'] == 4){ // add vendor
				$whr = array(
						'DOC_TYPE_ID' => $rsd_document_chk,
						'VENDOR_ID' => $var['vendor_id']
					);
				
				$db_record = $this->common_model->select_query('SMNTP_VENDOR_REQUIRED_DOC',$whr, 'DATE_CREATED,FILE_PATH,ORIGINAL_FILENAME');
				
				if(count($db_record) > 0){
					if($db_record[0]['ORIGINAL_FILENAME'] == $rsd_orig_name){
						if($rsd_date_upload == $db_record[0]['DATE_CREATED']){
							continue;
						}
					}
				}
			//}

			if (!empty($rsd_document_chk))
			{
				$is_file_exists = stripos(@get_headers(@$var['web_site_url'].$btn_rsd_preview)[0],"200 OK") ? true : false;
				if( ! $is_file_exists){
					$doc_row = $this->common_model->select_query_active('REQUIRED_DOCUMENT_NAME', 'SMNTP_VP_REQUIRED_DOCUMENTS',array('REQUIRED_DOCUMENT_ID' => $rsd_document_chk))->row_array();
					
					$doc_name = 'error';
					
					if(!empty($doc_row)){
						$error_output[] = $doc_row['REQUIRED_DOCUMENT_NAME'];
						log_message('error' , 'File does not exists ' .  $doc_row['REQUIRED_DOCUMENT_NAME']);
					}else{
						$error_output[] = $doc_name;
					}
					continue;
				}
				
				$rsd_batch[] = array(
					'VENDOR_ID' 		=> $var['vendor_id'],
					'DOC_TYPE_ID' 		=> $rsd_document_chk,
					'FILE_PATH' 		=> $btn_rsd_preview,
					'DATE_CREATED' 		=> $rsd_date_upload, //'TO_DATE(\''.$rsd_date_upload.'\', \'MM/DD/YYYY HH12:MI:SS AM\')',
					'ORIGINAL_FILENAME' => $rsd_orig_name
				);	
				
				$this->common_model->delete_table('SMNTP_VENDOR_REQUIRED_DOC', array(
					'VENDOR_ID' => $var['vendor_id'],
					'DOC_TYPE_ID' => $rsd_document_chk
				));
			}
			
		}

		if (!empty($rsd_batch)){
			$this->common_model->insert_table_batch('SMNTP_VENDOR_REQUIRED_DOC', $rsd_batch);
		}

		//Required Agreements
		if($docs_var['trade_vendor_type'] == false || $docs_var['trade_vendor_type'] == 0){
			$docs_var['trade_vendor_type'] = 0;
		}

		if($docs_var['registration_type'] == 4 && $docs_var['vendor_code_02'] != ''){
			$docs_var['trade_vendor_type_array'] = array(1, 2);
		}else if($docs_var['registration_type'] == 4 && $docs_var['vendor_code_02'] == ''){
			$docs_var['trade_vendor_type_array'] = $docs_var['trade_vendor_type'];
		}else if($docs_var['vendor_code_02'] != '' && $docs_var['vendor_code_02'] != ''){
			if($docs_var['trade_vendor_type'] == 1){
				$docs_var['trade_vendor_type'] = 2;
			}else if($docs_var['trade_vendor_type'] == 2){
				$docs_var['trade_vendor_type'] = 1;
			}
			$docs_var['trade_vendor_type_array'] = array(1, 2);
		}else{
			$docs_var['trade_vendor_type_array'] = $docs_var['trade_vendor_type'];
		}


		$ra_upload_count	= $this->post('ra_upload_count');
		$ra = $this->registration_model->get_ra_docs($docs_var);

		$ra_status = 0;

		if($ra_upload_count == count($ra)){
			$ra_status = 1;
		}

		$ra_batch = array();
		for ($i=1; $i <= count($ra); $i++)
		{ 
			$to_insert = 1;
			$ra_document_chk 		= $this->post('ra_document_chk'.$i); // get id
			$ra_date_upload 		= $this->post('ra_date_upload'.$i);
			$ra_orig_name 			= $this->post('ra_orig_name'.$i);
			$btn_ra_preview 		= $this->post('btn_ra_preview'.$i); // file path
			
			//if ($ra_date_upload != '')
			//{
			//	$ra_date_upload = DateTime::createFromFormat('m/d/Y h:i:s A', $ra_date_upload);
			//	//$ra_date_upload = $ra_date_upload->format('d-M-y h.i.s.u A');
			//	$ra_date_upload = $ra_date_upload->format('Y-m-d H:i:s');
			//}
			
			//if ($var['registration_type'] == 3 || $var['registration_type'] == 4){ // add vendor
				$whr = array(
						'DOC_TYPE_ID' => $ra_document_chk,
						'VENDOR_ID' => $var['vendor_id']
					);
				
				$db_record = $this->common_model->select_query('SMNTP_VENDOR_AGREEMENTS',$whr, 'DATE_CREATED,FILE_PATH,ORIGINAL_FILENAME');	
				
				if(count($db_record) > 0){
					if($db_record[0]['ORIGINAL_FILENAME'] == $ra_orig_name){
						if($ra_date_upload == $db_record[0]['DATE_CREATED']){
							$to_insert = 0;
						}
					}
				}
			//}
			if($to_insert == 1){
				if (!empty($ra_document_chk))
				{
						
					$is_file_exists = stripos(@get_headers(@$var['web_site_url'].$btn_ra_preview)[0],"200 OK") ? true : false;
					if( ! $is_file_exists){
						$doc_row = $this->common_model->select_query_active('REQUIRED_AGREEMENT_NAME', 'SMNTP_VP_REQUIRED_AGREEMENTS',array('REQUIRED_AGREEMENT_ID' => $ra_document_chk))->row_array();
						
						$doc_name = 'error';
						
						if(!empty($doc_row)){
							$error_output[] = $doc_row['REQUIRED_AGREEMENT_NAME'];
							log_message('error' , 'File does not exists ' .  $doc_row['REQUIRED_AGREEMENT_NAME']);
						}else{
							$error_output[] = $doc_name;
						}
						
						continue;
					}
					$ra_batch[] = array(
						'VENDOR_ID' 		=> $var['vendor_id'],
						'DOC_TYPE_ID' 		=> $ra_document_chk,
						'FILE_PATH' 		=> $btn_ra_preview,
						'DATE_CREATED' 		=> $ra_date_upload, //'TO_DATE('.$ra_date_upload.', \'MM/DD/YYYY HH12:MI AM\')',
						'ORIGINAL_FILENAME' => $ra_orig_name,
						'DOC_STATUS'		=> $ra_status
					);
					
					
					$this->common_model->delete_table('SMNTP_VENDOR_AGREEMENTS', array(
						'VENDOR_ID' => $var['vendor_id'],
						'DOC_TYPE_ID' => $ra_document_chk
					));
					
				}
			}
			
			// if already complete, update all to doc status 1
			if($ra_status == 1){
				$record = array(
					'DOC_STATUS' 	=> 1 // after accepting dpa in process na
						);
					
				$where = array(
						'VENDOR_ID' => $var['vendor_id']
					);
				$this->common_model->update_table('SMNTP_VENDOR_AGREEMENTS', $record, $where);
			}
			
		}

		if (!empty($ra_batch)){
			$this->common_model->insert_table_batch('SMNTP_VENDOR_AGREEMENTS', $ra_batch);
		}
		
		//Change Company Name Requirements
		if($var['registration_type'] == 5){
			$ccn_upload_count	= $this->post('ccn_upload_count');
			$ccn = $this->registration_model->get_ccn_docs();

			$ccn_status = 0;

			if($ccn_upload_count == count($ccn)){
				$ccn_status = 1;
			}

			$ccn_batch = array();
			for ($i=1; $i <= count($ccn); $i++)
			{ 
				$ccn_document_chk 		= $this->post('ccn_document_chk'.$i); // get id
				$ccn_date_upload 		= $this->post('ccn_date_upload'.$i);
				$ccn_orig_name 			= $this->post('ccn_orig_name'.$i);
				$btn_ccn_preview 		= $this->post('btn_ccn_preview'.$i); // file path
				
				//if ($ccn_date_upload != '')
				//{
				//	$ccn_date_upload = DateTime::createFromFormat('m/d/Y h:i:s A', $ccn_date_upload);
				//	$ccn_date_upload = $ccn_date_upload->format('Y-m-d H:i:s');
				//}

				if (!empty($ccn_document_chk))
				{
						
					$is_file_exists = stripos(@get_headers(@$var['web_site_url'].$btn_ccn_preview)[0],"200 OK") ? true : false;
					if( ! $is_file_exists){
						$doc_row = $this->common_model->select_query_active('REQUIRED_CCN_NAME', 'SMNTP_VP_REQUIRED_DOCS_CCN',array('REQUIRED_CCN_ID' => $ccn_document_chk))->row_array();
						
						$doc_name = 'error';
						
						if(!empty($doc_row)){
							$error_output[] = $doc_row['REQUIRED_CCN_NAME'];
							log_message('error' , 'File does not exists ' .  $doc_row['REQUIRED_CCN_NAME']);
						}else{
							$error_output[] = $doc_name;
						}
						
						continue;
					}
					$ccn_batch[] = array(
						'VENDOR_ID' 		=> $var['vendor_id'],
						'DOC_TYPE_ID' 		=> $ccn_document_chk,
						'FILE_PATH' 		=> $btn_ccn_preview,
						'DATE_CREATED' 		=> $ccn_date_upload, //'TO_DATE('.$ra_date_upload.', \'MM/DD/YYYY HH12:MI AM\')',
						'ORIGINAL_FILENAME' => $ccn_orig_name,
						'DOC_STATUS'		=> $ccn_status
					);
					
					
					$this->common_model->delete_table('SMNTP_VENDOR_CCN', array(
						'VENDOR_ID' => $var['vendor_id'],
						'DOC_TYPE_ID' => $ccn_document_chk
					));
				}
				
			}

			if (!empty($ccn_batch))
				$this->common_model->insert_table_batch('SMNTP_VENDOR_CCN', $ccn_batch);
		}
	
		return $error_output;
	}

	function vendor_data_get()
	{
		$user_id 	= $this->get('user_id');
		$vendor_id 	= $this->get('vendor_id');
		$invite_id 	= $this->get('invite_id');

		if(empty($invite_id)){
			$result = $this->registration_model->check_vendor_id_v2(array('vendor_id' => $vendor_id));
			$registration_type = $result[0]['REGISTRATION_TYPE'];
		}else{
			$where_arr = array('VENDOR_INVITE_ID' => $invite_id);
			$registration_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'REGISTRATION_TYPE', $where_arr);
		}

		$where_arr = array(
			'VENDOR_ID' => $vendor_id
		);
		
		if(empty($vendor_id)){
			$result = $this->registration_model->check_vendor_id(array('invite_id' => $invite_id));
			if( ! empty($result)){
				$where_arr['VENDOR_ID'] = $result[0]['VENDOR_ID'];
				$vendor_id = $result[0]['VENDOR_ID'];
			}
		}

		$rs_vendor 			= $this->registration_model->get_vendor_data($where_arr);
		$rs_brand 			= $this->registration_model->get_vendor_brand($where_arr);

		

		if (empty($invite_id))
			$invite_id = $rs_vendor->row()->VENDOR_INVITE_ID;

		$where_arr_vendor_invite_dtl = array(
								'SVI.VENDOR_INVITE_ID' 	=> $invite_id
							);
		$rs_vendor_invite_dtl = $this->registration_model->get_vendor_invite_details($where_arr_vendor_invite_dtl);

		$where_arr_office = array(
								'VENDOR_ID' 	=> $vendor_id,
								'ADDRESS_TYPE'  => 1 //1 = offce , 2 = factor, 3 = warehouse
							);
		$rs_addr_office 	= $this->registration_model->get_vendor_addr($where_arr_office);

		$where_arr_factory = array(
								'VENDOR_ID' 	=> $vendor_id,
								'ADDRESS_TYPE'  => 2 //1 = offce , 2 = factor, 3 = warehouse
							);
		$rs_addr_factory 	= $this->registration_model->get_vendor_addr($where_arr_factory);

		$where_arr_warehouse = array(
								'VENDOR_ID' 	=> $vendor_id,
								'ADDRESS_TYPE'  => 3 //1 = offce , 2 = factor, 3 = warehouse
							);
		$rs_addr_warehouse 	= $this->registration_model->get_vendor_addr($where_arr_warehouse);

		$where_arr_telno = array(
								'VENDOR_ID' 	=> $vendor_id,
								'CONTACT_DETAIL_TYPE'  => 1 // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
							);
		$rs_telno 	= $this->registration_model->get_vendor_contact($where_arr_telno);

		$where_arr_faxno = array(
								'VENDOR_ID' 	=> $vendor_id,
								'CONTACT_DETAIL_TYPE'  => 2 // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
							);
		$rs_faxno 	= $this->registration_model->get_vendor_contact($where_arr_faxno);

		$where_arr_mobno = array(
								'VENDOR_ID' 	=> $vendor_id,
								'CONTACT_DETAIL_TYPE'  => 3 // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
							);
		$rs_mobno 	= $this->registration_model->get_vendor_contact($where_arr_mobno);

		$where_arr_email = array(
								'VENDOR_ID' 	=> $vendor_id,
								'CONTACT_DETAIL_TYPE'  => 4 // type is 1 = telno, 2 faxno, 3 = mobno, 4 = email
							);

		$var['SVI.VENDOR_INVITE_ID'] = $invite_id;
		$rs 	= $this->invitecreation_model->get_invite_record($var);
		
		// Added MSF - 20191118 (IJR-10618)
		//$var2['SVC.VENDOR_INVITE_ID'] = $invite_id;
		//$var3['SVSC.VENDOR_INVITE_ID'] = $invite_id;
		//if($rs['query'][0]['REGISTRATION_TYPE'] != 4){
		//	$rs_cat = $this->invitecreation_model->get_invite_categories($var2);	
		//}else{
		//	if($rs['query'][0]['STATUS_ID'] != 19){
		//		$rs_cat = $this->invitecreation_model->get_invite_avc_categories($var2);	
		//	}else{
		//		$rs_cat = $this->invitecreation_model->get_invite_categories($var2);	
		//	}
		//}
		//
		//// Added MSF - 20191118 (IJR-10618)
		//if($rs['query'][0]['REGISTRATION_TYPE'] != 4){
		//	$rs_sub_cat = $this->invitecreation_model->get_invite_sub_categories($var3);
		//}else{
		//	if($rs['query'][0]['STATUS_ID'] != 19){
		//		$rs_sub_cat = $this->invitecreation_model->get_invite_avc_sub_categories($var3);	
		//	}else{
		//		$rs_sub_cat = $this->invitecreation_model->get_invite_sub_categories($var3);
		//	}
		//}
		
		$var2['SVC.VENDOR_INVITE_ID'] = $invite_id;
		$rs_cat = $this->invitecreation_model->get_invite_all_category($var2);
		$rs_cat_avc = $this->invitecreation_model->get_invite_allavc($var2);
		
		//$rs_avc_cat = $this->invitecreation_model->get_invite_avc_categories($var2);	
		//$rs_avc_sub_cat = $this->invitecreation_model->get_invite_avc_sub_categories($var3);

		$status_data['invite_id'] = $var2['SVC.VENDOR_INVITE_ID'];
		$rs_status 	= $this->registration_model->get_vendor_id($status_data);
		$data['rs_vrdnote'] 				= $rs_status['vrdnote'];
		$data['rs_note'] 					= $rs_status['note'];
		$data['position_id'] 				= $rs_status['position_id'];
		$data['creator_position_id'] 		= $rs_status['creator_position_id'];


		$rs_email 	= $this->registration_model->get_vendor_contact($where_arr_email);
		$rs_owner 	= $this->registration_model->get_vendor_owner($where_arr);
		$rs_authrep = $this->registration_model->get_vendor_authrep($where_arr);
		$rs_bank 	= $this->registration_model->get_vendor_bank($where_arr);
		$rs_retcust = $this->registration_model->get_vendor_retcust($where_arr);
		$rs_ob 		= $this->registration_model->get_vendor_other_business($where_arr);
		$rs_rel 	= $this->registration_model->get_vendor_relatives($where_arr);
		
		$rs_reqdoc 	= $this->registration_model->get_vendor_req_doc($where_arr);
		$rs_agree 	= $this->registration_model->get_vendor_agreements($where_arr);
	
		$rs_ccn 	= $this->registration_model->get_vendor_ccn($where_arr);
		$rs_ir 		= $this->registration_model->get_ir($where_arr);

		
		$vendor_invite_data = $this->registration_model->get_vendor_invite(array(
			'VENDOR_INVITE_ID'	=> $invite_id
		))->row_array();
		
		$inviter_matrix_data = $this->registration_model->get_users_in_matrix($vendor_invite_data['CREATED_BY'])[0];
		
		$approver_id = '';
		
		if( ! empty($inviter_matrix_data['BUHEAD_ID'])){
			$approver_id = $inviter_matrix_data['BUHEAD_ID'];
		}else if( ! empty($inviter_matrix_data['GHEAD_ID'])){
			$approver_id = $inviter_matrix_data['GHEAD_ID'];
		}
		
		$inviter_matrix_data_for_vrdhead = $this->registration_model->get_vrdhead_in_matrix($vendor_invite_data['CREATED_BY'])[0];
		$vrd_head_id = '';
		$vrd_head_id = $inviter_matrix_data_for_vrdhead['VRDHEAD_ID'];
		
		$vrdhead_data = $this->registration_model->get_user(array(
			'USER_ID' => $vrd_head_id
		))->row_array();
		
		$vrdhead_name = $vrdhead_data['USER_FIRST_NAME'] . ' ';
		$vrdhead_name .= (empty($vrdhead_data['USER_MIDDLE_NAME']) ? '' : $vrdhead_data['USER_MIDDLE_NAME'] . ' ');
		$vrdhead_name .= (empty($vrdhead_data['USER_LAST_NAME']) ? '' : $vrdhead_data['USER_LAST_NAME'] . ' ');
		
		$data['vrd_head'] 			= trim($vrdhead_name);
		
		/*$vendor_invite_logs 	= $this->registration_model->get_vendor_invite_logs(array(
			'VENDOR_INVITE_ID'	=> $invite_id,
			'STATUS_ID'			=> 3 //INVITED
		))->row_array();*/
		
		$division_head_data = $this->registration_model->get_user(array(
			'USER_ID' => $approver_id
		))->row_array();
		
		$division_head_name = $division_head_data['USER_FIRST_NAME'] . ' ';
		$division_head_name .= (empty($division_head_data['USER_MIDDLE_NAME']) ? '' : $division_head_data['USER_MIDDLE_NAME'] . ' ');
		$division_head_name .= (empty($division_head_data['USER_LAST_NAME']) ? '' : $division_head_data['USER_LAST_NAME'] . ' ');
		
		$data['division_head'] 			= trim($division_head_name);
		
		$where_arr = array('VENDOR_INVITE_ID' => $invite_id);
		$cc_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CC_VENDOR_CODE', $where_arr);
		$where_arr_cc = array('VENDOR_INVITE_ID' => $cc_vendor_code);
		$current_vt = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'TRADE_VENDOR_TYPE', $where_arr);
		$prev_vt = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'TRADE_VENDOR_TYPE', $where_arr_cc);
		$cc_vendor_name = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr_cc);
		$vendor_code_02 = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE_02', $where_arr_cc);
		$prev_registration_type_cc = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'PREV_REGISTRATION_TYPE', $where_arr_cc);
		
		if($vendor_code_02 != ''){
			if($prev_registration_type_cc == 4){
				if($prev_vt != $current_vt){
					$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE', $where_arr_cc);		
				}else{
					$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE_02', $where_arr_cc);
				}
			}else{
				if($prev_vt != $current_vt){
					$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE_02', $where_arr_cc);	
				}else{
					$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE', $where_arr_cc);	
				}
			}
		}else{
			$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE', $where_arr_cc);	
		}

		$data['xx_vendor_code']			= $old_vendor_code;
		$data['xx_vendor_name']			= $cc_vendor_name;
		$data['rs_registration_type']	= $registration_type;
		$data['rs_vreg'] 				= $rs_vendor->result_array();
		$data['count_vreg'] 			= $rs_vendor->num_rows();
		$data['rs_vbrand'] 				= $rs_brand->result_array();
		$data['count_vbrand'] 			= $rs_brand->num_rows();
		$data['rs_vaddr_office'] 		= $rs_addr_office->result_array();
		$data['count_vaddr_office'] 	= $rs_addr_office->num_rows();
		$data['rs_vaddr_factory'] 		= $rs_addr_factory->result_array();
		$data['count_vaddr_factory'] 	= $rs_addr_factory->num_rows();
		$data['rs_vaddr_warehouse'] 	= $rs_addr_warehouse->result_array();
		$data['count_vaddr_warehouse'] 	= $rs_addr_warehouse->num_rows();
		$data['rs_vc_telno'] 			= $rs_telno->result_array();
		$data['count_vc_telno'] 		= $rs_telno->num_rows();
		$data['rs_vc_faxno'] 			= $rs_faxno->result_array();
		$data['count_vc_faxno'] 		= $rs_faxno->num_rows();
		$data['rs_vc_mobno'] 			= $rs_mobno->result_array();
		$data['count_vc_mobno'] 		= $rs_mobno->num_rows();
		$data['rs_vc_email'] 			= $rs_email->result_array();
		$data['count_vc_email'] 		= $rs_email->num_rows();
		$data['rs_vowner'] 				= $rs_owner->result_array();
		$data['count_vowner'] 			= $rs_owner->num_rows();
		$data['rs_vauthrep'] 			= $rs_authrep->result_array();
		$data['count_vauthrep'] 		= $rs_authrep->num_rows();
		$data['rs_vbank'] 				= $rs_bank->result_array();
		$data['count_vbank'] 			= $rs_bank->num_rows();
		$data['rs_vretcust'] 			= $rs_retcust->result_array();
		$data['count_vretcust'] 		= $rs_retcust->num_rows();
		$data['rs_vob'] 				= $rs_ob->result_array();
		$data['count_vob'] 				= $rs_ob->num_rows();
		$data['rs_vrel'] 				= $rs_rel->result_array();
		$data['count_vrel'] 			= $rs_rel->num_rows();
		$data['rs_vreqdoc'] 			= $rs_reqdoc->result_array();
		$data['count_vreqdoc'] 			= $rs_reqdoc->num_rows();
		$data['rs_vagree'] 				= $rs_agree->result_array();
		$data['count_vagree'] 			= $rs_agree->num_rows();
		$data['rs_ccn'] 				= $rs_ccn->result_array();
		$data['count_ccn'] 				= $rs_ccn->num_rows();
		//$data['rs_cat'] 				= $rs_cat->result_array();
		//$data['count_cat'] 				= $rs_cat->num_rows();
		//$data['rs_avc_cat'] 				= $rs_avc_cat->result_array();
		//$data['count_avc_cat'] 				= $rs_avc_cat->num_rows();
		// Added MSF - 20191118 (IJR-10618)
		//$data['rs_sub_cat'] 			= $rs_sub_cat->result_array();
		//$data['count_sub_cat'] 			= $rs_sub_cat->num_rows();
		//$data['rs_avc_sub_cat'] 			= $rs_avc_sub_cat->result_array();
		//$data['count_avc_sub_cat'] 			= $rs_avc_sub_cat->num_rows();
		$data['rs_cat'] = $rs_cat->result_array();
		$data['count_cat'] = $rs_cat->num_rows();
		$data['rs_cat_avc'] = $rs_cat_avc->result_array();
		$data['count_cat_avc'] = $rs_cat_avc->num_rows();
		$data['rs_vendor_invite_dtl']	= $rs_vendor_invite_dtl->result_array();
		$data['count_vendor_invite_dtl']= $rs_vendor_invite_dtl->num_rows();
		
		$data['rs_ir'] 					= $rs_ir->result_array();
		$data['count_ir'] 				= $rs_ir->num_rows();

		$this->response($data);
	}


	// ----------------------------- topher functions -----------------------------

	function fetch_vendorid_approval_get()
	{
		$data['invite_id'] 				= $this->get('invite_id');
		$data['position_id'] 			= $this->get('position_id');
		$data['registration_type'] 			= $this->get('registration_type');
		$avc_termspayment = '';
		
		$rs = $this->registration_model->get_vendor_id($data);

		$where_arr = array('VENDOR_INVITE_ID' => $data['invite_id']);
		$vendor_name = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);

		$where_arr = array('VENDOR_INVITE_ID' => $data['invite_id']);
		$vrd_remarks = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'VRDNOTE', $where_arr);
		$hats_remarks = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'NOTE', $where_arr);
		$termspayment = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'TERMSPAYMENT', $where_arr);
		$avc_termspayment = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'AVC_TERMSPAYMENT', $where_arr);

		$rs['vendor_name']				= $vendor_name;
		$rs['vrd_remarks']				= $vrd_remarks;
		$rs['hats_remarks']				= $hats_remarks;
		$rs['termspayment']				= $termspayment;
		$rs['avc_termspayment']				= $avc_termspayment;
		
		$vrd_remarks = trim($rs['vrd_remarks']);
		if(empty($vrd_remarks)){
			$rs['vrd_remarks'] = NULL;
		}
		
		$hats_remarks = $hats_remarks;
		if(empty($hats_remarks)){
			$rs['hats_remarks'] = NULL;
		}
		
		$this->response($rs);

	}

	function save_approval_put()
	{
		$data['status'] 				= $this->put('status'); 
		$data['action'] 				= $this->put('action'); 
		$data['nxt_position_id'] 		= $this->put('nxt_position_id');
		$data['vendor_id'] 				= $this->put('vendor_id');
		$data['invite_id'] 				= $this->put('invite_id');
		$data['user_id'] 				= $this->put('user_id');
		$data['cbo_tp'] 				= $this->put('cbo_tp');
		$data['note_hts'] 				= $this->put('note_hts');
		$data['note_vrd'] 				= $this->put('note_vrd');
		$data['reject_remarks']			= $this->put('reject_remarks');
		$data['user_position_id']		= $this->put('user_position_id');
		$data['position_id']			= $this->put('position_id');
		$data['reg_type_id']			= $this->put('reg_type_id');
		
		$note_hts = trim($data['note_hts']);
		if(empty($note_hts)){
			$data['note_hts'] = NULL;
		}
		
		$note_vrd = trim($data['note_vrd']);
		if(empty($note_vrd)){
			$data['note_vrd'] = NULL;
		}
		
		if($data['action'] == 0){	
			if($data['user_position_id'] == 6 || ($data['user_position_id'] == 5 && $data['position_id'] == 6)){
				$data['reject_remarks'] = $data['note_hts'];
			}else if($data['user_position_id'] == 5){
				$data['reject_remarks'] = $data['note_vrd'];
			}
		}
		
		$rs = $this->registration_model->update_approval_status($data);

		$this->response($rs);

	}

	function update_vendor($var)
	{
		$business_yr 		= $this->post('cbo_yr_business');
		$ownership 			= $this->post('ownership');
		$vendor_type 		= $this->post('vendor_type');
		$tax_idno 			= $this->post('tax_idno');
		$tax_class 			= $this->post('tax_class');
		//nature of businiess
		$license_dist 		= ($this->post('nob_license_dist') 	? 1 : 0);
		$manufacturer 		= ($this->post('nob_manufacturer') 	? 1 : 0);
		$importer 			= ($this->post('nob_importer') 		? 1 : 0);
		$wholesaler 		= ($this->post('nob_wholesaler') 	? 1 : 0);
		$nob_others 		= ($this->post('nob_others') 		? 1 : 0);
		$txt_nob_others		= $this->post('txt_nob_others');
		$trade_vendor_type	= $this->post('trade_vendor_type');
		
		//business_asset and no_of_employee
		$business_asset = $this->post('business_asset');
		$no_of_employee = $this->post('no_of_employee');
		
		// update to SMNTP_VENDOR first
		$where_arr = array('VENDOR_INVITE_ID' => $var['invite_id']);
		$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);
		
		$created_by = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);
		
		$where_arr2 = array('USER_ID' => $created_by);
		$creator_position = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr2);
		
		//If NTS then gawing 3 ang Vendor Type
		if($creator_position == 11){
			$vendor_type = 3; // 3 = NTS
		}else if($creator_position == 7){
			$vendor_type = 2; // 2 = NTFAS
		}else if($creator_position == 2){
			$vendor_type = 1; // 1 = TRADE
			$tvt = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'TRADE_VENDOR_TYPE', $where_arr);
			if(!empty($tvt)){
				$trade_vendor_type = $tvt;
			}
		}
		
		$arr_record_vendor = array(
							'VENDOR_INVITE_ID'		=> $var['invite_id'],
							'VENDOR_NAME'			=> $vendorname,
							'YEAR_IN_BUSINESS'		=> $business_yr,
							'OWNERSHIP_TYPE'		=> $ownership, // Corporation = 1, Partnership = 2, Sole Proprietorship = 3
							'VENDOR_TYPE'			=> $vendor_type, //Trade = 1, Non Trade = 2
							'TAX_ID_NO'				=> $tax_idno,
							'TAX_CLASSIFICATION'	=> $tax_class,
							'NOB_DISTRIBUTOR'		=> $license_dist,
							'NOB_MANUFACTURER'		=> $manufacturer,
							'NOB_IMPORTER'			=> $importer,
							'NOB_WHOLESALER'		=> $wholesaler,
							'NOB_OTHERS'			=> $nob_others,
							'NOB_OTHERS_TEXT'		=> $txt_nob_others,
							'TRADE_VENDOR_TYPE'		=> $trade_vendor_type, // Outright = 1, Consignor = 2
							'EMPLOYEE'		=> $no_of_employee, 
							'BUSINESS_ASSET'		=> $business_asset 
					);

		$where_vendor_arr = array('VENDOR_ID' => $var['vendor_id']);
		
		if($var['status_id'] == 1){
			$already_submitted = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'DATE_CREATED', $where_vendor_arr);
			if(empty($already_submitted)){
				$arr_record_vendor['DATE_CREATED'] = NULL;
			}
		}else{
			$this->registration_model->update_vendor_submitted_date($var['vendor_id']);
		}
		
		
		$vendor_id_check = $this->users_model->get_vendor_user_data($var['vendor_id'])->row_array();
		if (empty($vendor_id_check)) // add vendor
		{
			$vendor_invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_vendor_arr);
			$user_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'USER_ID', array('VENDOR_INVITE_ID' => $vendor_invite_id));
			$this->common_model->update_table('SMNTP_USERS', array('VENDOR_ID' => $var['vendor_id']), array('USER_ID' => $user_id));
		}
		
		$this->common_model->update_table('SMNTP_VENDOR', $arr_record_vendor, $where_vendor_arr);
	}

	function delete_incrementing_tables($var) // delete to save new post
	{
		$where_vendor_arr = array('VENDOR_ID' => $var['vendor_id']);

		$this->registration_model->delete_table('SMNTP_VENDOR_BRAND', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_ADDRESSES', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_CONTACT_DETAILS', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_OWNERS', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_REP', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_BANK', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_OTHER_RETCUST', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_OTHER_BUSINESS', $where_vendor_arr);
		$this->registration_model->delete_table('SMNTP_VENDOR_RELATIVES', $where_vendor_arr);
		//$this->registration_model->delete_table('SMNTP_VENDOR_REQUIRED_DOC', $where_vendor_arr);
		//$this->registration_model->delete_table('SMNTP_VENDOR_AGREEMENTS', $where_vendor_arr);
	}

	function approval_email_data_get()
	{
		$data['user_id'] = $this->get('user_id');
		$data['user_position_id'] = $this->get('user_position_id');
		$data['invite_id'] = $this->get('invite_id');
		$data['position_id'] = $this->get('position_id');
		$data['reject_remarks'] = $this->get('reject_remarks');

		// get positionname of the approver
		$where_arr = array('POSITION_ID' => $data['position_id']);
		$data['positionname'] = $this->common_model->get_from_table_where_array('SMNTP_POSITION', 'POSITION_NAME', $where_arr);

		//get email datas
		$rs = $this->registration_model->approval_email_data($data);
		
		if(empty($data['reject_remarks'])){
			if($rs['query_result']->num_rows() > 0)
			{
				if($data['user_position_id'] == 5)// vrdhead
				{
					if($data['current_status_id'] != 15){
						if($rs['creator_position'] == 2)//senmer
						{
							if(!empty($rs['query_result']->row(0)->BUHEAD_ID) && $rs['query_result']->row(0)->BUHEAD_ID != $data['user_id'])
							{
								$where_arr = array('USER_ID' => $rs['query_result']->row(0)->BUHEAD_ID);
								$email = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);
								array_push($rs['cc'], $email);
							}
						}
						else if($rs['creator_position'] == 7)//buyer
						{
							if(!empty($rs['query_result']->row(0)->GHEAD_ID) && $rs['query_result']->row(0)->GHEAD_ID != $data['user_id'])
							{
								$where_arr = array('USER_ID' => $rs['query_result']->row(0)->GHEAD_ID);
								$email = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);
								array_push($rs['cc'], $email);
							}
						}
					}
				}
				else if($data['user_position_id'] == 6)// uhats
				{
					if($rs['creator_position'] == 2)//senmer
					{
						if(!empty($rs['query_result']->row(0)->BUHEAD_ID) && $rs['query_result']->row(0)->BUHEAD_ID != $data['user_id'])
						{
							$where_arr = array('USER_ID' => $rs['query_result']->row(0)->BUHEAD_ID);
							$email = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);
							array_push($rs['cc'], $email);
						}
					}
					else if($rs['creator_position'] == 7)//buyer
					{
						if(!empty($rs['query_result']->row(0)->GHEAD_ID) && $rs['query_result']->row(0)->GHEAD_ID != $data['user_id'])
						{
							$where_arr = array('USER_ID' => $rs['query_result']->row(0)->GHEAD_ID);
							$email = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);
							array_push($rs['cc'], $email);
						}
					}
					
					if(!empty($rs['query_result']->row(0)->VRDHEAD_ID) && $rs['query_result']->row(0)->VRDHEAD_ID != $data['user_id'])
					{
						$where_arr = array('USER_ID' => $rs['query_result']->row(0)->VRDHEAD_ID);
						$email = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);
						array_push($rs['cc'], $email);
					}


				}
			}
		}
		//send email notif
  		$this->common_model->send_email_notification($rs);

  		$this->response($rs);
	}

	function payment_terms_get()
	{
		$data = $this->registration_model->get_terms_of_payment();
		$this->response($data);
	}
	
	function splash_screen_get()
	{
		$rs = $this->registration_model->get_splash();
		
		$var['dpa_title'] = $rs->row()->LST_TITLE;
		$var['dpa_message'] = $rs->row()->LST_MESSAGE;
		// $var['dpa_sections'] = $rs->row()->LST_SECTIONS;
		$var['dpa_link_label'] = $rs->row()->LINK_LABEL;
		$this->response($var);
	}
	
	function doc_notification_get()
	{
		$rs = $this->registration_model->get_doc_notification();
		
		$var['dn_title'] = $rs->row()->SDT_TITLE;
		$var['dn_message'] = $rs->row()->SDT_MSG;
		$this->response($var);
	}

	function filter_city_get()
	{
		$rs = $this->registration_model->get_filter_city();
		
		if ($rs)
		{
			$data = $rs;

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	function filter_state_get()
	{
		$rs = $this->registration_model->get_filter_state();
		
		if ($rs)
		{
			$data = $rs;

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	function filter_country_get()
	{
		$rs = $this->registration_model->get_filter_country();
		
		if ($rs)
		{
			$data = $rs;

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	function filter_region_get()
	{
		$rs = $this->registration_model->get_filter_region();
		
		if ($rs)
		{
			$data = $rs;

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	function vendor_type_get()
	{
		$invite_id = $this->get('invite_id');
		$where_arr = array('VENDOR_INVITE_ID' => $invite_id);
		$vendor_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'BUSINESS_TYPE', $where_arr);
		$trade_vendor_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'TRADE_VENDOR_TYPE', $where_arr);

		$data = [
					'vendor_type' 			=> $vendor_type,
					'trade_vendor_type' 	=> $trade_vendor_type
				];
		$this->response($data);
	}

	function vendor_info_get()
	{
		$invite_id = $this->get('invite_id');
		$where_arr = array('VENDOR_INVITE_ID' => $invite_id);
		$registration_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'REGISTRATION_TYPE', $where_arr);
		$prev_registration_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'PREV_REGISTRATION_TYPE', $where_arr);
		//$cc_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CC_VENDOR_CODE', $where_arr);
		//$where_arr_cc = array('VENDOR_INVITE_ID' => $cc_vendor_code);
		//$cc_vendor_name = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr_cc);
		//$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE', $where_arr_cc);
		$current_status = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'STATUS_ID', $where_arr);
		
		
		//$where_arr = array('VENDOR_INVITE_ID' => $invite_id);
		$cc_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CC_VENDOR_CODE', $where_arr);
		$where_arr_cc = array('VENDOR_INVITE_ID' => $cc_vendor_code);
		$current_vt = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'TRADE_VENDOR_TYPE', $where_arr);
		$prev_vt = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'TRADE_VENDOR_TYPE', $where_arr_cc);
		$prev_registration_type_cc = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'PREV_REGISTRATION_TYPE', $where_arr_cc);
		$cc_vendor_name = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr_cc);
		
		if($registration_type == 5){
			$vendor_code_02 = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE_02', $where_arr_cc);
		}else{
			$vendor_code_02 = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE_02', $where_arr);
		}
		
		if($vendor_code_02 != ''){
			if($prev_registration_type_cc == 4){
				if($prev_vt != $current_vt){
					$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE', $where_arr_cc);		
				}else{
					$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE_02', $where_arr_cc);
				}
			}else{
				if($prev_vt != $current_vt){
					$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE_02', $where_arr_cc);	
				}else{
					$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE', $where_arr_cc);	
				}
			}
		}else{
			$old_vendor_code = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_CODE', $where_arr_cc);	
		}

		$data = [
					'prev_registration_type'	=> $prev_registration_type,
					'registration_type' 		=> $registration_type,
					'cc_vendor_code' 			=> $old_vendor_code,
					'cc_vendor_name' 			=> $cc_vendor_name,
					'current_status'			=> $current_status,
					'vendor_code_02'			=> $vendor_code_02
				];
		$this->response($data);
	}

	function message_info_get()
	{
		$action 		= $this->get('action');
		$next_position 	= $this->get('nxt_position_id');
		$status 		= $this->get('status');
		$vendor_id 		= $this->get('vendor_id');
		$user_id 		= $this->get('user_id');
		$reject_remarks = $this->get('reject_remarks');

		$recipient_id 	= '';
		$subject	 	= '';
		$topic	 		= '';
		$message	 	= '';

		if ($action == 1) // if approved get next approver ID
		{
			$where_arr = array(
								'POSITION_ID' => $next_position,
								'STATUS_ID' => $status
							);
			$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_APPROVAL_HIERARCHY', 'USER_ID', $where_arr);
		}
		elseif ($action == 0) // if rejected get ID of vendor
		{
			$where_arr = array(
								'VENDOR_ID' => $vendor_id
							);
			$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_ID', $where_arr);
		}
		
		
		$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

		$rs_msg = $this->common_model->get_message_default($where_arr_def);

		if ($rs_msg->num_rows() > 0)
		{
			$row = $rs_msg->row();

			$where_arr = array('VENDOR_ID' => $vendor_id);
			$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
			
			$subject	= str_replace('[vendorname]', $vendorname, $row->SUBJECT);
			$topic		= str_replace('[vendorname]', $vendorname, $row->TOPIC);
			$message	= $row->MESSAGE;
			
			if($action == 1){
				
				$where_arr 			= array('USER_ID' => $user_id);
				$sendername 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
				$sender_posid 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
				$sender_posname 	= $this->common_model->get_position_name($sender_posid);
				// $sendername 		= $sendername.'('.$sender_posname.')'; # remove position names

				$where_arr 			= array('USER_ID' => $recipient_id);
				$approvername 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
				$approver_posid 	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
				$approver_posname 	= $this->common_model->get_position_name($approver_posid);
				// $approvername 		= $approvername.' ('.$approver_posname.')'; # remove position names

				$message 	= str_replace('[sendername]', $sendername, $message);
				$message 	= str_replace('[approvername]', $approvername, $message);
				$message 	= str_replace('[vendorname]', $vendorname, $message);
			}else if($action == 0 || $action == 2){
				
				$where_arr 			= array('USER_ID' => $user_id);
				$approvername		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
				$sender_posid 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
				$sender_posname 	= $this->common_model->get_position_name($sender_posid);
				// $approvername 		= $approvername.'('.$sender_posname.')';# remove position names
				
				$where_arr 			= array('VENDOR_ID' => $vendor_id);
				$sender_id 			= $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr);
				
				$where_arr 			= array('VENDOR_INVITE_ID' => $sender_id);
				$sender_id 			= $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);
				
				$other_id = $this->get('other_id');
				if(!empty($other_id)){
					$sender_id = $other_id;
				}
				$where_arr			= array('USER_ID' => $sender_id);
				$sendername 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
				$sender_posid 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
				$sender_posname 	= $this->common_model->get_position_name($sender_posid);
				// $sendername 		= $sendername.'('.$sender_posname.')';# remove position names
				
				$recipient_id = $sender_id;
				
				if($action == 0){
					$message 	= str_replace('[requestor]', $sendername, $message);
					$message 	= str_replace('[approver_name]', $approvername, $message);
					$message 	= str_replace('[vendor_name]', $vendorname, $message);
					$message 	= str_replace('[reject_reason]', $reject_remarks, $message);
				}else if($action == 2){
					$message 	= str_replace('[sendername]', $sendername, $message);
					$message 	= str_replace('[approvername]', $approvername, $message);
					$message 	= str_replace('[vendorname]', $vendorname, $message);
					$message 	= str_replace('[remarks]', $reject_remarks, $message);
				}
			}
		}

		$data['recipient_id'] 	= $recipient_id;
		$data['subject'] 		= $subject;
		$data['topic'] 			= $topic;// .' - Rejected Vendor Invite Approval';
		$data['message'] 		= $message;
		
		$this->response($data);
	}

	function message_approvers_vendor_get()
	{
		$this->load->model('mail_model');
		$action 		= $this->get('action');
		$next_position 	= $this->get('nxt_position_id');
		$status 		= $this->get('status');
		$vendor_id 		= $this->get('vendor_id');
		$user_id 		= $this->get('user_id');
		$invite_id 		= $this->get('invite_id');
		$reject_remarks	= $this->get('reject_remarks');
		$user_position_id 	= $this->get('user_position_id');




		$recipient_id 	= '';
		$subject	 	= '';
		$topic	 		= '';
		$message	 	= '';
		$recipients = array();

		$var_data['positionname'] = '';

		if ($action == 1) // if approved get next approver ID
		{
			$where_arr = array(
								'POSITION_ID' => $next_position,
								'STATUS_ID' => $status
							);
			$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_APPROVAL_HIERARCHY', 'USER_ID', $where_arr);
		}
		elseif ($action == 0) // if rejected get ID of vendor
		{
			$where_arr = array(
								'VENDOR_ID' => $vendor_id
							);
			$recipient_id = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_ID', $where_arr);
		}

		$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

		$var_data['invite_id'] = $invite_id;
		$var_data['reject_remarks'] = $reject_remarks;

		$rs = $this->registration_model->approval_email_data($var_data);

		$message_data['TYPE'] = 'notification';
		$message_data['SENDER_ID'] = $user_id;
		$message_data['DATE_SENT'] = date('Y-m-d H:i:s');
		$message_data['VENDOR_ID'] = $vendor_id;
		$message_data['INVITE_ID'] = $invite_id;
		$message_data['RFQRFB_ID'] = $this->post('rfqrfb_id');

		$rs_msg = $this->common_model->get_message_default($where_arr_def);

		if ($rs_msg->num_rows() > 0)
		{
			$row = $rs_msg->row();

			$where_arr = array('VENDOR_ID' => $vendor_id);
			$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);

			$where_arr 			= array('USER_ID' => $user_id);
			$sendername 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
			$sender_posid 		= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_arr);
			$sender_posname 	= $this->common_model->get_position_name($sender_posid);
			// $sendername 		= $sendername.'('.$sender_posname.')';# remove position names

			$message_data['SUBJECT']	= str_replace('[vendorname]', $vendorname, $row->SUBJECT);
			$message_data['TOPIC']		= str_replace('[vendorname]', $vendorname, $row->TOPIC);

			//message for this employee
			$message	= $row->MESSAGE;

			if(!empty($reject_remarks))
			{
				$message = str_replace('[reject_reason]', $reject_remarks, $message); // first useronly / creator
			}
			$message 	= str_replace('[requestor]', $rs['creator_name'], $message); // first useronly / creator
			$message 	= str_replace('[approver_name]', $sendername, $message);
			$message 	= str_replace('[vendor_name]', $vendorname, $message);
			//end of message 

			$i = 1;
			//recipients and message
			$recipients = array();
			$recipients['recipients'][0] =  $rs['creator_id'];
			$recipients['messages'][0] = $message;

			if($user_position_id == 5)// vrdhead
			{
				if($rs['creator_position'] == 2)//senmer
				{
					if(!empty($rs['query_result']->row(0)->BUHEAD_ID) && $rs['query_result']->row(0)->BUHEAD_ID != $user_id)
					{
						$where_arr 			= array('USER_ID' => $rs['query_result']->row(0)->BUHEAD_ID);
						$destinationname	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
						
						//message for this employee
						$message	= $row->MESSAGE;

						if(!empty($reject_remarks))
						{
							$message = str_replace('[reject_reason]', $reject_remarks, $message); // first useronly / creator
						}
						$message 	= str_replace('[requestor]', $destinationname, $message); // first useronly / creator
						$message 	= str_replace('[approver_name]', $sendername, $message);
						$message 	= str_replace('[vendor_name]', $vendorname, $message);
						$recipients['messages'][$i] = $message;
						//end of message
						$recipients['recipients'][$i] =  $rs['query_result']->row(0)->BUHEAD_ID;
						$i++;
					}
				}
				else if($rs['creator_position'] == 7)//buyer
				{
					if(!empty($rs['query_result']->row(0)->GHEAD_ID) && $rs['query_result']->row(0)->GHEAD_ID != $user_id)
					{
						$where_arr 			= array('USER_ID' => $rs['query_result']->row(0)->GHEAD_ID);
						$destinationname	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
						
						//message for this employee
						$message	= $row->MESSAGE;

						if(!empty($reject_remarks))
						{
							$message = str_replace('[reject_reason]', $reject_remarks, $message); // first useronly / creator
						}
						$message 	= str_replace('[requestor]', $destinationname, $message); // first useronly / creator
						$message 	= str_replace('[approver_name]', $sendername, $message);
						$message 	= str_replace('[vendor_name]', $vendorname, $message);
						$recipients['messages'][$i] = $message;
						//end of message
						$recipients['recipients'][$i] = $rs['query_result']->row(0)->GHEAD_ID;
						$i++;
					}
				}
			}
			else if($user_position_id == 6)// uhats
			{
				if($rs['creator_position'] == 2)//senmer
				{
					if(!empty($rs['query_result']->row(0)->BUHEAD_ID) && $rs['query_result']->row(0)->BUHEAD_ID != $user_id)
					{
						$where_arr 			= array('USER_ID' => $rs['query_result']->row(0)->BUHEAD_ID);
						$destinationname	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
						
						//message for this employee
						$message	= $row->MESSAGE;

						if(!empty($reject_remarks))
						{
							$message = str_replace('[reject_reason]', $reject_remarks, $message); // first useronly / creator
						}
						$message 	= str_replace('[requestor]', $destinationname, $message); // first useronly / creator
						$message 	= str_replace('[approver_name]', $sendername, $message);
						$message 	= str_replace('[vendor_name]', $vendorname, $message);
						$recipients['messages'][$i] = $message;
						//end of message
						$recipients['recipients'][$i] = $rs['query_result']->row(0)->BUHEAD_ID;
						$i++;
					}
				}
				else if($rs['creator_position'] == 7)//buyer
				{
					if(!empty($rs['query_result']->row(0)->GHEAD_ID) && $rs['query_result']->row(0)->GHEAD_ID != $user_id)
					{
						$where_arr 			= array('USER_ID' => $rs['query_result']->row(0)->GHEAD_ID);
						$destinationname	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
						
						//message for this employee
						$message	= $row->MESSAGE;

						if(!empty($reject_remarks))
						{
							$message = str_replace('[reject_reason]', $reject_remarks, $message); // first useronly / creator
						}
						$message 	= str_replace('[requestor]', $destinationname, $message); // first useronly / creator
						$message 	= str_replace('[approver_name]', $sendername, $message);
						$message 	= str_replace('[vendor_name]', $vendorname, $message);
						$recipients['messages'][$i] = $message;
						//end of message
						$recipients['recipients'][$i] = $rs['query_result']->row(0)->GHEAD_ID;
						$i++;
					}
				}
				
				if(!empty($rs['query_result']->row(0)->VRDHEAD_ID) && $rs['query_result']->row(0)->VRDHEAD_ID != $user_id)
				{
					$where_arr 			= array('USER_ID' => $rs['query_result']->row(0)->VRDHEAD_ID);
					$destinationname	= $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
					
					//message for this employee
					$message	= $row->MESSAGE;

					if(!empty($reject_remarks))
					{
						$message = str_replace('[reject_reason]', $reject_remarks, $message); // first useronly / creator
					}
					$message 	= str_replace('[requestor]', $destinationname, $message); // first useronly / creator
					$message 	= str_replace('[approver_name]', $sendername, $message);
					$message 	= str_replace('[vendor_name]', $vendorname, $message);
					$recipients['messages'][$i] = $message;
					//end of message
					$recipients['recipients'][$i] = $rs['query_result']->row(0)->VRDHEAD_ID;
					$i++;
				}
			}

			// 	message for vendor
			
			$message	= $row->MESSAGE;
			if(!empty($reject_remarks))
			{
				$message = str_replace('[reject_reason]', $reject_remarks, $message); // first useronly / creator
			}
			$message 	= str_replace('[requestor]', $vendorname, $message); // first useronly / creator
			$message 	= str_replace('[approver_name]', $sendername, $message);
			$message 	= str_replace('[vendor_name]', $vendorname, $message);
			$recipients['messages'][$i] = $message;
			$recipients['recipients'][$i] 	= $recipient_id;
			//	end message for vendor
		}


		// if($action == 1){
		// 	$recipients->recipients = '2012';
		// 	//array_push($recipients['recipients'], 2012);
		// }

		$this->response(($status));

		$x = 0;
		foreach ($recipients['recipients'] as $recipient) {
			$message_data['BODY'] = urlencode($recipients['messages'][$x]);
			$message_data['RECIPIENT_ID'] = $recipient;
			$message_data['SENDER_ID'] = 0;
			$model_data = $this->mail_model->send_message($message_data);
			$x++;
		}

		
		
	}

	function email_approvers_vendor_get()
	{
		$user_id 		= $this->get('user_id');
		$invite_id 		= $this->get('invite_id');

    	$where_arr = array('VENDOR_INVITE_ID' => $invite_id);
		$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_NAME', $where_arr);

        $where_arr = array('VENDOR_INVITE_ID' => $invite_id);
		$creator_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);


		$recipient_array = array();
		$last_receiver = 0;

		$rs = $this->registration_model->get_usermatrix_data($creator_id);

		if($rs['total_rows'] > 0)
		{
			if($user_id != $rs['query']->row(0)->USER_ID) // senmer or buyer
			{
				if(!empty($rs['query']->row(0)->USER_ID))
					if($last_receiver == 0)
						array_push($recipient_array, $user_id);
			}else
				$last_receiver = 1;
			/*
			if($user_id != $rs['query']->row(0)->BUHEAD_ID) // buhead
			{
				if(!empty($rs['query']->row(0)->BUHEAD_ID))
					if($last_receiver == 0)
						array_push($recipient_array, $rs['query']->row(0)->BUHEAD_ID);
			}else
				$last_receiver = 1;


			if($user_id != $rs['query']->row(0)->GHEAD_ID) // ghead
			{
				if(!empty($rs['query']->row(0)->GHEAD_ID))
					if($last_receiver == 0)
						array_push($recipient_array, $rs['query']->row(0)->GHEAD_ID);
			}else
				$last_receiver = 1;
				
			if($user_id != $rs['query']->row(0)->VRDSTAFF_ID) //  vrdstaff
			{
				if(!empty($rs['query']->row(0)->VRDSTAFF_ID))
					if($last_receiver == 0)
						array_push($recipient_array, $rs['query']->row(0)->VRDSTAFF_ID);
			}else
				$last_receiver = 0;

				
			if($user_id != $rs['query']->row(0)->VRDHEAD_ID) // vrdhead
			{
				if(!empty($rs['query']->row(0)->VRDHEAD_ID))
					if($last_receiver == 0)
						array_push($recipient_array, $rs['query']->row(0)->VRDHEAD_ID);
			}else
				$last_receiver = 0;


			if($user_id != $rs['query']->row(0)->FASHEAD_ID) // fashead
			{
				if(!empty($rs['query']->row(0)->FASHEAD_ID))
					if($last_receiver == 0)
						array_push($recipient_array, $rs['query']->row(0)->FASHEAD_ID);
			}else
				$last_receiver = 0;
			*/
		}

		$reject_remarks = $this->put('reject_remarks');
		//print_r($recipient_array);
		if(sizeOf($recipient_array) > 0)
		{
			foreach($recipient_array as $id_row)
			{

				$where_arr = array('TEMPLATE_TYPE' => 34,
									'ACTIVE'	 => 1);
			    $email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

		    	$where_arr = array('USER_ID' => $id_row);
				$receiver = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_FIRST_NAME', $where_arr);
		    	$where_arr = array('USER_ID' => $user_id);
				$current_user = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_FIRST_NAME', $where_arr);

		    	$where_arr = array('USER_ID' => $id_row);
				$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

				//$email_template = str_replace('[receiver]', $receiver, $email_template);
				//$email_template = str_replace('[vendor_name]', $vendorname, $email_template);
				//$email_template = str_replace('[current_user]', $current_user, $email_template);
				
				$email_template = str_replace('[buyername]', $receiver, $email_template);
				$email_template = str_replace('[vendorname]', $vendorname, $email_template);
				$email_template = str_replace('[approvername]', $current_user, $email_template);
				$email_template = str_replace('[remarks]', $reject_remarks, $email_template);
				
				
				$email_data['subject'] = 'Vendor Approval';
				//$email_data['bcc'] = '';
				$email_data['content'] = nl2br($email_template);

				$email_data['to'] = $emailaddress;
				$this->common_model->send_email_notification($email_data);

			}

		}


		$this->response($recipient_array);


	}

	function invite_id_get()
	{
		$vendor_id = $this->get('vendor_id');

		$where_arr 	= array('VENDOR_ID' => $vendor_id);
		$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr);

		$this->response($invite_id);
	}

	function default_values_get()
	{
		$where_arr = array('CONFIG_NAME' => 'max_brand_allowed');
		$data['max_brand'] = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

		$where_arr = array('CONFIG_NAME' => 'max_opd_allowed');
		$data['max_opd'] = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

		$where_arr = array('CONFIG_NAME' => 'max_authrep_allowed');
		$data['max_authrep'] = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

		$where_arr = array('CONFIG_NAME' => 'max_bank_rep_allowed');
		$data['max_bank_rep'] = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

		$where_arr = array('CONFIG_NAME' => 'max_orcc_allowed');
		$data['max_orcc'] = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

		$where_arr = array('CONFIG_NAME' => 'max_other_business_allowed');
		$data['max_ob'] = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

		$where_arr = array('CONFIG_NAME' => 'max_relatives_allowed');
		$data['max_rel'] = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

		$this->response($data);
	}

	function show_tooltip_get()
	{
		$label = $this->get('label');

		$where_arr = array('ELEMENT_LABEL' => $label);
		$data['tooltip'] = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_TOOLTIPS', 'TOOLTIP', $where_arr);

		$this->response($data);
	}

	function pass_additional_requirements($var)
	{
		$docs_var['ownership'] 			= $this->post('ownership');
		$docs_var['trade_vendor_type'] 	= $this->post('trade_vendor_type');
		$docs_var['vendor_type'] 		= $this->post('vendor_type');
		$docs_var['registration_type'] 		= $this->post('registration_type');
		$category_sup_count = $this->post('cat_sup_count');
		
		$cat_array = '';
		for($i = 1; $i <= $category_sup_count; $i++){
			if(!($categoryid = $this->post('category_id' . $i))) {
				continue;
			}
			$cat_array .= $categoryid . ',';
		}
		
		$docs_var['category_id']		= explode(',', rtrim($cat_array, ','));
		$docs_var['invite_id'] 			= $this->post('invite_id');

		// save additional requirements
		$ra_upload_count	= $this->post('ra_upload_count');
		$ra = $this->registration_model->get_ra_docs($docs_var);

		$action_type = 1;


		$vendor_statuses = array(11,12,18,19,194,195,192); 

		if(!empty($var['real_status'])){ //action type = 0 save as draft;
				if($var['real_status'] > 9 && !in_array($var['real_status'],$vendor_statuses) && $var['status_id'] == 1){
					$action_type = 0;
				}else{
					$action_type = 1;	
			}
		}

		$ra_batch = array();
		$error_output =  array();
		for ($i=1; $i <= count($ra); $i++)
		{ 
			$to_insert = 1;
			$ra_document_chk 		= $this->post('ra_document_chk'.$i); // get id
			$ra_date_upload 		= $this->post('ra_date_upload'.$i);
			$ra_orig_name 			= $this->post('ra_orig_name'.$i);
			$btn_ra_preview 		= $this->post('btn_ra_preview'.$i); // file path

			//if ($ra_date_upload != '')
			//{
			//	$ra_date_upload = DateTime::createFromFormat('m/d/Y h:i:s A', $ra_date_upload);
			//	$ra_date_upload = $ra_date_upload->format('Y-m-d H:i:s');
			//}
			
			//if ($var['registration_type'] == 3 || $var['registration_type'] == 4 || $var['real_status'] == 195 || $var['real_status'] == 190){ // add vendor
			//if ($var['registration_type'] == 3 || $var['registration_type'] == 4 || $var['real_status'] == 195){ // add vendor
				$whr = array(
						'DOC_TYPE_ID' => $ra_document_chk,
						'VENDOR_ID' => $var['vendor_id']
					);
				
				$db_record = $this->common_model->select_query('SMNTP_VENDOR_AGREEMENTS',$whr, 'DATE_CREATED,FILE_PATH,ORIGINAL_FILENAME');	
				
				if(count($db_record) > 0){
					if($db_record[0]['ORIGINAL_FILENAME'] == $ra_orig_name){
						if($ra_date_upload == $db_record[0]['DATE_CREATED']){
							$to_insert = 0;
						}
					}
				}
			//}

			if($to_insert == 1){
				if (!empty($ra_document_chk))
				{
					$is_file_exists = stripos(@get_headers(@$var['web_site_url'].$btn_ra_preview)[0],"200 OK") ? true : false;
					if( ! $is_file_exists){
						$doc_row = $this->common_model->select_query_active('REQUIRED_AGREEMENT_NAME', 'SMNTP_VP_REQUIRED_AGREEMENTS',array('REQUIRED_AGREEMENT_ID' => $ra_document_chk))->row_array();
						
						$doc_name = 'error';
						
						if(!empty($doc_row)){
							$error_output[] = $doc_row['REQUIRED_AGREEMENT_NAME'];
							log_message('error' , 'File does not exists ' .  $doc_row['REQUIRED_AGREEMENT_NAME']);
						}else{
							$error_output[] = $doc_name;
						}
						
						continue;
					}
					
					$ra_batch[] = array(
									'VENDOR_ID' 		=> $var['vendor_id'],
									'DOC_TYPE_ID' 		=> $ra_document_chk,
									'FILE_PATH' 		=> $btn_ra_preview,
									'DATE_CREATED' 		=> $ra_date_upload,//'TO_DATE('.$ra_date_upload.', \'MM/DD/YYYY HH12:MI AM\')',
									'ORIGINAL_FILENAME' => $ra_orig_name,
									'DOC_STATUS'		=> $action_type
								);
								
					if($docs_var['registration_type'] != 4){
						$this->common_model->delete_table('SMNTP_VENDOR_AGREEMENTS', array(
							'VENDOR_ID' => $var['vendor_id'],
							'DOC_TYPE_ID' => $ra_document_chk
						));
					}else{
						$this->registration_model->delete_table('SMNTP_VENDOR_AVC_A', array(
							'VENDOR_ID' => $var['vendor_id'],
							'DOC_TYPE_ID' => $ra_document_chk
						));
					}
				}
			}
			
			if($action_type == 1){
					$record = array(
						'DOC_STATUS' 	=> 1 // after accepting dpa in process na
							);
						
					$where = array(
							'VENDOR_ID' => $var['vendor_id']
						);
						
				if($docs_var['registration_type'] != 4){
					$this->common_model->update_table('SMNTP_VENDOR_AGREEMENTS',$record, $where);
				}else{
					$this->common_model->update_table('SMNTP_VENDOR_AVC_A',$record, $where);
				}
			}
		}

		if (!empty($ra_batch)){
			if($docs_var['registration_type'] != 4){
				$this->common_model->insert_table_batch('SMNTP_VENDOR_AGREEMENTS', $ra_batch);
			}else{
				$this->common_model->insert_table_batch('SMNTP_VENDOR_AVC_A', $ra_batch);
			}
		}		
		
		return $error_output;
	}

	function get_vendor_invite_id_get()
	{
		$vendor_id = $this->get('vendor_id');

		$where_arr = array('VENDOR_ID' => $vendor_id);
		$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr);

		$this->response($invite_id);
	}

	function get_vendor_status_get()
	{
		$user_id = $this->get('user_id');

		$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'VENDOR_INVITE_ID', ['USER_ID' => $user_id]);

		$status_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_STATUS', 'STATUS_ID', ['VENDOR_INVITE_ID' => $invite_id]);

		$vendor_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_ID', ['VENDOR_INVITE_ID' => $invite_id]);
		// if additional req is alread uploaded update again and pass status == $upload_complete = true/ false pag ndi pa complete
		$upload_complete = $this->registration_model->check_additional_requirements_upload($vendor_id); 

		$trade_vendor_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'TRADE_VENDOR_TYPE', ['VENDOR_INVITE_ID' => $invite_id]);
		
		$business_type = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'BUSINESS_TYPE', ['VENDOR_INVITE_ID' => $invite_id]);
		
		$data = [
					'invite_id' 		=> $invite_id,
					//'vendor_id' 		=> $vendor_id,
					'status_id' 		=> $status_id,
					'business_type'		=> $business_type,
					'trade_vendor_type'	=> $trade_vendor_type,
					'upload_complete' 	=> $upload_complete
				];

		$this->response($data);
	}

function message_hats_get()
{

	$this->load->model('mail_model');
	$data['notification'] = "notification";
	$senderid['USER_ID'] = $this->get('user_id');

	$whr = array('POSITION_ID' => 6);

	$hats = $this->common_model->select_query('SMNTP_USERS',$whr,'USER_ID');



	$status = $this->get('status');
	$where_arr = array('VENDOR_ID' =>  $this->get('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
	$data['SUBJECT'] = $vendorname;
	$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	$where_arr 			= array('USER_ID' => $hats[0]['USER_ID']);
	$approver = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
	$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');
	
	$rs_msg[0]['SUBJECT'] = str_replace("[vendorname]", $vendorname, $rs_msg[0]['SUBJECT']);
	$rs_msg[0]['TOPIC'] = str_replace("[vendorname]", $vendorname, $rs_msg[0]['TOPIC']);
	$rs_msg[0]['MESSAGE'] = str_replace("[approvername]", $approver, $rs_msg[0]['MESSAGE']);
	$rs_msg[0]['MESSAGE'] = str_replace("[sendername]", $sender, $rs_msg[0]['MESSAGE']);
	$rs_msg[0]['MESSAGE'] = str_replace("[vendorname]", $vendorname, $rs_msg[0]['MESSAGE']);
	$rs_msg[0]['DATE_SENT'] = date('Y-m-d H:i:s');


	$insert_array = array(
			'SUBJECT' => $rs_msg[0]['SUBJECT'],
			'TOPIC' => $rs_msg[0]['TOPIC'],
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $rs_msg[0]['MESSAGE'],
			'TYPE' => 'notification',
			'SENDER_ID' => 0,//notif
			'RECIPIENT_ID' => $hats[0]['USER_ID'], //can be changed in query
			'INVITE_ID' =>$this->get('invite_id'),
			'VENDOR_ID' => $this->get('vendor_id')
		);

	$model_data = $this->mail_model->send_message($insert_array);


	$where_arr = array('TEMPLATE_TYPE' => 32,
	'ACTIVE'	 => 1);
	$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
	$email_header = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'TEMPLATE_HEADER', $where_arr);

	$where_arr = array('USER_ID' =>$hats[0]['USER_ID']);
	$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

	$email_template = str_replace('[approvername]', $approver, $email_template);
	$email_template = str_replace('[sendername]', $sender, $email_template);
	$email_template = str_replace('[vendorname]', $vendorname, $email_template);

	$email_header = str_replace('[vendorname]', $vendorname, $email_header);

	$email_data['subject'] = $email_header;
	//$email_data['bcc'] = '';
	$email_data['content'] = nl2br($email_template);

	$email_data['to'] = $emailaddress;

	$this->common_model->send_email_notification($email_data);

	
}


function message_buyer_get()
{
//begin message -> portal !email
	$this->load->model('mail_model');
	$data['notification'] = "notification";
	$senderid['USER_ID'] = $this->get('user_id');


	$whr = array('VENDOR_INVITE_ID' => $this->get('invite_id'));

	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');



	$status = $this->get('status');


	$where_arr = array('VENDOR_ID' =>  $this->get('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
	$data['SUBJECT'] = $vendorname;
	$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	$where_arr 			= array('USER_ID' => $bu[0]['CREATED_BY']);
	$buyer = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');

	$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	
	$rs_msg[0]['SUBJECT'] = str_replace("[vendorname]", $vendorname, $rs_msg[0]['SUBJECT']);
	$rs_msg[0]['TOPIC'] = str_replace("[vendorname]", $vendorname, $rs_msg[0]['TOPIC']);
	$rs_msg[0]['MESSAGE'] = str_replace("[buyername]", $buyer, $rs_msg[0]['MESSAGE']);
	$rs_msg[0]['MESSAGE'] = str_replace("[approvername]", $sender, $rs_msg[0]['MESSAGE']);
	$rs_msg[0]['MESSAGE'] = str_replace("[vendorname]", $vendorname, $rs_msg[0]['MESSAGE']);
	$rs_msg[0]['DATE_SENT'] = date('Y-m-d H:i:s');
	$insert_array = array(
			'SUBJECT' => $rs_msg[0]['SUBJECT'],
			'TOPIC' => $rs_msg[0]['TOPIC'],
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $rs_msg[0]['MESSAGE'],
			'TYPE' => 'notification',
			'SENDER_ID' => $senderid['USER_ID'],
			'RECIPIENT_ID' => $bu[0]['CREATED_BY'], //can be changed in query
			'INVITE_ID' =>$this->get('invite_id'),
			'VENDOR_ID' => $this->get('vendor_id')
		);

	$model_data = $this->mail_model->send_message($insert_array);
	//end message -> portal !email

	//begin message -> !portal email


			$where_arr = array('TEMPLATE_TYPE' => 60,
			'ACTIVE'	 => 1);
			$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
			$email_header = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'TEMPLATE_HEADER', $where_arr);

			$where_arr = array('USER_ID' => $bu[0]['CREATED_BY']);
			$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

			$email_template = str_replace('[buyername]', $buyer, $email_template);
			$email_template = str_replace('[vendorname]', $vendorname, $email_template);
			$email_template = str_replace('[approvername]', $sender, $email_template);
			$email_header   = str_replace('[vendorname]', $vendorname, $email_header);

			$email_data['subject'] = $email_header;
			//$email_data['bcc'] = '';
			$email_data['content'] = nl2br($email_template);

			$email_data['to'] = $emailaddress;
			$this->common_model->send_email_notification($email_data);

			//end message -> !portal email


	$this->response($insert_array);
}




function message_buyer_failed_put()
{
	$senderid['USER_ID'] = $this->put('user_id');
	$reject_remarks = $this->put('reject_remarks');
	$status = $this->put('status');
	$action = $this->put('action');
	
	$topic = $this->put('topic');
	if(empty($topic)){
		$topic = 'Vendor Approval';
	}
	

	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));

	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');


	$where_arr = array('VENDOR_ID' =>  $this->put('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
	$data['SUBJECT'] = $vendorname;
	$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	$where_arr 			= array('USER_ID' => $bu[0]['CREATED_BY']);
	$buyer = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');

	$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	

	if($status == 18 && $action == 0){
		$where_arr = array('TEMPLATE_TYPE' => 34,
		'ACTIVE'	 => 1);
	}else{
		$where_arr = array('TEMPLATE_TYPE' => 30,
		'ACTIVE'	 => 1);
	}
	$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

	$where_arr = array('USER_ID' => $bu[0]['CREATED_BY']);
	$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

	$email_template = str_replace('[buyername]', $buyer, $email_template);
	$email_template = str_replace('[vendorname]', $vendorname, $email_template);
	$email_template = str_replace('[approvername]', $sender, $email_template);
	$email_template = str_replace('[remarks]', $reject_remarks, $email_template);

	$email_data['subject'] = $vendorname . ' - Vendor Registration Approval - Rejected';
	//$email_data['bcc'] = 'marcanthonypacres@yahoo.com';
	$email_data['content'] = nl2br($email_template);

	$email_data['to'] = $emailaddress;
	$this->common_model->send_email_notification($email_data);

	$this->response($email_data);

}



function message_buhead_failed_put()
{
	$senderid['USER_ID'] = $this->put('user_id');
	$reject_remarks = $this->put('reject_remarks');
	$status = $this->put('status');
	$action = $this->put('action');
	
	$topic = $this->put('topic');
	if(empty($topic)){
		$topic = 'Vendor Approval';
	}
	

	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));

	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');


	$where_arr = array('VENDOR_ID' =>  $this->put('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
	$data['SUBJECT'] = $vendorname;
	$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	//$where_arr 			= array('USER_ID' => $bu[0]['CREATED_BY']);
	//$userid = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', 'BUHEAD_ID', $where_arr);
	$userids = $this->common_model->select_query_wherein('SMNTP_USERS_MATRIX', 'USER_ID', array($bu[0]['CREATED_BY']), 'BUHEAD_ID');
	//$this->response($this->db->last_query());
	$array_buhead = array();
	foreach($userids as $userid){
		if(!empty($userid['BUHEAD_ID'])){
			if( ! in_array($userid['BUHEAD_ID'], $array_buhead)){
				$array_buhead[] = $userid['BUHEAD_ID'];
				$where_arr 			= array('USER_ID' => $userid['BUHEAD_ID']);
				$buhead = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');

				$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	

				$where_arr = array('TEMPLATE_TYPE' => 34,
					'ACTIVE'	 => 1);
					
				$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

				$where_arr = array('USER_ID' => $userid['BUHEAD_ID']);
				$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

				$email_template = str_replace('[buyername]', $buhead, $email_template);
				$email_template = str_replace('[vendorname]', $vendorname, $email_template);
				$email_template = str_replace('[approvername]', $sender, $email_template);
				$email_template = str_replace('[remarks]', $reject_remarks, $email_template);
				
				$email_data['subject'] = $vendorname . ' - Vendor Registration Approval - Rejected';
				//$email_data['bcc'] = '';
				$email_data['content'] = nl2br($email_template);

				$email_data['to'] = $emailaddress;
				$this->common_model->send_email_notification($email_data);
			
			}
		}
	}

	$this->response($email_data);

}


function message_creator_failed_put()
{
	$senderid['USER_ID'] = $this->put('user_id');
	$reject_remarks = $this->put('reject_remarks');
	$status = $this->put('status');
	$action = $this->put('action');
	
	$topic = $this->put('topic');
	if(empty($topic)){
		$topic = 'Vendor Approval';
	}
	

	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));

	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');


	$where_arr = array('VENDOR_ID' =>  $this->put('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
	$data['SUBJECT'] = $vendorname;
	$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	$where_arr 			= array('USER_ID' => $bu[0]['CREATED_BY']);
	$buhead = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');

	$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	

	$where_arr = array('TEMPLATE_TYPE' => 34,
		'ACTIVE'	 => 1);
		
	$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

	$where_arr = array('USER_ID' => $bu[0]['CREATED_BY']);
	$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

	$email_template = str_replace('[buyername]', $buhead, $email_template);
	$email_template = str_replace('[vendorname]', $vendorname, $email_template);
	$email_template = str_replace('[approvername]', $sender, $email_template);
	$email_template = str_replace('[remarks]', $reject_remarks, $email_template);
	
	$email_data['subject'] = $vendorname . ' - Vendor Registration Approval - Rejected';
	//$email_data['bcc'] = '';
	$email_data['content'] = nl2br($email_template);

	$email_data['to'] = $emailaddress;
	$this->common_model->send_email_notification($email_data);

	$this->response($email_data);

}

function message_ghead_failed_put()
{
	$senderid['USER_ID'] = $this->put('user_id');
	$reject_remarks = $this->put('reject_remarks');
	$status = $this->put('status');
	$action = $this->put('action');
	
	$topic = $this->put('topic');
	if(empty($topic)){
		$topic = 'Vendor Approval';
	}
	

	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));

	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');


	$where_arr = array('VENDOR_ID' =>  $this->put('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
	$data['SUBJECT'] = $vendorname;
	$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	//$where_arr 			= array('USER_ID' => $bu[0]['CREATED_BY']);
	//$userid = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', 'GHEAD_ID', $where_arr);
	$userids = $this->common_model->select_query_wherein('SMNTP_USERS_MATRIX', 'USER_ID', array($bu[0]['CREATED_BY']), 'GHEAD_ID');
	//$this->response($this->db->last_query());
	$array_ghead = array();
	foreach($userids as $userid){
		if(!empty($userid['GHEAD_ID'])){
			if( ! in_array($userid['GHEAD_ID'], $array_ghead)){
				$array_ghead[] = $userid['GHEAD_ID'];
				$where_arr 			= array('USER_ID' => $userid['GHEAD_ID']);
				$buhead = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');

				$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	

				$where_arr = array('TEMPLATE_TYPE' => 34,
					'ACTIVE'	 => 1);
					
				$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

				$where_arr = array('USER_ID' => $userid['GHEAD_ID']);
				$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

				$email_template = str_replace('[buyername]', $buhead, $email_template);
				$email_template = str_replace('[vendorname]', $vendorname, $email_template);
				$email_template = str_replace('[approvername]', $sender, $email_template);
				$email_template = str_replace('[remarks]', $reject_remarks, $email_template);
				
				$email_data['subject'] = $vendorname . ' - Vendor Registration Approval - Rejected';
				//$email_data['bcc'] = '';
				$email_data['content'] = nl2br($email_template);

				$email_data['to'] = $emailaddress;
				$this->common_model->send_email_notification($email_data);
			}
		}
	}

	$this->response($email_data);

}

function message_fashead_failed_put()
{
	$senderid['USER_ID'] = $this->put('user_id');
	$reject_remarks = $this->put('reject_remarks');
	$status = $this->put('status');
	$action = $this->put('action');
	
	$topic = $this->put('topic');
	if(empty($topic)){
		$topic = 'Vendor Approval';
	}
	

	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));

	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');


	$where_arr = array('VENDOR_ID' =>  $this->put('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
	$data['SUBJECT'] = $vendorname;
	$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	//$where_arr 			= array('USER_ID' => $bu[0]['CREATED_BY']);
	//$userid = $this->common_model->get_from_table_where_array('SMNTP_USERS_MATRIX', 'FASHEAD_ID', $where_arr);
	
	$userids = $this->common_model->select_query_wherein('SMNTP_USERS_MATRIX', 'USER_ID', array($bu[0]['CREATED_BY']), 'FASHEAD_ID');
	$array_fashead = array();
	foreach($userids as $userid){
		if(!empty($userid['FASHEAD_ID'])){
			
			//if fashead sender id is equal to user matrix fashead then do not send
			if($senderid['USER_ID'] == $userid['FASHEAD_ID']){
				continue;
			}
			if( ! in_array($userid['FASHEAD_ID'], $array_fashead)){
				$array_fashead[] = $userid['FASHEAD_ID'];
				$where_arr 			= array('USER_ID' => $userid['FASHEAD_ID']);
				$fashead = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');

				$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	

				$where_arr = array('TEMPLATE_TYPE' => 34,
					'ACTIVE'	 => 1);
					
				$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

				$where_arr = array('USER_ID' => $userid['FASHEAD_ID']);
				$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

				$email_template = str_replace('[buyername]', $fashead, $email_template);
				$email_template = str_replace('[vendorname]', $vendorname, $email_template);
				$email_template = str_replace('[approvername]', $sender, $email_template);
				$email_template = str_replace('[remarks]', $reject_remarks, $email_template);
				
				$email_data['subject'] = $vendorname . ' - Vendor Registration Approval - Rejected';
				//$email_data['bcc'] = '';
				$email_data['content'] = nl2br($email_template);

				$email_data['to'] = $emailaddress;
				$this->common_model->send_email_notification($email_data);
			}
		}
	}

	$this->response($email_data);

}

function message_vrdhead_failed_put()
{
	$senderid['USER_ID'] = $this->put('user_id');
	$reject_remarks = $this->put('reject_remarks');
	$status = $this->put('status');
	$action = $this->put('action');
	$user_id = $this->put('user_id');
	$response = array();
	
	$topic = $this->put('topic');
	if(empty($topic)){
		$topic = 'Vendor Approval';
	}
	

	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));

	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');


	$where_arr = array('VENDOR_ID' =>  $this->put('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
	$data['SUBJECT'] = $vendorname;
	$where_arr_def = array(
								'TYPE_ID' 		=> 1, // for registration
								'STATUS_ID' 	=> $status
							);

	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	//$where_arr 			= array('USER_ID' => $bu[0]['CREATED_BY']);
	$vrdheads = $this->common_model->select_query_wherein('SMNTP_USERS_MATRIX', 'USER_ID', array($bu[0]['CREATED_BY']), 'VRDHEAD_ID');
	$array_vrdhead = array();
	$array_vrdhead[] = $user_id;
	foreach($vrdheads as $vrd){
		if(!empty($vrd['VRDHEAD_ID'])){
			if( ! in_array($vrd['VRDHEAD_ID'], $array_vrdhead)){
				$array_vrdhead[] = $vrd['VRDHEAD_ID'];
				$where_arr 			= array('USER_ID' => $vrd['VRDHEAD_ID']);
				$vrdhead = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');

				$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	

				$where_arr = array('TEMPLATE_TYPE' => 34,
					'ACTIVE'	 => 1);
				$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);
				$email_header = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'TEMPLATE_HEADER', $where_arr);
				/*$this->response($email_template);*/

				$where_arr = array('USER_ID' => $vrd['VRDHEAD_ID']);
				$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

				$email_template = str_replace('[buyername]', $vrdhead, $email_template);
				$email_template = str_replace('[vendorname]', $vendorname, $email_template);
				$email_template = str_replace('[approvername]', $sender, $email_template);
				$email_template = str_replace('[remarks]', $reject_remarks, $email_template);

				$email_header = str_replace('[vendorname]', $vendorname, $email_header);

				$email_data['subject'] = $email_header;
				//$email_data['bcc'] = '';
				$email_data['content'] = nl2br($email_template);

				$email_data['to'] = $emailaddress;
				$this->common_model->send_email_notification($email_data);


				
			}
		}
	}
	
	$response['email_data'] = $email_data;
	$response['array_vrdhead'] = $array_vrdhead;
	$response['vrdheads'] = $vrdheads;

	$this->response($response);

}

function message_vrdstaff_put()
{
//begin message -> portal !email
	$this->load->model('mail_model');
	$data['notification'] = "notification";
	$senderid['USER_ID'] = $this->put('user_id');


	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));

	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');

	$status = $this->put('status');



	//get vrdstaff


	$where = array('USER_ID' => $bu[0]['CREATED_BY']);
	$vrdstaff = $this->common_model->select_query('SMNTP_USERS_MATRIX',$where,'VRDSTAFF_ID');

	for($i = 0 ; $i<count($vrdstaff);$i++){
		
		if(!empty($vrdstaff[$i]['VRDSTAFF_ID'])){
			$vrdstaff_name = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vrdstaff[$i]['VRDSTAFF_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');



			if(!isset($vrdstaff_name[0]['USER_FIRST_NAME'])){
				$vrdstaff_name[0]['USER_FIRST_NAME'] = "";

			}
			if(!isset($vrdstaff_name[0]['USER_MIDDLE_NAME'])){

				$vrdstaff_name[0]['USER_MIDDLE_NAME'] = "";
			}
			if(!isset($vrdstaff_name[0]['USER_LAST_NAME'])){
				$vrdstaff_name[0]['USER_LAST_NAME'] = "";
				
			}

			$vtemp	 = trim($vrdstaff_name[0]['USER_FIRST_NAME'] ." " .$vrdstaff_name[0]['USER_MIDDLE_NAME'] ." " .$vrdstaff_name[0]['USER_LAST_NAME']);
			//$this->response($vtemp);



			$where_arr = array('VENDOR_ID' =>  $this->put('vendor_id'));
			$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);
			$data['SUBJECT'] = $vendorname;
			$where_arr_def = array(
										'TYPE_ID' 		=> 1, // for registration
										'STATUS_ID' 	=> 199
									);

			$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
			
			$where_arr 			= array('USER_ID' => $bu[0]['CREATED_BY']);
			$buyer = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');

			$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	
			$rs_msg[0]['SUBJECT'] = str_replace("[vendorname]", $vendorname, $rs_msg[0]['SUBJECT']);
			$rs_msg[0]['TOPIC'] = str_replace("[vendorname]", $vendorname, $rs_msg[0]['TOPIC']);
			$rs_msg[0]['MESSAGE'] = str_replace("[approver]",$vtemp, $rs_msg[0]['MESSAGE']);
			$rs_msg[0]['MESSAGE'] = str_replace("[sender]", $sender, $rs_msg[0]['MESSAGE']);
			$rs_msg[0]['MESSAGE'] = str_replace("[vendor]", $vendorname, $rs_msg[0]['MESSAGE']);
			$rs_msg[0]['DATE_SENT'] = date('Y-m-d H:i:s');
			$insert_array = array(
					'SUBJECT' => $rs_msg[0]['SUBJECT'],
					'TOPIC' => $rs_msg[0]['TOPIC'],
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $rs_msg[0]['MESSAGE'],
					'TYPE' => 'notification',
					'SENDER_ID' =>0,//notif
					'RECIPIENT_ID' => $vrdstaff[$i]['VRDSTAFF_ID'], //can be changed in query
					'INVITE_ID' =>$this->put('invite_id'),
					'VENDOR_ID' => $this->put('vendor_id')
				);

			//	$this->response($email_template);

			$model_data = $this->mail_model->send_message($insert_array);
			//end message -> portal !email

			//begin message -> !portal email


			$where_arr = array('TEMPLATE_TYPE' => 31,
			'ACTIVE'	 => 1);
			$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

			$where_arr = array('USER_ID' =>$vrdstaff[$i]['VRDSTAFF_ID']);
			$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

			$email_template = str_replace('[vrdstaff]', $vtemp, $email_template);
			$email_template = str_replace('[vendorname]', $vendorname, $email_template);
			$email_template = str_replace('[hats]', $sender, $email_template);

			$email_data['subject'] = $vendorname . ' - ' . 'Vendor Code Assignment';
			//$email_data['bcc'] = '';
			$email_data['content'] = nl2br($email_template);

			$email_data['to'] = $emailaddress;
			$this->common_model->send_email_notification($email_data);


			//$this->response($vrdstaff);
		}
		
	}

	//end message -> !portal email


	$this->response($insert_array);
}

function message_vrdstaff_two_put(){
	//begin message -> portal !email
	$this->load->model('mail_model');
	$data['notification'] = "notification";
	$senderid['USER_ID'] = $this->put('user_id');
	$sender = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $senderid, 'FULLNAME');	

	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));
	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');
	
	$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));
	$invite_type = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'REGISTRATION_TYPE');

	//get vrdstaff
	$where = array('USER_ID' => $bu[0]['CREATED_BY']);
	$vrdstaff = $this->common_model->select_query('SMNTP_USERS_MATRIX',$where,'VRDSTAFF_ID');
	
	$where_arr = array('VENDOR_ID' =>  $this->put('vendor_id'));
	$vendorname = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_NAME', $where_arr);

	for($i = 0 ; $i<count($vrdstaff);$i++){
		
		if(!empty($vrdstaff[$i]['VRDSTAFF_ID'])){
			$vrdstaff_name = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vrdstaff[$i]['VRDSTAFF_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');



			if(!isset($vrdstaff_name[0]['USER_FIRST_NAME'])){
				$vrdstaff_name[0]['USER_FIRST_NAME'] = "";

			}
			if(!isset($vrdstaff_name[0]['USER_MIDDLE_NAME'])){

				$vrdstaff_name[0]['USER_MIDDLE_NAME'] = "";
			}
			if(!isset($vrdstaff_name[0]['USER_LAST_NAME'])){
				$vrdstaff_name[0]['USER_LAST_NAME'] = "";
				
			}

			$vtemp	 = trim($vrdstaff_name[0]['USER_FIRST_NAME'] ." " .$vrdstaff_name[0]['USER_MIDDLE_NAME'] ." " .$vrdstaff_name[0]['USER_LAST_NAME']);

			$where_arr = array('TEMPLATE_TYPE' => 74,
			'ACTIVE'	 => 1);
			$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

			$where_arr = array('USER_ID' =>$vrdstaff[$i]['VRDSTAFF_ID']);
			$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

			$email_template = str_replace('[vrdstaff]', $vtemp, $email_template);
			$email_template = str_replace('[vendorname]', $vendorname, $email_template);
			$email_template = str_replace('[vrdhead]', $sender, $email_template);

			$email_data['subject'] = $vendorname . ' - ' . 'Schedule Request for Visit';
			//$email_data['bcc'] = '';
			$email_data['content'] = nl2br($email_template);

			$email_data['to'] = $emailaddress;
			$this->common_model->send_email_notification($email_data);


			//$this->response($vrdstaff);
		}
		
	}

	//end message -> !portal email


	//$this->response($insert_array);
}



function message_vrdhead_put()
{
	//begin message -> portal !email


	// $this->response($this->put('status'));

	$vtemp = array();
	$vrdtemp = array();
	$this->load->model('mail_model');
	$data['notification'] = "notification";
	$status = $this->put('status');

	$where_arr = array('TEMPLATE_TYPE' => 32,
			'ACTIVE'	 => 1);

	$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

	$where_arr_vrdstaff = array('TEMPLATE_TYPE' => 72,
			'ACTIVE'	 => 1);

	$email_template_vrdstaff = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr_vrdstaff);

	$sender = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $this->put('user_id')),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

	if(!isset($sender[0]['USER_FIRST_NAME'])){
		$sender[0]['USER_FIRST_NAME'] = "";

	}
	if(!isset($sender[0]['USER_MIDDLE_NAME'])){
		$sender[0]['USER_MIDDLE_NAME'] = "";
	}
	if(!isset($sender[0]['USER_LAST_NAME'])){
		$sender[0]['USER_LAST_NAME'] = "";
	}


	$sendername = trim($sender[0]['USER_FIRST_NAME'] ." ". $sender[0]['USER_MIDDLE_NAME'] ." ". $sender[0]['USER_LAST_NAME']);



	$vndinvid = $this->put('invite_id');
	$vndName = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $vndinvid),'USER_ID');
	$invite_type = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $vndinvid),'REGISTRATION_TYPE');

	$vndName = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vndName[0]['USER_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

	if(!isset($vndName[0]['USER_FIRST_NAME'])){
		$vndName[0]['USER_FIRST_NAME'] = "";

	}
	if(!isset($vndName[0]['USER_MIDDLE_NAME'])){

		$vndName[0]['USER_MIDDLE_NAME'] = "";
	}
	if(!isset($vndName[0]['USER_LAST_NAME'])){
		$vndName[0]['USER_LAST_NAME'] = "";
		
	}

	$vendorname = trim($vndName[0]['USER_FIRST_NAME'] ." ". $vndName[0]['USER_MIDDLE_NAME'] ." ". $vndName[0]['USER_LAST_NAME']);
	
	// Added MSF - 20191126 (IJR-10619)
	// Get Vendor's Category & Sub-Category
	
	if($invite_type[0]['REGISTRATION_TYPE'] != 4){
		$vendor_cat = $this->common_model->get_category($vndinvid);
	}else{
		$vendor_cat = $this->common_model->get_avc_category($vndinvid);
	}
	
	$cat = "";
	if($vendor_cat['resultcount'] > 0){
		$cat = "(";
		for($x=0; $x<$vendor_cat['resultcount']; $x++){
			if($x == 0){
				// With Sub Dept
				//$cat = "(".$vendor_cat['query'][$x]['CATEGORY_NAME']." - ".$vendor_cat['query'][$x]['SUB_CATEGORY_NAME'].")";
				$cat .= $vendor_cat['query'][$x]['CATEGORY_NAME'];

			}else{
				
				// With Sub Dept
				//$cat .= ", (".$vendor_cat['query'][$x]['CATEGORY_NAME']." - ".$vendor_cat['query'][$x]['SUB_CATEGORY_NAME'].")";
				$cat .= ", ".$vendor_cat['query'][$x]['CATEGORY_NAME'];
			}
		}
		$cat .= ")";
	}
	
	$new_sendername = $sendername . $cat;
	
	$business_type = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $this->put('invite_id')),'BUSINESS_TYPE')[0]['BUSINESS_TYPE'];
	//$this->response($business_type);
	$user = $this->put('user_id');
	$vrdhid = null;
	if($business_type == 3 || $business_type == 1 || $business_type == 4){
		
		$vrdhid = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('BUHEAD_ID' => $user),'VRDHEAD_ID');
		$vrdstaff = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('BUHEAD_ID' => $user),'VRDSTAFF_ID');
	

			$where_arr_def = array(
				'TYPE_ID' 		=> 1, // for registration
				'STATUS_ID' 	=> $status
			);
		
	}else{
		
		$vrdhid = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('FASHEAD_ID' => $user),'VRDHEAD_ID');
		$vrdstaff = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('FASHEAD_ID' => $user),'VRDSTAFF_ID');
	

			$where_arr_def = array(
				'TYPE_ID' 		=> 1, // for registration
				'STATUS_ID' 	=> $status
			);

	}
	//$this->response($this->db->last_query());
	$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();
	
	
	$where_arr_def_vrd = array(
		'TYPE_ID' 		=> 2, // for registration
		'STATUS_ID' 	=> $status
	);
	$rs_msg_vrd = $this->common_model->get_message_default($where_arr_def_vrd)->result_array();

	foreach ($vrdhid as $key => $value) {
		array_push($vtemp, $value['VRDHEAD_ID']);
	}

	$vtemp = array_unique($vtemp);
	$vtemp = array_values($vtemp);
	
	foreach ($vrdstaff as $key_two => $value_two) {
		array_push($vrdtemp, $value_two['VRDSTAFF_ID']);
	}

	$vrdtemp = array_unique($vrdtemp);
	$vrdtemp = array_values($vrdtemp);
	
	//$this->response($vtemp);
	foreach ($vtemp as $value) {
		if(!empty($value)){
			$approvername = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $value),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');	
			
			if(empty($approvername)){
				continue;
			}
			
			if(!isset($approvername[0]['USER_FIRST_NAME'])){
				$approvername[0]['USER_FIRST_NAME'] = "";
			
			}
			if(!isset($approvername[0]['USER_MIDDLE_NAME'])){
			
				$approvername[0]['USER_MIDDLE_NAME'] = "";
			}
			if(!isset($approvername[0]['USER_LAST_NAME'])){
				$approvername[0]['USER_LAST_NAME'] = "";
				
			}
			
			$approver = trim($approvername[0]['USER_FIRST_NAME'] ." ". $approvername[0]['USER_MIDDLE_NAME'] ." ". $approvername[0]['USER_LAST_NAME']);
			
			$tMess = $rs_msg;
			
			$tMess[0]['SUBJECT'] = str_replace('[vendorname]', $vendorname, $tMess[0]['SUBJECT']);
			$tMess[0]['TOPIC'] = str_replace('[vendorname]', $vendorname, $tMess[0]['TOPIC']);
			$tMess[0]['MESSAGE'] = str_replace('[approvername]', $approver, $tMess[0]['MESSAGE']);
			
			// Modified MSF - 20191126 (IJR-10619)
			//$tMess[0]['MESSAGE'] = str_replace('[sendername]', $sendername, $tMess[0]['MESSAGE']);
			$tMess[0]['MESSAGE'] = str_replace('[sendername]', $sendername . " " . $cat, $tMess[0]['MESSAGE']);
			$tMess[0]['MESSAGE'] = str_replace('[vendorname]', $vendorname, $tMess[0]['MESSAGE']);
			//$tMess[0]['DATE_SENT'] = date('d-M-Y h:i:s A');
			

			$insert_array = array(
				'SUBJECT' => $tMess[0]['SUBJECT'],
				'TOPIC' => $tMess[0]['TOPIC'],
				'DATE_SENT' => date('Y-m-d H:i:s'),
				'BODY' => $tMess[0]['MESSAGE'],
				'TYPE' => 'notification',
				'SENDER_ID' => 0,//notif
				'RECIPIENT_ID' => $value, //can be changed in query
				'INVITE_ID' =>$this->put('invite_id'),
				'VENDOR_ID' => $this->put('vendor_id')
			);

			$model_data = $this->mail_model->send_message($insert_array);

			$tmpEm = $email_template;
			$tmpEm = str_replace('[approvername]', $approver, $tmpEm);
			// Modified MSF - 20191126 (IJR-10619)
			//$tmpEm = str_replace('[sendername]', $sendername, $tmpEm);
			//$tmpEm = str_replace('[vendorname]', $vendorname, $tmpEm);
			$tmpEm = str_replace('[sendername]', $new_sendername, $tmpEm);
			$tmpEm = str_replace('[vendorname]', $vendorname, $tmpEm);

			$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', array('USER_ID' => $value));

			$email_data['subject'] = $vendorname . ' - ' . 'Vendor Registration Approval';
			//$email_data['bcc'] = '';
			$email_data['content'] = nl2br($tmpEm);
			$email_data['to'] = $emailaddress;
			
			$this->common_model->send_email_notification($email_data);
		}
	}
	
	foreach ($vrdtemp as $value) {
		if(!empty($value)){
			$approvername = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $value),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');	
			
			if(empty($approvername)){
				continue;
			}
			
			if(!isset($approvername[0]['USER_FIRST_NAME'])){
				$approvername[0]['USER_FIRST_NAME'] = "";
			
			}
			if(!isset($approvername[0]['USER_MIDDLE_NAME'])){
			
				$approvername[0]['USER_MIDDLE_NAME'] = "";
			}
			if(!isset($approvername[0]['USER_LAST_NAME'])){
				$approvername[0]['USER_LAST_NAME'] = "";
				
			}
			
			$approver = trim($approvername[0]['USER_FIRST_NAME'] ." ". $approvername[0]['USER_MIDDLE_NAME'] ." ". $approvername[0]['USER_LAST_NAME']);
			
			$tMess = $rs_msg_vrd;
			
			$tMess[0]['SUBJECT'] = str_replace('[vendorname]', $vendorname, $tMess[0]['SUBJECT']);
			$tMess[0]['TOPIC'] = str_replace('[vendorname]', $vendorname, $tMess[0]['TOPIC']);
			$tMess[0]['MESSAGE'] = str_replace('[approvername]', $approver, $tMess[0]['MESSAGE']);
			
			// Modified MSF - 20191126 (IJR-10619)
			//$tMess[0]['MESSAGE'] = str_replace('[sendername]', $sendername, $tMess[0]['MESSAGE']);
			$tMess[0]['MESSAGE'] = str_replace('[sendername]', $sendername . " " . $cat, $tMess[0]['MESSAGE']);
			$tMess[0]['MESSAGE'] = str_replace('[vendorname]', $vendorname, $tMess[0]['MESSAGE']);
			//$tMess[0]['DATE_SENT'] = date('d-M-Y h:i:s A');
			

			$insert_array = array(
				'SUBJECT' => $tMess[0]['SUBJECT'],
				'TOPIC' => $tMess[0]['TOPIC'],
				'DATE_SENT' => date('Y-m-d H:i:s'),
				'BODY' => $tMess[0]['MESSAGE'],
				'TYPE' => 'notification',
				'SENDER_ID' => 0,//notif
				'RECIPIENT_ID' => $value, //can be changed in query
				'INVITE_ID' =>$this->put('invite_id'),
				'VENDOR_ID' => $this->put('vendor_id')
			);

			$model_data = $this->mail_model->send_message($insert_array);

			$tmpEm = $email_template_vrdstaff;
			$tmpEm = str_replace('[approvername]', $approver, $tmpEm);
			// Modified MSF - 20191126 (IJR-10619)
			//$tmpEm = str_replace('[sendername]', $sendername, $tmpEm);
			//$tmpEm = str_replace('[vendorname]', $vendorname, $tmpEm);
			$tmpEm = str_replace('[sendername]', $new_sendername, $tmpEm);
			$tmpEm = str_replace('[vendorname]', $vendorname, $tmpEm);

			$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', array('USER_ID' => $value));

			$email_data['subject'] = $vendorname . ' - ' . 'Vendor Registration Approval';
			//$email_data['bcc'] = '';
			$email_data['content'] = nl2br($tmpEm);
			$email_data['to'] = $emailaddress;
			
			$this->common_model->send_email_notification($email_data);
		}
	}

	$this->response($email_data);
}
//

function send_email_to_put()
{
	
	$this->load->model('mail_model');
	$invid = $this->put('invite_id');
	$remarks = $this->put('reject_remarks');
	$status = $this->put('status');
	$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $invid),'CREATED_BY');
	$buyername = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $bu[0]['CREATED_BY']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');

	if(!isset($buyername[0]['USER_FIRST_NAME'])){
		$buyername[0]['USER_FIRST_NAME'] = "";
	}
	if(!isset($buyername[0]['USER_MIDDLE_NAME'])){
		$buyername[0]['USER_MIDDLE_NAME'] = "";
	}
	if(!isset($buyername[0]['USER_LAST_NAME'])){
		$buyername[0]['USER_LAST_NAME'] = "";
	}

	$buname = trim($buyername[0]['USER_FIRST_NAME'] ." " . $buyername[0]['USER_MIDDLE_NAME']. " ". $buyername[0]['USER_LAST_NAME']);

	$user = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $this->put('user_id')),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

	if(!isset($user[0]['USER_FIRST_NAME'])){
		$user[0]['USER_FIRST_NAME'] = "";
	}
	if(!isset($user[0]['USER_MIDDLE_NAME'])){
		$user[0]['USER_MIDDLE_NAME'] = "";
	}
	if(!isset($user[0]['USER_LAST_NAME'])){
		$user[0]['USER_LAST_NAME'] = "";
	}

	$username = trim($user[0]['USER_FIRST_NAME'] ." " . $user[0]['USER_MIDDLE_NAME']. " ". $user[0]['USER_LAST_NAME']);


	$vendoruid = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $this->put('invite_id')),'USER_ID');

//	$this->response($vendoruid);


	$vnd = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vendoruid[0]['USER_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

	if(!isset($vnd[0]['USER_FIRST_NAME'])){
		$vnd[0]['USER_FIRST_NAME'] = "";
	}
	if(!isset($vnd[0]['USER_MIDDLE_NAME'])){
		$vnd[0]['USER_MIDDLE_NAME'] = "";
	}
	if(!isset($vnd[0]['USER_LAST_NAME'])){
		$vnd[0]['USER_LAST_NAME'] = "";
	}

	$vndname = trim($vnd[0]['USER_FIRST_NAME'] ." " . $vnd[0]['USER_MIDDLE_NAME']. " ". $vnd[0]['USER_LAST_NAME']);

	//$this->response($vndname);

	$ttype = 39;
	$email = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE','CONTENT',array('TEMPLATE_TYPE' => $ttype, 'ACTIVE' => 1));

	$email = str_replace('[receiver]', $buname, $email);
	$email = str_replace('[remarks]', $this->put('reject_remarks'), $email);
	$email = str_replace('[approver]', $username, $email);
	$email = str_replace('[vendorname]', $vndname, $email);


	$email_data['subject'] = $vndname . ' - Vendor Registration Approval - Suspended';
	//$email_data['bcc'] = '';
	$email_data['content'] = nl2br($email);
	$email_data['to'] = $buyername[0]['USER_EMAIL'];

	/*$this->response($email_data);*/

	$this->common_model->send_email_notification($email_data);

	// notif
	if($status == 123 || $status == 124 || $status == 14){
		$inviter_notif_query = $this->common_model->get_message_default(array(
			'TYPE_ID' 		=> 1, // for registration
			'STATUS_ID' 	=> 123 
		));
		//Jay

		if ($inviter_notif_query->num_rows() > 0) {
			$inviter_notif_row = $inviter_notif_query->first_row();

			$inviter_msg_subject = str_replace('[vendorname]', $vndname, $inviter_notif_row->SUBJECT);
			$inviter_msg_topic = str_replace('[vendorname]', $vndname, $inviter_notif_row->TOPIC);
			$inviter_msg = str_replace('[sendername]', $buname, $inviter_notif_row->MESSAGE);
			$inviter_msg = str_replace('[vendorname]', $vndname, $inviter_msg);
			$inviter_msg = str_replace('[remarks]', $remarks, $inviter_msg);
			$inviter_msg = str_replace('[approvername]', $username, $inviter_msg);

			$insert_inv_msg_array = array(
				'SUBJECT' => $inviter_msg_subject,
				'TOPIC' => $inviter_msg_topic,
				'DATE_SENT' => date('Y-m-d H:i:s'),
				'BODY' => $inviter_msg,
				'TYPE' => 'notification',
				'SENDER_ID' =>  0,//notif
				'RECIPIENT_ID' => $bu[0]['CREATED_BY'], //can be changed in query
				'VENDOR_ID' => $this->put('vendor_id')
			);
		
			$model_data = $this->mail_model->send_message($insert_inv_msg_array);
		
		}
	}

}


function resubmit_additional_info_put()
	{

		$this->load->model('mail_model');
		$vndid = $this->put('vendor_id');

		if(!isset($vndid)){

			$this->response("error");

		}

		$whr = array('VENDOR_INVITE_ID' => $this->put('invite_id'));

		$bu = $this->common_model->select_query('SMNTP_VENDOR_INVITE',$whr,'CREATED_BY');


		$where = array('USER_ID' => $bu[0]['CREATED_BY']);
		$vrdstaff = $this->common_model->select_query('SMNTP_USERS_MATRIX',$where,'VRDSTAFF_ID');

		$where = array(
			'ACTIVE' => 1,
			'TEMPLATE_TYPE' => 35 
			);

		$temail = $this->common_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',$where,'CONTENT');

		//$this->response($temail);

		for($i = 0 ; $i<count($vrdstaff);$i++){

			$tme = $temail;

				$vrdstaff_name = $this->trim_name($this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $vrdstaff[$i]['VRDSTAFF_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME'));

				$vndn = $this->common_model->select_query('SMNTP_USERS',array('VENDOR_ID' => $vndid),'USER_ID,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

				//$this->response($vndn);
				$approver = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $this->put('user_id')),'USER_ID,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

				$approvername = $this->trim_name($approver);

				$vndname = $this->trim_name($vndn);

				$where_arr_def = array(
				'TYPE_ID' 		=> 1, // for registration
				'STATUS_ID' 	=> 156 //statusid for incomplete
				);


				$tMess = $this->common_model->get_message_default($where_arr_def)->result_array();


				$tMess[0]['SUBJECT'] = str_replace('[vendorname]', $vndname, $tMess[0]['SUBJECT']);
				$tMess[0]['TOPIC'] = str_replace('[vendorname]', $vndname, $tMess[0]['TOPIC']);
				$tMess[0]['MESSAGE'] = str_replace('[vrdstaff]', $vrdstaff_name, $tMess[0]['MESSAGE']);
				$tMess[0]['MESSAGE'] = str_replace('[vendorname]', $vndname, $tMess[0]['MESSAGE']);

				$insert_array = array(
				'SUBJECT' => $tMess[0]['SUBJECT'],
				'TOPIC' => $tMess[0]['TOPIC'],
				'DATE_SENT' => date('Y-m-d H:i:s'),
				'BODY' => $tMess[0]['MESSAGE'],
				'TYPE' => 'notification',
				'SENDER_ID' => 0,//notif,
				'RECIPIENT_ID' => $vrdstaff[$i]['VRDSTAFF_ID'], //can be changed in query
				'VENDOR_ID' => $this->put('vendor_id')
				);


				$model_data = $this->mail_model->send_message($insert_array);
			

			//$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

			$where_arr = array('USER_ID' => $vrdstaff[$i]['VRDSTAFF_ID']);
			$email = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);
			$tme[0]['CONTENT'] = str_replace('[vrdstaff]', $vrdstaff_name, $tme[0]['CONTENT']);
			$tme[0]['CONTENT'] = str_replace('[vendorname]', $vndname, $tme[0]['CONTENT']);
			//$tme = str_replace('[vendorname]', $vndname, $tme[0]['CONTENT']);


			$email_data['subject'] = 'Vendor Resubmitted Additional Requirements';
			//$email_data['bcc'] = 'marcanthonypacres@yahoo.com';
			$email_data['content'] = nl2br($tme[0]['CONTENT']);

			$email_data['to'] = $email;
			$this->common_model->send_email_notification($email_data);

		
/*	

			$email_data['subject'] = 'Vendor Approval';
			//$email_data['bcc'] = '';
			$email_data['content'] = nl2br($tme);

			$email_data['to'] = $emailaddress;
			$this->common_model->send_email_notification($email_data);*/



		}






	
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


	function message_bs_suspended_put()
	{
		$this->load->model('mail_model');
		$inv_id = $this->put('invite_id');
		$bsid = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $inv_id),'CREATED_BY,USER_ID');
		$remarks = $this->put('reject_remarks');

		$vndName_raw = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $bsid[0]['USER_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
		$bsname_raw = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $bsid[0]['CREATED_BY']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
		$approver_raw = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' =>$this->put('user_id')),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

		$vndname = $this->trim_name($vndName_raw);
		$bsname = $this->trim_name($bsname_raw);
		$appname = $this->trim_name($approver_raw);

		$where_arr_def = array(
			'TYPE_ID' 		=> 1, // for registration
			'STATUS_ID' 	=> 123 
		);

		// notif
		$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();

		$rs_msg[0]['SUBJECT'] = str_replace('[vendorname]', $vndname, $rs_msg[0]['SUBJECT']);
		$rs_msg[0]['TOPIC'] = str_replace('[vendorname]', $vndname, $rs_msg[0]['TOPIC']);
		$rs_msg[0]['MESSAGE'] = str_replace('[sendername]', $bsname, $rs_msg[0]['MESSAGE']);
		$rs_msg[0]['MESSAGE'] = str_replace('[vendorname]', $vndname, $rs_msg[0]['MESSAGE']);
		$rs_msg[0]['MESSAGE'] = str_replace('[remarks]', $remarks, $rs_msg[0]['MESSAGE']);
		$rs_msg[0]['MESSAGE'] = str_replace('[approvername]', $appname, $rs_msg[0]['MESSAGE']);

		$insert_array = array(
			'SUBJECT' => $rs_msg[0]['SUBJECT'],
			'TOPIC' => $rs_msg[0]['TOPIC'],
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $rs_msg[0]['MESSAGE'],
			'TYPE' => 'notification',
			'SENDER_ID' =>  0,//notif
			'RECIPIENT_ID' => $bsid[0]['CREATED_BY'], //can be changed in query
			'VENDOR_ID' => $this->put('vendor_id')
		);

		$model_data = $this->mail_model->send_message($insert_array);

		// email 
		$where = array(
			'ACTIVE' => 1,
			'TEMPLATE_TYPE' => 39 
		);

		$temail = $this->common_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',$where,'CONTENT');
		$where_arr = array('USER_ID' => $bsid[0]['CREATED_BY']);
		$email = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);

		$temail[0]['CONTENT'] = str_replace('[receiver]',$bsname,$temail[0]['CONTENT']);
		$temail[0]['CONTENT'] = str_replace('[vendorname]',$vndname,$temail[0]['CONTENT']);
		$temail[0]['CONTENT'] = str_replace('[approver]',$appname,$temail[0]['CONTENT']);
		$temail[0]['CONTENT'] = str_replace('[remarks]',$remarks,$temail[0]['CONTENT']);

		$email_data['subject'] = $vndname .' - Vendor Registration Approval - Suspended';
		//$email_data['bcc'] = 'marcanthonypacres@yahoo.com';
		$email_data['content'] = nl2br($temail[0]['CONTENT']);
		$email_data['to'] = $email;

		$this->common_model->send_email_notification($email_data);
	
		$this->response($email_data);

	}

	function portal_vendor_success_put()
	{
		$this->load->model('mail_model');


		$status = $this->put('SID');
		$user_id = $this->put('user_id');
		$data['vm'] = $this->put('vname');
		$email = $this->put('email');



		if($status == 194){		
		$data['edate'] = $this->put('sub_date');
		$where_arr_def = array(
			'TYPE_ID' 		=> 1, // for registration
			'STATUS_ID' 	=> 160 
		);

		$pm = $this->common_model->get_message_default($where_arr_def)->result_array();
		$vrdname = $this->common_model->select_query('SMNTP_USERS',array('USER_ID'=>$user_id),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
		$vrdemail= $this->common_model->select_query('SMNTP_USERS',array('USER_ID'=>$user_id),'USER_EMAIL');

		$vrdname = $this->trim_name($vrdname);

		$pm[0]['SUBJECT'] = str_replace('[vendorname]',$data['vm'], $pm[0]['SUBJECT']);
		$pm[0]['TOPIC'] = str_replace('[vendorname]',$data['vm'], $pm[0]['TOPIC']);
		$pm[0]['MESSAGE'] = str_replace('[vendorname]',$data['vm'], $pm[0]['MESSAGE']);
		$pm[0]['MESSAGE'] = str_replace('[vrdstaff]',$vrdname, $pm[0]['MESSAGE']);

		$insert_array = array(
			'SUBJECT' => $pm[0]['SUBJECT'],
			'TOPIC' => $pm[0]['TOPIC'],
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $pm[0]['MESSAGE'],
			'TYPE' => 'notification',
			'SENDER_ID' =>  0,//notif
			'RECIPIENT_ID' =>$user_id, //can be changed in query
			'VENDOR_ID' => $this->put('VID')
			);
		$model_data = $this->mail_model->send_message($insert_array);


		//email

		$em = $this->common_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => 43),'CONTENT');
		$em[0]['CONTENT'] = str_replace('[vrdstaff]', $vrdname, $em[0]['CONTENT']);
		$em[0]['CONTENT'] = str_replace('[vendor]', $data['vm'], $em[0]['CONTENT']);
		$em[0]['CONTENT'] = str_replace('[vendorname]', $data['vm'], $em[0]['CONTENT']);
		//$this->response($em);
		
		// Jay 
		// Chineck ko muna kung yung ARD kung submitted na baka kasi may tamaan
		// Kasi yung ginagamit dito Vendor Email at Hindi VRDStaff
		$ard_submitted = $this->put('ARD_already_submitted');
		$temp_email = '';
		if( ! $ard_submitted){
			$temp_email = $email;
		}else{
			$temp_email = $vrdemail[0]['USER_EMAIL'];
		}
		//jay-end
		
		//$this->response($temp_email);
		$email_data['subject'] = $data['vm'] . ' - ' . $pm[0]['TOPIC'];
		//$email_data['bcc'] = '';
		$email_data['content'] = nl2br($em[0]['CONTENT']);
		$email_data['to'] = $temp_email; 

		$this->common_model->send_email_notification($email_data);



		}else{
	
		$data['edate'] = $this->put('sub_date');
		$where_arr_def = array(
			'TYPE_ID' 		=> 1, // for registration
			'STATUS_ID' 	=> 190 
		);

		$pm = $this->common_model->get_message_default($where_arr_def)->result_array();

		$pm[0]['SUBJECT'] = str_replace('[vendorname]',$data['vm'], $pm[0]['SUBJECT']);
		$pm[0]['TOPIC'] = str_replace('[vendorname]',$data['vm'], $pm[0]['TOPIC']);
		$pm[0]['MESSAGE'] = str_replace('[vendor_name]',$data['vm'], $pm[0]['MESSAGE']);
		$pm[0]['MESSAGE'] = str_replace('[submission_deadline]',$data['edate'], $pm[0]['MESSAGE']);

		$insert_array = array(
			'SUBJECT' => $pm[0]['SUBJECT'],
			'TOPIC' => $pm[0]['TOPIC'],
			'DATE_SENT' => date('Y-m-d H:i:s'),
			'BODY' => $pm[0]['MESSAGE'],
			'TYPE' => 'notification',
			'SENDER_ID' =>  0,//notif
			'RECIPIENT_ID' =>$this->put('UID'), //can be changed in query
			'VENDOR_ID' => $this->put('VID')
			);

		$model_data = $this->mail_model->send_message($insert_array);


	/*	$em = $this->common_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => 21),'CONTENT');
		$em[0]['CONTENT'] = str_replace('[vendor_name]', $data['vm'], $em[0]['CONTENT']);
		$em[0]['CONTENT'] = str_replace('[submission_deadline]', $data['edate'], $em[0]['CONTENT']);


		$email_data['subject'] = 'Review Additional Requirements';
		//$email_data['bcc'] = 'marcanthonypacres@yahoo.com';
		$email_data['content'] = nl2br($em[0]['CONTENT']);
		$email_data['to'] = $email;*/

	//	$this->response($email_data);
//		$this->common_model->send_email_notification($email_data);




	


		}

	}

	//jay
	public function get_waive_data_get(){
		$invite_id = $this->get('invite_id');
		$result['rd_waive_result'] =  $this->registration_model->get_rd_waive_data($invite_id);
		$result['ad_waive_result'] =  $this->registration_model->get_ad_waive_data($invite_id);
		$this->response($result);
	}

	public function get_sysdate_get(){
		$systimestamp = $this->common_model->get_sysdate();

		$this->response($systimestamp); 
	}
	
	public function get_users_matrix_get(){
		$user_id = $this->get('user_id');
		$users = $this->registration_model->get_users_in_matrix($user_id);
		$this->response($users); 
	}

	public function hide_show_rfq_get()
	{

		$rfq = $this->common_model->select_query('SMNTP_SYSTEM_CONFIG',array('CONFIG_NAME' =>'hide_show_rfq'),'CONFIG_VALUE');

		$this->response($rfq);
	}

	function message_buhead_suspended_put(){
		$this->message_suspended('BUHEAD_ID');
	}
	function message_ghead_suspended_put(){
		//$this->message_suspended('GHEAD_ID');
		$this->suspend_vendor('GHEAD_ID');

	}
	function message_fashead_suspended_put(){
		$this->suspend_vendor('FASHEAD_ID');
	}
	function message_vrdhead_suspended_put(){
		$this->message_suspended('VRDHEAD_ID');
	}
	
	function message_suspended($id_string = '')
	{
		$this->load->model('mail_model');
		$inv_id = $this->put('invite_id');
		$bsid = $this->common_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $inv_id),'CREATED_BY,USER_ID');
		$remarks = $this->put('reject_remarks');

		$vndName_raw = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $bsid[0]['USER_ID']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
		$bsname_raw = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $bsid[0]['CREATED_BY']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');
		$approver_raw = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' =>$this->put('user_id')),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME');

		$vndname = $this->trim_name($vndName_raw);
		$bsname = $this->trim_name($bsname_raw);
		$appname = $this->trim_name($approver_raw);
		
		//14	Suspended by BU Head
		//123	Suspended by FAS Head
		//124	Suspended by HaTS
		//16	Suspended by VRD Head
		$where_arr_def = array(
			'TYPE_ID' 		=> 1, // for registration
			'STATUS_ID' 	=> 123 
		);

		$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array();

		$userids = $this->common_model->select_query_wherein('SMNTP_USERS_MATRIX', 'USER_ID', array($bsid[0]['CREATED_BY']), $id_string);
		
		//$this->response($this->db->last_query());
		$email_data = array();
		$array_buhead = array();
		foreach($userids as $userid){
			if(!empty($userid[$id_string])){
				if( ! in_array($userid[$id_string], $array_buhead)){
					$array_buhead[] = $userid[$id_string];
					$where_arr 			= array('USER_ID' => $userid[$id_string]);
					$buhead = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME', $where_arr, 'FULLNAME');
					
					//Send Email
					$where_arr = array('TEMPLATE_TYPE' => 39,'ACTIVE' => 1);
					$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

					$where_arr = array('USER_ID' => $userid[$id_string]);
					$emailaddress = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'USER_EMAIL', $where_arr);
					
					$email_template = str_replace('[receiver]'	,$buhead	,$email_template);
					$email_template = str_replace('[vendorname]',$vndname	,$email_template);
					$email_template = str_replace('[approver]'	,$appname	,$email_template);
					$email_template = str_replace('[remarks]'	,$remarks	,$email_template);
					
					$email_data['subject'] = $vndname . ' - Vendor Registration Approval - Suspended';
					$email_data['content'] = nl2br($email_template);

					$email_data['to'] = $emailaddress;
					$this->common_model->send_email_notification($email_data);
					
								//Send portal
					$rs_msg[0]['SUBJECT'] 	= str_replace('[vendorname]'	, $vndname	, $rs_msg[0]['SUBJECT']);
					$rs_msg[0]['TOPIC'] 	= str_replace('[vendorname]'	, $vndname	, $rs_msg[0]['TOPIC']);
					$rs_msg[0]['MESSAGE'] 	= str_replace('[sendername]'	, $bsname	, $rs_msg[0]['MESSAGE']);
					$rs_msg[0]['MESSAGE'] 	= str_replace('[vendorname]'	, $vndname	, $rs_msg[0]['MESSAGE']);
					$rs_msg[0]['MESSAGE'] 	= str_replace('[remarks]'		, $remarks	, $rs_msg[0]['MESSAGE']);
					$rs_msg[0]['MESSAGE'] 	= str_replace('[approvername]'	, $appname	, $rs_msg[0]['MESSAGE']);


					$insert_array = array(
						'SUBJECT' 		=> $rs_msg[0]['SUBJECT'],
						'TOPIC' 		=> $rs_msg[0]['TOPIC'],
						'DATE_SENT' 	=> date('Y-m-d H:i:s'),
						'BODY' 			=> $rs_msg[0]['MESSAGE'],
						'TYPE' 			=> 'notification',
						'SENDER_ID' 	=> 0,//notif
						'RECIPIENT_ID' 	=> $userid[$id_string], //can be changed in query
						'VENDOR_ID' 	=> $this->put('vendor_id')
					);


					$model_data = $this->mail_model->send_message($insert_array);

				}
			}
		}
		
		$this->response($email_data);
	}

	function suspended_vrdhead_put()
	{

		$this->load->model('mail_model');
		$inv_id = $this->put('invite_id');
		$reject = $this->put('reject_remarks');

		$user_id = $this->put('user_id');

		$approver = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID','SMNTP_USERS',array('USER_ID'=>$user_id));
		$approver = $approver->row();
		$approver_name = $approver->USER_FIRST_NAME ." ".$approver->USER_MIDDLE_NAME ." ".$approver->USER_LAST_NAME;

		$inviter = $this->common_model->select_query_active('CREATED_BY,VENDOR_NAME','SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID'=> $inv_id));
		$vendorname = $inviter->row()->VENDOR_NAME;
		$inviter_id = $inviter->row()->CREATED_BY;
		$vrdhead_ids = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('USER_ID' => $inviter_id),'VRDHEAD_ID');

		$where_arr_def = array('TEMPLATE_TYPE' => 39);
		$email_template = $this->common_model->select_query_active('*','SMNTP_EMAIL_DEFAULT_TEMPLATE',$where_arr_def);
		$email_template = $email_template->row();
		$where_arr_def = array('STATUS_ID' => 123);
		$notif_template = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$where_arr_def);
		$notif_template = $notif_template->row();


		$real_vrdhead_id = array();
		foreach ($vrdhead_ids as $key => $value) {
			if($value['VRDHEAD_ID'] != null || $value['VRDHEAD_ID'] != NULL ||$value['VRDHEAD_ID'] != ""){
				array_push($real_vrdhead_id,$value['VRDHEAD_ID']);
			}
		}

		$vrd_infos = $this->common_model->select_query_wherein('SMNTP_USERS','USER_ID',$real_vrdhead_id,'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL,USER_ID');
		foreach ($vrd_infos as $key => $value) {
			if($value['USER_FIRST_NAME'] == NULL){
				$value['USER_FIRST_NAME'] = "";
			}
			if($value['USER_MIDDLE_NAME'] == NULL){
				$value['USER_MIDDLE_NAME'] = "";
			}
			if($value['USER_LAST_NAME'] == NULL){
				$value['USER_LAST_NAME'] = "";
			}

			$vrdname = $value['USER_FIRST_NAME'] . " " . $value['USER_MIDDLE_NAME'] . " " . $value['USER_LAST_NAME'];
			$temp_email = $email_template;
			$temp_notif = $notif_template;

			$app_sen_ven = array(
					array('name' => $vrdname ,'type' => 'sender'),
					array('name' => $approver_name ,'type' => 'approver'),
					array('name' => $vrdname ,'type' => 'receiver'),
					array('name' => $vendorname ,'type' => 'vendor'),
					array('name' => $reject ,'type' => 'remark')
				);
		
			$temp_email = change_tag_email($temp_email,$app_sen_ven);
			$temp_notif = change_tag_email($temp_notif,$app_sen_ven);

			if($value['USER_ID'] != $user_id){

				$email_data = array(
					'to' => $value['USER_EMAIL'],
					'subject' => $temp_email->HEADER,
					'content' =>$temp_email->CONTENT
				);

				$res = $this->common_model->send_email_notification($email_data);
				$insert_array = array(
					'SUBJECT' => $temp_notif->SUBJECT,
					'TOPIC' => $temp_notif->TOPIC,
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $temp_notif->MESSAGE,
					'TYPE' => 'notification',
					'SENDER_ID' => 0,
					'RECIPIENT_ID' =>$value['USER_ID'],
					'INVITE_ID' =>$inv_id,
					'VENDOR_ID' => "" //set to 000 temporary
				);
				
			$model_data = $this->mail_model->send_message($insert_array);
			}	
		}	
	}

	function suspend_buhead_put()
	{

		$this->load->model('mail_model');
		$inv_id = $this->put('invite_id');
		$reject = $this->put('reject_remarks');

		$user_id = $this->put('user_id');
		$approver = $this->common_model->select_query_active('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID','SMNTP_USERS',array('USER_ID'=>$user_id));
		$approver = $approver->row();
		$approver_name = $approver->USER_FIRST_NAME ." ".$approver->USER_MIDDLE_NAME ." ".$approver->USER_LAST_NAME;
		$inviter = $this->common_model->select_query_active('CREATED_BY,VENDOR_NAME','SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID'=> $inv_id));
		$vendorname = $inviter->row()->VENDOR_NAME;
		$inviter_id = $inviter->row()->CREATED_BY;
		$buhead = $this->common_model->select_query('SMNTP_USERS_MATRIX',array('USER_ID' => $inviter_id),'BUHEAD_ID');

		$where_arr_def = array('TEMPLATE_TYPE' => 39);
		$email_template = $this->common_model->select_query_active('*','SMNTP_EMAIL_DEFAULT_TEMPLATE',$where_arr_def);
		$email_template = $email_template->row();
		$where_arr_def = array('STATUS_ID' => 123);
		$notif_template = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$where_arr_def);
		$notif_template = $notif_template->row();

		$buhead_info = $this->common_model->select_query_active('USER_EMAIL,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID','SMNTP_USERS',array('USER_ID'=>$buhead[0]['BUHEAD_ID']));
		$buhead_info = $buhead_info->row();
		$buhead_name = $buhead_info->USER_FIRST_NAME ." ".$buhead_info->USER_MIDDLE_NAME ." ".$buhead_info->USER_LAST_NAME;

			$app_sen_ven = array(
					array('name' => $approver_name ,'type' => 'approver'),
					array('name' => $buhead_name ,'type' => 'receiver'),
					array('name' => $buhead_name ,'type' => 'sender'),
					array('name' => $vendorname ,'type' => 'vendor'),
					array('name' => $reject ,'type' => 'remark')
				);

		$email_template = change_tag_email($email_template,$app_sen_ven);
		$notif_template = change_tag_email($notif_template,$app_sen_ven);


			$email_data = array(
				'to' => $buhead_info->USER_EMAIL,
				'subject' => $email_template->HEADER,
				'content' =>$email_template->CONTENT
			);

			$res = $this->common_model->send_email_notification($email_data);
			$insert_array = array(
					'SUBJECT' => $notif_template->SUBJECT,
					'TOPIC' => $notif_template->TOPIC,
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $notif_template->MESSAGE,
					'TYPE' => 'notification',
					'SENDER_ID' => 0,
					'RECIPIENT_ID' =>$buhead_info->USER_ID,
					'INVITE_ID' =>$inv_id,
					'VENDOR_ID' => "" //set to 000 temporary
				);
				
			$model_data = $this->mail_model->send_message($insert_array);
	}

	function suspend_vendor($user_type = ""){
		if($user_type == ""){
			$user_type = "GHEAD_ID";
		}

		$this->load->model('mail_model');
		$inv_id = $this->put('invite_id');
		$reject = $this->put('reject_remarks');
		$user_id = $this->put('user_id');
		$approver = $this->common_model->select_query_active('CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME,USER_ID','SMNTP_USERS',array('USER_ID'=>$user_id));
		$approver = $approver->row();
		$inviter = $this->common_model->select_query_active('CREATED_BY,VENDOR_NAME','SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID'=> $inv_id));
		$inviter = $inviter->row();	
		$vendorname = $inviter->VENDOR_NAME;
		$inviter_id = $inviter->CREATED_BY;

		$where_arr_def = array('TEMPLATE_TYPE' => 39);
		$email_template = $this->common_model->select_query_active('*','SMNTP_EMAIL_DEFAULT_TEMPLATE',$where_arr_def);
		$email_template = $email_template->row();
		$where_arr_def = array('STATUS_ID' => 123);
		$notif_template = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$where_arr_def);
		$notif_template = $notif_template->row();



		$recipients = $this->common_model->select_query_active($user_type,'SMNTP_USERS_MATRIX',array('USER_ID'=> $inviter_id));
		$recipients = $recipients->row();

		$rrecipient = array();

		foreach ($recipients as $key => $value) {
			if($value != NULL){
				array_push($rrecipient,$value);
			}	
		}
		$rrecipient = array_unique($rrecipient);

		foreach ($rrecipient as $key => $value) {
		$rec = $this->common_model->select_query_active('CONCAT((USER_FIRST_NAME) , (\' \') , (USER_MIDDLE_NAME) , (\' \') , (USER_LAST_NAME)) AS FULLNAME,USER_ID,USER_EMAIL','SMNTP_USERS',array('USER_ID'=>$value));	
			$rec = $rec->row();

			$app_sen_ven = array(
					array('name' => $rec->FULLNAME ,'type' => 'sender'),
					array('name' => $rec->FULLNAME ,'type' => 'receiver'),
					array('name' => $approver->FULLNAME ,'type' => 'approver'),
					array('name' => $vendorname ,'type' => 'vendor'),
					array('name' => $reject ,'type' => 'remark')
				);

			$temp_email = $email_template;
			$temp_notif = $notif_template;
			$temp_email = change_tag_email($temp_email,$app_sen_ven);
			$temp_notif = change_tag_email($temp_notif,$app_sen_ven);


			$email_data = array(
				'to' => $rec->USER_EMAIL,
				'subject' => $temp_email->HEADER,
				'content' =>$temp_email->CONTENT
			);

			$res = $this->common_model->send_email_notification($email_data);
			$insert_array = array(
					'SUBJECT' => $temp_notif->SUBJECT,
					'TOPIC' => $temp_notif->TOPIC,
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $temp_notif->MESSAGE,
					'TYPE' => 'notification',
					'SENDER_ID' => 0,
					'RECIPIENT_ID' =>$rec->USER_ID,
					'INVITE_ID' =>$inv_id,
					'VENDOR_ID' => "" //set to 000 temporary
				);
				
			$model_data = $this->mail_model->send_message($insert_array);
		}

		$this->response($email_data);
	}


	public function send_emal_vrd_put(){

		$vendor_user_id = $this->put('UID');
		$inviter = $this->common_model->select_query_active('VENDOR_INVITE_ID,CREATED_BY,VENDOR_NAME','SMNTP_VENDOR_INVITE',array('USER_ID'=> $vendor_user_id));
		$inviter = $inviter->row();	
		$inviter_id = $inviter->CREATED_BY; 

		$res = $this->db->query('SELECT SMNTP_USERS.USER_ID,SMNTP_USERS.USER_EMAIL,CONCAT(SMNTP_USERS.USER_FIRST_NAME , \' \' , SMNTP_USERS.USER_MIDDLE_NAME , \'\' , SMNTP_USERS.USER_LAST_NAME) AS FULLNAME FROM SMNTP_USERS LEFT JOIN SMNTP_USERS_MATRIX ON SMNTP_USERS.USER_ID = SMNTP_USERS_MATRIX.VRDSTAFF_ID WHERE SMNTP_USERS_MATRIX.USER_ID = '.$inviter_id.'');	

		$res = $res->result();

		//$this->response($res);

		foreach ($res as $value) {

			$where_arr_def = array('TEMPLATE_TYPE' => 43);
			$email_template = $this->common_model->select_query_active('*','SMNTP_EMAIL_DEFAULT_TEMPLATE',$where_arr_def)->row();

			$temp_email = $email_template;
			$temp_email->CONTENT = str_replace('[vrdstaff]',$value->FULLNAME,$temp_email->CONTENT);
			$temp_email->CONTENT = str_replace('[vendorname]',$inviter->VENDOR_NAME,$temp_email->CONTENT);
			$temp_email->TEMPLATE_HEADER  = str_replace('[vendorname]',$inviter->VENDOR_NAME,$temp_email->TEMPLATE_HEADER);



			$where_arr_def = array('STATUS_ID' => 160);
			$notif_template = $this->common_model->select_query_active('*','SMNTP_MESSAGE_DEFAULT',$where_arr_def);
			$notif_template = $notif_template->row();
			$temp_notif	= $notif_template;
			$temp_notif->MESSAGE = str_replace('[vrdstaff]',$value->FULLNAME,$temp_notif->MESSAGE);
			$temp_notif->MESSAGE = str_replace('[vendorname]',$inviter->VENDOR_NAME,$temp_notif->MESSAGE);
			$temp_notif->SUBJECT = str_replace('[vendorname]',$inviter->VENDOR_NAME,$temp_notif->SUBJECT);


			$email_data = array(
				'to' => $value->USER_EMAIL,
				'subject' => $temp_email->TEMPLATE_HEADER,
				'content' => $temp_email->CONTENT
			);

			$res = $this->common_model->send_email_notification($email_data);

			$insert_array = array(
					'SUBJECT' => $temp_notif->SUBJECT,
					'TOPIC' => $temp_notif->TOPIC,
					'DATE_SENT' => date('Y-m-d H:i:s'),
					'BODY' => $temp_notif->MESSAGE,
					'TYPE' => 'notification',
					'SENDER_ID' => 0,
					'RECIPIENT_ID' =>$value->USER_ID,
					'INVITE_ID' =>$inviter->VENDOR_INVITE_ID,
					'VENDOR_ID' => "" //set to 000 temporary
				);
				
			$model_data = $this->mail_model->send_message($insert_array);

		}
		$this->response(	count($res));


	}


}
?>
