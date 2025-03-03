<?php
class Mail_model extends CI_Model
{
	function get_vendor_subjects($user_id, $user_type_id = 1)
	{
		$this->db->_protect_identifiers=false;
		$this->db->select('(CASE WHEN INVITE.VENDOR_INVITE_ID IS NOT NULL THEN INVITE.VENDOR_INVITE_ID ELSE VENDOR.VENDOR_ID END) AS DATA_ID,
		CONCAT(INVITE.CREATED_BY , \'|\' , MATRIX.VRDSTAFF_ID) AS RECIPIENT_ID,
		(CASE WHEN INVITE.VENDOR_INVITE_ID IS NOT NULL THEN CONCAT(\'Registration - \' , INVITE.VENDOR_NAME) ELSE CONCAT(\'Registration - \' , VENDOR.VENDOR_NAME) END) AS SUBJECT,
		(CASE WHEN INVITE.VENDOR_INVITE_ID IS NOT NULL THEN (\'VENDOR_INVITE\') ELSE (\'VENDOR\') END) AS SUBJECT_TYPE', FALSE);

		$this->db->start_cache();

		$this->db->from('SMNTP_VENDOR_INVITE INVITE');
		$this->db->join('SMNTP_VENDOR VENDOR', 'INVITE.VENDOR_INVITE_ID = VENDOR.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_USERS USERS', 'INVITE.USER_ID = USERS.USER_ID', 'INNER');

		if($user_type_id == 2)
			$this->db->join('SMNTP_USERS_MATRIX MATRIX', 'INVITE.CREATED_BY = MATRIX.USER_ID', 'LEFT');
		else
			$this->db->join('SMNTP_USERS_MATRIX MATRIX', 'INVITE.CREATED_BY = MATRIX.USER_ID', 'INNER');
		
		$this->db->where('MATRIX.VRDSTAFF_ID', $user_id);

		$this->db->stop_cache();

		$rs = $this->db->get();
		$vendors = $rs->result_array();

		$this->db->select('RFQRFB.RFQRFB_ID AS DATA_ID, RFQRFB.CREATED_BY AS RECIPIENT_ID, (CASE WHEN RFQRFB_TYPE.RFQRFB_TYPE_NAME = \'RFQ\' THEN CONCAT(\'RFQ#\' , RFQRFB.RFQRFB_ID , \'- \' , RFQRFB.TITLE) ELSE CONCAT(\'RFB#\' , RFQRFB.RFQRFB_ID , \'- \' , RFQRFB.TITLE) END) AS SUBJECT, (\'RFQ_RFB\') AS SUBJECT_TYPE', FALSE);
		$this->db->join('SMNTP_RFQRFB_INVITED_VENDORS INVITED_VENDORS', 'INVITED_VENDORS.VENDOR_ID = VENDOR.VENDOR_ID', 'INNER');
		$this->db->join('SMNTP_RFQRFB RFQRFB', 'INVITED_VENDORS.RFQRFB_ID = RFQRFB.RFQRFB_ID', 'INNER');
		$this->db->join('SMNTP_RFQRFB_TYPE RFQRFB_TYPE', 'RFQRFB.RFQRFB_TYPE = RFQRFB_TYPE.RFQRFB_TYPE_ID', 'INNER');

		$rs = $this->db->get();
		$rfqrfb = $rs->result_array();

		$subjects = array_merge($vendors, $rfqrfb);
		
		//jay
		//trim pipe 
		foreach($subjects as $k => $d){
			$subjects[$k]['RECIPIENT_ID'] = trim($d['RECIPIENT_ID'], '|'); 
		}
		$temp_subjects = $subjects;
		
		foreach($subjects as $k => $d){
			if( empty($subjects[$k])){
				continue;
			}
			$temp = explode('|', $d['RECIPIENT_ID']);
			
			foreach($temp_subjects as $k2 => $d2){
				//if same
				if($k == $k2 || $d['DATA_ID'] != $d2['DATA_ID'] || $d['SUBJECT'] != $d2['SUBJECT']){
					continue;
				}
				
				$temp2 = explode('|', $d2['RECIPIENT_ID']);
				$matched_id = '';
				//Check id

				foreach($temp2 as $t){
					if( ! in_array($t,$temp)){
						//$matched = true;
						$matched_id .= '|' . $t;
						$temp[] = $t;
					}
				}
				if( ! empty($matched_id)){	
					$subjects[$k]['RECIPIENT_ID'] .= $matched_id;
				}
				unset($subjects[$k2]);
			}
		}
		//jay end
		
		$this->db->flush_cache();
		return $subjects;
	}

	function get_subjects_filter($user_id)
	{
		$this->db->select('(CASE WHEN VENDOR_ID != 0 THEN VENDOR_ID
				WHEN INVITE_ID != 0 THEN INVITE_ID
				WHEN RFQRFB_ID != 0 THEN RFQRFB_ID
				ELSE 0
			END) AS DATA_ID,
			(\'n/a\') AS RECIPIENT_ID,
			SUBJECT,
			(CASE
				WHEN VENDOR_ID != 0 THEN \'vendor\'
				WHEN INVITE_ID != 0 THEN \'invite\'
				WHEN RFQRFB_ID != 0 THEN \'rfqrfb\'
				ELSE \'N/A\'
			END) AS SUBJECT_TYPE', FALSE);
		$this->db->from('SMNTP_MESSAGES');
		$this->db->where('SENDER_ID', $user_id);
		$this->db->or_where('RECIPIENT_ID', $user_id);
		$this->db->group_by('VENDOR_ID, INVITE_ID, RFQRFB_ID, SUBJECT');
		$this->db->distinct();
		$this->db->order_by('SUBJECT','asc');

		$rs = $this->db->get();
	

		return $rs->result_array();
	}

	function get_senders($user_id, $user_type_id = 1,$position_id)
	{



		if($position_id == 10){
			$this->db->select("B.USER_ID,
									CASE WHEN B.USER_LAST_NAME IS NULL THEN B.USER_FIRST_NAME
									WHEN B.USER_MIDDLE_NAME IS NULL THEN CONCAT(B.USER_FIRST_NAME , ' ', B.USER_LAST_NAME)
									ELSE CONCAT(B.USER_FIRST_NAME , ' ', B.USER_MIDDLE_NAME , ' ', B.USER_LAST_NAME)
									END AS USERS_NAME, 
								A.VENDOR_ID",false);
			$this->db->from('SMNTP_MESSAGES A');
			$this->db->join('SMNTP_USERS B','A.SENDER_ID = B.USER_ID');	
		}else{
			$this->db->select('B.USER_ID, B.USER_FIRST_NAME AS USERS_NAME, D.VENDOR_ID');
			$this->db->from('SMNTP_MESSAGES A');
			$this->db->join('SMNTP_VENDOR_INVITE C','A.INVITE_ID = C.VENDOR_INVITE_ID');	
			$this->db->join('SMNTP_USERS B','C.USER_ID = B.USER_ID');	
			$this->db->join('SMNTP_VENDOR D','C.VENDOR_INVITE_ID = D.VENDOR_INVITE_ID');	
			$this->db->where('D.VENDOR_ID IS NOT NULL');
		}
		
		$this->db->distinct();
		$this->db->where(array('A.RECIPIENT_ID' => $user_id));
		$this->db->order_by('UPPER(USERS_NAME)','asc');	

		if(!empty($user_type_id))
			$this->db->where('B.USER_TYPE_ID !=', $user_type_id);
		
		
		$rs = $this->db->get()->result_array();
		$l = 0 ;

		$rt = array();
		foreach ($rs as $key => $value) {

			if($l == 0){
				array_push($rt, $value);
			}else{		
			$i = 0;
			
			for($i=0;$i < (count($rt)) ;$i++){
			
				if($this->return_torf($rt[$i],$value) == true){
					break;
				}
				if( $i == count($rt)-1){
					array_push($rt, $value);
				}
			}

			}	


			$l++;
		}
		return $rt;

	/*	$this->db->select('USER_ID, USER_FIRST_NAME || \' \' || USER_MIDDLE_NAME || \' \' || USER_LAST_NAME AS USERS_NAME', FALSE)->order_by('USER_FIRST_NAME','asc');
		
		
		
		$rs = $this->db->get_where('SMNTP_USERS', array('USER_ID !=' => $user_id));
		return $this->db->last_query();
		return $rs->result_array();*/
	}


	function get_from($user_id, $user_type_id = 1,$position_id)
	{
		if($position_id == 10){
			//$this->db->select('B.USER_ID,CONCAT(B.USER_FIRST_NAME , \' \' , B.USER_MIDDLE_NAME , \' \' , B.USER_LAST_NAME) AS USERS_NAME,A.VENDOR_ID');
			$this->db->select("B.USER_ID,
									CASE WHEN B.USER_LAST_NAME IS NULL THEN B.USER_FIRST_NAME
									WHEN B.USER_MIDDLE_NAME IS NULL THEN CONCAT(B.USER_FIRST_NAME , ' ', B.USER_LAST_NAME)
									ELSE CONCAT(B.USER_FIRST_NAME , ' ', B.USER_MIDDLE_NAME , ' ', B.USER_LAST_NAME)
									END AS USERS_NAME, 
								A.VENDOR_ID",false);
			$this->db->from('SMNTP_MESSAGES A');
			$this->db->join('SMNTP_USERS B','A.SENDER_ID = B.USER_ID');	
			$this->db->where("UPPER(A.TYPE) = 'MESSAGE'");
		}else{
			//$this->db->select('B.USER_ID,CONCAT(B.USER_FIRST_NAME , \' \' , B.USER_MIDDLE_NAME , \' \' , B.USER_LAST_NAME) AS USERS_NAME, A.VENDOR_ID');
			$this->db->select("B.USER_ID,
									CASE WHEN B.USER_LAST_NAME IS NULL THEN B.USER_FIRST_NAME
									WHEN B.USER_MIDDLE_NAME IS NULL THEN CONCAT(B.USER_FIRST_NAME , ' ', B.USER_LAST_NAME)
									ELSE CONCAT(B.USER_FIRST_NAME , ' ', B.USER_MIDDLE_NAME , ' ', B.USER_LAST_NAME)
									END AS USERS_NAME, 
								A.VENDOR_ID",false);
			$this->db->from('SMNTP_MESSAGES A');	
			$this->db->join('SMNTP_USERS B','A.SENDER_ID = B.USER_ID');	
			$this->db->where("UPPER(A.TYPE) = 'MESSAGE'");
		}
		
		$this->db->distinct();
		$this->db->where(array('A.RECIPIENT_ID' => $user_id));
		$this->db->order_by('UPPER(USERS_NAME)','asc');	

		if(!empty($user_type_id))
			$this->db->where('B.USER_TYPE_ID !=', $user_type_id);
		
		
		$rs = $this->db->get()->result_array();
		$l = 0 ;

		$rt = array();
		foreach ($rs as $key => $value) {

			if($l == 0){
				array_push($rt, $value);
			}else{		
			$i = 0;
			
			for($i=0;$i < (count($rt)) ;$i++){
			
				if($this->return_torf($rt[$i],$value) == true){
					break;
				}
				if( $i == count($rt)-1){
					array_push($rt, $value);
				}
			}

			}	


			$l++;
		}
		return $rt;
	}
	
	function return_torf($r1,$r2){
		if($r1 == $r2){
			return true;
		}else{
			return false;
		}
	}

	function get_topic($get_data)
	{
		$this->db->select('TOPIC');
		$this->db->from('SMNTP_MESSAGES');

		switch($get_data['subject_type'])
		{
			case 'VENDOR':
				if(!empty($get_data['vendor_id']))
					$this->db->where('VENDOR_ID', $get_data['vendor_id']);
			break;
			case 'VENDOR_INVITE':
				if(!empty($get_data['invite_id']))
					$this->db->where('INVITE_ID', $get_data['invite_id']);
			break;
			case 'RFQ_RFB':
				if(!empty($get_data['rfqrfb_id']))
					$this->db->where('RFQRFB_ID', $get_data['rfqrfb_id']);
			break;
			default:
				echo $get_data['subject_type'].'  -  asda';
				break;
		}
		//$this->db->where('RECIPIENT_ID', $get_data['user_id']);
		$this->db->where('SENDER_ID', $get_data['senderid']);
		$this->db->order_by('ID', 'desc');
		$this->db->limit(2);
		$rs = $this->db->get();

		if($rs->num_rows() > 0)
			$topic = $rs->row(0)->TOPIC;
		else
			$topic = '';

		return $topic;
	}

	function get_recipient($get_data)
	{
		switch($get_data['subject_type'])
		{
			case 'vendor':
				if(!empty($get_data['vendor_id']))
				{
					$this->db->select('V.VENDOR_NAME AS USERS_NAME, VI.USER_ID');
					$this->db->from('SMNTP_VENDOR V');
					$this->db->join('SMNTP_VENDOR_INVITE VI', 'VI.VENDOR_INVITE_ID = V.INVITE_ID', 'LEFT');
					$this->db->where('VENDOR_ID', $get_data['vendor_id']);
				
				}
			break;
			case 'invite':
				if(!empty($get_data['invite_id']))
				{
					$this->db->select('USER_ID, VENDOR_NAME AS USERS_NAME, USER_ID');
					$this->db->from('SMNTP_VENDOR_INVITE');
					$this->db->where('VENDOR_INVITE_ID', $get_data['invite_id']);
				
				}
			break;
			case 'rfqrfb':
				if(!empty($get_data['rfqrfb_id']))
				{
					$this->db->select('VENDOR_NAME AS USERS_NAME, USER_ID');
					$this->db->from('SMNTP_RFQRFB R');
					$this->db->join('SMNTP_RFQRFB_INVITED_VENDORS RIV', 'RIV.RFQRFB_ID = R.RFQRFB_ID', 'LEFT');
					$this->db->join('SMNTP_VENDOR_INVITE VI', 'VI.VENDOR_INVITE_ID = RIV.INVITE_ID', 'LEFT');
					$this->db->where('R.RFQRFB_ID', $get_data['rfqrfb_id']);
				
				}
			break;
		}


		$rs = $this->db->get();


		return $rs->result_array();
	}

	function get_inbox($model_data)
	{
		// MSG.SENDER_ID AS RECIPIENT_ID for reply
		$this->db->select('(CURRENT_DATE -( MSG.DATE_SENT - interval \'2\' hour)) AS MAIL_DATE_FORMATTED, CASE WHEN MSG.IS_READ = 1 THEN \'mail_read\' ELSE \'mail_unread\' END AS STATUS,
			CASE WHEN MSG.IS_READ = -1 THEN \'info\' ELSE \'\' END AS IS_READ,
			MSG.ID,
			CASE
				WHEN MSG.TYPE = \'message\' then
					CASE WHEN U.USER_LAST_NAME IS NULL THEN U.USER_FIRST_NAME
					WHEN U.USER_MIDDLE_NAME IS NULL THEN CONCAT(U.USER_FIRST_NAME , " ", U.USER_LAST_NAME)
					ELSE CONCAT(U.USER_FIRST_NAME , " ", U.USER_MIDDLE_NAME , " ", U.USER_LAST_NAME) END
				ELSE \'Portal\'
			END AS SENDER_RECIPIENT,
			MSG.SENDER_ID AS MSG_USER_ID,
			MSG.TYPE,
			MSG.SUBJECT,
			MSG.TOPIC,
			MSG.BODY,
			MSG.SENDER_ID AS RECIPIENT_ID,
			MSG.ID,
			CAST(DATE_FORMAT(MSG.DATE_SENT,"%m/%d/%y %h:%i:%s %p") AS CHAR)  AS MAIL_DATE', FALSE);
		$this->db->from('SMNTP_MESSAGES MSG');
		$this->db->join('SMNTP_USERS U', 'MSG.SENDER_ID = U.USER_ID', 'LEFT');
		
		if($model_data['data_id'] != 0){
			$this->db->where(array('MSG.RECIPIENT_ID' => $model_data['user_id'], 'MSG.ACTIVE' => '1', 'MSG.INVITE_ID' => $model_data['data_id'] ));
		}else{
			$this->db->where(array('MSG.RECIPIENT_ID' => $model_data['user_id'], 'MSG.ACTIVE' => '1'));
		}
		if (!empty($model_data['filter_type']) && !empty($model_data['filter_value']))
		{
			if ($model_data['filter_type'] === 'TOPIC') {
				$this->db->where("upper(" . $model_data['filter_type'] . ") like upper('%" . $model_data['filter_value'] . "%')");
			}
			else { // other filters
				$this->db->where($model_data['filter_type'], $model_data['filter_value']);
			}
		}

		if (!empty($model_data['sort_column']) && !empty($model_data['sort_type'])) {
			$this->db->order_by($model_data['sort_column'], $model_data['sort_type']);
		}
		else {
			$this->db->order_by('MSG.ID', 'DESC');
		}

		$rs = $this->db->get();
		$data['rs'] = $this->urldecode_rows($rs->result_array());
		$data['num_rows'] = $rs->num_rows();
		$data['last_query'] = $this->db->last_query();

		return $data;
	}

	function get_outbox($model_data)
	{
		// RECIPIENT_ID for reply
		$this->db->select('(CURRENT_DATE -( MSG.DATE_SENT - interval \'2\' hour)) AS MAIL_DATE_FORMATTED, 0 AS IS_READ,
			MSG.ID,
				CASE WHEN U.USER_LAST_NAME IS NULL THEN U.USER_FIRST_NAME
				WHEN U.USER_MIDDLE_NAME IS NULL THEN CONCAT(U.USER_FIRST_NAME , " ", U.USER_LAST_NAME)
				ELSE CONCAT(U.USER_FIRST_NAME , " ", U.USER_MIDDLE_NAME , " ", U.USER_LAST_NAME) END AS SENDER_RECIPIENT,
			MSG.RECIPIENT_ID AS MSG_USER_ID,
			MSG.TYPE,
			MSG.SUBJECT,
			MSG.TOPIC,
			MSG.BODY,
			MSG.RECIPIENT_ID,
			CAST(DATE_FORMAT(MSG.DATE_SENT,"%m/%d/%y %h:%i:%s %p") AS CHAR)  AS MAIL_DATE', FALSE);
		$this->db->from('SMNTP_MESSAGES MSG');
		$this->db->join('SMNTP_USERS U', 'MSG.RECIPIENT_ID = U.USER_ID', 'inner');
		if($model_data['data_id'] != 0){
			$this->db->where(array('MSG.SENDER_ID' => $model_data['user_id'], 'MSG.ACTIVE' => '1', 'MSG.TYPE' => 'message', 'MSG.INVITE_ID' => $model_data['data_id']));
		}else{
			$this->db->where(array('MSG.SENDER_ID' => $model_data['user_id'], 'MSG.ACTIVE' => '1', 'MSG.TYPE' => 'message'));
		}
		if (!empty($model_data['filter_type']) && !empty($model_data['filter_value']))
		{
			if ($model_data['filter_type'] === 'TOPIC') {
				$this->db->where("upper(" . $model_data['filter_type'] . ") like upper('%" . $model_data['filter_value'] . "%')");
			}
			else { // other filters
				$this->db->where($model_data['filter_type'], $model_data['filter_value']);
			}
		}

		if (!empty($model_data['sort_column']) && !empty($model_data['sort_type'])) {
			$this->db->order_by($model_data['sort_column'], $model_data['sort_type']);
		}
		else {
			$this->db->order_by('MSG.ID', 'DESC'); //$this->db->order_by('MSG.ID', 'DESC');
			
		}

		$rs = $this->db->get();
		$data['rs'] = $this->urldecode_rows($rs->result_array());
		$data['num_rows'] = $rs->num_rows();
		$data['last_query'] = $this->db->last_query();

		return $data;
	}

	function get_archive($model_data)
	{
		// MSG.SENDER_ID AS RECIPIENT_ID for reply
		$this->db->select('(CURRENT_DATE -( MSG.DATE_SENT - interval \'2\' hour)) AS MAIL_DATE_FORMATTED, CASE WHEN MSG.IS_READ = 1 THEN \'mail_read\' ELSE \'mail_unread\' END AS STATUS,
			CASE WHEN MSG.IS_READ = -1 THEN \'info\' ELSE \'\' END AS IS_READ,
			MSG.ID,
			CASE
				WHEN MSG.TYPE = \'message\' then 
					CASE WHEN U.USER_LAST_NAME IS NULL THEN U.USER_FIRST_NAME
					WHEN U.USER_MIDDLE_NAME IS NULL THEN CONCAT(U.USER_FIRST_NAME , " ", U.USER_LAST_NAME)
					ELSE CONCAT(U.USER_FIRST_NAME , " ", U.USER_MIDDLE_NAME , " ", U.USER_LAST_NAME) END
				ELSE \'Portal\'
			END AS SENDER_RECIPIENT,
			MSG.SENDER_ID AS MSG_USER_ID,
			MSG.TYPE,
			MSG.SUBJECT,
			MSG.TOPIC,
			MSG.BODY,
			MSG.SENDER_ID AS RECIPIENT_ID,
			CAST(DATE_FORMAT(MSG.DATE_SENT,"%m/%d/%y %h:%i:%s %p") AS CHAR)  AS MAIL_DATE', FALSE);
		$this->db->from('SMNTP_MESSAGES MSG');
		$this->db->join('SMNTP_USERS U', 'MSG.SENDER_ID = U.USER_ID', 'LEFT');
		
		if($model_data['data_id'] != 0){
			$this->db->where(array('MSG.RECIPIENT_ID' => $model_data['user_id'], 'MSG.ACTIVE' => '0', 'MSG.INVITE_ID' => $model_data['data_id']));
		}else{
			$this->db->where(array('MSG.RECIPIENT_ID' => $model_data['user_id'], 'MSG.ACTIVE' => '0'));
		}
		
		if (!empty($model_data['filter_type']) && !empty($model_data['filter_value']))
		{
			if ($model_data['filter_type'] === 'TOPIC') {
				$this->db->where("upper(" . $model_data['filter_type'] . ") like upper('%" . $model_data['filter_value'] . "%')");
			}
			else { // other filters
				$this->db->where($model_data['filter_type'], $model_data['filter_value']);
			}
		}

		if (!empty($model_data['sort_column']) && !empty($model_data['sort_type'])) {
			$this->db->order_by($model_data['sort_column'], $model_data['sort_type']);
		}
		else {
			$this->db->order_by('MAIL_DATE_FORMATTED', 'ASC');//$this->db->order_by('MSG.ID', 'DESC');
		}

		$rs = $this->db->get();
		$data['rs'] = $this->urldecode_rows($rs->result_array());
		$data['num_rows'] = $rs->num_rows();
		$data['last_query'] = $this->db->last_query();

		return $data;
	}

	function urldecode_rows($rs_array)
	{
		$count = 0;
		$rows_data = [];
		foreach ($rs_array as $row) {
			foreach ($row as $key => $value) {
				$rows_data[$count][$key] = nl2br(urldecode($value));
			}
			$count++;
		}

		return $rows_data;
	}

	function send_message($message_data)
	{
		$this->db->insert('SMNTP_MESSAGES', $message_data);
	}

	function mark_as_read($message_id)
	{
		$this->db->where('ID', $message_id);
		$this->db->update('SMNTP_MESSAGES', array('IS_READ' => 1));
		return $this->db->last_query();
	}

	function count_unread($user_id)
	{
		$rs = $this->db->get_where('SMNTP_MESSAGES', array('RECIPIENT_ID' => $user_id, 'IS_READ' => -1, 'ACTIVE' => 1));
		$data['num_rows'] = $rs->num_rows();

		return $data;
	}

	function archive_message($message_ids)
	{
		$message_ids = explode(',', $message_ids);

		$this->db->where_in('ID', $message_ids);
		$this->db->update('SMNTP_MESSAGES', array('ACTIVE' => 0));
	}

	function get_ids($message_id)
	{
		$this->db->select('VENDOR_ID, INVITE_ID, RFQRFB_ID');
		$this->db->from('SMNTP_MESSAGES');
		$this->db->where('ID', $message_id);
		$rs = $this->db->get();

		$ids['VENDOR_ID'] = $rs->row()->VENDOR_ID;
		$ids['INVITE_ID'] = $rs->row()->INVITE_ID;
		$ids['RFQRFB_ID'] = $rs->row()->RFQRFB_ID;

		return $ids;
	}

	function get_new_inbox($data){



		$type = "";
		$status = "";
		$from = "";
		$subj = "";
		$search_by = "";
		if(!empty($data['sort'])){
			if($data['sort_type'] == 'IS_READ'){
				$sort_by = " MSG.".$data['sort_type'] .' '. $data['sort'] .",MSG.DATE_SENT " . $data['sort'];	
			}else{
				$sort_by = $data['sort_type'] .' '. $data['sort'] .",MSG.DATE_SENT " . $data['sort'];	
			}
		}else{
			$sort_by = "MSG.DATE_SENT DESC";
		}

		if(!empty($data['search_topic'])){
			$search_by = "AND UPPER(MSG.TOPIC) LIKE UPPER('%" . $data['search_topic'] . "%')";
		}



		if(!empty($data['start'])){
			$start = $data['start'];
		}else{
			$start = 0;
		}

		if(!empty($data['length'])){
			$length = $data['length'] - 10;
		}else{
			$length = 10;
		}

		if(!empty($data['type'])){
			if($data['type'] == "message"){
				$type = "AND MSG.TYPE = '".$data['type']."' ";
			}else{
				$type = "AND (MSG.TYPE = 'Notification' OR MSG.TYPE = 'notification') ";
			}

			if($data['type'] == "all"){
				$type= "";
			}
		}


		if(!empty($data['status'])){
			if($data['status'] == 'mail_read'){
				$status = " AND MSG.IS_READ = 1";
			}else if($data['status'] == 'mail_unread'){
				$status = " AND MSG.IS_READ = -1";
			}
		}

		if(!empty($data['from'])){
			if($data['from'] != '' || $data['from'] != NULL || $data['from'] != null){
					$from = "AND MSG.SENDER_ID =" .$data['from'];
			}
		}

		if(!empty($data['subject'])){
			if($data['subject'] != '' || $data['subject'] != NULL || $data['subject'] != null){
				//	$subj = "AND MSG.SUBJECT LIKE '%" .$this->db->escape_like_str($data['subject']) . "%' ESCAPE '!' ";
				$subj = "AND MSG.SUBJECT = '". $data['subject'] ."' ";
			}
		}
		


		$userid = $data['user_id'];
		//CONCAT(DATE_FORMAT(MSG.DATE_SENT,'%d-'),UCASE(DATE_FORMAT(MSG.DATE_SENT,'%b')),DATE_FORMAT(MSG.DATE_SENT,'-%y %h.%i.%s.%f %p')) AS MAIL_DATE_FORMATTED,
		$qr = "SELECT * FROM (SELECT a.* FROM (
			SELECT 
			MSG.IS_READ,
			CONCAT(DATE_FORMAT(MSG.DATE_SENT,'%m/'),DATE_FORMAT(MSG.DATE_SENT,'%d'),DATE_FORMAT(MSG.DATE_SENT,'/%y %h:%i %p')) AS MAIL_DATE_FORMATTED,
			MSG.ID,
			MSG.SENDER_ID AS MSG_USER_ID,
			MSG.TYPE,
			CASE
				WHEN MSG.TYPE = 'message' then 
					CASE WHEN U.USER_LAST_NAME IS NULL THEN U.USER_FIRST_NAME
					WHEN U.USER_MIDDLE_NAME IS NULL THEN CONCAT(U.USER_FIRST_NAME , ' ', U.USER_LAST_NAME)
					ELSE CONCAT(U.USER_FIRST_NAME , ' ', U.USER_MIDDLE_NAME , ' ', U.USER_LAST_NAME) END
				ELSE 'Portal'
			END AS SENDER_RECIPIENT,
			MSG.SUBJECT,
			MSG.TOPIC,
			MSG.BODY,
			MSG.SENDER_ID AS RECIPIENT_ID
			FROM SMNTP_MESSAGES MSG LEFT JOIN SMNTP_USERS U ON MSG.SENDER_ID = U.USER_ID WHERE MSG.RECIPIENT_ID = ".$userid." AND MSG.ACTIVE = 1 ". $subj ." ".$type." ".$from." ".$search_by." ".$status." ORDER BY ".$sort_by.") a LIMIT 10 OFFSET ".$length.") AS NEW";

			$rs = $this->db->query($qr);	
			$rs = $rs->result();
/*
			return $this->db->last_query();
*/
		return $rs;

	}

	function get_all_inbox_count($data){

		$type = "";
		$status = "";
		$subj = "";
		$from = "";
		$search_by = "";


		if(!empty($data['status'])){
			if($data['status'] == 'mail_read'){
				$status = " AND MSG.IS_READ = 1";
			}else if($data['status'] == 'mail_unread'){
				$status = " AND MSG.IS_READ = -1";
			}
		}

		if(!empty($data['search_topic'])){
			$search_by = "AND UPPER(MSG.TOPIC) LIKE UPPER('%" . $data['search_topic'] . "%')";
		}


		if(!empty($data['type'])){
			if($data['type'] == "message"){
				$type = "AND MSG.TYPE = '".$data['type']."' ";
			}else{
				$type = "AND (MSG.TYPE = 'Notification' OR MSG.TYPE = 'notification') ";
			}

			
			if($data['type'] == "all"){
				$type= "";
			}
		}


		if(!empty($data['from'])){
			if($data['from'] != '' || $data['from'] != NULL || $data['from'] != null){
					$from = "AND MSG.SENDER_ID =" .$data['from'];
			}
		}

		if(!empty($data['subject'])){
			if($data['subject'] != '' || $data['subject'] != NULL || $data['subject'] != null){
					//$subj = "AND MSG.SUBJECT LIKE '%" .$this->db->escape_like_str($data['subject']) . "%' ESCAPE '!' ";
					$subj = "AND MSG.SUBJECT = '". $data['subject'] ."' ";
			}
		}

		$qr = "SELECT COUNT(*) AS COUNT
			FROM SMNTP_MESSAGES MSG LEFT JOIN SMNTP_USERS U ON MSG.SENDER_ID = U.USER_ID WHERE MSG.RECIPIENT_ID = " .$data['user_id'] ." AND MSG.ACTIVE = 1 ". $subj ." ".$search_by." ".$from." " .$type ." ".$status;

		$rs = $this->db->query($qr);
		$rs = $rs->result();
		return $rs;


	}

	function get_new_outbox($data){

		$type = "";
		$status = "";
		$from = "";
		$search_by = "";
		$subj = "";
/*		if(!empty($data['sort'])){
			$sort_by = $data['sort_type'] .' '. $data['sort'];		
		}else{
			$sort_by = "MSG.DATE_SENT DESC";
		}*/

		if(!empty($data['sort'])){
			if($data['sort_type'] == 'IS_READ'){
				$sort_by = " MSG.".$data['sort_type'] .' '. $data['sort'] .",MSG.DATE_SENT ASC";	
			}else{
				$sort_by = $data['sort_type'] .' '. $data['sort'] .",MSG.DATE_SENT ASC";	
		}
		//$sort_by = $data['sort_type'] .' '. $data['sort'];	


		}else{
		$sort_by = "MSG.DATE_SENT DESC";
		}

		if(!empty($data['search_topic'])){
			$search_by = "AND UPPER(MSG.TOPIC) LIKE UPPER('%" . $data['search_topic'] . "%')";
		}



		if(!empty($data['start'])){
			$start = $data['start'];
		}else{
			$start = 0;
		}

		if(!empty($data['length'])){
			$length = $data['length'] + 1;
		}else{
			$length = 11;
		}

		if(!empty($data['type'])){
			if($data['type'] == "message"){
				$type = "AND MSG.TYPE = '".$data['type']."' ";
			}else{
				$type = "AND (MSG.TYPE = 'Notification' OR MSG.TYPE = 'notification') ";
			}

			if($data['type'] == "all"){
				$type= "";
			}
		}


		if(!empty($data['status'])){
			if($data['status'] == 'mail_read'){
				$status = " AND MSG.IS_READ = 1";
			}else if($data['status'] == 'mail_unread'){
				$status = " AND MSG.IS_READ = -1";
			}
		}

		if(!empty($data['from'])){
			if($data['from'] != '' || $data['from'] != NULL || $data['from'] != null){
					$from = "AND MSG.RECIPIENT_ID =" .$data['from'];
			}
		}

		if(!empty($data['subject'])){
			if($data['subject'] != '' || $data['subject'] != NULL || $data['subject'] != null){
						$subj = "AND MSG.SUBJECT LIKE '%" .$data['subject'] . "%' ";
			}
		}
		


		$userid = $data['user_id'];

		//$qr = "SELECT * FROM (SELECT a.*, ROW_NUMBRE() FROM (
		//	SELECT 
		//	MSG.IS_READ,
		//	CAST(DATE_FORMAT(MSG.DATE_SENT,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS MAIL_DATE_FORMATTED,
		//	MSG.ID,
		//	MSG.SENDER_ID AS MSG_USER_ID,
		//	MSG.TYPE,
		//	CASE
		//		WHEN MSG.TYPE = 'message' then CONCAT(U.USER_FIRST_NAME , ' ' , U.USER_MIDDLE_NAME , ' ' , U.USER_LAST_NAME)
		//		ELSE 'Portal'
		//	END AS SENDER_RECIPIENT,
		//	MSG.SUBJECT,
		//	MSG.TOPIC,
		//	MSG.BODY,
		//	MSG.SENDER_ID AS RECIPIENT_ID
		//	FROM SMNTP_MESSAGES MSG LEFT JOIN SMNTP_USERS U ON MSG.RECIPIENT_ID = U.USER_ID WHERE MSG.SENDER_ID = ".$userid." AND MSG.ACTIVE = 1 ". $subj ." ".$type." ".$from." ".$search_by." ".$status." ORDER BY ".$sort_by.") a where ROW_NUMBER() < ".$length.") x where x.rnum > ".$start;
		
		$qr = "SELECT 
				MSG.IS_READ, CAST(DATE_FORMAT(MSG.DATE_SENT,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS MAIL_DATE_FORMATTED, 
				MSG.ID, MSG.SENDER_ID AS MSG_USER_ID, MSG.TYPE, 
				CASE WHEN MSG.TYPE = 'message' THEN 
					CASE WHEN U.USER_LAST_NAME IS NULL THEN U.USER_FIRST_NAME
					WHEN U.USER_MIDDLE_NAME IS NULL THEN CONCAT(U.USER_FIRST_NAME , ' ', U.USER_LAST_NAME)
					ELSE CONCAT(U.USER_FIRST_NAME , ' ', U.USER_MIDDLE_NAME , ' ', U.USER_LAST_NAME) END
				ELSE 'Portal' 
				END AS SENDER_RECIPIENT, 
				MSG.SUBJECT, MSG.TOPIC, MSG.BODY, MSG.SENDER_ID AS RECIPIENT_ID 
				FROM SMNTP_MESSAGES MSG 
				LEFT JOIN SMNTP_USERS U ON MSG.RECIPIENT_ID = U.USER_ID 
				WHERE MSG.SENDER_ID = ".$userid." AND MSG.ACTIVE = 1 ORDER BY MSG.DATE_SENT DESC";

			$rs = $this->db->query($qr);	

/*
			return $this->db->last_query();
*/
		$rs = $rs->result();
		return $rs;
	}

		function get_all_outbox_count($data){

		$type = "";
		$status = "";
		$subj = "";
		$search_by = "";
		$from = "";

		if(!empty($data['search_topic'])){
			$search_by = "AND UPPER(MSG.TOPIC) LIKE UPPER('%" . $data['search_topic'] . "%')";
		}

		if(!empty($data['from'])){
			if($data['from'] != '' || $data['from'] != NULL || $data['from'] != null){
					$from = "AND MSG.RECIPIENT_ID =" .$data['from'];
			}
		}



		if(!empty($data['status'])){
			if($data['status'] == 'mail_read'){
				$status = " AND MSG.IS_READ = 1";
			}else if($data['status'] == 'mail_unread'){
				$status = " AND MSG.IS_READ = -1";
			}
		}

		if(!empty($data['type'])){
			if($data['type'] == "message"){
				$type = "AND MSG.TYPE = '".$data['type']."' ";
			}else{
				$type = "AND (MSG.TYPE = 'Notification' OR MSG.TYPE = 'notification') ";
			}

			
			if($data['type'] == "all"){
				$type= "";
			}
		}

		if(!empty($data['subject'])){
			if($data['subject'] != '' || $data['subject'] != NULL || $data['subject'] != null){
						$subj = "AND MSG.SUBJECT LIKE '%" .$data['subject'] . "%'";
			}
		}

		$qr = "SELECT COUNT(*) AS COUNT
			FROM SMNTP_MESSAGES MSG LEFT JOIN SMNTP_USERS U ON MSG.SENDER_ID = U.USER_ID WHERE MSG.SENDER_ID = " .$data['user_id'] ." AND MSG.ACTIVE = 1 ". $subj ." ".$from."".$search_by." " .$type ." ".$status;

		$rs = $this->db->query($qr);
		$rs = $rs->result();

		return $rs;
	}

	function get_inbox_outbox_archive($data){


		$type = "";
		$status = "";
		$from = "";
		$sender_recipient = "RECIPIENT_ID";
		$message_active = "AND MSG.ACTIVE = '1'";
		$search_by = "";


		if($data['message_type'] == "inbox"){
			$sender_recipient = "RECIPIENT_ID";
		}else if($data['message_type'] == "outbox"){
			$sender_recipient = "SENDER_ID";
		}else if($data['message_type'] == "archives"){
			$sender_recipient = "RECIPIENT_ID";
			$message_active = "AND MSG.ACTIVE = '0'";
		}

		if(!empty($data['search_topic'])){
			$search_by = "AND UPPER(MSG.TOPIC) LIKE UPPER('%" . $data['search_topic'] . "%')";
		}



		$subj = "";
		if(!empty($data['sort'])){
			$sort_by = $data['sort_type'] .' '. $data['sort'];		
		}else{
			$sort_by = "MSG.DATE_SENT DESC";
		}

		if(!empty($data['start'])){
			$start = $data['start'];
		}else{
			$start = 0;
		}

		if(!empty($data['length'])){
			$length = $data['length'] + 1;
		}else{
			$length = 11;
		}

		if(!empty($data['type'])){
			if($data['type'] == "message"){
				$type = "AND MSG.TYPE = '".$data['type']."' ";
			}else{
				$type = "AND (MSG.TYPE = 'Notification' OR MSG.TYPE = 'notification') ";
			}

			if($data['type'] == "all"){
				$type= "";
			}
		}


		if(!empty($data['status'])){
			if($data['status'] == 'mail_read'){
				$status = " AND MSG.IS_READ = 1";
			}else if($data['status'] == 'mail_unread'){
				$status = " AND MSG.IS_READ = -1";
			}
		}

		if(!empty($data['from'])){
			if($data['from'] != '' || $data['from'] != NULL || $data['from'] != null){
					$from = "AND MSG.SENDER_ID =" .$data['from'];
			}
		}

		if(!empty($data['subject'])){
			if($data['subject'] != '' || $data['subject'] != NULL || $data['subject'] != null){
					$subj = "AND MSG.SUBJECT LIKE '%" .$this->db->escape_like_str($data['subject']) . "%' ESCAPE '!' ";
			}
		}
		
		$userid = $data['user_id'];

		$qr = "SELECT 
					MSG.IS_READ, MSG.DATE_SENT AS MAIL_DATE_FORMATTED, MSG.ID, MSG.SENDER_ID AS MSG_USER_ID, MSG.TYPE, 
					CASE WHEN MSG.TYPE = 'message' THEN 
						CASE WHEN U.USER_LAST_NAME IS NULL THEN U.USER_FIRST_NAME
						WHEN U.USER_MIDDLE_NAME IS NULL THEN CONCAT(U.USER_FIRST_NAME , ' ', U.USER_LAST_NAME)
						ELSE CONCAT(U.USER_FIRST_NAME , ' ', U.USER_MIDDLE_NAME , ' ', U.USER_LAST_NAME) END
					ELSE 'Portal' 
					END AS SENDER_RECIPIENT, 
					MSG.SUBJECT, MSG.TOPIC, MSG.BODY, MSG.SENDER_ID AS RECIPIENT_ID 
				FROM SMNTP_MESSAGES MSG 
				LEFT JOIN SMNTP_USERS U ON MSG.SENDER_ID = U.USER_ID 
WHERE MSG.RECIPIENT_ID = ".$userid." AND MSG.ACTIVE = '1' ORDER BY MSG.DATE_SENT DESC";

			$rs = $this->db->query($qr);	
		$rs = $rs->result();
	/*	$rs->QRS = $this->db->last_query();*/
		return $rs;

	}

	function get_inbox_outbox_archive_count($data){

		$type = "";
		$status = "";
		$subj = "";
		$userid = $data['user_id'];
		$search_by = "";

		$sender_recipient = "RECIPIENT_ID";
		$message_active = "AND MSG.ACTIVE = '1'";

		if(!empty($data['search_topic'])){
			$search_by = "AND UPPER(MSG.TOPIC) LIKE UPPER('%" . $data['search_topic'] . "%')";
		}



		if($data['message_type'] == "inbox"){
			$sender_recipient = "RECIPIENT_ID";
		}else if($data['message_type'] == "outbox"){
			$sender_recipient = "SENDER_ID";
		}else if($data['message_type'] == "archives"){
			$sender_recipient = "RECIPIENT_ID";
			$message_active = "AND MSG.ACTIVE = '0'";
		}


		if(!empty($data['status'])){
			if($data['status'] == 'mail_read'){
				$status = " AND MSG.IS_READ = 1";
			}else if($data['status'] == 'mail_unread'){
				$status = " AND MSG.IS_READ = -1";
			}
		}

		if(!empty($data['type'])){
			if($data['type'] == "message"){
				$type = "AND MSG.TYPE = '".$data['type']."' ";
			}else{
				$type = "AND (MSG.TYPE = 'Notification' OR MSG.TYPE = 'notification') ";
			}

			
			if($data['type'] == "all"){
				$type= "";
			}
		}

		if(!empty($data['subject'])){
			if($data['subject'] != '' || $data['subject'] != NULL || $data['subject'] != null){
					$subj = "AND MSG.SUBJECT LIKE '%" .$this->db->escape_like_str($data['subject']) . "%' ESCAPE '!' ";
			}
		}

		$qr = "SELECT COUNT(*) AS COUNT
			FROM SMNTP_MESSAGES MSG 
			LEFT JOIN SMNTP_USERS U ON MSG.SENDER_ID = U.USER_ID 
			WHERE  MSG.".$sender_recipient." = ".$userid." ".$message_active." ".$search_by." ". $subj ." " .$type ." ".$status;

		$rs = $this->db->query($qr);
		$rs = $rs->result();
		return $rs;
	}


}
?>