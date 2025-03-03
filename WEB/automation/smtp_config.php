<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once('PHPMailer/src/PHPMailer.php');
require_once('PHPMailer/src/SMTP.php');
require_once('PHPMailer/src/Exception.php');

$mail = new PHPMailer();
// SEND MAIL by PHP MAILER
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
$mail->isSMTP(); // Use SMTP protocol
$mail->Host = 'email-smtp.ap-southeast-1.amazonaws.com'; // Specify  SMTP server
$mail->SMTPAuth = true; // Auth. SMTP
$mail->Username = 'AKIAZEFUQG3C5GTQFO57'; // Mail who send by PHPMailer
$mail->Password = 'BAldEqWosAHiZ8VgN5EBzg1R0jF37sorjySei/zjZrdH'; // your pass mail box
$mail->Port = 587; // port of your out server
$mail->isHTML(true); // use HTML message
?>