<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dealers', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->string('town')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('auction_url')->nullable();
            $table->string('online_bidding_url')->nullable();
            $table->text('details')->nullable();
            $table->string('buyers_premium')->nullable();
            $table->text('directions')->nullable();
            $table->text('rail_station')->nullable();
            $table->text('notes')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->enum('type', ['auctioneer', 'dealer'])->default('auctioneer')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::create('dealer_media', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dealer_id')->unsigned()->index();
            $table->string('name')->nullable();
            $table->enum('type', ['image', 'video'])->default('image')->index();
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
        Schema::drop('dealers');
        Schema::drop('dealer_media');
    }
}
