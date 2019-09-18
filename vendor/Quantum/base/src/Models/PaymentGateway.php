<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : PaymentGateway.php
 **/

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'description', 'status', 'default', 'subscription_button_image', 'payment_button_image'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_gateway';
    
    public function transactions()
    {
        return $this->hasMany('Quantum\base\Models\Transactions');
    }
    

}