<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Inviteapproval_model extends CI_Model
{
	function invite_process($data)
	{
		$etimestamp = date('Y-m-d H:i:s');
		
		$date_timestamp = date('Y-m-d H:i:s');
		
		//LOGS FOR APPROVAL HISTORY
		$record_stat = array(
			'VENDOR_INVITE_ID' => $data['invite_id']
		);
			
		$this->db->select('VENDOR_INVITE_STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where(array_filter($record_stat));
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <= 
		
		$query = $this->db->get();

		$vendor_invite_status_id = $query->row()->VENDOR_INVITE_STATUS_ID;
		
		$record3 = array(
				'VENDOR_INVITE_STATUS_ID'	=> $vendor_invite_status_id,
				'VENDOR_INVITE_ID'	=> $data['invite_id'],
				'STATUS_ID'			=> $data['next_status'],
				'POSITION_ID'		=> $data['next_position'],
				'APPROVER_ID'		=> $data['user_id'],
				'APPROVER_REMARKS'	=> $data['remarks'],
				'DATE_UPDATED'		=> $date_timestamp,
				'ACTIVE'			=> 1,
			);
		
		$this->db->insert('SMNTP_VENDOR_STATUS_LOGS', $record3);
		
		//END

		if($data['reg_type_id'] == 4){
			$record = array(
						'STATUS_ID'			=> $data['next_status'],
						'APPROVER_REMARKS'	=> $data['remarks'],
						'APPROVER_ID'		=> $data['user_id'],
						'DATE_UPDATED'		=> $etimestamp,
						'PRIMARY_START_DATE'		=> $etimestamp,
						'POSITION_ID'		=> $data['next_position']
				);
		}else{
			$record = array(
						'STATUS_ID'			=> $data['next_status'],
						'APPROVER_REMARKS'	=> $data['remarks'],
						'APPROVER_ID'		=> $data['user_id'],
						'DATE_UPDATED'		=> $etimestamp,
						'POSITION_ID'		=> $data['next_position']
				);
		}

		$this->db->where('VENDOR_INVITE_ID', $data['invite_id']);
		$this->db->update('SMNTP_VENDOR_STATUS', $record);
		// echo $this->db->affected_rows();
		
		

		return $this->db->affected_rows();
	}

	function get_appr_history($data)
	{
		$rpp 			= $data['rpp'];
		$page_num 		= 1; //$data['page_num'];

		$valid 			= FALSE;
        $query 			= '';
        $resultscount 	= '';
        $finalquery 	= '';

        $this->db->start_cache();
        $this->db->from('SMNTP_VENDOR_STATUS_LOGS VSL');
        $this->db->join('SMNTP_STATUS SS', 'VSL.STATUS_ID = SS.STATUS_ID');
        $this->db->join('SMNTP_USERS U', 'VSL.APPROVER_ID = U.USER_ID');
        $this->db->join('SMNTP_POSITION P', 'U.POSITION_ID = P.POSITION_ID');
        $this->db->where('VSL.VENDOR_INVITE_ID', $data['invite_id']);
                        
        $this->db->stop_cache();
        $totalcount= $this->db->get()->num_rows();

        if ($totalcount > 0)
        {
             if ($page_num != 0 && $rpp != 0)
             {
                  $max = $rpp * $page_num;
                  $min = $max-$rpp;
             }

             $this->db->select('
             					U.USER_FIRST_NAME,
             					U.USER_LAST_NAME,
                                SS.STATUS_NAME,
                                P.POSITION_NAME,
                                VSL.APPROVER_REMARKS,
								SS.STATUS_NAME,
								VSL.DATE_UPDATED
                                   ', false);
             $this->db->order_by('VSL.VENDOR_STATUS_LOGS_ID', 'DESC');

             // if ($page_num != 0 && $rpp != 0)
             // {
                  // $this->db->limit($rpp,$min);
             // }

             $query = $this->db->get();
             // echo $this->db->last_query();
             $valid = TRUE;
             $finalquery = $query->result_array();
             $resultscount     = $query->num_rows();
        }

        $this->db->flush_cache();

        $data['resultscount'] = $resultscount;
        $data['totalcount'] = $totalcount;
        $data['valid'] = $valid;
        $data['query'] = $finalquery;

        return $data;
	}

	function get_rev_history($data)
	{
		$rpp 			= $data['rpp'];
		$page_num 		= 1; //$data['page_num'];

		$valid 			= FALSE;
        $query 			= '';
        $resultscount 	= '';
        $finalquery 	= '';

        $this->db->start_cache();
        $this->db->from('SMNTP_VENDOR_AUDIT_LOGS SVAL');
        $this->db->join('SMNTP_USERS SMU', 'SVAL.USER_ID = SMU.USER_ID');
        $this->db->where('SVAL.VENDOR_ID', $data['invite_id']);
                        
        $this->db->stop_cache();
        $totalcount= $this->db->get()->num_rows();

        if ($totalcount > 0)
        {
             if ($page_num != 0 && $rpp != 0)
             {
                  $max = $rpp * $page_num;
                  $min = $max-$rpp;
             }

             $this->db->select('SMU.USER_FIRST_NAME, SMU.USER_LAST_NAME, SVAL.VAR_FROM, SVAL.VAR_TO, SVAL.MODIFIED_FIELD, SVAL.MODIFIED_DATE', false);
             $this->db->order_by('SVAL.AUDIT_LOG_ID', 'DESC');

             $query = $this->db->get();
             $valid = TRUE;
             $finalquery = $query->result_array();
             $resultscount     = $query->num_rows();
        }

        $this->db->flush_cache();

        $data['resultscount'] = $resultscount;
        $data['totalcount'] = $totalcount;
        $data['valid'] = $valid;
        $data['query'] = $finalquery;

        return $data;
	}

	function get_smvs($data){

		//Get vendor desc
		$get_vendor_info = $this->db->query("SELECT SVI.VENDOR_INVITE_ID, SVI.TRADE_VENDOR_TYPE, SV.VENDOR_CODE_02, SVI.REGISTRATION_TYPE FROM SMNTP_VENDOR_INVITE SVI LEFT JOIN SMNTP_VENDOR SV  ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID WHERE SV.VENDOR_ID =".$data['invite_id'])->result_array();
		$vendor_invite_id = $get_vendor_info[0]['VENDOR_INVITE_ID'];
		$trade_vendor_type = $get_vendor_info[0]['TRADE_VENDOR_TYPE'];
		$vendor_code_02 = $get_vendor_info[0]['VENDOR_CODE_02'];
		$registration_type = $get_vendor_info[0]['REGISTRATION_TYPE'];

		if($vendor_code_02 != ''){
			$trade_vendor_type = "1,2";
		}
		
		if( $registration_type == 4 ){
			$trade_vendor_type = "1,2";
		}

		$get_category = $this->db->query("SELECT category_id FROM SMNTP_VENDOR_CATEGORIES WHERE VENDOR_INVITE_ID = ".$vendor_invite_id)->result_array();
		
		$cat_id = "0";
		foreach($get_category as $category_id){
			$cat_id .= "," . $category_id['category_id'];
		}
		
        // $finalquery = $this->db->query('
        	// SELECT 
        	// SSS.DESCRIPTION, CASE SVSS.TRADE_VENDOR_TYPE WHEN 1 THEN "OUTRIGHT" WHEN 2 THEN "STORE CONSIGNOR" WHEN 3 THEN "BOTH" END AS TRADE_VENDOR_TYPE, SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME, SVSS.POSITION, SVSS.EMAIL, SVSS.MOBILE_NO
        	// FROM SMNTP_SM_SYSTEMS SSS
        	// LEFT JOIN SMNTP_VENDOR_SM_SYSTEMS SVSS
        		// ON SSS.SM_SYSTEM_ID = SVSS.SM_SYSTEM_ID 
        		// AND SVSS.`VENDOR_INVITE_ID` = "'.$vendor_invite_id.'" 
        	// WHERE SSS.REMOVED = "N"
			// AND SSS.`SM_SYSTEM_ID` NOT IN (SELECT SM_SYSTEM_ID FROM SMNTP_VENDOR_SM_SYSTEMS WHERE REMOVED = "Y" AND VENDOR_INVITE_ID = "'.$vendor_invite_id.'")
        	// AND SSS.DEPARTMENT_ID IN ('.$cat_id.')
        	// AND SSS.TRADE_VENDOR_TYPE IN ('.$trade_vendor_type.')
        	// '        	)->result_array();
			
        $finalquery = $this->db->query('
        	SELECT 
        	SSS.`SM_SYSTEM_ID`, SSS.DESCRIPTION, CASE SVSS.TRADE_VENDOR_TYPE WHEN 1 THEN "OUTRIGHT" WHEN 2 THEN "STORE CONSIGNOR" WHEN 3 THEN "BOTH" END AS TRADE_VENDOR_TYPE, SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME, SVSS.POSITION, SVSS.EMAIL, SVSS.MOBILE_NO
        	FROM SMNTP_SM_SYSTEMS SSS
        	LEFT JOIN SMNTP_VENDOR_SM_SYSTEMS_TEMP SVSS
        		ON SSS.SM_SYSTEM_ID = SVSS.SM_SYSTEM_ID 
        		AND SVSS.`VENDOR_INVITE_ID` = "'.$vendor_invite_id.'" 
        	WHERE SSS.REMOVED = "N"
        	AND SSS.DEPARTMENT_ID IN ('.$cat_id.')
			AND SSS.`SM_SYSTEM_ID` NOT IN (SELECT SM_SYSTEM_ID FROM SMNTP_VENDOR_SM_SYSTEMS WHERE REMOVED = "Y" AND VENDOR_INVITE_ID = "'.$vendor_invite_id.'")
			AND SSS.TRADE_VENDOR_TYPE IN ('.$trade_vendor_type.')
        	')->result_array();
        $data['query'] = $finalquery;

        return $data;
	}

	function get_smvs_vendor($data){

		//Get vendor desc
		$get_vendor_info = $this->db->query("SELECT SVI.VENDOR_INVITE_ID, SVI.TRADE_VENDOR_TYPE, SV.VENDOR_CODE_02, SVI.REGISTRATION_TYPE FROM SMNTP_VENDOR_INVITE SVI LEFT JOIN SMNTP_VENDOR SV  ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID WHERE SVI.VENDOR_INVITE_ID =".$data['invite_id'])->result_array();
		$vendor_invite_id = $get_vendor_info[0]['VENDOR_INVITE_ID'];
		$trade_vendor_type = $get_vendor_info[0]['TRADE_VENDOR_TYPE'];
		$vendor_code_02 = $get_vendor_info[0]['VENDOR_CODE_02'];
		$registration_type = $get_vendor_info[0]['REGISTRATION_TYPE'];

		if($vendor_code_02 != ''){
			$trade_vendor_type = "1,2";
		}
		
		if( $registration_type == 4 ){
			$trade_vendor_type = "1,2";
		}

		$get_category = $this->db->query("SELECT category_id FROM SMNTP_VENDOR_CATEGORIES WHERE VENDOR_INVITE_ID = ".$vendor_invite_id)->result_array();
		
		$cat_id = "0";
		foreach($get_category as $category_id){
			$cat_id .= "," . $category_id['category_id'];
		}
		
		// SELECT 
  //       	SSS.`SM_SYSTEM_ID`, SSS.DESCRIPTION, IF(SSS.TRADE_VENDOR_TYPE=1,"SELECTED", "DISABLED") AS OUTRIGHT, IF(SSS.TRADE_VENDOR_TYPE=2,"SELECTED", "DISABLED") AS SC, SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME, SVSS.POSITION, SVSS.EMAIL, SVSS.MOBILE_NO
  //       	FROM SMNTP_SM_SYSTEMS SSS
  //       	LEFT JOIN SMNTP_VENDOR_SM_SYSTEMS_TEMP SVSS
  //       		ON SSS.SM_SYSTEM_ID = SVSS.SM_SYSTEM_ID 
  //       		AND SVSS.`VENDOR_INVITE_ID` = "'.$vendor_invite_id.'" 
  //       	WHERE SSS.REMOVED = "N"
  //       	AND SSS.DEPARTMENT_ID IN ('.$cat_id.')
		// 	AND SSS.`SM_SYSTEM_ID` NOT IN (SELECT SM_SYSTEM_ID FROM SMNTP_VENDOR_SM_SYSTEMS WHERE REMOVED = "Y" AND VENDOR_INVITE_ID = "'.$vendor_invite_id.'")
		// 	AND SSS.TRADE_VENDOR_TYPE IN ('.$trade_vendor_type.')
  //       	')->result_array();
        $finalquery = $this->db->query('
        	SELECT 
        	SSS.`SM_SYSTEM_ID`, SSS.DESCRIPTION, CASE SSS.TRADE_VENDOR_TYPE WHEN 1 THEN "OUTRIGHT" ELSE "SC" END AS TRADE_VENDOR_TYPE_DTL, SSS.TRADE_VENDOR_TYPE, SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME, SVSS.POSITION, SVSS.EMAIL, SVSS.MOBILE_NO
        	FROM SMNTP_SM_SYSTEMS SSS
        	LEFT JOIN SMNTP_VENDOR_SM_SYSTEMS_TEMP SVSS
        		ON SSS.SM_SYSTEM_ID = SVSS.SM_SYSTEM_ID 
        		AND SVSS.`VENDOR_INVITE_ID` = "'.$vendor_invite_id.'" 
        	WHERE SSS.REMOVED = "N"
        	AND SSS.DEPARTMENT_ID IN ('.$cat_id.')
			AND SSS.`SM_SYSTEM_ID` NOT IN (SELECT SM_SYSTEM_ID FROM SMNTP_VENDOR_SM_SYSTEMS WHERE REMOVED = "Y" AND VENDOR_INVITE_ID = "'.$vendor_invite_id.'")
			AND SSS.TRADE_VENDOR_TYPE IN ('.$trade_vendor_type.')
        	')->result_array();
        $data['query'] = $finalquery;

        return $data;
	}

function get_vendor_id_request_vendor($data){

		//Get vendor desc
		//$vendor_request_id = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$data['invite_id']."")->row()->VENDOR_REQUEST_ID;

		$get_vendor_request_info = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$data['invite_id']." LIMIT 1")->result_array();

		//var_dump(count($get_vendor_request_info)). die();
		
        if (count($get_vendor_request_info) > 0){

		//$get_vendor_request_info = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = 21032 LIMIT 1")->result_array();

			$vendor_request_id = $get_vendor_request_info[0]['VENDOR_REQUEST_ID'];

	        $finalquery = $this->db->query('SELECT VENDOR_ID_REQUEST_ID, IS_CHECKED, DATA_FROM, VENDOR_REQUEST_ID, TRADE_VENDOR_TYPE, UPPER(FIRST_NAME) AS FIRST_NAME, UPPER(MIDDLE_INITIAL) AS MIDDLE_INITIAL, UPPER(LAST_NAME) AS LAST_NAME,UPPER(DESIGNATION) AS DESIGNATION, REQUEST_TYPE, ROW_NUMBER() OVER (ORDER BY FIRST_NAME ASC) AS COUNT FROM SMNTP_VENDOR_ID_REQUESTS_TEMP WHERE VENDOR_REQUEST_ID = "'.$vendor_request_id.'"')->result_array();

	        $get_request_type = $this->db->query('SELECT ID, REQUEST_TYPE_NAME FROM SMNTP_REQUEST_TYPE WHERE ACTIVE = 1')->result_array();

	        $email = $this->db->query('SELECT SVSS.TRADE_VENDOR_TYPE, SVSS.EMAIL FROM SMNTP_VENDOR_SM_SYSTEMS_TEMP SVSS
			JOIN SMNTP_SM_SYSTEMS SSS ON SVSS.`SM_SYSTEM_ID` = SSS.`SM_SYSTEM_ID`
			WHERE SSS.DESCRIPTION = "Vendor ID/Pass"
			AND SVSS.`VENDOR_INVITE_ID` = '.$data['invite_id'])->result_array();

			
			$data['query'] = $finalquery;
			$data['query2'] = $get_request_type;
			$data['smvs_email']	= $email;

		    return $data;
		}else{
			/*$finalquery = $this->db->query(' 
				SELECT  " " AS VENDOR_ID_REQUEST_ID, false AS IS_CHECKED, " " AS VENDOR_REQUEST_ID, " " AS FIRST_NAME, " " AS MIDDLE_INITIAL, " " AS LAST_NAME, " " AS DESIGNATION, "NEW" AS REQUEST_TYPE, 1 AS COUNT')->result_array();*/

			$get_request_type = $this->db->query('SELECT ID, REQUEST_TYPE_NAME FROM SMNTP_REQUEST_TYPE WHERE ACTIVE = 1')->result_array();

			$email = $this->db->query('SELECT SVSS.TRADE_VENDOR_TYPE, SVSS.EMAIL FROM SMNTP_VENDOR_SM_SYSTEMS_TEMP SVSS
			JOIN SMNTP_SM_SYSTEMS SSS ON SVSS.`SM_SYSTEM_ID` = SSS.`SM_SYSTEM_ID`
			WHERE SSS.DESCRIPTION = "Vendor ID/Pass"
			AND SVSS.`VENDOR_INVITE_ID` = '.$data['invite_id'])->result_array();

			//$data['query'] = $finalquery;
			$data['query2'] = $get_request_type;
			$data['smvs_email']	= $email;
			
		    return $data;
		}
		
		// echo "<pre>";
		// var_dump($finalquery); die();
        
	}

	function get_vendor_id_request_save_vendor($data){

		//Get vendor desc
		//$vendor_request_id = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$data['invite_id']."")->row()->VENDOR_REQUEST_ID;

		$get_vendor_request_info = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS WHERE VENDOR_INVITE_ID = ".$data['invite_id']." LIMIT 1")->result_array();

		//var_dump(count($get_vendor_request_info)). die();
		
        if (count($get_vendor_request_info) > 0){

		//$get_vendor_request_info = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = 21032 LIMIT 1")->result_array();

			$vendor_request_id = $get_vendor_request_info[0]['VENDOR_REQUEST_ID'];

	        $finalquery = $this->db->query('SELECT VENDOR_ID_REQUEST_ID, IS_CHECKED, VENDOR_REQUEST_ID, UPPER(FIRST_NAME) AS FIRST_NAME, UPPER(MIDDLE_INITIAL) AS MIDDLE_INITIAL, UPPER(LAST_NAME) AS LAST_NAME,UPPER(DESIGNATION) AS DESIGNATION, REQUEST_TYPE, ROW_NUMBER() OVER (ORDER BY FIRST_NAME ASC) AS COUNT FROM SMNTP_VENDOR_ID_REQUESTS WHERE VENDOR_REQUEST_ID = "'.$vendor_request_id.'"')->result_array();

	        $get_request_type = $this->db->query('SELECT ID, REQUEST_TYPE_NAME FROM SMNTP_REQUEST_TYPE WHERE ACTIVE = 1')->result_array();

	        $email = $this->db->query('SELECT SVSS.EMAIL FROM SMNTP_VENDOR_SM_SYSTEMS SVSS
			JOIN SMNTP_SM_SYSTEMS SSS ON SVSS.`SM_SYSTEM_ID` = SSS.`SM_SYSTEM_ID`
			WHERE SSS.DESCRIPTION = "Vendor ID/Pass"
			AND SVSS.`VENDOR_INVITE_ID` = '.$data['invite_id'])->result_array();
			
			$data['query'] = $finalquery;
			$data['query2'] = $get_request_type;
			$data['smvs_email']	= $email;

		    return $data;
		}else{
			/*$finalquery = $this->db->query(' 
				SELECT  " " AS VENDOR_ID_REQUEST_ID, false AS IS_CHECKED, " " AS VENDOR_REQUEST_ID, " " AS FIRST_NAME, " " AS MIDDLE_INITIAL, " " AS LAST_NAME, " " AS DESIGNATION, "NEW" AS REQUEST_TYPE, 1 AS COUNT')->result_array();*/

			$get_request_type = $this->db->query('SELECT ID, REQUEST_TYPE_NAME FROM SMNTP_REQUEST_TYPE WHERE ACTIVE = 1')->result_array();

			$email = $this->db->query('SELECT SVSS.EMAIL FROM SMNTP_VENDOR_SM_SYSTEMS SVSS
			JOIN SMNTP_SM_SYSTEMS SSS ON SVSS.`SM_SYSTEM_ID` = SSS.`SM_SYSTEM_ID`
			WHERE SSS.DESCRIPTION = "Vendor ID/Pass"
			AND SVSS.`VENDOR_INVITE_ID` = '.$data['invite_id'])->result_array();

			//$data['query'] = $finalquery;
			$data['query2'] = $get_request_type;
			$data['smvs_email']	= $email;
			
		    return $data;
		}
		
		// echo "<pre>";
		// var_dump($finalquery); die();
        
	}

	function get_vendor_pass_request_vendor($data){

        $finalquery = $this->db->query('
        	SELECT VENDOR_REQUEST_ID, VENDOR_INVITE_ID, APPROVAL_DATE, VENDOR_NAME, TOTAL_PASS_QTY, TOTAL_AMOUNT_DEDUCTION 
        	FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = "'.$data['invite_id'].'"')->result_array();


        $get_request_type = $this->db->query('SELECT ID, REQUEST_TYPE_NAME FROM SMNTP_REQUEST_TYPE WHERE ACTIVE = 1')->result_array();


        $get_amount = $this->db->query('SELECT AMOUNT FROM SMNTP_VENDOR_ID_PASS_AMOUNT WHERE NOW() > EFFECTIVITY_DATE ORDER BY EFFECTIVITY_DATE DESC LIMIT 1')->result_array();

        $get_email = $this->db->query('SELECT SM_SYSTEM_ID, EMAIL FROM SMNTP_VENDOR_SM_SYSTEMS  WHERE SM_SYSTEM_ID IN (13,14) AND VENDOR_INVITE_ID = "'.$data['invite_id'].'"')->result_array();


        $check_pass = $this->db->query('SELECT COALESCE(SUM(SVP.QTY),0) AS QTY 
        		FROM SMNTP_VENDOR_REQUESTS SVR
        		LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVP.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
        		WHERE SVR.VENDOR_INVITE_ID ="'.$data['invite_id'].'"')->result_array();


         if (count($finalquery) > 0 )
		{

			$get_outright = $this->db->query("SELECT * FROM SMNTP_VENDOR_PASS_TEMP WHERE TRADE_VENDOR_TYPE = 1 AND VENDOR_REQUEST_ID = ".$finalquery[0]['VENDOR_REQUEST_ID'])->result_array();

        	$get_sc = $this->db->query("SELECT * FROM SMNTP_VENDOR_PASS_TEMP WHERE TRADE_VENDOR_TYPE = 2 AND VENDOR_REQUEST_ID = ".$finalquery[0]['VENDOR_REQUEST_ID'])->result_array();

			$data['query'] = $finalquery;
			$data['query2'] = $get_request_type;
			$data['query3'] = $get_amount;
			$data['email'] = $get_email;
			$data['pass_qty'] = $check_pass;
			$data['outright'] = $get_outright;
			$data['sc'] = $get_sc;

		    return $data;
		}else{
			$finalquery = $this->db->query(' SELECT " " AS VENDOR_INVITE_ID, " " AS APPROVAL_DATE, " " AS VENDOR_NAME, " " AS REQUESTORS_EMAIL_ADD, 0 AS VENDOR_PASS_QTY, " " AS PASS_REQUEST_TYPE, 1 AS TOTAL_PASS_QTY, " " AS TOTAL_AMOUNT_DEDUCTION')->result_array();
			$data['query'] = $finalquery;
			$data['query2'] = $get_request_type;
			$data['query3'] = $get_amount;
			$data['email'] = $get_email;
			$data['pass_qty'] = $check_pass;
		    return $data;
		}
		
	}

	function get_vendor_pass_save_request_vendor($data){

        $finalquery = $this->db->query('SELECT VENDOR_INVITE_ID, APPROVAL_DATE, VENDOR_NAME, REQUESTORS_EMAIL_ADD, VENDOR_PASS_QTY, REQUEST_TYPE AS PASS_REQUEST_TYPE, TOTAL_PASS_QTY, TOTAL_AMOUNT_DEDUCTION FROM SMNTP_VENDOR_REQUESTS WHERE VENDOR_INVITE_ID = "'.$data['invite_id'].'"')->result_array();

        $get_request_type = $this->db->query('SELECT ID, REQUEST_TYPE_NAME FROM SMNTP_REQUEST_TYPE WHERE ACTIVE = 1')->result_array();


        $get_amount = $this->db->query('SELECT AMOUNT FROM SMNTP_VENDOR_ID_PASS_AMOUNT WHERE ACTIVE = 1')->result_array();

         if (count($finalquery) > 0 )
		{
			$data['query'] = $finalquery;
			$data['query2'] = $get_request_type;
			$data['query3'] = $get_amount;
		    return $data;
		}else{
			$finalquery = $this->db->query(' SELECT " " AS VENDOR_INVITE_ID, " " AS APPROVAL_DATE, " " AS VENDOR_NAME, " " AS REQUESTORS_EMAIL_ADD, 0 AS VENDOR_PASS_QTY, " " AS PASS_REQUEST_TYPE, 1 AS TOTAL_PASS_QTY, " " AS TOTAL_AMOUNT_DEDUCTION')->result_array();
			$data['query'] = $finalquery;
			$data['query2'] = $get_request_type;
			$data['query3'] = $get_amount;
		    return $data;
		}
		
	}

	function get_request_history($data){
		$finalquery = $this->db->query('SELECT DATE_OF_REQUEST, TRADE_VENDOR_TYPE, REQUEST_TYPE, ID_TYPE, UPPER(LAST_NAME) AS LAST_NAME, UPPER(MIDDLE_INITIAL) AS MIDDLE_INITIAL, UPPER(FIRST_NAME) AS FIRST_NAME, UPPER(DESIGNATION) AS DESIGNATION, QUANTITY FROM SMNTP_VENDOR_REQUEST_HISTORY WHERE VENDOR_INVITE_ID = "'.$data['invite_id'].'" ORDER BY DATE_OF_REQUEST DESC')->result_array();

		$data['query'] = $finalquery;

	
		return $data;
	}
	function get_invite_details($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_INVITE SVI');
		$this->db->join('SMNTP_VENDOR_INVITE_TEMPLATE SVIT', 'SVIT.VEN_INV_ID = SVI.TEMPLATE_ID', 'LEFT');
		$this->db->join('SMNTP_CREDENTIALS SC', 'SC.USER_ID = SVI.USER_ID', 'LEFT');
		$this->db->where('SVI.VENDOR_INVITE_ID', $data['invite_id']);

		$query = $this->db->get();

		return $query;
	}

	function create_user($data)
	{	
		$user_name	= '';
		$user_id	= '';

		$this->db->insert('SMNTP_USERS', $data);
	
		if ($this->db->affected_rows() == 1)
		{
			$this->db->select('*');
			$this->db->from('SMNTP_USERS');
			$this->db->where($data); // to get the ID of newly insert
			$this->db->order_by('USER_ID DESC')->limit(2); // 2 because they use < not <= 

			$query = $this->db->get();

			$user_id = $query->row()->USER_ID;
			
			$user_name = $this->generate_username();

			$record = array(
					'USER_ID'	=> $user_id,
					'USERNAME'	=> $user_name,
					'DEACTIVATED_FLAG' => '0',
					'TIME_STAMP' => date('Y-m-d H:i:s.u')
				);

			$this->db->insert('SMNTP_CREDENTIALS', $record);
			
		}
		

		$var['user_name'] 	= $user_name;
		$var['user_id'] 	= $user_id;

		return $var;
	}

	function generate_username()
	{
		$n = 1;
		//AAA001  add leading zeroes
		$n = sprintf("%03d", $n);
		$user_name = 'AAA'.$n;

		$this->db->select('USER_ID');
		$this->db->from('SMNTP_CREDENTIALS');
		$this->db->where('USERNAME', $user_name);

		$rs2 = $this->db->get();
		while ( $rs2->num_rows() > 0 ) #while the code exist generate new one
		{
			//$this->db->select('USER_NAME_TEMP_ID.nextval', false);
			//// $this->db->from();
			//$rs = $this->db->get('DUAL');

			//$n = 1;
			//if ($rs->num_rows() > 0)
			//{
			//	$row = $rs->first_row();	
			//	$n = strtoupper($row->NEXTVAL);		
			//}

			$n = sprintf("%03d", rand(0,999));
			$user_name = 'AAA'.$n;

			$this->db->select('USER_ID');
			$this->db->from('SMNTP_CREDENTIALS');
			$this->db->where('USERNAME', $user_name);
			$rs2 = $this->db->get();
		}

		return $user_name;
	}

	function update_vendor_invite($data, $invite_id)
	{
		$this->db->where('VENDOR_INVITE_ID', $invite_id);
		$this->db->update('SMNTP_VENDOR_INVITE', $data);
	}

	// Added MSF - 20191105 (IJR-10612)
	function update_email($data, $invite_id){
		$this->db->where('VENDOR_INVITE_ID', $invite_id);
		$this->db->update('SMNTP_VENDOR_INVITE', $data);
	}

	function create_token($data)
	{
		$token = md5( microtime() );
		$this->db->query("DELETE FROM SMNTP_VENDOR_TOKEN WHERE USER_ID= '" . $data['user_id'] ."'");

		$record = array(
				'TOKEN'				=> $token,
				'USER_ID'			=> $data['user_id'],
				'DATE_CREATED'		=> date('Y-m-d H:i:s'),
				'VENDOR_INVITE_ID'	=> $data['invite_id'],
				'ACTIVE'			=> '1'
			);

		$this->db->insert('SMNTP_VENDOR_TOKEN', $record);

		return $token;
	}
	
	//For user token (not for vendors)
	function create_user_token($data)
	{
		$token = md5( microtime() );
		$this->db->query("DELETE FROM SMNTP_USERS_TOKENS WHERE USER_ID= '" . $data['user_id'] ."'");

		$record = array(
			'TOKEN'				=> $token,
			'USER_ID'			=> $data['user_id']
		);

		$this->db->insert('SMNTP_USERS_TOKENS', $record);

		return $token;
	}
	
	function get_sender($data){
		return $this->db->query('SELECT U.USER_FIRST_NAME, U.USER_MIDDLE_NAME, U.USER_LAST_NAME, U.USER_EMAIL, VI.CREATED_BY FROM SMNTP_VENDOR_INVITE VI JOIN SMNTP_USERS U ON VI.CREATED_BY = U.USER_ID WHERE VENDOR_INVITE_ID = ? ', array($data))->result();
	}
	
	function get_sender_email($data){
		return $this->db->query('SELECT USER_EMAIL FROM SMNTP_USERS WHERE USER_ID = ? ', array($data))->result();
	}
}
?>