<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Transactions.php
 **/

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['user_purchase_id', 'payment_gateway_id', 'trx_id', 'type', 'agreement_id', 'amount', 'user_id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    public function user_purchase()
    {
        return $this->belongsTo('Quantum\base\Models\UserPurchase');
    }

    public function user()
    {
        return $this->belongsTo('Quantum\base\Models\User');
    }

    public function payment_gateway()
    {
        return $this->belongsTo('Quantum\base\Models\PaymentGateway');
    }

}