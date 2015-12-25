<?php

/**
 * Created by PhpStorm.
 * User: abc
 * Date: 24-Nov-15
 * Time: 11:35 AM.
 */

use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Noodlehaus\Config;
use Codesource\User\User;
use Codesource\Helpers\Hash;
use \Codesource\Validation\Validator;
use Codesource\Mail\Mailer;
use Codesource\Middleware\CsrfMiddleware;
use Codesource\Middleware\BeforeMiddleware;
use \PHPMailer\PHPMailer\PHPMailer;
use RandomLib\Factory as RandomLib;

session_cache_limiter(false);
session_start();

ini_set('display_errors', 'On');

define('INC_ROOT', dirname(__DIR__));

require_once INC_ROOT.'/vendor/autoload.php';

$app = new Slim(array(
        'mode' => file_get_contents(INC_ROOT.'/mode'),
        'view' => new Twig(),
        'templates.path' => INC_ROOT.'/app/views',
    )
);

$app->configureMode($app->config('mode'), function () use ($app) {
    $app->config = Config::load(INC_ROOT."/app/config/{$app->config('mode')}.php");
});

require_once 'database.php';
require_once 'routes.php';
$app->auth = false;
$app->add(new BeforeMiddleware());
$app->add(new CsrfMiddleware());
$app->container->set('user', function () {
    return new User();
});
$app->container->singleton('hash', function () use ($app) {
    return new Hash($app->config);
});
$app->container->singleton('Validation', function () use ($app) {
    return new Validator($app->user);
});
$app->container->singleton('mail', function () use ($app) {
    $mailer = new PHPMailer();
    $mailer->isSMTP();
    $mailer->Host = $app->config->get('mail.host');
    $mailer->SMTPAuth = $app->config->get('mail.smtp_auth');
    $mailer->SMTPSecure = $app->config->get('mail.smtp_secure');
    $mailer->Port = $app->config->get('mail.port');
    $mailer->Username = $app->config->get('mail.username');
    $mailer->Password = $app->config->get('mail.password');
    $mailer->setFrom($app->config->get('mail.username'), $app->config->get('mail.displayname'));
    $mailer->isHTML($app->config->get('mail.html'));

    return new Mailer($app->view, $mailer);
});
$app->container->singleton('randomLib',function()  {
    $factory= new RandomLib();
    return $factory->getMediumStrengthGenerator();
});
$view = $app->view();
$view->parserOptions = array('debug' => $app->config->get('twig.debug'));
$view->parserExtensions = array(new TwigExtension());
