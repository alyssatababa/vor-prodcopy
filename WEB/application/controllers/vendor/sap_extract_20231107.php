<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Sap_extract extends CI_Controller
{
	function test(){
		$get_bdo = $this->rest_app->get('index.php/vendor/watson_extract/bdo/TVW001');
		echo $get_bdo[0]->FILE_PATH;
	}
	
	function paymentInfo($code,$type){

		$termName[5] = array("CONSO PD",99);
		$termName[4] = array("CONSO DATED",88);
		$termName[25] = array("CONSO DATED",88);
		$termName[22] = array("CONSO DATED",88);
		$termName[6] = array("CONSO DATED",88);
		$termName[7] = array("CONSO DATED CONSIGNOR",88);
		$termName[23] = array("CONSO DATED",88);

		if(!$termName[$code]) 
			return "";
		else
			return $termName[$code][$type];
	}

	function ownershipFLAG($code){
		$flag[1] = "WC158 1%";
		$flag[2] = "WC158 1%";
		$flag[3] = "WI158 1%";

		if(!$flag[$code])
			return "";
		else
			return $flag[$code];
	}

	function vatType($n){
		if($n == 1)
			return "INVAT-NCG";
		else
			return "INVAT-NONE";
	}

	function index(){
		
		$rs = $this->rest_app->get('index.php/vendor/sap_extract/data');
		
		//print_r($rs);
		//echo "here";
		//exit();
		$rs_count = count($rs);
		
		if($rs != 0){
			$filename = $rs[0]->FILE_NAME;
			$base_path = str_ireplace('/system/','/',BASEPATH);
			$first_line = array("BEG","1.0","D",$filename,date('mdy'));
			$last_line = array("EOF",$rs_count,"0","0");
			
			if(!file_exists( $base_path.'storage/sap_extract/' ) ){
				if(!mkdir($base_path.'storage/sap_extract/', 0777, true)){
					die('Failed to create directory');
				}
			}	
			
			$file = fopen($base_path.'storage/sap_extract/'.$filename, 'w');
			
			fputcsv($file, $first_line);
			
			for($i=0; $i<$rs_count; $i++){
				//For VTrad
				$vtrad = $this->rest_app->get('index.php/vendor/sap_extract/vtrad/'.$rs[$i]->H_SEGMENT1.'/'.$rs[$i]->S_OPERATING_UNIT_NAME);
				if($vtrad != NULL AND $vtrad != 0 AND $vtrad != null){
					$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION = str_replace("YYYY",$vtrad[0]->TPCDE,$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION);	
				}else{
					$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION = str_replace("YYYY","0000",$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION);	
				}
				
				//For ACCTS
				switch(substr($rs[$i]->H_SEGMENT1,1,1)){
					case 2:
						$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION = str_replace("XXXX","2002010",$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION);
						break;
					case 3:
						$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION = str_replace("XXXX","2002010",$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION);
						break;
					case 5:
						$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION = str_replace("XXXX","2002010",$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION);
						break;
					default:
						$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION = str_replace("XXXX","2001100",$rs[$i]->S_ACCTS_PAY_CODE_COMBINATION);
						break;
				}
				
				// For Ownership
				switch($rs[$i]->H_ORG_TYPE_LOOKUP_CODE){
					case 'CORPORATION':
						$rs[$i]->H_ORG_TYPE_LOOKUP_CODE = "Corporation";
						break;
						
					case 'PARTNERSHIP':
						$rs[$i]->H_ORG_TYPE_LOOKUP_CODE = "Partnership";
						break;
						
					case 'SOLE PROPRIETORSHIP':
						$rs[$i]->H_ORG_TYPE_LOOKUP_CODE = "Sole Proprietor";
						break;
						
					case 'FREELANCE':
						$rs[$i]->H_ORG_TYPE_LOOKUP_CODE = "Individual";
						break;
						
					default:
						break;
				}
				
				//H Terms Payment
				switch(strtoupper($rs[$i]->H_TERMS_NAME)){
					case '60 DAYS':
						$rs[$i]->H_TERMS_NAME = "PD 60 DAYS";
						$rs[$i]->S_TERMS_NAME = "PD 60 DAYS";
						break;
						
					case '30 DAYS':
						$rs[$i]->H_TERMS_NAME = "30 DAYS";
						$rs[$i]->S_TERMS_NAME = "30 DAYS";
						break;
						
					case '30 DAYS LESS 2 %':
						$rs[$i]->H_TERMS_NAME = "30 DAYS 2%";
						$rs[$i]->S_TERMS_NAME = "30 DAYS 2%";
						break;
						
					case 'COD 7 DAYS':
						$rs[$i]->H_TERMS_NAME = "COD 7 DAYS";
						$rs[$i]->S_TERMS_NAME = "COD 7 DAYS";
						break;
						
					case 'COD LESS 5%':
						$rs[$i]->H_TERMS_NAME = "COD 7 DAYS 5%";
						$rs[$i]->S_TERMS_NAME = "COD 7 DAYS 5%";
						break;
						
					case 'IMMEDIATE':
						$rs[$i]->H_TERMS_NAME = "IMMEDIATE";
						$rs[$i]->S_TERMS_NAME = "IMMEDIATE";
						break;
						
					case 'N/A':
						$rs[$i]->H_TERMS_NAME = "";
						$rs[$i]->S_TERMS_NAME = "";
						break;
						
					default:
						break;
				}
				
				//set PaymentTerms Name
				$rs[$i]->H_VAT_CODE = $this->vatType($rs[$i]->H_VAT_CODE);
				$rs[$i]->H_PAY_GROUP_LOOKUP_CODE = $this->paymentInfo($rs[$i]->H_PAY_GROUP_LOOKUP_CODE,0);
				$rs[$i]->H_PAYMENT_PRIORITY = $this->paymentInfo($rs[$i]->H_PAYMENT_PRIORITY,1);
				$rs[$i]->H_AWT_GROUP_NAME = $this->ownershipFLAG($rs[$i]->H_AWT_GROUP_NAME);
				
				$rs[$i]->S_VAT_CODE = $this->vatType($rs[$i]->S_VAT_CODE);
				$rs[$i]->S_PAY_GROUP_LOOKUP_CODE = $this->paymentInfo($rs[$i]->S_PAY_GROUP_LOOKUP_CODE,0);
				$rs[$i]->S_PAYMENT_PRIORITY = $this->paymentInfo($rs[$i]->S_PAYMENT_PRIORITY,1);
				$rs[$i]->S_AWT_GROUP_NAME = $this->ownershipFLAG($rs[$i]->S_AWT_GROUP_NAME);
				fputcsv($file, (array) $rs[$i]);
			}
			
			fputcsv($file, $last_line);
			
			fclose($file);
			
			chmod($base_path.'storage/sap_extract/'.$filename, 0777);
			
			echo 'Successfully extracted - '.$filename;
			
		}else{
			echo 'No data to extract - '.date('Y-m-d h:i:s');
		}
	}
}

?>