<?php

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

//Sending Email from Local Web Server using PHPMailer			
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';


//Create a new PHPMailer instance
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';

// Log that we're starting to handle the email request
error_log("Starting to handle email request from contact form");

$isSmtp = true;
if ($isSmtp) {
    require 'phpmailer/src/SMTP.php';

    //Enable SMTP debugging
    $mail->SMTPDebug = SMTP::DEBUG_OFF;

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Set the hostname of the mail server
    $mail->Host = 'smtp.improvmx.com';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication
    $mail->Username = 'contacted@egorbobrov.com';
    //Password to use for SMTP authentication
    $mail->Password = 'baUfMQDE6hQ0';
    //Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //Set timeout in seconds
    $mail->Timeout = 30;
}

// Form Fields Value Variables
$name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$subject = filter_var($_POST['subject'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$message = filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$message = nl2br($message);

//Use a fixed address in your own domain as the from address
$mail->setFrom('contacted@egorbobrov.com', 'My Website Notification');

//Set who the message is to be sent to
$mail->addAddress('richcarter.tech@gmail.com');

$mail->addReplyTo($email, $name);

//Send HTML or Plain Text email
$mail->isHTML(true);

// Message Body
$body_message = "Subject: " . $subject . "<br>";
$body_message .= "Name: " . $name . "<br>";
$body_message .= "Email: " . $email . "<br><br>";
$body_message .= "\n\n" . $message;

//Set the subject & Body Text
$mail->Subject = "New Message from $name";
$mail->Body = $body_message;

if(!$mail->send()) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} else {
    echo 'Message sent!';
}
