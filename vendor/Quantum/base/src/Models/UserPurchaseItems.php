<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UserPurchaseItems.php
 **/

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class UserPurchaseItems extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['user_purchase_id', 'item_id', 'name', 'quantity', 'price', 'model_id', 'model'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_purchase_items';

    public function user_purchase()
    {
        return $this->belongsTo('Quantum\base\Models\UserPurchase');
    }

}