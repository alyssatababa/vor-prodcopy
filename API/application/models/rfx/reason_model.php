<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Reason_model extends CI_Model{

		function select_all_reason(){
			$query = 'SELECT REASON_ID, REASON FROM SMNTP_RFX_REASON ';
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY REASON ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;

		}
		
		function select_reason($reason){
			$query = 'SELECT REASON_ID, REASON FROM SMNTP_RFX_REASON';
			$query.= " WHERE LOWER(REASON) LIKE LOWER('%".$reason."%') AND ACTIVE = 1";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;

		}
		
		function insert_reason($reason){
			
			$query = "INSERT INTO SMNTP_RFX_REASON (REASON_ID, REASON, ACTIVE, CREATED_BY) VALUES(?, ?, ?, ?)";
			$id = $this->get_latest_reason_id();
			$id = ((!empty($id)) ? ($id[0]->REASON_ID + 1 ) : 1 );
			$result = $this->db->query($query, array($id, $reason, 1, 1));
			return $result;
			//return $reason;
		}
		protected function get_latest_reason_id(){
			return $this->db->query('SELECT REASON_ID FROM (SELECT * FROM SMNTP_RFX_REASON ORDER BY REASON_ID DESC ) LIMIT 1')->result();
		}
		
		function update_reason($reason_id, $reason){
			$query = "UPDATE SMNTP_RFX_REASON SET REASON = '".$reason."' ";
			$query.= "WHERE REASON_ID = '".$reason_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function deactivate_reason($reason_id){
			$query = "UPDATE SMNTP_RFX_REASON SET ACTIVE = 0 ";
			$query.= "WHERE REASON_ID = '".$reason_id."'";
			
			$result = $this->db->query($query);
			return $query;
		}
	}
?>
