<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection(array(
    'driver' => $app->config->get('db.driver'),
    'host' => $app->config->get('db.host'),
    'database' => $app->config->get('db.name'),
    'username' => $app->config->get('db.username'),
    'password' => $app->config->get('db.password'),
    'charset' => $app->config->get('db.charset'),
    'collation' => $app->config->get('db.collation'),
    'prefix' => $app->config->get('db.prefix'),
));

$capsule->bootEloquent();
