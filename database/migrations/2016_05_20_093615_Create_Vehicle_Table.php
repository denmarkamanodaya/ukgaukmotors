<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_make', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('slug');
            $table->string('logo');
            $table->boolean('system');
            $table->timestamps();
        });

        Schema::create('vehicle_model', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_make_id')->unsigned()->index();
            $table->string('name');
            $table->string('slug');
            $table->boolean('system');
            $table->timestamps();
        });

        Schema::create('vehicle_type', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->boolean('system');
            $table->timestamps();
        });
        
        
        Schema::create('vehicle', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('dealer_id')->unsigned()->index();
            $table->text('url')->nullable();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('mileage')->nullable();
            $table->string('colour')->nullable()->index();
            $table->enum('gearbox', ['manual', 'automatic', 'semi-automatic','unlisted'])->default('unlisted')->index();
            $table->enum('fuel', ['petrol', 'diesel', 'electric', 'hybrid', 'lpg','unlisted'])->default('unlisted')->index();
            $table->string('engine_size')->nullable()->index();
            $table->integer('vehicle_make_id')->unsigned()->index();
            $table->integer('vehicle_model_id')->unsigned()->index();
            $table->integer('vehicle_type_id')->unsigned()->index();
            $table->dateTime('auction_date')->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });

        Schema::create('vehicle_media', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned()->index();
            $table->string('name');
            $table->enum('type', ['image', 'video'])->default('image')->index();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
        });

        Schema::create('media_queue', function(Blueprint $table)
        {
            $table->increments('id');
            $table->enum('type', ['image', 'video'])->default('image')->index();
            $table->text('process_media')->nullable();
            $table->integer('model_id')->unsigned();
            $table->string('model');
            $table->string('remote_path');
            $table->timestamps();
        });

        Artisan::call('db:seed', array('--class' => 'VehicleSeeder'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media_queue');
        Schema::drop('vehicle_type');
        Schema::drop('vehicle_make');
        Schema::drop('vehicle_model');
        Schema::drop('vehicle');
        Schema::drop('vehicle_media');
    }
}
