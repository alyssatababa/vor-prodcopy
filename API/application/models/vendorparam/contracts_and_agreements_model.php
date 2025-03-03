<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Contracts_and_agreements_model extends CI_Model{

		function select_all_ca(){
			$query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_AGREEMENT_ID, 
			REQUIRED_AGREEMENT_NAME, (CASE WHEN DESCRIPTION IS NULL THEN 'NO DESCRIPTION' ELSE DESCRIPTION END) 
			AS DESCRIPTION, TOOL_TIP, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' WHEN BUS_DIVISION = 2 THEN 'NOT-TRADE SERVICE' ELSE 'NON-TRADE' END) 
			AS BUS_DIVISION, CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS CA_DATE, DOWNLOADABLE, VIEWABLE 
			FROM SMNTP_VP_REQUIRED_AGREEMENTS ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY REQUIRED_AGREEMENT_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_ca($search_text){
			// $query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_AGREEMENT_ID, REQUIRED_AGREEMENT_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' WHEN BUS_DIVISION = 2 THEN 'NOT-TRADE SERVICE'  ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(DATE_CREATED, 'MM/DD/YY HH12:MI AM')  AS CA_DATE, DOWNLOADABLE, VIEWABLE FROM SMNTP_VP_REQUIRED_AGREEMENTS ";
			$query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_AGREEMENT_ID, REQUIRED_AGREEMENT_NAME, DESCRIPTION, TOOL_TIP, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' WHEN BUS_DIVISION = 2 THEN 'NOT-TRADE SERVICE'  ELSE 'NON-TRADE' END) AS BUS_DIVISION, CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS CA_DATE, DOWNLOADABLE, VIEWABLE FROM SMNTP_VP_REQUIRED_AGREEMENTS ";
			$query.= "WHERE (LOWER(REQUIRED_AGREEMENT_NAME) LIKE LOWER('%".$search_text."%') OR LOWER(DESCRIPTION) LIKE LOWER('%".$search_text."%') OR LOWER(BUS_DIVISION) LIKE LOWER('%".$search_text."%')) ";
			$query.= "AND ACTIVE = 1 ";
			$query.= "ORDER BY REQUIRED_AGREEMENT_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function insert_ca($ca_name, $description, $bus_division, $downloadable, $viewable, $tool_tip){
			$query = "INSERT INTO SMNTP_VP_REQUIRED_AGREEMENTS (REQUIRED_AGREEMENT_NAME, DESCRIPTION, BUS_DIVISION, DOWNLOADABLE, VIEWABLE, ACTIVE, CREATED_BY, TOOL_TIP)";
			$query.= " VALUES ('".$ca_name."','".addslashes($description)."','".$bus_division."','".$downloadable."', '".$viewable."', '1', '1', '".addslashes($tool_tip)."')";
			$this->db->query($query);
			$insert_id = $this->db->insert_id(); 
			
			$query2 = "INSERT INTO SMNTP_VENDOR_TOOLTIPS (ELEMENT_LABEL, TOOLTIP, SCREEN_NAME, SUBMODULE, SUBMODULE_NAME)";
			$query2 .= " VALUES ('ra_".$insert_id."','".addslashes($tool_tip)."','Additional Requirements - ".$ca_name."','Y','SMNTP_VP_REQUIRED_AGREEMENTS')";
			$result = $this->db->query($query2);
			return $result;
		}
		
		function update_ca($ca_id, $ca_name, $description, $bus_division, $downloadable, $viewable,$tool_tip){
			//$query = "UPDATE SMNTP_VP_REQUIRED_AGREEMENTS SET REQUIRED_AGREEMENT_NAME = '".$ca_name."', ";
			//$query.= "DESCRIPTION = '".$description."', ";
			//$query.= "BUS_DIVISION = '".$bus_division."', ";
			//$query.= "DOWNLOADABLE = '".$downloadable."', ";
			//$query.= "VIEWABLE = '".$viewable."' ";
			//$query.= "WHERE REQUIRED_AGREEMENT_ID = '".$ca_id."'";
			
			$result = $this->db->query('UPDATE SMNTP_VP_REQUIRED_AGREEMENTS SET  
										REQUIRED_AGREEMENT_NAME = ?,
										DESCRIPTION 	= ?, 
										BUS_DIVISION 	= ?, 
										DOWNLOADABLE	= ?, 
										VIEWABLE		= ?,
										TOOL_TIP 		= ?
										WHERE REQUIRED_AGREEMENT_ID = ? ', array(
											$ca_name, $description, $bus_division, $downloadable, $viewable, $tool_tip, $ca_id
										));

			$exist = $this->db->query("SELECT * FROM SMNTP_VENDOR_TOOLTIPS WHERE ELEMENT_LABEL = 'ra_".$ca_id."'")->result_array();
			if(count($exist) > 0){
				$query2 = "UPDATE SMNTP_VENDOR_TOOLTIPS ";
				$query2 .= "SET TOOLTIP = '".addslashes($tool_tip)."' ";
				$query2 .= "WHERE ELEMENT_LABEL = 'ra_".$ca_id."'";
				$result = $this->db->query($query2);
			}else{
				$query2 = "INSERT INTO SMNTP_VENDOR_TOOLTIPS (ELEMENT_LABEL, TOOLTIP, SCREEN_NAME, SUBMODULE, SUBMODULE_NAME)";
				$query2 .= " VALUES ('ra_".$ca_id."','".addslashes($tool_tip)."','Additional Contact Details','Y','SMNTP_VP_REQUIRED_AGREEMENTS')";
				$result = $this->db->query($query2);
			}

			return $result;
			//return $query;
		}

		function save_sample_file($ca_id, $sample_file){
			$query = "UPDATE SMNTP_VP_REQUIRED_AGREEMENTS SET DATE_CREATED = CURRENT_TIMESTAMP, SAMPLE_FILE = '".$sample_file."' ";
			$query.= "WHERE REQUIRED_AGREEMENT_ID = '".$ca_id."'";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}

		function get_sample_file($ca_id){
			$query = "SELECT SAMPLE_FILE FROM SMNTP_VP_REQUIRED_AGREEMENTS ";
			$query.= "WHERE REQUIRED_AGREEMENT_ID = '".$ca_id."'";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;	
		}
		
		function deactivate_ca($ca_id){
			$query = "UPDATE SMNTP_VP_REQUIRED_AGREEMENTS SET ACTIVE = 0 ";
			$query.= "WHERE REQUIRED_AGREEMENT_ID = '".$ca_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
	}
?>
