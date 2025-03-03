<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Status_model extends CI_Model{

		function select_all_status(){
			$query = 'SELECT STATUS_ID, STATUS from SMNTP_RFX_STATUS ';
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY STATUS ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;

		}
		
		function select_status($status){
			$query = 'SELECT STATUS_ID, STATUS from SMNTP_RFX_STATUS';
			$query.= " WHERE LOWER(STATUS) LIKE LOWER('%".$status."%') AND ACTIVE = 1";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;

		}
		
		function insert_status($status){
			$query = "INSERT INTO SMNTP_RFX_STATUS (STATUS_ID, STATUS, ACTIVE, CREATED_BY) VALUES(?, ?, ?, ?)";
			$id = $this->get_latest_status_id();
			$id = ((!empty($id)) ? ($id[0]->STATUS_ID + 1 ) : 1 );
			$result = $this->db->query($query, array($id, $status, 1, 1));
			//return $query;
			return $result;
		}
		protected function get_latest_status_id(){
			return $this->db->query('SELECT STATUS_ID FROM (SELECT * FROM SMNTP_RFX_STATUS ORDER BY STATUS_ID DESC ) LIMIT 1')->result();
		}
		
		function update_status($status_id, $status){
			$query = "UPDATE SMNTP_RFX_STATUS SET STATUS = '".$status."' ";
			$query.= "WHERE STATUS_ID = '".$status_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function deactivate_status($status_id){
			$query = "UPDATE SMNTP_RFX_STATUS SET ACTIVE = 0 ";
			$query.= "WHERE STATUS_ID = '".$status_id."'";
			
			$result = $this->db->query($query);
			return $query;
		}
	}
?>
