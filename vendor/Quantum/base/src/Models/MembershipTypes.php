<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTypes extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'membership_types';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'title', 'description', 'type', 'subscription', 'amount', 'subscription_period_amount', 'subscription_period_type',
     'expired_remove_roles', 'expired_change_status', 'expired_change_status_to', 'emails_id', 'page_after_registration', 'members_page_after_payment',
        'guest_page_after_payment', 'allow_user_signups', 'display_in_collections', 'position', 'register_default', 'status', 'expires', 'summary', 'email_validate', 'login_after_register', 'expire_email_id', 'tenant'];

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->tenant = config('app.name');
        });
    }

    public function roles()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class, 'membership_t_r');
    }

    public function rolesToRemove()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class, 'membership_t_r_r');
    }

    public function rolesToAdd()
    {
        return $this->belongsToMany(\Quantum\base\Models\Role::class, 'membership_t_r_a');
    }

    public function users()
    {
        return $this->hasMany(\Quantum\base\Models\UserMembership::class);
    }

    public function scopeTenant($query)
    {
        return $query->where('tenant', config('app.name'));
    }

}
