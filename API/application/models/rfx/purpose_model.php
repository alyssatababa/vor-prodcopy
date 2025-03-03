<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Purpose_model extends CI_Model{

		function select_all_purpose(){
			$query = 'SELECT PURPOSE_ID, PURPOSE from SMNTP_RFX_PURPOSE ';
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY PURPOSE ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;

		}
		
		function select_purpose($purpose){
			$query = 'SELECT PURPOSE_ID, PURPOSE from SMNTP_RFX_PURPOSE';
			$query.= " WHERE LOWER(PURPOSE) LIKE LOWER('%".$purpose."%') AND ACTIVE = 1";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;

		}
		
		function insert_purpose($purpose){
			$query = "INSERT INTO SMNTP_RFX_PURPOSE (PURPOSE_ID, PURPOSE, ACTIVE, CREATED_BY) VALUES(?, ?, ?, ?)";
			$id = $this->get_latest_purpose_id();
			$id = ((!empty($id)) ? ($id[0]->PURPOSE_ID + 1 ) : 1 );
			$result = $this->db->query($query, array($id, $purpose, 1, 1));
			return $result;
			//return $purpose;
		}
		protected function get_latest_purpose_id(){
			return $this->db->query('SELECT PURPOSE_ID FROM (SELECT * FROM SMNTP_RFX_PURPOSE ORDER BY PURPOSE_ID DESC ) LIMIT 1')->result();
		}
		
		function update_purpose($purpose_id, $purpose){
			$query = "UPDATE SMNTP_RFX_PURPOSE SET PURPOSE = '".$purpose."' ";
			$query.= "WHERE PURPOSE_ID = '".$purpose_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function deactivate_purpose($purpose_id){
			$query = "UPDATE SMNTP_RFX_PURPOSE SET ACTIVE = 0 ";
			$query.= "WHERE PURPOSE_ID = '".$purpose_id."'";
			
			$result = $this->db->query($query);
			return $query;
		}
	}
?>
