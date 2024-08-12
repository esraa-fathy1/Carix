<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../mail/src/Exception.php';
require '../mail/src/PHPMailer.php';
require '../mail/src/SMTP.php';
require '../connection.php';


$mail = new PHPMailer();
$mail->IsSMTP(); 
// $mail->SMTPDebug = 2; //detailes about error   
$mail->SMTPAuth   = true;                      //Set the SMTP server to send through   
$mail->SMTPSecure = 'tls';                                      //Enable implicit TLS encryption                                   
$mail->Host       = 'smtp.gmail.com';
$mail->Port       = 587;
$mail->IsHTML(true);                                  //Set email format to HTML
$mail->Username   = 'carix2024@gmail.com';                     //SMTP username //*****
$mail->Password   = 'qrtassntrgqsnkoz';                          //SMTP password



$mail ->CharSet ="UTF-8";

?>