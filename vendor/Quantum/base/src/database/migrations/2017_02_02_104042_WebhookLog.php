<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebhookLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhook_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('webhookid')->nullable();
            $table->string('event_version')->nullable();
            $table->string('resource_type')->nullable();
            $table->string('event_type')->nullable();
            $table->string('summary')->nullable();
            $table->string('resource_id')->nullable();
            $table->string('resource_state')->nullable();
            $table->decimal('resource_amount_total', 9, 2)->nullable();
            $table->string('resource_amount_currency')->nullable();
            $table->string('resource_payment_mode')->nullable();
            $table->string('resource_protection_eligibility')->nullable();
            $table->string('resource_protection_eligibility_type')->nullable();
            $table->decimal('resource_transaction_fee_value', 9, 2)->nullable();
            $table->string('resource_transaction_fee_currency')->nullable();
            $table->string('resource_invoice_number')->nullable();
            $table->string('resource_custom')->nullable();
            $table->string('resource_parent_payment')->nullable();
            $table->string('resource_billing_agreement_id')->nullable();
            $table->text('full_json');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('webhook_log');
    }
}
