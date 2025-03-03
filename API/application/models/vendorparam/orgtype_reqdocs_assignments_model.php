<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Orgtype_reqdocs_assignments_model extends CI_Model{

		function select_orgtype_reqdocs_not_in($orgtype_id,$vendortype_id){
			$query = "SELECT REQUIRED_DOCUMENT_ID, REQUIRED_DOCUMENT_NAME FROM SMNTP_VP_REQUIRED_DOCUMENTS WHERE REQUIRED_DOCUMENT_ID NOT IN ( ";
			$query.= "SELECT DOC_ID FROM SMNTP_VP_REQUIRED_DOCS_DEFN ";
			$query.= "WHERE OWNERSHIP_ID = '".$orgtype_id."' AND ";
			if($vendortype_id == 0){
				$query.= "VENDOR_TYPE_ID IS NULL) AND ";
			}else{
				$query.= "VENDOR_TYPE_ID = '".$vendortype_id."') AND ";
			}
			$query.= " ACTIVE = '1' ";
			$query.= "ORDER BY REQUIRED_DOCUMENT_NAME ASC";
			
			$result = $this->db->query($query);
			return $result->result_array();
			// return $query;
		}

		function select_orgtype_reqdocs($orgtype_id,$vendortype_id){
			$query = "SELECT A.OWNERSHIP_ID, C.REQUIRED_DOCUMENT_ID, C.REQUIRED_DOCUMENT_NAME FROM SMNTP_VP_REQUIRED_DOCS_DEFN A ";
			$query.= "LEFT JOIN SMNTP_OWNERSHIP B ON A.OWNERSHIP_ID = B.OWNERSHIP_ID ";
			$query.= "LEFT JOIN SMNTP_VP_REQUIRED_DOCUMENTS C ON A.DOC_ID = C.REQUIRED_DOCUMENT_ID ";
			$query.= "WHERE B.OWNERSHIP_ID = '".$orgtype_id."' AND ";
			if($vendortype_id == 0){
				$query.= "VENDOR_TYPE_ID IS NULL AND ";
			}else{
				$query.= "VENDOR_TYPE_ID = '".$vendortype_id."' AND ";
			}
			$query.= " A.ACTIVE = '1' ";
			$query.= "ORDER BY REQUIRED_DOCUMENT_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();			
		}

		function save_docs($orgtype_id, $docs, $created_by, $vendortype_id){
			$delete_query = "DELETE FROM SMNTP_VP_REQUIRED_DOCS_DEFN WHERE OWNERSHIP_ID = '".$orgtype_id."'";
			if($vendortype_id == 0){
				$delete_query .= " AND VENDOR_TYPE_ID IS NULL";
			}else{
				$delete_query .= " AND VENDOR_TYPE_ID ='".$vendortype_id."'";
			}
			$this->db->query($delete_query);
			//$this->db->query("UPDATE SMNTP_VP_REQUIRED_DOCS_DEFN SET ACTIVE = '0' WHERE OWNERSHIP_ID = '".$orgtype_id."'");
			$result = true;
			for ($x=0; $x<count($docs); $x++ ){
				if($docs[$x] != 0){
					$query = "INSERT INTO SMNTP_VP_REQUIRED_DOCS_DEFN (OWNERSHIP_ID, DOC_ID, ACTIVE, CREATED_BY, VENDOR_TYPE_ID) ";
					$query.= "VALUES ('".$orgtype_id."','".$docs[$x]."','1','".$created_by."'";
					
					if($vendortype_id == 0){
						$query.= ",null)"; 
					}else{
						$query.= ",'".$vendortype_id."')";
					}
					$result = $this->db->query($query);
				}
			}
			return $result;
			//return $query;
			//return count($docs) . " " . $docs[0];
		}

		// function save_screens($orgtype_id, $screens){
		// 	$this->db->query("DELETE SMNTP_SCREEN_POSITION_DEFN WHERE USER_ORGTYPE_ID = '".$orgtype_id."'");
		// 	for ($x=0; $x<count($screens); $x++ ){
		// 		$query = "INSERT INTO SMNTP_SCREEN_POSITION_DEFN (USER_ORGTYPE_ID, SCREEN_ID, ACTIVE) ";
		// 		$query.= "VALUES ('".$orgtype_id."','".$screens[$x]."','1') ";
				
		// 		$result = $this->db->query($query);
		// 	}
		// 	return $result;
		// }

	}
?>
