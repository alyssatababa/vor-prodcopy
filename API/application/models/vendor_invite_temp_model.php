<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class vendor_invite_temp_model extends CI_Model{

		function m_create_ven($data)
		{

			$tmpid = array(
				'VEN_INV_TITLE' => $data['VEN_INV_TITLE'],
				'VEN_INV_STATUS'	=> 1
			);


			$fp = $this->db->select('VEN_INV_MESSAGE')->where($tmpid)->where(array('USER_ID' => $data['USER_ID']))->from('SMNTP_VENDOR_INVITE_TEMPLATE')->get()->result_array();

			if(count($fp) > 0){


				return false;
			}

			if(strlen($data['VEN_INV_MESSAGE']) > 300){
				$data['VEN_INV_MESSAGE'] = substr($data['VEN_INV_MESSAGE'],0,300);
			}


			$res = $this->db->insert('SMNTP_VENDOR_INVITE_TEMPLATE',$data);
			return $res;

		}



		function m_get_all($data)
		{


			$data2 = array(
				'USER_ID' => $data['V.USER_ID']
				);


			$this->db->select('POSITION_ID');
			$utype = $this->db->where($data2)->get('SMNTP_USERS')->result_array();


			$this->db->select('
				(CURRENT_DATE - V.VEN_INV_DATE_CREATED) AS DATE_SORTING_FORMAT, 
				(CURRENT_DATE - V.VEN_INV_DATE_MODIFIED) AS DATE_SORTING_MODIFIED_FORMAT, 
				U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				V.VEN_INV_TITLE,
				V.VEN_INV_ID,
				V.VEN_INV_MESSAGE,
				CAST(DATE_FORMAT(V.VEN_INV_DATE_CREATED,"%m/%d/%y %h:%i:%s %p") AS CHAR) AS VEN_INV_DATE_CREATED,
				CAST(DATE_FORMAT(V.VEN_INV_DATE_MODIFIED,"%m/%d/%y %h:%i:%s %p") AS CHAR) AS VEN_INV_DATE_MODIFIED
				', false);
				// V.VEN_INV_DATE_CREATED,
				// V.VEN_INV_DATE_MODIFIED
				//
			$this->db->from('SMNTP_VENDOR_INVITE_TEMPLATE V');
			$this->db->join('SMNTP_USERS U', 'V.USER_ID = U.USER_ID','LEFT');
			$this->db->where('V.VEN_INV_STATUS','1');

			


			if($utype[0]['POSITION_ID'] != '1'){
			$this->db->where($data);
			}


			$sql = $this->db->get();

			$result = $sql->result_array();
			return $result;

		}

		function m_edit_ven($data, $data2, $user_id)
		{

			$tmpid = array(
				'VEN_INV_TITLE' => $data['VEN_INV_TITLE'],
				'USER_ID'		=> $user_id,
				'VEN_INV_STATUS'	=> 1
			);

			$rs = $this->db->select('VEN_INV_TITLE')->where($data2)->from('SMNTP_VENDOR_INVITE_TEMPLATE')->get()->result_array();

			if($rs[0]['VEN_INV_TITLE'] != $data['VEN_INV_TITLE']){

				$fp = $this->db->select('VEN_INV_MESSAGE')->where($tmpid)->from('SMNTP_VENDOR_INVITE_TEMPLATE')->get()->result_array();

				if(count($fp) > 0){

					return $this->db->last_query();
				}

			}

			if(strlen($data['VEN_INV_MESSAGE']) > 300){
				$data['VEN_INV_MESSAGE'] = substr($data['VEN_INV_MESSAGE'],0,300);
			}

			$this->db->where($data2);
			$res = $this->db->update('SMNTP_VENDOR_INVITE_TEMPLATE	', $data);

			return $res;


		}


		function m_del_ven($data)
		{

			$arr = array(
				'VEN_INV_STATUS' => '0'
				);
			$this->db->where($data);
			$res = $this->db->update('SMNTP_VENDOR_INVITE_TEMPLATE',$arr);
			$this->db->close();
			return $res;

		}

		function m_del_ven_mul($data)
		{

				$i = 0;




				for($i = 0;$i<count($data['ids']);$i++){

						$arr = array(
								'VEN_INV_ID' => $data['ids'][$i]
								);


							$this->db->where($arr);
						$res =$this->db->update('SMNTP_VENDOR_INVITE_TEMPLATE', array('VEN_INV_STATUS'=>0));

				}







			// $this->db->where($data);
			// $res = $this->db->update('SMNTP_VENDOR_INVITE_TEMPLATE',$arr);
			// $this->db->close();
		 return $res;

		}




}
?>
