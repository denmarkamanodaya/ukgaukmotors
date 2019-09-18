<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VehicleFeaturePivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_vehicle_feature_items', function(Blueprint $table)
        {
            $table->integer('vehicle_id')->unsigned()->index();
            $table->foreign('vehicle_id')->references('id')->on('vehicle')->onDelete('cascade');
            $table->integer('vehicle_feature_items_id')->unsigned()->index();
            $table->foreign('vehicle_feature_items_id')->references('id')->on('vehicle_feature_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vehicle_vehicle_feature_items');

    }
}
