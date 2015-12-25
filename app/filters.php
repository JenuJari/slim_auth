<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 25-12-2015
 * Time: PM 03:42
 * @param $required
 * @return Closure
 */

$authenticationCheck = function ($required) use ($app) {
    return function () use ($required, $app) {
        if ((!$app->auth && $required) || ($app->auth && !$required)) {
            $app->redirect($app->urlFor('home'));
        }
    };
};

$authenticated = function () use ($authenticationCheck) {
    return $authenticationCheck(true);
};

$guest = function () use ($authenticationCheck) {
    return $authenticationCheck(false);
};