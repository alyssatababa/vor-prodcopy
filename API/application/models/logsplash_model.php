<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class logsplash_model extends CI_Model{

		function m_create_vist($data)
		{

			$query = "INSERT INTO SMNTP_LOGIN_SPLASHSCREEN_TMPLT (LST_ID, LST_TITLE, LST_MESSAGE, USER_ID) VALUES(?, ?, ?, ?)";
			$id = $this->get_latest_status_id();
			$id = ((!empty($id)) ? ($id[0]->LST_ID + 1 ) : 1 );
			$result = $this->db->query($query, array($id, $data['LST_TITLE'],  $data['LST_MESSAGE'],  $data['USER_ID']));
			
			return $result;
		}

		protected function get_latest_status_id(){
			return $this->db->query('SELECT LST_ID FROM (SELECT * FROM SMNTP_LOGIN_SPLASHSCREEN_TMPLT ORDER BY LST_ID DESC ) LIMIT 1')->result();
		}
		
		function m_get_all()
		{

			$this->db->select('(CURRENT_DATE - V.LST_DATE_CREATED) AS DATE_SORTING_FORMAT, U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				V.LST_TITLE,
				V.LST_ID,
				V.LST_MESSAGE,
				CAST(DATE_FORMAT(V.LST_DATE_CREATED,"%m/%d/%y %h:%i:%s %p") AS CHAR) AS LST_DATE_CREATED,
				V.SELECTED			
				', false);
			$this->db->from('SMNTP_LOGIN_SPLASHSCREEN_TMPLT V');
			$this->db->join('SMNTP_USERS U', 'V.USER_ID = U.USER_ID','LEFT');
			$this->db->where('V.LST_STATUS','1');
			$sql = $this->db->get();
			$result = $sql->result_array();
			return $result;

		}

		function m_edit_ven($data, $data2)
		{

			$this->db->where($data2);
			$res = $this->db->update('SMNTP_LOGIN_SPLASHSCREEN_TMPLT	', $data); 

			return $res;


		}


		function m_del_ven($data)
		{

			$arr = array(
				'LST_STATUS' => '0'
				);
			$this->db->where($data);
			$res = $this->db->update('SMNTP_LOGIN_SPLASHSCREEN_TMPLT',$arr); 
			$this->db->close();
			return $res;

		}

		function m_sel_sel($data)
		{
			$sql = "UPDATE SMNTP_LOGIN_SPLASHSCREEN_TMPLT SET SELECTED = 0";
			$query = $this->db->query($sql);
			
			$data2 = array(
				'SELECTED' => '1'
				);

			$this->db->where($data);
			$res = $this->db->update('SMNTP_LOGIN_SPLASHSCREEN_TMPLT',$data2);
			return $res;
		}

		function select_query($table,$where,$looking)
		{
			$this->db->select($looking);
			$this->db->where($where);
			$res = $this->db->get($table);
			return $res->result_array();
		}
		function update_query($table,$record,$where)
		{
			$this->db->where($where);
			$this->db->update($table,$record);
			return $this->db->affected_rows();

		}





}
?>