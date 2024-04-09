<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
try {
    require __DIR__ . "../vendor/autoload.php";

    //$mail = new PHPMailer(true);
//
    //$mail->isSMTP();
    //$mail->SMTPAuth = true;
    //$mail->Host = "smtp.gmail.com";
    //$mail->SMTPSecure = 'ssl';
    //$mail->Port = 465;
    //$mail->Username = "fitpill.services@gmail.com";
    //$mail->Password = "wmrdcfpjstqtmqdi";
    //$mail->isHtml(true);

    return $mail;
} catch(Exception $e){
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}