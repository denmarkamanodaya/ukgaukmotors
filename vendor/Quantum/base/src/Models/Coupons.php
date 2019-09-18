<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Coupons.php
 **/

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['discount_amount', 'discount_percent', 'key', 'usage_amount', 'expires_on', 'status'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons';
    
    protected $dates = ['expires_on'];

    public function user_purchase()
    {
        return $this->hasMany('Quantum\base\Models\UserPurchase');
    }

}