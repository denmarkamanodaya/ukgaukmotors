<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GarageFeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garage_feed', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('title')->nullable();
            $table->string('search')->nullable();
            $table->string('auctioneer')->nullable();
            $table->string('location')->nullable();
            $table->string('vehicleMake')->nullable();
            $table->string('vehicleModel')->nullable();
            $table->string('auctionDay')->nullable();
            $table->integer('position')->unsigned()->default(1);
            $table->boolean('notify')->default(0);
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
        Schema::drop('garage_feed');
    }
}
