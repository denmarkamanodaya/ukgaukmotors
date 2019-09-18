<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VehicleMatchResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_match_results', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned()->nullable()->index();
            $table->string('title');
            $table->string('vehicle_make')->nullable();
            $table->string('vehicle_model')->nullable();
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
        Schema::drop('vehicle_match_results');
    }
}
