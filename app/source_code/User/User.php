<?php

namespace source_code\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of User.
 *
 * @property string first_name
 * @property string last_name
 * @property string username
 * @property string email
 * @property string remember_token
 * @property int Id
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

    public function getAvatarUrl($options = array())
    {
        $email = $this->email;
        $size = isset($options['size']) ? $options['size'] : 45;
        return 'http://www.gravatar.com/avatar/' . md5($email) . '?s=' . $size . '&d=identicon';
    }

    public function updateRememberMeToken($identifier, $hashedToken)
    {
        $this->update(array(
            "remember_indetifier" => $identifier,
            "remember_token" => $hashedToken
        ));

    }

    public function removeRememberToken()
    {
        $this->update(array(
            "remember_indetifier" => null,
            "remember_token" => null
        ));
    }
}
