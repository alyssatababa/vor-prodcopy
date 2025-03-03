<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class Common_model extends CI_Model
{

	function get_similar_list($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_INVITE');
		if (!empty($data['vendorname']))
			$this->db->where("upper(VENDOR_NAME) like upper(%" . $this->db->escape_like_str($data['vendorname']) . "%)");

		if (!empty($data['contact_person']))
			$this->db->or_where("upper(CONTACT_PERSON) like upper(%" . $this->db->escape_like_str($data['contact_person']) . "%)");

		if (!empty($data['email']))
			$this->db->or_where("upper(EMAIL) like upper(%" . $this->db->escape_like_str($data['email']) . "%)");

		$query = $this->db->get();

		return $query->result_array();
	}

	function get_next_process($data)
	{
		$next_status	= '';
		$next_position	= '';
		$business_type	= '';

		$last_cycle_approver = '';
		$reg_type_id = isset ($data['reg_type_id']) ? $data['reg_type_id'] : 1;

		$this->db->select('*');
		$this->db->from('SMNTP_STATUS_CONFIG');
		$this->db->where('CURRENT_STATUS_ID', $data['status']);
		$this->db->where('CURRENT_POSITION_ID', $data['position_id']);
		$this->db->where('TYPE_ID', $data['type']); // registration = 1
		$this->db->where('REGISTRATION_TYPE_ID', $reg_type_id); // 1 = new, 2 = from oracle

		if (!empty($data['business_type'])){
			$this->db->where('BUSINESS_TYPE', $data['business_type']);
		}
		
		if(!empty($data['approval_type'])){
			$this->db->where('BUSINESS_TYPE', NULL);
		}

		$query = $this->db->get();
		// echo $this->db->last_query(); exit;
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$next_position = $row->NEXT_POSITION_ID;
				$business_type = $row->BUSINESS_TYPE;

				$last_cycle_approver = $row->LAST_CYCLE_ACTION;

				if ($row->APPROVAL_PROCESS_FLAG == 0)
					$next_status = $row->NEXT_STATUS_ID;
				else
				{
					if (array_key_exists('action', $data))
					{
						if ($data['action'] == 3) // approve
							$next_status = $row->APPROVE_STATUS_ID;
						elseif ($data['action'] == 4) // reject
						{
							$next_status = $row->REJECT_STATUS_ID;
							$next_position = $row->REJECT_POSITION_ID;
						}
					}

				}


			}
		}

		$var['next_status']			= $next_status;
		$var['next_position']		= $next_position;
		$var['business_type']		= $business_type;
		$var['last_cycle_approver']	= $last_cycle_approver;

		return $var;
	}

	function send_email_notification($data)
	{

		$output = false;
		
		$this->load->helper('message_helper');
		$this->load->helper('file');
		$email_recipients = email_from();
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->clear(true);
		// Modified MSF - 20191108 (IJR-10617)
		//$this->email->from($email_recipients['from']);
		$this->email->from($email_recipients['from'], $email_recipients['sender_alias']);
		$this->email->to($data['to']);

		$data['bcc'] = $email_recipients['bcc'];

		$has_gmail = false;
		if (array_key_exists('cc', $data)) {
			$this->email->cc($data['cc']);			
			if (strpos($data['cc'], 'gmail.com') !== false)
				$has_gmail = true;
		}
		if (array_key_exists('bcc', $data)){
			$this->email->bcc($data['bcc']);
			if (strpos($data['bcc'], 'gmail.com') !== false)
				$has_gmail = true;
		}

		if (array_key_exists('attach', $data))
		{
			for ($i=0; $i < count($data['attach']); $i++)
			{
				$this->email->attach($data['attach'][$i]);
			}
		}

		if (array_key_exists('to', $data)) {
			if (strpos($data['to'], 'gmail.com') !== false)
				$has_gmail = true;

			// trim subject if gmail exists as a recepient
			if ($has_gmail==true) {
				$ellipsis = strlen($data['subject'])>58 ? "..." : "";
				$this->email->subject(substr($data['subject'],0,57).$ellipsis);
			} else {
				$this->email->subject($data['subject']);
			}
			$data['content'] = str_replace('<br />',"",$data['content']);
			$data['content'] = htmlspecialchars($data['content']);			
			$data['content'] = nl2br($data['content']);
			$data['content'] = htmlspecialchars_decode($data['content']);
			$this->email->message($data['content']);
			$output =$this->email->send();

			$email_logs = $this->db->select('CONFIG_VALUE')->from('SMNTP_SYSTEM_CONFIG')->where(array('CONFIG_NAME' => 'sent_email_logs'))->get();
			$email_logs = $email_logs->row();
			$email_logs = $email_logs->CONFIG_VALUE;

			if($email_logs == 1){

			$logs = $this->email->print_debugger();
			$logs .= "\n";
			$path =  APPPATH.'/logs/email/'.date('Y-M-d').'.txt';		

			if(file_exists($path))
			{
			    write_file($path, $logs, 'a');
			}
			else
			{
			    write_file($path, $logs);
			}

			}	



	
		}

		$data['attach'] = null;
		$data['attach'] = array();
		return $output;
	}

	function get_from_table_where_array($sTable, $sWhatField, $WhereArray, $sConcatField = false, $escapeWhereField = true)
    { # get single item from a database
		$this->db->_protect_identifiers=false;
    	if (!$sConcatField)// sConcatField is for fields like these concat(field1,field2) as sConcatField
    		$sConcatField = $sWhatField;

    	$Value = '';
    	$this->db->start_cache();
    	$this->db->from($sTable);
			if ($escapeWhereField)
				$this->db->where($WhereArray);
			else
				$this->db->where($WhereArray, '', $escapeWhereField);

			$this->db->stop_cache();
			$cnt = $this->db->count_all_results(); #try to retrieve count first

			if ($cnt>0)
				{
					$this->db->select($sWhatField);	#add select statement
					$query = $this->db->get(); # run query
					$row = $query->row_array();
					$Value = $row[$sConcatField];
				}

			$this->db->flush_cache();
			return $Value;

    }

	function token_info($token)
	{
		$this->db->select('SVT.*, SC.*, SVI.CREATED_BY, SVI.VENDOR_NAME, SVI.REGISTRATION_TYPE');
		$this->db->from('SMNTP_VENDOR_TOKEN SVT');
		$this->db->join('SMNTP_CREDENTIALS SC', 'SC.USER_ID = SVT.USER_ID');
		$this->db->join('SMNTP_VENDOR_INVITE SVI', 'SC.USER_ID = SVI.USER_ID');
		$this->db->where('SVT.TOKEN', $token);
		$this->db->where('SVT.ACTIVE', 1);

		$query = $this->db->get();

		$var['resultcount'] = $query->num_rows();
		$var['query'] = $query->result_array();

		return $var;
	}
	
	function user_token_info($token)
	{
		$this->db->select('SUT.*, SC.*');
		$this->db->from('SMNTP_USERS_TOKENS SUT');
		$this->db->join('SMNTP_CREDENTIALS SC', 'SC.USER_ID = SUT.USER_ID');
		$this->db->where('SUT.TOKEN', $token);
		$this->db->where('SUT.ACTIVE', 1);

		$query = $this->db->get();

		$var['resultcount'] = $query->num_rows();
		$var['query'] = $query->result_array();

		return $var;
	}


   function reset_password($data)
   {
		$this->load->library('CryptManager');
		//$user_info = $this->db->get_where('SMNTP_CREDENTIALS', array('USERNAME' => $data['username']))->row_array();
		
		//$this->db->select('CONCAT(DATE_FORMAT(TIME_STAMP,"%d-"),UCASE(DATE_FORMAT(TIME_STAMP,"%b")),DATE_FORMAT(TIME_STAMP,"-%y %h.%i.%s.%f %p")) AS TIME_STAMP');
		//$this->db->from('SMNTP_CREDENTIALS');
		//$this->db->where('USERNAME', $data['username']);
		//$user_info = $this->db->get();
		
		$user_info = $this->db->query('SELECT CREDENTIAL_ID,USER_ID,USERNAME,PASSWORD,CONCAT(DATE_FORMAT(TIME_STAMP,"%d-"),UCASE(DATE_FORMAT(TIME_STAMP,"%b")),DATE_FORMAT(TIME_STAMP,"-%y %h.%i.%s.%f %p")) AS TIME_STAMP,DEACTIVATED_FLAG FROM SMNTP_CREDENTIALS WHERE USERNAME = ?', array($data['username']))->row_array();
		$date_timestamp = $user_info['TIME_STAMP'];
		
		$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $date_timestamp . $data['user_id'])),0,16);
		$enc_password = $this->cryptmanager->encrypt($data['password'], FALSE, $file_crypt_key);
				
		$record = array(
			'PASSWORD' => $enc_password
		);

		$this->db->where('USER_ID', $data['user_id']);
   		$this->db->update('SMNTP_CREDENTIALS', $record);

   		return $this->db->affected_rows();
   }

   function deactive_token($token)
   {
   		$record = array(
					'ACTIVE' => 0
				);

		$this->db->where('TOKEN', $token);
   		$this->db->update('SMNTP_VENDOR_TOKEN', $record);
   }
   function deactive_user_token($token)
   {
   		$record = array(
					'ACTIVE' => 0
				);

		$this->db->where('TOKEN', $token);
   		$this->db->update('SMNTP_USERS_TOKENS', $record);
   }


   function update_table($table_name, $record_arr, $where_arr)
   {
   		$this->db->where($where_arr);
   		$this->db->update($table_name, $record_arr);

   		return $this->db->affected_rows();
   }

   function delete_table($table_name, $where_arr)
   {
   		$this->db->where($where_arr);
   		$this->db->delete($table_name);

   		return $this->db->affected_rows();
   }

   function insert_table($table_name, $record_arr)
   {
   		$this->db->insert($table_name, $record_arr);

   		return $this->db->affected_rows();
   }

   function insert_table_batch($table_name, $record_arr)
   {
   		$this->db->insert_batch($table_name, $record_arr);

   		return $this->db->affected_rows();
   }

   function get_email_template($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_EMAIL_DEFAULT_TEMPLATE');
		$this->db->where($data);

		$result = $this->db->get();

		return $result;
	}

	function get_message_default($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_MESSAGE_DEFAULT');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_position_name($position_id)
	{
		$this->db->select('POSITION_NAME');
		$this->db->from('SMNTP_POSITION');
		$this->db->where('POSITION_ID', $position_id);

		$query = $this->db->get();

		return $query->row(0)->POSITION_NAME;

	}
	
	function get_vendor_id($vendor_invite_id){
		$this->db->select('VENDOR_ID');
		$this->db->from('SMNTP_VENDOR');
		$this->db->where('VENDOR_INVITE_ID', $vendor_invite_id);

		$query = $this->db->get();

		return $query->row(0)->VENDOR_ID;
	}
	
	function get_change_pass_response($user_id, $old_password, $new_password){
		$this->load->library('CryptManager');
		
		$rs = $this->db->query('SELECT USER_ID, PASSWORD ,CONCAT(DATE_FORMAT(TIME_STAMP,"%d-"),UCASE(DATE_FORMAT(TIME_STAMP,"%b")),DATE_FORMAT(TIME_STAMP,"-%y %h.%i.%s.%f %p")) AS TIME_STAMP FROM SMNTP_CREDENTIALS WHERE USER_ID = ?', array($user_id))->result_array();
		
		//$this->db->select('USER_ID, PASSWORD, TIME_STAMP');
		//$this->db->from('SMNTP_CREDENTIALS');
		//$this->db->where('USER_ID', $user_id);
		//$rs = $this->db->get();
		$rows = count($rs);
		if ($rows > 0) {
			$db_user_id = $rs[0]['USER_ID'];
			$db_password = $rs[0]['PASSWORD'];
			$db_time_stamp = $rs[0]['TIME_STAMP'];
			
			$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $db_time_stamp . $db_user_id)),0,16);
			$dec_password = base64_decode($this->cryptmanager->decrypt($db_password, FALSE, $file_crypt_key));
			$enc_password = $this->cryptmanager->encrypt($new_password, FALSE, $file_crypt_key);
			
			if($dec_password == $old_password){ // Password Match
				$record = array(
					'PASSWORD' => $enc_password
				);
				$this->db->where('USER_ID', $user_id);
				$this->db->update('SMNTP_CREDENTIALS', $record);
				$err_code = '1';
			}else{
				$err_code = '2';
			}
		}else{
			$err_code = '3';
		}
		
		$data['err_code'] = $err_code;

		return $data['err_code'];
	}
	
	function get_change_email_response($user_id, $old_password, $new_password){
		
		$this->db->select('USER_ID, USER_EMAIL');
		$this->db->from('SMNTP_USERS');
		$this->db->where('USER_ID', $user_id);
		$rs = $this->db->get();
		$rows = $rs->num_rows();
		if ($rows > 0) {
				$record = array(
					'EMAIL' => $new_password
				);
				
				$record_two = array(
					'USER_EMAIL' => $new_password
				);
			
				$this->db->where('USER_ID', $user_id);
				$this->db->update('SMNTP_VENDOR_INVITE', $record);
				
				$this->db->where('USER_ID', $user_id);
				$this->db->update('SMNTP_USERS', $record_two);
				$err_code = '1';
		}else{
			$err_code = '2';
		}
		
		$data['err_code'] = $err_code;

		return $data['err_code'];
	}

	function get_userdata_validation($user_data)
	{
		$this->db->select('U.USER_ID, C.USERNAME, U.USER_EMAIL, (U.USER_FIRST_NAME || \' \' || U.USER_MIDDLE_NAME || \' \' || U.USER_LAST_NAME) AS USERS_NAME');
		$this->db->from('SMNTP_USERS U');
		$this->db->join('SMNTP_CREDENTIALS C', 'U.USER_ID = C.USER_ID', 'INNER');
		$this->db->where('C.USERNAME', $user_data);
		// $this->db->or_where('U.USER_EMAIL', $user_data);
		$rs = $this->db->get();

		$rows = $rs->num_rows();

		if ($rows > 0) {
			$err_code = '1';
			$user_id = $rs->row()->USER_ID;
			$username = $rs->row()->USERNAME;
			$users_name = $rs->row()->USERS_NAME;
			$user_email = $rs->row()->USER_EMAIL;
		}
		else {
			$err_code = '2';
			$user_id = '';
			$username = '';
			$users_name = '';
			$user_email = '';
		}

		$data['user_id'] = $user_id;
		$data['username'] = $username;
		$data['users_name'] = $users_name;
		$data['user_email'] = $user_email;
		$data['err_code'] = $err_code;

		return $data;
	}

	function create_token($user_id)
	{
		$token = md5( microtime() );

		$record = array(
			'TOKEN' => $token,
			'USER_ID' => $user_id
		);

		$this->db->insert('SMNTP_USERS_TOKENS', $record);

		return $token;
	}

	function get_users_name($user_id)
	{
		$this->db->select('(USER_FIRST_NAME || \' \' || USER_MIDDLE_NAME || \' \' || USER_LAST_NAME) AS USERS_NAME');
		$rs = $this->db->get_where('SMNTP_USERS', array('USER_ID' => $user_id));
		$users_name = $rs->row()->USERS_NAME;

		return $users_name;
	}
	
	function get_username($user_id)
	{
		$this->db->select('USERNAME');
		$rs = $this->db->get_where('SMNTP_CREDENTIALS', array('USER_ID' => $user_id));
		$usersname = $rs->row()->USERNAME;

		return $usersname;
	}


	function is_token_active($token)
	{
		$rs = $this->db->get_where('SMNTP_USERS_TOKENS', array('TOKEN' => $token, 'ACTIVE' => 1));

		return ($rs->num_rows() > 0) ? '0' : '1';
	}

	function deactivate_token($user_id)
	{
		$record = array(
			'ACTIVE' => 0
		);

		// Deactivate all token under a user to avoid multiple available tokens Since a user should only update his/her password once
		$this->db->where('USER_ID', $user_id);
		$this->db->where('ACTIVE', 1);
		$this->db->update('SMNTP_USERS_TOKENS', $record);
	}

	// Gets status of RFQ/RFB and Vendor
	function get_status()
	{
		$this->db->select('STATUS_ID, STATUS_NAME, STATUS_TYPE');
		$this->db->order_by('STATUS_SORT');
		$rs = $this->db->get_where('SMNTP_STATUS', array('ACTIVE' => 1));

		return $rs->result_array();
	}

	function active_tokens()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_TOKEN');
		$this->db->where('ACTIVE', 1);

		$query = $this->db->get();

		return $query;
	}

	function get_additional_start_date($record){
		$this->db->update('SMNTP_VENDOR_STATUS', $record);
		$this->db->where('STATUS_ID', 190);
		$this->db->or_where('STATUS_ID', 195);
	}

	function select_query($table,$where,$gt, $escape_argument = true){
	
	return	$this->db->select($gt,$escape_argument)->from($table)->where($where)->get()->result_array();

	}

	function select_query_wherein($table,$whr,$wherein,$gt){

		$this->db->select($gt);
		$this->db->from($table);
		$this->db->where_in($whr,$wherein);
		$rs = $this->db->get();

		return $rs->result_array();

	}

	function select_vendor_info($vndorid){

		$this->db->select('A.VENDOR_INVITE_ID,B.USER_ID,B.CREATED_BY');
		$this->db->from('SMNTP_VENDOR A');
		$this->db->join('SMNTP_VENDOR_INVITE B','B.VENDOR_INVITE_ID = A.VENDOR_INVITE_ID','LEFT');
		$rs = $this->db->where($vndorid)->get();
		return $rs->result_array();

	}

	function select_query_wherein2($whr,$wherein,$whr1,$wherein1){

		$this->db->select('B.INCOMPLETE_REASON,A.REQUIRED_AGREEMENT_NAME');
		$this->db->from('SMNTP_VP_REQUIRED_AGREEMENTS A');
		$this->db->join('SMNTP_INCOMPLETE_DOC_REASONS B','B.DOCUMENT_ID = A.REQUIRED_AGREEMENT_ID','LEFT');
		$this->db->where_in($whr,$wherein);
		$this->db->where_in($whr1,$wherein1);
		$rs = $this->db->get();

		return $rs->result_array();

	}


	function select_query2($wr,$wr1){

		$this->db->select('B.INCOMPLETE_REASON,A.REQUIRED_AGREEMENT_NAME');	
		$this->db->from('SMNTP_VP_REQUIRED_AGREEMENTS A');	
		$this->db->join('SMNTP_INCOMPLETE_DOC_REASONS B','B.DOCUMENT_ID = A.REQUIRED_AGREEMENT_ID','LEFT');
		$this->db->where(array('A.REQUIRED_AGREEMENT_ID' => $wr[0]));
		$this->db->where(array('B.REASON_ID' => $wr1[0]));
		$rs = $this->db->get()->result_array();
		return $rs;
	//return	$this->db->select($gt,$escape_argument)->from($table)->where($where)->get()->result_array();

	}

	function select_vrdstaff_join($wr)
	{
		$this->db->select("A.VRDSTAFF_ID, COALESCE(B.USER_FIRST_NAME,'') AS USER_FIRST_NAME, COALESCE(B.USER_MIDDLE_NAME,'') AS USER_MIDDLE_NAME, COALESCE(B.USER_LAST_NAME,'') AS USER_LAST_NAME,B.USER_EMAIL", FALSE);
		$this->db->from('SMNTP_USERS_MATRIX A');
		$this->db->join('SMNTP_USERS B','B.USER_ID = A.VRDSTAFF_ID','LEFT OUTER');		
		$this->db->where('A.VRDHEAD_ID',$wr);	
		$this->db->distinct();
		$rs = $this->db->get()->result_array();
		
		return $rs;
	}

	function select_vrdstaff_join2($wr)
	{
/*		$this->db->select("A.VRDSTAFF_ID, COALESCE(B.USER_FIRST_NAME,'') AS USER_FIRST_NAME, COALESCE(B.USER_MIDDLE_NAME,'') AS USER_MIDDLE_NAME, COALESCE(B.USER_LAST_NAME,'') AS USER_LAST_NAME,B.USER_EMAIL", FALSE);*/
		
		$this->db->select('VRDSTAFF_ID');
		$this->db->from('SMNTP_USERS_MATRIX');	
		$this->db->where('USER_ID',$wr);
		$this->db->where('VRDSTAFF_ID is NOT NULL');		
		$rs = $this->db->get()->result_array();
		$tmparr = array();

		foreach ($rs as $key => $value) {
			array_push($tmparr, $value['VRDSTAFF_ID']);
		}

		if(count($rs) > 1){
			$this->db->select('USER_ID AS VRDSTAFF_ID,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
			$this->db->from('SMNTP_USERS');
			$this->db->where_in('USER_ID',$tmparr);
		}

		if(count($rs) == 1){
			$this->db->select("USER_ID AS VRDSTAFF_ID,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL");
			$this->db->from('SMNTP_USERS');
			$this->db->where('USER_ID',$tmparr[0]);
		}

		$rst = $this->db->get()->result_array();
		return $rst;
	
	}

	function get_sysdate()
	{
		$query = "SELECT CAST(DATE_FORMAT(NOW(),'%m/%d/%Y %h:%i:%s %p') AS CHAR) AS SYS_DATE";
		$result = $this->db->query($query);

		return $result->row(0)->SYS_DATE;

	}

   function expired_column($column, $expdate, $where)
   {
   		$query = "UPDATE SMNTP_VENDOR_STATUS SET " . $column . " = STR_TO_DATE('" . $expdate . "','%m/%d/%Y') WHERE VENDOR_INVITE_ID = '" . $where . "'";
		$result = $this->db->query($query);

		return $result;
   }

   function update_brand($data)
   {   	
   	$this->db->where($data);
   	$this->db->update('SMNTP_BRAND',array('STATUS' => 1));
   }


   	function select_query_notin($table,$where,$where2,$gt, $escape_argument = true){
	
	return	$this->db->select($gt,$escape_argument)->from($table)->where($where)->where($where2)->distinct()->get()->result_array();

	}


	function select_query_active($items,$table,$where){


		$ls = $this->db->select($items)->from($table)->where($where)->get();

		return $ls;


	}

	function get_config($where){
		return $this->db->get_where('SMNTP_SYSTEM_CONFIG', $where);
	}

	function log_user($data){
		return $this->db->insert('SMNTP_VISITOR_LOGS', $data);
	}

	// Added MSF - 20191126 (IJR-10619)
	function get_category($vendor_invite_id){

		//$this->db->select("CONCAT(SC.CATEGORY_NAME,CONCAT(' - ', SSC.SUB_CATEGORY_NAME)) AS CATEGORY");
		//$this->db->select('SC.CATEGORY_NAME,SSC.SUB_CATEGORY_NAME');
		$this->db->distinct();
		$this->db->select('SC.CATEGORY_NAME');
		$this->db->from('SMNTP_VENDOR_CATEGORIES SVC');
		$this->db->join('SMNTP_CATEGORY SC', 'SVC.CATEGORY_ID = SC.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_SUB_CATEGORIES SVSC', 'SVC.VENDOR_INVITE_ID = SVSC.VENDOR_INVITE_ID AND SC.CATEGORY_ID = SVSC.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_SUB_CATEGORY SSC', 'SVSC.SUB_CATEGORY_ID = SSC.SUB_CATEGORY_ID', 'LEFT');
		$this->db->where('SVC.VENDOR_INVITE_ID', $vendor_invite_id);
		
		//return $query;
		$query = $this->db->get();

		$var['resultcount'] = $query->num_rows();
		$var['query'] = $query->result_array();

		return $var;
		//return $this->db->query($query);
	}

	// Added MSF - 20191126 (IJR-10619)
	function get_avc_category($vendor_invite_id){

		//$this->db->select("CONCAT(SC.CATEGORY_NAME,CONCAT(' - ', SSC.SUB_CATEGORY_NAME)) AS CATEGORY");
		//$this->db->select('SC.CATEGORY_NAME,SSC.SUB_CATEGORY_NAME');
		$this->db->distinct();
		$this->db->select('SC.CATEGORY_NAME');
		$this->db->from('SMNTP_VENDOR_AVC_CAT SVC');
		$this->db->join('SMNTP_CATEGORY SC', 'SVC.CATEGORY_ID = SC.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_AVC_SUB_CAT SVSC', 'SVC.VENDOR_INVITE_ID = SVSC.VENDOR_INVITE_ID AND SC.CATEGORY_ID = SVSC.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_SUB_CATEGORY SSC', 'SVSC.SUB_CATEGORY_ID = SSC.SUB_CATEGORY_ID', 'LEFT');
		$this->db->where('SVC.VENDOR_INVITE_ID', $vendor_invite_id);
		
		//return $query;
		$query = $this->db->get();

		$var['resultcount'] = $query->num_rows();
		$var['query'] = $query->result_array();

		return $var;
		//return $this->db->query($query);
	}
	
	function get_completed_vendors(){
		//$this->db->select('SV.VENDOR_ID, SV.VENDOR_INVITE_ID, SV.VENDOR_NAME');
		$this->db->select('SV.VENDOR_NAME');
		$this->db->from('SMNTP_VENDOR SV');
		$this->db->join('SMNTP_VENDOR_STATUS SVS', 'SV.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID');
		$this->db->where('SVS.STATUS_ID = 19');
		
		//return $query;
		$query = $this->db->get();

		$var['resultcount'] = $query->num_rows();
		$var['query'] = $query->result_array();

		return $var;
	}
	
	
	function get_all_users()
	{
		return $this->db->query('SELECT CREDENTIAL_ID,USER_ID,USERNAME,PASSWORD,CONCAT(DATE_FORMAT(TIME_STAMP,"%d-"),UCASE(DATE_FORMAT(TIME_STAMP,"%b")),DATE_FORMAT(TIME_STAMP,"-%y %h.%i.%s.%f %p")) AS TIME_STAMP,DEACTIVATED_FLAG FROM SMNTP_CREDENTIALS WHERE DEACTIVATED_FLAG = 0')->result_array();
	}
}
?>
