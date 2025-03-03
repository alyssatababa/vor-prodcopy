<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Currency_model extends CI_Model{

		function select_all_currency(){
			$query = "SELECT CURRENCY_ID, CURRENCY, ABBREVIATION, COUNTRY, DEFAULT_FLAG FROM SMNTP_RFX_CURRENCY ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY CURRENCY ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}

		function get_default(){
			$query = "SELECT CURRENCY_ID FROM SMNTP_RFX_CURRENCY ";
			$query.= "WHERE ACTIVE = 1 AND DEFAULT_FLAG = 1 ";
			$query.= "ORDER BY CURRENCY ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_currency($search_text){
			$query = "SELECT CURRENCY_ID, CURRENCY, ABBREVIATION, COUNTRY, DEFAULT_FLAG FROM SMNTP_RFX_CURRENCY ";
			$query.= "WHERE (LOWER(CURRENCY) LIKE LOWER('%".$search_text."%') OR LOWER(ABBREVIATION) LIKE LOWER('%".$search_text."%') OR LOWER(COUNTRY) LIKE LOWER('%".$search_text."%')) ";
			$query.= "AND ACTIVE = 1 ";
			$query.= "ORDER BY CURRENCY ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function insert_currency($currency, $abbreviation, $country){
			$query = "INSERT INTO SMNTP_RFX_CURRENCY (CURRENCY_ID, CURRENCY, ABBREVIATION, COUNTRY, ACTIVE, CREATED_BY) 
						VALUES(?, ?, ?, ?, ?, ?)";
			$id = $this->get_latest_currency_id();
			$id = ((!empty($id)) ? ($id[0]->CURRENCY_ID + 1 ) : 1 );
			$result = $this->db->query($query, array($id, $currency, $abbreviation, $country, 1, 1));
			return $result;
		}
		protected function get_latest_currency_id(){
			return $this->db->query('SELECT CURRENCY_ID FROM (SELECT * FROM SMNTP_RFX_CURRENCY ORDER BY CURRENCY_ID DESC ) LIMIT 1')->result();
		}
		
		function update_currency($currency_id, $currency, $abbreviation, $country){
			$query = "UPDATE SMNTP_RFX_CURRENCY SET CURRENCY = '".$currency."', ";
			$query.= "ABBREVIATION = '".$abbreviation."', ";
			$query.= "COUNTRY = '".$country."' ";
			$query.= "WHERE CURRENCY_ID = '".$currency_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function deactivate_currency($currency_id){
			$query = "UPDATE SMNTP_RFX_CURRENCY SET ACTIVE = 0 ";
			$query.= "WHERE CURRENCY_ID = '".$currency_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}

		function default_currency($currency_id){
			$query = "UPDATE SMNTP_RFX_CURRENCY SET DEFAULT_FLAG = 0 ";
			$result = $this->db->query($query);

			$query = "UPDATE SMNTP_RFX_CURRENCY SET DEFAULT_FLAG = 1 ";
			$query.= "WHERE CURRENCY_ID = '".$currency_id."'";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}
		
		function sort_currency($order_by){
			$query = "SELECT CURRENCY_ID, CURRENCY, ABBREVIATION, COUNTRY FROM SMNTP_RFX_CURRENCY ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY " . $order_by;

			$result = $this->db->query($query);
			return $result->result_array();			
		}
	}
?>
