<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Required_documents_model extends CI_Model{

		function select_all_reqdocs(){
			$query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_DOCUMENT_ID, REQUIRED_DOCUMENT_NAME, TOOL_TIP, (CASE WHEN DESCRIPTION IS NULL THEN 'NO DESCRIPTION' ELSE DESCRIPTION END) AS DESCRIPTION, 
			(CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' WHEN BUS_DIVISION = 2 THEN 'NON-TRADE SERVICE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, 
			CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR)  AS REQDOCS_DATE, SAMPLE_FILE 
			FROM SMNTP_VP_REQUIRED_DOCUMENTS ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY REQUIRED_DOCUMENT_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_reqdocs($search_text){
			// $query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_DOCUMENT_ID, REQUIRED_DOCUMENT_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' WHEN BUS_DIVISION = 2 THEN 'NON-TRADE SERVICE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(DATE_CREATED, 'MM/DD/YY HH12:MI AM')  AS REQDOCS_DATE, SAMPLE_FILE FROM SMNTP_VP_REQUIRED_DOCUMENTS ";
			$query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_DOCUMENT_ID, REQUIRED_DOCUMENT_NAME, DESCRIPTION, TOOL_TIP, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' WHEN BUS_DIVISION = 2 THEN 'NON-TRADE SERVICE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS REQDOCS_DATE, SAMPLE_FILE FROM SMNTP_VP_REQUIRED_DOCUMENTS ";

			$query.= "WHERE (LOWER(REQUIRED_DOCUMENT_NAME) LIKE LOWER('%".$search_text."%') OR LOWER(DESCRIPTION) LIKE LOWER('%".$search_text."%') OR LOWER(BUS_DIVISION) LIKE LOWER('%".$search_text."%')) ";
			$query.= "AND ACTIVE = 1 ";
			$query.= "ORDER BY REQUIRED_DOCUMENT_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function insert_reqdocs($reqdocs_name, $description, $bus_division, $created_by, $tool_tip){

			$query = "INSERT INTO SMNTP_VP_REQUIRED_DOCUMENTS (REQUIRED_DOCUMENT_NAME, DESCRIPTION, BUS_DIVISION, SAMPLE_FILE, ACTIVE, CREATED_BY,TOOL_TIP) ";
			$query.= "VALUES ('".$reqdocs_name."','".$description."','".$bus_division."','','1','".$created_by."','".addslashes($tool_tip)."')";
			$this->db->query($query);
			$insert_id = $this->db->insert_id(); 
			
			$query2 = "INSERT INTO SMNTP_VENDOR_TOOLTIPS (ELEMENT_LABEL, TOOLTIP, SCREEN_NAME, SUBMODULE, SUBMODULE_NAME)";
			$query2 .= " VALUES ('rsd_".$insert_id."','".addslashes($tool_tip)."','Required Documents - $reqdocs_name','Y','SMNTP_VP_REQUIRED_DOCUMENTS')";
			$result = $this->db->query($query2);
			return $result;
		}

		// CASE 
		
		function update_reqdocs($reqdocs_id, $reqdocs_name, $description, $bus_division, $tool_tip){
			$query = "UPDATE SMNTP_VP_REQUIRED_DOCUMENTS SET REQUIRED_DOCUMENT_NAME = '".$reqdocs_name."', ";
			$query.= "DESCRIPTION = '".$description."', ";
			$query.= "BUS_DIVISION = '".$bus_division."', ";
			$query.= "TOOL_TIP = '".addslashes($tool_tip)."' ";
			$query.= "WHERE REQUIRED_DOCUMENT_ID = '".$reqdocs_id."'";
			
			$result = $this->db->query($query);

			$exist = $this->db->query("SELECT * FROM SMNTP_VENDOR_TOOLTIPS WHERE ELEMENT_LABEL = 'rsd_".$reqdocs_id."'")->result_array();
			if(count($exist) > 0){
				$query2 = "UPDATE SMNTP_VENDOR_TOOLTIPS ";
				$query2 .= "SET TOOLTIP = '".addslashes($tool_tip)."' ";
				$query2 .= "WHERE ELEMENT_LABEL = 'rsd_".$reqdocs_id."'";
				$result = $this->db->query($query2);
			}else{
				$query2 = "INSERT INTO SMNTP_VENDOR_TOOLTIPS (ELEMENT_LABEL, TOOLTIP, SCREEN_NAME, SUBMODULE, SUBMODULE_NAME)";
				$query2 .= " VALUES ('rsd_".$reqdocs_id."','".addslashes($tool_tip)."','Required Documents','Y','SMNTP_VP_REQUIRED_DOCUMENTS')";
				$result = $this->db->query($query2);
			}

			return $result;
		}

		function save_sample_file($reqdocs_id, $sample_file){
			$query = "UPDATE SMNTP_VP_REQUIRED_DOCUMENTS SET SAMPLE_FILE = '".$sample_file."' ";
			$query.= "WHERE REQUIRED_DOCUMENT_ID = '".$reqdocs_id."'";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}

		function get_sample_file($reqdocs_id){
			$query = "SELECT SAMPLE_FILE FROM SMNTP_VP_REQUIRED_DOCUMENTS ";
			$query.= "WHERE REQUIRED_DOCUMENT_ID = '".$reqdocs_id."'";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;	
		}
		
		function deactivate_reqdocs($reqdocs_id){
			$query = "UPDATE SMNTP_VP_REQUIRED_DOCUMENTS SET ACTIVE = 0 ";
			$query.= "WHERE REQUIRED_DOCUMENT_ID = '".$reqdocs_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
	}
?>
