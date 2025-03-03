<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Activation_model extends CI_Model{

		function getmaintable_activation($data)
		{
			//Jay
			$query_string = ' SELECT ROW_NUMBER() OVER ( ORDER BY T.DEACTIVATION_DATE ) AS ROWNUM, T.* FROM 
			(
			SELECT DISTINCT VI.VENDOR_NAME, VI.VENDOR_INVITE_ID, V.VENDOR_CODE, CAST(DATE_FORMAT(VS.DATE_UPDATED,"%m/%d/%Y") AS CHAR) AS 
			DEACTIVATION_DATE, VS.STATUS_ID as CURRENT_STATUS_ID
			FROM SMNTP_VENDOR_INVITE VI
			INNER JOIN SMNTP_CREDENTIALS C ON C.USER_ID = VI.USER_ID
			INNER JOIN SMNTP_VENDOR_STATUS VS ON VS.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID
			LEFT JOIN SMNTP_VENDOR V ON V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID
			LEFT JOIN SMNTP_USERS_MATRIX UM ON UM.USER_ID=VI.CREATED_BY
			WHERE ';
			
			$where = '';
			$array = array();
			/*
			$this->db->select('ROWNUM, VI.VENDOR_NAME, VI.VENDOR_INVITE_ID, V.VENDOR_CODE, to_char(VS.DATE_UPDATED,'."'MM/DD/YYYY'".')  AS DEACTIVATION_DATE, VS.STATUS_ID as CURRENT_STATUS_ID', FALSE);
			$this->db->from('SMNTP_VENDOR_INVITE VI');
			$this->db->join('SMNTP_CREDENTIALS C', 'C.USER_ID = VI.USER_ID', 'INNER');
			$this->db->join('SMNTP_VENDOR_STATUS VS', 'VS.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID', 'INNER');
			$this->db->join('SMNTP_VENDOR V', 'V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID', 'LEFT');
			$this->db->join('SMNTP_USERS_MATRIX UM', 'UM.USER_ID=VI.CREATED_BY', 'LEFT');
			*/
			
			if(!empty($data['vendorname'])){
				$where .='upper(VI.VENDOR_NAME) LIKE \'%'.strtoupper($data['vendorname']).'%\'AND ';
				//$this->db->where('upper(VI.VENDOR_NAME) LIKE \'%'.strtoupper($data['vendorname']).'%\'', '', false);
			}

			if(!empty($data['vendorcode'])){
				$where .= 'upper(V.VENDOR_CODE) LIKE \'%'.strtoupper($data['vendorcode']).'%\'AND ';
				//$this->db->where('upper(V.VENDOR_CODE) LIKE \'%'.strtoupper($data['vendorcode']).'%\'');
			}

			if($data['position_id'] == 7){
				$where .= 'VI.BUSINESS_TYPE = 2 AND ';
				$where .= 'UM.USER_ID = ? AND ';
				$array[] = $data['user_id'];
				//$this->db->where('VI.BUSINESS_TYPE', 2);
			}elseif($data['position_id'] == 2){
				$where .= 'VI.BUSINESS_TYPE = 1 AND ';
				$where .= 'UM.USER_ID = ? AND ';
				$array[] = $data['user_id'];
				//$this->db->where('VI.BUSINESS_TYPE', 1);
			}elseif($data['position_id'] == 11){
				$where .= 'VI.BUSINESS_TYPE = 3 AND ';
				$where .= 'UM.USER_ID = ? AND ';
				$array[] = $data['user_id'];
				//$this->db->where('VI.BUSINESS_TYPE', 1);
			}elseif($data['position_id'] == 4){
				$where .= 'UM.VRDSTAFF_ID = ? AND ';
				$array[] = $data['user_id'];
				//$this->db->where('UM.VRDSTAFF_ID', $data['user_id']);
			}elseif($data['position_id'] == 3){
				$where .= 'UM.BUHEAD_ID = ? AND ';
				$array[] = $data['user_id'];
				//$this->db->where('UM.VRDSTAFF_ID', $data['user_id']);
			}
			else{
				$where .= 'UM.VRDHEAD_ID = ? AND ';
				$array[] = $data['user_id'];
				//$this->db->where('UM.VRDHEAD_ID', $data['user_id']);
			}
			
			/*if($data['position_id'] == 7 || $data['position_id'] == 4 || $data['position_id'] == 2|| $data['position_id'] == 11){
				$where .= 'VS.STATUS_ID = 191 AND ';
				//$this->db->where('VS.STATUS_ID', 191);
			}else{
				$where .= 'VS.STATUS_ID = 193 AND ';
				//$this->db->where('VS.STATUS_ID', 193);
			}*/


			//marc		
			if($data['position_id'] == 2 || $data['position_id'] == 7|| $data['position_id'] == 11){//buyer-senmer-NTS
				$where .= 'VS.STATUS_ID = 191 AND ';
			}
			if($data['position_id'] == 8 || $data['position_id'] == 3){//ghead-buhead
				$where .= 'VS.STATUS_ID = 196 AND ';				
			}

/*			if($data['position_id'] == 5){ //vrd head
				$where .= 'VS.STATUS_ID = 196 AND ';
				$where .= 'VS.ADDITIONAL_DEACTIVATED_FLAG = 1 AND ';
			}*/

			if($data['position_id'] == 4 || $data['position_id'] == 5){//vrdstaff //vrdhead-acttivate account
				$where .= 'VS.STATUS_ID = 193 AND ';					
			}
			//end



			$where .= 'C.DEACTIVATED_FLAG = 1 ';
			//$this->db->where('C.DEACTIVATED_FLAG', 1);
			$query_string .= $where . ') T';
			$query = $this->db->query($query_string, $array);

			//return $this->db->last_query();
			
			//$query = $this->db->get();

			$rs['query'] = $query->result_array();
			$rs['lq'] = $this->db->last_query();
			$rs['resultscount'] = $query->num_rows();

			return $rs;
		}

		function activate_selected($data)
		{

			//$date_timestamp = date('m/d/Y h:i:s A');
			//$date_timestamp = DateTime::createFromFormat('m/d/Y h:i:s A', $date_timestamp);
			//$date_timestamp = $date_timestamp->format("d-M-y h.i.s.u A");
			$date_timestamp = date('Y/m/d H:i:s');

			$isprimary = 0;
			//$current_date = date('d-M-Y');
			$ard_expiration = '';
			$field = '';

			$update_array = array();

	
			if($data['position_id'] == 7 ||$data['position_id'] == 4 || $data['position_id'] == 2 || $data['position_id'] == 11) // buyer or vrdstaff or senmer or nts
			{
				$isprimary = 1;
				$field = 'PRIMARY_START_DATE';
			
				/*$expiration_days = $this->db->get_where('SMNTP_SYSTEM_CONFIG', array(
					'CONFIG_NAME' => 'primary_requirement_extension'
				))->row_array();
				$current_date = 'CURRENT_DATE + ' . $expiration_days['CONFIG_VALUE'];*/
			}
			elseif($data['position_id'] == 5) // vrd gead
			{
				$isprimary = 2;
				$field = 'ADDITIONAL_START_DATE';
				$update_array['ADDITIONAL_DEACTIVATED_FLAG'] = 0;
				$expiration_days = $this->db->get_where('SMNTP_SYSTEM_CONFIG', array(
					'CONFIG_NAME' => 'additional_requirement_deactivate'
				))->row_array();
				
				//$update_array[$field] = 'CURRENT_DATE';
				$ard_expiration = 'CURRENT_DATE + ' . $expiration_days['CONFIG_VALUE'];
				//$update_array['ADDITIONAL_EXPIRATION'] = $ard_expiration;
			}

			$update_array['STATUS_ID'] = $data['next_status_id'];
			$update_array['POSITION_ID'] = $data['next_position_id'];

			for($i = 1; $i <= $data['numselected']; $i++)
			{
				if($data['position_id'] == 5){
					$user_id = $this->db->query('SELECT USER_ID FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID = ?',$data['vendorinviteid'.$i])->result_array();
					$user_status_logs = array(
						'USER_ID' => $user_id[0]['USER_ID'],
						'USER_STATUS_ID' => 1,
						'DATE_MODIFIED' => date("Y-m-d h:i:s")
					);

					$susl = $this->db->insert('SMNTP_USERS_STATUS_LOGS',$user_status_logs);

					$this->db->set('ADDITIONAL_START_DATE', 'CURRENT_TIMESTAMP', FALSE);
					//$this->db->set('ADDITIONAL_EXPIRATION', $ard_expiration, FALSE);
				}

				//$update_array[$field] = $current_date;
				$this->db->where('VENDOR_INVITE_ID', $data['vendorinviteid'.$i]);
				$this->db->set('DATE_UPDATED', 'CURRENT_TIMESTAMP', FALSE);
				$this->db->update('SMNTP_VENDOR_STATUS', $update_array);

				$this->db->flush_cache();

				if($data['position_id'] == 5)
				{
					$this->db->select('USER_ID');
					$this->db->from('SMNTP_VENDOR_INVITE');
					$this->db->where('VENDOR_INVITE_ID', $data['vendorinviteid'.$i]);

					$rs = $this->db->get();

					$userid = $rs->row(0)->USER_ID;

	        		$this->db->flush_cache();


	        		$this->db->where('USER_ID', $userid);

	        		$login_array = array('DEACTIVATED_FLAG' => 0);

					$this->db->update('SMNTP_CREDENTIALS', $login_array);

					$log_array = array('USER_ID' => $data['user_id'],
									   'ACTION_ID' => 29,
									   'ACTIVE' => 1,
									   'DATE_CREATED' => $date_timestamp);

					$this->db->insert('SMNTP_ACTION_LOGS', $log_array);

				}else
				{
					$log_array = array('USER_ID' => $data['user_id'],
									   'ACTION_ID' => 30,
									   'ACTIVE' => 1,
									   'DATE_CREATED' => $date_timestamp);

					$this->db->insert('SMNTP_ACTION_LOGS', $log_array);
				}


				$this->db->select('VENDOR_INVITE_STATUS_ID');
				$this->db->from('SMNTP_VENDOR_STATUS');
				$this->db->where(array('VENDOR_INVITE_ID' => $data['vendorinviteid'.$i]));
				$this->db->order_by('VENDOR_INVITE_STATUS_ID','desc');
				$res = $this->db->get()->result_array();

				$insert_logs_array = array(
					'POSITION_ID' => $data['next_position_id'],
					'VENDOR_INVITE_ID' => $data['vendorinviteid'.$i],
					'STATUS_ID' => $data['next_status_id'],
					'APPROVER_ID' => $data['user_id'],
					'DATE_UPDATED' => $date_timestamp
				);

				if(!empty($res[0]['VENDOR_INVITE_STATUS_ID'])){
					$insert_logs_array['VENDOR_INVITE_STATUS_ID'] = $res[0]['VENDOR_INVITE_STATUS_ID'];
				}



				$activation_logs = $this->activation_logs($insert_logs_array);

				//return $activation_logs;

			}



			return 1;
		}

		function get_vendor_data($user_id, $position_id)
		{
			//Jay
			$query_string = ' SELECT ROW_NUMBER() OVER ( ORDER BY T.DEACTIVATION_DATE ) AS ROWNUM, T.* FROM 
			(
			SELECT DISTINCT VI.VENDOR_NAME, VI.VENDOR_INVITE_ID, V.VENDOR_CODE, CAST(DATE_FORMAT(VS.DATE_UPDATED,"%m/%d/%Y") AS CHAR) AS DEACTIVATION_DATE
			FROM SMNTP_VENDOR_INVITE VI
			INNER JOIN SMNTP_CREDENTIALS C ON C.USER_ID = VI.USER_ID
			INNER JOIN SMNTP_VENDOR_STATUS VS ON VS.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID
			LEFT JOIN SMNTP_VENDOR V ON V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID
			LEFT JOIN SMNTP_USERS_MATRIX UM ON UM.USER_ID=VI.CREATED_BY 
			WHERE ';
		
			/*
			$this->db->select('ROWNUM, VI.VENDOR_NAME, VI.VENDOR_INVITE_ID, V.VENDOR_CODE, to_char(VS.DATE_UPDATED,'."'MM/DD/YYYY'".')   AS "DEACTIVATION_DATE"', FALSE);
			$this->db->from('SMNTP_VENDOR_INVITE VI');
			$this->db->join('SMNTP_CREDENTIALS C', 'C.USER_ID = VI.USER_ID', 'INNER');
			$this->db->join('SMNTP_VENDOR_STATUS VS', 'VS.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID', 'INNER');
			$this->db->join('SMNTP_VENDOR V', 'V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID', 'LEFT');
			$this->db->join('SMNTP_USERS_MATRIX UM', 'UM.USER_ID=VI.CREATED_BY', 'LEFT');
			*/
			$where = '';
			if($position_id == 7 || $position_id == 8){
				$where .= 'VI.BUSINESS_TYPE = 2 AND ';
				//$this->db->where('VI.BUSINESS_TYPE', 2);
			}
			elseif($position_id == 2 || $position_id == 3){
				$where .= 'VI.BUSINESS_TYPE = 1 AND ';
				//$this->db->where('VI.BUSINESS_TYPE', 1);
			}

			if($position_id == 2 || $position_id == 7){
				$where .= 'UM.USER_ID = ? AND ';
				//$this->db->where('UM.USER_ID', $user_id);
			}elseif($position_id == 3){
				$where .= 'UM.BUHEAD_ID = ? AND ';
				//$this->db->where('UM.BUHEAD_ID', $user_id);
			}elseif($position_id == 8){
				$where .= 'UM.GHEAD_ID = ? AND ';
				//$this->db->where('UM.GHEAD_ID', $user_id);
			}elseif($position_id == 4){
				$where .= 'UM.VRDSTAFF_ID = ? AND ';
				//$this->db->where('UM.VRDSTAFF_ID', $user_id);
			}else{
				$where .= 'UM.VRDHEAD_ID = ? AND ';
				//$this->db->where('UM.VRDHEAD_ID', $user_id);
			}

			if($position_id == 7 || $position_id == 4 || $position_id == 2){
				$where .= 'VS.STATUS_ID = 191 AND ';
				//$this->db->where('VS.STATUS_ID', 191);
			}elseif($position_id == 8 || $position_id == 3){
				$where .= 'VS.STATUS_ID = 196 AND ';
				//$this->db->where('VS.STATUS_ID', 196);
			}else{
				$where .= 'VS.STATUS_ID = 193 AND ';
				//$this->db->where('VS.STATUS_ID', 193);
			}
			
			$where .= 'C.DEACTIVATED_FLAG = 1 ';
			//$this->db->where('C.DEACTIVATED_FLAG', 1);
			$query_string .= $where . ') T';
			$query = $this->db->query($query_string, array($user_id));
			//$query = $this->db->get();

			$rs['lq'] = $this->db->last_query();
			$rs['query'] = $query->result_array();

			$rs['resultscount'] = $query->num_rows();

			return $rs;
		}

		function getapprovaltable_activation($data)
		{	
			//Jay
			$query_string = ' SELECT ROW_NUMBER() OVER ( ORDER BY T.DEACTIVATION_DATE ) AS ROWNUM, T.* FROM 
			(
			SELECT DISTINCT VI.VENDOR_NAME, VI.VENDOR_INVITE_ID, V.VENDOR_CODE, 
			CAST(DATE_FORMAT(VS.DATE_UPDATED,"%m/%d/%Y") AS CHAR) AS DEACTIVATION_DATE, 
			VS.STATUS_ID as CURRENT_STATUS_ID
			FROM SMNTP_VENDOR_INVITE VI
			INNER JOIN SMNTP_CREDENTIALS C ON C.USER_ID = VI.USER_ID
			INNER JOIN SMNTP_VENDOR_STATUS VS ON VS.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID
			LEFT JOIN SMNTP_VENDOR V ON V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID
			LEFT JOIN SMNTP_USERS_MATRIX UM ON UM.USER_ID=VI.CREATED_BY 
			WHERE ';
			/*
			$this->db->select('ROWNUM, VI.VENDOR_NAME, VI.VENDOR_INVITE_ID, V.VENDOR_CODE, to_char(VS.DATE_UPDATED,'."'MM/DD/YYYY'".')  AS DEACTIVATION_DATE, VS.STATUS_ID as CURRENT_STATUS_ID', FALSE);
			$this->db->from('SMNTP_VENDOR_INVITE VI');
			$this->db->join('SMNTP_CREDENTIALS C', 'C.USER_ID = VI.USER_ID', 'INNER');
			$this->db->join('SMNTP_VENDOR_STATUS VS', 'VS.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID', 'INNER');
			$this->db->join('SMNTP_VENDOR V', 'V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID', 'LEFT');
			$this->db->join('SMNTP_USERS_MATRIX UM', 'UM.USER_ID=VI.CREATED_BY', 'LEFT');
			*/
			
			$where = '';
			if(!empty($data['vendorname'])){
				$where .= 'upper(VI.VENDOR_NAME) LIKE \'%'.strtoupper($data['vendorname']).'%\' AND ';
				//$this->db->where('upper(VI.VENDOR_NAME) LIKE \'%'.strtoupper($data['vendorname']).'%\'', '', false);
			}

			if(!empty($data['vendorcode'])){
				$where .= 'upper(V.VENDOR_CODE) LIKE \'%'.strtoupper($data['vendorcode']).'%\' AND ';
				//$this->db->where('upper(V.VENDOR_CODE) LIKE \'%'.strtoupper($data['vendorcode']).'%\'');
			}

			if($data['position_id'] == 3){
				$where .= 'VI.BUSINESS_TYPE IN(1, 3) AND ';
				//$this->db->where('VI.BUSINESS_TYPE', 1);
			}elseif($data['position_id'] == 8){
				$where .= 'VI.BUSINESS_TYPE = 2 AND ';
				//$this->db->where('VI.BUSINESS_TYPE', 2);
			}
/*	
		if($data['position_id'] == 3 || $data['position_id'] == 8){
				$where .= 'VS.STATUS_ID IN (191,196) AND ';
			}else{
				$where .= 'VS.STATUS_ID = 193 AND ';
			}
*/
			
			if($data['position_id'] == 2 || $data['position_id'] == 7){
				$where .= 'UM.USER_ID = ? AND ';
				//$this->db->where('UM.USER_ID', $user_id);
			}elseif($data['position_id'] == 3){
				$where .= 'UM.BUHEAD_ID = ? AND ';
				//$this->db->where('UM.BUHEAD_ID', $user_id);
			}elseif($data['position_id'] == 8){
				$where .= 'UM.GHEAD_ID = ? AND ';
				//$this->db->where('UM.GHEAD_ID', $user_id);
			}elseif($data['position_id'] == 4){
				$where .= 'UM.VRDSTAFF_ID = ? AND ';
				//$this->db->where('UM.VRDSTAFF_ID', $user_id);
			}else{
				$where .= 'UM.VRDHEAD_ID = ? AND ';
				//$this->db->where('UM.VRDHEAD_ID', $user_id);
			}

			if($data['position_id'] == 5){
				$where .= 'VS.STATUS_ID = 196 AND ';
				$where .= 'VS.ADDITIONAL_DEACTIVATED_FLAG = 1 AND ';
			}elseif($data['position_id'] == 4){
				$where .= 'VS.STATUS_ID = 193 AND ';
				$where .= 'VS.ADDITIONAL_DEACTIVATED_FLAG = 1 AND ';
			}elseif($data['position_id'] == 3 || $data['position_id'] == 8){
				$where .= 'VS.STATUS_ID = 196 AND ';
				$where .= 'VS.PRIMARY_DEACTIVATED_FLAG = 1 AND ';
			}elseif($data['position_id'] == 11){
				$where .= 'VS.STATUS_ID = 191 AND ';
				$where .= 'VS.PRIMARY_DEACTIVATED_FLAG = 1 AND ';
			}else{
				$where .= 'VS.STATUS_ID = 193 AND ';
			}
			

			//$this->db->where('VS.STATUS_ID', 196);
			
			if($data['position_id'] != 3 && $data['position_id'] != 8 && $data['position_id'] != 4){
				//$where .= 'VS.POSITION_ID = ? AND ';
			}
			//$this->db->where('VS.POSITION_ID', $data['position_id']);


			$where .= 'C.DEACTIVATED_FLAG = 1';
			//$this->db->where('C.DEACTIVATED_FLAG', 1);
			
			$query_string .= $where . ') T';
			$query = $this->db->query($query_string, array($data['user_id']));
			//$query = $this->db->get();
			$rs['query'] = $query->result_array();
			$rs['lq'] = $this->db->last_query();
			
			$rs['resultscount'] = $query->num_rows();

			return $rs;
		}

		function approve_selected($data)
		{

			///return $data;
			//$date_timestamp = date('m/d/Y h:i:s A');
			//$date_timestamp = DateTime::createFromFormat('m/d/Y h:i:s A', $date_timestamp);
			//$date_timestamp = $date_timestamp->format("d-M-y h.i.s.u A");
			$date_timestamp = date('Y/m/d H:i:s');

			for($i = 1; $i <= $data['numselected']; $i++)
			{

				if($data['position_id'] == 5){

					$user_id = $this->db->query('SELECT USER_ID FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID = ?',$data['vendorinviteid'.$i])->result_array();
					$user_status_logs = array(
						'USER_ID' => $user_id[0]['USER_ID'],
						'USER_STATUS_ID' => 1,
						'DATE_MODIFIED' => date("Y-m-d h:i:s")
					);

					$susl = $this->db->insert('SMNTP_USERS_STATUS_LOGS',$user_status_logs);
					
					$user_array = array('USER_STATUS' => 1);
					$this->db->where('USER_ID', $user_id[0]['USER_ID']);
					$this->db->update('SMNTP_USERS', $user_array);
					
					$credential_array = array('DEACTIVATED_FLAG' => 0);
					$this->db->where('USER_ID', $user_id[0]['USER_ID']);
					$this->db->update('SMNTP_CREDENTIALS', $credential_array);
					
					$expiration_days = $this->db->get_where('SMNTP_SYSTEM_CONFIG', array(
						'CONFIG_NAME' => 'additional_requirement_deactivate'
					))->row_array();
					$update_array = array('STATUS_ID' => 190,
						'POSITION_ID' => 10,
						'PRIMARY_DEACTIVATED_FLAG' => 0,
						'ADDITIONAL_DEACTIVATED_FLAG' => 0
					);
					$this->db->set('ADDITIONAL_START_DATE', 'CURRENT_DATE', FALSE);
					//$this->db->set('ADDITIONAL_EXPIRATION',  'CURRENT_DATE + '. $expiration_days['CONFIG_VALUE'], FALSE);
				}else{
					
					
					$expiration_days = $this->db->get_where('SMNTP_SYSTEM_CONFIG', array(
						'CONFIG_NAME' => 'primary_requirement_extension'
					))->row_array();
					$update_array = array('STATUS_ID' => 9,
						'POSITION_ID' => 10,
						'PRIMARY_DEACTIVATED_FLAG' => 0,
						'ADDITIONAL_DEACTIVATED_FLAG' => 0
					);
					$this->db->set('PRIMARY_START_DATE', 'CURRENT_DATE', FALSE);
					//$this->db->set('PRIMARY_EXPIRATION', 'CURRENT_DATE + ' . $expiration_days['CONFIG_VALUE'], FALSE);


				}
				$this->db->set('DATE_UPDATED', 'CURRENT_DATE', FALSE);
/*				$update_array = array('STATUS_ID' => 9,
									  'POSITION_ID' => 10,
									  'PRIMARY_DEACTIVATED_FLAG' => 0,
									  'ADDITIONAL_DEACTIVATED_FLAG' => 0
									  );

									  //*/


				$this->db->where('VENDOR_INVITE_ID', $data['vendorinviteid'.$i]);
				$this->db->update('SMNTP_VENDOR_STATUS', $update_array);
	        	$this->db->flush_cache();

				$this->db->select('USER_ID');
				$this->db->from('SMNTP_VENDOR_INVITE');
				$this->db->where('VENDOR_INVITE_ID', $data['vendorinviteid'.$i]);

				$rs = $this->db->get();

				$userid = $rs->row(0)->USER_ID;

        		$this->db->flush_cache();


        		$this->db->where('USER_ID', $userid);

        		$login_array = array('DEACTIVATED_FLAG' => 0);

				$this->db->update('SMNTP_CREDENTIALS', $login_array);

			}

			$log_array = array('USER_ID' => $data['user_id'],
							   'ACTION_ID' => 29,
							   'ACTIVE' => 1,
							   'DATE_CREATED' => $date_timestamp);

			$this->db->insert('SMNTP_ACTION_LOGS', $log_array);


			return $data;

		}

		function get_emails($vendor_invite_id)
		{
			$email = '';

			$this->db->select('U.USER_EMAIL');
			$this->db->from('SMNTP_VENDOR_INVITE VI');
			$this->db->join('SMNTP_USERS U', 'U.USER_ID=VI.USER_ID');
			$this->db->where('VI.VENDOR_INVITE_ID', $data['vendorinviteid'.$i]);

			$rs = $this->db->get();

			if($rs->num_rows() > 0)
				$email = $rs->row(0)->USER_EMAIL;

			return $email;

		}

		function activation_logs($data){


			$res = $this->db->insert('SMNTP_VENDOR_STATUS_LOGS',$data);


			return $this->db->last_query();

		}

		function get_vendor_invite_status($data)
		{

			$this->db->select('VENDOR_INVITE_STATUS_ID');
			$this->db->from('SMNTP_VENDOR_STATUS');
			$this->db->where(array('VENDOR_INVITE_ID' => $data));
			$this->db->order_by('VENDOR_INVITE_STATUS_ID','desc');
			$res = $this->db->get()->result_array();

			return $res;


		}



}

?>