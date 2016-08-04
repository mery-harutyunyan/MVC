<?php

class Mailer
{
    protected $fromEmail;
    protected $fromSite;

    public function __construct()
    {
        $this->fromEmail = WEBSITE_MAIL;
        $this->fromSite = WEBSITE_TITLE;
    }

    /**
     * get mail content
     */
    public function getMailHtml($file, $data)
    {


        ob_start();                      // start capturing output
        include(ROOT . DS . 'app' . DS . 'views' . DS . 'mails' . DS . $file . '.php');   // execute the file
        $html = ob_get_contents();    // get the contents from the buffer
        ob_end_clean();

        // var_dump($html);die;
        return $html;

    }

    public function sendVerificationEmail($data)
    {

        $toName = $data['first_name'] . ' ' . $data['last_name'];
        $toEmail = $data['email'];
        $subject = 'Account verification';
        $message = $this->getMailHtml('email_verification', $data);

        $headers = 'To: ' . $toName . ' <' . $toEmail . '>' . "\r\n";
        $headers .= 'From: ' . $this->fromSite . ' <' . $this->fromEmail . '>' . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($toEmail, $subject, $message, $headers);
    }

    /**
     * send Password Change Email
     */
    public function sendPasswordChangeEmail($data)
    {
        $toName = $data['first_name'] . ' ' . $data['last_name'];
        $toEmail = $data['email'];
        $subject = 'Change password';
        $message = $this->getMailHtml('change_password', $data);

        $headers = 'To: ' . $toName . ' <' . $toEmail . '>' . "\r\n";
        $headers .= 'From: ' . $this->fromSite . ' <' . $this->fromEmail . '>' . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($toEmail, $subject, $message, $headers);
    }
}