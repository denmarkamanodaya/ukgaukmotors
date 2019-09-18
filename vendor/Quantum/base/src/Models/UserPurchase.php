<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UserPurchase.php
 **/

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class UserPurchase extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'guest_key', 'subtotal', 'total', 'subscription', 'subscription_period_amount', 'subscription_period_type', 'status'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_purchase';

    public function items()
    {
        return $this->hasMany('Quantum\base\Models\UserPurchaseItems');
    }

    public function transactions()
    {
        return $this->hasMany('Quantum\base\Models\Transactions');
    }

}