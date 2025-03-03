<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	public function index()
	{
		$data['user_type_list'] = $this->get_user_type();
		$data['mvrd'] = $this->get_max_vrdstaff();
		$this->load->view('maintenance/users',$data);
	}
	
	public function create_user()
	{
			
		$data['log_id'] = $this->input->post('l_id');
		$data['l_pass'] = $this->input->post('l_pass');
		$data['f_name'] = $this->input->post('f_name');
		$data['m_name'] = $this->input->post('m_name');
		$data['l_name'] = $this->input->post('l_name');
		$data['mob'] = $this->input->post('mob');
		$data['e_mails'] = $this->input->post('e_mails');
		$data['u_type'] = $this->input->post('u_type');
		$data['p_type'] = $this->input->post('p_type');
		$data['snd'] = $this->input->post('snd');
		$data['a_type'] = $this->input->post('a_type');
		$data['approver'] = $this->input->post('approver');
	
		$result = $this->rest_app->post('index.php/users_admin/adduser/', $data, '');
		echo var_dump($result);
	}
		
	public function search_user()
	{	
	

		$z = $this->input->post('s_type');

		if($z == 1){
				$data = array(
						'u_id' => $this->input->post('datas'),
						'u_name' => $this->input->post('datas'),
						'u_type' => 1
					);
		}
		if($z == 2){
				$data = array(
						'u_id' => $this->input->post('datas'),
						'u_type' => 2
					);
		}
		if($z == 3){
				$data = array(
						'u_name' => $this->input->post('datas'),
						'u_type' => 3
					);
		}	
		
		$result = $this->rest_app->post('index.php/users_admin/searchuser/', $data, '');

		for($i = 0; $i < count($result->data); $i++){

			if($result->data[$i]->LAST_ATTEMPT == null){
				continue;
			}

			$result->data[$i]->LAST_ATTEMPT = date("l F j, Y g:i:s a",$result->data[$i]->LAST_ATTEMPT);
		}
		echo json_encode(	$result);
		
	}
	
	public function get_user_type()
	{
				
		$result = $this->rest_app->post('index.php/users_admin/get_user_type/', '', '');	
		$res = json_encode($result);		
		return json_decode($res, true);
		
	}

	public function get_max_vrdstaff()
	{
		$result = $this->rest_app->get('index.php/users_admin/max_vrd/', '', '');	
		return $result;

	}

	public function get_position()
	{
		
		
		$data = array(
		'sID' => $this->input->post('data')
		);
		
		$sID = $this->input->post('data');	
		$result = $this->rest_app->post('index.php/users_admin/get_position/', $data, '');	
		$res = json_encode($result);
		
		echo $res;
		
	}
	
	public function get_info()
	{		
		$data = array(
		'USER_ID' => $this->input->post('data')
		);		
		$result = $this->rest_app->get('index.php/users_admin/get_info/', $data, '');	
		$res = json_encode($result);	
		echo $res;
	
	}
	
	public function update_user()
	{	
	
		$data['adat'] = $this->input->post('adat');
		$data['l_pass'] = $this->input->post('e_pw');
		$data['f_name'] = $this->input->post('ef_name');
		$data['m_name'] = $this->input->post('em_name');
		$data['l_name'] = $this->input->post('el_name');
		$data['mob'] = $this->input->post('emob');
		$data['e_mails'] = $this->input->post('ee_mails');
		$data['u_type'] = $this->input->post('eu_type');
		$data['p_type'] = $this->input->post('ep_type');
		$data['a_type'] = $this->input->post('a_type');
		$data['approver'] = $this->input->post('approver');

		
		$result = $this->rest_app->post('index.php/users_admin/edit_user/', $data, '');		
		echo json_encode($result);	
	}


	public function search_username()
	{
			$data['USERNAME'] = $this->input->post('dat');
			$result = $this->rest_app->get('index.php/users_admin/try_compare/', $data, '');	
			echo json_encode($result);
			
	}

	public function del_user()
	{

		$data = array(
			'USER_ID' => $this->input->post('data')
			);
		$result = $this->rest_app->post('index.php/users_admin/delete_user/', $data, '');		
		echo json_encode($result);	

	}

	public function get_approver()
	{

		$data['POSITION_ID'] = $this->input->post('data');
		$result = $this->rest_app->get('index.php/users_admin/approver',$data,'');	
		echo json_encode($result);

	}

	public function get_category(){
	$data['VENDOR_TYPE'] = $this->input->post('position_id');
	
	if($this->input->post('position_id') == 2){
		$data['VENDOR_TYPE'] = 1;
	}else if($this->input->post('position_id') == 7){
		$data['VENDOR_TYPE'] = 2;
	}else if($this->input->post('position_id') == 11){
		$data['VENDOR_TYPE'] = 3;
	}else{
		$data['VENDOR_TYPE'] = NULL;
	}
	
	$data['CATEGORY_NAME'] = $this->input->post('data');
	$result = $this->rest_app->get('index.php/users_admin/category',$data,'');	
	echo json_encode($result);	

	}

	public function save_user_new(){
		//For Uploader only
		if( ! empty($this->input->post('uploader')) && $this->input->post('uploader') == 'true'){
			//Get the ID's of Approver and GHEAD, VRD HEAD, VRD STAFF
			$uploader_data = array(
				'head'	=> $this->input->post('head'),
				'vrdhead'	=> $this->input->post('vrdhead'),
				'vrd'	=> $this->input->post('vrd'),
				'category'	=> $this->input->post('category'),
				'vendor_type_id'	=> $this->input->post('vendor_type_id'),
				'user_id'		=> $this->session->userdata['user_id']
			);
			$result = $this->rest_app->get('index.php/users_admin/get_uploader_data_id',$uploader_data,'');	
			
			if( ! empty($result->head)){
				$_POST['head'] = $result->head;
			}
			
			if( ! empty($result->vrd)){
				$_POST['vrd'] = $result->vrd;
			}
			
			if( ! empty($result->vrdhead)){
				$_POST['vrdhead'] = $result->vrdhead;
			}
			
			if( ! empty($result->category)){
				$_POST['category'] = $result->category;
			}else{
				echo json_encode(array('error' => 'Something went wrong. Empty category.'));
				die();
			}
			if($this->input->post('type') == 2 || $this->input->post('type') == 11){
				if(empty($this->input->post('head')[2])){
					echo json_encode(array('error' => 'Something went wrong. Empty BUHEAD.'));
					die();
				}
			}else if($this->input->post('type') == 7){
				if(empty($this->input->post('head')[4])){
					echo json_encode(array('error' => 'Something went wrong. Empty FASHEAD.'));
					die();
				}else if(empty($this->input->post('head')[3])){
					echo json_encode(array('error' => 'Something went wrong. Empty GHEAD.'));
					die();
				}
			}
		}
		//end
	
		$data = $this->input->post('data');
		$type = $this->input->post('type');
		$head = $this->input->post('head');
		$cat = $this->input->post('category');
		$vrdhead = $this->input->post('vrdhead');

		if(($type == 7) ||($type == 2) || ($type == 11)){
			$catencode =  join('|',$cat);
		}else{
			$catencode = '';
		}
		
		//echo $catencode; die();
		$_info = array(
			'USER_FIRST_NAME' => $data['fn'],
			'USER_MIDDLE_NAME' => $data['mn'],
			'USER_LAST_NAME' => $data['ln'],
			'USER_MOBILE' => $data['mo'],
			'USER_EMAIL' => $data['em'],
			'USER_NAME' => $data['log'],
			//'PASSWORD' => $data['pw'],
			'POSITION_ID' => $type,
			'VRDHEAD_ID'=> $head[0],
			'VRDSTAFF_ID'=> $head[1],
			'BUHEAD_ID'=> $head[2],
			'GHEAD_ID'=> $head[3],
			'FASHEAD_ID'=> $head[4],
			'CAT' => $catencode,
			'snd' => $data['se'],
			'VRD' => $this->input->post('vrd'),
			'surl' => base_url(),
			'nvrdh' => $vrdhead

		);
		
		//echo "<pre>";
		//print_r($_info);
		//echo "</pre>";
		//die();
		$result = $this->rest_app->post('index.php/users_admin/new_user',$_info,'');	

		//$this->rest_app->debug();
		//echo "<pre>";
		//print_r($result);
		//echo "</pre>";
		//die();
		if($result[0] == "exist"){
			echo json_encode($result);
			return;
		}
		
		echo json_encode($result);
		//return;
		//var_dump($info['snd']);
	}


	public function searchuser()
	{	
	
		$data['SEARCH'] = $this->input->post('_search');
		$data['TYPE'] = $this->input->post('_type');
		$data['START'] = $this->input->post('_start');
		$data['S_TYPE'] = $this->input->post('s_type');
		$data['SORT'] = $this->input->post('_sort');
		$result = $this->rest_app->get('index.php/users_admin/searchuser',$data,'');
/*
		var_dump($result->data[0]);
		return;
		
*/
		for($i = 0; $i < count($result->data); $i++){
			
			if($result->data[$i]->POSITION_ID == 2 || $result->data[$i]->POSITION_ID == 11){
				$result->data[$i]->IS_INVITER = TRUE;
			}else{
				$result->data[$i]->IS_INVITER = FALSE;
			}

			if(empty($result->data[$i]->LAST_ATTEMPT)){//$result[$i]->LAST_ATTEMPT == null){
				$result->data[$i]->LAST_ATTEMPT = NULL;
				continue;
			}

			$result->data[$i]->LAST_ATTEMPT = date("l F j, Y g:i:s a",$result->data[$i]->LAST_ATTEMPT);
		}

		echo json_encode($result);
		
	}

	public function get_catuser()
	{

		$data['USER_ID'] = $this->input->post('data');

		$result = $this->rest_app->get('index.php/users_admin/searchcat',$data,'');

		echo json_encode($result);


	}	


	public function save_user_edit()
	{

		$data = $this->input->post('data');
		$type = $this->input->post('type');
		$head = $this->input->post('head');
		$cat = $this->input->post('category');
		$vrdhead = $this->input->post('vrdhead');

		
		if(($type == 7) ||($type == 2) || ($type == 11))
		{
		$catencode =  join('|',$cat);
		}else{
			$catencode = '';
		}

		$_info = array(
			'USER_FIRST_NAME' => $data['fn'],
			'USER_MIDDLE_NAME' => $data['mn'],
			'USER_LAST_NAME' => $data['ln'],
			'USER_MOBILE' => $data['mo'],
			'USER_EMAIL' => $data['em'],
			'USER_ID' => $data['ui'],
			'PASSWORD' => $data['pw'],
			'USER_STATUS' => $data['user_status'],
			'POSITION_ID' => $type,
			'VRDHEAD_ID'=> $head[0],
			'VRDSTAFF_ID'=> $head[1],
			'BUHEAD_ID'=> $head[2],
			'GHEAD_ID'=> $head[3],
			'FASHEAD_ID'=> $head[4],
			'CAT' => $catencode,
			'VRD' => $this->input->post('vrd'),
			'nvrdh' => $vrdhead
			);





		$result = $this->rest_app->post('index.php/users_admin/edit_new_user',$_info,'');	
		echo json_encode($result);
	}

	public function edit_user_info()
	{
		$data['USER_ID'] = $this->input->post('uid');
		$result = $this->rest_app->get('index.php/users_admin/view_user_info',$data,'');

		echo json_encode($result);
	}

	public function view_pending()
	{
		$data['USER_ID'] = $this->input->post('uid');
		$result = $this->rest_app->get('index.php/users_admin/view_pending_records',$data,'');
		echo json_encode($result);
	}

	
	//
	// Uploader
	//

	public function uploader_view(){
		if ($this->session->userdata('logged_in') != 1 || $this->session->userdata('position_id') != 1) die('Access is forbidden.');
		$this->load->view('maintenance/uploader_user_csv');
	}
	
	public function upload_file(){
		$this->load->helper('file');
		@delete_files('public/upload_files/user_csv');
		
		$results = array();
		
		$config['upload_path']          = 'public/upload_files/user_csv/';
		$config['allowed_types']        = '*';

		$this->load->library('upload', $config);
		
		foreach($_FILES as $file_name => $value){
			
			if ( ! $this->upload->do_upload($file_name))
			{
				$results[$file_name]['output_message'] = $this->upload->display_errors();
				$results[$file_name]['error'] = TRUE;
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				
				if( ! empty($data)){
					$results[$file_name]['output_message'] = 'Success! File has been uploaded.';
					
					$csv_array = $this->csv_to_array($data['upload_data']['full_path'], $file_name);
					
					if( ! empty($csv_array['message'])){
						$results[$file_name]['output_message'] = $csv_array['message'];
						$results[$file_name]['error'] = TRUE;
					}else{
						$results[$file_name]['csv_array'] = $csv_array;
						$results[$file_name]['error'] = FALSE;
					}
					
				}else{
					$results[$file_name]['output_message'] = 'Failed! Something went wrong.';
					$results[$file_name]['error'] = TRUE;
				}
			}
		}
		
		//Separate VRD head and staff
		if( ! empty($results['users']) &&  ! empty($results['vrd']) ){
			foreach($results['vrd']['csv_array'] as $key => $value){
				$results['vrd']['csv_array'][$key]['NAME'] = strtoupper(trim($value ['NAME']));
				if(strtoupper(trim(@$value['RESPONSIBILITY'])) == 'VRD HEAD' || strtoupper(trim(@$value['RESPONSIBILTY'])) == 'VRD HEAD'){
					$results['users']['vrd_head'][] =  trim($value['ADID']);
					//$results['vrd']['csv_array'][$key]['position_id'] = 5; //vrd head
				}else if(strtoupper(trim(@$value['RESPONSIBILITY'])) == 'VRD STAFF' || strtoupper(trim(@$value['RESPONSIBILTY'])) == 'VRD STAFF'){
					$results['users']['vrd_staff'][] = trim($value['ADID']);
					//$results['vrd']['csv_array'][$key]['position_id'] = 4; //vrd staff
				}
			}
		}
		
		// Convert Assigned Categories to array
		if( ! empty($results['users'])){
			foreach($results['users']['csv_array'] as $key => $value){
				if( ! empty(trim($value['DEPARTMENT / CATEGORY']))){
					$results['users']['csv_array'][$key]['DEPARTMENT / CATEGORY'] = explode(',', $value['DEPARTMENT / CATEGORY']);
					$results['users']['csv_array'][$key]['DEPARTMENT / CATEGORY'] = array_map('trim', $results['users']['csv_array'][$key]['DEPARTMENT / CATEGORY']);
				}
			}
		}
		
		// List all Inviters ID
		$inviters_id_array = array();
		if( ! empty($results['users'])){
			foreach($results['users']['csv_array'] as $key => $value){
				if( ! in_array(trim($value['ADID3']), $inviters_id_array) && ! empty(trim($value['ADID3']))){
					$inviters_id_array[] = trim($value['ADID3']);
				}
			}
		}
		
		$inviters_data = array();
		$checked_approver = array();
		$checked_ghead = array();
		$counter = 0;
		if( ! empty($inviters_id_array)){
			foreach($inviters_id_array as $value){
				
				if( ! array_key_exists(trim($value), $inviters_data)){
					$email = '';
					$name  = '';
					$approvers = array();
					$ghead		= array();
					$categories = array();
					$position_id = '';
					$vendor_type = '';
					$error_message = '';
					$check_if_inviter_inactive = $this->rest_app->get('index.php/users_admin/get_credentials',array('USER_NAME' => $value),'');
					
					
					if(! empty($check_if_inviter_inactive)){
						if($check_if_inviter_inactive[0]->USER_STATUS == 0){
							$error_message = $value . ' Inactive Inviter ADID already exists.';
						}else{
							$error_message = $value . ' Inviter ADID already exists.';
						}
					}
					
					$error_message_temp = '';
					foreach($results['users']['csv_array'] as $key => $value2){
									
						if(trim($value2['ADID3']) == trim($value)){
							$name = strtoupper(trim($value2['INVITER']));
							$email = trim($value2['E-MAIL ADDRESS3']);
							$vendor_type_str = strtoupper(trim($value2['VENDOR TYPE']));
							
							if(strtoupper(trim($value2['GROUP HEAD'])) == 'N/A' && (
							$vendor_type_str == 'NON TRADE' || $vendor_type_str == 'NON-TRADE')){
								$position_id = 11;	
								$vendor_type = 3;
							}else if($vendor_type_str == 'TRADE OUTRIGHT' || 
										$vendor_type_str == 'TRADE STORE CONSIGNOR' ){
								$position_id = 2;	
								$vendor_type = 1;
							}else if(($vendor_type_str == 'NON-TRADE' || $vendor_type_str == 'NON TRADE') && in_array('FIXED ASSETS AND SUPPLIES (FAS)', $value2['DEPARTMENT / CATEGORY'])){
								$position_id = 7;	
								$vendor_type = 2;
							}else{
								$error_message_temp = 'Something went wrong. Can\'t find Vendor Type. ' . $vendor_type_str . ' ';
							}
							
							if($vendor_type == 1 || $vendor_type == 3){
								$approvers = array(
									'name'	=> strtoupper(trim($value2['APPROVER'])),
									'email'	=> trim($value2['E-MAIL ADDRESS1']),
									'adid'	=> trim($value2['ADID1']),
									'position_id'	=> 3
								);
							}else if($vendor_type == 2){
								$approvers = array(
									'name'	=> strtoupper(trim($value2['APPROVER'])),
									'email'	=> trim($value2['E-MAIL ADDRESS1']),
									'adid'	=> trim($value2['ADID1']),
									'position_id'	=> 9
								);

								$ghead = array(
									'name'	=> strtoupper(trim($value2['GROUP HEAD'])),
									'email'	=> trim($value2['E-MAIL ADDRESS2']),
									'adid'	=> trim($value2['ADID2']),
									'position_id'	=> 8
								);
								
								if(trim($value2['ADID2']) == $value2['ADID1']){
									$error_message_temp = 'Duplicate. Approver and GHEAD have same ADID';
								}else if(empty($value2['ADID2'])){
									$error_message_temp = 'No GHEAD Found.';
								}else if(empty($value2['ADID1'])){
									$error_message_temp = 'No Approver Found.';
								}

							}
							
							//Get all assigned categories
							foreach($results['users']['csv_array'] as $key3 => $value3){
								if(trim($value3['ADID3']) == trim($value)){
									if( ! empty($value3['DEPARTMENT / CATEGORY']) &&is_array($value3['DEPARTMENT / CATEGORY'])){
										if(empty($categories)){
											$categories = $value3['DEPARTMENT / CATEGORY'];
										}else{
											$categories = array_merge($categories, $value3['DEPARTMENT / CATEGORY']);
										}
									}
								}
							}
							
							break;
							
						}
					}
					$error_message .= ' ' . $error_message_temp;
					if( ! empty($approvers)){
						
						if( ! in_array($approvers['adid'], $checked_approver)){
							$check_if_approver_inactive = $this->rest_app->get('index.php/users_admin/get_credentials',array('USER_NAME' => $approvers['adid']),'');
							
							if( ! empty($check_if_approver_inactive)){
								
								if($check_if_approver_inactive[0]->USER_STATUS == 0){
									$error_message .= ' '. $approvers['adid'] .' Inactive Approver ADID already exists.';
									$checked_approver[$approvers['adid']] = ' '. $approvers['adid'] .' Inactive Approver ADID already exists.';
								}else{
									/*$error_message .= ' ' . $approvers['adid'] . ' Approver ADID already exists.';
									$checked_approver[$approvers['adid']] = ' '. $approvers['adid'] .' Approver ADID already exists.';*/
								}
								$checked_approver[$approvers['adid']] = false;
							}else{
								
								$checked_approver[$approvers['adid']] = false;
							}
							
						}else{
							if(! empty($checked_approver[$approvers['adid']])){
								$error_message .= $checked_approver[$approvers['adid']];
							}
						}
					}else{
						$error_message .= 'No Approver found.';
					}
					
					if( ! empty($ghead)){
						if( ! array_key_exists($ghead['adid'], $checked_ghead)){
							
							$check_if_approver_inactive = $this->rest_app->get('index.php/users_admin/get_credentials',array('USER_NAME' => $ghead['adid']),'');
							if( ! empty($check_if_approver_inactive)){
								if($check_if_approver_inactive[0]->USER_STATUS == 0){
									$error_message .= ' '. $ghead['adid'] .' GHEAD ADID already exists.';
									$checked_ghead[$ghead['adid']] = ' '. $ghead['adid'] .' Inactive GHEAD ADID already exists.';
								}else{
									/*$error_message .= ' ' . $ghead['adid'] . ' GHEAD ADID already exists.';
									$checked_ghead[$ghead['adid']] =  ' ' . $ghead['adid'] . ' GHEAD ADID already exists.';*/
								}
							}
						}else{
							if(! empty($checked_ghead[$ghead['adid']])){
								$error_message .= $checked_ghead[$ghead['adid']];
							}
						}
					}
					
					$inviters_data[$counter] = array(
						'approver'		=> $approvers,
						'ghead'			=> $ghead,
						'categories'	=> array_unique($categories),
						'email'			=> $email,
						'adid'			=> trim($value),
						'name'			=> $name,
						'position_id'	=> $position_id,
						'vendor_type'	=> $vendor_type,
						'error'			=> trim($error_message)
					);
					$counter++;
				}
			}
		}
		
		if( ! empty($inviters_data)){
			$results['users']['csv_array'] = $inviters_data;
		}
		
		/*//Add Category
		
		$upload_result;
		if( ! empty($results['categories'])){
			$upload_result['categories']['error']   = $results['categories']['error'];
			$upload_result['categories']['output_message']  = $results['categories']['output_message'];
			$upload_result['categories']['result'] = $this->uploader_add_category_batch($results['categories']['csv_array']);
		}
		//Add VRD
		
		echo json_encode($results['vrd']['csv_array']);
		die();
		if( ! empty($results['vrd'])){
			$upload_result['vrd']['error']   = $results['vrd']['error'];
			$upload_result['vrd']['output_message']  = $results['vrd']['output_message'];
			$upload_result['vrd']['result'] = $this->save_user_new_batch($results['vrd']['csv_array']);
		}
		
		//Add Users */
		
		echo json_encode($results);
	}
	
	
	public function save_user_new_batch($data){
		//For Uploader only
		/*if( ! empty($this->input->post('uploader')) && $this->input->post('uploader') == 'true'){
			//Get the ID's of Approver and GHEAD, VRD HEAD, VRD STAFF
			$uploader_data = array(
				'head'	=> $this->input->post('head'),
				'vrdhead'	=> $this->input->post('vrdhead'),
				'vrd'	=> $this->input->post('vrd'),
				'category'	=> $this->input->post('category'),
				'vendor_type_id'	=> $this->input->post('vendor_type_id'),
				'user_id'		=> $this->session->userdata['user_id']
			);
			$result = $this->rest_app->get('index.php/users_admin/get_uploader_data_id',$uploader_data,'');	
			
			$_POST['head'] = $result->head;
			$_POST['vrd'] = $result->vrd;
			$_POST['vrdhead'] = $result->vrdhead;
			$_POST['category'] = $result->category;
		}*/
		//end
		
		$data['surl'] = base_url();
		$result = $this->rest_app->post('index.php/users_admin/new_user',$_info,'');	

		return $result;
	}
	
	public function uploader_add_category_batch($data)
	{
		$data['csv_array'] = $data;
		$data['user_id'] = $this->session->userdata['user_id'];
		
		$result = $this->rest_app->post('index.php/category_form/uploader_create_category_batch/', $data, '');	
		return $result;
	}

	
	public function csv_to_array($file_path, $file_name){
		$items = array();
		try {
			$file = fopen($file_path,"r");
			$headers = array();
			$row = 0;

			while(!feof($file))
			{

				$row++;
				$values = fgetcsv($file);
				
				// Error on json_encode: Malformed UTF-8 characters, possibly incorrectly encoded
				// Convert to UT8
				// Src: https://stackoverflow.com/a/46305914
				if(is_array($values) && ! empty($values)){					
					foreach($values as $key => $value){
						$values[$key] = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
					}
				}
				
				if($file_name == 'users'){
					unset($values[0]);
				}
				
				if($row == 1){
					$headers = $values;
					
					/*foreach($headers as $k => $v){
						$headers[$k] = str_replace(' ','_',$v);
					}*/
					
					if($file_name == 'users'){
						$headers[2] = 'ADID1'; // Approver BUHEAD or FASHEAD
						$headers[5] = 'ADID2'; // GHEAD
						$headers[8] = 'ADID3'; // INVITER
						$headers[3] = 'E-MAIL ADDRESS1'; // Approver BUHEAD or FASHEAD
						$headers[6] = 'E-MAIL ADDRESS2'; // GHEAD
						$headers[9] = 'E-MAIL ADDRESS3'; // INVITER
					}
				}
				
				if ($values && $row != 1 ) {
					
					if (count($values) > count($headers)) {
						$values = array_slice ($values, 0, count($headers) -1, true);
					}
					
					array_push($items, array_combine($headers, $values));
				}

			}
			
			fclose($file);

		} catch (Exception $e) {
			# TODO: LOG EXCEPTION, AND UPDATE SMNTP_BD_FILE_QUEUE TABLE
			$data['message'] = 'Caught exception: '.  $e->getMessage(). "\n";
			return $data;
		}
		
		return $items;
	}
	
	
	public function add_category(){
		
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'CATEGORY_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'BUSINESS_TYPE' => $n[2],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/category_form/create_category/', $data, '');	
		echo json_encode($result);
	}
	
	public function export_data(){
		
	}

	public function resend_email(){

		

		$user_inf = array(
			'USER_ID' => $this->input->post('uid'),
			'USER_NAME' => $this->input->post('uname'),
			'burl'	=> $this->input->post('burl')
		);


		$result = $this->rest_app->post('index.php/users_admin/resend_email',$user_inf,'');	

		echo json_encode($result);
	}
	
	public function unlock_account(){
		$data = $this->input->post();
		$result = $this->rest_app->post('index.php/users_admin/unlock_account',$data,'');	
		echo $result;
	}
	
	public function get_unlock_account_logs(){
		$data = $this->input->get();
		$result = $this->rest_app->get('index.php/users_admin/get_unlock_account_logs',$data,'');	
		$table_str ='';
		if($result === FALSE){
			$table_str = 'Table SMNTP_USERS_TOKENS_LOGS does not exists';
		}else{
			foreach($result as $val){
				$table_str .= '<tr>';
				$table_str .= '<td>' . $val->DATE_UNLOCKED_FORMATTED .'</td>';
				$table_str .= '<td>' . $val->REASON . '</td>';
				$table_str .= '</tr>';
			}
		}
		echo $table_str;
	}
	
	public function get_user_status_history_body(){
		$data = $this->input->get();
		$result = $this->rest_app->get('index.php/users_admin/get_user_status_history_body',$data,'');
		$table_str ='';
		if($result === FALSE){
			$table_str = 'Table SMNTP_USERS_STATUS_LOGS does not exists';
		}else{
			foreach($result as $val){
				$table_str .= '<tr>';
				$table_str .= '<td>' . $val->USER_STATUS .'</td>';
				$table_str .= '<td>' . $val->DATE_MODIFIED . '</td>';
				$table_str .= '</tr>';
			}
		}
		echo $table_str;
	}
	
	public function get_resend_logs(){
		$data = $this->input->get();
		$result = $this->rest_app->get('index.php/users_admin/get_resend_logs',$data,'');
		
		$output['total'] = 0;
			$table_str ='';
		if($result === FALSE){
			$table_str = 'Table SMNTP_USERS_TOKENS_LOGS does not exists';
		}else{
			$output['total'] = count($result);
			foreach($result as $val){
				$table_str .= '<tr ' . ($val->RESULT == 0 ? 'class="danger"' : '') . '>';
				$table_str .= '<td>' . $val->TOKEN . '</td>';
				$table_str .= '<td>' . $val->RESEND_DATE_FORMATTED .'</td>';
				$table_str .= '<td>' . ($val->RESULT == 1 ? 'Success' : 'Failed') . '</td>';
				$table_str .= '</tr>';
			}
		}
		$output['table'] = $table_str;
		echo json_encode($output);
	}	
}
