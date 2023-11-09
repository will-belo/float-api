<?php

namespace App\Models;

use App\Traits\UserObserver;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, UserObserver;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password', 'client_id', 'client_secret'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($attribute)
    {
        $pass = Hash::make(($attribute));

        $this->attributes['password'] = $pass;
    }

    public function setClientIdAttribute($attribute)
    {
        $this->attributes['client_id'] = 'client_id_'.$attribute;
    }

    public function setClientSecretAttribute($attribute)
    {
        $this->attributes['client_secret'] = 'client_secret_'.$attribute;
    }
}
