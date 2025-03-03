<?php
//include("db_connect.php");
//include("smtp_config.php");
//
//$db = new DB;


$str = shell_exec('df -h');
$arr = explode(PHP_EOL, $str); 
$arr = array_chunk($arr,5); 

echo "<pre>";
//print_r($arr);
echo $arr[1][0];
echo "<br/>";
print_r(explode("  ",$arr[1][0]));
?>