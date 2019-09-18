<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : BillingPlan.php
 **/

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class BillingPlan extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['pp_plan_id', 'membership_types_id', 'coupons_id', 'amount', 'subscription_period_amount', 'subscription_period_type', 'membership_update'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pp_billing_plans';



}