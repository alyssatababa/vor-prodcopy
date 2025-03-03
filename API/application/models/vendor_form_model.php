<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class vendor_form_model extends CI_Model{
//----Brand
		function create_new_brand($data)
		{
			
			$query = $this->db->query('SELECT COUNT(*) AS TOTAL FROM SMNTP_BRAND WHERE BRAND_NAME = ?', array($data['BRAND_NAME']))->result_array()[0]['TOTAL'];
			if($query > 0){
				return false;
			}
			$res = $this->db->insert('SMNTP_BRAND',$data);
			return $res;

		}
		
		function select_all_brand($data)
		{
	
			$status = array(
				'STATUS' => '1'
			);

			$this->db->select("(CURRENT_DATE - V.DATE_UPLOADED) AS DATE_SORTING_FORMAT, U.USER_FIRST_NAME,
			U.USER_MIDDLE_NAME,
			U.USER_LAST_NAME,
			V.BRAND_NAME,
			V.DESCRIPTION,
			V.BRAND_ID,
			V.STATUS, 
			CAST(DATE_FORMAT(V.DATE_UPLOADED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS DATE_UPLOADED				
			",FALSE);
			$this->db->from('SMNTP_BRAND V');
			$this->db->join('SMNTP_USERS U', 'V.CREATED_BY = U.USER_ID','LEFT');
			$this->db->where($status);
			$this->db->like($data);
			$this->db->limit(1000);
			$sql = $this->db->get();
			$result = $sql->result_array();
			return $result;
		}


		function edit_brand($data,$data2)
		{
					
			$this->db->where($data2);
			$res = $this->db->update('SMNTP_BRAND',$data);
			return $res;
		}

		function del_brand($data)
		{

			$data2 = array(
				'STATUS' => '0'
				);
					
			$this->db->where($data);
			$res = $this->db->update('SMNTP_BRAND',$data2);
			return $res;
		}




//----State
		function create_new_state($data)
		{

			$res = $this->db->insert('SMNTP_STATE_PROVINCE',$data);
			return $res;

		}


		function select_all_state($data)
		{

				$this->db->select("(CURRENT_DATE - V.DATE_UPLOADED) AS DATE_SORTING_FORMAT, U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				V.STATE_PROV_NAME,
				V.DESCRIPTION,
				V.STATE_PROV_ID,
				V.STATUS,
				CAST(DATE_FORMAT(V.DATE_UPLOADED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS DATE_UPLOADED				
				",FALSE);
			$this->db->from('SMNTP_STATE_PROVINCE V');
			$this->db->join('SMNTP_USERS U', 'V.CREATED_BY = U.USER_ID','LEFT');
			$this->db->like($data);
			$this->db->where(array('STATUS'=>'1'));
			$sql = $this->db->get();

			$result = $sql->result_array();
			return $result;
		}


		function edit_state($data,$data2)
		{
					
			$this->db->where($data2);
			$res = $this->db->update('SMNTP_STATE_PROVINCE',$data);
			return $res;
		}


		function del_state($data)
		{
			$status = array(
				'STATUS' => '0'
				);	
			$this->db->where($data);
			$res = $this->db->update('SMNTP_STATE_PROVINCE',$status);
			return $res;
		}
//----City
		function create_new_city($data)
		{

			$res = $this->db->insert('SMNTP_CITY',$data);
			return $res;

		}


		function select_all_city($data)
		{


				$this->db->select("(CURRENT_DATE - V.DATE_UPLOADED) AS DATE_SORTING_FORMAT, U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				V.CITY_NAME,
				V.DESCRIPTION,
				V.CITY_ID,
				V.STATUS,
				CAST(DATE_FORMAT(V.DATE_UPLOADED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS DATE_UPLOADED				
				",FALSE);
			$this->db->from('SMNTP_CITY V');
			$this->db->join('SMNTP_USERS U', 'V.CREATED_BY = U.USER_ID','LEFT');
			$this->db->like($data);
			$this->db->where(array('V.STATUS'=>'1'));
			$sql = $this->db->get();
			$result = $sql->result_array();
			return $result;
		}


		function edit_city($data,$data2)
		{
					
			$this->db->where($data2);
			$res = $this->db->update('SMNTP_CITY',$data);
			return $res;
		}

		function del_city($data)
		{
			$status = array(
				'STATUS'=>'0'
				);		
			$this->db->where($data);
			$res = $this->db->update('SMNTP_CITY',$status);
			return $res;
		}


//---Country
		function create_new_country($data)
		{

			$res = $this->db->insert('SMNTP_COUNTRY',$data);
			return $res;

		}


		function select_all_country($data)
		{


				$this->db->select("(CURRENT_DATE - V.DATE_UPLOADED) AS DATE_SORTING_FORMAT, U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				V.COUNTRY_NAME,
				V.DESCRIPTION,
				V.COUNTRY_ID,
				V.STATUS,
				CAST(DATE_FORMAT(V.DATE_UPLOADED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS DATE_UPLOADED,
				V.DEFAULT_FLAG			
				",FALSE);
			$this->db->from('SMNTP_COUNTRY V');
			$this->db->join('SMNTP_USERS U', 'V.CREATED_BY = U.USER_ID','LEFT');
			$this->db->where(array('STATUS'=>'1'));
			$this->db->like($data);
			$this->db->limit(1000);
			$sql = $this->db->get();
			$result = $sql->result_array();
			return $result;
		}


		function edit_country($data,$data2)
		{
					
			$this->db->where($data2);
			$res = $this->db->update('SMNTP_COUNTRY',$data);
			return $res;
		}
		function del_country($data)
		{
			$this->db->where($data);
			$res = $this->db->update('SMNTP_COUNTRY',array('STATUS' => '0'));
			return $res;
		}

		function m_sel_country($data)
		{
			$sql = "UPDATE SMNTP_COUNTRY SET DEFAULT_FLAG = 0";
			$query = $this->db->query($sql);
			
			$data2 = array(
				'DEFAULT_FLAG' => '1'
				);

			$this->db->where($data);
			$res = $this->db->update('SMNTP_COUNTRY',$data2);
			return $res;


		}

//---CATEGORY
		function get_category($data){
			return $this->db->query('SELECT * FROM SMNTP_CATEGORY WHERE UPPER(CATEGORY_NAME) = ? AND BUSINESS_TYPE = ?', $data)->result_array();
		}
		
		function uploader_create_new_category($data)
		{
			$id = $this->get_latest_category_id();
			$id = ((!empty($id)) ? ($id[0]->CATEGORY_ID + 1 ) : 1 );
			$data['CATEGORY_ID'] = $id;
			$query = 'INSERT INTO SMNTP_CATEGORY(CATEGORY_NAME, DESCRIPTION, BUSINESS_TYPE, CREATED_BY, CATEGORY_ID) VALUES(?, ?, ?, ?, ?)';
			$res = $this->db->insert('SMNTP_CATEGORY',$data);
			if($res){
				return $id;
			}else{
				return $res;
			}

		}
		
		function create_new_category($data)
		{
			$id = $this->get_latest_category_id();
			$id = ((!empty($id)) ? ($id[0]->CATEGORY_ID + 1 ) : 1 );
			$data['CATEGORY_ID'] = $id;
			$query = 'INSERT INTO SMNTP_CATEGORY(CATEGORY_NAME, DESCRIPTION, BUSINESS_TYPE, CREATED_BY, CATEGORY_ID) VALUES(?, ?, ?, ?, ?)';
			$res = $this->db->insert('SMNTP_CATEGORY',$data);
			return $res;

		}
		protected function get_latest_category_id(){
			return $this->db->query('SELECT MAX(CATEGORY_ID) AS CATEGORY_ID FROM SMNTP_CATEGORY')->result();
		}

		function select_all_category($data)
		{

				$this->db->select("(CURRENT_DATE - V.DATE_UPLOADED) AS DATE_SORTING_FORMAT, U.USER_FIRST_NAME,
				U.USER_MIDDLE_NAME,
				U.USER_LAST_NAME,
				V.CATEGORY_NAME,
				V.BUSINESS_TYPE,
				V.DESCRIPTION,
				V.CATEGORY_ID,
				V.STATUS,
				CAST(DATE_FORMAT(V.DATE_UPLOADED,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS DATE_UPLOADED				
				",FALSE);
			$this->db->from('SMNTP_CATEGORY V');
			$this->db->join('SMNTP_USERS U', 'V.CREATED_BY = U.USER_ID','LEFT');
			$this->db->where(array('V.STATUS' => '1'));
			$this->db->like($data);
			$this->db->limit(1000);
			$sql = $this->db->get();
			$result = $sql->result_array();
			return $result;
		}


		function edit_category($data,$data2)
		{
					
			$this->db->where($data2);
			$res = $this->db->update('SMNTP_CATEGORY',$data);
			return $res;
		}

		function del_category($data)
		{

			$sta =array(
				'STATUS' => '0'
				);
					
			$this->db->where($data);
			$res = $this->db->update('SMNTP_CATEGORY',$sta);
			return $res;
		}



}
?>