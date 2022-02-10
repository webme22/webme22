<?php
include_once(__DIR__.'/../settings.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require __DIR__.'/../vendor/autoload.php';

class Mailer extends PHPMailer
{
    private $vars;
    function __construct()
    {
        PHPMailer::__construct();
        global $MAIL_HOST, $MAIL_DRIVER,$MAIL_PORT,$MAIL_USERNAME,$MAIL_PASSWORD,$MAIL_ENCRYPTION, $MAIL_FROM_ADDRESS, $MAIL_FROM_NAME;

        //$this->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->isSMTP();                                            //Send using SMTP
        $this->Host       = $MAIL_HOST;                     //Set the SMTP server to send through
        $this->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->Username   = $MAIL_USERNAME;                     //SMTP username
        $this->Password   = $MAIL_PASSWORD;                               //SMTP password
        $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->Port       = $MAIL_PORT;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $this->isHTML(true);                                  //Set email format to HTML

        //Recipients
        $this->setFrom($MAIL_FROM_ADDRESS, $MAIL_FROM_NAME);
    }
    public function sendMail($to, $subject, $body, $alt_body){
        try {
            $this->addAddress($to[0]);
            foreach (array_slice($to, 1) as $recipient){
                $this->addCC($recipient);
            }
            $this->Subject = $subject;
            $this->Body    = $this->view(__DIR__.'/../resources/views/mail/'.$body);
            $this->AltBody = $this->view(__DIR__.'/../resources/views/mail/'.$alt_body);;
            $this->send();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
    private function view($file){
        foreach($this->vars as $key => $value){
            $$key = $value;
        }
        ob_start();
        require($file);
        return ob_get_clean();
    }
    public function setVars($vars){
        $this->vars = $vars;
    }
}
