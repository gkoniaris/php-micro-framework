<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    protected $mail;
    
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setSmtpConfiguration();
    }

    private function setSmtpConfiguration()
    {
        global $settings;

        $this->mail->SMTPDebug = 0;
        $this->mail->isSMTP();
        $this->mail->Host = $settings['MAIL']['HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $settings['MAIL']['USERNAME'];
        $this->mail->Password = $settings['MAIL']['PASSWORD'];
        $this->mail->SMTPSecure = "tls";
        $this->mail->Port = $settings['MAIL']['PORT'];
    }

    public function from($email, $name)
    {
        if (!$email || !$name) throw new \Exception('Please provide a valid email and name for the sender');

        $this->mail->From = $email;
        $this->mail->FromName = $name;

        return $this;
    }

    public function to($email, $name)
    {
        if (!$email) throw new \Exception('Please provide a valid email and name for the recipient');
        
        $this->mail->addAddress($email, $name);

        return $this;
    }

    public function subject($text)
    {
        $this->mail->Subject = $text;

        return $this;
    }

    public function view($view)
    {
        $this->mail->isHTML(true);

        return $this;
    }

    public function text($text)
    {
        $this->mail->isHTML(false);
        $this->mail->Body = $text;

        return $this;
    }

    public function altText($text)
    {
        $this->mail->AltBody = $text;

        return $this;
    }

    public function send()
    {
        try {
            $this->mail->send();
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}