<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class Sm_vendor_systems_model extends CI_Model
{
	function get_department(){
		// SELECT CATEGORY_NAME FROM SMNTP_CATEGORY WHERE STATUS = 1 ORDER BY category_name
		$this->db->select('CATEGORY_ID, CATEGORY_NAME');
		$this->db->from('SMNTP_CATEGORY');
		$this->db->where('STATUS', 1);
		$this->db->order_by('CATEGORY_NAME');

		$result = $this->db->get();

		return $result->result_array();
	}
	
	function get_all_data(){
		// SELECT SSS.`SM_SYSTEM_ID`, SSS.`DESCRIPTION`, SCat.`CATEGORY_NAME`, SSS.`DATE_CREATED` FROM SMNTP_SM_SYSTEMS SSS 
		//	JOIN SMNTP_CATEGORY SCat ON SSS.`DEPARTMENT_ID` = SCat.`CATEGORY_ID`;
		
		$query = "SELECT
				  SSS.`SM_SYSTEM_ID`, SSS.`DESCRIPTION`, SSS.`DEPARTMENT_ID`, CASE WHEN SSS.DEPARTMENT_ID = 0 THEN '-- ALL --' ELSE SCat.`CATEGORY_NAME` END AS CATEGORY_NAME, SSS.TOOL_TIP, SSS.TRADE_VENDOR_TYPE, CASE SSS.TRADE_VENDOR_TYPE WHEN 1 THEN 'Outright' ELSE 'Store Consignor' END AS TRADE_VENDOR_TYPE_DESC, SSS.`DATE_CREATED`
				  FROM SMNTP_SM_SYSTEMS SSS
				  LEFT JOIN SMNTP_CATEGORY SCat ON SSS.`DEPARTMENT_ID` = SCat.`CATEGORY_ID`
				  WHERE SSS.`REMOVED` = 'N'";

		$result = $this->db->query($query);

		return $result->result_array();
	}

	function search_smvs($search_string){
		$query = "SELECT 
				  SSS.`SM_SYSTEM_ID`, SSS.`DESCRIPTION`, SSS.`DEPARTMENT_ID`, CASE WHEN SSS.DEPARTMENT_ID = 0 THEN '-- ALL --' ELSE SCat.`CATEGORY_NAME` END AS CATEGORY_NAME, SSS.TOOL_TIP, SSS.TRADE_VENDOR_TYPE, CASE SSS.TRADE_VENDOR_TYPE WHEN 1 THEN 'Outright' ELSE 'Store Consignor' END AS TRADE_VENDOR_TYPE_DESC, SSS.`DATE_CREATED`
				  FROM SMNTP_SM_SYSTEMS SSS
				  LEFT JOIN SMNTP_CATEGORY SCat ON SSS.`DEPARTMENT_ID` = SCat.`CATEGORY_ID`";

		$query.= "WHERE SSS.`REMOVED` = 'N' AND LOWER(SSS.`DESCRIPTION`) LIKE LOWER('%".addslashes($search_string)."%') OR LOWER(SCat.`CATEGORY_NAME`) LIKE LOWER('%".addslashes($search_string)."%')";


		$result = $this->db->query($query);
		return $result->result_array();
	}

	function insert_smvs($department, $smvs_name, $created_by, $smvs_tool_tip, $trade_vendor_type){

		$check_exist = $this->db->query("SELECT * FROM SMNTP_SM_SYSTEMS WHERE DESCRIPTION = '".addslashes($smvs_name)."' and TRADE_VENDOR_TYPE = '".$trade_vendor_type."' and REMOVED = 'N'")->result_array();
		if(count($check_exist) > 0){
			return "duplicate";
		}else{
			$query = "INSERT INTO SMNTP_SM_SYSTEMS (DEPARTMENT_ID, DESCRIPTION, DATE_CREATED, created_by, TOOL_TIP, TRADE_VENDOR_TYPE) ";
			$query.= "VALUES ('".$department."','".addslashes($smvs_name)."',NOW(),'".$created_by."', '".addslashes($smvs_tool_tip)."', '".$trade_vendor_type."')";
			$this->db->query($query);
			$insert_id = $this->db->insert_id(); 

			$query2 = "INSERT INTO SMNTP_VENDOR_TOOLTIPS (ELEMENT_LABEL, TOOLTIP, SCREEN_NAME, SUBMODULE, SUBMODULE_NAME)";
			$query2 .= "VALUES ('smvs_".$insert_id."','".addslashes($smvs_tool_tip)."','SM Vendor System - $smvs_name','Y','SMNTP_SM_SYSTEMS')";
			$result = $this->db->query($query2);
			return $result;
		}
	}

	function update_smvs($smvs_id, $department, $smvs_name, $smvs_tool_tip, $trade_vendor_type){

		$check_exist = $this->db->query("SELECT * FROM SMNTP_SM_SYSTEMS WHERE DESCRIPTION = '".addslashes($smvs_name)."' and TRADE_VENDOR_TYPE = '".$trade_vendor_type."' and REMOVED = 'N' and SM_SYSTEM_ID != ".$smvs_id)->result_array();
		if(count($check_exist) > 0){
			return "duplicate";
		}else{
			$query = "UPDATE SMNTP_SM_SYSTEMS ";
			$query.= "SET DEPARTMENT_ID = '".$department."', ";
			$query.= "DESCRIPTION = '".addslashes($smvs_name)."', ";
			$query.= "TOOL_TIP = '".addslashes($smvs_tool_tip)."', ";
			$query.= "TRADE_VENDOR_TYPE = '".$trade_vendor_type."' ";
			$query.= "WHERE SM_SYSTEM_ID = '".$smvs_id."'";
			$this->db->query($query);


			$query2 = "UPDATE SMNTP_VENDOR_TOOLTIPS ";
			$query2 .= "SET TOOLTIP = '".addslashes($smvs_tool_tip)."' ";
			$query2 .= "WHERE ELEMENT_LABEL = 'smvs_".$smvs_id."'";
			$result = $this->db->query($query2);
			return $result;
		}
	}

	function remove_smvs($smvs_id, $user_id){
		$query = "UPDATE SMNTP_SM_SYSTEMS ";
		$query.= "SET REMOVED = 'Y', ";
		$query.= "REMOVED_BY = '".$user_id."', ";
		$query.= "DATE_REMOVED = NOW() ";
		$query.= "WHERE SM_SYSTEM_ID = '".$smvs_id."'";
		$this->db->query($query);

		$query2 = "DELETE FROM SMNTP_VENDOR_TOOLTIPS ";
		$query2 .= "WHERE ELEMENT_LABEL = 'smvs_".$smvs_id."'";
		$result = $this->db->query($query2);
		return $result;
	}

	function vendor_insert($vendor_invite_id, $sm_system, $tvt, $fn, $mi, $ln, $pos, $ea, $mn, $user_id){

		$query1 = "DELETE FROM SMNTP_VENDOR_SM_SYSTEMS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id." and SM_SYSTEM_ID =".$sm_system;
		$this->db->query($query1);

		if ($fn != '' and $ln != '' and $pos != '' and $ea != '' and $mn != ''){
			$query2 = "INSERT INTO SMNTP_VENDOR_SM_SYSTEMS_TEMP (VENDOR_INVITE_ID, SM_SYSTEM_ID, TRADE_VENDOR_TYPE, FIRST_NAME, MIDDLE_NAME, LAST_NAME, POSITION, EMAIL, MOBILE_NO, DATE_CREATED, CREATED_BY)";
			$query2 .= "VALUES (".$vendor_invite_id.",".$sm_system.",".$tvt.",'".$fn."','".$mi."','".$ln."','".$pos."','".$ea."','".$mn."',NOW(),".$user_id.")";
			$result = $this->db->query($query2);
		}else{
			$result = 1;
		}

		return $result;
	}

	function vendor_revert_smvs($vendor_invite_id){

		$query1 = "DELETE FROM SMNTP_VENDOR_SM_SYSTEMS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id;
		$this->db->query($query1);

		$query2 = "INSERT INTO SMNTP_VENDOR_SM_SYSTEMS_TEMP SELECT * FROM SMNTP_VENDOR_SM_SYSTEMS WHERE VENDOR_INVITE_ID = ".$vendor_invite_id;
		$result = $this->db->query($query2);

		return $result;
	}

	function check_smvs($x){
		$get_vendor_info = $this->db->query("SELECT SVI.VENDOR_INVITE_ID, SVI.TRADE_VENDOR_TYPE, SV.VENDOR_CODE_02, SVI.REGISTRATION_TYPE FROM SMNTP_VENDOR_INVITE SVI LEFT JOIN SMNTP_VENDOR SV  ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID WHERE SVI.VENDOR_INVITE_ID =".$x)->result_array();
		$vendor_invite_id = $get_vendor_info[0]['VENDOR_INVITE_ID'];
		$trade_vendor_type = $get_vendor_info[0]['TRADE_VENDOR_TYPE'];
		$vendor_code_02 = $get_vendor_info[0]['VENDOR_CODE_02'];
		$registration_type = $get_vendor_info[0]['REGISTRATION_TYPE'];
		
		//Check Vendor Status
		$get_vendor_status = $this->db->query("SELECT STATUS_ID FROM SMNTP_VENDOR_STATUS WHERE VENDOR_INVITE_ID =".$x)->result_array();
		$vendor_status_id = $get_vendor_status[0]['STATUS_ID'];
		if($vendor_status_id == 190 || $vendor_status_id == 195){
			$data['query'] = 1;
			return $data;
		}

		if($vendor_code_02 != ''){
			$trade_vendor_type = "1,2";
		}

		if($registration_type == 4){
			$trade_vendor_type = "1,2";
		}

		$get_category = $this->db->query("SELECT category_id FROM SMNTP_VENDOR_CATEGORIES WHERE VENDOR_INVITE_ID = ".$vendor_invite_id)->result_array();
		
		$cat_id = "0";
		foreach($get_category as $category_id){
			$cat_id .= "," . $category_id['category_id'];
		}

		$finalquery = $this->db->query('
        	SELECT 
        	-- SSS.`SM_SYSTEM_ID`, SSS.DESCRIPTION, CASE SSS.TRADE_VENDOR_TYPE WHEN 1 THEN "OUTRIGHT" ELSE "SC" END AS TRADE_VENDOR_TYPE_DTL, SSS.TRADE_VENDOR_TYPE, SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME, SVSS.POSITION, SVSS.EMAIL, SVSS.MOBILE_NO
        	count(*) AS RECORD_COUNT
        	FROM SMNTP_SM_SYSTEMS SSS
        	LEFT JOIN SMNTP_VENDOR_SM_SYSTEMS_TEMP SVSS
        		ON SSS.SM_SYSTEM_ID = SVSS.SM_SYSTEM_ID 
        		AND SVSS.`VENDOR_INVITE_ID` = "'.$vendor_invite_id.'" 
        	WHERE SSS.REMOVED = "N"
        	AND SSS.DEPARTMENT_ID IN ('.$cat_id.')
			AND SSS.`SM_SYSTEM_ID` NOT IN (SELECT SM_SYSTEM_ID FROM SMNTP_VENDOR_SM_SYSTEMS WHERE REMOVED = "Y" AND VENDOR_INVITE_ID = "'.$vendor_invite_id.'")
			AND SSS.TRADE_VENDOR_TYPE IN ('.$trade_vendor_type.')
			AND SVSS.FIRST_NAME IS NULL
        	')->result_array();
        $data['query'] = $finalquery;

        return $data;
	}
}