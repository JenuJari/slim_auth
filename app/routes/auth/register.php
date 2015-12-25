<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 27-11-2015
 * Time: AM 12:02.
 */
$app->get('/register',$guest(), function () use ($app) {
    $app->render('auth/register.twig');
})->name('register');

$app->post('/register',$guest(), function () use ($app) {
    $request = $app->request;
    $email = $request->post('email');
    $user_name = $request->post('user_name');
    $password = $request->post('password');
    $Confirm_password = $request->post('confirm_password');
    $v = $app->Validation;
    $v->validate([
        'email' => [$email, 'required|email|uniqueEmail'],
        'username' => [$user_name, 'required|alnumDash|max(20)'],
        'password' => [$password, 'required|min(6)'],
        'confirm_password' => [$Confirm_password, 'required|matches(password)'],
    ]);

    if ($v->passes()) {
        $identifier = $app->randomLib->generateString(128);
        $user = $app->user->create([
            'email' => $email,
            'username' => $user_name,
            'password' => $app->hash->hashPassword($password),
            'active' => false,
            'active_hash' => $app->hash->hash($identifier),
        ]);
        $app->mail->send('email/auth/register.twig', ['user' => $user, 'identifier' => $identifier], function ($message) use ($user) {
            $message->to($user->email, '');
            $message->subject('Thanks for registering');
        });
        $app->flash('global', 'You are successfully registered');
        $app->response->redirect($app->urlFor('home'));
    } else {
        $app->render('auth/register.twig', [
            'errors' => $v->errors(),
            'request' => $request,
        ]);
    }

})->name('register.post');
