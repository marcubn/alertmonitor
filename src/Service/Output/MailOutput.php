<?php

namespace AlertMonitor\Service\Output;


class MailOutput implements OutputInterface
{
    private $mailer;

    private $from;

    private $to;

    const SUBJECT = "Something bad happened";

    public function __construct($host, $port, $user, $pass, $from, $to)
    {
        $transport = \Swift_SmtpTransport::newInstance($host, $port)->setUsername($user)->setPassword($pass);
        $this->mailer = \Swift_Mailer::newInstance($transport);
        $this->from = $from;
        $this->to = $to;
    }

    public function processResults($data)
    {
        $message = \Swift_Message::newInstance()
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setSubject(self::SUBJECT)
            ->setBody(implode("\n", $data));

        $this->mailer->send($message);
    }
}