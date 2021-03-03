<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'mobile', 'username', 'role_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return HasOne
     */
    public function role()
    {
        return $this->hasOne(Role::class);
    }

    /**
     * @return HasOne
     */
    public function token()
    {
        return $this->hasOne(Token::class);
    }

    /**
     * @return HasOne
     */
    public function otp()
    {
        return $this->hasOne(Otp::class);
    }

    /**
     * @return HasOne
     */
    public function bloodInformation()
    {
        return $this->hasOne(BloodInformation::class);
    }

    /**
     * @return HasOne
     */
    public function donor()
    {
        return $this->hasOne(Donor::class);
    }
}
