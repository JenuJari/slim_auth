<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 28-11-2015
 * Time: AM 12:49
 */

namespace Codesource\Middleware;

use Codesource\User\User;
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

        $this->checkRememberMe();
        $this->app->view->appendData([
            'auth' => $this->app->auth,
            'baseUrl' => $this->app->config->get('app.url')
        ]);

    }

    protected function checkRememberMe()
    {
        if (!$this->app->auth && $data = $this->app->getCookie($this->app->config->get('auth.remember'))) {
            $credentials = explode('___', $data);
            if (empty(trim($data)) || count($credentials) !== 2) {
                $this->app->response->redirect($this->app->urlFor('home'));
            } else {
                $identifier = $credentials[0];
                $hashedToken = $this->app->hash->hash($credentials[1]);

                /** @var User $user */
                $user = $this->app->user
                    ->where('remember_indetifier', $identifier)
                    ->first();

                if (!!$user) {
                    if ($this->app->hash->hashCheck($hashedToken, $user->remember_token)) {
                        $_SESSION[$this->app->config->get('auth.session')] = $user->Id;
                        $this->app->auth = $this->app->user->where('id', $user->Id)->first();
                    } else {
                        $user->removeRememberToken();
                    }
                }

            }
        }

    }
}