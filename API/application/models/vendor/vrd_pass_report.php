<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
*
*/
class vrd_pass_report extends CI_Model
{
	
	function get_data(){
		$file_name = date('mdy').'.VOR';
		$vendor_codes = '';
		$vendor_codes_02 = '';
		$vendor_codes_v4 = '';
		$res = [];
		$res2 = [];
		$res3 = [];

		$date = '2024-10-19 00:00:01';

		
		$get_vendor_code = $this->db->query("SELECT SV.VENDOR_CODE FROM SMNTP_VENDOR_INVITE SVI 
											 JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID
											 JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID
											 LEFT JOIN SMNTP_VENDOR_REQUESTS SVR ON SVI.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
											 WHERE SVR.PASS_EXTRACTED != 'Y'
											 AND SVS.STATUS_ID = 19 
											 AND SVI.REGISTRATION_TYPE IN (1,2,3,5) 
											 AND SVS.DATE_UPDATED BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()");
		$result_vendor_code = $get_vendor_code->result();
		$count_vendor_code = count($result_vendor_code);
		if($count_vendor_code > 0){
			for($b=0; $b<$count_vendor_code; $b++){
				if($vendor_codes != ''){
					$vendor_codes .= ',"'. $result_vendor_code[$b]->VENDOR_CODE . '"';	
				}else{
					$vendor_codes .= '"'. $result_vendor_code[$b]->VENDOR_CODE . '"';	
				}
			}
			
			$query = 'SELECT SVP.VENDOR_CODE,
						SVR.VENDOR_INVITE_ID,
						SVR.VENDOR_REQUEST_ID,
						SVR.DATE_CREATED,
						SVR.APPROVAL_DATE,
						SVP.TRADE_VENDOR_TYPE,
						A.NEW_TRADE_VENDOR_TYPE,
						UCASE(SVR.VENDOR_NAME) AS VENDOR_NAME,
						CASE SVP.TRADE_VENDOR_TYPE WHEN 1 THEN EMAIL_ADD_OUTRIGHT 
							WHEN 2 THEN EMAIL_ADD_SC  END AS REQUESTORS_EMAIL_ADD,
						SVP.QTY,
						SVP.REQUEST_TYPE,
						SRT.REQUEST_TYPE_CODE
						FROM SMNTP_VENDOR_REQUESTS SVR
						LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVP.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						JOIN SMNTP_REQUEST_TYPE SRT ON SVP.REQUEST_TYPE = SRT.REQUEST_TYPE_NAME
						LEFT JOIN (SELECT CASE WHEN SVI.PREV_REGISTRATION_TYPE = 4 OR SVI.REGISTRATION_TYPE = 4 THEN 
									CASE SV.TRADE_VENDOR_TYPE WHEN 1 THEN 2 ELSE 1 END
								ELSE 
									SV.TRADE_VENDOR_TYPE
								END AS NEW_TRADE_VENDOR_TYPE,
								SV.TRADE_VENDOR_TYPE,
								SVI.VENDOR_INVITE_ID,
								SVI.PREV_REGISTRATION_TYPE,
								SVI.REGISTRATION_TYPE
							FROM SMNTP_VENDOR SV 
							LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
							WHERE SV.VENDOR_CODE IN ('.$vendor_codes.')) A ON A.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
						WHERE VENDOR_CODE IN ('.$vendor_codes.')';
			//return $query;
			$res = $this->db->query($query);
			$res = $res->result_array();
		}else{
			$res = [];
		}
		$get_vendor_code_02 = $this->db->query("SELECT SV.VENDOR_CODE_02 FROM SMNTP_VENDOR_INVITE SVI 
											 JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID
											 JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID
											 LEFT JOIN SMNTP_VENDOR_REQUESTS SVR ON SVI.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
											 WHERE SVR.PASS_EXTRACTED != 'Y'
											 AND SVS.STATUS_ID = 19 
											 AND SV.VENDOR_CODE_02 IS NOT NULL
											 AND SVI.REGISTRATION_TYPE IN (2,3,5) 
											 AND SVS.DATE_UPDATED BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()");
		$result_vendor_code_02 = $get_vendor_code_02->result();

		//var_dump($result_vendor_code_02);die;
		$count_vendor_code_02 = count($result_vendor_code_02);
		if($count_vendor_code_02 > 0){
			for($b=0; $b<$count_vendor_code_02; $b++){
				if($vendor_codes_02 != ''){
					$vendor_codes_02 .= ',"'. $result_vendor_code_02[$b]->VENDOR_CODE_02 . '"';	
				}else{
					$vendor_codes_02 .= '"'. $result_vendor_code_02[$b]->VENDOR_CODE_02 . '"';	
				}
			}
			
			$query2 = 'SELECT SVP.VENDOR_CODE_02 AS VENDOR_CODE,
						SVR.VENDOR_INVITE_ID,
						SVR.VENDOR_REQUEST_ID,
						SVR.DATE_CREATED,
						SVR.APPROVAL_DATE,
						SVP.TRADE_VENDOR_TYPE,
						A.NEW_TRADE_VENDOR_TYPE,
						UCASE(SVR.VENDOR_NAME) AS VENDOR_NAME,
						CASE SVP.TRADE_VENDOR_TYPE WHEN 1 THEN EMAIL_ADD_OUTRIGHT 
							WHEN 2 THEN EMAIL_ADD_SC  END AS REQUESTORS_EMAIL_ADD,
						SVP.QTY,
						SVP.REQUEST_TYPE,
						SRT.REQUEST_TYPE_CODE
						FROM SMNTP_VENDOR_REQUESTS SVR
						LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVP.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						JOIN SMNTP_REQUEST_TYPE SRT ON SVP.REQUEST_TYPE = SRT.REQUEST_TYPE_NAME
						LEFT JOIN (SELECT CASE WHEN SVI.PREV_REGISTRATION_TYPE = 4 OR SVI.REGISTRATION_TYPE = 4 THEN 
									CASE SV.TRADE_VENDOR_TYPE WHEN 1 THEN 2 ELSE 1 END
								ELSE 
									SV.TRADE_VENDOR_TYPE
								END AS NEW_TRADE_VENDOR_TYPE,
								SV.TRADE_VENDOR_TYPE,
								SVI.VENDOR_INVITE_ID,
								SVI.PREV_REGISTRATION_TYPE,
								SVI.REGISTRATION_TYPE
							FROM SMNTP_VENDOR SV 
							LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
							WHERE SV.VENDOR_CODE_02 IN ('.$vendor_codes_02.')) A ON A.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
						WHERE VENDOR_CODE_02 IN ('.$vendor_codes_02.')';
			//return $query2;
			 $res2 = $this->db->query($query2);
			 $res2 = $res2->result_array();
		}else{
			$res2 = [];

			//var_dump($res2);die;
	}
		$get_vendor_code_v4 = $this->db->query("SELECT SV.VENDOR_CODE_02 FROM SMNTP_VENDOR_INVITE SVI 
											 JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID
											 JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID
											 LEFT JOIN SMNTP_VENDOR_REQUESTS SVR ON SVI.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
											 WHERE SVR.PASS_EXTRACTED != 'Y'
											 AND SVS.STATUS_ID = 19 
											 AND SVI.REGISTRATION_TYPE IN (4) 
											 AND SVS.DATE_UPDATED BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()");
		$result_vendor_code_v4 = $get_vendor_code_v4->result();
		$count_vendor_code_v4 = count($result_vendor_code_v4);
		if($count_vendor_code_v4 > 0){
			for($b=0; $b<$count_vendor_code_v4; $b++){
				if($vendor_codes_v4 != ''){
					$vendor_codes_v4 .= ',"'. $result_vendor_code_v4[$b]->VENDOR_CODE_02 . '"';	
				}else{
					$vendor_codes_v4 .= '"'. $result_vendor_code_v4[$b]->VENDOR_CODE_02 . '"';	
				}
			}
			
			$query3 = 'SELECT SVP.VENDOR_CODE_02 AS VENDOR_CODE,
						SVR.VENDOR_INVITE_ID,
						SVR.VENDOR_REQUEST_ID,
						SVR.DATE_CREATED,
						SVR.APPROVAL_DATE,
						SVP.TRADE_VENDOR_TYPE,
						A.NEW_TRADE_VENDOR_TYPE,
						A.TRADE_VENDOR_TYPE,
						UCASE(SVR.VENDOR_NAME) AS VENDOR_NAME,
						CASE SVP.TRADE_VENDOR_TYPE WHEN 1 THEN EMAIL_ADD_OUTRIGHT 
							WHEN 2 THEN EMAIL_ADD_SC  END AS REQUESTORS_EMAIL_ADD,
						SVP.QTY,
						SVP.REQUEST_TYPE,
						SRT.REQUEST_TYPE_CODE
						FROM SMNTP_VENDOR_REQUESTS SVR
						LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVP.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						JOIN SMNTP_REQUEST_TYPE SRT ON SVP.REQUEST_TYPE = SRT.REQUEST_TYPE_NAME
						LEFT JOIN (SELECT CASE WHEN SVI.PREV_REGISTRATION_TYPE = 4 OR SVI.REGISTRATION_TYPE = 4 THEN 
									CASE SV.TRADE_VENDOR_TYPE WHEN 1 THEN 2 ELSE 1 END
								ELSE 
									SV.TRADE_VENDOR_TYPE
								END AS NEW_TRADE_VENDOR_TYPE,
								SV.TRADE_VENDOR_TYPE,
								SVI.VENDOR_INVITE_ID,
								SVI.PREV_REGISTRATION_TYPE,
								SVI.REGISTRATION_TYPE
							FROM SMNTP_VENDOR SV 
							LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
							WHERE SV.VENDOR_CODE_02 IN ('.$vendor_codes_v4.')) A ON A.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
						WHERE VENDOR_CODE_02 IN ('.$vendor_codes_v4.')';
			//return $query;
			 $res3 = $this->db->query($query3);
			$res3 = $res3->result_array();
		}else{
			$res3 = [];
	}
		$finalquery = array_merge($res, $res2, $res3);

		//var_dump($finalquery);die;

		foreach ($finalquery as $value) {

			$check_exists = $this->db->query('SELECT * FROM SMNTP_VENDOR_REQUEST_HISTORY 
				WHERE VENDOR_INVITE_ID = '.$value['VENDOR_INVITE_ID'].' 
				AND DATE_OF_REQUEST = "'.$value['DATE_CREATED'].'"
				AND REQUEST_TYPE = "'.$value['REQUEST_TYPE'].'"
				AND QUANTITY = "'.$value['QTY'].'"')->result_array();

			if($value['TRADE_VENDOR_TYPE'] == 1){
				$trade_vendor_type = 'OUTRIGHT';
			}else if($value['TRADE_VENDOR_TYPE'] == 2){
				$trade_vendor_type = 'STORE CONSIGNOR';
			}

			if(!$check_exists){
				$insert_logs = $this->db->query('INSERT INTO SMNTP_VENDOR_REQUEST_HISTORY (VENDOR_INVITE_ID, DATE_CREATED, DATE_OF_REQUEST, TRADE_VENDOR_TYPE, REQUEST_TYPE, ID_TYPE, QUANTITY) VALUES ('.$value['VENDOR_INVITE_ID'].', NOW(), "'.$value['DATE_CREATED'].'", "'.$trade_vendor_type.'", "'.$value['REQUEST_TYPE'].'", "PASS", "'.$value['QTY'].'")');	
			}


			$delete_vrpass_temp = "DELETE FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$value['VENDOR_INVITE_ID']."";
			$result_delete_vrpass_temp = $this->db->query($delete_vrpass_temp);

			$delete_pass_temp = "DELETE FROM SMNTP_VENDOR_PASS_TEMP WHERE VENDOR_REQUEST_ID = ".$value['VENDOR_REQUEST_ID']."";
			$result_delete_pass_temp = $this->db->query($delete_pass_temp);
			
    		$insert_blank = $this->db->query('INSERT INTO SMNTP_VENDOR_REQUESTS_TEMP (VENDOR_REQUEST_ID, VENDOR_INVITE_ID, APPROVAL_DATE, VENDOR_NAME, TOTAL_PASS_QTY, TOTAL_AMOUNT_DEDUCTION) VALUES ('.$value['VENDOR_REQUEST_ID'].', '.$value['VENDOR_INVITE_ID'].', " ", "'.$value['VENDOR_NAME'].'", " ", " ")');

    		$insert_pass_blank = $this->db->query('INSERT INTO SMNTP_VENDOR_PASS_TEMP (VENDOR_REQUEST_ID, VENDOR_CODE, EMAIL_ADD_OUTRIGHT, TRADE_VENDOR_TYPE, QTY, REQUEST_TYPE) VALUES ('.$value['VENDOR_REQUEST_ID'].', " ", " ", " ", " ", " ")');

    		$update_records = $this->db->query("
				UPDATE SMNTP_VENDOR_REQUESTS SVR 
				LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVR.VENDOR_REQUEST_ID = SVP.VENDOR_REQUEST_ID 
				SET PASS_EXTRACTED = 'Y', PASS_DATE_EXTRACTED = NOW()
				WHERE VENDOR_INVITE_ID = ".$value['VENDOR_INVITE_ID']."");

		}



		return $finalquery;
	}
	
}
?>