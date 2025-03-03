<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class vendor_request_pass_id_model extends CI_Model
{
	// function get_request_type(){
	// 	$query = "SELECT REQUEST_TYPE_NAME FROM SMNTP_REQUEST_TYPE WHERE ACTIVE =1";

	// 	$result = $this->db->get($query);

	// 	return $result->result_array();
	// }

	function vendor_request_insert($vendor_invite_id, $approval_date, $vendorname, $vendor_code, $vendor_code_02, $req_emailadd_outright, $req_emailadd_sc, $vendor_qty_outright, $reqtype_pass_outright, $vendor_qty_sc, $reqtype_pass_sc, $total_qty, $total_amount, $user_id, $vendorid){

		/*var_dump($vendor_invite_id);*/


		$vendor_request_id_old = 0;
		if($vendor_invite_id != ''){

 

			/*var_dump('C1');*/

 

			$vendor_request_id_old_arr = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."")->result_array();

 

 

			if (count($vendor_request_id_old_arr) > 0) {

 

				$vendor_request_id_old = $vendor_request_id_old_arr[0]['VENDOR_REQUEST_ID'];
				$count_vendor_request_id_old = count($vendor_request_id_old_arr);

 

				if($count_vendor_request_id_old >= 1){
					$query1 = "DELETE FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id." AND VENDOR_REQUEST_ID = ". $vendor_request_id_old;
					$this->db->query($query1);
					$query2 = "DELETE FROM SMNTP_VENDOR_ID_REQUESTS_TEMP WHERE VENDOR_REQUEST_ID = ".$vendor_request_id_old."";
					$this->db->query($query2);
					$del_exists = "DELETE FROM SMNTP_VENDOR_PASS_TEMP WHERE VENDOR_REQUEST_ID = ".$vendor_request_id_old."";
					$this->db->query($del_exists);
				}
			}
		}
		
	

			/*var_dump('C8');*/

			//var_dump($vendor_qty_outright); die();

				$query3 = "INSERT INTO SMNTP_VENDOR_REQUESTS_TEMP (DATE_CREATED, VENDOR_INVITE_ID, APPROVAL_DATE, VENDOR_NAME, TOTAL_PASS_QTY, TOTAL_AMOUNT_DEDUCTION, USER_ID)";
				$query3 .= 'VALUES (NOW(),'.$vendor_invite_id.',"'.$approval_date.'","'.$vendorname.'", '.$total_qty.', "'.$total_amount.'", '.$user_id.')';
				$this->db->query($query3);

			


			/*var_dump('C9');*/
		$vendor_request_id = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."")->row()->VENDOR_REQUEST_ID;



		//VENDOR_CODE
		
		$vendor_code1 = $this->db->query("SELECT SV.VENDOR_CODE,
						CASE WHEN SV.VENDOR_CODE_02 IS NULL THEN SVI.TRADE_VENDOR_TYPE 
						ELSE 
							CASE WHEN SVI.TRADE_VENDOR_TYPE = 1 THEN 2 ELSE 1 END
						END AS TRADE_VENDOR_TYPE
					FROM SMNTP_VENDOR SV
					LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID
					WHERE SV.VENDOR_CODE IS NOT NULL 
					AND SV.VENDOR_INVITE_ID = ".$vendor_invite_id."")->result_array();

		//var_dump($this->db->last_query()); die();	
		
		if(count($vendor_code1) > 0){
			if($vendor_code1[0]['TRADE_VENDOR_TYPE'] == 1){
				$query4 = "INSERT INTO SMNTP_VENDOR_PASS_TEMP (VENDOR_REQUEST_ID, VENDOR_CODE, EMAIL_ADD_OUTRIGHT, TRADE_VENDOR_TYPE, QTY, REQUEST_TYPE)";
				$query4 .= "VALUES (".$vendor_request_id.", '".$vendor_code."', '".$req_emailadd_outright."', '".$vendor_code1[0]['TRADE_VENDOR_TYPE']."', '".$vendor_qty_outright."', '".$reqtype_pass_outright."')";
				$this->db->query($query4);
			}else if($vendor_code1[0]['TRADE_VENDOR_TYPE'] == 2){
				$query4 = "INSERT INTO SMNTP_VENDOR_PASS_TEMP (VENDOR_REQUEST_ID, VENDOR_CODE, EMAIL_ADD_SC, TRADE_VENDOR_TYPE, QTY, REQUEST_TYPE)";
				$query4 .= "VALUES (".$vendor_request_id.", '".$vendor_code."', '".$req_emailadd_sc."', '".$vendor_code1[0]['TRADE_VENDOR_TYPE']."', '".$vendor_qty_sc."', '".$reqtype_pass_sc."')";
				$this->db->query($query4);

			//var_dump($query4);die();
			}
		}else{

			$get_trade_vendor_type = $this->db->query("SELECT TRADE_VENDOR_TYPE FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."")->result_array();

			if($get_trade_vendor_type[0]['TRADE_VENDOR_TYPE'] == 1){
				$query4 = "INSERT INTO SMNTP_VENDOR_PASS_TEMP (VENDOR_REQUEST_ID, TRADE_VENDOR_TYPE, EMAIL_ADD_OUTRIGHT, QTY, REQUEST_TYPE)";
				$query4 .= "VALUES (".$vendor_request_id.", '".$get_trade_vendor_type[0]['TRADE_VENDOR_TYPE']."', '".$req_emailadd_outright."', '".$vendor_qty_outright."', '".$reqtype_pass_outright."')";
				$this->db->query($query4);
			}else if($get_trade_vendor_type[0]['TRADE_VENDOR_TYPE'] == 2){
				$query4 = "INSERT INTO SMNTP_VENDOR_PASS_TEMP (VENDOR_REQUEST_ID, TRADE_VENDOR_TYPE, EMAIL_ADD_SC, QTY, REQUEST_TYPE)";
				$query4 .= "VALUES (".$vendor_request_id.", '".$get_trade_vendor_type[0]['TRADE_VENDOR_TYPE']."', '".$req_emailadd_sc."', '".$vendor_qty_sc."', '".$reqtype_pass_sc."')";
				$this->db->query($query4);
			}
			

			//var_dump($get_trade_vendor_type);die();
		}
		

		//VENDOR_CODE_02
		
		$vendor_code2 = $this->db->query("SELECT SV.VENDOR_CODE_02 AS VENDOR_CODE,
				CASE WHEN SV.VENDOR_CODE_02 IS NOT NULL THEN SVI.TRADE_VENDOR_TYPE 
				ELSE 
					CASE WHEN SVI.TRADE_VENDOR_TYPE = 1 THEN 2 ELSE 1 END
				END AS TRADE_VENDOR_TYPE
			FROM SMNTP_VENDOR SV
			LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID
			WHERE SV.VENDOR_CODE_02 IS NOT NULL 
			AND SV.VENDOR_INVITE_ID = ".$vendor_invite_id."")->result_array();

		//var_dump($vendor_code2); die();
		
		
		if(count($vendor_code2) > 0){
			if($vendor_code2[0]['TRADE_VENDOR_TYPE'] == 1){
			$query5 = "INSERT INTO SMNTP_VENDOR_PASS_TEMP (VENDOR_REQUEST_ID, VENDOR_CODE_02, EMAIL_ADD_OUTRIGHT, TRADE_VENDOR_TYPE, QTY, REQUEST_TYPE)";
			$query5 .= "VALUES (".$vendor_request_id.", '".$vendor_code_02."', '".$req_emailadd_outright."', '".$vendor_code2[0]['TRADE_VENDOR_TYPE']."', '".$vendor_qty_outright."', '".$reqtype_pass_outright."')";
			$this->db->query($query5);
			}else if($vendor_code2[0]['TRADE_VENDOR_TYPE'] == 2){
				$query5 = "INSERT INTO SMNTP_VENDOR_PASS_TEMP (VENDOR_REQUEST_ID, VENDOR_CODE_02, EMAIL_ADD_SC, TRADE_VENDOR_TYPE, QTY, REQUEST_TYPE)";
				$query5 .= "VALUES (".$vendor_request_id.", '".$vendor_code_02."', '".$req_emailadd_sc."', '".$vendor_code2[0]['TRADE_VENDOR_TYPE']."', '".$vendor_qty_sc."', '".$reqtype_pass_sc."')";
				$this->db->query($query5);
			}

			//var_dump($query5);die();
		}
		
		

		//var_dump($vendor_invite_id); die();

		/*var_dump('C10');*/

		/*if(count($vendor_request_id_old) > 0){
			$query4 = "DELETE FROM SMNTP_VENDOR_ID_REQUESTS_TEMP WHERE VENDOR_REQUEST_ID = ".$vendor_request_id_old."";
		$this->db->query($query4);

		}
		*/
		
		/*var_dump('C11');*/
		$vendorid = json_decode($vendorid);

		/*var_dump('C12');*/

		if(count($vendorid) == 0){
			return false;
		}else{
			foreach ($vendorid as $key => $value) {
			/*var_dump('C13');
			var_dump($vendor_request_id);*/

				$query6 = "INSERT INTO SMNTP_VENDOR_ID_REQUESTS_TEMP (IS_CHECKED, TRADE_VENDOR_TYPE, VENDOR_REQUEST_ID, FIRST_NAME, MIDDLE_INITIAL, LAST_NAME, DESIGNATION, REQUEST_TYPE, DATA_FROM, DATE_CREATED)";
				$query6 .= 'VALUES ("'.($value->IS_CHECKED).'","'.strtoupper($value->TRADE_VENDOR_TYPE).'",'.$vendor_request_id.', "'.strtoupper($value->FIRST_NAME).'", "'.strtoupper($value->MIDDLE_INITIAL).'" ,"'.strtoupper($value->LAST_NAME).'","'.strtoupper($value->POSITION).'", "'.strtoupper($value->REQUEST_TYPE).'", "'.strtoupper($value->DATA_FROM).'", NOW())';

	        	$result = $this->db->query($query6);

	        	//var_dump($query5); die();

        	/*var_dump('C14');*/
   			}

   		/*var_dump('C15');
   		var_dump($result);*/ 
   		//var_dump($vendorid); exit();
   	
   		//var_dump($result); die();
   		
        	return $result;
		}


		
		
	}

	function check_vendor_request($vendor_invite_id){
		$get_vendor_request = $this->db->query("SELECT COUNT(*) AS COUNT FROM SMNTP_VENDOR_REQUESTS WHERE VENDOR_INVITE_ID =".$vendor_invite_id)->result_array();

		$data['query'] = $get_vendor_request;

		//=var_dump($data); exit();
		return $data;
	}

	function check_pass_qty($vendor_invite_id){
		$get_pass_qty = $this->db->query("SELECT IFNULL(VENDOR_PASS_QTY, '0') AS VENDOR_PASS_QTY FROM SMNTP_VENDOR_REQUESTS WHERE VENDOR_INVITE_ID =".$vendor_invite_id)->result_array();

		if(!$get_pass_qty){
			$data['query'][0]['VENDOR_PASS_QTY'] = '0';
		}else{
			$data['query'] = $get_pass_qty;
		}



		//var_dump($this->db->last_query()); exit();
		return $data;
	}

	function vendor_email_insert($vendor_invite_id, $email_outright, $email_sc){

		$vendor_request_id = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."")->result_array();


		if (count($vendor_request_id) > 0) {
			$query = "UPDATE SMNTP_VENDOR_PASS_TEMP SET EMAIL_ADD_OUTRIGHT = '".$email_outright."', EMAIL_ADD_SC = '".$email_sc."' WHERE VENDOR_REQUEST_ID = ".$vendor_request_id[0]['VENDOR_REQUEST_ID'];

			$result = $this->db->query($query);

			return $result;
		}
	}
	
}