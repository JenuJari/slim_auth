<?php
/**
 * Created by PhpStorm.
 * User: Jenish
 * Date: 27-11-2015
 * Time: AM 12:38
 */

namespace source_code\Validation;

use Violin\Violin;
use Codesource\User\User;

class Validator extends Violin
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->addFieldMessages([
            'email' => [
                'uniqueEmail' => "This email is already in use."
            ],
            'username'=> [
                'uniqueUsername' => "This user name is already in use."
            ]
        ]);
    }

    public function validate_uniqueEmail($value, $input, $args)
    {
        $count = $this->user->where('email', $value)->count();
        return !(bool)$count;
    }

    public function validate_uniqueUsername($value, $input, $args)
    {
        $count = $this->user->where('username', $value)->count();
        return !(bool)$count;
    }
}