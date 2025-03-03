<?php
include("db_connect.php");
include("smtp_config.php");

$db = new DB;

$vendor_count = 0;
$same_vendor = 0;
$previous_date_updated = '';
$record_set = $db->query('SELECT SVI.VENDOR_INVITE_ID, SV.VENDOR_ID, DATE(SVI.`DATE_CREATED`) AS DATE_CREATED, SVSL.`DATE_UPDATED`, SVSL.STATUS_ID, SV.VENDOR_CODE, SS.STATUS_NAME FROM SMNTP_VENDOR_INVITE SVI
							JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID
							JOIN SMNTP_VENDOR_STATUS_LOGS SVSL ON SVI.VENDOR_INVITE_ID = SVSL.`VENDOR_INVITE_ID`
							JOIN SMNTP_STATUS SS ON SVSL.STATUS_ID = SS.STATUS_ID
							-- WHERE SVSL.`STATUS_ID` = 19 AND (SVI.`DATE_CREATED` > "2022-11-11 00:00:01" OR SVSL.`DATE_UPDATED` > "2022-11-11 00:00:01")
							WHERE SVSL.`STATUS_ID` = 19 AND (SVI.`DATE_CREATED` > "2023-05-10 00:00:01" OR SVSL.`DATE_UPDATED` > "2023-05-10 00:00:01")
							AND (SVI.`DATE_CREATED` < "2023-05-23 00:00:01" OR SVSL.`DATE_UPDATED` < "2023-05-23 00:00:01")
							-- and SVI.VENDOR_INVITE_ID = 20536
							ORDER BY SVI.`VENDOR_INVITE_ID`'
						)->fetchAll();
$total_records = count($record_set);

