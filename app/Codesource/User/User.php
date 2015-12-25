<?php

namespace Codesource\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of User.
 *
 * @property string first_name
 * @property string last_name
 * @property string username
 *
 * @author abc
 */
class User extends Model
{
    //put your code here

    protected $table = 'users';
    Protected $primaryKey = "Id";
    protected $fillable = array(
        'username', 'first_name', 'last_name', 'password', 'email', 'active', 'active_hash', 'recover_hash', 'remember_indetifier', 'remember_token',
    );

    public function getFullName()
    {
        if (!$this->first_name || !$this->last_name) {
            return null;
        }

        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullNameOrUserName()
    {
        return $this->getFullName() ?: $this->username;
    }

    public function activateAccount()
    {
        $this->active = true;
        $this->active_hash = null;
        $this->save();
    }
}
