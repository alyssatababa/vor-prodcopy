<?php
include("db_connect.php");
include("smtp_config.php");

$db = new DB;

if(!file_exists( '/var/www/html/WEB/automation/storage/outbound/'.date('Y').'/'.date('m').'/'.date('d').'/' ) ){
	if(!mkdir('/var/www/html/WEB/automation/storage/outbound/'.date('Y').'/'.date('m').'/'.date('d').'/', 0777, true)){
		die('Failed to create directory');
	}
}

$filename = date('Y').date('m').date('d')."_VendorSap.csv";
$file = fopen('/var/www/html/WEB/automation/storage/outbound/'.date('Y').'/'.date('m').'/'.date('d').'/'.$filename, 'w');

$record_set = $db->query('
						SELECT "VENDOR_CODE", "VENDOR_NAME"
						UNION
						SELECT SV.VENDOR_CODE, SV.VENDOR_NAME 
						FROM SMNTP_VENDOR_INVITE SVI 
						JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID 
						JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID 
						WHERE SVS.STATUS_ID = 19 AND SVI.REGISTRATION_TYPE IN (2,3) AND DATE(SVS.DATE_UPDATED) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)'
						)->fetchAll();
$total_records = count($record_set);

for($a=0; $a<$total_records; $a++){
	fputcsv($file, (array) $record_set[$a]);
}

$msg_footer = "This is an automated notification for VOR Migrated Vendors that are still not complying.<br/><br/><b><i>***This is an automated notification. Please do not reply.***</i></b><br/>";
$mail->SetFrom('smvendoronlineregistration@smretail.com', 'SM Vendor Online Registration');
$mail->Subject = "VOR - List of Vendor for SAPFICO";
$mail->MsgHTML("Please see attached file.<br/><br/>" .$msg_footer);
$mail->AddAddress("markjoseph.s.francisco@smretail.com", "");
$mail->AddAddress("Leane.A.Malubag@smretail.com", "");
$mail->AddAddress("Rosaly.B.Abapo@smretail.com", "");
$mail->addAttachment('/var/www/html/WEB/automation/storage/outbound/'.date('Y').'/'.date('m').'/'.date('d').'/'.$filename);
try {
	$mail->Send();
	echo "Email Sent Successfully.\n";
} catch (phpmailerException $e) {
	echo $e->errorMessage();
}
?>