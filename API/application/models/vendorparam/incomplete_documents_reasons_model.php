<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Incomplete_documents_reasons_model extends CI_Model{

		function select_all_incdocreasons(){
			$query = 'SELECT 
						(CURRENT_DATE -( DATE_CREATED - interval \'2\' hour)) AS DATE_SORTING_FORMAT, 
						REASON_ID,
						DOCUMENT_TYPE, 
					   	CASE DOCUMENT_TYPE
					      	WHEN 1 THEN \'Primary Requirement\' 
					      	WHEN 2 THEN \'Additional Requirement\'
					   	END AS "DOCUMENT_TYPE_NAME",
					   
					   	CASE DOCUMENT_TYPE
					      	WHEN 1 THEN (SELECT REQUIRED_DOCUMENT_NAME FROM SMNTP_VP_REQUIRED_DOCUMENTS WHERE REQUIRED_DOCUMENT_ID = DOCUMENT_ID AND ACTIVE = 1)
					     	WHEN 2 THEN (SELECT REQUIRED_AGREEMENT_NAME FROM SMNTP_VP_REQUIRED_AGREEMENTS WHERE REQUIRED_AGREEMENT_ID = DOCUMENT_ID AND ACTIVE = 1)
					   	END AS "DOCUMENT_NAME",
					   
					   	DOCUMENT_ID, INCOMPLETE_REASON, CAST(DATE_FORMAT(DATE_CREATED,"%m/%d/%y %h:%i:%s %p") AS CHAR)  AS DATE_CREATED, CREATED_BY
					FROM SMNTP_INCOMPLETE_DOC_REASONS IDR 
					WHERE ACTIVE = 1 
					ORDER BY DATE_SORTING_FORMAT DESC';

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_incdocreasons($search_text){
			$query = 'SELECT
						(CURRENT_DATE -( DATE_CREATED - interval \'2\' hour)) AS DATE_SORTING_FORMAT, 
						REASON_ID,
						DOCUMENT_TYPE, 
					   	CASE DOCUMENT_TYPE
					      	WHEN 1 THEN \'Primary Requirement\' 
					      	WHEN 2 THEN \'Secondary Requirements\'
					   	END AS DOCUMENT_TYPE_NAME,
					   
					   	CASE DOCUMENT_TYPE
					      	WHEN 1 THEN (SELECT REQUIRED_DOCUMENT_NAME FROM SMNTP_VP_REQUIRED_DOCUMENTS WHERE REQUIRED_DOCUMENT_ID = DOCUMENT_ID AND ACTIVE = 1)
					     	WHEN 2 THEN (SELECT REQUIRED_AGREEMENT_NAME FROM SMNTP_VP_REQUIRED_AGREEMENTS WHERE REQUIRED_AGREEMENT_ID = DOCUMENT_ID AND ACTIVE = 1)
					   	END AS DOCUMENT_NAME,
					   
					   	DOCUMENT_ID, INCOMPLETE_REASON, CAST(DATE_FORMAT(DATE_CREATED,"%m/%d/%y %h:%i:%s %p") AS CHAR)  AS DATE_CREATED, CREATED_BY 
					FROM SMNTP_INCOMPLETE_DOC_REASONS IDR 
					WHERE (ACTIVE = 1) AND ';

			$query .= "(LOWER(INCOMPLETE_REASON) LIKE LOWER('%". trim($search_text). "%')) ";		
			$query .= 'ORDER BY DATE_SORTING_FORMAT DESC';

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function insert_incdocreasons($incdocreasons_name, $document_type_id, $document_name, $created_by){
			$query = "INSERT INTO SMNTP_INCOMPLETE_DOC_REASONS (INCOMPLETE_REASON, DOCUMENT_TYPE, DOCUMENT_ID, CREATED_BY) ";
			$query.= "VALUES ('".$incdocreasons_name."','".$document_type_id."','".$document_name."','".$created_by."')";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}
		
		function update_incdocreasons($incdocreasons_id, $incdocreasons_name, $document_type_id, $document_name){
			$query = "UPDATE SMNTP_INCOMPLETE_DOC_REASONS SET INCOMPLETE_REASON = '".$incdocreasons_name."', ";
			$query.= "DOCUMENT_TYPE = '".$document_type_id."', ";
			$query.= "DOCUMENT_ID = '".$document_name."' ";
			$query.= "WHERE REASON_ID = '".$incdocreasons_id."'";

			$result = $this->db->query($query);
			return $result;
			//return $query;
		}

		function deactivate_incdocreasons($incdocreasons_id){
			$query = "UPDATE SMNTP_INCOMPLETE_DOC_REASONS SET ACTIVE = 0 ";
			$query.= "WHERE REASON_ID = '".$incdocreasons_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
	}
?>
