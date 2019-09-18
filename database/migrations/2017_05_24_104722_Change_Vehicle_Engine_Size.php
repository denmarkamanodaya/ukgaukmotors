<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeVehicleEngineSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('vehicle', 'vehicle_engine_size_id'))
        {
            Schema::table('vehicle', function ($table) {
                $table->integer('vehicle_engine_size_id')->unsigned()->nullable();
            });
            Schema::table('vehicle', function ($table) {
                $table->integer('vehicle_body_type_id')->unsigned()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
