<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Requestor_model extends CI_Model{

		function select_all_requestor(){
			$query = "SELECT REQUESTOR_ID, REQUESTOR, COMPANY, DEPARTMENT FROM SMNTP_RFX_REQUESTOR ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY REQUESTOR ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_requestor($search_text){
			$query = "SELECT REQUESTOR_ID, REQUESTOR, COMPANY, DEPARTMENT FROM SMNTP_RFX_REQUESTOR ";
			$query.= "WHERE (LOWER(REQUESTOR) LIKE LOWER('%".$search_text."%') OR LOWER(COMPANY) LIKE LOWER('%".$search_text."%') OR LOWER(DEPARTMENT) LIKE LOWER('%".$search_text."%')) ";
			$query.= "AND ACTIVE = 1";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function insert_requestor($requestor, $abbreviation, $country){
			$query = "INSERT INTO SMNTP_RFX_REQUESTOR (REQUESTOR_ID, REQUESTOR, COMPANY, DEPARTMENT, ACTIVE, CREATED_BY) VALUES(?, ?, ?, ?, ?, ?) ";
			$id = $this->get_latest_requestor_id();
			$id = ((!empty($id)) ? ($id[0]->REQUESTOR_ID + 1 ) : 1 );
			$result = $this->db->query($query, array($id, $requestor, $abbreviation, $country, 1, 1));
			return $result;
		}
		
		protected function get_latest_requestor_id(){
			return $this->db->query('SELECT REQUESTOR_ID FROM (SELECT * FROM SMNTP_RFX_REQUESTOR ORDER BY REQUESTOR_ID DESC )  LIMIT 1')->result();
		}
		
		function update_requestor($requestor_id, $requestor, $abbreviation, $country){
			$query = "UPDATE SMNTP_RFX_REQUESTOR SET REQUESTOR = '".$requestor."', ";
			$query.= "COMPANY = '".$abbreviation."', ";
			$query.= "DEPARTMENT = '".$country."' ";
			$query.= "WHERE REQUESTOR_ID = '".$requestor_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function deactivate_requestor($requestor_id){
			$query = "UPDATE SMNTP_RFX_REQUESTOR SET ACTIVE = 0 ";
			$query.= "WHERE REQUESTOR_ID = '".$requestor_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function sort_requestor($order_by){
			$query = "SELECT REQUESTOR_ID, REQUESTOR, COMPANY, DEPARTMENT FROM SMNTP_RFX_REQUESTOR ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY " . $order_by;

			$result = $this->db->query($query);
			return $result->result_array();			
		}
	}
?>
