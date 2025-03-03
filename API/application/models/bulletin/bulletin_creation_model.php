<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bulletin_creation_model extends CI_Model{

	public function get_bulletin_creation_data($data){
		$result['reference_no'] = $this->get_next_bulletin_reference_no();
		if(empty($result['reference_no'])){
			$result['reference_no'] = 1;
		}else{
			$result['reference_no'] = $result['reference_no'][0]['REFERENCE_NO'] + 1;
		}
		$result['companies'] 	 = $this->get_companies();
		$result['vendor_groups'] = $this->get_vendor_groups();
		
		return $result;
	}
	
	public function get_next_reference_id(){
		$next_id = $this->db->query('SELECT COUNT(*) AS TOTAL FROM SMNTP_BULLETIN')->result_array();
		return $next_id[0]['TOTAL'] + 1;
	}
	
	public function get_companies(){
		$companies = $this->db->query('SELECT COMPANY_ID, COMPANY_CODE, COMPANY_NAME 
										FROM SMNTP_COMPANIES 
										WHERE ACTIVE = 1 
										ORDER BY COMPANY_NAME')->result_array();
		return $companies;
	}
	
	public function get_vendor_groups(){
		$vendor_groups = $this->db->query('SELECT * 
												FROM SMNTP_VENDOR_LIST 
												WHERE ACTIVE = 1 
												ORDER BY VENDOR_LIST_NAME')->result_array();
												
		return $vendor_groups;
	}
	
	public function get_vendors($data){
		$query = $this->db->query("SELECT A.VENDOR_ID, A.VENDOR_NAME, A.VENDOR_TYPE, A.VENDOR_CODE
										FROM SMNTP_VENDOR A
										LEFT JOIN SMNTP_VENDOR_INVITE B ON A.VENDOR_INVITE_ID = B.VENDOR_INVITE_ID
										WHERE B.ACTIVE = 1 AND LOWER(A.VENDOR_NAME)
										LIKE '%". $data['vendorname'] . "%' 
										ORDER BY A.VENDOR_NAME");
		$results['result'] = $query->result();							
		$results['num_rows'] = $query->num_rows();
		return $results;
	}
	
	public function create_bulletin($data){
	
		$next_bulletin_id = $this->get_next_bulletin_id();
		if(empty($next_bulletin_id)){
			$next_bulletin_id = 1;
		}else{
			$next_bulletin_id = $next_bulletin_id[0]['BULLETIN_ID'] + 1;
		}
		
		//check updated reference next number again.
		
		$params['bulletin_id'] = $next_bulletin_id;
		
		//$params['reference_no'] = $data['reference_no'];
		$result['reference_no'] = $this->get_next_bulletin_reference_no();
		if(empty($result['reference_no'])){
			$params['reference_no'] = 1;
		}else{
			$params['reference_no'] = $result['reference_no'][0]['REFERENCE_NO'] + 1;
		}
		
		$params['subject'] = $data['subject'];
		$params['created_by'] = $data['user_id'];
		//$params['creation_date'] = $data['creation_date'];
		
		if($data['expiration_date'] == -1){
			$data['expiration_date'] = '';
		}else{
			$data['expiration_date'] = $data['expiration_date'];
		}
		$params['company_id'] = $data['company_id'];
		$params['bulletin_content'] = $data['message'];
		$params['bulletin_id_formatted'] = $data['bulletin_id_formatted'];
		$params['special_button'] = $data['special_button'];
		$data['publish_date'] = date('d-m-Y', strtotime($data['publish_date'])); 
		$data['approval_period'] = date('d-m-Y', strtotime($data['approval_period'])); 
		$data['expiration_date'] = date('d-m-Y', strtotime($data['publish_date']));   
		$sql = 'INSERT INTO SMNTP_BULLETIN
					(BULLETIN_ID,
						REFERENCE_NO,
						SUBJECT,
						CREATED_BY,
						PUBLISHING_DATE,
						APPROVAL_PERIOD,
						EXPIRATION_DATE,
						COMPANY_ID,
						BULLETIN_CONTENT,
						BULLETIN_ID_FORMATTED,
						SPECIAL_BUTTON) 
					VALUES(?, ?, ?, ?, 
							STR_TO_DATE("' . $data['publish_date'] . '","%d-%m-%y"), 
							STR_TO_DATE("' . $data['approval_period'] . '","%d-%m-%y"), 
							STR_TO_DATE("' . $data['expiration_date'] . '","%d-%m-%y"), 
							?, ?, ?, ? )';
		$result = $this->db->query($sql,$params, TRUE);
		
		if($result && ! empty($data['attachment_data'])){
			//Add attachment
	
			foreach($data['attachment_data'] as $value){
				$next_bulletin_attachment_id = $this->get_next_bulletin_attachment_id();
				if(empty($next_bulletin_attachment_id)){
					$next_bulletin_attachment_id = 1;
				}else{
					$next_bulletin_attachment_id = $next_bulletin_attachment_id[0]['BULLETIN_ATTACHMENT_ID'] + 1;
				}
				//edit bulletin formatted id here
				$sql = 'INSERT INTO SMNTP_BULLETIN_ATTACHMENT
							(BULLETIN_ATTACHMENT_ID, BULLETIN_ID, ATTACHMENT, CREATED_BY)
						VALUES(?, ?, ?, ?)';
				$this->db->query($sql, array($next_bulletin_attachment_id, 
														$next_bulletin_id,
														$value['full_path'],
														$params['created_by']));
			}
		}
		
		//vendors
		if($result && ! empty($data['vendors'])){
			$vendors = explode(',', $data['vendors']);
			
			foreach($vendors as $vendor){
				$next_bulletin_recepient_id = $this->get_next_bulletin_recepient_id();
				if(empty($next_bulletin_recepient_id)){
					$next_bulletin_recepient_id = 1;
				}else{
					$next_bulletin_recepient_id = $next_bulletin_recepient_id[0]['BULLETIN_RECEPIENT_ID'] + 1;
				}
				$sql = 'INSERT INTO SMNTP_BULLETIN_RECEPIENT
							(BULLETIN_RECEPIENT_ID, 
								BULLETIN_ID, 
								CREATED_BY,
								VENDOR_ID)
						VALUES(?, ?, ?, ?)';
				$this->db->query($sql, array($next_bulletin_recepient_id, 
														$next_bulletin_id,
														$params['created_by'],
														trim($vendor)));
			}
		}
		
		//groups
		if($result && ! empty($data['groups'])){
			$groups = explode(',', $data['groups']);
			
			foreach($groups as $group){
				$next_bulletin_recepient_id = $this->get_next_bulletin_recepient_id();
				if(empty($next_bulletin_recepient_id)){
					$next_bulletin_recepient_id = 1;
				}else{
					$next_bulletin_recepient_id = $next_bulletin_recepient_id[0]['BULLETIN_RECEPIENT_ID'] + 1;
				}
				$sql = 'INSERT INTO SMNTP_BULLETIN_RECEPIENT
							(BULLETIN_RECEPIENT_ID, 
								BULLETIN_ID, 
								CREATED_BY,
								GROUP_ID)
						VALUES(?, ?, ?, ?)';
				$this->db->query($sql, array($next_bulletin_recepient_id, 
														$next_bulletin_id,
														$params['created_by'],
														trim($group)));
			}
		}
		
		return $result;
	}
	public function get_bulletin_id_sequence(){
		return $this->db->query("SELECT COUNT(*) AS TOTAL FROM SMNTP_BULLETIN WHERE CAST(DATE_FORMAT(DATE_CREATED,'%m/%d/%Y') AS CHAR) = CAST(DATE_FORMAT(CURDATE(),'%m/%d/%Y') AS CHAR)")->result_array()[0]['TOTAL'] + 1;
	}
	
	protected function get_next_bulletin_recepient_id(){
		return $this->db->query('SELECT BULLETIN_RECEPIENT_ID 
									FROM SMNTP_BULLETIN_RECEPIENT
									ORDER BY BULLETIN_RECEPIENT_ID DESC 
									FETCH NEXT 1 ROWS ONLY')->result_array();
	}
	
	protected function get_next_bulletin_id(){
		return $this->db->query('SELECT BULLETIN_ID 
									FROM SMNTP_BULLETIN 
									ORDER BY BULLETIN_ID DESC 
									FETCH NEXT 1 ROWS ONLY')->result_array();
	}
	
	protected function get_next_bulletin_attachment_id(){
		return $this->db->query('SELECT BULLETIN_ATTACHMENT_ID 
									FROM SMNTP_BULLETIN_ATTACHMENT 
									ORDER BY BULLETIN_ATTACHMENT_ID DESC 
									FETCH NEXT 1 ROWS ONLY')->result_array();
	}
	
	protected function get_next_bulletin_reference_no(){
		return $this->db->query('SELECT REFERENCE_NO 
									FROM SMNTP_BULLETIN 
									ORDER BY REFERENCE_NO DESC 
									FETCH NEXT 1 ROWS ONLY')->result_array();
	}

	public function get_dashboard_data(){

		$res  =  $this->db->query("SELECT * FROM SMNTP_STATUS WHERE STATUS_TYPE = '4' ORDER BY STATUS_ID DESC");
		return $res->result_array();


	}

}

?>
