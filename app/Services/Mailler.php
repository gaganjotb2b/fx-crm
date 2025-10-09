<?php

namespace App\Services;

use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
use Latte\Engine;

class Mailler
{
    private Message $mail;
    private SmtpMailer $mailer;
    private $file_name;
    private $subject;
    private $emailAddress;
    private $pushData;
    private Engine $latte;


    function __construct($email, $subject, $file_name, $data = array())
    {
        $this->emailAddress = $email;
        $this->subject = $subject;
        $this->file_name = $file_name;
        $this->pushData = $data;
    }

    public function SendMail()
    {
        $this->mail = new Message;
        $this->latte = new Engine;
        $this->mail->setFrom('mofakharulislamcse11@gmail.com', $this->subject)
            ->addTo($this->emailAddress)
            ->setSubject($this->subject)
            ->setHtmlBody(
                $this->latte->renderToString(__DIR__ . '/../../views/user/template/emails/' . $this->file_name . '.latte.php', $this->pushData)
            );
 
        $username = "dev_rajin@arif.itcornertest.com";
        $password = "=b*Q?Fm&3vsQ";
        $this->mailer = new SmtpMailer([
            'host' => 'mail.arif.itcornertest.com',
            'port' => 465,
            'username' => $username,
            'password' => $password,
            'secure' => 'ssl',
        ]);

        $this->mailer->send($this->mail);
    }
}
