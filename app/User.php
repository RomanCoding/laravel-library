<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'business_name',
        'business_address', 'email', 'password',
        'access_level', 'suburb', 'state',
        'phone', 'website', 'network_visible',
        'logo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Max access level in system
    const MAX_LEVEL = 3;
    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getLogoAttribute($value)
    {
        return $value ? '/images/logos/' . $value : null;
    }
    
    public function scopeVisible($query)
    {
        return $query->where('access_level', '>=', 2)->where('network_visible', 1);
    }

    public function isAdmin()
    {
        return $this->access_level === 3;
    }
}
