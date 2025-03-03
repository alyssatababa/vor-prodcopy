<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class edit_email_model extends CI_Model{

		function get_all_email()
		{
			$select = '*';
			$where['ACTIVE'] = 1;
			$table = 'SMNTP_EMAIL_DEFAULT_TEMPLATE';

			return $this->select_query($table,$where,$select);


		}

		function get_all_notification()
		{
			// $select = '*';
			// $where['STATUS_ID'] > 0;
			// $table = 'SMNTP_MESSAGE_DEFAULT';

			// return $this->select_query($table,$where,$select);

			$query = "SELECT A.*, B.STATUS_NAME FROM SMNTP_MESSAGE_DEFAULT A LEFT JOIN SMNTP_STATUS B ON A.STATUS_ID = B.STATUS_ID";
			$result = $this->db->query($query);
			//return $query;
			return $result->result_array();
		}

		function select_query($from,$where,$get)
		{
			$this->db->select($get);
			$this->db->from($from);
			$this->db->where($where);
			$result = $this->db->get();
			return $result->result_array();


		/*	
		$this->db->select($get)->from($from)->where($where)->get()->result_array();
		shv
		*/

		}

		function update_query($where,$updata,$table)
		{
			$this->db->where($where);
			$res = $this->db->update($table,$updata);
			return $res;

		}

		function insert_query($updata,$table)
		{
			return $this->db->insert($table,$updata);
		}
	


}
?>