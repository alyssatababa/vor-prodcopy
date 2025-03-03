<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class Epaf_ims_report extends CI_Model
{
	
	function get_data(){		
		if(date('N') == 2){
			$date_from = date('Y-m-d', strtotime('previous friday')).' 08:00:00';
			$date_to = date('Y-m-d', strtotime('today')).' 07:59:59';
		}else if(date('N') == 5){
			$date_from = date('Y-m-d', strtotime('previous tuesday')).' 08:00:00';
			$date_to = date('Y-m-d', strtotime('today')).' 07:59:59';
		}
		
		//$date_from = '2023-05-30 08:00:00';
		//$date_to = '2023-06-02 07:59:59';
		
		//$query = "SELECT
		//			SVI.VENDOR_INVITE_ID,
		//			CASE SVI.REGISTRATION_TYPE 
		//			WHEN 4 THEN SV.VENDOR_CODE_02
		//			ELSE SV.`VENDOR_CODE` END AS VENDOR_CODE, 
		//			SVSS.`EMAIL`, 
		//			CASE SVI.REGISTRATION_TYPE WHEN 4 THEN CASE SVI.`TRADE_VENDOR_TYPE` WHEN 1 THEN 'Store Consignor' ELSE 'Outright' END ELSE CASE SVI.`TRADE_VENDOR_TYPE` WHEN 1 THEN 'Outright' ELSE 'Store Consignor' END END
		//			AS Vendor_Type_Description, SSS.SM_SYSTEM_ID
		//			FROM SMNTP_VENDOR_INVITE SVI
		//			JOIN SMNTP_VENDOR SV ON SVI.`VENDOR_INVITE_ID` = SV.`VENDOR_INVITE_ID`
		//			JOIN SMNTP_VENDOR_STATUS SVS ON SVI.`VENDOR_INVITE_ID` = SVS.`VENDOR_INVITE_ID`
		//			JOIN SMNTP_VENDOR_SM_SYSTEMS SVSS ON SVI.`VENDOR_INVITE_ID` = SVSS.`VENDOR_INVITE_ID` AND SVSS.TRADE_VENDOR_TYPE = CASE SVI.REGISTRATION_TYPE WHEN 4 THEN CASE SVI.`TRADE_VENDOR_TYPE` WHEN 1 THEN 2 ELSE 1 END ELSE SVI.TRADE_VENDOR_TYPE END
		//			JOIN SMNTP_SM_SYSTEMS SSS ON SVSS.`SM_SYSTEM_ID` = SSS.`SM_SYSTEM_ID`
		//			WHERE 1=1 
		//			AND SVS.`STATUS_ID` = 19
		//			AND SSS.`SM_SYSTEM_ID` IN (9,10,11,12)
		//			AND SVS.DATE_UPDATED BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND DATE_ADD(NOW(), INTERVAL 1 DAY)";
		
		$query = "SELECT a.VENDOR_INVITE_ID, a.REGISTRATION_TYPE, a.DATE_UPDATED, a.EMAIL, a.VENDOR_TYPE_DESCRIPTION, a.SM_SYSTEM_ID, a.VENDOR_CODE, a.VENDOR_ID FROM (SELECT
					SVI.VENDOR_INVITE_ID,
					CASE SVI.REGISTRATION_TYPE 
					WHEN 4 THEN SV.VENDOR_CODE_02
					ELSE SV.`VENDOR_CODE` END AS VENDOR_CODE, 
					SVSS.`EMAIL`, 
					CASE SVI.REGISTRATION_TYPE 
						WHEN 4 THEN CASE SVI.`TRADE_VENDOR_TYPE` 
						WHEN 1 THEN 'Store Consignor' ELSE 'Outright' END 
					ELSE 
						CASE SVI.`TRADE_VENDOR_TYPE` 
						WHEN 1 THEN 'Outright' ELSE 'Store Consignor' END
					END
					AS Vendor_Type_Description, SSS.SM_SYSTEM_ID,SVI.REGISTRATION_TYPE,SVSL.DATE_UPDATED, SV.VENDOR_ID
					FROM SMNTP_VENDOR_INVITE SVI
					JOIN SMNTP_VENDOR SV ON SVI.`VENDOR_INVITE_ID` = SV.`VENDOR_INVITE_ID`
					JOIN SMNTP_VENDOR_STATUS_LOGS SVSL ON SVI.`VENDOR_INVITE_ID` = SVSL.`VENDOR_INVITE_ID`
					JOIN SMNTP_VENDOR_SM_SYSTEMS SVSS ON SVI.`VENDOR_INVITE_ID` = SVSS.`VENDOR_INVITE_ID` AND SVSS.TRADE_VENDOR_TYPE = CASE SVI.REGISTRATION_TYPE WHEN 4 THEN CASE SVI.`TRADE_VENDOR_TYPE` WHEN 1 THEN 2 ELSE 1 END ELSE SVI.TRADE_VENDOR_TYPE END
					JOIN SMNTP_SM_SYSTEMS SSS ON SVSS.`SM_SYSTEM_ID` = SSS.`SM_SYSTEM_ID`
					WHERE 1=1 
					AND SVSL.`STATUS_ID` = 19
					AND SSS.`SM_SYSTEM_ID` IN (9,10,11,12)
					AND SVSL.DATE_UPDATED BETWEEN '".$date_from."' AND '".$date_to."'
					UNION ALL 
					SELECT
					SVI.VENDOR_INVITE_ID,
					SV.VENDOR_CODE_02 AS VENDOR_CODE, 
					SVSS.`EMAIL`, 
					CASE SVI.TRADE_VENDOR_TYPE WHEN 2 THEN 'Outright' ELSE 'Store Consignor' END AS VENDOR_TYPE_DESCRIPTION,
					SSS.SM_SYSTEM_ID,SVI.REGISTRATION_TYPE,SVSL.DATE_UPDATED, SV.VENDOR_ID
					FROM SMNTP_VENDOR_INVITE SVI
					JOIN SMNTP_VENDOR SV ON SVI.`VENDOR_INVITE_ID` = SV.`VENDOR_INVITE_ID`
					JOIN SMNTP_VENDOR_STATUS_LOGS SVSL ON SVI.`VENDOR_INVITE_ID` = SVSL.`VENDOR_INVITE_ID`
					JOIN SMNTP_VENDOR_SM_SYSTEMS SVSS 
						ON SVI.`VENDOR_INVITE_ID` = SVSS.`VENDOR_INVITE_ID` 
						AND SVSS.TRADE_VENDOR_TYPE = CASE SVI.`TRADE_VENDOR_TYPE` WHEN 2 THEN 1 ELSE 2 END 
					JOIN SMNTP_SM_SYSTEMS SSS ON SVSS.`SM_SYSTEM_ID` = SSS.`SM_SYSTEM_ID`
					WHERE 1=1 
					AND SVSL.`STATUS_ID` = 19
					AND SV.VENDOR_CODE_02 != ''
					AND SSS.`SM_SYSTEM_ID` IN (9,10,11,12)
					AND SVSL.DATE_UPDATED BETWEEN '".$date_from."' AND '".$date_to."'
					) a ORDER BY a.VENDOR_INVITE_ID, a.DATE_UPDATED ASC";
		$res = $this->db->query($query);
		
		$checker = array();
		$vendor_details = array();
		
		$count_res = count($res->result());
		for($a=0; $a<$count_res; $a++){
			$count_audit_log = 0;
			
			$vendor_invite_id = $res->result()[$a]->VENDOR_INVITE_ID;
			$last_completed = $res->result()[$a]->DATE_UPDATED;
			$registration_type = $res->result()[$a]->REGISTRATION_TYPE;
			$vendor_code = $res->result()[$a]->VENDOR_CODE;
			$email = $res->result()[$a]->EMAIL;
			$vendor_type_description = $res->result()[$a]->VENDOR_TYPE_DESCRIPTION;
			$sm_system_id = $res->result()[$a]->SM_SYSTEM_ID;
			$vendor_id = $res->result()[$a]->VENDOR_ID;
			
			if($registration_type == 1 || $registration_type == 2 || $registration_type == 5){
				array_push($vendor_details, array("VENDOR_INVITE_ID" => $vendor_invite_id,
													"VENDOR_CODE" => $vendor_code,
													"EMAIL" => $email,
													"Vendor_Type_Description" => $vendor_type_description,
													"SM_SYSTEM_ID" => $sm_system_id)
													);
			}else{
				$query_date_updated = "SELECT DATE_UPDATED
										FROM SMNTP_VENDOR_STATUS_LOGS 
										WHERE VENDOR_INVITE_ID = ".$vendor_invite_id." AND STATUS_ID = 19 AND DATE_UPDATED < '".$last_completed."'
										ORDER BY DATE_UPDATED DESC
										LIMIT 0,1";
				$res_date_updated = isset($this->db->query($query_date_updated)->result()[0]->DATE_UPDATED) ? $this->db->query($query_date_updated)->result()[0]->DATE_UPDATED : '';
				
				if($res_date_updated != ''){
					if($res_date_updated > $date_from){
					}else{
						$check_audit_logs = "SELECT VAR_TO
											 FROM SMNTP_VENDOR_AUDIT_LOGS 
											 WHERE vendor_id = ".$vendor_id." AND MODIFIED_DATE >= '".$res_date_updated."' AND DATE(MODIFIED_DATE) <= '".$last_completed."' 
											 AND VAR_TO = '".$email."'
											 AND (MODIFIED_FIELD LIKE '%ePAF%Email%' OR MODIFIED_FIELD LIKE '%IMS%Email%')";
						$res_audit_log = $this->db->query($check_audit_logs)->result();
						$count_audit_log = count($res_audit_log);
						if($count_audit_log > 0){
							array_push($vendor_details, array("VENDOR_INVITE_ID" => $vendor_invite_id,
																"VENDOR_CODE" => $vendor_code,
																"EMAIL" => $email,
																"Vendor_Type_Description" => $vendor_type_description,
																"SM_SYSTEM_ID" => $sm_system_id)
																);
						}
					}
				}else{
					array_push($vendor_details, array("VENDOR_INVITE_ID" => $vendor_invite_id,
														"VENDOR_CODE" => $vendor_code,
														"EMAIL" => $email,
														"Vendor_Type_Description" => $vendor_type_description,
														"SM_SYSTEM_ID" => $sm_system_id)
														);
				}
			}
		}
		
		return $vendor_details;
		//exit();
		
		// -- AND SVS.DATE_UPDATED BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND DATE_ADD(NOW(), INTERVAL 1 DAY)
		//return $res->result();
	}

	function get_emails(){
		$query = "SELECT EMAIL_ADDRESS,TAG_SYSTEM FROM SMNTP_EPAF_IMS_EMAIL WHERE EMAIL_STATUS = 'Y'";
		$res = $this->db->query($query);
		return $res->result();
	}
	
	function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
		return false;
	}
}
?>