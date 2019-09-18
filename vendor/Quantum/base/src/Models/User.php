<?php

namespace Quantum\base\Models;


use Quantum\base\Traits\AclUser;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, AclUser, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password', 'status', 'email_confirmed', 'email_code', 'last_login', 'last_login_ip', 'previous_login_date', 'registered_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $with = ['profile'];

    protected $dates = ['last_login', 'previous_login_date'];

    public function profile()
    {
        return $this->hasOne('Quantum\base\Models\Profile');
    }

    public function roles()
    {
        return $this->belongsToMany('Quantum\base\Models\Role');
    }

    public function membership()
    {
        return $this->hasMany('Quantum\base\Models\UserMembership');
    }

    public function purchase()
    {
        return $this->hasMany('Quantum\base\Models\UserPurchase');
    }

    public function notifications()
    {
        return $this->hasMany('Quantum\base\Models\UserNotifications');
    }

    /**
     * Check media all access
     *
     * @return bool
     */
    public function accessMediasAll()
    {
        return $this->hasRole('admin');
    }
    /**
     * Check media access one folder
     *
     * @return bool
     */
    public function accessMediasFolder()
    {
        return $this->hasRole('member');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($user){
        $user->email_code = str_random(30);
    });
    }

}
