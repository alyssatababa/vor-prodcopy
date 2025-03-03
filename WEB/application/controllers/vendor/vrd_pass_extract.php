<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class vrd_pass_extract extends CI_Controller
{
	function index(){
		$rs = $this->rest_app->get('index.php/vendor/vrd_pass_extract/data');
		echo "<pre>";
		//print_r($rs);
		//exit();
		$rs_count = count($rs);

		

		if($rs_count != 0){

				foreach ($rs as $key => $value) {
			
				
				$filename = "VRD_".$rs[$key]->VENDOR_CODE."_PASS_".date('YmdHi').".csv";
				$req_emailadd = $rs[$key]->REQUESTORS_EMAIL_ADD;
				$base_path = str_ireplace('/system/','/',BASEPATH);
				$first_line = array($req_emailadd);
				$total_row = 0;
				
				
				if(!file_exists( $base_path.'storage/vrd_pass_extract/' ) ){
					if(!mkdir($base_path.'storage/vrd_pass_extract/', 0777, true)){
						die('Failed to create directory');
					}
				}	
			 			
				$file = fopen($base_path.'storage/vrd_pass_extract/'.$filename, 'w');

				fputcsv($file, $first_line);
				
				for($i=0; $i<$rs_count; $i++){

					/*switch($rs[$i]->REQUEST_TYPE){
						case 'NEW':
							$rs[$key]->REQUEST_TYPE = "NEW";
							break;
							
						case 'RENEWAL':
							$rs[$key]->REQUEST_TYPE = "RNEW";
							break;
							
						case 'LOST':
							$rs[$key]->REQUEST_TYPE = "LST";
							break;
							
						case 'CHANGE IN DETAILS':
							$rs[$key]->REQUEST_TYPE = "CDT";
							break;

						case 'DAMAGED':
							$rs[$key]->REQUEST_TYPE = "DAM";
							break;

						case 'ADDITIONAL':
							$rs[$key]->REQUEST_TYPE = "ADD";
							break;
							
						default:
							break;
					}*/

					if($rs[$key]->VENDOR_CODE == $rs[$i]->VENDOR_CODE){
						$vendor_code = $rs[$i]->VENDOR_CODE;
						$vendor_name = $rs[$i]->VENDOR_NAME;

						$qty = $rs[$i]->QTY;
						$request_type = $rs[$i]->REQUEST_TYPE_CODE;
						
						
						$test = '"'.$vendor_code.'","'.$vendor_name.'","'.$qty.'","'.$request_type.'",'.PHP_EOL;

						$total_row++;
						fwrite($file, $test);
					}
					
				}
				$last_line = array($total_row);

				fputcsv($file, $last_line);
				
				fclose($file);
				
				chmod($base_path.'storage/vrd_pass_extract/'.$filename, 0777);
				
				echo 'Successfully extracted - '.$filename.'<br>';

			}
				
			}else{
				echo 'No data to extract - '.date('Y-m-d h:i:s');
			}
		
	}
}

?>