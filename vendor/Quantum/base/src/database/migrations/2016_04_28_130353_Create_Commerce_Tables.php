<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommerceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->boolean('default')->default(0);
            $table->string('payment_button_image')->nullable();
            $table->string('subscription_button_image')->nullable();
            $table->timestamps();
        });

        Schema::create('user_purchase', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('guest_key')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->boolean('expires')->default(0);
            $table->boolean('subscription')->default(0);
            $table->string('subscription_period_amount')->nullable();
            $table->enum('subscription_period_type', ['Days', 'Weeks', 'Months', 'Years'])->default('Months')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active')->nullable();
            $table->timestamps();
        });

        Schema::create('user_purchase_items', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_purchase_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->string('name');
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 10, 2);
            $table->integer('model_id')->unsigned();
            $table->string('model');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_purchase_id')->unsigned();
            $table->integer('payment_gateway_id')->unsigned();
            $table->enum('type', ['sale', 'agreement'])->default('sale');
            $table->string('trx_id');
            $table->string('agreement_id');
            $table->decimal('amount', 10, 2)->default('0.00');
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->decimal('discount_amount', 10, 2);
            $table->integer('discount_percent')->unsigned();
            $table->string('key')->unique();
            $table->integer('usage_amount')->unsigned();
            $table->dateTime('expires_on')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->timestamps();
        });

        Schema::create('coupons_user_purchase', function (Blueprint $table) {
            $table->integer('coupons_id')->unsigned()->index();
            $table->foreign('coupons_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->integer('user_purchase_id')->unsigned()->index();
            $table->foreign('user_purchase_id')->references('id')->on('user_purchase')->onDelete('cascade');
        });

        Schema::create('pp_billing_plans', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('pp_plan_id')->index();
            $table->integer('membership_types_id')->unsigned()->index();
            $table->integer('coupons_id')->unsigned()->index();
            $table->decimal('amount', 10, 2);
            $table->string('subscription_period_amount');
            $table->enum('subscription_period_type', ['Days', 'Weeks', 'Months', 'Years'])->default('Months')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pp_billing_plans');
        Schema::drop('coupons_user_purchase');
        Schema::drop('coupons');
        Schema::drop('transactions');
        Schema::drop('user_purchase_items');
        Schema::drop('user_purchase');
        Schema::drop('payment_gateway');
    }
}
