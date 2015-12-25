<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 25-12-2015
 * Time: PM 04:05
 */

namespace Codesource\Middleware;

use Slim\Middleware;

class CsrfMiddleware extends Middleware
{

    protected $key;

    /**
     * Call
     *
     * Perform actions specific to this middleware and optionally
     * call the next downstream middleware.
     */
    public function call()
    {
        $this->key = $this->app->config->get('csrf.key');
        $this->app->hook('slim.before', [$this, 'check']);
        $this->next->call();
    }

    public function check()
    {
        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = $this->app->hash->hash(
                $this->app->randomLib->generateString(128)
            );
        }

        $token = $_SESSION[$this->key];
        if (in_array($this->app->request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $submittedToken = $this->app->request->post($this->key) ?: '';
            if (!$this->app->hash->hashCheck($submittedToken, $token)) {
                throw new \Exception('CSRF token mismatch');
            }
        }

        $this->app->view->appendData([
            'csrf_key' => $this->key,
            'csrf_token' => $token
        ]);
    }

}