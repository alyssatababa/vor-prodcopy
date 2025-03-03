<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Vor_extract extends CI_Controller
{
	function index(){
		$rs = $this->rest_app->get('index.php/vendor/vor_extract/data');
		$rs = json_decode(json_encode($rs), true);
		//echo "<pre>";
		//print_r($rs);
		//exit();
		$rs_count = count($rs);
		
		//echo $rs_count;
		//exit();
		if($rs_count != 0){
			$filename = date('Ymd').".VP";
			$base_path = str_ireplace('/system/','/',BASEPATH);
			
			if(!file_exists( $base_path.'storage/vor_extract/' ) && !file_exists( $base_path.'storage/vp_extract/' )){
				if(!mkdir($base_path.'storage/vor_extract/', 0777, true) && !mkdir($base_path.'storage/vp_extract/', 0777, true)){
					die('Failed to create directory');
				}
			}	
			
			
			$file = fopen($base_path.'storage/vor_extract/'.$filename, 'w');
			$file2 = fopen($base_path.'storage/vp_extract/'.$filename, 'w');
			
			for($i=0; $i<$rs_count; $i++){
				if(isset($rs[$i])){
					$vendor_code = $rs[$i]['VENDOR_CODE'];
					$vendor_name_long = $rs[$i]['VENDOR_NAME_LONG'];
					$vendor_name_short = $rs[$i]['VENDOR_NAME_SHORT'];
					$address = $rs[$i]['ADDRESS'];
					$city = $rs[$i]['CITY'];
					$state = $rs[$i]['STATE'];
					$country = $rs[$i]['COUNTRY'];
					$zip_code = $rs[$i]['ZIP_CODE'];
					$tel_no = $rs[$i]['TEL_NO'];
					$ownership = $rs[$i]['OWNERSHIP'];
					$s_fax = $rs[$i]['S_FAX'];
					$terms_payment = $rs[$i]['TERMS_PAYMENT'];
					$authrep = $rs[$i]['AUTHREP'];
					$tax_id_no = $rs[$i]['TAX_ID_NO'];
					$owner_first_name = $rs[$i]['OWNER_FIRST_NAME'];
					$owner_middle_name = $rs[$i]['OWNER_MIDDLE_NAME'];
					$owner_last_name = $rs[$i]['OWNER_LAST_NAME'];
					$dept_code = $rs[$i]['DEPT_CODE'];
					$username = $rs[$i]['USERNAME'];
					$email = $rs[$i]['EMAIL'];
					$vendor_type = $rs[$i]['VENDOR_TYPE'];
					$contact_person = $rs[$i]['FIRST_NAME'];
					$position = $rs[$i]['POSITION'];
					$vp_email = $rs[$i]['VP_EMAIL'];
					$mobile_no = $rs[$i]['MOBILE_NO'];
					$role_code = $rs[$i]['MIDDLE_NAME']; //CONTACT PERSON (MIDDLE_NAME)
					$role_description = $rs[$i]['LAST_NAME']; //CONTACT PERSON (LAST_NAME)
					$test = '"'.$vendor_code.'","'.$vendor_name_long.'","'.$vendor_name_short.'","'.$address.'","'.$city.'","'.$state.'","'.$country.'","'.$zip_code.'","'.$tel_no.'","'.$ownership.'","","'.$s_fax.'","","'.$terms_payment.'","","'.$authrep.'","","","","","'.$tax_id_no.'","","","","","","","'.$owner_first_name.'","'.$owner_middle_name.'","'.$owner_last_name.'","'.$dept_code.'","","","","","'.$username.'","'.$email.'","","'.$vendor_type.'","'.$contact_person.'","'.$position.'","'.$vp_email.'","'.$mobile_no.'","'.$role_code.'","'.$role_description.'"'.PHP_EOL;
				//	echo $test."<br/><br/>";
					fwrite($file, $test);
					fwrite($file2, $test);
				}
			}
				//exit();
			fclose($file);
			fclose($file2);
			
			chmod($base_path.'storage/vor_extract/'.$filename, 0777);
			chmod($base_path.'storage/vp_extract/'.$filename, 0777);

			echo 'Successfully extracted - '.$filename;
		
		}else{
			echo 'No data to extract - '.date('Y-m-d h:i:s');
		}
	}
}

?>