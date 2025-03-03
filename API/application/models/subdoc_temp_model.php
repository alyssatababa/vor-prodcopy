<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class subdoc_temp_model extends CI_Model{

		function m_create_vist($data)
		{
			$query = "INSERT INTO SMNTP_SUBMITTED_DOCS_TMPLT (SDT_ID, SDT_TITLE, SDT_MSG, USER_ID) VALUES(?, ?, ?, ?)";
			$id = $this->get_latest_status_id();
			$id = ((!empty($id)) ? ($id[0]->SDT_ID + 1 ) : 1 );
			$result = $this->db->query($query, array($id, $data['SDT_TITLE'],  $data['SDT_MSG'],  $data['USER_ID']));
			
			return $result;
		}
		
		protected function get_latest_status_id(){
			return $this->db->query('SELECT SDT_ID FROM (SELECT * FROM SMNTP_SUBMITTED_DOCS_TMPLT ORDER BY SDT_ID DESC ) LIMIT 1')->result();
		}
		function m_get_all()
		{


			$this->db->select('(CURRENT_DATE - V.SDT_DATE_CREATED) AS DATE_SORTING_FORMAT, U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				V.SDT_TITLE,
				V.SDT_ID,
				V.SDT_MSG,
				CAST(DATE_FORMAT(V.SDT_DATE_CREATED,"%m/%d/%y %h:%i:%s %p") AS CHAR) AS SDT_DATE_CREATED,
				V.SELECTED				
				', false);
			$this->db->from('SMNTP_SUBMITTED_DOCS_TMPLT V');
			$this->db->join('SMNTP_USERS U', 'V.USER_ID = U.USER_ID','LEFT');
			$this->db->where('V.SDT_STATUS','1');
			$sql = $this->db->get();
			$result = $sql->result_array();
			$i = 0;
			/*
			for($i=0;$i<count($result); $i++){
				$tmp = explode(" ", $result[$i]['SDT_DATE_CREATED']);
				$result[$i]['SDT_DATE_CREATED'] =date("M-d-Y", strtotime($tmp[0]));
			}*/

			return $result;

		}

		function m_edit_ven($data, $data2)
		{

			$this->db->where($data2);
			$res = $this->db->update('SMNTP_SUBMITTED_DOCS_TMPLT	', $data); 

			return $res;


		}


		function m_del_ven($data)
		{

			$arr = array(
				'SDT_STATUS' => '0'
				);
			$this->db->where($data);
			$res = $this->db->update('SMNTP_SUBMITTED_DOCS_TMPLT',$arr); 
			$this->db->close();
			return $res;

		}

		function m_sel_sel($data)
		{
			$sql = "UPDATE SMNTP_SUBMITTED_DOCS_TMPLT SET SELECTED = 0";
			$query = $this->db->query($sql);
			
			$data2 = array(
				'SELECTED' => '1'
				);

			$this->db->where($data);
			$res = $this->db->update('SMNTP_SUBMITTED_DOCS_TMPLT',$data2);
			return $res;


		}








}
?>