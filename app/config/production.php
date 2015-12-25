<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'app' => array(
        'url' => "http://localhost/",
        'hash' => array(
            "algo" => PASSWORD_BCRYPT,
            "cost" => 10
        ),
    ),
    'db' => array(
        'driver' => "mysql",
        'host' => "127.0.0.1",
        'name' => "site",
        'username' => "root",
        'password' => "",
        'charset' => "utf8",
        'collation' => "utf8_unicode_ci",
        'prefix' => ""
    ),
    'auth' => array(
        'session' => "user_id",
        'remember' => "user_r"
    ),
    'mail' => array(
        'smtp_auth' => TRUE,
        'smtp_secure' => "tls",
        'host' => "smtp.gmail.com",
        'username' => "jenishjariwala54@gmail.com",
        'password' => "",
        'port' => 587,
        'html' => true
    ),
    'twig' => array(
        'debuge' => true,
    ),
    'csrf' => array(
        'session' => "csrf_token",
    ),
);
