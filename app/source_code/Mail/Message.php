<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 05-12-2015
 * Time: PM 04:32
 */

namespace source_code\Mail;
class Message
{
    protected $mailer;

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function to($address, $name)
    {
        $this->mailer->addAddress($address, $name);
    }

    public function subject($subject)
    {
        $this->mailer->Subject = $subject;
    }

    public function body($body)
    {
        $this->mailer->Body = $body;

    }
}