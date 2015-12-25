<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 28-11-2015
 * Time: AM 12:49
 */

namespace Codesource\Middleware;

use Slim\Middleware;

class BeforeMiddleware extends Middleware
{

    /**
     * Call
     *
     * Perform actions specific to this middleware and optionally
     * call the next downstream middleware.
     */
    public function call()
    {
        $this->app->hook('slim.before', [$this, 'run']);
        $this->next->call();
    }

    public function run()
    {
        if (isset($_SESSION[$this->app->config->get('auth.session')]))
            $this->app->auth = $this->app->user->where('id', $_SESSION[$this->app->config->get('auth.session')])->first();

        $this->app->view->appendData([
            'auth' => $this->app->auth,
            'baseUrl' => $this->app->config->get('app.url')
        ]);
    }
}