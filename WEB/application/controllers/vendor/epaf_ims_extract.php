<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Epaf_ims_extract extends CI_Controller
{
	function index(){
		//$date_from = date('Y-m-d', strtotime('-7 days'));
		
		//if(date('N') == 2){
		//	$date_from = date('Y-m-d', strtotime('previous friday'));
		//}else if(date('N') == 5){
		//	$date_from = date('Y-m-d', strtotime('previous tuesday'));
		//}
		
		if(date('N') == 2){
			$date_from = date('Y-m-d', strtotime('previous friday')).' 08:00:00';
			$date_to = date('Y-m-d', strtotime('today')).' 07:59:59';
		}else if(date('N') == 5){
			$date_from = date('Y-m-d', strtotime('previous tuesday')).' 08:00:00';
			$date_to = date('Y-m-d', strtotime('today')).' 07:59:59';
		}
		
		//$date_from = '2023-05-30 08:00:00';
		//$date_to = '2023-06-02 07:59:59';
		$date_file = date('Ymdhi', strtotime($date_to));
		
		$rs = $this->rest_app->get('index.php/vendor/epaf_ims_extract/data');
		$email = $this->rest_app->get('index.php/vendor/epaf_ims_extract/email');
		
		//echo "<pre>";
		//print_r($rs);
		//exit();

		$rs_count = count($rs);
		$email_count = count($email);
		
		if($rs != 0){
			$epaf_array = array(9,10);
			$ims_array = array(11,12);

			$epaf_counter = 0;
			$ims_counter = 0;

			$base_path = str_ireplace('/system/','/',BASEPATH);	
			
			
			for($i=0; $i<$rs_count; $i++){
				$string = array($rs[$i]->VENDOR_CODE,$rs[$i]->EMAIL,$rs[$i]->Vendor_Type_Description);
				if(in_array($rs[$i]->SM_SYSTEM_ID,$epaf_array)){
					if($epaf_counter == 0){
			
						if(!file_exists( $base_path.'storage/epaf/' ) ){
							if(!mkdir($base_path.'storage/epaf/', 0777, true)){
								die('Failed to create directory');
							}
						}

						$file_epaf = fopen($base_path.'storage/epaf/Vendor_Email_ePAF_'.$date_file.'.csv', 'w');
						$epaf_counter = 1;
					}
					fputcsv($file_epaf, $string);
				}

				if(in_array($rs[$i]->SM_SYSTEM_ID,$ims_array)){
					if($ims_counter == 0){
			
						if(!file_exists( $base_path.'storage/ims/' ) ){
							if(!mkdir($base_path.'storage/ims/', 0777, true)){
								die('Failed to create directory');
							}
						}

						$file_ims = fopen($base_path.'storage/ims/Vendor_Email_'.$date_file.'.csv', 'w');
						$ims_counter = 1;
					}
					fputcsv($file_ims, $string);
				}
			}

			if($epaf_counter > 0){
				fclose($file_epaf);
				chmod($base_path.'storage/epaf/Vendor_Email_ePAF_'.$date_file.'.csv', 0777);
				
				// Email
				for($a=0; $a<$email_count; $a++){
					if($email[$a]->TAG_SYSTEM == 'EPAF'){
						$msg_footer = "This is an automated notification for List of vendors with update in Contact Information (EPAF) from ".$date_from." to ".$date_to.".<br/><br/><b><i>***This is an automated notification. Please do not reply.***</i></b><br/>";
						$email_data['subject'] = "VOR - List of vendors with update in Contact Information (EPAF) from ".$date_from." to ".$date_to;
						$email_data['content'] = "Please see attached file.<br/><br/>" .$msg_footer;
						$email_data['to'] = $email[$a]->EMAIL_ADDRESS;
						$attached_file= $base_path.'storage/epaf/Vendor_Email_ePAF_'.$date_file.'.csv';
						$email_data['attach'] = [$attached_file];
						$this->send_email_notification($email_data);
					}
				}

				echo 'Successfully extracted - '.$base_path.'storage/ims/Vendor_Email_ePAF_'.$date_file.'.csv<br/>';
			}

			if($ims_counter > 0){
				fclose($file_ims);
				chmod($base_path.'storage/ims/Vendor_Email_'.$date_file.'.csv', 0777);

				// Email
				for($a=0; $a<$email_count; $a++){
					if($email[$a]->TAG_SYSTEM == 'IMS'){
						$msg_footer = "This is an automated notification for List of vendors with update in Contact Information (IMS) from ".$date_from." to ".$date_to.".<br/><br/><b><i>***This is an automated notification. Please do not reply.***</i></b><br/>";
						$email_data['subject'] = "VOR - List of vendors with update in Contact Information (IMS) from ".$date_from." to ".$date_to;
						$email_data['content'] = "Please see attached file.<br/><br/>" .$msg_footer;
						$email_data['to'] = $email[$a]->EMAIL_ADDRESS;
						// $this->send_email_notification($email_data);
						//$attached_file= $_SERVER["DOCUMENT_ROOT"]."storage/ims/Vendor_Email_".date('Ymd').".csv";
						$attached_file= $base_path."storage/ims/Vendor_Email_".$date_file.".csv";
						$email_data['attach'] = [$attached_file];
						$this->send_email_notification($email_data);
					}
				}

				echo 'Successfully extracted - '.$base_path.'storage/ims/Vendor_Email_'.$date_file.'.csv';
			}
			
		}else{
			echo 'No data to extract - '.date('Y-m-d h:i:s');
		}
	}

	function send_email_notification($data)
	{
		$output = false;
		
		$this->load->helper('message_helper');
		$this->load->helper('file');
		$email_recipients = email_from();
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->clear(true);
		// Modified MSF - 20191108 (IJR-10617)
		//$this->email->from($email_recipients['from']);
		$this->email->from($email_recipients['from'], $email_recipients['sender_alias']);
		$this->email->to($data['to']);

		$data['bcc'] = $email_recipients['bcc'];

		$has_gmail = false;
		if (array_key_exists('cc', $data)) {
			$this->email->cc($data['cc']);			
			if (strpos($data['cc'], 'gmail.com') !== false)
				$has_gmail = true;
		}
		if (array_key_exists('bcc', $data)){
			$this->email->bcc($data['bcc']);
			if (strpos($data['bcc'], 'gmail.com') !== false)
				$has_gmail = true;
		}

		if (array_key_exists('attach', $data))
		{
			for ($i=0; $i < count($data['attach']); $i++)
			{
				$this->email->attach($data['attach'][$i]);
			}
		}

		if (array_key_exists('to', $data)) {
			if (strpos($data['to'], 'gmail.com') !== false)
				$has_gmail = true;

			// trim subject if gmail exists as a recepient
			if ($has_gmail==true) {
				$ellipsis = strlen($data['subject'])>58 ? "..." : "";
				$this->email->subject(substr($data['subject'],0,57).$ellipsis);
			} else {
				$this->email->subject($data['subject']);
			}
			$data['content'] = str_replace('<br />',"",$data['content']);
			$data['content'] = htmlspecialchars($data['content']);			
			$data['content'] = nl2br($data['content']);
			$data['content'] = htmlspecialchars_decode($data['content']);
			$this->email->message($data['content']);
			$output =$this->email->send();	
		}

		$data['attach'] = null;
		$data['attach'] = array();
		return $output;
	}
}

?>