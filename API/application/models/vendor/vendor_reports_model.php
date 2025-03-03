<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Vendor_reports_model extends CI_Model{

		function get_expired_invites_pdf($date_from, $date_to, $cat_filter){
			$this->db->select('*');
			$this->db->from('SMNTP_SYSTEM_CONFIG');
			$this->db->where('CONFIG_NAME', 'invite_expiration_days');

			$query = $this->db->get();

			$config_value = $query->row()->CONFIG_VALUE;

			$query = "SELECT ROW_NUMBER() OVER ( ORDER BY T.EXPIRED_ORDER ) AS ROWNUM, T.* FROM (";
			$query.= "SELECT A.VENDOR_NAME, E.CATEGORY_NAME, 
			CONCAT(CONCAT(CONCAT(CONCAT(C.USER_FIRST_NAME, ' '), C.USER_MIDDLE_NAME), ' '),
			C.USER_LAST_NAME) AS INVITER, UPPER(CAST(DATE_FORMAT(DATE_ADD(A.DATE_CREATED, INTERVAL '".$config_value."' DAY),'%d-%b-%y') AS CHAR)) AS DATE_EXPIRED,
			CAST(DATE_FORMAT(DATE_ADD(A.DATE_CREATED, INTERVAL '".$config_value."' DAY),'%Y-%m-%d') AS CHAR) AS EXPIRED_ORDER ";
			$query.= "FROM SMNTP_VENDOR_INVITE A ";
			$query.= "LEFT JOIN SMNTP_VENDOR_STATUS B ON A.VENDOR_INVITE_ID = B.VENDOR_INVITE_ID ";
			$query.= "LEFT JOIN SMNTP_USERS C ON A.CREATED_BY = C.USER_ID ";
			$query.= "LEFT JOIN SMNTP_VENDOR_CATEGORIES D ON A.VENDOR_INVITE_ID = D.VENDOR_INVITE_ID ";
			$query.= "LEFT JOIN SMNTP_CATEGORY E ON D.CATEGORY_ID = E.CATEGORY_ID ";


			$query.= "WHERE B.STATUS_ID = '5' ";

			if ($date_from != "" || $date_to != "")
				// $query.= "AND TO_CHAR(A.DATE_CREATED + INTERVAL '".$config_value."' DAY, 'DD-MON-YY') >= TO_DATE('".$date_from."', 'YYYY-MM-DD') AND TO_CHAR(A.DATE_CREATED + INTERVAL '".$config_value."' DAY, 'DD-MON-YY') <= TO_DATE('".$date_to."', 'YYYY-MM-DD') ";
				$query.= "AND DATE_ADD(A.DATE_CREATED, INTERVAL '".$config_value."' DAY) >= STR_TO_DATE('".$date_from."','%Y-%m-%d') AND DATE_ADD(A.DATE_CREATED, INTERVAL '".$config_value."' DAY) <= STR_TO_DATE('".$date_to."','%Y-%m-%d') ";


			// if ($cat_filter > 0) {
			// 	$query.= " AND E.CATEGORY_ID = ".$cat_filter." ";
			// }

			$query.= "ORDER BY EXPIRED_ORDER ASC) T";
			
			$result = $this->db->query($query);
			//return $query;
			return $result->result_array();
		}

		//added JRM June 23, 2021
		function get_pending_invites($IDs){
			$query = "SELECT SCr.USERNAME, CONCAT(CONCAT(CONCAT(CONCAT(SU.USER_FIRST_NAME,' '),SU.USER_MIDDLE_NAME),' '), SU.USER_LAST_NAME) AS FULL_NAME, SVI.VENDOR_NAME, SVS.STATUS_ID, SS.STATUS_NAME";
			$query .= ", CASE SVI.REGISTRATION_TYPE WHEN 1 THEN 'New Vendor' WHEN 2 THEN 'Migration' WHEN 3 THEN 'Update Vendor' WHEN 4 THEN 'Add Vendor Code' WHEN 5 THEN 'Change in Company Name' END AS REGISTRATION_TYPE ";
			$query .= "FROM SMNTP_VENDOR_INVITE SVI ";
			$query .= "JOIN SMNTP_VENDOR_STATUS SVS ON SVS.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID ";
			$query .= "JOIN SMNTP_STATUS SS ON SVS.STATUS_ID = SS.STATUS_ID ";
			$query .= "JOIN SMNTP_USERS SU ON SU.USER_ID = SVI.CREATED_BY ";
			$query .= "JOIN SMNTP_CREDENTIALS SCr ON SU.USER_ID = SCr.USER_ID ";
			$query .= "WHERE SVI.CREATED_BY IN $IDs AND SVI.REGISTRATION_TYPE != 2 ";
			$query .= "ORDER BY SU.USER_ID, SVI.VENDOR_INVITE_ID, SVS.VENDOR_INVITE_STATUS_ID";
			
			$result = $this->db->query($query);
			return $result->result_array();
		}
		
		//added JRM June 21, 2021
		function get_usernames(){
			$query = "SELECT SU.USER_ID,SC.USERNAME, SU.USER_FIRST_NAME, SU.USER_LAST_NAME FROM SMNTP_USERS SU LEFT JOIN SMNTP_CREDENTIALS SC ON SU.USER_ID = SC.USER_ID WHERE SU.USER_TYPE_ID = 1 ORDER BY SU.USER_FIRST_NAME";
			$result = $this->db->query($query);
			return $result->result();
		}
		//added JRM June 25, 2021
		function get_vendor_codes(){
			$query = "SELECT VENDOR_CODE, VENDOR_NAME FROM SMNTP_VENDOR ORDER BY VENDOR_NAME";
			$result = $this->db->query($query);
			return $result->result();
		}
		//added JRM June 25, 2021
		function get_contacts_per_vendor($VCodes){
			$query = "SELECT SV.VENDOR_NAME, SV.VENDOR_CODE, 'Authorized Representatives' AS DETAILS, SVR.FIRST_NAME, SVR.MIDDLE_NAME, SVR.LAST_NAME, SVR.POSITION FROM SMNTP_VENDOR SV ";
			$query .= "LEFT JOIN SMNTP_VENDOR_REP SVR ON SV.VENDOR_ID = SVR.VENDOR_ID WHERE SV.VENDOR_CODE IN ".$VCodes;
			$query .= "UNION ";
			$query .= "SELECT SV.VENDOR_NAME, SV.VENDOR_CODE, 'Owners/Partners/Directors' AS DETAILS, SVO.FIRST_NAME, SVO.MIDDLE_NAME, SVO.LAST_NAME, SVO.POSITION FROM SMNTP_VENDOR SV ";
			$query .= "LEFT JOIN SMNTP_VENDOR_OWNERS SVO ON SV.VENDOR_ID = SVO.VENDOR_ID WHERE SV.VENDOR_CODE IN ".$VCodes;
			$result = $this->db->query($query);
			return $result->result();
		}

		function get_active_inacvtive_user($user_type, $user_status){
			$query = 'SELECT
					  A.USER_FIRST_NAME  AS USER_FIRST_NAME,
					  A.USER_MIDDLE_NAME AS USER_MIDDLE_NAME,
					  A.USER_LAST_NAME   AS USER_LAST_NAME,
					  B.USERNAME         AS USERNAME,
					  C.USER_TYPE        AS USER_TYPE,
					  D.POSITION_NAME    AS POSITION_NAME,
					  A.USER_MOBILE      AS USER_MOBILE,
					  A.USER_EMAIL       AS USER_EMAIL,
					  A.USER_ID          AS USER_ID,
					  J.LAST_ATTEMPT     AS LAST_ATTEMPT,
					  A.POSITION_ID      AS POSITION_ID,
					  B.PASSWORD         AS PASSWORD
					FROM SMNTP_USERS A
					      LEFT JOIN SMNTP_CREDENTIALS B
					        ON A.USER_ID = B.USER_ID
					     LEFT JOIN SMNTP_USER_TYPES C
					       ON A.USER_TYPE_ID = C.USER_TYPE_ID
					    LEFT JOIN SMNTP_LOGIN_ATTEMPTS J
					      ON A.USER_ID = J.USER_ID
					   LEFT JOIN SMNTP_POSITION D
					     ON A.POSITION_ID = D.POSITION_ID
					WHERE 1=1
					AND A.USER_ID != 4596362';

			if($user_type == 2){
				$query .= ' AND A.POSITION_ID != 10 '; //SM Users
			}else if($user_type == 1){
				$query .= ' AND A.POSITION_ID = 10 '; // Vendor Users
			}

			if($user_status == 1){ //Active
				//$query .= " AND A.USER_STATUS = 1 ";
				$query .= " AND B.DEACTIVATED_FLAG = 0 ";
			}else if($user_status == 0){ // In-Active
				//$query .= " AND A.USER_STATUS = 0 ";
				$query .= " AND B.DEACTIVATED_FLAG = 1 ";
			}
			
			$result = $this->db->query($query);
			return $result->result();
		}

		function get_active_inactive_report($UserIDs){
			$user_ids = 'X';
			if (strpos($UserIDs, "zzzz") !== false) {
				$UserIDs = str_replace("(","",$UserIDs);
				$UserIDs = str_replace(")","",$UserIDs);
				$UserIDs = str_replace('"',"",$UserIDs);
				$set_var = explode("zzzz",$UserIDs);
				$user_type = $set_var[0];
				$user_status = $set_var[1];
				$user_ids = $set_var[2];
				
				$pos = '';
				if($user_type == 2){
					$pos = ' AND SU.POSITION_ID != 10 '; //SM Users
				}elseif($user_type == 1){
					$pos = ' AND SU.POSITION_ID = 10 '; // Vendor Users
				}
				
				if($user_status != 2){
					$ustatus = "AND SU.USER_STATUS = ".$user_status;
				}else{
					$ustatus = "a";
				}
			}
			
			//Deactivated = 1, User Status = 0; Deactivated = 0, User Status = 1
			
			if($user_ids != 'ALL'){
				$where_clause = " WHERE SU.USER_ID IN ".$UserIDs;
			}else{
				$where_clause = " WHERE 1=1 AND SU.USER_ID != '4596362' ".$ustatus.$pos;
			}
			$query = "SELECT 
						SCred.USERNAME AS LOGIN_ID,
						CASE WHEN SU.USER_LAST_NAME IS NULL THEN SU.USER_FIRST_NAME
						ELSE CONCAT(SU.USER_FIRST_NAME, ' ' , SU.USER_MIDDLE_NAME) END AS USER_NAME,
						SPos.POSITION_NAME, 
						SUT.USER_TYPE,
						CASE WHEN SU.USER_STATUS = 0 THEN 'INACTIVE' ELSE 'ACTIVE' END AS USER_STATUS,
						CASE WHEN SUSL.DATE_MODIFIED IS NULL THEN SU.USER_DATE_CREATED ELSE SUSL.DATE_MODIFIED END AS EFFECTIVE_DATE
						FROM SMNTP_CREDENTIALS SCred
						JOIN SMNTP_USERS SU ON SCred.USER_ID = SU.USER_ID
						JOIN SMNTP_POSITION SPos ON SU.POSITION_ID = SPos.POSITION_ID
						JOIN SMNTP_USER_TYPES SUT ON SU.USER_TYPE_ID = SUT.USER_TYPE_ID
						LEFT JOIN (SELECT * FROM SMNTP_USERS_STATUS_LOGS ORDER BY ID DESC) SUSL ON SU.USER_ID = SUSL.USER_ID
						".$where_clause."
						GROUP BY SU.USER_ID";
			$result = $this->db->query($query);
			return $result->result_array();
		}

		function get_contact_person_per_vendor($vendor_id, $date_from, $date_to){
			if(($date_from == 'noneed' || $date_to == 'noneed') && ($vendor_id == '("ALL")' || $vendor_id == '()') ){ // All Vendor
				$where = " ";
			}else if(($date_from == 'noneed' || $date_to == 'noneed') ){ // Vendor Only
				$where = " WHERE A.VENDOR_CODE IN ".$vendor_id." ";
			}else if($vendor_id == '("ALL")' || $vendor_id == '()'){ // All vendor per date
				$where = " WHERE DATE(SVS.DATE_UPDATED) BETWEEN '".$date_from."' and '".$date_to."'";
			}else{ // Per Vendor Per Date
				$vendor_id = str_replace('"ALL",','',$vendor_id);
				$where = " WHERE A.VENDOR_CODE IN ".$vendor_id." AND DATE(SVS.DATE_UPDATED) BETWEEN '".$date_from."' and '".$date_to."'";
			}
			$query = "SELECT * FROM (SELECT 
						SV.VENDOR_ID, SVI.VENDOR_INVITE_ID, SV.VENDOR_NAME, SV.VENDOR_CODE, CASE SVSS.TRADE_VENDOR_TYPE WHEN 1 THEN 'OUTRIGHT' WHEN 2 THEN 'CONSIGNOR' END AS VENDOR_TYPE,
						GROUP_CONCAT(SCat.CATEGORY_NAME) AS DEPARTMENT, 
						CONCAT(SVA.ADDRESS_LINE, ' ' , SCity.CITY_NAME, ' ', SVA.ZIP_CODE) AS ADDRESS,
						SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME, SVSS.POSITION, SVSS.EMAIL AS EMAIL, SVSS.MOBILE_NO AS MOBILE_NO,
						CONCAT(SVCD_TEL_NO.COUNTRY_CODE, SVCD_TEL_NO.AREA_CODE, SVCD_TEL_NO.CONTACT_DETAIL) AS TEL_NO, 
						CONCAT(SVCD_FAX.COUNTRY_CODE, SVCD_TEL_NO.AREA_CODE, SVCD_FAX.CONTACT_DETAIL) AS FAX_NO, 
						SSS.DESCRIPTION AS VENDOR_SYSTEM 
						FROM SMNTP_VENDOR_SM_SYSTEMS SVSS
						JOIN SMNTP_SM_SYSTEMS SSS ON SVSS.SM_SYSTEM_ID = SSS.SM_SYSTEM_ID
						JOIN SMNTP_VENDOR SV ON SV.VENDOR_INVITE_ID = SVSS.VENDOR_INVITE_ID
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_EMAIL ON SV.VENDOR_ID = SVCD_EMAIL.VENDOR_ID AND SVCD_EMAIL.CONTACT_DETAIL_TYPE = 4
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_MOBILE ON SV.VENDOR_ID = SVCD_MOBILE.VENDOR_ID AND SVCD_MOBILE.CONTACT_DETAIL_TYPE = 3
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_FAX ON SV.VENDOR_ID = SVCD_FAX.VENDOR_ID AND SVCD_FAX.CONTACT_DETAIL_TYPE = 2
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_TEL_NO ON SV.VENDOR_ID = SVCD_TEL_NO.VENDOR_ID AND SVCD_TEL_NO.CONTACT_DETAIL_TYPE = 1
						JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
						LEFT JOIN SMNTP_VENDOR_ADDRESSES SVA ON SV.VENDOR_ID = SVA.VENDOR_ID AND SVA.PRIMARY = 1 AND SVA.ADDRESS_TYPE = 1
						LEFT JOIN SMNTP_CITY SCity ON SVA.BRGY_MUNICIPALITY_ID = SCity.CITY_ID
						LEFT JOIN SMNTP_STATE_PROVINCE SSP ON SVA.STATE_PROVINCE_ID = SSP.STATE_PROV_ID
						LEFT JOIN SMNTP_VENDOR_CATEGORIES SVCat ON SV.VENDOR_INVITE_ID = SVCat.VENDOR_INVITE_ID
						LEFT JOIN SMNTP_CATEGORY SCat ON SVCat.CATEGORY_ID = SCat.CATEGORY_ID
						GROUP BY SV.VENDOR_ID, SV.VENDOR_INVITE_ID, SVSS.SM_SYSTEM_ID, SVSS.TRADE_VENDOR_TYPE, SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME, SVSS.POSITION
						UNION ALL
						SELECT 
						SV.VENDOR_ID, SVI.VENDOR_INVITE_ID, SV.VENDOR_NAME, SV.VENDOR_CODE, CASE SVI.TRADE_VENDOR_TYPE WHEN 1 THEN 'OUTRIGHT' WHEN 2 THEN 'CONSIGNOR' END AS VENDOR_TYPE, 
						GROUP_CONCAT(SCat.CATEGORY_NAME) AS DEPARTMENT, 
						CONCAT(SVA.ADDRESS_LINE, ' ' , SCity.CITY_NAME, ' ', SVA.ZIP_CODE) AS ADDRESS, 
						SVR.FIRST_NAME, SVR.MIDDLE_NAME, SVR.LAST_NAME, SVR.POSITION,
						SVCD_EMAIL.CONTACT_DETAIL AS EMAIL,
						CONCAT(SVCD_MOBILE.COUNTRY_CODE, SVCD_MOBILE.CONTACT_DETAIL) AS MOBILE_NO, 
						CONCAT(SVCD_TEL_NO.COUNTRY_CODE, SVCD_TEL_NO.AREA_CODE, SVCD_TEL_NO.CONTACT_DETAIL) AS TEL_NO, 
						CONCAT(SVCD_FAX.COUNTRY_CODE, SVCD_TEL_NO.AREA_CODE, SVCD_FAX.CONTACT_DETAIL) AS FAX_NO, 
						'ALL' AS VENDOR_SYSTEM 
						FROM SMNTP_VENDOR SV 
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_EMAIL ON SV.VENDOR_ID = SVCD_EMAIL.VENDOR_ID AND SVCD_EMAIL.CONTACT_DETAIL_TYPE = 4
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_MOBILE ON SV.VENDOR_ID = SVCD_MOBILE.VENDOR_ID AND SVCD_MOBILE.CONTACT_DETAIL_TYPE = 3
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_FAX ON SV.VENDOR_ID = SVCD_FAX.VENDOR_ID AND SVCD_FAX.CONTACT_DETAIL_TYPE = 2
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_TEL_NO ON SV.VENDOR_ID = SVCD_TEL_NO.VENDOR_ID AND SVCD_TEL_NO.CONTACT_DETAIL_TYPE = 1
						LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
						LEFT JOIN SMNTP_VENDOR_REP SVR ON SV.VENDOR_ID = SVR.VENDOR_ID AND SVR.AUTH_SIG = 'Y'
						LEFT JOIN SMNTP_VENDOR_ADDRESSES SVA ON SV.VENDOR_ID = SVA.VENDOR_ID AND SVA.PRIMARY = 1 AND SVA.ADDRESS_TYPE = 1
						LEFT JOIN SMNTP_CITY SCity ON SVA.BRGY_MUNICIPALITY_ID = SCity.CITY_ID
						LEFT JOIN SMNTP_STATE_PROVINCE SSP ON SVA.STATE_PROVINCE_ID = SSP.STATE_PROV_ID
						LEFT JOIN SMNTP_VENDOR_CATEGORIES SVCat ON SV.VENDOR_INVITE_ID = SVCat.VENDOR_INVITE_ID
						LEFT JOIN SMNTP_CATEGORY SCat ON SVCat.CATEGORY_ID = SCat.CATEGORY_ID
						UNION ALL
						SELECT 
						SV.VENDOR_ID, SVI.VENDOR_INVITE_ID, SV.VENDOR_NAME, SV.VENDOR_CODE, CASE SVI.TRADE_VENDOR_TYPE WHEN 1 THEN 'OUTRIGHT' WHEN 2 THEN 'CONSIGNOR' END AS VENDOR_TYPE, 
						GROUP_CONCAT(SCat.CATEGORY_NAME) AS DEPARTMENT, 
						CONCAT(SVA.ADDRESS_LINE, ' ' , SCity.CITY_NAME, ' ', SVA.ZIP_CODE) AS ADDRESS, 
						SVR.FIRST_NAME, SVR.MIDDLE_NAME, SVR.LAST_NAME, SVR.POSITION,
						SVCD_EMAIL.CONTACT_DETAIL AS EMAIL,
						CONCAT(SVCD_MOBILE.COUNTRY_CODE, SVCD_MOBILE.CONTACT_DETAIL) AS MOBILE_NO, 
						CONCAT(SVCD_TEL_NO.COUNTRY_CODE, SVCD_TEL_NO.AREA_CODE, SVCD_TEL_NO.CONTACT_DETAIL) AS TEL_NO, 
						CONCAT(SVCD_FAX.COUNTRY_CODE, SVCD_TEL_NO.AREA_CODE, SVCD_FAX.CONTACT_DETAIL) AS FAX_NO, 
						'ALL' AS VENDOR_SYSTEM 
						FROM SMNTP_VENDOR SV 
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_EMAIL ON SV.VENDOR_ID = SVCD_EMAIL.VENDOR_ID AND SVCD_EMAIL.CONTACT_DETAIL_TYPE = 4
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_MOBILE ON SV.VENDOR_ID = SVCD_MOBILE.VENDOR_ID AND SVCD_MOBILE.CONTACT_DETAIL_TYPE = 3
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_FAX ON SV.VENDOR_ID = SVCD_FAX.VENDOR_ID AND SVCD_FAX.CONTACT_DETAIL_TYPE = 2
						LEFT JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCD_TEL_NO ON SV.VENDOR_ID = SVCD_TEL_NO.VENDOR_ID AND SVCD_TEL_NO.CONTACT_DETAIL_TYPE = 1
						LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
						LEFT JOIN SMNTP_VENDOR_OWNERS SVR ON SV.VENDOR_ID = SVR.VENDOR_ID AND SVR.AUTH_SIG = 'Y'
						LEFT JOIN SMNTP_VENDOR_ADDRESSES SVA ON SV.VENDOR_ID = SVA.VENDOR_ID AND SVA.PRIMARY = 1 AND SVA.ADDRESS_TYPE = 1
						LEFT JOIN SMNTP_CITY SCity ON SVA.BRGY_MUNICIPALITY_ID = SCity.CITY_ID
						LEFT JOIN SMNTP_STATE_PROVINCE SSP ON SVA.STATE_PROVINCE_ID = SSP.STATE_PROV_ID
						LEFT JOIN SMNTP_VENDOR_CATEGORIES SVCat ON SV.VENDOR_INVITE_ID = SVCat.VENDOR_INVITE_ID
						LEFT JOIN SMNTP_CATEGORY SCat ON SVCat.CATEGORY_ID = SCat.CATEGORY_ID
						GROUP BY SV.VENDOR_ID, SVI.VENDOR_INVITE_ID, SVI.TRADE_VENDOR_TYPE, SVR.FIRST_NAME, SVR.MIDDLE_NAME, SVR.LAST_NAME, SVR.POSITION) A 
						JOIN SMNTP_VENDOR_STATUS SVS ON A.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID AND SVS.STATUS_ID = 19
						".$where."
						ORDER BY A.VENDOR_CODE";
			
			$result = $this->db->query($query);
			//return $query;
			return $result->result_array();
		}

		function get_contact_persons($temp_data){
			$temp_date = explode("|||||",$temp_data);
			$where_clause = "";
			if($temp_date[0] != '' && $temp_date[1] != ''){
				if($temp_date[0] != '01011999' && $temp_date[1] != '01011999'){
					$where_clause = "AND SVS.DATE_UPDATED BETWEEN '".$temp_date[0]."' AND '".$temp_date[1]."' ";
				}
			}
			$query = "SELECT 'ALL' AS VENDOR_CODE,'ALL' AS VENDOR_NAME
					UNION ALL
					SELECT * FROM (
					SELECT SV.VENDOR_CODE, SV.VENDOR_NAME FROM SMNTP_VENDOR SV
					JOIN SMNTP_VENDOR_STATUS SVS ON SV.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID
					WHERE 1=1
					".$where_clause."
					AND SVS.STATUS_ID = 19 ORDER BY SV.VENDOR_NAME) a";
			$result = $this->db->query($query);
			return $result->result_array();
		}

		function get_expired_invites($date_from, $date_to, $user_id, $cat_filter){
			$this->db->select('*');
			$this->db->from('SMNTP_SYSTEM_CONFIG');
			$this->db->where('CONFIG_NAME', 'invite_expiration_days');

			$query = $this->db->get();

			$config_value = $query->row()->CONFIG_VALUE;

			$this->db->select('*');
			$this->db->from('SMNTP_USERS');
			$this->db->where('USER_ID', $user_id);

			$query = $this->db->get();

			$position_id = $query->row()->POSITION_ID;

			// if($position_id == '4' || $position_id == '5')
			// 	$user_id = "";

			// $query = "SELECT ROWNUM, T.* FROM (";
			// $query.= "SELECT A.VENDOR_NAME, E.CATEGORY_NAME, CONCAT(CONCAT(CONCAT(CONCAT(C.USER_FIRST_NAME, ' '), C.USER_MIDDLE_NAME), ' '), C.USER_LAST_NAME) AS INVITER, TO_CHAR(A.DATE_CREATED + INTERVAL '".$config_value."' DAY, 'DD-MON-YY') AS DATE_EXPIRED, TO_CHAR(A.DATE_CREATED + INTERVAL '".$config_value."' DAY, 'YYYY-MM-DD') AS EXPIRED_ORDER, A.CREATED_BY ";
			// $query.= "FROM SMNTP_VENDOR_INVITE A ";
			// $query.= "LEFT JOIN SMNTP_VENDOR_STATUS B ON A.VENDOR_INVITE_ID = B.VENDOR_INVITE_ID ";
			// $query.= "LEFT JOIN SMNTP_USERS C ON A.CREATED_BY = C.USER_ID ";
			// $query.= "LEFT JOIN SMNTP_VENDOR_CATEGORIES D ON A.VENDOR_INVITE_ID = D.VENDOR_INVITE_ID ";
			// $query.= "LEFT JOIN SMNTP_CATEGORY E ON D.CATEGORY_ID = E.CATEGORY_ID ";


			// $query.= "WHERE B.STATUS_ID = '5' ";
			// if ($user_id != ""){
			// 	$query.= "AND A.CREATED_BY ='" . $user_id . "'";
			// }

			// if ($date_from != "" || $date_to != "")
			// 	$query.= "AND TO_CHAR(A.DATE_CREATED + INTERVAL '".$config_value."' DAY, 'DD-MON-YY') >= TO_DATE('".$date_from."', 'YYYY-MM-DD') AND TO_CHAR(A.DATE_CREATED + INTERVAL '".$config_value."' DAY, 'DD-MON-YY') <= TO_DATE('".$date_to."', 'YYYY-MM-DD') ";

			// // if ($cat_filter > 0) {
			// // 	$query.= " AND E.CATEGORY_ID = ".$cat_filter." ";
			// // }

			// $query.= "ORDER BY EXPIRED_ORDER ASC) T";

			$query = "SELECT ROW_NUMBER() OVER ( ORDER BY T.EXPIRED_ORDER ) AS ROWNUM, T.* FROM (";
			// Modified by MSF - 20191118 (IJR-10618)
			//$query.= "SELECT SVI.VENDOR_INVITE_ID, SVI.VENDOR_NAME, SC.CATEGORY_NAME, CONCAT(CONCAT(CONCAT(CONCAT(SU.USER_FIRST_NAME, ' '), SU.USER_MIDDLE_NAME), ' '), SU.USER_LAST_NAME) AS INVITER,
			//	TO_CHAR(SVS.INVITE_EXPIRATION, 'DD-MON-YY') AS DATE_EXPIRED, TO_CHAR(SVS.INVITE_EXPIRATION, 'YYYY-MM-DD') AS EXPIRED_ORDER ";
			$query.= "SELECT SVI.VENDOR_INVITE_ID, SVI.VENDOR_NAME, SC.CATEGORY_NAME, SSuC.SUB_CATEGORY_NAME, CONCAT(CONCAT(CONCAT(CONCAT(SU.USER_FIRST_NAME, ' '), SU.USER_MIDDLE_NAME), ' '), SU.USER_LAST_NAME) AS INVITER,
				UPPER(CAST(DATE_FORMAT(SVS.INVITE_EXPIRATION,'%d-%b-%y') AS CHAR)) AS DATE_EXPIRED, UPPER(CAST(DATE_FORMAT(SVS.INVITE_EXPIRATION,'%Y-%m-%d') AS CHAR)) AS EXPIRED_ORDER ";
			$query.= "FROM SMNTP_VENDOR_INVITE SVI ";
			$query.= "LEFT JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID ";
			$query.= "LEFT JOIN SMNTP_VENDOR_BRAND SVB ON SVB.VENDOR_ID = SV.VENDOR_ID ";
			$query.= "LEFT JOIN SMNTP_VENDOR_REP SVR ON SVR.VENDOR_ID = SV.VENDOR_ID ";
			$query.= "JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID ";
			$query.= "JOIN SMNTP_STATUS SS ON SVS.STATUS_ID = SS.STATUS_ID ";
			$query.= "JOIN SMNTP_USERS_MATRIX UM ON SVI.CREATED_BY = UM.USER_ID ";
			$query.= "LEFT JOIN SMNTP_STATUS_CONFIG SSC ON SVS.STATUS_ID = SSC.CURRENT_STATUS_ID AND SSC.CURRENT_POSITION_ID = SVS.POSITION_ID AND (SVS.POSITION_ID = 4 OR SVS.POSITION_ID =0) "; 
			$query.= "LEFT JOIN SMNTP_MESSAGES MES ON MES.INVITE_ID = SVI.VENDOR_INVITE_ID AND MES.RECIPIENT_ID = 2006 AND MES.IS_READ = -1 ";
			$query.= "LEFT JOIN SMNTP_VENDOR_CATEGORIES SVC ON SVC.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID ";
			$query.= "JOIN SMNTP_CATEGORY SC ON SC.CATEGORY_ID = SVC.CATEGORY_ID ";
			// Added MSF - 20191118 (IJR-10618)
			$query.= "LEFT JOIN SMNTP_VENDOR_SUB_CATEGORIES SVSC ON SV.VENDOR_INVITE_ID = SVSC.VENDOR_INVITE_ID AND SVC.CATEGORY_ID = SVSC.CATEGORY_ID ";
			$query.= "LEFT JOIN SMNTP_SUB_CATEGORY SSuC ON SSuC.SUB_CATEGORY_ID = SVSC.SUB_CATEGORY_ID ";
			$query.= "LEFT JOIN SMNTP_USERS SU ON SVI.CREATED_BY = SU.USER_ID ";


			$query.= "WHERE SVS.STATUS_ID = '5' ";
			if($position_id == '4'){
				$query.= "AND UM.VRDSTAFF_ID ='" . $user_id . "' ";
			}elseif ($position_id == '5'){
				$query.= "AND UM.VRDHEAD_ID ='" . $user_id . "' ";
			}else{
				$query.= "AND SVI.CREATED_BY ='" . $user_id . "' ";
			}

			if ($date_from != "" || $date_to != "")
				// $query.= "AND TO_CHAR(SVS.INVITE_EXPIRATION, 'DD-MON-YY') >= TO_DATE('".$date_from."', 'YYYY-MM-DD') AND TO_CHAR(SVS.INVITE_EXPIRATION, 'DD-MON-YY') <= TO_DATE('".$date_to."', 'YYYY-MM-DD') ";
				$query.= "AND SVS.INVITE_EXPIRATION >= STR_TO_DATE('".$date_from."','%Y-%m-%d') AND SVS.INVITE_EXPIRATION <= STR_TO_DATE('".$date_to."','%Y-%m-%d') ";


			// Modified MSF - 20191118 (IJR-10618)
			//$query.= "GROUP BY SVI.VENDOR_INVITE_ID , SVI.VENDOR_NAME, SC.CATEGORY_NAME, SU.USER_FIRST_NAME, SU.USER_MIDDLE_NAME, SU.USER_LAST_NAME, SVS.INVITE_EXPIRATION ";
			$query.= "GROUP BY SVI.VENDOR_INVITE_ID, SVI.VENDOR_NAME, SC.CATEGORY_NAME, SSuC.SUB_CATEGORY_NAME, SU.USER_FIRST_NAME, SU.USER_MIDDLE_NAME, SU.USER_LAST_NAME, SVS.INVITE_EXPIRATION ";
			$query.= "ORDER BY EXPIRED_ORDER, UPPER(SVI.VENDOR_NAME), UPPER(SC.CATEGORY_NAME) ASC) T";
			// return $query;
			$result = $this->db->query($query);
			// return $user_id;
			return $result->result();
		}

		function get_expired_config(){
			// $query = "SELECT CONFIG_VALUE FROM SMNTP_SYSTEM_CONFIG WHERE SYSTEM_CONFIG_ID = '3'";
			// $result = $this->db->query($query);

			// return $result->result_array();

			$this->db->select('*');
			$this->db->from('SMNTP_SYSTEM_CONFIG');
			$this->db->where('SYSTEM_CONFIG_ID', '3');

			$query = $this->db->get();

			$config_value = $query->row()->CONFIG_VALUE;

			return get_expired_invites($config_value);
		}



		function get_deactivated_account($date_from, $date_to, $user_id, $cat_filter){
			$this->db->select('*');
			$this->db->from('SMNTP_USERS');
			$this->db->where('USER_ID', $user_id);

			$query = $this->db->get();

			$position_id = $query->row()->POSITION_ID;

			// if($position_id == '4' || $position_id == '5')
			// 	$user_id = "";
			
			$query = "SELECT ROW_NUMBER() OVER ( ORDER BY T.DEACTIVATION_ORDER ) AS ROWNUM, T.* FROM (";
			$query.= "SELECT DISTINCT VI.VENDOR_NAME, V.VENDOR_CODE, UPPER(CAST(DATE_FORMAT(VI.DATE_CREATED,'%d-%b-%y') AS CHAR)) AS CREATE_DATE, ";
			// Modified MSF - 20191118 (IJR-10618)
			//$query.= "CONCAT(CONCAT(CONCAT(CONCAT(U.USER_FIRST_NAME, ' '), U.USER_MIDDLE_NAME), ' '), U.USER_LAST_NAME) AS INVITER, ";
			$query.= "SC.CATEGORY_NAME, SSuC.SUB_CATEGORY_NAME, CONCAT(CONCAT(CONCAT(CONCAT(U.USER_FIRST_NAME, ' '), U.USER_MIDDLE_NAME), ' '), U.USER_LAST_NAME) AS INVITER, ";
			// Added MSF - 20191118 (IJR-10618)
			$query.= "SVT.VENDOR_TYPE_NAME, CASE V.VENDOR_TYPE WHEN 1 THEN 'OUTRIGHT' WHEN 2 THEN 'CONSIGNOR' ELSE 'NON-TRADE' END AS VENDOR_TYPE, ";
			$query.= "UPPER(CAST(DATE_FORMAT(VS.DATE_UPDATED,'%d-%b-%y') AS CHAR)) AS DEACTIVATION_DATE, S.STATUS_NAME as REASON, UPPER(CAST(DATE_FORMAT(VS.DATE_UPDATED,'%Y-%m-%d') AS CHAR)) AS DEACTIVATION_ORDER, VI.CREATED_BY ";
			$query.= "FROM SMNTP_VENDOR_INVITE VI ";
			$query.= "INNER JOIN SMNTP_CREDENTIALS C ON C.USER_ID = VI.USER_ID ";
			$query.= "INNER JOIN SMNTP_VENDOR_STATUS VS ON VS.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID ";
			$query.= "LEFT JOIN SMNTP_VENDOR V ON V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID ";
			// Modified MSF - 20191118 (IJR-10618)
			$query.= "LEFT JOIN SMNTP_VENDOR_TYPE SVT ON VI.BUSINESS_TYPE = SVT.VENDOR_TYPE_ID ";
			$query.= "LEFT JOIN SMNTP_USERS U ON U.USER_ID=VI.CREATED_BY ";
			$query.= "RIGHT JOIN SMNTP_STATUS S ON S.STATUS_ID = VS.STATUS_ID ";
			$query.= "JOIN SMNTP_USERS_MATRIX UM ON VI.CREATED_BY = UM.USER_ID ";	
			// Modified MSF - 20191118 (IJR-10618)
			$query.= "LEFT JOIN SMNTP_VENDOR_CATEGORIES SVC ON SVC.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID ";
			$query.= "JOIN SMNTP_CATEGORY SC ON SC.CATEGORY_ID = SVC.CATEGORY_ID ";
			$query.= "LEFT JOIN SMNTP_VENDOR_SUB_CATEGORIES SVSC ON VI.VENDOR_INVITE_ID = SVSC.VENDOR_INVITE_ID AND SVC.CATEGORY_ID = SVSC.CATEGORY_ID ";
			$query.= "LEFT JOIN SMNTP_SUB_CATEGORY SSuC ON SSuC.SUB_CATEGORY_ID = SVSC.SUB_CATEGORY_ID ";
			$query.= "WHERE VS.STATUS_ID IN(191,193) AND C.DEACTIVATED_FLAG = 1 ";

			// if ($user_id != ""){
			// 	$query.= "AND VI.CREATED_BY ='" . $user_id . "'";
			// }

			if($position_id == '4'){
				$query.= "AND UM.VRDSTAFF_ID ='" . $user_id . "' ";
			}elseif ($position_id == '5'){
				$query.= "AND UM.VRDHEAD_ID ='" . $user_id . "' ";
			}else{
				$query.= "AND VI.CREATED_BY ='" . $user_id . "' ";
			}

			if ($date_from != "" || $date_to != "")
				// $query.= "AND VS.DATE_UPDATED >= TO_DATE('".$date_from."', 'YYYY-MM-DD') AND VS.DATE_UPDATED <= TO_DATE('".$date_to."', 'YYYY-MM-DD') ";
				$query.= "AND VS.DATE_UPDATED >= STR_TO_DATE('".$date_from."','%Y-%m-%d') AND VS.DATE_UPDATED <= STR_TO_DATE('".$date_to."','%Y-%m-%d') ";

			$query.= "ORDER BY DEACTIVATION_ORDER, UPPER(VI.VENDOR_NAME) ASC) T";

			$result = $this->db->query($query);
			return $result->result();	
			// return $query;		
		}
		//Added JRM - June 16 2021
		function get_deleted_vendor_invites($delType, $user_id){
			
			$query = "SELECT ROW_NUMBER() OVER ( ORDER BY SVI.VENDOR_NAME ) AS ROWNUM, SVI.VENDOR_NAME, SS.STATUS_NAME, CAST(DATE_FORMAT(SVI.REMOVE_DATE,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS REMOVE_DATE, CONCAT(SC.USERNAME,' - ',SU.USER_FIRST_NAME,' ',SU.USER_MIDDLE_NAME,' ',SU.USER_LAST_NAME) AS DELETED_BY, SVI.REASON_FOR_DELETION FROM SMNTP_VENDOR_INVITE_SYS_LOGS SVI ";
			$query .= "LEFT JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID ";
			$query .= "LEFT JOIN SMNTP_STATUS SS ON SVS.STATUS_ID = SS.STATUS_ID ";
			$query .= "LEFT JOIN SMNTP_USERS SU ON SVI.CREATED_BY = SU.USER_ID ";
			$query .= "LEFT JOIN SMNTP_CREDENTIALS SC ON SVI.CREATED_BY = SC.USER_ID ";
			$query .= "WHERE SVI.REMOVE_DATE IS NOT NULL AND SVI.REMOVE_DATE > '01-Sep-21' ";
			
			if($delType == "vn")
				$query .= "ORDER BY SVI.VENDOR_NAME";
			else
				$query .= "ORDER BY SU.USER_FIRST_NAME";
				
			
			//if ($date_from != "" || $date_to != "")
				//$query.= "WHERE SVI.REMOVE_DATE >= TO_DATE('".$date_from."', 'YYYY-MM-DD') AND SVI.REMOVE_DATE <= TO_DATE('".$date_to."', 'YYYY-MM-DD') ";

			//$query.= "ORDER BY SVI.REMOVE_DATE";
			
			$result = $this->db->query($query);
			return $result->result();	
			//return $query;	

		}
		
		// Added MSF - 20191126 (IJR-10619)
		function get_completed_accounts($date_from, $date_to, $user_id, $cat_filter){
			$query = "SELECT ROW_NUMBER() OVER ( ORDER BY SVS.DATE_UPDATED ) AS ROWNUM, SV.VENDOR_NAME, SV.VENDOR_CODE, SC.CATEGORY_NAME, SSC.SUB_CATEGORY_NAME, SVS.DATE_UPDATED ";
			$query .= "FROM SMNTP_VENDOR SV ";
			$query .= "INNER JOIN SMNTP_VENDOR_STATUS SVS ON SV.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID ";
			$query .= "LEFT JOIN SMNTP_VENDOR_CATEGORIES SVC ON SV.VENDOR_INVITE_ID = SVC.VENDOR_INVITE_ID ";
			$query .= "LEFT JOIN SMNTP_CATEGORY SC ON SVC.CATEGORY_ID = SC.CATEGORY_ID ";
			$query .= "LEFT JOIN SMNTP_VENDOR_SUB_CATEGORIES SVSC ON SV.VENDOR_INVITE_ID = SVSC.VENDOR_INVITE_ID AND SVC.CATEGORY_ID = SVSC.CATEGORY_ID ";
			$query .= "LEFT JOIN SMNTP_SUB_CATEGORY  SSC ON SVSC.SUB_CATEGORY_ID = SSC.SUB_CATEGORY_ID ";
			$query .= "WHERE SVS.STATUS_ID = 19 ";

			if ($date_from != "" || $date_to != "")
				// $query.= "AND SVS.DATE_UPDATED >= TO_DATE('".$date_from."', 'YYYY-MM-DD') AND SVS.DATE_UPDATED <= TO_DATE('".$date_to."', 'YYYY-MM-DD') ";
				$query.= "AND SVS.DATE_UPDATED >= STR_TO_DATE('".$date_from."','%Y-%m-%d') AND SVS.DATE_UPDATED <= STR_TO_DATE('".$date_to."','%Y-%m-%d') ";

			$query.= "ORDER BY SVS.DATE_UPDATED";

			$result = $this->db->query($query);
			return $result->result();	
			//return $query;	

		}

		// Added MSF - 20191126 (IJR-10619)
		function get_validation_schedule($date_from, $date_to, $user_id, $cat_filter){
			$query = "SELECT T.*, CONCAT(T.DISP_RV_FROM,CONCAT(' - ',T.DISP_RV_TO)) AS DISPLAY_DATE FROM ( ";
			$query .= "SELECT ROW_NUMBER() OVER ( ORDER BY SV.DATE_CREATED DESC) AS ROWNUM, SV.VENDOR_NAME, SV.VENDOR_CODE, SC.CATEGORY_NAME, SSuC.SUB_CATEGORY_NAME, CAST(DATE_FORMAT(SVS.RV_FROM,'%M %d,%Y') AS CHAR) AS DISP_RV_FROM, CAST(DATE_FORMAT(STR_TO_DATE(RV_TO,'%d-%M-%Y'),'%M %d,%Y') AS CHAR) AS DISP_RV_TO, RV_FROM, CAST(DATE_FORMAT(STR_TO_DATE(RV_TO,'%d-%M-%Y'),'%d-%m-%y') AS CHAR) AS RV_TO ";
			$query .= "FROM SMNTP_VENDOR SV ";
			$query .= "INNER JOIN SMNTP_VENDOR_STATUS SVS ON SV.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID ";
			$query .= "LEFT JOIN SMNTP_VENDOR_CATEGORIES SVC ON SVC.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID ";
			$query .= "LEFT JOIN SMNTP_CATEGORY SC ON SC.CATEGORY_ID = SVC.CATEGORY_ID ";
			$query .= "LEFT JOIN SMNTP_VENDOR_SUB_CATEGORIES SVSC ON SVSC.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID AND SVSC.CATEGORY_ID = SVC.CATEGORY_ID ";
			$query .= "LEFT JOIN SMNTP_SUB_CATEGORY SSuC ON SSuC.SUB_CATEGORY_ID = SVSC.SUB_CATEGORY_ID ";
			$query .= "WHERE SVS.STATUS_ID IN (19) AND DATE(SVS.DATE_UPDATED) > STR_TO_DATE(RV_TO,'%d-%M-%Y') ";
			$query .= "UNION ALL ";
			$query .= "SELECT ROW_NUMBER() OVER ( ORDER BY SV.DATE_CREATED DESC) AS ROWNUM, SV.VENDOR_NAME, SV.VENDOR_CODE, SC.CATEGORY_NAME, SSuC.SUB_CATEGORY_NAME, CAST(DATE_FORMAT(SVS.RV_FROM,'%M %d,%Y') AS CHAR) AS DISP_RV_FROM, CAST(DATE_FORMAT(STR_TO_DATE(RV_TO,'%d-%M-%Y'),'%M %d,%Y') AS CHAR) AS DISP_RV_TO, RV_FROM, CAST(DATE_FORMAT(STR_TO_DATE(RV_TO,'%d-%M-%Y'),'%d-%m-%y') AS CHAR) AS RV_TO ";
			$query .= "FROM SMNTP_VENDOR SV ";
			$query .= "INNER JOIN SMNTP_VENDOR_STATUS SVS ON SV.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID ";
			$query .= "LEFT JOIN SMNTP_VENDOR_CATEGORIES SVC ON SVC.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID ";
			$query .= "LEFT JOIN SMNTP_CATEGORY SC ON SC.CATEGORY_ID = SVC.CATEGORY_ID ";
			$query .= "LEFT JOIN SMNTP_VENDOR_SUB_CATEGORIES SVSC ON SVSC.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID AND SVSC.CATEGORY_ID = SVC.CATEGORY_ID ";
			$query .= "LEFT JOIN SMNTP_SUB_CATEGORY SSuC ON SSuC.SUB_CATEGORY_ID = SVSC.SUB_CATEGORY_ID ";
			$query .= "WHERE SVS.STATUS_ID IN (198) AND CURDATE() > STR_TO_DATE(RV_TO,'%d-%M-%Y') ";
			$query .= ") T ";

			if ($date_from != "" || $date_to != ""){
				$query .= "WHERE (RV_FROM BETWEEN STR_TO_DATE('".$date_from."','%Y-%m-%d') AND STR_TO_DATE('".$date_to."','%Y-%m-%d')) ";
				$query .= "OR (STR_TO_DATE(RV_TO,'%d-%M-%Y') BETWEEN STR_TO_DATE('".$date_from."','%Y-%m-%d') AND STR_TO_DATE('".$date_to."','%Y-%m-%d')) ";
			}

			$result = $this->db->query($query);
			return $result->result();	
			//return $query;
		}

		function get_deactivated_account_pdf($date_from, $date_to, $cat_filter){
			$query = "SELECT ROW_NUMBER() OVER ( ORDER BY T.DEACTIVATION_ORDER) AS ROWNUM, T.* FROM (";
			$query.= "SELECT DISTINCT VI.VENDOR_NAME, V.VENDOR_CODE, UPPER(CAST(DATE_FORMAT(VI.DATE_CREATED,'%d-%b-%y') AS CHAR)) AS CREATE_DATE, CONCAT(CONCAT(CONCAT(CONCAT(U.USER_FIRST_NAME, ' '), U.USER_MIDDLE_NAME), ' '), U.USER_LAST_NAME) AS INVITER, UPPER(CAST(DATE_FORMAT(VS.DATE_UPDATED,'%d-%b-%y') AS CHAR)) AS DEACTIVATION_DATE, S.STATUS_NAME as REASON, UPPER(CAST(DATE_FORMAT(VS.DATE_UPDATED,'%d-%b-%y') AS CHAR)) AS DEACTIVATION_ORDER ";
			$query.= "FROM SMNTP_VENDOR_INVITE VI ";
			$query.= "INNER JOIN SMNTP_CREDENTIALS C ON C.USER_ID = VI.USER_ID ";
			$query.= "INNER JOIN SMNTP_VENDOR_STATUS VS ON VS.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID ";
			$query.= "LEFT JOIN SMNTP_VENDOR V ON V.VENDOR_INVITE_ID = VI.VENDOR_INVITE_ID ";
			$query.= "LEFT JOIN SMNTP_USERS U ON U.USER_ID=VI.CREATED_BY ";
			$query.= "RIGHT JOIN SMNTP_STATUS S ON S.STATUS_ID = VS.STATUS_ID ";
			$query.= "WHERE VS.STATUS_ID IN(191,193) AND C.DEACTIVATED_FLAG = 1 ";

			if ($date_from != "" || $date_to != "")
				$query.= "AND VS.DATE_UPDATED >= STR_TO_DATE('".$date_from."','%Y-%m-%d') AND VS.DATE_UPDATED <= STR_TO_DATE('".$date_to."','%Y-%m-%d') ";

			$query.= "ORDER BY DEACTIVATION_ORDER ASC) T";

			$result = $this->db->query($query);
			return $result->result_array();	
			//return $query;		
		}

		function get_buyer_senmer_email(){
			$query = "SELECT U.USER_EMAIL, U.USER_ID, U.POSITION_ID ";
			$query.= "FROM SMNTP_POSITION P LEFT JOIN SMNTP_USERS U ON P.POSITION_ID = U.POSITION_ID ";
			$query.= "WHERE P.POSITION_ID = '2' OR P.POSITION_ID = '7' OR P.POSITION_ID = '4' OR P.POSITION_ID = '5'";
			// $query.= "WHERE U.USER_ID = '2003' ";
			
			$result = $this->db->query($query);
			return $result->result_array();	
			//return $query;
		}

		function get_sysdate(){
			//$query = "SELECT EXTRACT(DAY FROM CURRENT_DATE) AS SYSDAY FROM SMNTP_USERS";
			$query = "SELECT CURRENT_DATE FROM DUAL";
			$result = $this->db->query($query);

			return $result->result_array();	
		}

		// function get_expired_invites(){
		// 	$query = "SELECT CONFIG_VALUE FROM SMNTP_SYSTEM_CONFIG WHERE SYSTEM_CONFIG_ID = '3'";

		// 	$query = "SELECT A.VENDOR_NAME, CONCAT(C.USER_FIRST_NAME, ' ', C.USER_MIDDLE_NAME, ' ', C.USER_LAST_NAME), A.DATE_CREATED ";
		// 	$query.= "FROM SMNTP_VENDOR_INVITE A ";
		// 	$query.= "LEFT JOIN SMNTP_VENDOR_STATUS B ON A.VENDOR_INVITE_ID = B.VENDOR_INVITE_ID ";
		// 	$query.= "LEFT JOIN SMNTP_USERS C ON A.CREATED_BY = C.USER_ID"
		// 	$query.= "WHERE B.STATUS_ID = '5'";
			
		// 	$result = $this->db->query($query);
		// 	return $result->result_array();
		// }

		// function select_orgtype_reqdocs($orgtype_id,$vendortype_id){
		// 	$query = "SELECT A.OWNERSHIP_ID, C.REQUIRED_DOCUMENT_ID, C.REQUIRED_DOCUMENT_NAME FROM SMNTP_VP_REQUIRED_DOCS_DEFN A ";
		// 	$query.= "LEFT JOIN SMNTP_OWNERSHIP B ON A.OWNERSHIP_ID = B.OWNERSHIP_ID ";
		// 	$query.= "LEFT JOIN SMNTP_VP_REQUIRED_DOCUMENTS C ON A.DOC_ID = C.REQUIRED_DOCUMENT_ID ";
		// 	$query.= "WHERE B.OWNERSHIP_ID = '".$orgtype_id."' AND ";
		// 	if($vendortype_id == 0){
		// 		$query.= "VENDOR_TYPE_ID IS NULL AND ";
		// 	}else{
		// 		$query.= "VENDOR_TYPE_ID = '".$vendortype_id."' AND ";
		// 	}
		// 	$query.= " A.ACTIVE = '1' ";
		// 	$query.= "ORDER BY REQUIRED_DOCUMENT_NAME ASC";

		// 	$result = $this->db->query($query);
		// 	return $result->result_array();			
		// }

		// function save_docs($orgtype_id, $docs, $created_by, $vendortype_id){
		// 	$delete_query = "DELETE SMNTP_VP_REQUIRED_DOCS_DEFN WHERE OWNERSHIP_ID = '".$orgtype_id."'";
		// 	if($vendortype_id == 0){
		// 		$delete_query .= " AND VENDOR_TYPE_ID IS NULL";
		// 	}else{
		// 		$delete_query .= " AND VENDOR_TYPE_ID ='".$vendortype_id."'";
		// 	}
		// 	$this->db->query($delete_query);
		// 	//$this->db->query("UPDATE SMNTP_VP_REQUIRED_DOCS_DEFN SET ACTIVE = '0' WHERE OWNERSHIP_ID = '".$orgtype_id."'");
		// 	$result = true;
		// 	for ($x=0; $x<count($docs); $x++ ){
		// 		if($docs[$x] != 0){
		// 			$query = "INSERT INTO SMNTP_VP_REQUIRED_DOCS_DEFN (OWNERSHIP_ID, DOC_ID, ACTIVE, CREATED_BY, VENDOR_TYPE_ID) ";
		// 			$query.= "VALUES ('".$orgtype_id."','".$docs[$x]."','1','".$created_by."'";
					
		// 			if($vendortype_id == 0){
		// 				$query.= ",null)"; 
		// 			}else{
		// 				$query.= ",'".$vendortype_id."')";
		// 			}
		// 			$result = $this->db->query($query);
		// 		}
		// 	}
		// 	return $result;
		// 	//return $query;
		// 	//return count($docs) . " " . $docs[0];
		// }

		

	}
?>
