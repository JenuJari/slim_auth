<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 05-12-2015
 * Time: PM 04:21
 */

namespace Codesource\Mail;

class Mailer
{

    protected $mailer;
    protected $view;

    public function __construct($view, $mailer)
    {
        $this->view = $view;
        $this->mailer = $mailer;
    }

    public function send($template, $data, $callback)
    {
        $message = new Message($this->mailer);
        $this->view->appendData($data);
        $message->body($this->view->render($template));
        call_user_func($callback, $message);
       try{
           $this->mailer->send();
       }catch (\Exception $e){
           var_dump($e);die;
       }
    }
}