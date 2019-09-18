<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemoteToVehicleMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('vehicle_media', 'remote_type'))
        {
            Schema::table('vehicle_media', function ($table) {
                $table->integer('remote_type')->unsigned()->default(1);
                $table->integer('remote_lot')->unsigned()->nullable();
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
