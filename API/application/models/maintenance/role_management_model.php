<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Role_management_model extends CI_Model{

		function select_position(){
			$query = "SELECT POSITION_ID, POSITION_NAME FROM SMNTP_POSITION ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY POSITION_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_vendor_type(){
			$query = "SELECT * FROM SMNTP_VENDOR_TYPE ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY VENDOR_TYPE_ID ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}

		function select_all_screens(){
			$query = "SELECT SCREEN_ID, SCREEN_NAME FROM SMNTP_SCREENS ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY SCREEN_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}

		function select_screens($position_id, $vendor_type_id = NULL){
			$query = "SELECT A.SCREEN_ID, C.SCREEN_NAME, C.DESCRIPTION FROM SMNTP_SCREEN_POSITION_DEFN A ";
			$query.= "LEFT JOIN SMNTP_POSITION B ON A.USER_POSITION_ID = B.POSITION_ID ";
			$query.= "LEFT JOIN SMNTP_SCREENS C ON A.SCREEN_ID = C.SCREEN_ID ";
			$query.= "WHERE B.POSITION_ID = '".$position_id."' AND C.ACTIVE = 1 ";
			
			if(!empty($vendor_type_id) && $vendor_type_id != -1){
				$query.= "AND A.VENDOR_TYPE_ID = " . $vendor_type_id;
			}
			
			$query.= " ORDER BY SCREEN_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			// return $query;
		}

		function select_screens_not_in($position_id, $vendor_type_id = NULL){
			$query = "SELECT SCREEN_ID, SCREEN_NAME, DESCRIPTION FROM SMNTP_SCREENS WHERE SCREEN_ID NOT IN ( ";
			$query.= "SELECT SCREEN_ID FROM SMNTP_SCREEN_POSITION_DEFN ";
			$query.= "WHERE USER_POSITION_ID = '".$position_id."'";
			
			if(!empty($vendor_type_id) && $vendor_type_id != -1){
				$query.= "AND SMNTP_SCREEN_POSITION_DEFN.VENDOR_TYPE_ID = " . $vendor_type_id;
			}
			
			$query .=") AND ACTIVE = 1 ";
			$query .= " ORDER BY SCREEN_NAME ASC";


			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}

		function save_screens($position_id, $screens, $vendor_type_id = null){
			$result = null;
			
			if(!empty($vendor_type_id) && $vendor_type_id != -1){
				$this->db->query("DELETE FROM SMNTP_SCREEN_POSITION_DEFN WHERE USER_POSITION_ID = ? AND VENDOR_TYPE_ID = ?", array($position_id, $vendor_type_id));
				
				for ($x=0; $x<count($screens); $x++ ){
					$query = "INSERT INTO SMNTP_SCREEN_POSITION_DEFN (USER_POSITION_ID, SCREEN_ID, ACTIVE, VENDOR_TYPE_ID) ";
					$query.= "VALUES ('".$position_id."','".$screens[$x]."','1', '" . $vendor_type_id . "') ";
					
					$result = $this->db->query($query);
				}
			}else{
				$this->db->query("DELETE FROM SMNTP_SCREEN_POSITION_DEFN WHERE USER_POSITION_ID = ".$position_id);
				
				for ($x=0; $x<count($screens); $x++ ){
					$query = "INSERT INTO SMNTP_SCREEN_POSITION_DEFN (USER_POSITION_ID, SCREEN_ID, ACTIVE) ";
					$query.= "VALUES (".$position_id.",".$screens[$x].",1) ";
					
					$result = $this->db->query($query);
				}
			}
			return $result;
		}

// 		function save_role()


// INSERT INTO SMNTP_RFX_CURRENCY(CURRENCY, ABBREVIATION, COUNTRY, ACTIVE, CREATED_BY)
// SELECT 'WONA', '1', '1', '1', '1'
// FROM DUAL
// WHERE NOT EXISTS(SELECT * FROM SMNTP_RFX_CURRENCY WHERE CURRENCY='WONA')


// SELECT SCREEN_ID FROM SMNTP_SCREEN_POSITION_DEFN A LEFT JOIN SMNTP_POSITION B ON A.USER_POSITION_ID = B.POSITION_ID WHERE B.POSITION_ID =

		// function select_all_reqdocs(){
		// 	$query = "SELECT REQDOCS_ID, REQDOCS_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(UPLOAD_DATE, 'DAY MON DD, YYYY - HH:MI AM') AS REQDOCS_DATE FROM SMNTP_VENDOR_PARAM_REQDOCS ";
		// 	$query.= "WHERE ACTIVE = 1 ";
		// 	$query.= "ORDER BY REQDOCS_NAME ASC";

		// 	$result = $this->db->query($query);
		// 	return $result->result_array();
		// 	//return $query;
		// }
		
		// function select_reqdocs($search_text){
		// 	$query = "SELECT REQDOCS_ID, REQDOCS_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(UPLOAD_DATE, 'DAY MON DD, YYYY - HH:MI AM') AS REQDOCS_DATE FROM SMNTP_VENDOR_PARAM_REQDOCS ";
		// 	$query.= "WHERE (REQDOCS_NAME LIKE '%".$search_text."%' OR DESCRIPTION LIKE '%".$search_text."%' OR BUS_DIVISION LIKE '%".$search_text."%') ";
		// 	$query.= "AND ACTIVE = 1 ";
		// 	$query.= "ORDER BY REQDOCS_NAME ASC";

		// 	$result = $this->db->query($query);
		// 	return $result->result_array();
		// 	//return $query;
		// }
		
		// function insert_reqdocs($reqdocs_name, $description, $bus_division, $created_by){
		// 	$query = "INSERT INTO SMNTP_VENDOR_PARAM_REQDOCS (REQDOCS_NAME, DESCRIPTION, BUS_DIVISION, ACTIVE, CREATED_BY) ";
		// 	$query.= "VALUES ('".$reqdocs_name."','".$description."','".$bus_division."','1','".$created_by."')";
			
		// 	$result = $this->db->query($query);
		// 	return $result;
		// 	//return $query;
		// }
		
		// function update_reqdocs($reqdocs_id, $reqdocs_name, $description, $bus_division){
		// 	$query = "UPDATE SMNTP_VENDOR_PARAM_REQDOCS SET REQDOCS_NAME = '".$reqdocs_name."', ";
		// 	$query.= "DESCRIPTION = '".$description."', ";
		// 	$query.= "BUS_DIVISION = '".$bus_division."' ";
		// 	$query.= "WHERE REQDOCS_ID = '".$reqdocs_id."'";
			
		// 	$result = $this->db->query($query);
		// 	return $result;
		// 	//return $query;
		// }
		
		// function deactivate_reqdocs($reqdocs_id){
		// 	$query = "UPDATE SMNTP_VENDOR_PARAM_REQDOCS SET ACTIVE = 0 ";
		// 	$query.= "WHERE REQDOCS_ID = '".$reqdocs_id."'";
			
		// 	$result = $this->db->query($query);
		// 	return $result;
		// }
	}
?>
