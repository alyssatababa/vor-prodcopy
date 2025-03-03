<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Measure_model extends CI_Model{

		function select_all_measure(){
			$query = "SELECT UNIT_OF_MEASURE, MEASURE_NAME, MEASURE_ABBREV FROM SMNTP_UNIT_OF_MEASURE ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY MEASURE_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_measure($search_text){
			$query = "SELECT UNIT_OF_MEASURE, MEASURE_NAME, MEASURE_ABBREV FROM SMNTP_UNIT_OF_MEASURE ";
			$query.= "WHERE (LOWER(MEASURE_NAME) LIKE LOWER('%".$search_text."%') OR LOWER(MEASURE_ABBREV) LIKE LOWER('%".$search_text."%') ) ";
			$query.= "AND ACTIVE = 1";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function insert_measure($measure, $abbreviation){
			$query = "INSERT INTO SMNTP_UNIT_OF_MEASURE (UNIT_OF_MEASURE, MEASURE_NAME, MEASURE_ABBREV, ACTIVE, CREATED_BY) 
						VALUES(?, ?, ?, ?, ?)";
			$id = $this->get_latest_measure_id();
			$id = ((!empty($id)) ? ($id[0]->UNIT_OF_MEASURE + 1 ) : 1 );
			$result = $this->db->query($query, array($id, $measure, $abbreviation, 1, 1));
			return $result;
		}
		protected function get_latest_measure_id(){
			return $this->db->query('SELECT UNIT_OF_MEASURE FROM (SELECT * FROM SMNTP_UNIT_OF_MEASURE ORDER BY UNIT_OF_MEASURE DESC ) LIMIT 1')->result();
		}
		
		function update_measure($measure_id, $measure, $abbreviation){
			$query = "UPDATE SMNTP_UNIT_OF_MEASURE SET MEASURE_NAME = '".$measure."', ";
			$query.= "MEASURE_ABBREV = '".$abbreviation."' ";
			$query.= "WHERE UNIT_OF_MEASURE = '".$measure_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function deactivate_measure($measure_id){
			$query = "UPDATE SMNTP_UNIT_OF_MEASURE SET ACTIVE = 0 ";
			$query.= "WHERE UNIT_OF_MEASURE = '".$measure_id."'";
			
			$result = $this->db->query($query);
			return $result;
		}
		
		function sort_measure($order_by){
			$query = "SELECT UNIT_OF_MEASURE, MEASURE_NAME, MEASURE_ABBREV FROM SMNTP_UNIT_OF_MEASURE ";
			$query.= "WHERE ACTIVE = 1 ";
			$query.= "ORDER BY " . $order_by;

			$result = $this->db->query($query);
			return $result->result_array();			
		}
	}
?>
