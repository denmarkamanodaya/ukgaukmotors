<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'user_membership';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'membership_types_id', 'expires', 'expires_on', 'status', 'user_purchase_id'];

    protected $dates = ['expires_on'];

    public function membership()
    {
        return $this->belongsTo(\Quantum\base\Models\MembershipTypes::class, 'membership_types_id');
    }
    
    public function user()
    {
        return $this->hasOne(\Quantum\base\Models\User::class, 'user_id');
    }

    public function subscription()
    {
        return $this->hasOne(\Quantum\base\Models\Transactions::class, 'user_purchase_id', 'user_purchase_id')->where('type', 'agreement');
    }

}
