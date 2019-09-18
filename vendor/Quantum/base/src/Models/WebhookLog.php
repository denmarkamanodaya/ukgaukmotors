<?php

namespace Quantum\base\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = array(
        'webhookid', 'event_version', 'resource_type', 'event_type', 'summary', 'resource_id', 'resource_state', 'resource_amount_total',
        'resource_amount_currency', 'resource_payment_mode', 'resource_protection_eligibility', 'resource_protection_eligibility_type',
        'resource_transaction_fee_value', 'resource_transaction_fee_currency', 'resource_invoice_number', 'resource_custom',
        'resource_parent_payment', 'resource_billing_agreement_id', 'full_json'
    );
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'webhook_log';
}
