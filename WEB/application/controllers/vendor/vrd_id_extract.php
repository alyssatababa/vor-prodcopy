<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class vrd_id_extract extends CI_Controller
{
	function index(){
		$rs = $this->rest_app->get('index.php/vendor/vrd_id_extract/data');
		//sort($rs);
		echo "<pre>";
		//print_r($rs);
		//exit();
		$rs_count = count($rs);

		$next_vendor_code = '';
		$same_vendcode = TRUE;
		$counter = 0;
		$data_count = 0;
		$file = null;

		$output = []; 

			if($rs_count != 0){
				foreach ($rs as $key => $value) {
					$counter++;
					$data_count++;

					$same_vendcode = TRUE;
					$vendor_code = $rs[$key]->VENDOR_CODE;

					$filename = "VRD_".$rs[$key]->VENDOR_CODE."_ID_".date('YmdHi').".csv";
					$req_emailadd = $rs[$key]->REQUESTORS_EMAIL_ADD;
					$designation = $rs[$key]->DESIGNATION;
					$base_path = str_ireplace('/system/','/',BASEPATH);
					$first_line = array($req_emailadd);
					//$last_line = array($rs_count);

					if($key <= $rs_count) {
						if(isset($rs[$key+1])){
							$next_vendor_code = $rs[$key+1]->VENDOR_CODE;
						}else{
							$next_vendor_code = '';
						}
					} else {
						$next_vendor_code = '';
					}

					if($next_vendor_code != $vendor_code){
						$same_vendcode = FALSE;
					}	

				//	print_r(' vendor_code: ' . $vendor_code . ' next_vendor_code:' . $next_vendor_code . ' same_vendcode:' . $same_vendcode);

					/*switch($rs[$key]->REQUEST_TYPE){
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

					$first_name = $rs[$key]->FIRST_NAME;
					$middle_initial = $rs[$key]->MIDDLE_INITIAL;
					$last_name = $rs[$key]->LAST_NAME;
					$designation = $rs[$key]->DESIGNATION;
					$vendor_code = $rs[$key]->VENDOR_CODE;
					$vendor_invite_id = $rs[$key]->VENDOR_INVITE_ID;
					$date_created = $rs[$key]->DATE_CREATED;
					$approval_date = $rs[$key]->APPROVAL_DATE;
					$vendor_name = $rs[$key]->VENDOR_NAME;
					$request_type = $rs[$key]->REQUEST_TYPE_CODE;
					$test = '"'.$first_name.'","'.$middle_initial.'","'.$last_name.'","'.$designation.'","'.$vendor_code.'","'.$vendor_name.'","'.$request_type.'"'.PHP_EOL;

					array_push($output, $test); 

					//print_r($file ." : " . $test);


					if(!$same_vendcode) {
						$last_line = array($data_count);

						//$lineout = join mo yung string ng array dito tapos ang pang append nila is "new line" - wag mo ko pansinin dito

						$file = fopen($base_path.'storage/vrd_id_extract/'.$filename, 'w');
							fputcsv($file, $first_line);

						if(!file_exists( $base_path.'storage/vrd_id_extract/' ) ){
							if(!mkdir($base_path.'storage/vrd_id_extract/', 0777, true)){
								die('Failed to create directory');
							}
						}
						
						for($i=0;$i<count($output);$i++){ 
							fwrite($file, $output[$i]);
						}

						fputcsv($file, $last_line);
						fclose($file);
						chmod($base_path.'storage/vrd_id_extract/'.$filename, 0777);
						//echo '<br>';
						echo 'Successfully extracted - '.$filename.'<br>';

						$output = [];
						$data_count = 0;
					}
				}
			}else{
				echo 'No data to extract - '.date('Y-m-d h:i:s');
			}
		
	}
}

?>