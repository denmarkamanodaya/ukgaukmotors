<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleDescriptionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_make_description', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_make_id')->unsigned()->index();
            $table->mediumText('content')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_model_description', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_model_id')->unsigned()->index();
            $table->mediumText('content')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_model_variant_description', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_model_variant_id')->unsigned()->index();
            $table->mediumText('content')->nullable();
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
        Schema::drop('vehicle_model_variant_description');
        Schema::drop('vehicle_model_description');
        Schema::drop('vehicle_make_description');
    }
}
