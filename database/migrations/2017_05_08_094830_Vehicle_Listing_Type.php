<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VehicleListingType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_listing_type', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->boolean('system');
            $table->timestamps();
        });

        if(!Schema::hasColumn('vehicle', 'vehicle_listing_type_id'))
        {
            Schema::table('vehicle', function ($table) {
                $table->integer('vehicle_listing_type')->unsigned()->default(1)->index();
                $table->integer('user_id')->unsigned()->nullable()->index();
            });
        }

        Schema::create('vehicle_features', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->boolean('system');
            $table->integer('position');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_feature_items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('vehicle_features_id')->unsigned()->index();
            $table->string('name');
            $table->string('slug');
            $table->boolean('system');
            $table->integer('position');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('user_extras', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('item_name');
            $table->string('amount');
            $table->boolean('expires');
            $table->dateTime('expire_date')->nullable();
        });


        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vehicle_listing_type');
        Schema::drop('vehicle_features');
        Schema::drop('vehicle_feature_items');
        Schema::drop('user_extras');
    }
}
