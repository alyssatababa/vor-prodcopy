<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Watson_extract_test extends CI_Controller
{
	function test(){
		$get_bdo = $this->rest_app->get('index.php/vendor/watson_extract_test/bdo/TVW001');
		echo $get_bdo[0]->FILE_PATH;
	}
	
	function index(){
		$rs = $this->rest_app->get('index.php/vendor/watson_extract_test/data');
		//print_r($rs);
		//exit();
		$rs_count = count($rs);
		
		if($rs != 0){
			$filename = $rs[0]->FILE_NAME;     
			$base_path = str_ireplace('/system/','/',BASEPATH);
			$first_line = array("BEG","1","D",$filename,date('mdy'));
			$last_line = array("EOF",$rs_count,"0","0");
			
			if(!file_exists( $base_path.'storage/watson_extract/' ) ){
				if(!mkdir($base_path.'storage/watson_extract/', 0777, true)){
					die('Failed to create directory');
				}
			}	
			
			$file = fopen($base_path.'storage/watson_extract/'.$filename, 'w');
			
			fputcsv($file, $first_line);
			
			for($i=0; $i<$rs_count; $i++){
				//print_r($rs[$i]);
				//echo "<br/>";
				fputcsv($file, (array)$rs[$i]);
				
				//$get_bdo = $this->rest_app->get('index.php/vendor/watson_extract/bdo/'.$rs[$i]->H_SEGMENT1);
				////print_r($get_bdo);
				////echo "<br/>";
				//$file_name = explode("/",$get_bdo[0]->FILE_PATH);
				//$file = explode(".",$file_name[1]);
				//$file_extension = $file[1];
				////copy($base_path.$get_bdo[0]->FILE_PATH,$base_path.'storage/watson_extract/'.date('Y').'/'.date('m').'/'.date('d').'/'.$file_name[1]);
				//
				//copy($base_path.$get_bdo[0]->FILE_PATH,$base_path.'storage/watson_extract/'.$rs[$i]->H_SEGMENT1.'.'.$file_extension);
			}
			
			fputcsv($file, $last_line);
			
			fclose($file); 
			
			
			
			for($i=0; $i<$rs_count; $i++){
				$get_bdo = $this->rest_app->get('index.php/vendor/watson_extract_test/bdo/'.$rs[$i]->H_SEGMENT1);
				
				$file_name = explode("/",$get_bdo[0]->FILE_PATH);
				$file = explode(".",$file_name[1]);
				$file_extension = $file[1];
				//copy($base_path.$get_bdo[0]->FILE_PATH,$base_path.'storage/watson_extract/'.date('Y').'/'.date('m').'/'.date('d').'/'.$file_name[1]);
				
				copy($base_path.$get_bdo[0]->FILE_PATH,$base_path.'storage/watson_extract/'.$rs[$i]->H_SEGMENT1.'.'.$file_extension);
			}
			
			echo 'Successfully extracted - '.$filename;
			
		}else{
			echo 'No data to extract - '.date('Y-m-d h:i:s');
		}
	}
}

?>