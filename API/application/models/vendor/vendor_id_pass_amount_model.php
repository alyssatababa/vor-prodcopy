<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class vendor_id_pass_amount_model extends CI_Model
{
	
	function get_all_data(){
		// SELECT SSS.`SM_SYSTEM_ID`, SSS.`DESCRIPTION`, SCat.`CATEGORY_NAME`, SSS.`DATE_CREATED` FROM SMNTP_SM_SYSTEMS SSS 
		//	JOIN SMNTP_CATEGORY SCat ON SSS.`DEPARTMENT_ID` = SCat.`CATEGORY_ID`;
		
		$query = "SELECT AMOUNT_ID, AMOUNT, DESCRIPTION, EFFECTIVITY_DATE, DATE_CREATED FROM SMNTP_VENDOR_ID_PASS_AMOUNT ORDER BY AMOUNT_ID DESC LIMIT 10";

		$result = $this->db->query($query);

		return $result->result_array();
	}

	function search_vendor_id_pass_amount($search_string){
		$query = "SELECT AMOUNT_ID, AMOUNT, DESCRIPTION, EFFECTIVITY_DATE, DATE_CREATED FROM SMNTP_VENDOR_ID_PASS_AMOUNT ";

		$query.= "WHERE ACTIVE = 1 AND LOWER(DESCRIPTION) LIKE LOWER('%".addslashes($search_string)."%')";


		$result = $this->db->query($query);
		return $result->result_array();
	}

	function insert_vendor_id_pass_amount($AMOUNT, $description, $created_by, $effectivity_date){

		$check_exist = $this->db->query("SELECT * FROM SMNTP_VENDOR_ID_PASS_AMOUNT WHERE DESCRIPTION = '".addslashes($AMOUNT)."' AND ACTIVE = '1'")->result_array();
		if(count($check_exist) > 0){
			return "duplicate";
		}else{
			$query = "INSERT INTO SMNTP_VENDOR_ID_PASS_AMOUNT (AMOUNT, DESCRIPTION, DATE_CREATED, EFFECTIVITY_DATE, CREATED_BY) ";
			$query.= "VALUES ('".$AMOUNT."','".$description."',NOW(),'".$effectivity_date."','".$created_by."')";
			$result = $this->db->query($query);
		//	$insert_id = $this->db->insert_id(); 
		}
		return $result;
	}

	function update_vendor_id_pass_amount($amount_id, $amount, $description, $effectivity_date, $created_by){

		$check_exist = $this->db->query("SELECT AMOUNT_ID, AMOUNT, DESCRIPTION, EFFECTIVITY_DATE FROM SMNTP_VENDOR_ID_PASS_AMOUNT WHERE DESCRIPTION = '".addslashes($description)."' and ACTIVE = '1' and AMOUNT_ID != ".$amount_id." AND EFFECTIVITY_DATE = ".$effectivity_date)->result_array();
		if(count($check_exist) > 0){
			return "duplicate";
		}else{
			/*$query = "UPDATE SMNTP_VENDOR_ID_PASS_AMOUNT ";
			$query.= "SET AMOUNT = '".addslashes($amount)."', ";
			$query.= "DESCRIPTION = '".addslashes($description)."', ";
			$query.= "EFFECTIVITY_DATE = '".addslashes($effectivity_date)."' ";
			$query.= "WHERE AMOUNT_ID = '".$amount_id."'";
			$this->db->query($query);*/
			$query = "INSERT INTO SMNTP_VENDOR_ID_PASS_AMOUNT (AMOUNT, DESCRIPTION, DATE_CREATED, EFFECTIVITY_DATE, CREATED_BY) ";
			$query.= "VALUES ('".$amount."','".$description."',NOW(),'".$effectivity_date."','".$created_by."')";
			$result = $this->db->query($query);
		}
		return $result;
	}

	function remove_vendor_id_pass_amount($amount_id, $user_id){
		$query = "UPDATE SMNTP_VENDOR_ID_PASS_AMOUNT ";
		$query.= "SET ACTIVE = 0, ";
		$query.= "REMOVED_BY = '".$user_id."', ";
		$query.= "DATE_REMOVED = NOW() ";
		$query.= "WHERE AMOUNT_ID = '".$amount_id."'";
		$this->db->query($query);
		return $query;
	}
}