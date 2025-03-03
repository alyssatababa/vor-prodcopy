<?php

/*$html_brand = "http://10.13.163.42/dev/index.php/vendor/check_status/cron_check"; //dev*/
//$html_brand = "http://10.13.165.38/index.php/vendor/check_status/cron_check";	//prod
//$html_brand = "http://10.143.0.146/API/index.php/vendor/check_status/cron_check";	//prod
$html_brand = "http://api.smvendoronlineregistration.com/index.php/vendor/check_status/cron_check";	//prod

$ch = curl_init();

$options = array(
	CURLOPT_URL            => $html_brand,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HEADER         => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING       => "",
	CURLOPT_AUTOREFERER    => true,
	CURLOPT_CONNECTTIMEOUT => 120,
	CURLOPT_TIMEOUT        => 120,
	CURLOPT_MAXREDIRS      => 10,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_USERPWD => 'webserver:sssi-smntp-webserver',
	CURLOPT_CUSTOMREQUEST => "PUT",
);
curl_setopt_array( $ch, $options );
$response = curl_exec($ch); 
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ( $httpCode != 200 ){
	echo "Return code is {$httpCode} \n"
		.curl_error($ch);
} else {
	echo "<pre>".htmlspecialchars($response)."</pre>";
}

curl_close($ch);

?>