for($a=0; $a< $total_records; $a++){
	$vendor_invite_id = $record_set[$a]['VENDOR_INVITE_ID'];
	$vendor_id = $record_set[$a]['VENDOR_ID'];
	$vendor_code = $record_set[$a]['VENDOR_CODE'];
	$date_created = $record_set[$a]['DATE_CREATED'];
	$date_updated = $record_set[$a]['DATE_UPDATED'];
	$current_status = $record_set[$a]['STATUS_NAME'];
	
	if($date_created >= '2022-11-11' ){
		$previous_status = $db->query("SELECT SS.STATUS_NAME, SVSL.STATUS_ID FROM SMNTP_VENDOR_STATUS_LOGS SVSL
									   JOIN SMNTP_STATUS SS ON SVSL.STATUS_ID = SS.STATUS_ID
									   WHERE SVSL.VENDOR_INVITE_ID = ".$vendor_invite_id." AND SVSL.DATE_UPDATED <= '".$date_updated."' ORDER BY SVSL.DATE_UPDATED DESC LIMIT 1,1")->fetchAll();
		if($previous_status[0]['STATUS_ID'] != 254){
			
			$get_vendor_sm_systems = $db->query("SELECT DISTINCT EMAIL FROM SMNTP_VENDOR_SM_SYSTEMS WHERE SM_SYSTEM_ID IN (9,10) AND vendor_invite_id = ".$vendor_invite_id)->fetchAll();
			if(count($get_vendor_sm_systems) > 0){
				//echo 'Vendor Invite ID: '.$vendor_invite_id.'<br/>';
				//echo 'Date Created: '.$date_created.'<br/>';
				//echo 'Date Updated: '.$date_updated.'<br/>';
				//echo 'Current Status: '.$current_status.'<br/>';
				//echo 'Previous Status: '.$previous_status[0]['STATUS_NAME'].'<br/>';
				//echo 'Email(s): ';
				//foreach($get_vendor_sm_systems as $vendor_email){
				//	echo $vendor_email['EMAIL'].' | ';
				//}
				//echo 'New Vendor 2022-11-11<br/>';
	
				if($vendor_invite_id == $same_vendor){
					$audit_logs = $db->query("SELECT VAR_TO
									FROM SMNTP_VENDOR_AUDIT_LOGS 
									WHERE vendor_id = ".$vendor_id." AND MODIFIED_DATE >= '".$previous_date_updated."' AND DATE(MODIFIED_DATE) <= '".$date_updated."'
									AND (MODIFIED_FIELD LIKE '%ePAF%Email%' OR MODIFIED_FIELD LIKE '%IMS%Email%')")->fetchAll();
					if(count($audit_logs) > 0){
						foreach($get_vendor_sm_systems as $vendor_email){
							if($vendor_email['EMAIL'] != 'NA' && $vendor_email['EMAIL'] != 'n/a' && $vendor_email['EMAIL'] != 'N/A' && $vendor_email['EMAIL'] != 'na'){
								echo $vendor_code;
								echo '\t'.$vendor_email['EMAIL'];
								echo '<br/>';	
							}
						}
					}
				}else{
					$previous_date_updated = '';
					
					foreach($get_vendor_sm_systems as $vendor_email){						
						if($vendor_email['EMAIL'] != 'NA' && $vendor_email['EMAIL'] != 'n/a' && $vendor_email['EMAIL'] != 'N/A' && $vendor_email['EMAIL'] != 'na'){
							echo $vendor_code;
							echo '\t'.$vendor_email['EMAIL'];
							echo '<br/>';	
						}
					}
				}
				
				$vendor_count += 1;
			}
		}
	}else{
		$previous_status = $db->query("SELECT SS.STATUS_NAME, SVSL.STATUS_ID FROM SMNTP_VENDOR_STATUS_LOGS SVSL
									   JOIN SMNTP_STATUS SS ON SVSL.STATUS_ID = SS.STATUS_ID
									   WHERE SVSL.VENDOR_INVITE_ID = ".$vendor_invite_id." AND SVSL.DATE_UPDATED <= '".$date_updated."' ORDER BY SVSL.DATE_UPDATED DESC LIMIT 1,1")->fetchAll();
		if($previous_status[0]['STATUS_ID'] != 254){
			$get_vendor_sm_systems = $db->query("SELECT DISTINCT EMAIL FROM SMNTP_VENDOR_SM_SYSTEMS WHERE SM_SYSTEM_ID IN (9,10) AND vendor_invite_id = ".$vendor_invite_id)->fetchAll();
			if(count($get_vendor_sm_systems) > 0){									   
				//echo 'Vendor Invite ID: '.$vendor_invite_id.'<br/>';
				//echo 'Date Created: '.$date_created.'<br/>';
				//echo 'Date Updated: '.$date_updated.'<br/>';
				//echo 'Current Status: '.$current_status.'<br/>';
				//echo 'Previous Status: '.$previous_status[0]['STATUS_NAME'].'<br/>';
				//echo 'Email(s): ';
				//foreach($get_vendor_sm_systems as $vendor_email){
				//	echo $vendor_email['EMAIL'].' | ';
				//}
				//echo '<br/>';
				//echo 'Old Vendor 2022-11-11<br/>';
	
				if($vendor_invite_id == $same_vendor){
					$audit_logs = $db->query("SELECT VAR_TO
									FROM SMNTP_VENDOR_AUDIT_LOGS 
									WHERE vendor_id = ".$vendor_id." AND MODIFIED_DATE >= '".$previous_date_updated."' AND DATE(MODIFIED_DATE) <= '".$date_updated."'
									AND (MODIFIED_FIELD LIKE '%ePAF%Email%' OR MODIFIED_FIELD LIKE '%IMS%Email%')")->fetchAll();
					if(count($audit_logs) > 0){
						foreach($get_vendor_sm_systems as $vendor_email){
							if($vendor_email['EMAIL'] != 'NA' && $vendor_email['EMAIL'] != 'n/a' && $vendor_email['EMAIL'] != 'N/A' && $vendor_email['EMAIL'] != 'na'){
								echo $vendor_code;
								echo '\t'.$vendor_email['EMAIL'];
								echo '<br/>';	
							}
						}
					}
				}else{
					$previous_date_updated = '';
					
					foreach($get_vendor_sm_systems as $vendor_email){
						if($vendor_email['EMAIL'] != 'NA' && $vendor_email['EMAIL'] != 'n/a' && $vendor_email['EMAIL'] != 'N/A' && $vendor_email['EMAIL'] != 'na'){
							echo $vendor_code;
							echo '\t'.$vendor_email['EMAIL'];
							echo '<br/>';	
						}
					}
				}
				
				$vendor_count += 1;
			}
		}
	}
	
	$same_vendor = $vendor_invite_id;
	$previous_date_updated = $record_set[$a]['DATE_UPDATED'];
}

//echo "<br/><br/>here".$vendor_count;

//echo("<pre>");
//print_r($record_set);
?>