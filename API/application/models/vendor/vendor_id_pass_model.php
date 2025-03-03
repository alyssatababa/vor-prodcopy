<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class vendor_id_pass_model extends CI_Model
{
	
	function get_all_data(){
		// SELECT SSS.`SM_SYSTEM_ID`, SSS.`DESCRIPTION`, SCat.`CATEGORY_NAME`, SSS.`DATE_CREATED` FROM SMNTP_SM_SYSTEMS SSS 
		//	JOIN SMNTP_CATEGORY SCat ON SSS.`DEPARTMENT_ID` = SCat.`CATEGORY_ID`;
		
		$query = "SELECT ID, REQUEST_TYPE_NAME, REQUEST_TYPE_CODE, DESCRIPTION, DATE_CREATED FROM SMNTP_REQUEST_TYPE WHERE ACTIVE = 1";

		$result = $this->db->query($query);

		return $result->result_array();
	}

	function search_vendor_id_pass($search_string){
		$query = "SELECT ID, REQUEST_TYPE_NAME, REQUEST_TYPE_CODE, DESCRIPTION, DATE_CREATED FROM SMNTP_REQUEST_TYPE ";

		$query.= "WHERE ACTIVE = 1 AND LOWER(DESCRIPTION) LIKE LOWER('%".addslashes($search_string)."%') OR LOWER(REQUEST_TYPE_CODE) LIKE LOWER('%".addslashes($search_string)."%')";


		$result = $this->db->query($query);
		return $result->result_array();
	}

	function insert_vendor_id_pass($request_type_name, $request_type_code, $description, $created_by){

		$check_exist = $this->db->query("SELECT * FROM SMNTP_REQUEST_TYPE WHERE DESCRIPTION = '".addslashes($request_type_name)."' AND ACTIVE = '1'")->result_array();
		if(count($check_exist) > 0){
			return "duplicate";
		}else{
			$query = "INSERT INTO SMNTP_REQUEST_TYPE (REQUEST_TYPE_NAME, REQUEST_TYPE_CODE, DESCRIPTION, DATE_CREATED, CREATED_BY) ";
			$query.= "VALUES ('".$request_type_name."', '".$request_type_code."', '".$description."',NOW(),'".$created_by."')";
			$this->db->query($query);
		//	$insert_id = $this->db->insert_id(); 
		}
		return $query->result_array();
	}

	function update_vendor_id_pass($request_type_id, $request_type_name, $request_type_code, $description){

		$check_exist = $this->db->query("SELECT ID, REQUEST_TYPE_NAME, REQUEST_TYPE_CODE, DESCRIPTION FROM SMNTP_REQUEST_TYPE WHERE DESCRIPTION = '".addslashes($description)."' and ACTIVE = '1' and ID != ".$request_type_id)->result_array();
		if(count($check_exist) > 0){
			return "duplicate";
		}else{
			$query = "UPDATE SMNTP_REQUEST_TYPE ";
			$query.= "SET REQUEST_TYPE_NAME = '".addslashes($request_type_name)."', ";
			$query.= "REQUEST_TYPE_CODE = '".addslashes($request_type_code)."', ";
			$query.= "DESCRIPTION = '".addslashes($description)."' ";
			$query.= "WHERE ID = '".$request_type_id."'";
			$this->db->query($query);
		}
		return $query;
	}

	function remove_vendor_id_pass($request_type_id, $user_id){
		$query = "UPDATE SMNTP_REQUEST_TYPE ";
		$query.= "SET ACTIVE = 0, ";
		$query.= "REMOVED_BY = '".$user_id."', ";
		$query.= "DATE_REMOVED = NOW() ";
		$query.= "WHERE ID = '".$request_type_id."'";
		$this->db->query($query);
		return $query;
	}
}