<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->get('/',function () use ($app) {
    //echo $app->randomLib->generateString(128);
    $app->render('home.twig');
})->name('home');
