<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 28-11-2015
 * Time: AM 12:19.
 */
$app->get('/login',$guest(), function () use ($app) {
    $app->render('auth/login.twig');
})->name('login');

$app->post('/login',$guest(), function () use ($app) {
    $request = $app->request;
    $identifier = $request->post('identifier');
    $password = $request->post('password');
    $v = $app->Validation;
    $v->validate([
        'identifier' => [$identifier, 'required'],
        'password' => [$password, 'required'],
    ]);

    if ($v->passes()) {
        $u = $app->user
            ->where('username', $identifier)
            ->orwhere('email', $identifier)
            ->where('active', true)
            ->first();
        if ($u && $app->hash->hashPasswordCheck($password, $u->password)) {
            $_SESSION[$app->config->get('auth.session')] = $u->Id;
            $app->flash('global', 'You are now logged in!');
        } else {
            $app->flash('global', 'Could not log you in!');
        }
        $app->response->redirect($app->urlFor('home'));
    }
    $app->render('auth/login.twig', [
        'errors' => $v->errors(),
        'request' => $request,
    ]);
})->name('login.post');

$app->get('/logout',$authenticated(), function () use ($app) {
    session_unset();
    $app->response->redirect($app->urlFor('home'));
})->name('log_out');
