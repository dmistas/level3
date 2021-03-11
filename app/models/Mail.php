<?php

namespace App\models;
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail
{
    private $mail;
    public function __construct(PHPMailer $mail)//Create a new PHPMailer instance
    {
        $this->mail = $mail;
        /**
         *
         * MAIL_MAILER=smtp
         * MAIL_HOST=smtp.mailtrap.io
         * MAIL_PORT=2525
         * MAIL_USERNAME=f607a1fee8c128
         * MAIL_PASSWORD=8f20cc3778129a
         * MAIL_ENCRYPTION=tls
         */

//Tell PHPMailer to use SMTP
        $this->mail->isSMTP();
//Enable SMTP debugging
//SMTP::DEBUG_OFF = off (for production use)
//SMTP::DEBUG_CLIENT = client messages
//SMTP::DEBUG_SERVER = client and server messages
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
//Set the hostname of the mail server
        $this->mail->Host = 'smtp.mailtrap.io';
//Set the SMTP port number - likely to be 25, 465 or 587
        $this->mail->Port = 2525;
//Whether to use SMTP authentication
        $this->mail->SMTPAuth = true;
//Username to use for SMTP authentication
        $this->mail->Username = 'f607a1fee8c128';
//Password to use for SMTP authentication
        $this->mail->Password = '8f20cc3778129a';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//Set who the message is to be sent from
        $this->mail->setFrom('from@example.com', 'First Last');
//Set an alternative reply-to address
    }

    public function sendVerificationEmail($whoToEmail, $whoToName, $selector, $token)
    {


        $this->mail->addAddress($whoToEmail, $whoToName);
//Set the subject line
        $this->mail->isHTML(true);                                  //Set email format to HTML
        $this->mail->Subject = 'Please verify your email adress';
        $this->mail->Body    = "this link for verify email <a href=\"http://site.test/verification?selector={$selector}&token={$token}\">verify email</a>";
        $this->mail->AltBody = "http://site.test/verification?selector={$selector}&token={$token}";


//send the message, check for errors
        if (!$this->mail->send()) {
            return 'Mailer Error: ' . $this->mail->ErrorInfo;
        } else {
            return 'Message sent!';
        }
    }
}