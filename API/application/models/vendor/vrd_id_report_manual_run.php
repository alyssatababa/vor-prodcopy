<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class vrd_id_report extends CI_Model
{
	
	function get_data(){
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
											 WHERE SVR.ID_EXTRACTED != 'Y'
											 AND SVS.STATUS_ID = 19 
											 AND SVI.REGISTRATION_TYPE IN (1,2,3,5) 
											 AND SVS.DATE_UPDATED BETWEEN DATE_SUB(NOW(), INTERVAL 4 DAY) AND NOW()");


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

			
			$query = 'SELECT CASE WHEN SVP.VENDOR_CODE IN ('.$vendor_codes.') THEN SVP.VENDOR_CODE
							END AS VENDOR_CODE,
						SVR.VENDOR_REQUEST_ID,
						SVR.VENDOR_INVITE_ID,
						SVR.DATE_CREATED,
						SVR.APPROVAL_DATE,
						B.TRADE_VENDOR_TYPE,
						A.NEW_TRADE_VENDOR_TYPE,
						CASE SVP.TRADE_VENDOR_TYPE WHEN 1 THEN EMAIL_ADD_OUTRIGHT 
							WHEN 2 THEN EMAIL_ADD_SC  END AS REQUESTORS_EMAIL_ADD,
						UCASE(B.FIRST_NAME) AS FIRST_NAME,
						UCASE(B.MIDDLE_INITIAL) AS MIDDLE_INITIAL, 
						UCASE(B.LAST_NAME) AS LAST_NAME,
						DESIGNATION,
						SVR.VENDOR_NAME,
						B.REQUEST_TYPE,
						SRT.REQUEST_TYPE_CODE
						FROM SMNTP_VENDOR_REQUESTS SVR
						LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVP.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						LEFT JOIN (
							SELECT CASE TRADE_VENDOR_TYPE WHEN "OUTRIGHT" THEN 1 WHEN "STORE CONSIGNOR" THEN 2 WHEN "BOTH" THEN 3 END AS TRADE_VENDOR_TYPE,
							VENDOR_REQUEST_ID,
							FIRST_NAME,
							MIDDLE_INITIAL,
							LAST_NAME,
							DESIGNATION,
							REQUEST_TYPE
						FROM SMNTP_VENDOR_ID_REQUESTS) B ON B.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						LEFT JOIN SMNTP_REQUEST_TYPE SRT ON B.REQUEST_TYPE = SRT.REQUEST_TYPE_NAME
						LEFT JOIN (SELECT CASE WHEN SVI.PREV_REGISTRATION_TYPE = 4 OR SVI.REGISTRATION_TYPE = 4 THEN 
									CASE SV.TRADE_VENDOR_TYPE WHEN 1 THEN 2 ELSE 1 END
								ELSE 
									SV.TRADE_VENDOR_TYPE
								END AS NEW_TRADE_VENDOR_TYPE,
								SVI.VENDOR_INVITE_ID
							FROM SMNTP_VENDOR SV 
							LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
							WHERE SV.VENDOR_CODE IN ('.$vendor_codes.')) A ON A.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
						WHERE B.FIRST_NAME!= " " 
						AND B.LAST_NAME != " "
						AND SVP.VENDOR_CODE != " "
						AND A.NEW_TRADE_VENDOR_TYPE = B.TRADE_VENDOR_TYPE
						OR B.TRADE_VENDOR_TYPE = 3
						AND SVP.VENDOR_CODE IN ('.$vendor_codes.')';
			//return $query
			
			$res = $this->db->query($query);
			$res = $res->result_array();
		}else{
			$res = [];
		}



		// registration type = 2, 3
		 $get_vendor_code_02 = $this->db->query("SELECT SV.VENDOR_CODE_02 FROM SMNTP_VENDOR_INVITE SVI 
											 JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID
											 JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID
											 LEFT JOIN SMNTP_VENDOR_REQUESTS SVR ON SVI.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
											 WHERE SVR.ID_EXTRACTED != 'Y'
											 AND SVS.STATUS_ID = 19 
											 AND SV.VENDOR_CODE_02 IS NOT NULL
											 AND SVI.REGISTRATION_TYPE IN (2,3,5) 
											 AND SVS.DATE_UPDATED BETWEEN DATE_SUB(NOW(), INTERVAL 4 DAY) AND NOW()");

		$result_vendor_code_02 = $get_vendor_code_02->result();
		$count_vendor_code_02 = count($result_vendor_code_02);
		if($count_vendor_code_02 > 0){
			for($c=0; $c<$count_vendor_code_02; $c++){
				if($vendor_codes_02 != ''){
					$vendor_codes_02 .= ',"'. $result_vendor_code_02[$c]->VENDOR_CODE_02 . '"';	
				}else{
					$vendor_codes_02 .= '"'. $result_vendor_code_02[$c]->VENDOR_CODE_02 . '"';	
				}
			}

			$query2 = 'SELECT CASE WHEN SVP.VENDOR_CODE_02 IN ('.$vendor_codes_02.') THEN SVP.VENDOR_CODE_02
							END AS VENDOR_CODE,
						SVR.VENDOR_REQUEST_ID,
						SVR.VENDOR_INVITE_ID,
						SVR.DATE_CREATED,
						SVR.APPROVAL_DATE,
						B.TRADE_VENDOR_TYPE,
						A.NEW_TRADE_VENDOR_TYPE,
						CASE SVP.TRADE_VENDOR_TYPE WHEN 1 THEN EMAIL_ADD_OUTRIGHT 
							WHEN 2 THEN EMAIL_ADD_SC  END AS REQUESTORS_EMAIL_ADD,
						UCASE(B.FIRST_NAME) AS FIRST_NAME,
						UCASE(B.MIDDLE_INITIAL) AS MIDDLE_INITIAL, 
						UCASE(B.LAST_NAME) AS LAST_NAME,
						DESIGNATION,
						SVR.VENDOR_NAME,
						B.REQUEST_TYPE,
						SRT.REQUEST_TYPE_CODE
						FROM SMNTP_VENDOR_REQUESTS SVR
						LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVP.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						LEFT JOIN (
							SELECT CASE TRADE_VENDOR_TYPE WHEN "OUTRIGHT" THEN 1 WHEN "STORE CONSIGNOR" THEN 2 WHEN "BOTH" THEN 3 END AS TRADE_VENDOR_TYPE,
							VENDOR_REQUEST_ID,
							FIRST_NAME,
							MIDDLE_INITIAL,
							LAST_NAME,
							DESIGNATION,
							REQUEST_TYPE
						FROM SMNTP_VENDOR_ID_REQUESTS) B ON B.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						LEFT JOIN SMNTP_REQUEST_TYPE SRT ON B.REQUEST_TYPE = SRT.REQUEST_TYPE_NAME
						LEFT JOIN (SELECT CASE WHEN SVI.PREV_REGISTRATION_TYPE = 4 OR SVI.REGISTRATION_TYPE = 4 THEN 
									CASE SV.TRADE_VENDOR_TYPE WHEN 1 THEN 2 ELSE 1 END
								ELSE 
									SV.TRADE_VENDOR_TYPE
								END AS NEW_TRADE_VENDOR_TYPE,
								SVI.VENDOR_INVITE_ID
							FROM SMNTP_VENDOR SV 
							LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
							WHERE SV.VENDOR_CODE_02 IN ('.$vendor_codes_02.')) A ON A.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
						WHERE B.FIRST_NAME!= " " 
						AND B.LAST_NAME != " "
						AND SVP.VENDOR_CODE_02 != " "
						AND A.NEW_TRADE_VENDOR_TYPE != B.TRADE_VENDOR_TYPE
						OR B.TRADE_VENDOR_TYPE = 3
						AND SVP.VENDOR_CODE_02 IN ('.$vendor_codes_02.')';
			//return $query2;
			$res2 = $this->db->query($query2);
			$res2 = $res2->result_array();
		}else{
		$res2 = [];
	}

		// registration type = 4
		 $get_vendor_code_v4 = $this->db->query("SELECT SV.VENDOR_CODE_02 FROM SMNTP_VENDOR_INVITE SVI 
											 JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID
											 JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID
											 LEFT JOIN SMNTP_VENDOR_REQUESTS SVR ON SVI.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
											 WHERE SVR.ID_EXTRACTED != 'Y'
											 AND SVS.STATUS_ID = 19 
											 AND SVI.REGISTRATION_TYPE IN (4) 
											AND SVS.DATE_UPDATED BETWEEN DATE_SUB(NOW(), INTERVAL 4 DAY) AND NOW()");

		$result_vendor_code_v4 = $get_vendor_code_v4->result();
		$count_vendor_code_v4 = count($result_vendor_code_v4);
		if($count_vendor_code_v4 > 0){
			for($d=0; $d<$count_vendor_code_v4; $d++){
				if($vendor_codes_v4 != ''){
					$vendor_codes_v4 .= ',"'. $result_vendor_code_v4[$d]->VENDOR_CODE_02 . '"';	
				}else{
					$vendor_codes_v4 .= '"'. $result_vendor_code_v4[$d]->VENDOR_CODE_02 . '"';	
				}
			}

		$query3 = 'SELECT CASE WHEN SVP.VENDOR_CODE_02 IN ('.$vendor_codes_v4.') THEN SVP.VENDOR_CODE_02
							END AS VENDOR_CODE,
						SVR.VENDOR_REQUEST_ID,
						SVR.VENDOR_INVITE_ID,
						SVR.DATE_CREATED,
						SVR.APPROVAL_DATE,
						B.TRADE_VENDOR_TYPE,
						A.NEW_TRADE_VENDOR_TYPE,
						CASE SVP.TRADE_VENDOR_TYPE WHEN 1 THEN EMAIL_ADD_OUTRIGHT 
							WHEN 2 THEN EMAIL_ADD_SC END AS REQUESTORS_EMAIL_ADD,
						UCASE(B.FIRST_NAME) AS FIRST_NAME,
						UCASE(B.MIDDLE_INITIAL) AS MIDDLE_INITIAL, 
						UCASE(B.LAST_NAME) AS LAST_NAME,
						DESIGNATION,
						SVR.VENDOR_NAME,
						B.REQUEST_TYPE,
						SRT.REQUEST_TYPE_CODE
						FROM SMNTP_VENDOR_REQUESTS SVR
						LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVP.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						LEFT JOIN (
							SELECT CASE TRADE_VENDOR_TYPE WHEN "OUTRIGHT" THEN 1 WHEN "STORE CONSIGNOR" THEN 2 WHEN "BOTH" THEN 3 END AS TRADE_VENDOR_TYPE,
							VENDOR_REQUEST_ID,
							FIRST_NAME,
							MIDDLE_INITIAL,
							LAST_NAME,
							DESIGNATION,
							REQUEST_TYPE
						FROM SMNTP_VENDOR_ID_REQUESTS) B ON B.VENDOR_REQUEST_ID = SVR.VENDOR_REQUEST_ID
						LEFT JOIN SMNTP_REQUEST_TYPE SRT ON B.REQUEST_TYPE = SRT.REQUEST_TYPE_NAME
						LEFT JOIN (SELECT CASE WHEN SVI.PREV_REGISTRATION_TYPE = 4 OR SVI.REGISTRATION_TYPE = 4 THEN 
									CASE SV.TRADE_VENDOR_TYPE WHEN 1 THEN 2 ELSE 1 END
								ELSE 
									SV.TRADE_VENDOR_TYPE
								END AS NEW_TRADE_VENDOR_TYPE,
								SVI.VENDOR_INVITE_ID
							FROM SMNTP_VENDOR SV 
							LEFT JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID
							WHERE SV.VENDOR_CODE_02 IN ('.$vendor_codes_v4.')) A ON A.VENDOR_INVITE_ID = SVR.VENDOR_INVITE_ID
						WHERE B.FIRST_NAME!= " " 
						AND B.LAST_NAME != " "
						AND SVP.VENDOR_CODE_02 != " "
						AND A.NEW_TRADE_VENDOR_TYPE != B.TRADE_VENDOR_TYPE
						OR B.TRADE_VENDOR_TYPE = 3
						AND SVP.VENDOR_CODE_02 IN ('.$vendor_codes_v4.')';
		
			//return $query3;
			$res3 = $this->db->query($query3);
			$res3 =  $res3->result_array();
		}else{
			$res3 = [];
	}

		$finalquery = array_merge($res, $res2, $res3);

		foreach ($finalquery as $value) {

			$check_exists = $this->db->query('SELECT * FROM SMNTP_VENDOR_REQUEST_HISTORY 
				WHERE VENDOR_INVITE_ID = '.$value['VENDOR_INVITE_ID'].' 
				AND FIRST_NAME = "'.$value['FIRST_NAME'].'"
				AND MIDDLE_INITIAL = "'.$value['MIDDLE_INITIAL'].'"
				AND LAST_NAME = "'.$value['LAST_NAME'].'"
				AND DESIGNATION = "'.$value['DESIGNATION'].'"
				AND DATE_OF_REQUEST = "'.$value['DATE_CREATED'].'"')->result_array();



			if($value['TRADE_VENDOR_TYPE'] == 1){
				$trade_vendor_type = 'OUTRIGHT';
			}else if($value['TRADE_VENDOR_TYPE'] == 2){
				$trade_vendor_type = 'STORE CONSIGNOR';
			}else if($value['TRADE_VENDOR_TYPE'] == 3){
				$trade_vendor_type = 'BOTH';
			}

			if(!$check_exists){
				$insert_logs = $this->db->query('INSERT INTO SMNTP_VENDOR_REQUEST_HISTORY (VENDOR_INVITE_ID, DATE_CREATED, DATE_OF_REQUEST, TRADE_VENDOR_TYPE, REQUEST_TYPE, ID_TYPE, LAST_NAME, MIDDLE_INITIAL, FIRST_NAME, DESIGNATION, QUANTITY) VALUES ('.$value['VENDOR_INVITE_ID'].', NOW(), "'.$value['DATE_CREATED'].'", "'.$trade_vendor_type.'", "'.$value['REQUEST_TYPE'].'", "ID", "'.$value['LAST_NAME'].'", "'.$value['MIDDLE_INITIAL'].'", "'.$value['FIRST_NAME'].'", "'.$value['DESIGNATION'].'", "1")');

			}

			$delete_vrid_temp = "DELETE FROM SMNTP_VENDOR_ID_REQUESTS_TEMP WHERE VENDOR_REQUEST_ID = ".$value['VENDOR_REQUEST_ID']."";
	    	$result_delete_vrid_temp = $this->db->query($delete_vrid_temp);

			$insert_blank = $this->db->query('INSERT INTO SMNTP_VENDOR_ID_REQUESTS_TEMP (VENDOR_REQUEST_ID, TRADE_VENDOR_TYPE, FIRST_NAME, MIDDLE_INITIAL, LAST_NAME, DESIGNATION, REQUEST_TYPE)  VALUES ('.$value['VENDOR_REQUEST_ID'].', " ", " ", " ", " ", " ", " ")');

			$update_records = $this->db->query("
				UPDATE SMNTP_VENDOR_REQUESTS SVR 
				LEFT JOIN SMNTP_VENDOR_PASS SVP ON SVR.VENDOR_REQUEST_ID = SVP.VENDOR_REQUEST_ID 
				SET ID_EXTRACTED = 'Y', ID_DATE_EXTRACTED = NOW()
				WHERE VENDOR_INVITE_ID = ".$value['VENDOR_INVITE_ID']."");

		}
		return $finalquery;



	}
}
?>