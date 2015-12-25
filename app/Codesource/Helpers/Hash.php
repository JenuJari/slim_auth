<?php

namespace Codesource\Helpers;

/**
 * Description of Hash
 *
 * @author abc
 */
class Hash {

    protected $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function hashPassword($password) {
        return password_hash(
                $password, $this->config->get('app.hash.algo'), array('cost' => $this->config->get('app.hash.cost'),)
        );
    }

    public function hashPasswordCheck($password, $hash) {
        return password_verify($password, $hash);
    }

    public function hash($value)
    {
        return hash('sha256', $value);
    }

    public function hashCheck($known,$user)
    {
        return hash_equals($known,$user);
    }

}
