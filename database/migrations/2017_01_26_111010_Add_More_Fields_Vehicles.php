<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('vehicle', 'registration'))
        {
            Schema::table('vehicle', function ($table) {
                $table->string('registration')->nullable();
                $table->string('mot')->nullable();
                $table->string('estimate')->nullable();
                $table->string('co2')->nullable();
                $table->string('type')->nullable();
                $table->string('service_history')->nullable();
                $table->text('additional_info')->nullable();
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
