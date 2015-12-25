<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 15-12-2015
 * Time: PM 11:43
 */

$app->get('/account_activate',$guest(), function () use ($app) {
    $request = $app->request;
    $email = $request->get('email');
    $identifier = $request->get('identifier');
    $hasedIdentifier = $app->hash->hash($identifier);
    $user = $app->user->where('email', $email)->where('active', false)->first();
    if (!$user || !$app->hash->hashCheck($user->active_hash, $hasedIdentifier)) {
        $app->flash('global', 'There was en error in activating your account');
        $app->response->redirect($app->urlFor('home'));
    }else{
        $user->activateAccount();
        $app->flash('global', 'Your account has been activated and you can sign in now.');
        $app->response->redirect($app->urlFor('home'));
    }
})->name('account_activate');