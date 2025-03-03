<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Rfq_bid_monitor_model extends CI_Model{

		
		function select_rfqb($search_text){
			$query = "SELECT TITLE, CAST(DATE_FORMAT(SUBMISSION_DEADLINE,'%M %d,%Y') AS CHAR) AS SUB_DATE FROM SMNTP_RFQRFB ";
			$query.= "WHERE RFQRFB_ID = '". $search_text ."'";

			$result = $this->db->query($query);
			return $result->result_array();
			// return $query;

			// $this->db->select('TITLE, TO_CHAR(SUBMISSION_DEADLINE, 'DAY MON DD, YYYY - HH:MI AM') AS SUB_DATE');
			// $this->db->from('SMNTP_RFQRFB');
			// $this->db->where('RFQRFB_ID', $search_text);

			// $result = $this->db->get();
			// $purpose = $result->result_array();
			// // // // if ($result->num_rows() > 0)
			// // // // {
			// // // // 	foreach($result->result() as $row)
			// // // // 	{
			// // // // 		$purpose[$row->PURPOSE_ID] = $row->PURPOSE;
			// // // // 	}
			// // // // }

			// return $purpose;
		}

		function select_rfqrfb_ipr($rfqrfb_id){
			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB_IPR');
			$this->db->where('RFQRFB_ID', $rfqrfb_id);

			$result = $this->db->get();
			return $result->result_array();
		}

		function select_ipr_table($rfqrfb_id){
			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB_IPR_TABLE');
			$this->db->where('RFQRFB_ID', $rfqrfb_id);

			$result = $this->db->get();
			return $result->result_array();
		}

		function select_rfqrfb_position($rfqrfb_id){
			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB_IPR_TABLE');
			$this->db->where('RFQRFB_ID', $rfqrfb_id);

			$result = $this->db->get();
			return $result->result_array();			
		}

		function get_rfqrfb_config($rfqrfb_id){
			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB_STATUS RS' );
			$this->db->join('SMNTP_STATUS_CONFIG SC', 'RS.STATUS_ID = SC.CURRENT_STATUS_ID','LEFT');
			$this->db->where('RFQRFB_ID', $rfqrfb_id);

			$result = $this->db->get();
			return $result->result_array();			
		}

		function set_rfqrfb_status($rfqrfb_id, $rfqrfb_status, $reason, $position_id, $extension_date, $invite_status, $user_id, $curr_status){
			$format_date = date_create($extension_date);
			$new_date = date_format($format_date, 'd-M-Y');
			$query = "UPDATE SMNTP_RFQRFB_STATUS SET STATUS_ID = '".$rfqrfb_status."', APPROVER_REMARKS = '".$reason."', POSITION_ID = '".$position_id."', EXTENSION_DATE = STR_TO_DATE('".$new_date."','%d-%b-%y') ";
			$query.= "WHERE RFQRFB_ID = '".$rfqrfb_id."'";

			$result = $this->db->query($query);

			//logs/apporval history ============================

			$this->db->select('RFQRFB_STATUS_ID, EXTENSION_DATE, APPROVER_REMARKS');
			$this->db->from('SMNTP_RFQRFB_STATUS');
			$this->db->where('RFQRFB_ID', $rfqrfb_id);

			$query = $this->db->get();
			//$result = $query->result_array();

			$rfq_status_id = $query->row()->RFQRFB_STATUS_ID;
			$extension_date = $query->row()->EXTENSION_DATE;
			$approver_remarks = $query->row()->APPROVER_REMARKS;

			$logs_insert = array(
									'RFQRFB_STATUS_ID'		=> $rfq_status_id,
									'RFQRFB_ID'				=> $rfqrfb_id,
									'STATUS_ID' 			=> $rfqrfb_status,
									'POSITION_ID' 			=> $position_id,
									'APPROVER_REMARKS'		=> $approver_remarks,
									'APPROVER_ID'			=> $user_id,
									'DATE_UPDATED'			=> date('d-M-Y h:i: A'),
									'EXTENSION_DATE'		=> $extension_date

									);

			$this->db->insert('SMNTP_RFQRFB_STATUS_LOGS', $logs_insert);

			//=================================================

			// $this->db->select('*');
			// $this->db->from('SMNTP_RFQRFB_IPR_TABLE');
			// $this->db->where('RFQRFB_ID', $rfqrfb_id);

			// $result = $this->db->get();
			// return $result->result_array();	
			if ($invite_status != 0 && $rfqrfb_status == 26){
				$query = "UPDATE SMNTP_RFQRFB_INVITE_STATUS SET STATUS_ID = '".$invite_status."' ";
				$query.= "WHERE RFQRFB_ID = '".$rfqrfb_id."'";

				$result = $this->db->query($query);				
			}

			return $result;

			// if ($result == 1){
			// 	$format_date = date_create($sub_date);
			// 	$new_date = date_format($format_date, 'd-M-Y');
			// 	$query = "UPDATE SMNTP_RFQRFB SET SUBMISSION_DEADLINE = TO_DATE('".$new_date."', 'DD-MON-YY') ";
			// 	$query.= "WHERE RFQRFB_ID = '".$rfqrfb_id."'";			

			// 	$result = $this->db->query($query);				
			// 	return $result;
			// }
			
			// return $query;
		}

		function set_sub_deadline($rfqrfb_id, $sub_date){
			$format_date = date_create($sub_date);
			$new_date = date_format($format_date, 'd-M-Y');
			$query = "UPDATE SMNTP_RFQRFB SET SUBMISSION_DEADLINE = STR_TO_DATE('".$new_date."','%d-%b-%y') ";
			$query.= "WHERE RFQRFB_ID = '".$rfqrfb_id."'";
			// $this->db->select('*');
			// $this->db->from('SMNTP_RFQRFB_IPR_TABLE');
			// $this->db->where('RFQRFB_ID', $rfqrfb_id);

			// $result = $this->db->get();
			// return $result->result_array();			

			$result = $this->db->query($query);
			return $result;
			// return $query;
		}

		function select_rfqrfb_approval($rfqrfb_id){
			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB_MONITOR_APPROVAL');
			$this->db->where('RFQRFB_ID', $rfqrfb_id);
			// $query = "SELECT * FROM SMNTP_RFQRFB_MONITOR_APPROVAL WHERE RFQRFB_ID = '".$rfqrfb_id."'";

			$result = $this->db->get();
			return $result->result_array();
			// return "$query";
		}


// SELECT 
//   RIV.RFQRFB_ID, 
//   RF.TITLE, 
//   RV.VENDOR_NAME, 
//   (CASE WHEN RA.PARTICIPATE = '0' THEN 'REJECT' WHEN RA.PARTICIPATE = '1' THEN 'ACCEPT' ELSE '' END) AS INVITE_STATUS, 
//   RA.DATE_CREATED, 
//   RR.DATE_CREATED 
// FROM SMNTP_RFQRFB_INVITED_VENDORS RIV
// LEFT JOIN SMNTP_VENDOR RV ON RIV.VENDOR_ID = RV.VENDOR_ID
// LEFT JOIN SMNTP_RFQRFB_ACKNOWLEDGEMENT RA ON RIV.RFQRFB_ID = RA.RFQRFB_ID AND RIV.VENDOR_ID = RA.VENDOR_ID
// LEFT JOIN SMNTP_RFQRFB_RESPONSE RR ON RIV.RFQRFB_ID = RR.RFQRFB_ID AND RIV.VENDOR_ID = RR.VENDOR_ID
// LEFT JOIN SMNTP_RFQRFB RF ON RIV.RFQRFB_ID = RF.RFQRFB_ID;


		// function select_all_reqdocs(){
		// 	$query = "SELECT REQDOCS_ID, REQDOCS_NAME, DESCRIPTION, (CASE WHEN BUS_DIVISION = 1 THEN 'TRADE' ELSE 'NON-TRADE' END) AS BUS_DIVISION, TO_CHAR(UPLOAD_DATE, 'DAY MON DD, YYYY - HH:MI AM') AS REQDOCS_DATE FROM SMNTP_VENDOR_PARAM_REQDOCS ";
		// 	$query.= "WHERE ACTIVE = 1 ";
		// 	$query.= "ORDER BY REQDOCS_NAME ASC";

		// 	$result = $this->db->query($query);
		// 	return $result->result_array();
		// 	//return $query;
		// }
		
		function insert_reqdocs($reqdocs_name, $description, $bus_division, $created_by){
			$query = "INSERT INTO SMNTP_VENDOR_PARAM_REQDOCS (REQDOCS_NAME, DESCRIPTION, BUS_DIVISION, ACTIVE, CREATED_BY) ";
			$query.= "VALUES ('".$reqdocs_name."','".$description."','".$bus_division."','1','".$created_by."')";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}
		
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
