<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 28-11-2015
 * Time: AM 12:19.
 */
$app->get('/login', $guest(), function () use ($app) {
    $app->render('auth/login.twig');
})->name('login');

$app->post('/login', $guest(), function () use ($app) {
    $request = $app->request;
    $identifier = $request->post('identifier');
    $password = $request->post('password');
    $remember = $request->post('remember');

    $v = $app->Validation;
    $v->validate(array('identifier' => array($identifier, 'required'),
        'password' => array($password, 'required')));

    if ($v->passes()) {
        $u = $app->user
            ->where('username', $identifier)
            ->orwhere('email', $identifier)
            ->where('active', true)
            ->first();
        if ($u && $app->hash->hashPasswordCheck($password, $u->password)) {
            $_SESSION[$app->config->get('auth.session')] = $u->Id;

            if ($remember == 'on') {
                $rememberIdentifier = $app->randomLib->generateString(128);
                $rememberToken = $app->randomLib->generateString(128);
                $u->updateRememberMeToken($rememberIdentifier, $app->hash->hash($rememberToken));
                $app->setCookie($app->config->get('auth.remember'),
                    "{$rememberIdentifier}___{$rememberToken}",
                    \Carbon\Carbon::parse('+1 week')->timestamp);

            }

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

$app->get('/logout', $authenticated(), function () use ($app) {
    unset($_SESSION[$app->config->get('auth.session')]);
    if ($app->getCookie($app->config->get('auth.remember'))) {
        $app->auth->removeRememberToken();
        $app->deleteCookie($app->config->get('auth.remember'));
    }
    $app->flash('global', 'You are now logged out!');
    $app->response->redirect($app->urlFor('home'));
})->name('log_out');
