<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Secondary_documents_model extends CI_Model{

		function select_all_secagmt(){
			$query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_AGREEMENT_ID, REQUIRED_AGREEMENT_NAME, (CASE WHEN DESCRIPTION IS NULL THEN 'NO DESCRIPTION' ELSE DESCRIPTION END) AS DESCRIPTION, 
			(CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, 
			CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR)  AS secagmt_DATE, SAMPLE_FILE 
			FROM SMNTP_VP_REQUIRED_AGREEMENTS ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY REQUIRED_AGREEMENT_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_secagmt($search_text){
			// $query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_AGREEMENT_ID, REQUIRED_AGREEMENT_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(DATE_CREATED, 'MM/DD/YY HH12:MI AM')  AS secagmt_DATE, SAMPLE_FILE FROM SMNTP_VP_REQUIRED_AGREEMENTS ";
			$query = "SELECT (CURRENT_DATE -( DATE_CREATED - interval '2' hour)) AS DATE_SORTING_FORMAT, REQUIRED_AGREEMENT_ID, REQUIRED_AGREEMENT_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS secagmt_DATE, SAMPLE_FILE FROM SMNTP_VP_REQUIRED_AGREEMENTS ";

			$query.= "WHERE (LOWER(REQUIRED_AGREEMENT_NAME) LIKE LOWER('%".$search_text."%') OR LOWER(DESCRIPTION) LIKE LOWER('%".$search_text."%') OR LOWER(BUS_DIVISION) LIKE LOWER('%".$search_text."%')) ";
			$query.= "AND ACTIVE = 1 ";
			$query.= "ORDER BY REQUIRED_AGREEMENT_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function insert_secagmt($secagmt_name, $description, $bus_division, $created_by){
			$query = "INSERT INTO SMNTP_VP_REQUIRED_AGREEMENTS (REQUIRED_AGREEMENT_NAME, DESCRIPTION, BUS_DIVISION, SAMPLE_FILE, ACTIVE, CREATED_BY) ";
			$query.= "VALUES ('".$secagmt_name."','".$description."','".$bus_division."','','1','".$created_by."')";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}
		
		function update_secagmt($secagmt_id, $secagmt_name, $description, $bus_division){
			$query = "UPDATE SMNTP_VP_REQUIRED_AGREEMENTS SET REQUIRED_AGREEMENT_NAME = '".$secagmt_name."', ";
			$query.= "DESCRIPTION = '".$description."', ";
			$query.= "BUS_DIVISION = '".$bus_division."' ";
			$query.= "WHERE REQUIRED_AGREEMENT_ID = '".$secagmt_id."'";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}

		function save_sample_file($secagmt_id, $sample_file){
			$query = "UPDATE SMNTP_VP_REQUIRED_AGREEMENTS SET SAMPLE_FILE = '".$sample_file."' ";
			$query.= "WHERE REQUIRED_AGREEMENT_ID = '".$secagmt_id."'";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}

		function get_sample_file($secagmt_id){
			$query = "SELECT SAMPLE_FILE FROM SMNTP_VP_REQUIRED_AGREEMENTS ";
			$query.= "WHERE REQUIRED_AGREEMENT_ID = '".$secagmt_id."'";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;	
		}
		
		function deactivate_secagmt($secagmt_id){
			$query = "UPDATE SMNTP_VP_REQUIRED_AGREEMENTS SET ACTIVE = 0 ";
			$query.= "WHERE REQUIRED_AGREEMENT_ID = '".$secagmt_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
	}
?>
