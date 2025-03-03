<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class visit_invite_temp_model extends CI_Model{

		function m_create_vist($data)
		{

			$res = $this->db->insert('SMNTP_VST_INVT_TMPLT',$data);
			return $res;

		}

		function m_get_all()
		{


			$this->db->select('
				(CURRENT_DATE - V.VST_DATE_CREATED) AS DATE_SORTING_FORMAT, 
				U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				V.VST_INV_TITLE,
				V.VST_ID,
				V.VST_INV_MSG,		
				CAST(DATE_FORMAT(V.VST_DATE_CREATED,"%m/%d/%y %h:%i:%s %p") AS CHAR) AS VST_DATE_CREATED', FALSE);
			$this->db->from('SMNTP_VST_INVT_TMPLT V');
			$this->db->join('SMNTP_USERS U', 'V.USER_ID = U.USER_ID','LEFT');
			$this->db->where('V.VST_INV_STATUS','1');
			$sql = $this->db->get();
			$result = $sql->result_array();

			/*$i = 0;
			for($i=0;$i<count($result); $i++){
			$tmp = explode(" ", $result[$i]['VST_DATE_CREATED']);

			$result[$i]['VST_DATE_CREATED'] =date("M-d-Y", strtotime($tmp[0]));
			}*/
			return $result;

		}

		function m_edit_ven($data, $data2)
		{

			$this->db->where($data2);
			$res = $this->db->update('SMNTP_VST_INVT_TMPLT	', $data); 

			return $res;


		}


		function m_del_ven($data)
		{

			$arr = array(
				'VST_INV_STATUS' => '0'
				);
			$this->db->where($data);
			$res = $this->db->update('SMNTP_VST_INVT_TMPLT',$arr); 
			return $res;

		}

		function m_del_ven_mul($data)
		{

			$arr = array(
				'VST_INV_STATUS' => '0'
				);

			for($i = 0 ; $i < count($data['VST_ID']);$i++){

				$dat = array(
					'VST_ID' => $data['VST_ID'][$i]
				);

			$this->db->where($dat);
			 $res = $this->db->update('SMNTP_VST_INVT_TMPLT',$arr); 

			}


			return $res;

			
			 
			

		}






}
?>