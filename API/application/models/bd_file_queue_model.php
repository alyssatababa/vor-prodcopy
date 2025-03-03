<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Bd_file_queue_model extends CI_Model{

		function read($file_queue_id){
			$this->db->from('SMNTP_BD_FILE_QUEUE Q');
            if ($file_queue_id)
		         $this->db->where('FILE_QUEUE_ID', $file_queue_id);

			$result = $this->db->get();
			return $result->result_array();
		}
	}
?>
