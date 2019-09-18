<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VehicleEngineSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_engine_size', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_type_id')->unsigned()->index();
            $table->string('slug')->nullable();
            $table->string('size')->nullable();
            $table->integer('position')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::create('vehicle_body_type', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_type_id')->unsigned()->index();
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->integer('position')->unsigned()->default(1);
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
        Schema::drop('vehicle_body_type');
        Schema::drop('vehicle_engine_size');

    }
}
