<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class Watson_report extends CI_Model
{
	function get_bdo($vendor_code){
		$this->db->select('SVRD.FILE_PATH');
		$this->db->from('SMNTP_VENDOR SV');
		$this->db->join('SMNTP_VENDOR_REQUIRED_DOC SVRD', 'SV.VENDOR_ID = SVRD.VENDOR_ID AND SVRD.DOC_TYPE_ID = 45');
		$this->db->where('SV.VENDOR_CODE', $vendor_code);
		$this->db->or_where('SV.VENDOR_CODE_02', $vendor_code);

		$query = $this->db->get();
		$last_query = $this->db->last_query();

		return $query->result_array();
		//return $last_query;
	}
	
	function get_data(){
		ini_set('max_execution_time', 600);
		$file_name = '10819999'.date('mdY').'1.M03';
		//$vendor_codes = '"TVW001","990888","074228","014330","150772"';
		$vendor_codes = '';
		$arr_vc = (array) null;
		
		$update_records = array(
								'EXTRACTED' => 'Y',
								'DATE_EXTRACTED' => date('Y-m-d h:i:s')
							);
		
		$get_vendor_code = $this->db->query("SELECT VENDOR_CODE FROM SMNTP_WATSON_EXTRACT WHERE EXTRACTED = 'N'");
		$result_vendor_code = $get_vendor_code->result();
		$count_vendor_code = count($result_vendor_code);
		if($count_vendor_code > 0){
			for($a=0; $a<$count_vendor_code; $a++){
				if($a == 0){
					$vendor_codes .= '"'. $result_vendor_code[$a]->VENDOR_CODE . '"';	
				}else{
					$vendor_codes .= ',"'. $result_vendor_code[$a]->VENDOR_CODE . '"';	
				}
								
				$this->db->where('VENDOR_CODE', $result_vendor_code[$a]->VENDOR_CODE);
				$this->db->update('SMNTP_WATSON_EXTRACT', $update_records);
			}
			
			$res = $this->db->query('SELECT 
					CASE WHEN SV.VENDOR_CODE IN ('.$vendor_codes.') THEN CONCAT(UCASE(SV.VENDOR_NAME), " - " ,SV.VENDOR_CODE)
						ELSE CONCAT(UCASE(SV.VENDOR_NAME), " - " ,SV.VENDOR_CODE_02)
						END AS H_VENDOR_NAME,
					UCASE(SV.VENDOR_NAME) AS H_VENDOR_NAME_ALT,
					CASE WHEN SV.VENDOR_CODE IN ('.$vendor_codes.') THEN SV.VENDOR_CODE
						ELSE VENDOR_CODE_02
						END AS H_SEGMENT1, 
					CASE WHEN SV.VENDOR_CODE IN ('.$vendor_codes.') THEN 
							CASE SV.TRADE_VENDOR_TYPE
								WHEN 1 THEN "TRADE OUTRIGHT" 
								WHEN 2 THEN "TRADE STORE CONSIGNOR" 
								ELSE "NON TRADE GENERAL" 
							END
						ELSE
							CASE SV.TRADE_VENDOR_TYPE
								WHEN 2 THEN "TRADE OUTRIGHT" 
								WHEN 1 THEN "TRADE STORE CONSIGNOR" 
							END 
						END AS H_VENDOR_TYPE_LOOKUP_CODE, UCASE(STP.TERMS_PAYMENT_NAME) AS H_TERMS_NAME, "" AS H_ALWAYS_TAKE_DISC_FLAG, "" AS H_PAY_DATE_BASIS_LOOKUP_CODE,
						"" AS H_PAY_GROUP_LOOKUP_CODE, "" AS H_PAYMENT_PRIORITY, "" AS H_INVOICE_CURRENCY_CODE, "" AS H_PAYMENT_CURRENCY_CODE, "" AS H_DISTRIBUTION_SET_NAME, 
						CONCAT(SV.TAX_ID_NO, CASE WHEN SV.TAX_CLASSIFICATION = 1 THEN "V" ELSE "N" END) AS H_NUM_1099, UCASE(SO.OWNERSHIP_NAME) AS H_ORG_TYPE_LOOKUP_CODE, 
						"" AS H_VAT_CODE, "" AS H_PAYMENT_METHOD_LOOKUP_CODE, "" AS H_AMOUNT_INCLUDES_TAX_FLAG, 
						CONCAT(SV.TAX_ID_NO, CASE WHEN SV.TAX_CLASSIFICATION = 1 THEN "V" ELSE "N" END) AS H_VAT_REGISTRATION_NUM, 
						"" AS H_ALLOW_AWT_FLAG,  "" AS H_AWT_GROUP_NAME,  "" AS H_ATTRIBUTE_CATEGORY,  "" AS H_ATTRIBUTE1,  "" AS H_ALLOW_TAX_APPLICABILITY,  "" AS H_BANK_NAME,  "" AS H_BRANCH_NAME,  "" AS H_ACCOUNT_NUMBER,  "" AS H_BANK_ATTRIBUTE,  "HEAD OFFICE" AS S_VENDOR_SITE_CODE,  "" AS S_PURCHASING_SITE_FLAG,  "" AS S_PAY_SITE_FLAG, 
					UCASE(SVA.ADDRESS_LINE) AS S_ADDRESS_LINE1, 
					UCASE(SCity.CITY_NAME) AS S_CITY, "PH" AS S_COUNTRY, SVA.ZIP_CODE AS S_AREA_CODE, 
					CD.TEL_NO AS S_PHONE, 
					CD.FAX_NO AS S_FAX, 
					"" AS S_PAYMENT_METHOD_LOOKUP_CODE, "" AS S_TERMS_DATE_BASIS, "" AS S_VAT_CODE, "" AS S_DISTRIBUTION_SET_NAME, "" AS S_ACCTS_PAY_CODE_COMBINATION, 
					"" AS S_PREPAY_CODE_COMBINATION, "" AS S_PAY_GROUP_LOOKUP_CODE, "" AS S_PAYMENT_PRIORITY, UCASE(STP.TERMS_PAYMENT_NAME) AS S_TERMS_NAME, 
					"" AS S_INVOICE_CURRENCY_CODE, "" AS S_PAYMENT_CURRENCY_CODE, "" AS S_AMOUNT_INCLUDES_TAX_FLAG, "" AS S_ATTRIBUTE_CATEGORY, "" AS S_ATTRIBUTE1, "" AS S_ATTRIBUTE2, 
					"" AS S_ATTRIBUTE15, CONCAT(SV.TAX_ID_NO, CASE WHEN SV.TAX_CLASSIFICATION = 1 THEN "V" ELSE "N" END) AS S_VAT_REGISTRATION_NUM, "081" AS S_OPERATING_UNIT_NAME, "" AS S_ALLOW_AWT_FLAG, "" AS S_AWT_GROUP_NAME, 
					CD.EMAIL AS S_EMAIL_ADDRESS, "" AS S_PRIMARY_PAY_SITE_FLAG, "HEAD OFFICE" AS C_VENDOR_SITE_CODE, "081" AS C_OPERATING_UNIT_NAME, 
					UCASE(SVO.FIRST_NAME) AS C_FIRST_NAME, UCASE(SVO.MIDDLE_NAME) AS C_MIDDLE_NAME, UCASE(SVO.LAST_NAME) AS C_LAST_NAME, "" AS C_PREFIX, UCASE(SVO.POSITION) AS C_TITLE, "" AS C_PHONE,
					CASE WHEN AVR.MIDDLE_NAME = "" THEN UCASE(CONCAT(AVR.FIRST_NAME, " ", AVR.LAST_NAME))
						ELSE UCASE(CONCAT(AVR.FIRST_NAME, " ", AVR.MIDDLE_NAME, ", ", AVR.LAST_NAME))
						END AS C_CONTACT_NAME_ALT, 
					CD.TEL_NO AS C_ALT_PHONE, 
					CD.FAX_NO AS C_FAX, "'.$file_name.'" AS FILE_NAME
					FROM SMNTP_VENDOR SV
					JOIN SMNTP_VENDOR_TYPE SVT ON SV.VENDOR_TYPE = SVT.VENDOR_TYPE_ID
					JOIN SMNTP_VENDOR_ADDRESSES SVA ON SV.VENDOR_ID = SVA.VENDOR_ID AND SVA.ADDRESS_TYPE = "1" AND `PRIMARY` = 1
					JOIN SMNTP_CITY SCity ON SVA.BRGY_MUNICIPALITY_ID = SCity.CITY_ID
					JOIN SMNTP_STATE_PROVINCE SSP ON SVA.STATE_PROVINCE_ID = SSP.STATE_PROV_ID
					JOIN SMNTP_COUNTRY SCountry ON SVA.COUNTRY_ID = SCountry.COUNTRY_ID
					JOIN(SELECT VENDOR_ID, REPLACE(GROUP_CONCAT(TEL_NO),",","") AS TEL_NO, SUM(TEL_NO_EXTENSION_LOCAL_NUMBER)AS TEL_NO_EXTENSION_LOCAL_NUMBER, REPLACE(GROUP_CONCAT(FAX_NO),",","") AS FAX_NO,SUM(FAX_NO_EXTENSION_LOCAL_NUMBER) AS FAX_NO_EXTENSION_LOCAL_NUMBER,SUM(MOBILE_NO) AS MOBILE_NO, REPLACE(GROUP_CONCAT(EMAIL),",","") AS EMAIL FROM (
					SELECT
					SVCDTHREE.VENDOR_ID,
					CASE WHEN SVCDTHREE.CONTACT_DETAIL_TYPE = 1 THEN CONCAT(SVCDTHREE.AREA_CODE, " ",SVCDTHREE.CONTACT_DETAIL) ELSE "" END AS TEL_NO, 
					CASE WHEN SVCDTHREE.CONTACT_DETAIL_TYPE = 1 THEN SVCDTHREE.EXTENSION_LOCAL_NUMBER ELSE "" END AS TEL_NO_EXTENSION_LOCAL_NUMBER, 
					CASE WHEN SVCDTHREE.CONTACT_DETAIL_TYPE = 2 THEN CONCAT(SVCDTHREE.AREA_CODE, " ", SVCDTHREE.CONTACT_DETAIL) ELSE "" END AS FAX_NO,
					CASE WHEN SVCDTHREE.CONTACT_DETAIL_TYPE = 2 THEN SVCDTHREE.EXTENSION_LOCAL_NUMBER ELSE "" END AS FAX_NO_EXTENSION_LOCAL_NUMBER, 
					CASE WHEN SVCDTHREE.CONTACT_DETAIL_TYPE = 3 THEN SVCDTHREE.CONTACT_DETAIL ELSE "" END AS MOBILE_NO,
					CASE WHEN SVCDTHREE.CONTACT_DETAIL_TYPE = 4 THEN SVCDTHREE.CONTACT_DETAIL ELSE "" END AS EMAIL
					FROM 
						(SELECT VENDOR_ID, CONTACT_DETAIL_TYPE, MIN(VENDOR_CONTACT_DETAIL_ID) AS VENDOR_CONTACT_DETAIL_ID FROM SMNTP_VENDOR_CONTACT_DETAILS GROUP BY VENDOR_ID,CONTACT_DETAIL_TYPE) SAC
						JOIN SMNTP_VENDOR_CONTACT_DETAILS SVCDTHREE ON SAC.VENDOR_CONTACT_DETAIL_ID = SVCDTHREE.VENDOR_CONTACT_DETAIL_ID)a GROUP BY VENDOR_ID) 
					CD ON SV.VENDOR_ID = CD.VENDOR_ID
					JOIN (SELECT SVRONE.* FROM 
						(SELECT VENDOR_ID, MIN(VENDOR_REP_ID) AS VENDOR_REP_ID FROM SMNTP_VENDOR_REP GROUP BY VENDOR_ID) SAB
						JOIN SMNTP_VENDOR_REP SVRONE ON SAB.VENDOR_REP_ID = SVRONE.VENDOR_REP_ID
					) AVR ON SV.VENDOR_ID = AVR.VENDOR_ID
					JOIN (
						SELECT SVOONE.* FROM 
						(SELECT VENDOR_ID, MIN(VENDOR_OWNER_ID) AS VENDOR_OWNER_ID FROM SMNTP_VENDOR_OWNERS GROUP BY VENDOR_ID) SAA
						JOIN SMNTP_VENDOR_OWNERS SVOONE ON SAA.VENDOR_OWNER_ID = SVOONE.VENDOR_OWNER_ID
					) SVO ON SV.VENDOR_ID = SVO.VENDOR_ID
					JOIN SMNTP_OWNERSHIP SO ON SV.OWNERSHIP_TYPE = SO.OWNERSHIP_ID
					JOIN SMNTP_VENDOR_STATUS SS ON SV.VENDOR_INVITE_ID = SS.VENDOR_INVITE_ID
					JOIN SMNTP_TERMS_PAYMENT STP ON SS.TERMSPAYMENT = STP.TERMS_PAYMENT_ID
					WHERE SV.VENDOR_CODE IN ('.$vendor_codes.') OR SV.VENDOR_CODE_02 IN ('.$vendor_codes.')');
			return $res->result();
		}else{
			return 0;
		}
	}
}
?>