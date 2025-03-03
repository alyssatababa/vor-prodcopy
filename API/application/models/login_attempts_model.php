<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Login_attempts_model extends CI_Model{

		function read($user_id){

			$result = $this->db->get_where('SMNTP_LOGIN_ATTEMPTS', array('USER_ID'=>$user_id));
			// echo $this->db->last_query();
			// $result = $this->db->get();
			// var_dump($result->result_array());
			return $result->result_array();

		}

		function update($user_id,$unlock_time,$attempts,$last_attempt){
			$data = array(
               'UNLOCK_TIME' => $unlock_time,
			   'LAST_ATTEMPT' => $last_attempt,
               'ATTEMPTS' => $attempts
            );

			$this->db->where('USER_ID', $user_id);
			return $this->db->update('SMNTP_LOGIN_ATTEMPTS', $data);
		}

		function insert($user_id,$unlock_time,$attempts,$last_attempt){
			$data = array(
				'USER_ID' => $user_id,
				'UNLOCK_TIME' => $unlock_time,
				'LAST_ATTEMPT' => $last_attempt,
				'ATTEMPTS' => $attempts
			);

			return $this->db->insert('SMNTP_LOGIN_ATTEMPTS', $data);
		}
	}
?>
