<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class OrganizationType_model extends CI_Model{

		// function select_all_orgtype(){
		// 	$query = "SELECT OWNERSHIP_ID, OWNERSHIP_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(DATE_CREATED, 'MM/DD/YY HH12:MI AM')  AS OWNERSHIP_DATE FROM SMNTP_OWNERSHIP ";
		// 	$query.= "WHERE ACTIVE = 1 ";
		// 	$query.= "ORDER BY OWNERSHIP_NAME ASC";

		// 	$result = $this->db->query($query);
		// 	return $result->result_array();
		// 	//return $query;
		// }
		
		// function select_orgtype($search_text){
		// 	$query = "SELECT OWNERSHIP_ID, OWNERSHIP_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(DATE_CREATED, 'MM/DD/YY HH12:MI AM')  AS OWNERSHIP_DATE FROM SMNTP_OWNERSHIP ";
		// 	$query.= "WHERE (OWNERSHIP_NAME LIKE '%".$search_text."%' OR DESCRIPTION LIKE '%".$search_text."%' OR BUS_DIVISION LIKE '%".$search_text."%') ";
		// 	$query.= "AND ACTIVE = 1 ";
		// 	$query.= "ORDER BY OWNERSHIP_NAME ASC";

		// 	$result = $this->db->query($query);
		// 	return $result->result_array();
		// 	//return $query;
		// }
		
		// function insert_orgtype($orgtype_name, $description, $bus_division){
		// 	$query = "INSERT INTO SMNTP_OWNERSHIP (OWNERSHIP_NAME, DESCRIPTION, BUS_DIVISION, ACTIVE, CREATED_BY) ";
		// 	$query.= "VALUES ('".$orgtype_name."','".$description."','".$bus_division."','1','1')";
			
		// 	$result = $this->db->query($query);
		// 	return $result;
		// }
		
		// function update_orgtype($orgtype_id, $orgtype_name, $description, $bus_division){
		// 	$query = "UPDATE SMNTP_OWNERSHIP SET OWNERSHIP_NAME = '".$orgtype_name."', ";
		// 	$query.= "DESCRIPTION = '".$description."', ";
		// 	$query.= "BUS_DIVISION = '".$bus_division."' ";
		// 	$query.= "WHERE OWNERSHIP_ID = '".$orgtype_id."'";
			
		// 	$result = $this->db->query($query);
		// 	return $result;
		// 	//return $query;
		// }
		
		// function deactivate_orgtype($orgtype_id){
		// 	$query = "UPDATE SMNTP_OWNERSHIP SET ACTIVE = 0 ";
		// 	$query.= "WHERE OWNERSHIP_ID = '".$orgtype_id."'";
			
		// 	$result = $this->db->query($query);
		// 	return $result;
		// }

		function select_all_orgtype(){
			// $query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, OWNERSHIP_ID, OWNERSHIP_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(DATE_CREATED, 'MM/DD/YY HH12:MI AM')  AS OWNERSHIP_DATE FROM SMNTP_OWNERSHIP ";
			$query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, OWNERSHIP_ID, OWNERSHIP_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR)  AS OWNERSHIP_DATE FROM SMNTP_OWNERSHIP ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY OWNERSHIP_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_orgtype($search_text){
			// $query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, OWNERSHIP_ID, OWNERSHIP_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(DATE_CREATED, 'MM/DD/YY HH12:MI AM')  AS OWNERSHIP_DATE FROM SMNTP_OWNERSHIP ";
			$query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, OWNERSHIP_ID, OWNERSHIP_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS OWNERSHIP_DATE FROM SMNTP_OWNERSHIP ";

			$query.= "WHERE (LOWER(OWNERSHIP_NAME) LIKE LOWER('%".$search_text."%') OR LOWER(DESCRIPTION) LIKE LOWER('%".$search_text."%') OR LOWER(BUS_DIVISION) LIKE LOWER('%".$search_text."%')) ";
			$query.= "AND ACTIVE = 1 ";
			$query.= "ORDER BY OWNERSHIP_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function insert_orgtype($orgtype_name, $description, $bus_division, $created_by){
			$query = "INSERT INTO SMNTP_OWNERSHIP (OWNERSHIP_NAME, DESCRIPTION, BUS_DIVISION, ACTIVE, CREATED_BY) ";
			$query.= "VALUES ('".$orgtype_name."','".$description."','".$bus_division."','1','".$created_by."')";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function update_orgtype($orgtype_id, $orgtype_name, $description, $bus_division){
			$query = "UPDATE SMNTP_OWNERSHIP SET OWNERSHIP_NAME = '".$orgtype_name."', ";
			$query.= "DESCRIPTION = '".$description."', ";
			$query.= "BUS_DIVISION = '".$bus_division."' ";
			$query.= "WHERE OWNERSHIP_ID = '".$orgtype_id."'";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}
		
		function deactivate_orgtype($orgtype_id){
			$query = "UPDATE SMNTP_OWNERSHIP SET ACTIVE = 0 ";
			$query.= "WHERE OWNERSHIP_ID = '".$orgtype_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
	}
?>
