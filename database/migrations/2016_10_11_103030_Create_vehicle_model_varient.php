<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreateVehicleModelVarient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_model_varient', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_model_id')->unsigned()->index();
            $table->string('model_platform')->nullable();
            $table->string('model_name')->nullable();
            $table->string('model_desc')->nullable();
            $table->string('source')->nullable();
            $table->string('year_sold')->nullable();
            $table->string('location')->nullable();
            $table->string('classification')->nullable();
            $table->string('body_type')->nullable();
            $table->string('doors')->nullable();
            $table->string('seats')->nullable();
            $table->string('engine_place')->nullable();
            $table->string('drivetrain')->nullable();
            $table->string('cylinders')->nullable();
            $table->string('displacement')->nullable();
            $table->string('power_ps')->nullable();
            $table->string('power_kw')->nullable();
            $table->string('power_rpm')->nullable();
            $table->string('torque_nm')->nullable();
            $table->string('torque_rpm')->nullable();
            $table->string('bore_stroke')->nullable();
            $table->string('compression_ration')->nullable();
            $table->string('valves_cylinder')->nullable();
            $table->string('crankshaft')->nullable();
            $table->string('fuel_injection')->nullable();
            $table->string('supercharged')->nullable();
            $table->string('catalytic')->nullable();
            $table->string('manual')->nullable();
            $table->string('automatic')->nullable();
            $table->string('suspension_front')->nullable();
            $table->string('suspension_rear')->nullable();
            $table->string('assisted_steering')->nullable();
            $table->string('brakes_front')->nullable();
            $table->string('brakes_rear')->nullable();
            $table->string('abs')->nullable();
            $table->string('esp')->nullable();
            $table->string('tire_size')->nullable();
            $table->string('tire_size_rear')->nullable();
            $table->string('wheel_base')->nullable();
            $table->string('track_front')->nullable();
            $table->string('track_rear')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('curb_weight')->nullable();
            $table->string('gross_weight')->nullable();
            $table->string('cargo_space')->nullable();
            $table->string('tow_weight')->nullable();
            $table->string('gas_tank')->nullable();
            $table->string('zero_hundred')->nullable();
            $table->string('max_speed')->nullable();
            $table->string('fuel_eff')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('co2')->nullable();
            $table->boolean('system')->default(0);
            $table->boolean('default')->default(0);


            $table->timestamps();
        });

        Artisan::call('db:seed', array('--class' => 'Vehicle_Model_Varient_Setup'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vehicle_model_varient');

    }
}
