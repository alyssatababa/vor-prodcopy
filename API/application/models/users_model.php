<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Users_model extends CI_Model{

		function read($username, $password){
			/*$this->db->select("U.USER_ID,
				U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				U.VENDOR_ID,
				A.ATTEMPTS,
				A.UNLOCK_TIME,
				A.LAST_ATTEMPT,
				AL.DATE_CREATED AS RAW_LOGIN_DATE,
				CAST(DATE_FORMAT(AL.DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS LAST_LOGIN,
				CASE WHEN C.PASSWORD='".$password."' THEN 1 ELSE 0 END AS PASSWORD_VALID,
				U.USER_TYPE_ID,
				U.POSITION_ID,
				U.USER_STATUS,
				U.USER_MOBILE,
				U.USER_EMAIL,
				T.USER_TYPE,
				VD.SVDID,
				P.POSITION_NAME,
				VI.VENDOR_INVITE_ID,
				P.POSITION_CODE,
				P.BUSINESS_TYPE,
				VI.BUSINESS_TYPE AS V_BUSINESS_TYPE",false);
			$this->db->from('SMNTP_USERS U');
			$this->db->join('SMNTP_CREDENTIALS C', 'C.USER_ID = U.USER_ID','INNER');
			$this->db->join('SMNTP_USER_TYPES T', 'T.USER_TYPE_ID = U.USER_TYPE_ID','INNER');
			$this->db->join('SMNTP_POSITION P', 'P.POSITION_ID = U.POSITION_ID','INNER');
			$this->db->join('SMNTP_LOGIN_ATTEMPTS A', 'U.USER_ID = A.USER_ID','LEFT');
			$this->db->join('SMNTP_VENDOR_DPA VD', 'U.USER_ID = VD.USER_ID AND VD.ACTIVE = 1','LEFT');
			$this->db->join('SMNTP_ACTION_LOGS AL', 'AL.USER_ID = U.USER_ID','LEFT');
			$this->db->join('SMNTP_ACTIONS SA', 'SA.ACTION_ID = AL.ACTION_ID','LEFT');
			$this->db->join('SMNTP_VENDOR_INVITE VI', 'U.USER_ID = VI.USER_ID','LEFT');
			$this->db->where('C.USERNAME', $username);
			$this->db->where('C.DEACTIVATED_FLAG', 0);
			$this->db->order_by('AL.DATE_CREATED', 'DESC');
			$this->db->limit(3);*/
			
			
			// tip: when accessing an index of an array, always check if the array index exists. don't assume that it exists. sure its boring and you may forget it when on a rush, but it helps and its a good practice ;)
			// $user_id = $this->db->query('SELECT USER_ID FROM SMNTP_CREDENTIALS WHERE USERNAME = ?', array($username))->result_array()[0]['USER_ID'];
			
			$this->load->library('CryptManager');
			$user_info = $this->db->query('SELECT CREDENTIAL_ID,USER_ID,USERNAME,PASSWORD,CONCAT(DATE_FORMAT(TIME_STAMP,"%d-"),UCASE(DATE_FORMAT(TIME_STAMP,"%b")),DATE_FORMAT(TIME_STAMP,"-%y %h.%i.%s.%f %p")) AS TIME_STAMP,DEACTIVATED_FLAG FROM SMNTP_CREDENTIALS WHERE USERNAME = ?', array($username))->row_array();
			
			$user_info_id = $user_info_password = '';
			$valid_pass = 0;
			if( ! empty($user_info)){
				$user_info_id = $user_info['USER_ID'];
				$time_stamp = $user_info['TIME_STAMP'];
				$user_info_password = $user_info['PASSWORD'];
				
				$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $time_stamp . $user_info_id)), 0, 16);
				$enc_password = $this->cryptmanager->encrypt($password, FALSE, $file_crypt_key);
				$dec_password = base64_decode($this->cryptmanager->decrypt($user_info_password, FALSE, $file_crypt_key));
				//log_message('debug', 'Fiel Crypt Key: ' . $file_crypt_key);
				//log_message('debug', 'Raw Password: ' . $password);
				//log_message('debug', 'Input Password: ' . $enc_password);
				//log_message('debug', 'Saved Password: ' . $user_info_password);
				if($dec_password == $password){
					$valid_pass = 1;
					//log_message('debug', 'Result Passowrd: ' . $valid_pass);
				}
			}
			
			$user_query = $this->db->query('SELECT USER_ID FROM SMNTP_CREDENTIALS WHERE USERNAME = ?', array($username));
			if ($user_query && $user_query->num_rows() > 0) {
				$user_id = $user_query->result_array()[0]['USER_ID'];
				//AND "C"."DEACTIVATED_FLAG" = 0 
				$string_query = 'SELECT a.* FROM (select inner_query.*													FROM (SELECT U.USER_ID, U.USER_FIRST_NAME, U.USER_MIDDLE_NAME, U.USER_LAST_NAME, U.VENDOR_ID, V.VENDOR_CODE, 
																	A.ATTEMPTS, A.UNLOCK_TIME, A.LAST_ATTEMPT, AL.DATE_CREATED AS RAW_LOGIN_DATE, 
																	DATE_FORMAT(AL.DATE_CREATED,"%m/%d/%Y %h:%i %p") AS LAST_LOGIN, ' . $valid_pass .' AS PASSWORD_VALID, 
																	U.USER_TYPE_ID, U.POSITION_ID, U.USER_STATUS, U.USER_MOBILE, U.USER_EMAIL, 
																	T.USER_TYPE, VD.SVDID, P.POSITION_NAME, VI.VENDOR_INVITE_ID, 
																	P.POSITION_CODE, P.BUSINESS_TYPE, VI.BUSINESS_TYPE AS V_BUSINESS_TYPE, C.DEACTIVATED_FLAG, VI.REGISTRATION_TYPE 
                                                                                                                    ,row_number() over ( ORDER BY AL.DATE_CREATED DESC)  rnum
									FROM SMNTP_USERS U
									INNER JOIN SMNTP_CREDENTIALS C ON C.USER_ID = U.USER_ID
									INNER JOIN SMNTP_USER_TYPES T ON T.USER_TYPE_ID = U.USER_TYPE_ID
									INNER JOIN SMNTP_POSITION P ON P.POSITION_ID = U.POSITION_ID
									LEFT JOIN SMNTP_LOGIN_ATTEMPTS A ON U.USER_ID = A.USER_ID
									LEFT JOIN SMNTP_VENDOR_DPA VD ON U.USER_ID = VD.USER_ID AND VD.ACTIVE = 1
									LEFT JOIN SMNTP_ACTION_LOGS AL ON AL.USER_ID = U.USER_ID
									LEFT JOIN SMNTP_ACTIONS SA ON SA.ACTION_ID = AL.ACTION_ID
									LEFT JOIN SMNTP_VENDOR_INVITE VI ON U.USER_ID = VI.USER_ID
									LEFT JOIN SMNTP_VENDOR V ON V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID
									WHERE C.USERNAME = ?
									 
									AND ((SELECT COUNT(*) FROM SMNTP_ACTION_LOGS II WHERE II.USER_ID = ? AND II.DATE_CREATED IS NULL) <= 1 OR AL.DATE_CREATED IS NOT NULL)
									ORDER BY AL.DATE_CREATED DESC
									) inner_query WHERE inner_query.rnum < 3) a';
				
				//$result = $this->db->get();
				$result = $this->db->query($string_query, array($username, $user_id));
				//echo $this->db->last_query();
				// $result = $this->db->get();
				// var_dump($result->result_array());
				return $result->result_array();
			} else {
				return null;
			}			

		}

		function log_action($data)
		{
			//jay
			//Check if there is existing action id in smntp_screens
			if( ! empty($data['SCREEN_ID'])){
				
				//Get the Screen data
				$screen_data = $this->db->query('SELECT ACTION_ID, MENU_LABEL, SCREEN_NAME FROM SMNTP_SCREENS WHERE SCREEN_ID = ?', array($data['SCREEN_ID']))->result_array();					
				
				if( ! empty($screen_data)){
					
					//Action name
					$menu_label = 'Accessed '. $screen_data[0]['SCREEN_NAME'];
					
					//if there is existing id in SMNTP_SCREEN, check the action_id in SMNTP_ACTION table
					if( ! empty($screen_data[0]['ACTION_ID'])){
						
						$action_tbl_result = $this->db->query('SELECT COUNT(*) AS TOTAL FROM SMNTP_ACTIONS WHERE ACTION_ID = ? ', array($screen_data[0]['ACTION_ID']))->result_array()[0]['TOTAL'];
						
						//If action id of screen does not match in smntp_action, search the menu label 
						if(empty($action_tbl_result)){
							
							$action_tbl_result = $this->db->query('SELECT ACTION_ID FROM SMNTP_ACTIONS WHERE UPPER(ACTION_NAME) = ? ', array(strtoupper($menu_label)))->result_array();
							
							//If there is a result, assign the existing ACTION_ID 
							if( ! empty($action_tbl_result)){
								$data['ACTION_ID'] = $action_tbl_result[0]['ACTION_ID'];
								
								//Update the action id
								$this->db->update('SMNTP_SCREENS', array('ACTION_ID' => $data['ACTION_ID']), array('SCREEN_ID' => $data['SCREEN_ID']));
							}else{
								//Insert new action
								$this->db->insert('SMNTP_ACTIONS', array(
									'ACTION_NAME' => $menu_label,
									'ACTION_DESCRIPTION' => $screen_data[0]['SCREEN_NAME']
								));
								
								//Get the last inserted id
								
								$data['ACTION_ID'] = $this->db->query('SELECT ACTION_ID FROM SMNTP_ACTIONS WHERE ACTION_NAME = ?', array($menu_label))->result_array();
							
								$data['ACTION_ID'] = $data['ACTION_ID'][0]['ACTION_ID'];
								
							}
							$this->db->update('SMNTP_SCREENS', array('ACTION_ID' => $data['ACTION_ID']), array('SCREEN_ID' => $data['SCREEN_ID']));
							
						}else{
							//If does exist
							$data['ACTION_ID'] = $screen_data[0]['ACTION_ID'];
						}
						
					}else{
						//If assigned ACTION_ID is NULL 
						
						//Check if there is existing action name
						$action_tbl_result = $this->db->query('SELECT ACTION_ID FROM SMNTP_ACTIONS WHERE UPPER(ACTION_NAME) = ? ', array(strtoupper($menu_label)))->result_array();
						
						//If there is a result, assign the existing ACTION_ID 
						if( ! empty($action_tbl_result)){
							$data['ACTION_ID'] = $action_tbl_result[0]['ACTION_ID'];
						}else{
							//Insert new action
							$this->db->insert('SMNTP_ACTIONS', array(
								'ACTION_NAME' => $menu_label,
								'ACTION_DESCRIPTION' => $screen_data[0]['SCREEN_NAME']
							));
							
							//Get the last inserted id
							$data['ACTION_ID'] = $this->db->query('SELECT ACTION_ID FROM SMNTP_ACTIONS WHERE ACTION_NAME = ?', array($menu_label))->result_array();
							
							$data['ACTION_ID'] = $data['ACTION_ID'][0]['ACTION_ID'];
							
						}
						
					
						//Assign the action_id in screen
						//TABLE, SET, WHERE
						$this->db->update('SMNTP_SCREENS', array('ACTION_ID' => $data['ACTION_ID']), array('SCREEN_ID' => $data['SCREEN_ID']));
					}
				}
			}
			
			$this->db->insert('SMNTP_ACTION_LOGS', array('USER_ID' => $data['USER_ID'], 'ACTION_ID' => $data['ACTION_ID'], 'DATE_CREATED' => date('Y/m/d H:i:s'), 'ACTIVE' => '1'));

			return $data['ACTION_ID'];
		}

		function get_action_logs($user_id)
		{
			$this->db->select('(CURRENT_DATE - AL.DATE_CREATED) AS DATE_SORTING_FORMAT, CONCAT(DATE_FORMAT(AL.DATE_CREATED,"%d-"),UCASE(DATE_FORMAT(AL.DATE_CREATED,"%b")),DATE_FORMAT(AL.DATE_CREATED,"-%y %h.%i.%s.%f %p")) AS ACTION_DATE, A.ACTION_NAME', FALSE);
			$this->db->from('SMNTP_ACTION_LOGS AL');
			$this->db->join('SMNTP_ACTIONS A', 'AL.ACTION_ID = A.ACTION_ID', 'INNER');
			$this->db->where('AL.USER_ID', $user_id);
			$this->db->order_by('AL.DATE_CREATED DESC');
			$this->db->limit(100);
			$rs = $this->db->get();

			return $rs->result_array();
		}
		
		function get_vendor_data($vendor_id){
			return $this->db->get_where('SMNTP_VENDOR', array('VENDOR_ID' => $vendor_id));
		}
		
		function get_vendor_user_data($vendor_id){
			return $this->db->get_where('SMNTP_USERS', array('VENDOR_ID' => $vendor_id));
		}
		
		function get_vendor_assigned_categories($vendor_invite_id){
			return $this->db->get_where('SMNTP_VENDOR_CATEGORIES', array('VENDOR_INVITE_ID' => $vendor_invite_id));
		}
		
		function get_check_migrated_token($vendor_invite_id, $password){
			
			//$this->db->get_where('SMNTP_VENDOR_TOKEN', array('VENDOR_INVITE_ID' => $vendor_invite_id, 'TOKEN' => $password));
			
			$this->db->select('*');
			$this->db->from('SMNTP_VENDOR_TOKEN');
			$this->db->where('VENDOR_INVITE_ID', $vendor_invite_id);
			$this->db->where('TOKEN', $password);
			$this->db->where('ACTIVE', 1);
			$rs = $this->db->get();
			
			return $rs->result_array();
		}
	
	}
?>
